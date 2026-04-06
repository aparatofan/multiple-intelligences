<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

global $wpdb;
$table_name = $wpdb->prefix . 'mi_survey_results';
$wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );

delete_option( 'mi_survey_gdpr_text_en' );
delete_option( 'mi_survey_gdpr_text_pl' );
delete_option( 'mi_survey_db_version' );
