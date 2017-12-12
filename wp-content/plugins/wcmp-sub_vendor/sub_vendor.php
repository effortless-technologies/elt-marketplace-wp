<?php

/*
  Plugin Name: WCMp Vendor Staff
  Plugin URI: http://dualcube.com
  Description: Vendors can assign staff to manage different departments of their shop.
  Author: Dualcube
  Version: 1.0.1
  Author URI: http://dualcube.com
 */

if (!class_exists('WCMp_subvendor_Dependencies'))
    require_once 'includes/class-dc-dependencies.php';
require_once 'includes/wcmp-sub-vendor-core-functions.php';
require_once 'config.php';
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
if (!defined('WCMP_SUB_VENDOR_PLUGIN_TOKEN'))
    exit;
if (!defined('WCMP_SUB_VENDOR_TEXT_DOMAIN'))
    exit;

if (!WCMp_subvendor_Dependencies::woocommerce_plugin_active_check()) {
    add_action('admin_notices', 'wcmp_sub_vendor_woocommerce_inactive_notice');
} elseif (!WCMp_subvendor_Dependencies::wc_marketplace_plugin_active_check()) {
    add_action('admin_notices', 'wcmp_sub_vendor_wcmp_inactive_notice');
}
if (class_exists('WCMp')) {
    if (!class_exists('WCMP_Sub_Vendor')) {
        require_once( 'classes/class-wcmp-sub-vendor.php' );
        global $WCMP_Sub_Vendor;
        $WCMP_Sub_Vendor = new WCMP_Sub_Vendor(__FILE__);
        $GLOBALS['WCMP_Sub_Vendor'] = $WCMP_Sub_Vendor;
        // Activation Hooks
        register_activation_hook(__FILE__, array($WCMP_Sub_Vendor, 'activate_WCMP_Sub_Vendor'));
        register_activation_hook(__FILE__, 'flush_rewrite_rules');

        // Deactivation Hooks
        register_deactivation_hook(__FILE__, array($WCMP_Sub_Vendor, 'deactivate_WCMP_Sub_Vendor'));
    }
}