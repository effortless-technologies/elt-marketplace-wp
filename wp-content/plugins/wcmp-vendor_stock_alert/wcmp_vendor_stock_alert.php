<?php
/**
 * Plugin Name: WCMp Vendor Stock Alert
 * Plugin URI: http://wc-marketplace.com/product/wcmp-product-stock-alert/
 * Description: This plugin will alert vendors when a product is low in stock, or out of stock
 * Author: WC Marketplace, The Grey Parrots
 * Version: 1.0.5
 * Author URI: https://wc-marketplace.com/
 * Text Domain: wcmp-vendor_stock_alert
 * Domain Path: /languages/
 */

if(!defined('ABSPATH')) exit; // Exit if accessed directly

if(class_exists('WCMp')) {
	if ( ! class_exists( 'WCMp_Vendor_Stock_Alert_Dependencies' ) )
		require_once 'includes/class-wcmp-stock-alert-dependencies.php';
	require_once 'includes/wcmp-vendor-stock-alert-core-functions.php';
	require_once 'config_vendor_stock_alert.php';
	
	if(!defined('WCMP_VENDOR_STOCK_ALERT_PLUGIN_TOKEN')) exit;
	if(!defined('WCMP_VENDOR_STOCK_ALERT_TEXT_DOMAIN')) exit;
	
	if(!WCMp_Vendor_Stock_Alert_Dependencies::woocommerce_plugin_active_check()) {
		add_action( 'admin_notices', 'vsa_woocommerce_inactive_notice' );
	}
	
	if(!WCMp_Vendor_Stock_Alert_Dependencies::wc_marketplace_plugin_active_check()) {
		add_action( 'admin_notices', 'vsa_wcmp_inactive_notice' );
	}
	
	if(!class_exists('WCMp_Vendor_Stock_Alert')) {
		require_once( 'classes/class-wcmp-vendor-stock-alert.php' );
		global $WCMp_Vendor_Stock_Alert;
		$WCMp_Vendor_Stock_Alert = new WCMp_Vendor_Stock_Alert( __FILE__ );
		$GLOBALS['WCMp_Vendor_Stock_Alert'] = $WCMp_Vendor_Stock_Alert;
		
		// Activation Hooks
		register_activation_hook( __FILE__, array('WCMp_Vendor_Stock_Alert', 'activate_wcmp_vendor_stock_alert') );
		register_activation_hook( __FILE__, 'flush_rewrite_rules' );
		
		// Deactivation Hooks
		register_deactivation_hook( __FILE__, array('WCMp_Vendor_Stock_Alert', 'deactivate_wcmp_vendor_stock_alert') );
	}
}
?>