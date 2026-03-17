<?php
/**
 * Plugin Name: HFE Properties
 * Plugin URI: https://homesforexpats.nl
 * Description: Custom properties management system for Homes for Expats with carousel display and detailed property pages
 * Version: 1.0.0
 * Author: Homes for Expats
 * Author URI: https://homesforexpats.nl
 * Text Domain: hfe-properties
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('HFE_PROPERTIES_VERSION', '1.0.4');
define('HFE_PROPERTIES_PATH', plugin_dir_path(__FILE__));
define('HFE_PROPERTIES_URL', plugin_dir_url(__FILE__));

/**
 * Main HFE Properties Class
 */
class HFE_Properties {

    /**
     * Instance of this class
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
        $this->include_files();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }

    /**
     * Include required files
     */
    private function include_files() {
        require_once HFE_PROPERTIES_PATH . 'includes/post-types.php';
        require_once HFE_PROPERTIES_PATH . 'includes/meta-boxes.php';
        require_once HFE_PROPERTIES_PATH . 'includes/shortcodes.php';
        require_once HFE_PROPERTIES_PATH . 'includes/template-functions.php';
        require_once HFE_PROPERTIES_PATH . 'includes/installer.php';
        require_once HFE_PROPERTIES_PATH . 'includes/settings.php';
        require_once HFE_PROPERTIES_PATH . 'includes/page-updater.php';
        require_once HFE_PROPERTIES_PATH . 'includes/banner-injector.php';
        require_once HFE_PROPERTIES_PATH . 'includes/smart-parser.php';
        require_once HFE_PROPERTIES_PATH . 'includes/property-filters.php';
    }

    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain('hfe-properties', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        // Swiper CSS
        wp_enqueue_style(
            'swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
            array(),
            '11.0.0'
        );

        // Plugin CSS
        wp_enqueue_style(
            'hfe-properties',
            HFE_PROPERTIES_URL . 'assets/css/hfe-properties.css',
            array('swiper'),
            HFE_PROPERTIES_VERSION
        );

        // Swiper JS
        wp_enqueue_script(
            'swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            array(),
            '11.0.0',
            true
        );

        // Plugin JS
        wp_enqueue_script(
            'hfe-properties',
            HFE_PROPERTIES_URL . 'assets/js/hfe-properties.js',
            array('jquery', 'swiper'),
            HFE_PROPERTIES_VERSION,
            true
        );
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts($hook) {
        global $post_type;

        if ('hfe_property' === $post_type) {
            wp_enqueue_media();

            wp_enqueue_style(
                'hfe-properties-admin',
                HFE_PROPERTIES_URL . 'assets/css/admin.css',
                array(),
                HFE_PROPERTIES_VERSION
            );

            wp_enqueue_script(
                'hfe-properties-admin',
                HFE_PROPERTIES_URL . 'assets/js/admin.js',
                array('jquery'),
                HFE_PROPERTIES_VERSION,
                true
            );
        }
    }
}

// Initialize the plugin
function hfe_properties() {
    return HFE_Properties::get_instance();
}

hfe_properties();

/**
 * Plugin Activation Hook
 */
function hfe_properties_activate() {
    // Register post types and taxonomies first
    require_once HFE_PROPERTIES_PATH . 'includes/post-types.php';
    hfe_register_property_post_type();
    hfe_register_property_type_taxonomy();
    hfe_register_property_location_taxonomy();

    // Flush rewrite rules
    flush_rewrite_rules();

    // Run installer
    require_once HFE_PROPERTIES_PATH . 'includes/installer.php';
    HFE_Properties_Installer::install();
}
register_activation_hook(__FILE__, 'hfe_properties_activate');

/**
 * Plugin Deactivation Hook
 */
function hfe_properties_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'hfe_properties_deactivate');
