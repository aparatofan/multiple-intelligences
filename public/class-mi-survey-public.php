<?php
class MI_Survey_Public {

    public function enqueue_styles() {
        if ( ! $this->has_shortcode() ) {
            return;
        }
        wp_enqueue_style( 'mi-survey-public', MI_SURVEY_PLUGIN_URL . 'public/css/mi-survey-public.css', array(), MI_SURVEY_VERSION );
    }

    public function enqueue_scripts() {
        if ( ! $this->has_shortcode() ) {
            return;
        }
        wp_enqueue_script(
            'chartjs',
            'https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js',
            array(),
            '4.4.0',
            true
        );
        wp_enqueue_script(
            'html2canvas',
            'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js',
            array(),
            '1.4.1',
            true
        );
        wp_enqueue_script(
            'jspdf',
            'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js',
            array(),
            '2.5.1',
            true
        );
        wp_enqueue_script(
            'mi-survey-public',
            MI_SURVEY_PLUGIN_URL . 'public/js/mi-survey-public.js',
            array( 'jquery', 'chartjs', 'html2canvas', 'jspdf' ),
            MI_SURVEY_VERSION,
            true
        );

        $lang = 'pl';
        wp_localize_script( 'mi-survey-public', 'miSurvey', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'mi_survey_nonce' ),
            'i18n'    => array(
                'errorRequired'    => __( 'Please fill in all required fields.', 'mi-survey' ),
                'errorConsent'     => __( 'Please accept the consent checkbox.', 'mi-survey' ),
                'errorSubmit'      => __( 'An error occurred. Please try again.', 'mi-survey' ),
                'emailSent'        => __( 'Report sent to your email!', 'mi-survey' ),
                'emailError'       => __( 'Failed to send email. Please try again.', 'mi-survey' ),
                'checked'          => __( 'checked', 'mi-survey' ),
                'dynamicNoteEn'    => 'The MI profile is dynamic. It changes over time.',
                'dynamicNotePl'    => 'Profil MI jest dynamiczny. Zmienia się w czasie.',
                'surveyDateEn'     => 'Survey date',
                'surveyDatePl'     => 'Data ankiety',
                'downloadPdf'      => __( 'Download PDF', 'mi-survey' ),
                'sendEmail'        => __( 'Send to email', 'mi-survey' ),
                'generatingPdf'    => __( 'Generating PDF...', 'mi-survey' ),
            ),
        ) );
    }

    private function has_shortcode() {
        global $post;
        return is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'mi_survey' );
    }

    public function render_shortcode( $atts ) {
        ob_start();
        include MI_SURVEY_PLUGIN_DIR . 'public/partials/mi-survey-form.php';
        include MI_SURVEY_PLUGIN_DIR . 'public/partials/mi-survey-report.php';
        return ob_get_clean();
    }

    public function handle_submit() {
        check_ajax_referer( 'mi_survey_nonce', 'nonce' );

        $name     = isset( $_POST['user_name'] ) ? sanitize_text_field( wp_unslash( $_POST['user_name'] ) ) : '';
        $email    = isset( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '';
        $lang     = isset( $_POST['language'] ) && 'en' === $_POST['language'] ? 'en' : 'pl';
        $answers  = isset( $_POST['answers'] ) && is_array( $_POST['answers'] ) ? array_map( 'sanitize_text_field', $_POST['answers'] ) : array();
        $consent  = isset( $_POST['consent'] ) && '1' === $_POST['consent'] ? 1 : 0;

        if ( empty( $name ) || empty( $email ) || ! is_email( $email ) || ! $consent ) {
            wp_send_json_error( array( 'message' => __( 'Invalid input.', 'mi-survey' ) ) );
        }

        $scores = MI_Survey_Scoring::calculate( $answers );

        global $wpdb;
        $table = $wpdb->prefix . 'mi_survey_results';
        $wpdb->insert(
            $table,
            array(
                'user_name'     => $name,
                'user_email'    => $email,
                'language'      => $lang,
                'answers'       => wp_json_encode( $answers ),
                'scores'        => wp_json_encode( $scores ),
                'created_at'    => current_time( 'mysql' ),
                'consent_given' => $consent,
            ),
            array( '%s', '%s', '%s', '%s', '%s', '%s', '%d' )
        );

        $labels       = MI_Survey_Questions::get_type_labels( $lang );
        $sorted       = MI_Survey_Scoring::sort_by_score( $scores );
        $descriptions = array();
        foreach ( $sorted as $type => $score ) {
            $descriptions[ $type ] = MI_Survey_Descriptions::get_description( $type, $lang );
        }

        $icon_urls = array();
        foreach ( array_keys( $sorted ) as $type ) {
            $icon_urls[ $type ] = MI_Survey_Questions::get_icon_url( $type, 'svg' );
        }

        wp_send_json_success( array(
            'scores'       => $sorted,
            'labels'       => $labels,
            'descriptions' => $descriptions,
            'iconUrls'     => $icon_urls,
            'date'         => current_time( 'Y-m-d' ),
            'language'     => $lang,
            'userName'     => $name,
            'userEmail'    => $email,
        ) );
    }

    public function handle_send_email() {
        check_ajax_referer( 'mi_survey_nonce', 'nonce' );

        $name   = isset( $_POST['user_name'] ) ? sanitize_text_field( wp_unslash( $_POST['user_name'] ) ) : '';
        $email  = isset( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '';
        $lang   = isset( $_POST['language'] ) && 'en' === $_POST['language'] ? 'en' : 'pl';
        $scores = isset( $_POST['scores'] ) ? json_decode( stripslashes( $_POST['scores'] ), true ) : array();
        $date   = isset( $_POST['date'] ) ? sanitize_text_field( wp_unslash( $_POST['date'] ) ) : current_time( 'Y-m-d' );

        if ( empty( $email ) || ! is_email( $email ) || empty( $scores ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid input.', 'mi-survey' ) ) );
        }

        $sent = MI_Survey_Email::send_report( $email, $name, $scores, $lang, $date );

        if ( $sent ) {
            wp_send_json_success();
        } else {
            wp_send_json_error( array( 'message' => __( 'Failed to send email.', 'mi-survey' ) ) );
        }
    }
}
