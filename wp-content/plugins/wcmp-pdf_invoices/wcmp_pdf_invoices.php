<?php
/**
 * Plugin Name: WCMp PDF Invoices
 * Plugin URI: https://wc-marketplace.com
 * Description: WCMp Vendor PDF Invoices
 * Author: WC Marketplace, The Grey Parrots
 * Version: 1.1.1
 * Author URI: https://wc-marketplace.com
 * Requires at least: 4.2
 * Tested up to: 4.8

 * Text Domain: wcmp-pdf_invoices
 * Domain Path: /languages/
 */

if ( ! class_exists( 'WCMp_Dependencies_PDF_Invoices' ) ){
	require_once 'includes/class-wcmp-pdf-invoices-dependencies.php';
}
require_once 'includes/wcmp-pdf-invoices-core-functions.php';
require_once 'wcmp_pdf_invoices_config.php';

if(!defined('ABSPATH')) exit; // Exit if accessed directly
if(!defined('WCMp_PDF_INVOICES_PLUGIN_TOKEN')) exit;
if(!defined('WCMp_PDF_INVOICES_TEXT_DOMAIN')) exit;

if(!WCMp_Dependencies_PDF_Invoices::woocommerce_plugin_active_check()) {
  add_action( 'admin_notices', 'woocommerce_inactive_notice' );
}

//register_activation_hook( __FILE__, 'flush_rewrite_rules' );
//register_activation_hook( __FILE__, 'activate_wcmp_pdf_invoicess' );
//register_activation_hook( __FILE__, 'flush_rewrite_rules' );

if(!WCMp_Dependencies_PDF_Invoices::wc_marketplace_plugin_active_check()) {
  add_action( 'admin_notices', 'wcmp_inactive_notice' );
}

if(!WCMp_Dependencies_PDF_Invoices::woocommerce_pdf_invoices_packing_slips_plugin_active_check()) {
  add_action( 'admin_notices', 'wpips_inactive_notice' );
}

if(!class_exists('WCMp_PDF_Invoices') && WCMp_Dependencies_PDF_Invoices::woocommerce_plugin_active_check() && WCMp_Dependencies_PDF_Invoices::wc_marketplace_plugin_active_check() && WCMp_Dependencies_PDF_Invoices::woocommerce_pdf_invoices_packing_slips_plugin_active_check()) {
	require_once( 'classes/class-wcmp-pdf-invoices.php' );
	global $WCMp_PDF_Invoices;
	$WCMp_PDF_Invoices = new WCMp_PDF_Invoices( __FILE__ );
	$GLOBALS['WCMp_PDF_Invoices'] = $WCMp_PDF_Invoices;
	//add_filter( 'settings_pages_tab_options', array('WCMp_PDF_Invoices', "pdf_invoices_settings_option")); 
	//add_filter( 'settings_pages_tab_new_input', array('WCMp_PDF_Invoices', "pdf_invoices_settings_sanitize"), 10, 2);
	// Activation Hooks
	register_activation_hook( __FILE__, array('WCMp_PDF_Invoices', 'activate_wcmp_pdf_invoices') );
	register_activation_hook( __FILE__, 'flush_rewrite_rules' );
	
	// Deactivation Hooks
	register_deactivation_hook( __FILE__, array('WCMp_PDF_Invoices', 'deactivate_wcmp_pdf_invoices') );
}
