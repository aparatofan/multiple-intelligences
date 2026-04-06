<?php
/**
 * Plugin Name:       MI Survey
 * Plugin URI:        https://polecanynauczycielangielskiego.pl
 * Description:       Bilingual (Polish/English) Multiple Intelligences survey based on Howard Gardner's theory.
 * Version:           1.0.0
 * Author:            PNA
 * Author URI:        https://polecanynauczycielangielskiego.pl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mi-survey
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'MI_SURVEY_VERSION', '1.0.0' );
define( 'MI_SURVEY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MI_SURVEY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MI_SURVEY_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

require_once MI_SURVEY_PLUGIN_DIR . 'includes/class-mi-survey-loader.php';
require_once MI_SURVEY_PLUGIN_DIR . 'includes/class-mi-survey-i18n.php';
require_once MI_SURVEY_PLUGIN_DIR . 'includes/class-mi-survey-activator.php';
require_once MI_SURVEY_PLUGIN_DIR . 'includes/class-mi-survey-deactivator.php';
require_once MI_SURVEY_PLUGIN_DIR . 'includes/class-mi-survey-questions.php';
require_once MI_SURVEY_PLUGIN_DIR . 'includes/class-mi-survey-scoring.php';
require_once MI_SURVEY_PLUGIN_DIR . 'includes/class-mi-survey-descriptions.php';
require_once MI_SURVEY_PLUGIN_DIR . 'includes/class-mi-survey-email.php';
require_once MI_SURVEY_PLUGIN_DIR . 'admin/class-mi-survey-admin.php';
require_once MI_SURVEY_PLUGIN_DIR . 'public/class-mi-survey-public.php';

register_activation_hook( __FILE__, array( 'MI_Survey_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'MI_Survey_Deactivator', 'deactivate' ) );

/**
 * Main plugin class.
 */
final class MI_Survey {

    private static $instance = null;
    private $loader;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->loader = new MI_Survey_Loader();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->loader->run();
    }

    private function set_locale() {
        $i18n = new MI_Survey_I18n();
        $this->loader->add_action( 'plugins_loaded', $i18n, 'load_plugin_textdomain' );
    }

    private function define_admin_hooks() {
        $admin = new MI_Survey_Admin();
        $this->loader->add_action( 'admin_menu', $admin, 'add_admin_menu' );
        $this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_scripts' );
        $this->loader->add_action( 'wp_ajax_mi_survey_export_csv', $admin, 'export_csv' );
        $this->loader->add_action( 'wp_ajax_mi_survey_delete_result', $admin, 'delete_result' );
    }

    private function define_public_hooks() {
        $public = new MI_Survey_Public();
        $this->loader->add_action( 'wp_enqueue_scripts', $public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $public, 'enqueue_scripts' );
        $this->loader->add_action( 'wp_ajax_mi_survey_submit', $public, 'handle_submit' );
        $this->loader->add_action( 'wp_ajax_nopriv_mi_survey_submit', $public, 'handle_submit' );
        $this->loader->add_action( 'wp_ajax_mi_survey_send_email', $public, 'handle_send_email' );
        $this->loader->add_action( 'wp_ajax_nopriv_mi_survey_send_email', $public, 'handle_send_email' );
        add_shortcode( 'mi_survey', array( $public, 'render_shortcode' ) );
    }
}

add_action( 'plugins_loaded', array( 'MI_Survey', 'instance' ) );
