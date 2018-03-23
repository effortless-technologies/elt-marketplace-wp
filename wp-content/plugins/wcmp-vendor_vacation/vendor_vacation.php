<?php
/**
 * Plugin Name: WCMp Vendor Vacation
 * Plugin URI: https://wc-marketplace.com/
 * Description: Vendor vacation for WCMp vendors
 * Author: WC Marketplace, The Grey Parrots
 * Version: 1.1.0
 * Author URI: https://wc-marketplace.com/
 * Text Domain: wcmp-vendor_vacation
 * Domain Path: /languages/
 */

if (!class_exists('WCMp_Vendor_Vacation_Dependencies')) {
    require_once 'includes/class-dc-dependencies.php';
}
require_once 'includes/wcmp-vendor-vacation-core-functions.php';
require_once 'vendor_vacation_config.php';
if(!defined('ABSPATH')) exit; // Exit if accessed directly
if(!defined('WCMP_VENDOR_VACATION_PLUGIN_TOKEN')) exit;
if(!defined('WCMP_VENDOR_VACATION_TEXT_DOMAIN')) exit;
if(!WCMp_Vendor_Vacation_Dependencies::wc_marketplace_plugin_active_check()) {
	add_action( 'admin_notices', 'wcmp_vendor_vacation_wcmp_inactive_notice' );
} else {
    if(!class_exists('WCMP_Vendor_Vacation')) {
        require_once( 'classes/class-wcmp-vendor-vacation.php' );
        global $WCMP_Vendor_Vacation;
        $WCMP_Vendor_Vacation = new WCMP_Vendor_Vacation( __FILE__ );
        $GLOBALS['WCMP_Vendor_Vacation'] = $WCMP_Vendor_Vacation;

        // Activation Hooks
        register_activation_hook( __FILE__, array('WCMP_Vendor_Vacation', 'activate_wcmp_vendor_vacation') );
        register_activation_hook( __FILE__, 'flush_rewrite_rules' );

        // Deactivation Hooks
        register_deactivation_hook( __FILE__, array('WCMP_Vendor_Vacation', 'deactivate_wcmp_vendor_vacation') );
    }
}

?>
