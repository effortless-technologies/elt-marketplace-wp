<?php

class WCMP_Vendor_Vacation {

    public $plugin_url;
    public $plugin_path;
    public $version;
    public $token;
    public $text_domain;
    public $library;
    public $shortcode;
    public $admin;
    public $frontend;
    public $template;
    public $ajax;
    private $file;
    public $settings;
    public $license;
    public $dc_wp_fields;
    public $selected_item;

    public function __construct($file) {

        $this->file = $file;
        $this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
        $this->plugin_path = trailingslashit(dirname($file));
        $this->token = WCMP_VENDOR_VACATION_PLUGIN_TOKEN;
        $this->text_domain = WCMP_VENDOR_VACATION_TEXT_DOMAIN;
        $this->version = WCMP_VENDOR_VACATION_PLUGIN_VERSION;

        add_action('init', array(&$this, 'init'));
        add_action('wcmp_init', array(&$this, 'init_after_wcmp_load'));
    }

    /**
     * initilize plugin on WP init
     */
    function init() {

        // Init Text Domain
        $this->load_plugin_textdomain();

        // Init library
        $this->load_class('library');
        $this->library = new WCMP_Vendor_Vacation_Library();

        // Init ajax
        if (defined('DOING_AJAX')) {
            $this->load_class('ajax');
            $this->ajax = new WCMP_Vendor_Vacation_Ajax();
        }

        if (is_admin()) {
            $this->load_class('admin');
            $this->admin = new WCMP_Vendor_Vacation_Admin();
        }

        if (!is_admin() || defined('DOING_AJAX')) {
            $this->load_class('frontend');
            $this->frontend = new WCMP_Vendor_Vacation_Frontend();

            // init shortcode
            $this->load_class('shortcode');
            $this->shortcode = new WCMP_Vendor_Vacation_Shortcode();

            // init templates
            $this->load_class('template');
            $this->template = new WCMP_Vendor_Vacation_Template();
        }

        // DC License Activation
        if (is_admin()) {
            $this->load_class('license');
            $this->license = WCMP_Vendor_Vacation_LICENSE();
        }

        // DC Wp Fields
        $this->dc_wp_fields = $this->library->load_wp_fields();
    }
    
    function init_after_wcmp_load(){
        add_action('settings_vendor_general_tab_options', array(&$this, 'add_vendor_vacation_endpoint_option'));
        add_filter('settings_vendor_general_tab_new_input', array(&$this, 'save_vendor_vacation_endpoint_option'), 10, 2);
        add_filter('wcmp_endpoints_query_vars', array(&$this, 'add_wcmp_endpoints_query_vars'));
        add_filter('wcmp_vendor_dashboard_nav', array(&$this, 'add_tab_to_vendor_dashboard'));
    }
    
    public function add_vendor_vacation_endpoint_option($settings_tab_options) {
        global $WCMP_Vendor_Vacation;
        $settings_tab_options['sections']['wcmp_vendor_general_settings_endpoint_ssection']['fields']['wcmp_vendor_vacation_endpoint'] = array('title' => __('Vendor Vacation', 'wcmp-vendor_vacation'), 'type' => 'text', 'id' => 'wcmp_vendor_vacation_endpoint', 'label_for' => 'wcmp_vendor_vacation_endpoint', 'name' => 'wcmp_vendor_vacation_endpoint', 'hints' => __('Set endpoint for vendor vacation page', 'wcmp-vendor_vacation'), 'placeholder' => 'vendor-vacation');
        return $settings_tab_options;
    }

    public function save_vendor_vacation_endpoint_option($new_input, $input) {
        if (isset($input['wcmp_vendor_vacation_endpoint']) && !empty($input['wcmp_vendor_vacation_endpoint'])) {
            $new_input['wcmp_vendor_vacation_endpoint'] = sanitize_text_field($input['wcmp_vendor_vacation_endpoint']);
        }
        return $new_input;
    }

    public function add_wcmp_endpoints_query_vars($endpoints) {
        global $WCMP_Vendor_Vacation;
        $endpoints['vendor-vacation'] = array(
            'label' => __('Vacation Settings', 'wcmp-vendor_vacation'),
            'endpoint' => get_wcmp_vendor_settings('wcmp_vendor_vacation_endpoint', 'vendor', 'general', 'vendor-vacation')
        );
        if (!get_option('vendor_vacation_added')) {
            flush_rewrite_rules();
            update_option('vendor_vacation_added', 1);
        }
        return $endpoints;
    }

    public function add_tab_to_vendor_dashboard($nav) {
        global $WCMP_Vendor_Vacation;
        $nav['store-settings']['submenu']['vendor-vacation'] = array(
            'label' => __('Vacation', 'wcmp-vendor_vacation')
            , 'url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_vacation_endpoint', 'vendor', 'general', 'vendor-vacation'))
            , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_vacation_capability', true)
            , 'position' => 60
            , 'link_target' => '_self'
        );
        return $nav;
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
        $locale = apply_filters('plugin_locale', $locale, 'wcmp-vendor_vacation');
        load_textdomain('wcmp-vendor_vacation', WP_LANG_DIR . '/wcmp-vendor_vacation/wcmp-vendor_vacation-' . $locale . '.mo');
        load_plugin_textdomain('wcmp-vendor_vacation', false, plugin_basename(dirname(dirname(__FILE__))) . '/languages');
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
    static function activate_wcmp_vendor_vacation() {
        global $WCMp, $WCMP_Vendor_Vacation;

        // License Activation
        $WCMP_Vendor_Vacation->load_class('license');
        WCMP_Vendor_Vacation_LICENSE()->activation();
    }

    /**
     * UnInstall upon deactivation.
     *
     * @access public
     * @return void
     */
    static function deactivate_wcmp_vendor_vacation() {
        global $WCMP_Vendor_Vacation;
        delete_option('wcmp_vendor_vacation_installed');

        // License Deactivation
        $WCMP_Vendor_Vacation->load_class('license');
        WCMP_Vendor_Vacation_LICENSE()->uninstall();
        delete_option('vendor_vacation_added');
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

}
