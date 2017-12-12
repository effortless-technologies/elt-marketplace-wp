<?php
/**
 * Plugin Name: WCMp Vendor Verification
 * Plugin URI: https://wc-marketplace.com/
 * Description: Verify vendors by their address, social presence and of course ID proof and let them show off the badge of  assurance they earned to let the consumers know of their authenticity.
 * Author: WC Marketplace, The Grey Parrots
 * Version: 1.0.0
 * Author URI: https://wc-marketplace.com/
 * Text Domain: wcmp-vendor-verification
 * Domain Path: /languages/
 */

if (!class_exists('WCMp_Vendor_Verification_Dependencies')) {
    require_once 'includes/class-wcmp-vendor-verification-dependencies.php';
}
require_once 'includes/wcmp-vendor-verification-core-functions.php';
require_once 'wcmp_vendor_verification_config.php';
if(!defined('ABSPATH')) exit; // Exit if accessed directly
if(!defined('WCMP_VENDOR_VERIFICATION_PLUGIN_TOKEN')) exit;
if(!defined('WCMP_VENDOR_VERIFICATION_TEXT_DOMAIN')) exit;
if(!WCMp_Vendor_Verification_Dependencies::wc_marketplace_plugin_active_check()) {
	add_action( 'admin_notices', 'wcmp_vendor_verification_wcmp_inactive_notice' );
} else {
    if(!class_exists('WCMP_Vendor_Verification')) {
        require_once( 'classes/class-wcmp-vendor-verification.php' );
        global $WCMP_Vendor_Verification;
        $WCMP_Vendor_Verification = new WCMP_Vendor_Verification( __FILE__ );
        $GLOBALS['WCMP_Vendor_Verification'] = $WCMP_Vendor_Verification;

        // Activation Hooks
        register_activation_hook( __FILE__, array('WCMP_Vendor_Verification', 'activate_wcmp_vendor_verification') );
        register_activation_hook( __FILE__, 'flush_rewrite_rules' );
        // Deactivation Hooks
        register_deactivation_hook( __FILE__, array('WCMP_Vendor_Verification', 'deactivate_wcmp_vendor_verification') );
    }
}

?>
