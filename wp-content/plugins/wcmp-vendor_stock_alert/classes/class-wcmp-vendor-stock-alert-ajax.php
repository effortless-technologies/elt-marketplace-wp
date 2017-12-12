<?php
class WCMp_Vendor_Stock_Alert_Ajax {

	public function __construct() {
		add_action( 'woocommerce_reduce_order_stock', array($this, 'wcmp_vendor_alert') );
		
		// Low stock alert
		add_action( 'wcmp_low_stock', array($this, 'wcmp_low_stock'), 10, 2 );
		
		// Out of stock alert
		add_action( 'wcmp_out_of_stock', array($this, 'wcmp_out_of_stock'), 10, 2 );
	}

	/**
	 * This function is triggered when a customer orders
	 *
	 * @param order     Order object
	 * @return
	 */
	function wcmp_vendor_alert( $order ) {
		global $WCMp_Vendor_Stock_Alert;
		
		$order_id = $order->id;
		
		$order = wc_get_order( $order_id );
		$items = $order->get_items( 'line_item' );
		
		foreach( $items as $item_id => $item ) {
			$stock_alert_settings = $wcmp_stock_alert_settings = array();
			$product_id = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
			$product = wc_get_product($product_id);
			if( function_exists('get_wcmp_product_vendors') ) {
				$vendor = get_wcmp_product_vendors( $item['product_id'] );
				if( isset($vendor) && !empty($vendor) ) {
					$vendor_id = $vendor->id;
					
					$stock_alert_settings = get_user_meta( $vendor_id, 'wcmp_vendor_stock_alert_settings', true );
					$wcmp_stock_alert_settings = $WCMp_Vendor_Stock_Alert->wcmp_capabilities;

					if( isset($wcmp_stock_alert_settings['low_stock_enabled']) && $wcmp_stock_alert_settings['low_stock_enabled'] =='Enable' && isset($stock_alert_settings['low_stock_enabled']) && $stock_alert_settings['low_stock_enabled'] == 'Enable'){
						$low_stock_enabled = isset($stock_alert_settings['low_stock_enabled']) ? $stock_alert_settings['low_stock_enabled'] : '';
						$low_stock_limit = isset($stock_alert_settings['low_stock_limit']) ? $stock_alert_settings['low_stock_limit'] : '';
					}
					else{
						$low_stock_enabled = '';
						$low_stock_limit = '';
					}

					if( isset($wcmp_stock_alert_settings['out_of_stock_enabled']) && $wcmp_stock_alert_settings['out_of_stock_enabled'] =='Enable' && isset($stock_alert_settings['out_of_stock_enabled']) && $stock_alert_settings['out_of_stock_enabled'] == 'Enable'){
						$out_of_stock_enabled = isset($stock_alert_settings['out_of_stock_enabled']) ? $stock_alert_settings['out_of_stock_enabled'] : '';
						$out_of_stock_limit = isset($stock_alert_settings['out_of_stock_limit']) ? $stock_alert_settings['out_of_stock_limit'] : '';
					}
					else{
						$out_of_stock_enabled = '';
						$out_of_stock_limit = '';
					}
					
					if( isset($stock_alert_settings) || isset($wcmp_stock_alert_settings) ) {
						if( $low_stock_enabled == 'Enable' ) {
							$stock_quantity = $product->get_stock_quantity();
							
							if( $stock_quantity <= $low_stock_limit && $stock_quantity > $out_of_stock_limit ) {
								do_action( 'wcmp_low_stock', $item['product_id'], $product_id );
							}
						}
						
						if( $out_of_stock_enabled == 'Enable' ) {
							$stock_quantity = $product->get_stock_quantity();
						
							if( $stock_quantity <= $out_of_stock_limit ) {
								do_action( 'wcmp_out_of_stock', $item['product_id'], $product_id );
							}
						}
					}
				}
			}
		}
	}
	
	
	/**
	 * This function is triggered when a product reached low stock limit set by Admin or vendor
	 *
	 * @param product_id	parent_id     id of the product, id of the parent
	 * @return
	 */
	function wcmp_low_stock( $product_id, $_id ) {
		
		// WC Marketplace
		if( WCMp_Vendor_Stock_Alert_Dependencies::wc_marketplace_plugin_active_check() ) {
			if( function_exists('get_wcmp_product_vendors') ) {
				$vendor = get_wcmp_product_vendors( $product_id );
				if( isset($vendor) && !empty($vendor) ) {
					$vendor_email = $vendor->user_data->user_email;
				}
			}
		}
		
		if( isset($vendor_email) && !empty($vendor_email) ) {
			$email = WC()->mailer()->emails['WCMp_Email_Low_Stock_Alert'];
			$email->trigger( $vendor_email, $_id );
		}
	} 
	
	/**
	 * This function is triggered when a product reached out of stock limit set by Admin or vendor
	 *
	 * @param product_id	parent_id     id of the product, id of the parent
	 * @return
	 */
	function wcmp_out_of_stock( $product_id, $_id ) {
		
		// WC Marketplace
		if( WCMp_Vendor_Stock_Alert_Dependencies::wc_marketplace_plugin_active_check() ) {
			if( function_exists('get_wcmp_product_vendors') ) {
				$vendor = get_wcmp_product_vendors( $product_id );
				if( isset($vendor) && !empty($vendor) ) {
					$vendor_email = $vendor->user_data->user_email;
				}
			}
		}
		
		if( isset($vendor_email) && !empty($vendor_email) ) {
			$email = WC()->mailer()->emails['WCMp_Email_Out_of_Stock_Alert'];
			$email->trigger( $vendor_email, $_id );
		}
	}
	
}
