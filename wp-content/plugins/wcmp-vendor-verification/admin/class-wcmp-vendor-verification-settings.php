<?php

class WCMP_Vendor_Verification_Settings {

    private $tabs = array();
    private $options;
    private $tabsection_membership = array();

    /**
     * Start up
     */
    public function __construct() {
        global $WCMP_Vendor_Verification;
        add_filter('wcmp_tabs', array(&$this, 'add_membership_tab'), 15, 1);
        // Settings tabs
        add_action('settings_page_vendor_verification_tab_init', array(&$this,'wcmp_vendor_verification_tab_init'),10,1);
        
    }

    function add_membership_tab($tabs) {
        global $WCMP_Vendor_Verification;
        $tabs['vendor_verification'] = __('Vendor Verification', 'wcmp-vendor-verification');
        return $tabs;
    }


    function wcmp_vendor_verification_tab_init($tab) {
        global $WCMP_Vendor_Verification;
        $WCMP_Vendor_Verification->admin->load_class("settings-{$tab}", $WCMP_Vendor_Verification->plugin_path, $WCMP_Vendor_Verification->token);
        new WCMP_Vendor_Verification_Settings_Gneral($tab);
    }

}
