<?php

class WCMP_Vendor_Membership_Settings {
    
    private $tabsection_membership = array();

    /**
     * Start up
     */
    public function __construct() {
        add_filter('wcmp_tabs', array(&$this, 'add_membership_tab'), 15, 1);

        // Settings tabs
        add_action('settings_page_membership_tab_init', array(&$this, 'membership_general_tab_init'), 10, 1);
        add_action('settings_page_membership_payment_tab_init', array(&$this, 'membership_payment_tab_init'), 10, 2);
        add_action('settings_page_membership_plan_design_tab_init', array(&$this, 'membership_plan_design_tab_init'), 10, 2);
        $this->tabsection_membership = apply_filters('wcmp_tabsection_membership', array(
            'general' => array('title' => __('General', 'wcmp-vendor_membership')),
            'payment' => array('title' => __('Payment Settings', 'wcmp-vendor_membership')),
            'plan_design' => array('title' => __('Template Design', 'wcmp-vendor_membership'))
        ));

        add_filter('wcmp_get_subtabs', array(&$this, 'wcmp_membership_subtabs'), 10, 2);
        add_filter('is_wcmp_tab_has_subtab', array(&$this, 'is_wcmp_tab_has_subtab'));
        add_filter('wcmp_subtab_init_exclude_list', array(&$this, 'wcmp_subtab_init_exclude_list'), 10, 2);
    }

    public function wcmp_subtab_init_exclude_list($exclude_list, $tab) {
        if ('membership' === $tab) {
            $exclude_list = array_diff($exclude_list, array('payment'));
        }
        return $exclude_list;
    }

    public function wcmp_membership_subtabs($subtabs, $tab) {
        if ('membership' === $tab) {
            $subtabs = $this->tabsection_membership;
        }
        return $subtabs;
    }

    public function is_wcmp_tab_has_subtab($tabs) {
        $tabs[] = 'membership';
        return $tabs;
    }

    public function add_membership_tab($tabs) {
        $tabs['membership'] = __('Membership', 'wcmp-vendor_membership');
        return $tabs;
    }

    public function membership_general_tab_init($tab) {
        global $WCMP_Vendor_Membership;
        $WCMP_Vendor_Membership->admin->load_class("settings-{$tab}", $WCMP_Vendor_Membership->plugin_path, $WCMP_Vendor_Membership->token);
        new WCMP_Vendor_General_Settings_Gneral($tab);
    }

    public function membership_payment_tab_init($tab, $subsection) {
        global $WCMP_Vendor_Membership;
        $WCMP_Vendor_Membership->admin->load_class("settings-{$tab}-{$subsection}", $WCMP_Vendor_Membership->plugin_path, $WCMP_Vendor_Membership->token);
        new WCMP_Vendor_Membership_Settings_Gneral($tab, $subsection);
    }

    public function membership_plan_design_tab_init($tab, $subsection) {
        global $WCMP_Vendor_Membership;
        $WCMP_Vendor_Membership->admin->load_class("settings-{$tab}-{$subsection}", $WCMP_Vendor_Membership->plugin_path, $WCMP_Vendor_Membership->token);
        new WCMP_Vendor_Plan_Design_Settings_Gneral($tab, $subsection);
    }

}
