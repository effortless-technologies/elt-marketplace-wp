<?php
/**
 * Plugin Name: WCMp Frontend Manager
 * Plugin URI: https://wc-marketplace.com/product/wcmp-frontend-product-manager/
 * Description: Allow your vendors to manage their individual shops from the front end using the WCMp Frontend Manager.
 * Author: WC Marketplace, Arim Ghosh
 * Version: 2.1.5
 * Author URI: https://wc-marketplace.com
 *
 * Text Domain: wcmp_frontend_product_manager
 * Domain Path: /languages/
 *
 */

if(!defined('ABSPATH')) exit; // Exit if accessed directly

if(class_exists('WCMp')) {

	if ( ! class_exists( 'WCMp_Frontend_Product_Manager_Dependencies' ) )
		require_once 'includes/class-wcmp-frontend-product-manager-dependencies.php';
	require_once 'includes/wcmp-frontend-product-manager-core-functions.php';
	require_once 'frontend_product_manager_config.php';
	
	if(!defined('WCMP_FRONTEND_PRODUCT_MANAGER_PLUGIN_TOKEN')) exit;
	if(!defined('WCMP_FRONTEND_PRODUCT_MANAGER_TEXT_DOMAIN')) exit;
	
	
	if(!WCMp_Frontend_Product_Manager_Dependencies::woocommerce_plugin_active_check()) {
		add_action( 'admin_notices', 'fpm_woocommerce_inactive_notice' );
	}
	
	if(!WCMp_Frontend_Product_Manager_Dependencies::wc_marketplace_plugin_active_check()) {
		add_action( 'admin_notices', 'fpm_wcmp_inactive_notice' );
	}
	
	if(!class_exists('WCMp_Frontend_Product_Manager')) {
		require_once( 'classes/class-wcmp-frontend-product-manager.php' );
		global $WCMp_Frontend_Product_Manager;
		$WCMp_Frontend_Product_Manager = new WCMp_Frontend_Product_Manager( __FILE__ );
		$GLOBALS['WCMp_Frontend_Product_Manager'] = $WCMp_Frontend_Product_Manager;
		
		// Activation Hooks
		register_activation_hook( __FILE__, array('WCMp_Frontend_Product_Manager', 'activate_wcmp_frontend_product_manager') );
		register_activation_hook( __FILE__, 'flush_rewrite_rules' );
		
		// Deactivation Hooks
		register_deactivation_hook( __FILE__, array('WCMp_Frontend_Product_Manager', 'deactivate_wcmp_frontend_product_manager') );
	}
}
?>