<?php

class WCMp_Product_Import_Export_Bundle {

    public $plugin_url;
    public $plugin_path;
    public $version;
    public $token;
    public $text_domain;
    public $library;
    public $admin;
    public $frontend;
    public $template;
    public $shortcode;
    public $ajax;
    private $file;
    public $settings;
    public $license;
    public $wcmp_wp_fields;
    public $export;
    public $import;
    public $import_variation;
    public $wcmp_export_meta_fields;
    public $wcmp_import_meta_fields;

    public function __construct($file) {
        $this->file = $file;
        $this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
        $this->plugin_path = trailingslashit(dirname($file));
        $this->token = WCMP_PRODUCT_IMPORT_EXPORT_BUNDLE_PLUGIN_TOKEN;
        $this->text_domain = WCMP_PRODUCT_IMPORT_EXPORT_BUNDLE_TEXT_DOMAIN;
        $this->version = WCMP_PRODUCT_IMPORT_EXPORT_BUNDLE_PLUGIN_VERSION;
        add_action('init', array(&$this, 'init'));
        add_action('init', array($this, 'export_request'), 20);
        add_action('init', array($this, 'load_admin_scripts_styles'));
        add_action('admin_init', array($this, 'wcmp_product_importers'));
        if (!is_admin()) {
            add_filter('body_class', array($this, 'add_woocommerce_class'));
        }
    }

    /**
     * initilize plugin on WP init
     */
    function init() {
        global $WCMp;
        // Init Text Domain

        $this->load_plugin_textdomain();
//        $this->load_class('full-width-template');
//        $this->wcmp_importexport_full_width_tenmplate = new WCMp_Product_Import_Export_Bundle_PageTemplater();
        // Init library
        $this->load_class('library');
        $this->library = new WCMp_Product_Import_Export_Bundle_Library();

        // Init ajax
        if (defined('DOING_AJAX')) {
            $this->load_class('ajax');
            $this->ajax = new WCMp_Product_Import_Export_Bundle_Ajax();
        }

        if (is_admin()) {
            $this->load_class('admin');
            $this->admin = new WCMp_Product_Import_Export_Bundle_Admin();
        }

        if (!is_admin() || defined('DOING_AJAX')) {
            $this->load_class('frontend');
            $this->frontend = new WCMp_Product_Import_Export_Bundle_Frontend();
            // init shortcode
            $this->load_class('shortcode');
            $this->shortcode = new WCMp_Product_Import_Export_Bundle_Shortcode();
        }
        // init templates
        $this->load_class('template');
        $this->template = new WCMp_Product_Import_Export_Bundle_Template();
        // init export class
        $this->load_class('export');
        $this->export = new WCMp_Product_Import_Export_Bundle_Export();
        // check and import wprdpress import class
        $this->load_wordpress_importer();
        // init import class
        $this->load_class('import');
        $this->import = new WCMp_Product_Import_Export_Bundle_Import();
        // init variation-import class
        $this->load_class('import_variation');
        $this->import_variation = new WCMp_Product_Import_Export_Bundle_Import_Variation();
        // WCMp License Activation
        if (is_admin()) {
            $this->load_class('license');
            $this->license = WCMp_Product_Import_Export_Bundle_LICENSE();
        }
        // WCMp Wp Fields
        $this->wcmp_wp_fields = $this->library->load_wp_fields();
        //export meta fields
        $this->wcmp_export_meta_fields = $this->library->load_export_meta_fields_arr();
        $this->wcmp_import_meta_fields = $this->library->load_import_meta_fields_arr();
    }

    /**
     * Load Localisation files.
     *
     * Note: the first-loaded translation file overrides any following ones if the same translation is present
     *
     * @access public
     * @return void
     */
    public function load_plugin_textdomain() {
        $locale = is_admin() && function_exists('get_user_locale') ? get_user_locale() : get_locale();
        $locale = apply_filters('plugin_locale', $locale, 'wcmp-product-import-export-bundle');
        load_textdomain('wcmp-product-import-export-bundle', WP_LANG_DIR . '/wcmp-product-import-export-bundle/wcmp-product-import-export-bundle-' . $locale . '.mo');
        load_plugin_textdomain('wcmp-product-import-export-bundle', false, plugin_basename(dirname(__FILE__)) . '/languages');
    }

    public function load_class($class_name = '') {
        if ('' != $class_name && '' != $this->token) {
            require_once ('class-' . esc_attr($this->token) . '-' . esc_attr($class_name) . '.php');
        } // End If Statement
    }

// End load_class()

    /**
     * Install upon activation.
     *
     * @access public
     * @return void
     */
    static function activate_wcmp_product_import_export_bundle() {
        global $WCMp_Product_Import_Export_Bundle;
        // License Activation
        $WCMp_Product_Import_Export_Bundle->load_class('license');
        WCMp_Product_Import_Export_Bundle_LICENSE()->activation();

        $WCMp_Product_Import_Export_Bundle->load_class('install');
        $install = new WCMp_Product_Import_Export_Bundle_Install();


        update_option('wcmp_product_import_export_bundle_installed', 1);
    }

    /**
     * UnInstall upon deactivation.
     *
     * @access public
     * @return void
     */
    function deactivate_wcmp_product_import_export_bundle() {
        global $WCMp_Product_Import_Export_Bundle;
        delete_option('wcmp_product_import_export_bundle_installed');
        // License Deactivation
        $WCMp_Product_Import_Export_Bundle->load_class('license');
        WCMp_Product_Import_Export_Bundle_LICENSE()->uninstall();
    }

    /** Cache Helpers ******************************************************** */

    /**
     * Sets a constant preventing some caching plugins from caching a page. Used on dynamic pages
     *
     * @access public
     * @return void
     */
    function nocache() {
        if (!defined('DONOTCACHEPAGE'))
            define("DONOTCACHEPAGE", "true");
        // WP Super Cache constant
    }

    public function export_request() {
        if (!empty($_GET['action']) && !empty($_GET['page']) && $_GET['page'] == 'wcmp-product-import-export-bundle') {
            switch ($_GET['action']) {
                case "export" :
                    $this->export->simple_export('product');
                    break;
                case "export_variations" :
                    $this->export->export_variable('product');
                    break;
            }
        }
    }

    function wcmp_product_importers() {
        register_importer('wcmp_import_export_csv', 'WCMp Products (CSV)', __('Import vendor products.', $this->text_domain), array($this, 'importer'));
        register_importer('wcmp_import_export_variation_csv', 'WCMp Product Variations (CSV)', __('Import vendor product variations', $this->text_domain), array($this, 'variation_wcmp_importer'));
    }

    public function importer() {
        if (!defined('WP_LOAD_IMPORTERS')) {
            return;
        }
        $this->load_class('csv-field-parser');
        $GLOBALS['WCMp_Product_Import_Bundle'] = $this->import;
        $GLOBALS['WCMp_Product_Import_Bundle']->trigger();
    }

    public function variation_wcmp_importer() {
        if (!defined('WP_LOAD_IMPORTERS')) {
            return;
        }
        $this->load_class('csv-field-parser');
        $GLOBALS['WCMp_Product_Import_Bundle'] = $this->import_variation;
        $GLOBALS['WCMp_Product_Import_Bundle']->trigger();
    }

    public function load_wordpress_importer() {
        require_once ABSPATH . 'wp-admin/includes/import.php';
        if (!class_exists('WP_Importer')) {
            $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
            if (file_exists($class_wp_importer)) {
                require $class_wp_importer;
            }
        }
    }

    function add_woocommerce_class($classes) {
        $classes[] = 'woocommerce';
        return $classes;
    }

    public function load_admin_scripts_styles() {
        global $woocommerce;
        wp_enqueue_style('wcmp-csv-importer', plugins_url(basename(plugin_dir_path(WCMP_PRODUCT_IMPORT_EXPORT_BUNDLE_FILE)) . '/assets/admin/css/wcmp_import_export.css', basename(__FILE__)), '', WCMP_PRODUCT_IMPORT_EXPORT_BUNDLE_PLUGIN_VERSION, 'screen');
//        if (is_admin()) {
//            wp_enqueue_style('admin', plugins_url(basename(plugin_dir_path(WCMP_PRODUCT_IMPORT_EXPORT_BUNDLE_FILE)) . '/assets/admin/css/admin.css', basename(__FILE__)), '', WCMP_PRODUCT_IMPORT_EXPORT_BUNDLE_PLUGIN_VERSION, 'all');
//        }
    }

}