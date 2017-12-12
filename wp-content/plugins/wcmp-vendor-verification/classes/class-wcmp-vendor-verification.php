<?php

class WCMP_Vendor_Verification {

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
    public $auth;
    private $file;
    public $settings;
    public $license;
    public $dc_wp_fields;
    public $selected_item;

    public function __construct($file) {

        $this->file = $file;
        $this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
        $this->plugin_path = trailingslashit(dirname($file));
        $this->token = WCMP_VENDOR_VERIFICATION_PLUGIN_TOKEN;
        $this->text_domain = WCMP_VENDOR_VERIFICATION_TEXT_DOMAIN;
        $this->version = WCMP_VENDOR_VERIFICATION_PLUGIN_VERSION;

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
        $this->library = new WCMP_Vendor_Verification_Library();

        // Init ajax
        if (defined('DOING_AJAX')) {
            $this->load_class('ajax');
            $this->ajax = new WCMP_Vendor_Verification_Ajax();
        }

        if (is_admin()) {
            $this->load_class('admin');
            $this->admin = new WCMP_Vendor_Verification_Admin();
        }

        if (!is_admin() || defined('DOING_AJAX')) {
            $this->load_class('frontend');
            $this->frontend = new WCMP_Vendor_Verification_Frontend();

            // init templates
            $this->load_class('template');
            $this->template = new WCMP_Vendor_Verification_Template();
        }

        // DC License Activation
        if (is_admin()) {
            $this->load_class('license');
            $this->license = WCMP_Vendor_Verification_LICENSE();
        }

        // DC Wp Fields
        $this->dc_wp_fields = $this->library->load_wp_fields();

        // Hybrid Auth
        $this->hybridauth_init();

        // Vendor Verification Auth
        $this->load_class('auth');
        $this->auth = new WCMP_Vendor_Verification_Auth();
        
    }

    function hybridauth_init(){
        if ( !class_exists( 'Hybrid_Auth' ) ) {
            require_once $this->library->hybridauth_lib_path . 'Auth.php';
        }
        if ( !class_exists( 'Hybrid_Endpoint' ) ) {
            require_once $this->library->hybridauth_lib_path . 'Endpoint.php';
        }
    }
    
    function init_after_wcmp_load(){
        add_action('settings_vendor_general_tab_options', array(&$this, 'add_vendor_verification_endpoint_option'));
        add_filter('settings_vendor_general_tab_new_input', array(&$this, 'save_vendor_verification_endpoint_option'), 10, 2);
        add_filter('wcmp_endpoints_query_vars', array(&$this, 'add_wcmp_endpoints_query_vars'));
        add_filter('wcmp_vendor_dashboard_nav', array(&$this, 'add_tab_to_vendor_dashboard'));
    }
    
    public function add_vendor_verification_endpoint_option($settings_tab_options) {
        global $WCMP_Vendor_Verification;
        $settings_tab_options['sections']['wcmp_vendor_general_settings_endpoint_ssection']['fields']['wcmp_vendor_verification_endpoint'] = array('title' => __('Vendor Verification', 'wcmp-vendor-verification'), 'type' => 'text', 'id' => 'wcmp_vendor_verification_endpoint', 'label_for' => 'wcmp_vendor_verification_endpoint', 'name' => 'wcmp_vendor_verification_endpoint', 'hints' => __('Set endpoint for vendor verification page', 'wcmp-vendor-verification'), 'placeholder' => 'vendor-verification');
        return $settings_tab_options;
    }

    public function save_vendor_verification_endpoint_option($new_input, $input) {
        if (isset($input['wcmp_vendor_verification_endpoint']) && !empty($input['wcmp_vendor_verification_endpoint'])) {
            $new_input['wcmp_vendor_verification_endpoint'] = sanitize_text_field($input['wcmp_vendor_verification_endpoint']);
        }
        return $new_input;
    }

    public function add_wcmp_endpoints_query_vars($endpoints) {
        global $WCMP_Vendor_Verification;
        $endpoints['vendor-verification'] = array(
            'label' => __('Vendor Verification', 'wcmp-vendor-verification'),
            'endpoint' => get_wcmp_vendor_settings('wcmp_vendor_verification_endpoint', 'vendor', 'general', 'vendor-verification')
        );
        if (!get_option('vendor_verification_added')) {
            flush_rewrite_rules();
            update_option('vendor_verification_added', 1);
        }
        return $endpoints;
    }

    public function add_tab_to_vendor_dashboard($nav) {
        global $WCMP_Vendor_Verification;
        $nav['store-settings']['submenu']['vendor-verification'] = array(
            'label' => __('Verification', 'wcmp-vendor-verification')
            , 'url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_verification_endpoint', 'vendor', 'general', 'vendor-verification'))
            , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_verification_capability', true)
            , 'position' => 55
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
        $locale = apply_filters('plugin_locale', $locale, 'wcmp-vendor-verification');
        load_textdomain('wcmp-vendor-verification', WP_LANG_DIR . '/wcmp-vendor-verification/wcmp-vendor-verification-' . $locale . '.mo');
        load_plugin_textdomain('wcmp-vendor-verification', false, plugin_basename(dirname(dirname(__FILE__))) . '/languages');
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
    static function activate_WCMP_Vendor_Verification() {
        global $WCMp, $WCMP_Vendor_Verification;

        // License Activation
        $WCMP_Vendor_Verification->load_class('license');
        WCMP_Vendor_Verification_LICENSE()->activation();
    }

    /**
     * UnInstall upon deactivation.
     *
     * @access public
     * @return void
     */
    static function deactivate_WCMP_Vendor_Verification() {
        global $WCMP_Vendor_Verification;
        delete_option('WCMP_Vendor_Verification_installed');

        // License Deactivation
        $WCMP_Vendor_Verification->load_class('license');
        WCMP_Vendor_Verification_LICENSE()->uninstall();
        delete_option('vendor_verification_added');
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
