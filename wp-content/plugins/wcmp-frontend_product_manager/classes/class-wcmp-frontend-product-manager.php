<?php

class WCMp_Frontend_Product_Manager {

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
    public $wcmp_wp_fields;
    public $wc_variable;
    public $wc_grouped;
    public $wc_external;
    public $import_controller;
    public $wc_yithauction;
    public $wc_auction;
    public $wc_addons;
    public $wcmp_fpm_wcbooking;
    public $wcaccommodation;
    public $wcmp_fpm_wcrental;
    public $wcmp_fpm_wcbundle;
    public $wcmp_fpm_wcsubscriptions;

    public function __construct($file) {

        $this->file = $file;
        $this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
        $this->plugin_path = trailingslashit(dirname($file));
        $this->token = WCMP_FRONTEND_PRODUCT_MANAGER_PLUGIN_TOKEN;
        $this->text_domain = WCMP_FRONTEND_PRODUCT_MANAGER_TEXT_DOMAIN;
        $this->version = WCMP_FRONTEND_PRODUCT_MANAGER_PLUGIN_VERSION;

        add_filter('wcmp_vendor_capabilities', array($this, 'add_vendor_capability'), 10, 1);
        add_action('init', array(&$this, 'init'));
        add_action('wcmp_init', array(&$this, 'init_after_wcmp_load'));
    }

    /**
     * Add vendor import export capability
     */
    public function add_vendor_capability($caps) {
        global $WCMp;
        if ($WCMp->vendor_caps->vendor_capabilities_settings('vendor_import_capability')) {
            $caps['vendor_import_capability'] = true;
        } else {
            $caps['vendor_import_capability'] = false;
        }
        if ($WCMp->vendor_caps->vendor_capabilities_settings('vendor_export_capability')) {
            $caps['vendor_export_capability'] = true;
        } else {
            $caps['vendor_export_capability'] = false;
        }
        return $caps;
    }

    /**
     * initilize plugin on WP init
     */
    function init_after_wcmp_load() {
        // filter for adding text field in WCMp admin settings general tab for entering endpoint for product import
        add_action('settings_vendor_general_tab_options', array(&$this, 'add_import_export_endpoint_option'));

        // filter for saving text field value in WCMp admin settings general tab for entering endpoint for product import to database
        add_filter('settings_vendor_general_tab_new_input', array(&$this, 'save_import_export_endpoint_option'), 10, 2);

        //filter for adding wcmp endpoint query vars
        add_filter('wcmp_endpoints_query_vars', array(&$this, 'add_wcmp_endpoints_query_vars'));

        //filter for adding import export menu to vendor dashboard according to the capabilities
        $user_id = get_current_user_id();
        $vendor = get_wcmp_vendor($user_id);
        $user = new WP_User($user_id);
        if ($vendor && $user->has_cap('edit_products')) {
            add_filter('wcmp_vendor_dashboard_nav', array(&$this, 'add_import_export_plugin_menu'), 20);
        }

        add_filter('wcmp_vendor_dashboard_header_right_panel_nav', array(&$this, 'remove_dashboard_header_right_panel_nav'));
    }

    /**
     * initilize plugin on WP init
     */
    function init() {
        global $WCMp;

        // Init Text Domain
        $this->load_plugin_textdomain();

        // Init library
        $this->wcmp_wp_fields = $WCMp->library->load_wcmp_frontend_fields();
        //$this->load_class('library');
        //$this->library = new WCMp_Frontend_Product_Manager_Library();
        // Init ajax
        if (defined('DOING_AJAX')) {
            $this->load_class('ajax');
            $this->ajax = new WCMp_Frontend_Product_Manager_Ajax();
        }

        if (is_admin()) {
            $this->load_class('admin');
            $this->admin = new WCMp_Frontend_Product_Manager_Admin();
        }

        if (!is_admin() || defined('DOING_AJAX')) {
            $this->load_class('frontend');
            $this->frontend = new WCMp_Frontend_Product_Manager_Frontend();

            // init shortcode
            /* $this->load_class('shortcode');
              $this->shortcode = new WCMp_Frontend_Product_Manager_Shortcode(); */

            // init templates
            $this->load_class('template');
            $this->template = new WCMp_Frontend_Product_Manager_Template();

            //loads import controller
            $this->load_class('import-controller');
            $this->import_controller = new WCMP_Product_Import_Export_Bundle_Import_Controller();
        }

        // DC License Activation
        if (is_admin()) {
            $this->load_class('license');
            $this->license = WCMp_Frontend_Product_Manager_LICENSE();
        }

        if ($WCMp->vendor_caps->vendor_can('variable')) {
            $this->load_class('wcvariable');
            $this->wc_variable = new WCMp_Frontend_Product_Manager_Variable();
        }

        if ($WCMp->vendor_caps->vendor_can('grouped')) {
            $this->load_class('wcgrouped');
            $this->wc_grouped = new WCMp_Frontend_Product_Manager_Grouped();
        }

        if ($WCMp->vendor_caps->vendor_can('external')) {
            $this->load_class('wcexternal');
            $this->wc_external = new WCMp_Frontend_Product_Manager_External();
        }

        // Check if Yith Auction
        if (wcmp_forntend_manager_is_yithauction()) {
            $this->load_class('wcyithauction');
            $this->wc_yithauction = new WCMp_Frontend_Product_Manager_YTH_Auction();
        }

        // Check WC Simple Auction
        if (wcmp_forntend_manager_is_wcsauction()) {
            $this->load_class('wcsauction');
            $this->wc_auction = new WCMp_Frontend_Product_Manager_WCS_Auction();
        }

        // Check WC Product Addons
        if (wcmp_forntend_manager_has_wcaddons()) {
            $this->load_class('wcaddons');
            $this->wc_addons = new WCMp_Frontend_Product_Manager_WC_Addons();
        }

        // Check if WC Bookings
        if (wcmp_forntend_manager_is_booking()) {
            $this->load_class('wcbookings');
            $this->wcmp_fpm_wcbooking = new WCMp_Frontend_Product_Manager_WCBookings();
        }

        // init wcaccommodation
        if (wcmp_forntend_manager_is_accommodation_booking()) {
            $this->load_class('wcaccommodation');
            $this->wcaccommodation = new WCMp_Frontend_Product_Manager_WCAccommodationBookings();
        }

        // Check if WC Rental
        if (wcmp_forntend_manager_is_rental()) {
            $this->load_class('wcrental');
            $this->wcmp_fpm_wcrental = new WCMp_Frontend_Product_Manager_WCRental();
        }

        // Check if WC Bundle
        if (wcmp_forntend_manager_is_bundle()) {
            $this->load_class('wcbundle');
            $this->wcmp_fpm_wcbundle = new WCMp_Frontend_Product_Manager_WCBundle();
        }

        // Check if WC Subscriptions
        if (wcmp_forntend_manager_is_subscription()) {
            $this->load_class('wcsubscriptions');
            $this->wcmp_fpm_wcsubscriptions = new WCMp_Frontend_Product_Manager_WCSubscriptions();
        }

        $this->restrict_wp_backend();

        // DC Wp Fields
        /* $this->wcmp_wp_fields = $this->library->load_fpm_fields(); */
    }

    /**
     * set default value
     */
    static function additional_product_type_capability_set() {
        if (wcmp_forntend_manager_is_booking()) {
            if (!get_wcmp_vendor_settings('booking', 'capabilities', 'product')) {
                update_wcmp_vendor_settings('booking', 'Enable', 'capabilities', 'product');
            }
        }

        if (wcmp_forntend_manager_is_bundle()) {
            if (!get_wcmp_vendor_settings('bundle', 'capabilities', 'product')) {
                update_wcmp_vendor_settings('bundle', 'Enable', 'capabilities', 'product');
            }
        }

        if (wcmp_forntend_manager_is_subscription()) {
            if (!get_wcmp_vendor_settings('subscription', 'capabilities', 'product')) {
                update_wcmp_vendor_settings('subscription', 'Enable', 'capabilities', 'product');
            }
            if (!get_wcmp_vendor_settings('variable-subscription', 'capabilities', 'product')) {
                update_wcmp_vendor_settings('variable-subscription', 'Enable', 'capabilities', 'product');
            }
        }

        if (wcmp_forntend_manager_is_yithauction() || wcmp_forntend_manager_is_wcsauction()) {
            if (!get_wcmp_vendor_settings('auction', 'capabilities', 'product')) {
                update_wcmp_vendor_settings('auction', 'Enable', 'capabilities', 'product');
            }
        }
    }

    /**
     * adds text field in WCMp admin settings general tab for entering endpoint for product import export
     * @return array
     */
    public function add_import_export_endpoint_option($settings_tab_options) {
        global $WCMp_Frontend_Product_Manager;

        $settings_tab_options['sections']['wcmp_vendor_general_settings_endpoint_ssection']['fields']['wcmp_product_import_endpoint'] = array('title' => __('Product Import', 'wcmp-product_import_export_bundle'), 'type' => 'text', 'id' => 'wcmp_product_import_endpoint', 'label_for' => 'wcmp_product_import_endpoint', 'name' => 'wcmp_product_import_endpoint', 'hints' => __('Set endpoint for product import page', 'wcmp-product_import_export_bundle'), 'placeholder' => 'product-import');
        $settings_tab_options['sections']['wcmp_vendor_general_settings_endpoint_ssection']['fields']['wcmp_product_export_endpoint'] = array('title' => __('Product Export', 'wcmp-product_import_export_bundle'), 'type' => 'text', 'id' => 'wcmp_product_export_endpoint', 'label_for' => 'wcmp_product_export_endpoint', 'name' => 'wcmp_product_export_endpoint', 'hints' => __('Set endpoint for product export page', 'wcmp-product_import_export_bundle'), 'placeholder' => 'product-export');

        if (wcmp_forntend_manager_is_yithauction() || wcmp_forntend_manager_is_wcsauction()) {
            // Vendor Dashbard Auctions
            $settings_tab_options['sections']['wcmp_vendor_general_settings_endpoint_ssection']['fields']['frontend_auction_manager'] = array('title' => __('Auctions', 'wcmp-frontend_product_manager'), 'type' => 'text', 'id' => 'frontend_auction_manager', 'label_for' => 'frontend_auction_manager', 'name' => 'frontend_auction_manager', 'hints' => __('Set Endpoint for Vendor Dashboard Auctions page', 'wcmp-frontend_product_manager'), 'placeholder' => 'auctions');
        }

        return $settings_tab_options;
    }

    /**
     * saves text field values in WCMp admin settings general tab for entering endpoint for product import export in database
     * @return array
     */
    public function save_import_export_endpoint_option($new_input, $input) {

        if (isset($input['wcmp_product_import_endpoint']) && !empty($input['wcmp_product_import_endpoint'])) {
            $new_input['wcmp_product_import_endpoint'] = sanitize_text_field($input['wcmp_product_import_endpoint']);
        }
        if (isset($input['wcmp_product_export_endpoint']) && !empty($input['wcmp_product_export_endpoint'])) {
            $new_input['wcmp_product_export_endpoint'] = sanitize_text_field($input['wcmp_product_export_endpoint']);
        }

        if (wcmp_forntend_manager_is_yithauction() || wcmp_forntend_manager_is_wcsauction()) {
            $new_input['frontend_auction_manager'] = $input['frontend_auction_manager'];
        }
        return $new_input;
    }

    /**
     * adds query vars
     * @return array
     */
    public function add_wcmp_endpoints_query_vars($endpoints) {
        $endpoints['product-import'] = array(
            'label' => __('Import', 'wcmp-frontend_product_manager'),
            'endpoint' => get_wcmp_vendor_settings('wcmp_product_import_endpoint', 'vendor', 'general', 'product-import')
        );
        $endpoints['product-export'] = array(
            'label' => __('Export', 'wcmp-frontend_product_manager'),
            'endpoint' => get_wcmp_vendor_settings('wcmp_product_export_endpoint', 'vendor', 'general', 'product-export')
        );
        $endpoints['auctions'] = array(
            'label' => __('Auctions', 'wcmp-frontend_product_manager'),
            'endpoint' => get_wcmp_vendor_settings('wcmp_vendor_auction_endpoint', 'vendor', 'general', 'auctions')
        );
        $endpoints['booking-details'] = array(
            'label' => __('Booking Details', 'wcmp-frontend_product_manager'),
            'endpoint' => 'booking-details'
        );
        $endpoints['booking-calendar'] = array(
            'label' => __('Calendar', 'wcmp-frontend_product_manager'),
            'endpoint' => 'booking-calendar'//get_wcmp_vendor_settings('wcmp_booking_manage_endpoint', 'vendor', 'general','booking-calendar')
        );
        $endpoints['all-booking'] = array(
            'label' => __('All Bookings', 'wcmp-frontend_product_manager'),
            'endpoint' => 'all-booking'//get_wcmp_vendor_settings('wcmp_booking_manage_endpoint', 'vendor', 'general','booking-calendar')
        );
        if (!get_option('wcb_wcmp_fpm_endpoint_added')) {
            flush_rewrite_rules();
            update_option('wcb_wcmp_fpm_endpoint_added', true);
        }

        return $endpoints;
    }

    /**
     * adds import export menu to vendor dashboard according to the capabilities
     * @return array
     */
    public function add_import_export_plugin_menu($nav) {
        $nav['vendor-products']['submenu']['product-import'] = array(
            'label' => __('Import', 'wcmp-frontend_product_manager')
            , 'url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_import_endpoint', 'vendor', 'general', 'product-import'))
            , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_import_capability', 'vendor_import_capability')
            , 'position' => 30
            , 'link_target' => '_self'
            , 'nav_icon' => 'wcmp-font ico-import-icon'
        );
        $nav['vendor-products']['submenu']['product-export'] = array(
            'label' => __('Export', 'wcmp-frontend_product_manager')
            , 'url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_export_endpoint', 'vendor', 'general', 'product-export'))
            , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_export_capability', 'vendor_export_capability')
            , 'position' => 40
            , 'link_target' => '_self'
            , 'nav_icon' => 'wcmp-font ico-export-icon'
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
        $locale = apply_filters('plugin_locale', $locale, 'wcmp-frontend_product_manager');
        load_textdomain('wcmp-frontend_product_manager', WP_LANG_DIR . '/wcmp-frontend_product_manager/wcmp-frontend_product_manager-' . $locale . '.mo');
        load_plugin_textdomain('wcmp-frontend_product_manager', false, plugin_basename(dirname(dirname(__FILE__))) . '/languages');
    }

    public function load_class($class_name = '') {
        if ('' != $class_name && '' != $this->token) {
            require_once ('class-' . esc_attr($this->token) . '-' . esc_attr($class_name) . '.php');
        } // End If Statement
    }

// End load_class()
    /**
     * Booking activation.
     *
     * @access public
     * @return void
     */
    static function woo_booking_activate() {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        $vendor_role = get_role('dc_vendor');
        $vendor_role->add_cap('manage_bookings');
    }

    /**
     * UnInstall booking deactivation.
     *
     * @access public
     * @return void
     */
    static function deactivate_woo_booking() {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        $vendor_role = get_role('dc_vendor');
        if ($vendor_role->has_cap('manage_bookings')) {
            $vendor_role->remove_cap('manage_bookings');
        }
        delete_option('wcmp_frontend_product_manager_installed');
    }

    /**
     * Install upon activation.
     *
     * @access public
     * @return void
     */
    static function activate_wcmp_frontend_product_manager() {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        $vendor_role = get_role('dc_vendor');
        // License Activation
        $WCMp_Frontend_Product_Manager->load_class('license');
        WCMp_Frontend_Product_Manager_LICENSE()->activation();

        require_once ( $WCMp->plugin_path . 'includes/class-wcmp-install.php' );
        $WCMp_Install = new WCMp_Install();
        $array_pages = get_option('wcmp_vendor_general_settings_name');

        // Product Manager Pages
        $WCMp_Install->wcmp_product_vendor_plugin_create_page(esc_sql(_x('wcmp-frontend_product_manager', 'page_slug', 'wcmp-frontend_product_manager')), 'wcmp_frontend_product_manager_page_id', __('Product Manager', 'wcmp-frontend_product_manager'), '[wcmp_frontend_product_manager]');
        $WCMp_Install->wcmp_product_vendor_plugin_create_page(esc_sql(_x('wcmp_products', 'page_slug', 'wcmp-frontend_product_manager')), 'wcmp_pending_products_page_id', __('Products', 'wcmp-frontend_product_manager'), '[wcmp_pending_products]');
        $array_pages['frontend_product_manager'] = get_option('wcmp_frontend_product_manager_page_id');
        $array_pages['wcmp_pending_products'] = get_option('wcmp_pending_products_page_id');

        // Coupon Manager Pages
        $WCMp_Install->wcmp_product_vendor_plugin_create_page(esc_sql(_x('wcmp_frontend_coupon_manager', 'page_slug', 'wcmp-frontend_product_manager')), 'wcmp_frontend_coupon_manager_page_id', __('Coupon Manager', 'wcmp-frontend_product_manager'), '[wcmp_frontend_coupon_manager]');
        $WCMp_Install->wcmp_product_vendor_plugin_create_page(esc_sql(_x('wcmp_coupons', 'page_slug', 'wcmp-frontend_product_manager')), 'wcmp_coupons_page_id', __('Coupons', 'wcmp-frontend_product_manager'), '[wcmp_coupons]');
        $array_pages['frontend_coupon_manager'] = get_option('wcmp_frontend_coupon_manager_page_id');
        $array_pages['wcmp_coupons'] = get_option('wcmp_coupons_page_id');


        update_option('wcmp_vendor_general_settings_name', $array_pages);
        
        if(!get_option('wcmp_frontend_product_manager_installed')){
            $this->additional_product_type_capability_set();
            update_option('wcmp_frontend_product_manager_installed', 1);
        }

        if (wcmp_forntend_manager_is_booking()) {
            $vendor_role->add_cap('manage_bookings');
        } else {
            if ($vendor_role->has_cap('manage_bookings')) {
                $vendor_role->remove_cap('manage_bookings');
            }
        }
    }

    /**
     * UnInstall upon deactivation.
     *
     * @access public
     * @return void
     */
    static function deactivate_wcmp_frontend_product_manager() {
        global $WCMp_Frontend_Product_Manager;
        $vendor_role = get_role('dc_vendor');
        delete_option('wcmp_frontend_product_manager_installed');
        $vendor_role->remove_cap('manage_bookings');
        // License Deactivation
        $WCMp_Frontend_Product_Manager->load_class('license');
        WCMp_Frontend_Product_Manager_LICENSE()->uninstall();
    }

    static function restrict_wp_backend() {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        if (is_user_logged_in()) {
            $general_settings = get_option('wcmp_general_settings_name');
            $current_vendor_id = apply_filters('wcmp_current_loggedin_vendor_id', get_current_user_id());
            if (is_user_wcmp_vendor($current_vendor_id) && is_admin() && isset($general_settings['is_backend_diabled']) && !defined('DOING_AJAX') && (!isset($_GET['action']) || $_GET['action'] != 'download_product_csv')) {
                wp_redirect(get_permalink(wcmp_vendor_dashboard_page_id()));
                exit;
            }
        }
    }

    function remove_dashboard_header_right_panel_nav($nav) {
        if (is_user_logged_in()) {
            $general_settings = get_option('wcmp_general_settings_name');
            if (isset($general_settings['is_backend_diabled']) && isset($nav['wp-admin'])) {
                unset($nav['wp-admin']);
            }
            return $nav;
        }
    }

    // Generate Taxonomy HTML
    function generateTaxonomyHTML($taxonomy, $product_categories, $categories, $nbsp = '') {
        global $WCMp, $WCMp_Frontend_Product_Manager;

        foreach ($product_categories as $cat) {
            if (apply_filters('is_visible_wcmp_frontend_product_cat', true, $cat->term_id, $taxonomy)) {
                echo '<option value="' . esc_attr($cat->term_id) . '"' . selected(in_array($cat->term_id, $categories), true, false) . '>' . $nbsp . esc_html($cat->name) . '</option>';
            }
            $product_child_categories = get_terms($taxonomy, 'orderby=name&hide_empty=0&parent=' . absint($cat->term_id));
            if ($product_child_categories) {
                $this->generateTaxonomyHTML($taxonomy, $product_child_categories, $categories, $nbsp . '&nbsp;&nbsp;');
            }
        }
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
