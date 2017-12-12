<?php
/*
Plugin Name: WCMp Vendor SEO and Analytics
Plugin URI: http://wc-marketplace.com/product/wcmp-vendor-seo-and-analytics/
Description: This Plugin will help to seo optimize by vendor on his/her product pages and shop pages and they can install his/her own google analytics for treffic report by google analytics.
Author: DualCube
Version: 1.0.0
Author URI: http://dualcube.com
*/

if(!defined('ABSPATH')) exit; // Exit if accessed directly

if(class_exists('WCMp')) {

	if ( ! class_exists( 'WCMp_Vendor_Shop_SEO_Dependencies' ) )
		require_once trailingslashit(dirname(__FILE__)).'includes/class-wcmp-vendor-shop-seo-dependencies.php';
	require_once trailingslashit(dirname(__FILE__)).'includes/wcmp-vendor-shop-seo-core-functions.php';
	require_once trailingslashit(dirname(__FILE__)).'wcmp_vendor_shop_seo_config.php';
	
	if(!defined('WCMP_VENDOR_SHOP_SEO_PLUGIN_TOKEN')) exit;
	if(!defined('WCMP_VENDOR_SHOP_SEO_TEXT_DOMAIN')) exit;

	if(!WCMp_Vendor_Shop_SEO_Dependencies::woocommerce_plugin_active_check()) {
		add_action( 'admin_notices', 'vss_woocommerce_inactive_notice' );
	}
	
	if(!WCMp_Vendor_Shop_SEO_Dependencies::wc_marketplace_plugin_active_check()) {
		add_action( 'admin_notices', 'vss_wcmp_inactive_notice' );
	}

  if(!class_exists('WCMp_Vendor_Shop_SEO')) {
    require_once( trailingslashit(dirname(__FILE__)).'classes/class-wcmp-vendor-shop-seo.php' );
    global $WCMp_Vendor_Shop_SEO;
    $WCMp_Vendor_Shop_SEO = new WCMp_Vendor_Shop_SEO( __FILE__ );
    $GLOBALS['WCMp_Vendor_Shop_SEO'] = $WCMp_Vendor_Shop_SEO;

    // Activation Hooks
    register_activation_hook( __FILE__, array('WCMP_Vendor_Shop_Seo', 'activate_wcmp_vendor_shop_seo') );
    register_activation_hook( __FILE__, 'flush_rewrite_rules' );

    // Deactivation Hooks
    register_deactivation_hook( __FILE__, array('WCMP_Vendor_Shop_Seo', 'deactivate_wcmp_vendor_shop_seo') );
  }
}
?>