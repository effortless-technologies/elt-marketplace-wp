<?php
if(!function_exists('fpm_woocommerce_inactive_notice')) {
	function fpm_woocommerce_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sAdvanced Frontend Manager is inactive.%s The %sWooCommerce plugin%s must be active for the Advanced Frontend Manager to work. Please %sinstall & activate WooCommerce%s', WCMP_FRONTEND_PRODUCT_MANAGER_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=woocommerce' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('fpm_wcmp_inactive_notice')) {
	function fpm_wcmp_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sAdvanced Frontend Manager is inactive.%s The %sWC Marketplace%s must be active for the Advanced Frontend Manager to work. Please %sinstall, activate & make sure WC Marketplace%s is updated', WCMP_FRONTEND_PRODUCT_MANAGER_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/dc-woocommerce-multi-vendor/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=wc+marketplace' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('is_wcmp_pass_notice')) {
	function is_wcmp_pass_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( 'Warning! This version of Frontend Manager is not compatible with older version of WC Marketplace. Please update to WCMp 3.0 or later.', WCMP_FRONTEND_PRODUCT_MANAGER_TEXT_DOMAIN ) ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('is_import_export_pass_notice')) {
	function is_import_export_pass_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( 'Advanced Frontend Manager is inactive! Not compatible with "WCMp Vendor Product Import Export", which is obsolete. Please note, AFM supports "product import and export" / "other product types" from vendor frontend. Remove the outdated add-on and try again. ', WCMP_FRONTEND_PRODUCT_MANAGER_TEXT_DOMAIN ) ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('is_pts_pass_notice')) {
	function is_pts_pass_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( 'Advanced Frontend Manager is inactive! Not compatible with "WCMp Advanced Product Types", which is obsolete. Please note, AFM supports "product import and export" / "other product types" from vendor frontend. Remove the outdated add-on and try again. ', WCMP_FRONTEND_PRODUCT_MANAGER_TEXT_DOMAIN ) ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('is_forntend_product_manager_page')) {
	function is_forntend_product_manager_page() {
		$pages = get_option("wcmp_vendor_general_settings_name");
		if(isset($pages['frontend_product_manager'])) {
			return is_page( $pages['frontend_product_manager'] ) ? true : false;
		}
		return false;
	}
}

if(!function_exists('get_forntend_product_manager_page')) {
	function get_forntend_product_manager_page() {
		$pages = get_option("wcmp_vendor_general_settings_name");
		if(isset($pages['frontend_product_manager'])) {
			return get_permalink( $pages['frontend_product_manager'] );
		}
		return false;
	}
}

if(!function_exists('is_vendor_products_page')) {
	function is_vendor_products_page() {
		$pages = get_option("wcmp_vendor_general_settings_name");
		if(isset($pages['wcmp_pending_products'])) {
			return is_page( $pages['wcmp_pending_products'] ) ? true : false;
		}
		return false;
	}
}

if(!function_exists('get_vendor_products_page')) {
	function get_vendor_products_page() {
		$pages = get_option("wcmp_vendor_general_settings_name");
		if(isset($pages['wcmp_pending_products'])) {
			return get_permalink( $pages['wcmp_pending_products'] );
		}
		return false;
	}
}

if(!function_exists('is_vendor_coupons_page')) {
	function is_vendor_coupons_page() {
		$pages = get_option("wcmp_vendor_general_settings_name");
		if(isset($pages['wcmp_coupons'])) {
			return is_page( $pages['wcmp_coupons'] ) ? true : false;
		}
		return false;
	}
}

if(!function_exists('get_vendor_coupons_page')) {
	function get_vendor_coupons_page() {
		$pages = get_option("wcmp_vendor_general_settings_name");
		if(isset($pages['wcmp_coupons'])) {
			return get_permalink( $pages['wcmp_coupons'] );
		}
		return false;
	}
}

if(!function_exists('is_frontend_coupon_manager_page')) {
	function is_frontend_coupon_manager_page() {
		$pages = get_option("wcmp_vendor_general_settings_name");
		if(isset($pages['frontend_coupon_manager'])) {
			return is_page( $pages['frontend_coupon_manager'] ) ? true : false;
		}
		return false;
	}
}

if(!function_exists('get_frontend_coupon_manager_page')) {
	function get_frontend_coupon_manager_page() {
		$pages = get_option("wcmp_vendor_general_settings_name");
		if(isset($pages['frontend_coupon_manager'])) {
			return get_permalink( $pages['frontend_coupon_manager'] );
		}
		return false;
	}
}

if(!function_exists('get_forntend_product_manager_messages')) {
	function get_forntend_product_manager_messages() {
		global $WCMp, $WCMp_Frontend_Product_Manager;
		
		$messages = array(
											'no_title' => __('Insert Product Title before submit.', 'wcmp-frontend_product_manager'),
											'sku_unique' => __('Product SKU must be unique.', 'wcmp-frontend_product_manager'),
											'variation_sku_unique' => __('Variation SKU must be unique.', 'wcmp-frontend_product_manager'),
											'product_saved' => __('Product Successfully Saved.', 'wcmp-frontend_product_manager'),
											'product_published' => __('Product Successfully Published.', 'wcmp-frontend_product_manager'),
											);
		
		return $messages;
	}
}

if(!function_exists('get_forntend_coupon_manager_messages')) {
	function get_forntend_coupon_manager_messages() {
		global $WCMp, $WCMp_Frontend_Product_Manager;
		
		$messages = array(
											'no_title' => __('Insert Coupon Title before submit.', 'wcmp-frontend_product_manager'),
											'coupon_saved' => __('Coupon Successfully Saved.', 'wcmp-frontend_product_manager'),
											'coupon_published' => __('Coupon Successfully Published.', 'wcmp-frontend_product_manager'),
											);
		
		return $messages;
	}
}

if( !function_exists( 'wcmp_forntend_manager_is_booking' ) ) {
	function wcmp_forntend_manager_is_booking() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if (is_multisite())
			$active_plugins = array_merge( $active_plugins, get_site_option('active_sitewide_plugins', array() ) );
		
		// WC Booking Check
		$is_booking = ( in_array( 'woocommerce-bookings/woocommerce-bookings.php', $active_plugins ) || array_key_exists( 'woocommerce-bookings/woocommerce-bookings.php', $active_plugins ) ) ? 'wcbooking' : false;
		
		return $is_booking;
	}
}

if( !function_exists( 'wcmp_forntend_manager_is_subscription' ) ) {
	function wcmp_forntend_manager_is_subscription() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if (is_multisite())
			$active_plugins = array_merge( $active_plugins, get_site_option('active_sitewide_plugins', array() ) );
		
		// WC Subscriptions Check
		$is_subscriptions = ( in_array( 'woocommerce-subscriptions/woocommerce-subscriptions.php', $active_plugins ) || array_key_exists( 'woocommerce-subscriptions/woocommerce-subscriptions.php', $active_plugins ) ) ? 'wcsubscriptions' : false;
		
		return $is_subscriptions;
	}
}

if( !function_exists( 'wcmp_forntend_manager_is_bundle' ) ) {
  function wcmp_forntend_manager_is_bundle() {
    $active_plugins = (array) get_option( 'active_plugins', array() );
    if (is_multisite())
      $active_plugins = array_merge( $active_plugins, get_site_option('active_sitewide_plugins', array() ) );
    
    // WC Bundle Check
    $is_bundle = ( in_array( 'woocommerce-product-bundles/woocommerce-product-bundles.php', $active_plugins ) || array_key_exists( 'woocommerce-product-bundles/woocommerce-product-bundles.php', $active_plugins ) ) ? 'wcbundle' : false;
    
    return $is_bundle;
  }
}

if( !function_exists( 'wcmp_forntend_manager_is_yithauction' ) ) {
	function wcmp_forntend_manager_is_yithauction() {

		$active_plugins = (array) get_option( 'active_plugins', array() );
		if (is_multisite())
			$active_plugins = array_merge( $active_plugins, get_site_option('active_sitewide_plugins', array() ) );
		//print_r($active_plugins);
		// YITH Auctions Check
		$is_yithauction = ( in_array( 'yith-woocommerce-auctions-premium/init.php', $active_plugins ) || array_key_exists( 'yith-woocommerce-auctions-premium/init.php', $active_plugins ) ) ? 'yithauction' : false;
		
		return $is_yithauction;

	}
}

if( !function_exists( 'wcmp_forntend_manager_is_wcsauction' ) ) {
	function wcmp_forntend_manager_is_wcsauction() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if (is_multisite())
			$active_plugins = array_merge( $active_plugins, get_site_option('active_sitewide_plugins', array() ) );
		
		// YITH Auctions Check
		$is_yithauction = ( in_array( 'woocommerce-simple-auctions/woocommerce-simple-auctions.php', $active_plugins ) || array_key_exists( 'woocommerce-simple-auctions/woocommerce-simple-auctions.php', $active_plugins ) ) ? 'yithauction' : false;
		
		return $is_yithauction;
	}
}

if( !function_exists( 'wcmp_forntend_manager_has_wcaddons' ) ) {
	function wcmp_forntend_manager_has_wcaddons() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if (is_multisite())
			$active_plugins = array_merge( $active_plugins, get_site_option('active_sitewide_plugins', array() ) );
		
		// YITH Auctions Check
		$is_yithauction = ( in_array( 'woocommerce-product-addons/woocommerce-product-addons.php', $active_plugins ) || array_key_exists( 'woocommerce-product-addons/woocommerce-product-addons.php', $active_plugins ) ) ? 'yithauction' : false;
		
		return $is_yithauction;
	}
}

if( !function_exists( 'wcmp_forntend_manager_is_booking' ) ) {
	function wcmp_forntend_manager_is_booking() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if (is_multisite())
			$active_plugins = array_merge( $active_plugins, get_site_option('active_sitewide_plugins', array() ) );
		
		// WC Booking Check
		$is_booking = ( in_array( 'woocommerce-bookings/woocommerce-bookings.php', $active_plugins ) || array_key_exists( 'woocommerce-bookings/woocommerce-bookings.php', $active_plugins ) ) ? 'wcbooking' : false;
		
		return $is_booking;
	}
}

if( !function_exists( 'wcmp_forntend_manager_is_accommodation_booking' ) ) {
	function wcmp_forntend_manager_is_accommodation_booking() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if (is_multisite())
			$active_plugins = array_merge( $active_plugins, get_site_option('active_sitewide_plugins', array() ) );
		
		// WC Booking Check
		$is_booking = ( in_array( 'woocommerce-accommodation-bookings/woocommerce-accommodation-bookings.php', $active_plugins ) || array_key_exists( 'woocommerce-accommodation-bookings/woocommerce-accommodation-bookings.php', $active_plugins ) ) ? 'wcaccommodationbooking' : false;
		
		return $is_booking;
	}
}

if( !function_exists( 'wcmp_forntend_manager_is_rental' ) ) {
	function wcmp_forntend_manager_is_rental() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if (is_multisite())
			$active_plugins = array_merge( $active_plugins, get_site_option('active_sitewide_plugins', array() ) );
		
		// WC Rental & Booking Check
		$is_rental = ( in_array( 'booking-and-rental-system-woocommerce/redq-rental-and-bookings.php', $active_plugins ) || array_key_exists( 'booking-and-rental-system-woocommerce/redq-rental-and-bookings.php', $active_plugins ) ) ? 'wcrental' : false;
		
		return $is_rental;
	}
}
if( !function_exists( 'wcmp_forntend_manager_is_bundle' ) ) {
  function wcmp_forntend_manager_is_bundle() {
    $active_plugins = (array) get_option( 'active_plugins', array() );
    if (is_multisite())
      $active_plugins = array_merge( $active_plugins, get_site_option('active_sitewide_plugins', array() ) );
    
    // WC Bundle Check
    $is_bundle = ( in_array( 'woocommerce-product-bundles/woocommerce-product-bundles.php', $active_plugins ) || array_key_exists( 'woocommerce-product-bundles/woocommerce-product-bundles.php', $active_plugins ) ) ? 'wcbundle' : false;
    
    return $is_bundle;
  }
}

if( !function_exists( 'wcmp_forntend_manager_is_subscription' ) ) {
	function wcmp_forntend_manager_is_subscription() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if (is_multisite())
			$active_plugins = array_merge( $active_plugins, get_site_option('active_sitewide_plugins', array() ) );
		
		// WC Subscriptions Check
		$is_subscriptions = ( in_array( 'woocommerce-subscriptions/woocommerce-subscriptions.php', $active_plugins ) || array_key_exists( 'woocommerce-subscriptions/woocommerce-subscriptions.php', $active_plugins ) ) ? 'wcsubscriptions' : false;
		
		return $is_subscriptions;
	}
}




function get_all_variations( $product_id ) {

  /**
   * Filters variation query args to get variations for a variable product.
   *
   * @since 2.3.0
   * @param array $args get_posts args
   */
  $args =  array(
    'post_parent' => $product_id,
    'post_type'   => 'product_variation',
    'orderby'     => 'menu_order',
    'order'       => 'ASC',
    'fields'      => 'ids',
    'post_status' => array( 'publish', 'private' ),
    'numberposts' => -1,
  ) ;

  return get_posts( $args );
}
?>