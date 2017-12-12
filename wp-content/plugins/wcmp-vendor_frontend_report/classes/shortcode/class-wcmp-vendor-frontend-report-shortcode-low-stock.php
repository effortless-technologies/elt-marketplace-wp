<?php
class WCMP_Vendor_Report_Shortcode_Low_Stock {

	public function __construct() {

	}

	/**
	 * Output the demo shortcode.
	 *
	 * @access public
	 * @param array $atts
	 * @return void
	 */
	public static function output( $attr ) {
		global $wpdb, $WCMp_Vendor_Frontend_Report, $WCMp, $woocommerce, $wp_locale;
		$user_id = get_current_user_id();
		if(function_exists( 'is_user_wcmp_vendor' ) ) {
			if(is_user_wcmp_vendor($user_id)) {
				
				//$frontend_script_path = $WCMp_Vendor_Frontend_Report->plugin_url . 'assets/frontend/js/';
				//wp_enqueue_script('wcmp_frontend_report_low_stock_js', $frontend_script_path.'low_in_stock.js', array('jquery'), $WCMp_Vendor_Frontend_Report->version, true);
				
				$user_id = get_current_user_id();
				if(is_user_wcmp_vendor($user_id)) {
					$vendor = get_wcmp_vendor($user_id);
					// Get products using a query - this is too advanced for get_posts :(
					
					$stock = false; $nostock = false;
					$stock_alert_settings = get_user_meta( $user_id, 'wcmp_vendor_stock_alert_settings', true );
					$wcmp_stock_alert_settings = get_wcmp_vendor_settings( 'wcmp_product_settings_name' );
					
					if ( WCMp_Vendor_Frontend_Report_Dependencies::wcmp_vendor_stock_alert_plugin_active_check()) {
						if(!$stock) {
							if(!empty($stock_alert_settings) && isset($stock_alert_settings['low_stock_limit']) && !empty($stock_alert_settings['low_stock_limit'])) $stock   = absint( $stock_alert_settings['low_stock_limit'] );
							if(!$stock) {
								if(!empty($wcmp_stock_alert_settings) && isset($wcmp_stock_alert_settings['low_stock_limit']) && !empty($wcmp_stock_alert_settings['low_stock_limit'])) $stock   = absint( $wcmp_stock_alert_settings['low_stock_limit'] );
							}
						}
						
						if(!$nostock) {
							if(!empty($stock_alert_settings) && isset($stock_alert_settings['out_of_stock_limit']) && !empty($stock_alert_settings['out_of_stock_limit'])) $nostock   = absint( $stock_alert_settings['out_of_stock_limit'] );
							if(!$nostock) {
								if(!empty($wcmp_stock_alert_settings) && isset($wcmp_stock_alert_settings['out_of_stock_limit']) && !empty($wcmp_stock_alert_settings['out_of_stock_limit'])) $nostock   = absint( $wcmp_stock_alert_settings['out_of_stock_limit'] );
							}
						}
					}
					
					if(!$stock) $stock   = absint( max( get_option( 'woocommerce_notify_low_stock_amount' ), 1 ) );
					if(!$nostock) $nostock = absint( max( get_option( 'woocommerce_notify_no_stock_amount' ), 0 ) );
					
					$query_from = apply_filters( 'wcmp_report_low_in_stock_query_from', "FROM {$wpdb->posts} as posts
						INNER JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id
						INNER JOIN {$wpdb->postmeta} AS postmeta2 ON posts.ID = postmeta2.post_id
						WHERE 1=1
						AND posts.post_author = {$vendor->id} 
						AND posts.post_type IN ( 'product', 'product_variation' )
						AND posts.post_status = 'publish'
						AND postmeta2.meta_key = '_manage_stock' AND postmeta2.meta_value = 'yes'
						AND postmeta.meta_key = '_stock' AND CAST(postmeta.meta_value AS SIGNED) <= '{$stock}'
						AND postmeta.meta_key = '_stock' AND CAST(postmeta.meta_value AS SIGNED) > '{$nostock}'
					" );
					$current_page = 1;
					$per_page = 5;
					$items     = $wpdb->get_results( $wpdb->prepare( "SELECT posts.ID as id, posts.post_parent as parent {$query_from} GROUP BY posts.ID ORDER BY posts.post_title DESC LIMIT %d, %d;", ( $current_page - 1 ) * $per_page, $per_page ), ARRAY_A );
					
					$max_items = $wpdb->get_var( "SELECT COUNT( DISTINCT posts.ID ) {$query_from};" );	
					
					wp_localize_script('wcmp_frontend_report_low_stock_js', 'wcmp_frontend_report_low_stock', array( 'max_items' => $max_items ));
					
					$more_button = true;
					if(count($items) == $max_items) $more_button = false;
					
					$WCMp_Vendor_Frontend_Report->template->get_template( 'reports/low_in_stock.php', array('items' => $items, 'max_items' => $max_items, 'current_page' => $current_page + 1, 'per_page' => $per_page, 'show_more_button' => $more_button) );
				}
			} else { ?>
				<div>
					<label for="vendor_profile">
						<?php
							_e('Your account is not vendor capable.', $WCMp_Vendor_Frontend_Report->text_domain);
						?>
					</label>
				</div>
				<?php
			}
		}
	}
}
?>