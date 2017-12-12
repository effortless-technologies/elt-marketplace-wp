<?php
if(!function_exists('fpm_woocommerce_inactive_notice')) {
	function fpm_woocommerce_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCMp Frontend Product Manager is inactive.%s The %sWooCommerce plugin%s must be active for the WCMp Frontend Product Manager to work. Please %sinstall & activate WooCommerce%s', WCMP_FRONTEND_PRODUCT_MANAGER_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=woocommerce' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('fpm_wcmp_inactive_notice')) {
	function fpm_wcmp_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCMp Frontend Product Manager is inactive.%s The %sWC Marketplace%s must be active for the WCMp Frontend Product Manager to work. Please %sinstall & activate WC Marketplace%s', WCMP_FRONTEND_PRODUCT_MANAGER_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/dc-woocommerce-multi-vendor/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=wc+marketplace' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
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
											'no_title' => __('Insert Product Title before submit.', 'wcmp_frontend_product_manager'),
											'sku_unique' => __('Product SKU must be unique.', 'wcmp_frontend_product_manager'),
											'variation_sku_unique' => __('Variation SKU must be unique.', 'wcmp_frontend_product_manager'),
											'product_saved' => __('Product Successfully Saved.', 'wcmp_frontend_product_manager'),
											'product_published' => __('Product Successfully Published.', 'wcmp_frontend_product_manager'),
											);
		
		return $messages;
	}
}

if(!function_exists('get_forntend_coupon_manager_messages')) {
	function get_forntend_coupon_manager_messages() {
		global $WCMp, $WCMp_Frontend_Product_Manager;
		
		$messages = array(
											'no_title' => __('Insert Coupon Title before submit.', 'wcmp_frontend_product_manager'),
											'coupon_saved' => __('Coupon Successfully Saved.', 'wcmp_frontend_product_manager'),
											'coupon_published' => __('Coupon Successfully Published.', 'wcmp_frontend_product_manager'),
											);
		
		return $messages;
	}
}
?>