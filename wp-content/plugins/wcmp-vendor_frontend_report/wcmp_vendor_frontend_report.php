<?php

/**
 * Plugin Name: WCMp Advanced Vendor Report
 * Plugin URI: http://wc-marketplace.com/product/wcmp-advanced-vendor-report/
 * Description: Give your seller a goldmine of information to work with. With this add-on, your seller can download a whole range of reports right from the front-end.
 * Author: WC Marketplace, The Grey Parrots
 * Version: 1.1.0
 * Author URI: https://wc-marketplace.com/
 * Requires at least: 4.0
 * Tested up to: 4.9.2
 * 
 * Text Domain: wcmp-vendor_frontend_report
 * Domain Path: /languages/
 */

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

if (class_exists('WCMp')) {
    if (!class_exists('WCMp_Vendor_Frontend_Report_Dependencies'))
        require_once 'includes/class-wcmp-vendor-frontend-report-dependencies.php';
    require_once 'includes/wcmp-vendor-frontend-report-core-functions.php';
    require_once 'wcmp-vendor_frontend_report-config.php';

    if (!defined('WCMP_VENDOR_FRONTEND_REPORT_PLUGIN_TOKEN'))
        exit;
    if (!defined('WCMP_VENDOR_FRONTEND_REPORT_TEXT_DOMAIN'))
        exit;

    if (!WCMp_Vendor_Frontend_Report_Dependencies::woocommerce_plugin_active_check()) {
        add_action('admin_notices', 'vfr_woocommerce_inactive_notice');
    }

    if (!WCMp_Vendor_Frontend_Report_Dependencies::wc_marketplace_plugin_active_check()) {
        add_action('admin_notices', 'vfr_wcmp_inactive_notice');
    }

    if (!class_exists('WCMP_Vendor_Frontend_Report')) {
        require_once( 'classes/class-wcmp-vendor-frontend-report.php' );
        global $WCMp_Vendor_Frontend_Report;
        $WCMp_Vendor_Frontend_Report = new WCMP_Vendor_Frontend_Report(__FILE__);
        $GLOBALS['WCMP_Vendor_Frontend_Report'] = $WCMp_Vendor_Frontend_Report;

        // Activation Hooks
        register_activation_hook(__FILE__, array('WCMP_Vendor_Frontend_Report', 'activate_wcmp_vendor_frontend_report'));
        register_activation_hook(__FILE__, 'flush_rewrite_rules');

        // Deactivation Hooks
        register_deactivation_hook(__FILE__, array('WCMP_Vendor_Frontend_Report', 'deactivate_wcmp_vendor_frontend_report'));
    }
}
?>