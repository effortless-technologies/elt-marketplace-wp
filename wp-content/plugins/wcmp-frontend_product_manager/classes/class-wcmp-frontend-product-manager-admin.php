<?php

class WCMp_Frontend_Product_Manager_Admin {

    public function __construct() {

        add_action('admin_enqueue_scripts', array(&$this, 'enqueue_admin_script'), 30);

        add_filter("settings_vendor_general_tab_options", array($this, "frontend_product_manager_settings_option"));

        add_filter("settings_vendor_general_tab_new_input", array($this, "frontend_product_manager_settings_sanitize"), 10, 2);
        
        add_filter('is_wcmp_backend_disabled',array(&$this,'is_wcmp_backend_disabled'));
    }

    /**
     * Settings panel of WCMp Frontend Product Manager Pages Settings
     *
     * @param settings_tab_options     WCMp Settings fields product tab fields
     * @return WCMp Vendor Stock Alert settings fields are merged
     */
    function frontend_product_manager_settings_option($settings_tab_options) {
        global $WCMp, $WCMp_Frontend_Product_Manager;

        $pages_array = array();
        $pages = get_pages();
        foreach ($pages as $page) {
            $pages_array[$page->ID] = $page->post_title;
        }

        $array_pages = get_option('wcmp_vendor_general_settings_name');

        // Product Manager Pages
        if (!isset($array_pages['frontend_product_manager']) || !isset($array_pages['wcmp_pending_products'])) {
            $array_pages['frontend_product_manager'] = get_option('wcmp_frontend_product_manager_page_id');
            $array_pages['wcmp_pending_products'] = get_option('wcmp_pending_products_page_id');
            update_option('wcmp_vendor_general_settings_name', $array_pages);
        }

        $settings_tab_options["sections"]["wcmp_pages_section"]["fields"]["frontend_product_manager"] = array('title' => __('Frontend Product Manager', 'wcmp_frontend_product_manager'), 'type' => 'select', 'options' => $pages_array, 'hints' => __('Choose your preferred page for Frontend Product Manager.', 'wcmp_frontend_product_manager'));
        $settings_tab_options["sections"]["wcmp_pages_section"]["fields"]["wcmp_pending_products"] = array('title' => __('Pending Product(s)', 'wcmp_frontend_product_manager'), 'type' => 'select', 'options' => $pages_array, 'hints' => __('Choose your preferred page for Vendor Pending Product(s).', 'wcmp_frontend_product_manager'));

        // Coupon Manager Pages
        if (!isset($array_pages['frontend_coupon_manager']) || !isset($array_pages['wcmp_coupons'])) {
            $array_pages['frontend_coupon_manager'] = get_option('wcmp_frontend_coupon_manager_page_id');
            $array_pages['wcmp_coupons'] = get_option('wcmp_coupons_page_id');
            update_option('wcmp_vendor_general_settings_name', $array_pages);
        }

        $settings_tab_options["sections"]["wcmp_pages_section"]["fields"]["frontend_coupon_manager"] = array('title' => __('Frontend Coupon Manager', 'wcmp_frontend_product_manager'), 'type' => 'select', 'options' => $pages_array, 'hints' => __('Choose your preferred page for Frontend Coupon Manager.', 'wcmp_frontend_product_manager'));
        $settings_tab_options["sections"]["wcmp_pages_section"]["fields"]["wcmp_coupons"] = array('title' => __('Coupon(s)', 'wcmp_frontend_product_manager'), 'type' => 'select', 'options' => $pages_array, 'hints' => __('Choose your preferred page for Vendor Coupon(s).', 'wcmp_frontend_product_manager'));

        return $settings_tab_options;
    }

    /**
     * Save settings of WCMp Vendor Frontend Product Manager Settings
     *
     * @param new_input	input     WCMp Settings inputs
     * @return WCMp Vendor Stock Alert settings inputs are merged
     */
    function frontend_product_manager_settings_sanitize($new_input, $input) {
        global $WCMp, $WCMp_Frontend_Product_Manager;

        if (isset($input['frontend_product_manager']))
            $new_input['frontend_product_manager'] = sanitize_text_field($input['frontend_product_manager']);

        if (isset($input['wcmp_pending_products']))
            $new_input['wcmp_pending_products'] = sanitize_text_field($input['wcmp_pending_products']);

        if (isset($input['frontend_coupon_manager']))
            $new_input['frontend_coupon_manager'] = sanitize_text_field($input['frontend_coupon_manager']);

        if (isset($input['wcmp_coupons']))
            $new_input['wcmp_coupons'] = sanitize_text_field($input['wcmp_coupons']);

        return $new_input;
    }

    /**
     * Admin Scripts
     */
    public function enqueue_admin_script() {
        global $WCMp, $woocommerce, $WCMp_Frontend_Product_Manager;
        $screen = get_current_screen();

        if (is_user_logged_in() && !current_user_can( 'administrator' ) )
            wp_enqueue_style('wcmp_fpm_admin_css', $WCMp_Frontend_Product_Manager->plugin_url . 'assets/admin/css/frontend_product_manager.css', array(), $WCMp_Frontend_Product_Manager->version);

        if (in_array($screen->id, array('woocommerce_page_wcmp-setting-admin'))) {
            wp_enqueue_script('wcmp_fpm_setting_restriction_js', $WCMp_Frontend_Product_Manager->plugin_url . 'assets/admin/js/fpm_setting_restriction.js', array('jquery'), $WCMp_Frontend_Product_Manager->version, true);
            wp_enqueue_style('wcmp_fpm_setting_restriction_css', $WCMp_Frontend_Product_Manager->plugin_url . 'assets/admin/css/fpm_setting_restriction.css', array(), $WCMp_Frontend_Product_Manager->version);
        }
    }
    /*
     * enable wcmp backend enable / disabled function
     */
    public function is_wcmp_backend_disabled($option){
        global $WCMp_Frontend_Product_Manager;
        $option['custom_tags'] = array();
        $option['desc'] = __('Offer a frontend single dashboard for all vendor purpose and eliminate their backend access requirement.','wcmp_frontend_product_manager');
        return $option;
    }

}
