<?php
class MI_Survey_Admin {

    public function enqueue_styles( $hook ) {
        if ( false === strpos( $hook, 'mi-survey' ) ) {
            return;
        }
        wp_enqueue_style( 'mi-survey-admin', MI_SURVEY_PLUGIN_URL . 'admin/css/mi-survey-admin.css', array(), MI_SURVEY_VERSION );
    }

    public function enqueue_scripts( $hook ) {
        if ( false === strpos( $hook, 'mi-survey' ) ) {
            return;
        }
        wp_enqueue_script(
            'chartjs',
            'https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js',
            array(),
            '4.4.0',
            true
        );
        wp_enqueue_script( 'mi-survey-admin', MI_SURVEY_PLUGIN_URL . 'admin/js/mi-survey-admin.js', array( 'jquery', 'chartjs' ), MI_SURVEY_VERSION, true );
        wp_localize_script( 'mi-survey-admin', 'miSurveyAdmin', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'mi_survey_admin_nonce' ),
        ) );
    }

    public function add_admin_menu() {
        add_menu_page(
            __( 'MI Survey', 'mi-survey' ),
            __( 'MI Survey', 'mi-survey' ),
            'manage_options',
            'mi-survey',
            array( $this, 'render_results_page' ),
            'dashicons-chart-bar',
            30
        );
        add_submenu_page(
            'mi-survey',
            __( 'Results', 'mi-survey' ),
            __( 'Results', 'mi-survey' ),
            'manage_options',
            'mi-survey',
            array( $this, 'render_results_page' )
        );
        add_submenu_page(
            'mi-survey',
            __( 'Settings', 'mi-survey' ),
            __( 'Settings', 'mi-survey' ),
            'manage_options',
            'mi-survey-settings',
            array( $this, 'render_settings_page' )
        );
    }

    public function render_results_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mi_survey_results';

        // Detail view.
        if ( isset( $_GET['view'] ) && is_numeric( $_GET['view'] ) ) {
            $id     = absint( $_GET['view'] );
            $result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE id = %d", $id ) );

            if ( ! $result ) {
                echo '<div class="wrap"><h1>' . esc_html__( 'Result Not Found', 'mi-survey' ) . '</h1></div>';
                return;
            }

            $scores  = json_decode( $result->scores, true );
            $answers = json_decode( $result->answers, true );
            $labels  = MI_Survey_Questions::get_type_labels( $result->language );
            $sorted  = MI_Survey_Scoring::sort_by_score( $scores );

            echo '<div class="wrap mi-survey-admin">';
            echo '<h1>' . esc_html__( 'Survey Result Detail', 'mi-survey' ) . '</h1>';
            echo '<p><a href="' . esc_url( admin_url( 'admin.php?page=mi-survey' ) ) . '">&larr; ' . esc_html__( 'Back to Results', 'mi-survey' ) . '</a></p>';
            echo '<div class="mi-admin-detail">';
            echo '<table class="widefat"><tbody>';
            echo '<tr><th>' . esc_html__( 'Name', 'mi-survey' ) . '</th><td>' . esc_html( $result->user_name ) . '</td></tr>';
            echo '<tr><th>' . esc_html__( 'Email', 'mi-survey' ) . '</th><td>' . esc_html( $result->user_email ) . '</td></tr>';
            echo '<tr><th>' . esc_html__( 'Language', 'mi-survey' ) . '</th><td>' . esc_html( strtoupper( $result->language ) ) . '</td></tr>';
            echo '<tr><th>' . esc_html__( 'Date', 'mi-survey' ) . '</th><td>' . esc_html( $result->created_at ) . '</td></tr>';
            echo '<tr><th>' . esc_html__( 'Items Checked', 'mi-survey' ) . '</th><td>' . ( is_array( $answers ) ? count( $answers ) : 0 ) . '</td></tr>';
            echo '</tbody></table>';

            echo '<h3>' . esc_html__( 'Scores', 'mi-survey' ) . '</h3>';
            echo '<div class="mi-admin-chart-wrap"><canvas id="mi-admin-chart" width="600" height="300"></canvas></div>';
            echo '<script>document.addEventListener("DOMContentLoaded",function(){';
            echo 'var labels=' . wp_json_encode( array_values( array_map( function( $t ) use ( $labels ) { return $labels[ $t ]; }, array_keys( $sorted ) ) ) ) . ';';
            echo 'var data=' . wp_json_encode( array_values( $sorted ) ) . ';';
            echo 'if(typeof Chart!=="undefined"){new Chart(document.getElementById("mi-admin-chart"),{type:"bar",data:{labels:labels,datasets:[{label:"Score",data:data,backgroundColor:"#e8a317"}]},options:{indexAxis:"y",responsive:true,scales:{x:{min:0,max:10}},plugins:{legend:{display:false}}}});}';
            echo '});</script>';

            echo '<h3>' . esc_html__( 'Checked Answers', 'mi-survey' ) . '</h3>';
            echo '<p>' . ( is_array( $answers ) ? esc_html( implode( ', ', $answers ) ) : '—' ) . '</p>';
            echo '</div></div>';
            return;
        }

        // Results list.
        $orderby_allowed = array( 'user_name', 'user_email', 'language', 'created_at' );
        $orderby         = isset( $_GET['orderby'] ) && in_array( $_GET['orderby'], $orderby_allowed, true ) ? sanitize_sql_orderby( $_GET['orderby'] ) : 'created_at';
        $order           = isset( $_GET['order'] ) && 'asc' === strtolower( $_GET['order'] ) ? 'ASC' : 'DESC';
        $results         = $wpdb->get_results( "SELECT * FROM {$table_name} ORDER BY {$orderby} {$order}" );

        echo '<div class="wrap mi-survey-admin">';
        echo '<h1>' . esc_html__( 'MI Survey Results', 'mi-survey' ) . '</h1>';
        echo '<p>';
        echo '<a href="' . esc_url( wp_nonce_url( admin_url( 'admin-ajax.php?action=mi_survey_export_csv' ), 'mi_survey_admin_nonce', '_wpnonce' ) ) . '" class="button button-secondary">' . esc_html__( 'Export CSV', 'mi-survey' ) . '</a>';
        echo '</p>';

        if ( empty( $results ) ) {
            echo '<p>' . esc_html__( 'No results yet.', 'mi-survey' ) . '</p>';
        } else {
            $base_url = admin_url( 'admin.php?page=mi-survey' );
            echo '<table class="widefat striped mi-admin-results-table">';
            echo '<thead><tr>';
            echo '<th><a href="' . esc_url( add_query_arg( array( 'orderby' => 'user_name', 'order' => 'asc' ), $base_url ) ) . '">' . esc_html__( 'Name', 'mi-survey' ) . '</a></th>';
            echo '<th><a href="' . esc_url( add_query_arg( array( 'orderby' => 'user_email', 'order' => 'asc' ), $base_url ) ) . '">' . esc_html__( 'Email', 'mi-survey' ) . '</a></th>';
            echo '<th><a href="' . esc_url( add_query_arg( array( 'orderby' => 'language', 'order' => 'asc' ), $base_url ) ) . '">' . esc_html__( 'Language', 'mi-survey' ) . '</a></th>';
            echo '<th>' . esc_html__( 'Top 3 Intelligences', 'mi-survey' ) . '</th>';
            echo '<th><a href="' . esc_url( add_query_arg( array( 'orderby' => 'created_at', 'order' => 'desc' ), $base_url ) ) . '">' . esc_html__( 'Date', 'mi-survey' ) . '</a></th>';
            echo '<th>' . esc_html__( 'Actions', 'mi-survey' ) . '</th>';
            echo '</tr></thead><tbody>';

            foreach ( $results as $row ) {
                $scores = json_decode( $row->scores, true );
                $labels = MI_Survey_Questions::get_type_labels( $row->language );
                $top3   = '';
                if ( is_array( $scores ) ) {
                    arsort( $scores );
                    $i = 0;
                    foreach ( $scores as $type => $score ) {
                        if ( $i >= 3 ) break;
                        $top3 .= ( $top3 ? ', ' : '' ) . $labels[ $type ] . ' (' . $score . ')';
                        $i++;
                    }
                }
                $view_url   = add_query_arg( array( 'view' => $row->id ), $base_url );
                $delete_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=mi_survey_delete_result&id=' . $row->id ), 'mi_survey_admin_nonce', '_wpnonce' );

                echo '<tr>';
                echo '<td>' . esc_html( $row->user_name ) . '</td>';
                echo '<td>' . esc_html( $row->user_email ) . '</td>';
                echo '<td>' . esc_html( strtoupper( $row->language ) ) . '</td>';
                echo '<td>' . esc_html( $top3 ) . '</td>';
                echo '<td>' . esc_html( $row->created_at ) . '</td>';
                echo '<td><a href="' . esc_url( $view_url ) . '">' . esc_html__( 'View', 'mi-survey' ) . '</a>';
                echo ' | <a href="' . esc_url( $delete_url ) . '" class="mi-delete-result" onclick="return confirm(\'' . esc_attr__( 'Delete this result?', 'mi-survey' ) . '\')">' . esc_html__( 'Delete', 'mi-survey' ) . '</a>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
        }
        echo '</div>';
    }

    public function render_settings_page() {
        if ( isset( $_POST['mi_survey_save_settings'] ) && check_admin_referer( 'mi_survey_settings_nonce' ) ) {
            update_option( 'mi_survey_gdpr_text_en', sanitize_textarea_field( wp_unslash( $_POST['gdpr_text_en'] ) ) );
            update_option( 'mi_survey_gdpr_text_pl', sanitize_textarea_field( wp_unslash( $_POST['gdpr_text_pl'] ) ) );
            echo '<div class="updated"><p>' . esc_html__( 'Settings saved.', 'mi-survey' ) . '</p></div>';
        }

        $gdpr_en = get_option( 'mi_survey_gdpr_text_en', '' );
        $gdpr_pl = get_option( 'mi_survey_gdpr_text_pl', '' );

        echo '<div class="wrap mi-survey-admin">';
        echo '<h1>' . esc_html__( 'MI Survey Settings', 'mi-survey' ) . '</h1>';
        echo '<form method="post">';
        wp_nonce_field( 'mi_survey_settings_nonce' );
        echo '<table class="form-table">';
        echo '<tr><th><label for="gdpr_text_en">' . esc_html__( 'GDPR Consent Text (EN)', 'mi-survey' ) . '</label></th>';
        echo '<td><textarea id="gdpr_text_en" name="gdpr_text_en" rows="4" class="large-text">' . esc_textarea( $gdpr_en ) . '</textarea></td></tr>';
        echo '<tr><th><label for="gdpr_text_pl">' . esc_html__( 'GDPR Consent Text (PL)', 'mi-survey' ) . '</label></th>';
        echo '<td><textarea id="gdpr_text_pl" name="gdpr_text_pl" rows="4" class="large-text">' . esc_textarea( $gdpr_pl ) . '</textarea></td></tr>';
        echo '</table>';
        echo '<p class="submit"><input type="submit" name="mi_survey_save_settings" class="button button-primary" value="' . esc_attr__( 'Save Settings', 'mi-survey' ) . '"></p>';
        echo '</form></div>';
    }

    public function export_csv() {
        if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'mi_survey_admin_nonce' ) ) {
            wp_die( 'Unauthorized' );
        }

        global $wpdb;
        $table   = $wpdb->prefix . 'mi_survey_results';
        $results = $wpdb->get_results( "SELECT * FROM {$table} ORDER BY created_at DESC" );
        $labels  = MI_Survey_Questions::get_type_labels( 'en' );

        header( 'Content-Type: text/csv; charset=UTF-8' );
        header( 'Content-Disposition: attachment; filename=mi-survey-results-' . gmdate( 'Y-m-d' ) . '.csv' );
        header( 'Pragma: no-cache' );
        header( 'Expires: 0' );

        $output = fopen( 'php://output', 'w' );
        fprintf( $output, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) ); // UTF-8 BOM.

        $header = array( 'ID', 'Name', 'Email', 'Language', 'Date' );
        foreach ( $labels as $label ) {
            $header[] = $label;
        }
        fputcsv( $output, $header );

        foreach ( $results as $row ) {
            $scores = json_decode( $row->scores, true );
            $line   = array(
                $row->id,
                $row->user_name,
                $row->user_email,
                $row->language,
                $row->created_at,
            );
            foreach ( array_keys( $labels ) as $type ) {
                $line[] = isset( $scores[ $type ] ) ? $scores[ $type ] : 0;
            }
            fputcsv( $output, $line );
        }

        fclose( $output );
        exit;
    }

    public function delete_result() {
        if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'mi_survey_admin_nonce' ) ) {
            wp_die( 'Unauthorized' );
        }

        $id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
        if ( $id > 0 ) {
            global $wpdb;
            $table = $wpdb->prefix . 'mi_survey_results';
            $wpdb->delete( $table, array( 'id' => $id ), array( '%d' ) );
        }

        wp_safe_redirect( admin_url( 'admin.php?page=mi-survey' ) );
        exit;
    }
}
