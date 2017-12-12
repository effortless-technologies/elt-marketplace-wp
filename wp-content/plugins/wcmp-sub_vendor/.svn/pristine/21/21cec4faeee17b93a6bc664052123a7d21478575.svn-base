<?php

class WCMP_Sub_Vendor_Frontend {

    public function __construct() {
        //enqueue scripts
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_scripts'));
        

        add_action('wcmp_sub_vendor_frontend_hook', array(&$this, 'wcmp_sub_vendor_frontend_function'), 10, 2);
        $current_user = wp_get_current_user();
        foreach ($current_user->roles as $key => $value) {
            $user_role = $value;
            if (isset($user_role)) {
                if ($user_role == 'dc_sub_vendor') {
                    add_filter('is_wcmp_show_store_settings',array(&$this,'is_wcmp_show_store_settings_callback'));
                    add_filter('is_wcmp_show_product_tab',array(&$this,'is_wcmp_show_product_tab_callback'));
                    add_filter('is_wcmp_show_report_tab',array(&$this,'is_wcmp_show_report_tab_callback'));
                    add_filter('is_wcmp_show_order_tab',array(&$this,'is_wcmp_show_order_tab_callback'));
                    add_filter('is_wcmp_show_payment_tab',array(&$this,'is_wcmp_show_payment_tab_callback'));
                    add_filter('wcmp_show_vendor_announcements',array(&$this,'wcmp_show_vendor_announcements_callback'));
                    add_filter('wcmp_vendor_shop_permalink',array(&$this,'wcmp_vendor_shop_permalink_callback'));
                    //enqueue styles
                    add_action('wp_enqueue_scripts', array(&$this, 'frontend_styles'));
                }
            }
        }
        

        $current_user = wp_get_current_user();
        foreach ($current_user->roles as $key => $value) {

            $user_role = $value;
            if (isset($user_role)) {
                if ($user_role == 'dc_sub_vendor') {
                    add_filter('is_user_wcmp_vendor', array(&$this, 'is_user_wcmp_vendor_callback'), 20, 2);
                }
            }
        }
    }
    function wcmp_vendor_shop_permalink_callback($permalink){
        $user_id = get_current_user_id();
        $permalink = wcmp_report_vendor($user_id)->permalink;
        return $permalink;
    }
    
    function wcmp_show_vendor_announcements_callback($show){
        return false;
    }
    
    function is_wcmp_show_payment_tab_callback($show){
        $current_user = wp_get_current_user();
        if(!user_can( $current_user, 'manage_payment' )){
            $show = false;
        }
        return $show;
    }
    function is_wcmp_show_order_tab_callback($show){
        $current_user = wp_get_current_user();
        if(!user_can( $current_user, 'manage_woocommerce_orders' )){
            $show = false;
        }
        return $show;
    }
    function is_wcmp_show_report_tab_callback($show){
        $current_user = wp_get_current_user();
        if(!user_can( $current_user, 'view_woocommerce_reports' )){
            $show = false;
        }
        return $show;
    }
    function is_wcmp_show_product_tab_callback($show){
        $current_user = wp_get_current_user();
        if(!user_can( $current_user, 'manage_product' )){
            $show = false;
        }
        return $show;
    }
    function is_wcmp_show_store_settings_callback($show){
        return false;
    }
    function frontend_scripts() {
        global $WCMP_Sub_Vendor;
        $frontend_script_path = $WCMP_Sub_Vendor->plugin_url . 'assets/frontend/js/';
        $frontend_script_path = str_replace(array('http:', 'https:'), '', $frontend_script_path);
        $pluginURL = str_replace(array('http:', 'https:'), '', $WCMP_Sub_Vendor->plugin_url);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        // Enqueue your frontend javascript from here
    }

    function frontend_styles() {
        global $WCMP_Sub_Vendor;
        $frontend_style_path = $WCMP_Sub_Vendor->plugin_url . 'assets/frontend/css/';
        $frontend_style_path = str_replace(array('http:', 'https:'), '', $frontend_style_path);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        // Enqueue your frontend stylesheet from here
        wp_enqueue_style('wcmp_vendor_staff_frontend_css',$frontend_style_path.'frontend.css');
    }

    function dc_wcmp_sub_vendor_frontend_function() {
        // Do your frontend work here
    }

    function is_user_wcmp_vendor_callback($is_user_wcmp_vendor, $user) {
        if (!$is_user_wcmp_vendor) {
            $is_user_wcmp_sub_vendor = ( is_array($user->roles) && in_array('dc_sub_vendor', $user->roles) );
            return $is_user_wcmp_sub_vendor;
        }
        return $is_user_wcmp_vendor;
    }

}
