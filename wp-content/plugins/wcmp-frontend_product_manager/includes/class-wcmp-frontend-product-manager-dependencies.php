<?php
/**
 * WC Dependency Checker
 *
 */
class WCMp_Frontend_Product_Manager_Dependencies {
	
	private static $active_plugins;
	
	static function init() {
		self::$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() )
			self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
	}
	
	static function woocommerce_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce/woocommerce.php', self::$active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', self::$active_plugins );
		return false;
	}
	
	// WC Marketplace
	static function wc_marketplace_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'dc-woocommerce-multi-vendor/dc_product_vendor.php', self::$active_plugins ) || array_key_exists( 'dc-woocommerce-multi-vendor/dc_product_vendor.php', self::$active_plugins );
		return false;
	}
	
	// Yoast SEO
	static function fpm_yoast_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'wordpress-seo/wp-seo.php', self::$active_plugins ) || array_key_exists( 'wordpress-seo/wp-seo.php', self::$active_plugins );
		return false;
	}
	
	// WooCommerce Custom Product Tabs Lite
	static function fpm_wc_tabs_lite_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-custom-product-tabs-lite/woocommerce-custom-product-tabs-lite.php', self::$active_plugins ) || array_key_exists( 'woocommerce-custom-product-tabs-lite/woocommerce-custom-product-tabs-lite.php', self::$active_plugins );
		return false;
	}
	
	// WooCommerce Product FEES
	static function fpm_wc_product_fees_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-product-fees/woocommerce-product-fees.php', self::$active_plugins ) || array_key_exists( 'woocommerce-product-fees/woocommerce-product-fees.php', self::$active_plugins );
		return false;
	}
	
	// WooCommerce Bulk Discount
	static function fpm_wc_bulk_discount_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-bulk-discount/woocommerce-bulk-discount.php', self::$active_plugins ) || array_key_exists( 'woocommerce-bulk-discount/woocommerce-bulk-discount.php', self::$active_plugins );
		return false;
	}
	
	// MapPress
	static function fpm_mappress_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'mappress-google-maps-for-wordpress/mappress.php', self::$active_plugins ) || array_key_exists( 'mappress-google-maps-for-wordpress/mappress.php', self::$active_plugins );
		return false;
	}
	
	// Toolset Types
	static function fpm_toolset_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'types/wpcf.php', self::$active_plugins ) || array_key_exists( 'types/wpcf.php', self::$active_plugins );
		return false;
	}
	
	// Advanced Custom Field
	static function fpm_acf_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'advanced-custom-fields/acf.php', self::$active_plugins ) || array_key_exists( 'advanced-custom-fields/acf.php', self::$active_plugins );
		return false;
	}
}