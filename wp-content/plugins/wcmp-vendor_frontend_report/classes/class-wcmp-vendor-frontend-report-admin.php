<?php

class WCMP_Vendor_Frontend_Report_Admin {

    public $settings;

    public function __construct() {
        add_filter("settings_vendor_general_tab_options", array($this, "vendor_frontend_report_settings_option"));

        add_filter("settings_vendor_general_tab_new_input", array($this, "vendor_frontend_report_settings_sanitize"), 10, 2);
    }

    /**
     * Settings panel of WCMp Vendor Frontend Report Pages Settings
     *
     * @param settings_tab_options     WCMp Settings fields product tab fields
     * @return WCMp Vendor Frontend Report settings fields are merged
     */
    function vendor_frontend_report_settings_option($settings_tab_options) {
        global $WCMp, $WCMp_Vendor_Frontend_Report;
        $pages = get_pages();
        $woocommerce_pages = array(wc_get_page_id('shop'), wc_get_page_id('cart'), wc_get_page_id('checkout'), wc_get_page_id('myaccount'));
        foreach ($pages as $page) {
            if (!in_array($page->ID, $woocommerce_pages)) {
                $pages_array[$page->ID] = $page->post_title;
            }
        }
        if(get_wcmp_vendor_settings('frontend_vendor_reports', 'pages')){
            update_wcmp_vendor_settings('frontend_vendor_reports', get_wcmp_vendor_settings('frontend_vendor_reports', 'pages'), 'vendor', 'general');
            delete_wcmp_vendor_settings('frontend_vendor_reports', 'pages');
        }
        if(!get_wcmp_vendor_settings('frontend_vendor_reports', 'vendor', 'general')){
            update_wcmp_vendor_settings('frontend_vendor_reports', get_option('wcmp_vendor_frontend_report_page_id'), 'vendor', 'general');
        }
        
        $settings_tab_options["sections"]["wcmp_pages_section"]["fields"]["frontend_vendor_reports"] = array('title' => __('Advanced Vendor Reports', 'wcmp-vendor_frontend_report'), 'type' => 'select', 'options' => $pages_array, 'hints' => __('Choose your preferred page for Vendor Frontend Report.', 'wcmp-vendor_frontend_report'));
        return $settings_tab_options;
    }

    /**
     * Save settings of WCMp Vendor Frontend Report Settings
     *
     * @param new_input	input     WCMp Settings inputs
     * @return WCMp Vendor Frontend Report settings inputs are merged
     */
    function vendor_frontend_report_settings_sanitize($new_input, $input) {
        global $WCMp, $WCMp_Vendor_Frontend_Report;

        if (isset($input['frontend_vendor_reports']))
            $new_input['frontend_vendor_reports'] = sanitize_text_field($input['frontend_vendor_reports']);

        return $new_input;
    }

}
