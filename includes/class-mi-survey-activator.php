<?php
class MI_Survey_Activator {

    public static function activate() {
        self::create_table();
        self::set_default_options();
    }

    private static function create_table() {
        global $wpdb;
        $table_name      = $wpdb->prefix . 'mi_survey_results';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table_name} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_name VARCHAR(255) NOT NULL,
            user_email VARCHAR(255) NOT NULL,
            language VARCHAR(2) NOT NULL DEFAULT 'pl',
            answers TEXT NOT NULL,
            scores TEXT NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            consent_given TINYINT(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (id),
            KEY idx_email (user_email),
            KEY idx_created (created_at)
        ) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );

        update_option( 'mi_survey_db_version', '1.0.0' );
    }

    private static function set_default_options() {
        $default_en = 'I consent to the processing of my personal data (name and email) for the purpose of this survey. Your data will be stored securely and will not be shared with third parties.';
        $default_pl = 'Wyrażam zgodę na przetwarzanie moich danych osobowych (imię i adres email) w celu przeprowadzenia tej ankiety. Twoje dane będą przechowywane w sposób bezpieczny i nie będą udostępniane osobom trzecim.';

        if ( false === get_option( 'mi_survey_gdpr_text_en' ) ) {
            add_option( 'mi_survey_gdpr_text_en', $default_en );
        }
        if ( false === get_option( 'mi_survey_gdpr_text_pl' ) ) {
            add_option( 'mi_survey_gdpr_text_pl', $default_pl );
        }
    }
}
