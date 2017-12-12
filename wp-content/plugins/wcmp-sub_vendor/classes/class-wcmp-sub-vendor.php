<?php

class WCMP_Sub_Vendor {

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
    public $dc_wp_fields;
    public $sub_vendor;

    public function __construct($file) {
        global $wp;
        $this->file = $file;
        $this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
        $this->plugin_path = trailingslashit(dirname($file));
        $this->token = WCMP_SUB_VENDOR_PLUGIN_TOKEN;
        $this->text_domain = WCMP_SUB_VENDOR_TEXT_DOMAIN;
        $this->version = WCMP_SUB_VENDOR_PLUGIN_VERSION;

        add_action('init', array(&$this, 'init'), 0);
        add_filter('login_redirect', array($this, 'wp_wcmp_vendor_login'), 10, 3);
    }
    
    function wp_wcmp_vendor_login($redirect_to, $request, $user) {
        global $WCMp;
        $pages = get_wcmp_vendor_settings('wcmp_pages_settings_name');
        //is there a user to check?
        if (isset($user->roles) && is_array($user->roles)) {
            //check for admins
            if (in_array('dc_sub_vendor', $user->roles)) {
                // redirect them to the default place
                $redirect_to = get_permalink($pages['vendor_dashboard']);
                return $redirect_to;
            } else {
                return $redirect_to;
            }
        } else {
            return $redirect_to;
        }
    }

    /**
     * initilize plugin on WP init
     */
    function init() {

        $current_user = wp_get_current_user();
        foreach ($current_user->roles as $key => $value) {

            $user_role = $value;
            if (isset($user_role)) {

                if ($user_role == 'dc_sub_vendor') {
                    //add_filter('template_path', array(&$this, 'sub_vendor_dashboard_path'));
                    add_filter('wcmp_order_vendor', array(&$this, 'wcmp_vendor_orders_and_shipping_menu'));
                    add_filter('wcmp_dashboard_vendor', array(&$this, 'wcmp_reporting_vendor'));
                    add_filter('wcmp_dashboard_sales_vendor', array(&$this, 'wcmp_reporting_vendor'));
                    add_filter('wcmp_dashboard_pending_shipping_vendor', array(&$this, 'wcmp_reporting_vendor'));
                    add_filter('wcmp_dashboard_report_vendor', array(&$this, 'wcmp_vendor_reports_reporting_vendor_id'));
                    add_filter('wcmp_dashboard_sale_stats_vendor', array(&$this, 'wcmp_reporting_vendor_object'));
                    add_filter('wcmp_vendor_by_term', array(&$this, 'wcmp_reporting_vendor'));
                    add_filter('wcmp_dashboard_order_details_vendor', array(&$this, 'wcmp_reporting_vendor_object'));
                    add_filter('wcmp_order_details_export_vendor', array(&$this, 'wcmp_reporting_vendor_object'));
                    add_filter('wcmp_csv_download_per_order_vendor', array(&$this, 'wcmp_reporting_vendor_object'));
                    add_filter('wcmp_shipping_vendor', array(&$this, 'wcmp_reporting_vendor_id'));
                    add_filter('wcmp_mark_as_shipped_vendor', array(&$this, 'wcmp_reporting_vendor_id'));
                    add_filter('wcmp_dashboard_new_vendor', array(&$this, 'wcmp_reporting_vendor_object'));
                    add_filter('wcmp_transaction_vendor', array(&$this, 'wcmp_reporting_vendor_object'));
                    add_filter('wcmp_vendor_dashboard_pages_vendor', array(&$this, 'wcmp_vendor_orders_and_shipping_menu'));
                    add_filter('wcmp_get_vendor_orders_vendor', array(&$this, 'wcmp_reporting_vendor_object'));
                    add_filter('dashboard_view_order_permission', array(&$this, 'dashboard_view_order_permission'));
                    add_filter('dashboard_manage_payment_permission', array(&$this, 'dashboard_manage_payment_permission'));
                    add_action('init', array(&$this, 'remove_dashboard_page_shortcodes'));
                    add_filter('wcmp_dashboard_shipping_vendor', array(&$this, 'wcmp_reporting_vendor_id'));
                }
            }
        }

        // Init Text Domain
        $this->load_plugin_textdomain();
        // dc sub-vendor
        $this->load_class('menu');
        $sub_vendor = new WCMP_Sub_Vendor_Menu();

        // Init library
        $this->load_class('library');
        $this->library = new WCMP_Sub_Vendor_Library();

        // Init ajax
        if (defined('DOING_AJAX')) {
            $this->load_class('ajax');
            $this->ajax = new WCMP_Sub_Vendor_Ajax();
        }

        if (is_admin()) {
            $this->load_class('admin');
            $this->admin = new WCMP_Sub_Vendor_Admin();
        }

        if (!is_admin() || defined('DOING_AJAX')) {
            $this->load_class('frontend');
            $this->frontend = new WCMP_Sub_Vendor_Frontend();

            // init shortcode
            $this->load_class('shortcode');
            $this->shortcode = new WCMP_Sub_Vendor_Shortcode();

            // init templates
            $this->load_class('template');
            $this->template = new WCMP_Sub_Vendor_Template();
        }
        // DC License Activation
        if (is_admin()) {
            $this->load_class('license');
            $this->license = WCMP_Sub_Vendor_LICENSE();
        }

        // DC Wp Fields
        $this->dc_wp_fields = $this->library->load_wp_fields();
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
        $locale = apply_filters('plugin_locale', get_locale(), $this->token);

        load_textdomain($this->text_domain, WP_LANG_DIR . "/wcmp-sub-vendor/wcmp-sub-vendor-$locale.mo");
        load_textdomain($this->text_domain, $this->plugin_path . "/languages/wcmp-sub-vendor-$locale.mo");
    }

    public function load_class($class_name = '') {
        if ('' != $class_name && '' != $this->token) {
            require_once ('class-' . esc_attr($this->token) . '-' . esc_attr($class_name) . '.php');
        } // End If Statement
    }

// End load_class()

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

    public function dc_sub_vendor() {
        return 'dc_sub_vendor';
    }

    public function sub_vendor_dashboard_path() {
        return WP_PLUGIN_DIR . '/wcmp-sub_vendor/templates/';
    }

    public function wcmp_reporting_vendor() {

        $current_vendor = wp_get_current_user();
        $reporting_vendor_id = get_user_meta($current_vendor->ID, '_report_vendor');
        $reporting_vendor = get_userdata($reporting_vendor_id[0]);

        return $reporting_vendor;
    }

    public function wcmp_reporting_vendor_id() {
        $current_vendor = wp_get_current_user();
        $reporting_vendor = get_user_meta($current_vendor->ID, '_report_vendor');
        $reporting_vendor_id = $reporting_vendor[0];
        return $reporting_vendor_id;
    }

    public function wcmp_reporting_vendor_object() {
        $current_vendor = wp_get_current_user();
        $reporting_vendor_id = get_user_meta($current_vendor->ID, '_report_vendor');

        $reporting_vendor = new WCMp_Vendor($reporting_vendor_id[0]);
        return $reporting_vendor;
    }

    public function wcmp_vendor_orders_and_shipping_menu() {

        $current_sub_vendor = wp_get_current_user();
        if (isset($current_sub_vendor->allcaps['manage_woocommerce_orders'])) {
            $reporting_vendor_id = get_user_meta($current_sub_vendor->ID, '_report_vendor');

            $reporting_vendor = new WCMp_Vendor($reporting_vendor_id[0]);

            return $reporting_vendor;
        }
    }

    public function dashboard_view_order_permission() {

        $current_sub_vendor = wp_get_current_user();
        if (isset($current_sub_vendor->allcaps['manage_woocommerce_orders'])) {

            $dashboard_view_order_permission = 'true';
            return $dashboard_view_order_permission;
        }
    }

    public function dashboard_manage_payment_permission() {
        $current_sub_vendor = wp_get_current_user();
        if (isset($current_sub_vendor->allcaps['manage_payment'])) {

            $dashboard_manage_payment_permission = 'true';
            return $dashboard_manage_payment_permission;
        }
    }

    public function wcmp_vendor_reports_reporting_vendor_id() {

        $current_sub_vendor = wp_get_current_user();
        if (isset($current_sub_vendor->allcaps['view_woocommerce_reports'])) {
            $reporting_vendor = get_user_meta($current_sub_vendor->ID, '_report_vendor');
            $reporting_vendor_id = $reporting_vendor[0];
            return $reporting_vendor_id;
        }
    }

    public function remove_dashboard_page_shortcodes() {
        $current_sub_vendor = wp_get_current_user();
        remove_shortcode('shop_settings');
        remove_shortcode('vendor_billing');
        remove_shortcode('vendor_shipping_settings');
        if (empty($current_sub_vendor->allcaps['manage_payment'])) {
            remove_shortcode('transaction_details');
        }
        if (empty($current_sub_vendor->allcaps['view_woocommerce_reports'])) {
            remove_shortcode('vendor_report');
        }
        if (empty($current_sub_vendor->allcaps['manage_woocommerce_orders'])) {
            remove_shortcode('vendor_orders');
        }
    }
    
    /**
     * Install upon activation.
     *
     * @access public
     * @return void
     */
    public function activate_WCMP_Sub_Vendor() {
        global $WCMP_Sub_Vendor;
        // License Activation
        $WCMP_Sub_Vendor->load_class('license');
        WCMP_Sub_Vendor_LICENSE()->activation();
        update_option('WCMP_Sub_Vendor_installed', 1);
    }

    /**
     * UnInstall upon deactivation.
     *
     * @access public
     * @return void
     */
    public function deactivate_WCMP_Sub_Vendor() {
        global $WCMP_Sub_Vendor;
        delete_option('WCMP_Sub_Vendor_installed');

        // License Deactivation
        $WCMP_Sub_Vendor->load_class('license');
        WCMP_Sub_Vendor_LICENSE()->uninstall();
    }

}