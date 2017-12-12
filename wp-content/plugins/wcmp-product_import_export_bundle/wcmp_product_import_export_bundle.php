<?php
/**
 * Plugin Name: WCMp Vendor Product Import Export
 * Plugin URI: http://wc-marketplace.com/product/wcmp-vendor-product-import-export/
 * Description: The WCMp Vendor Product Import Export add-on makes bulk product import a breeze for your vendors.
 * Author: WC Marketplace, The Grey Parrots
 * Version: 1.0.6
 * Author URI: https://wc-marketplace.com/
 * 
 * Text Domain: wcmp-product-import-export-bundle
 * Domain Path: /languages/
 */

if(!defined('ABSPATH')) exit; // Exit if accessed directly
if(class_exists('WCMp')) {
	if ( ! class_exists( 'WC_Import_Export_Dependencies' ) ) {
		require_once trailingslashit(dirname(__FILE__)).'includes/class-wcmp-import-export-bundle-dependencies.php';		
	}	
	require_once trailingslashit(dirname(__FILE__)).'includes/wcmp-product-import-export-bundle-core-functions.php';
	require_once trailingslashit(dirname(__FILE__)).'product-import-export-bundle-config.php';
	
	
	if(!defined('WCMP_PRODUCT_IMPORT_EXPORT_BUNDLE_PLUGIN_TOKEN')) exit;
	if(!defined('WCMP_PRODUCT_IMPORT_EXPORT_BUNDLE_TEXT_DOMAIN')) exit;
	
	if(!WC_Import_Export_Dependencies::woocommerce_plugin_active_check()) {
		add_action( 'admin_notices', 'pie_woocommerce_inactive_notice' );
	}
	
	if(!WC_Import_Export_Dependencies::wc_marketplace_plugin_active_check()) {
		add_action( 'admin_notices', 'pie_wcmp_inactive_notice' );
	}
	
	if(!class_exists('WCMp_Product_Import_Export_Bundle')) { 
		require_once( trailingslashit(dirname(__FILE__)).'classes/class-wcmp-product-import-export-bundle.php' );
		global $WCMp_Product_Import_Export_Bundle;
		$WCMp_Product_Import_Export_Bundle = new WCMp_Product_Import_Export_Bundle( __FILE__ );
		$GLOBALS['WCMp_Product_Import_Export_Bundle'] = $WCMp_Product_Import_Export_Bundle;	
		
		// Activation Hooks
		register_activation_hook( __FILE__, array('WCMp_Product_Import_Export_Bundle', 'activate_wcmp_product_import_export_bundle') );
		register_activation_hook( __FILE__, 'flush_rewrite_rules' );	
		
		// Deactivation Hooks
		register_deactivation_hook( __FILE__, array('WCMp_Product_Import_Export_Bundle', 'deactivate_wcmp_product_import_export_bundle') );
		
		if( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wcmp_import_export_action_links');
		}
	}
}
?>
