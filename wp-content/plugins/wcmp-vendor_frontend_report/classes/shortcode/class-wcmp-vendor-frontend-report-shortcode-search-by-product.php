<?php
class WCMP_Vendor_Report_Shortcode_Search_By_Product {

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
		global $WCMp_Vendor_Frontend_Report, $WCMp, $woocommerce, $wp_locale;
		
		$user_id = get_current_user_id();
		
		if(function_exists( 'is_user_wcmp_vendor' ) ) {
			if(is_user_wcmp_vendor($user_id)) {
				
				
				?>
				<style>
					#search_product_chosen {
						width : 215px !important;
					}
					.chosen-container-single .chosen-single {
						height: 32px !important;
					}
				</style>
				<?php
				// if( ! wp_style_is( 'woocommerce_chosen_styles', 'queue' ) ) {
				// 	wp_enqueue_style( 'woocommerce_chosen_styles', WC()->plugin_url() . '/assets/css/chosen.css' );
				// }
				
				//$frontend_script_path = $WCMp_Vendor_Frontend_Report->plugin_url . 'assets/frontend/js/';
				//wp_enqueue_script('jquery-ui-datepicker');
				//$suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
				//wp_enqueue_script( 'ajax-chosen', WC()->plugin_url() . '/assets/js/chosen/ajax-chosen.jquery' . $suffix . '.js', array( 'jquery', 'chosen' ), WC_VERSION );
				//wp_enqueue_script( 'chosen' );
				//wp_enqueue_script('dc_product_search_js', $frontend_script_path.'product_search.js', array('jquery','jquery-ui-datepicker'), $WCMp_Vendor_Frontend_Report->version, true);
				wp_localize_script('dc_product_search_js', 'wcmp_report_product_search', array( 'security' => wp_create_nonce("search-products")));
				
				
				if(isset($_GET['product_serach_from_date'])) $start_date = strtotime($_GET['product_serach_from_date']); 
				else $start_date =  strtotime( date('Ymd', strtotime( date('Ym', current_time('timestamp') ) . '01' ) ) );
				
				if(isset($_GET['product_serach_to_date'])) $end_date = strtotime($_GET['product_serach_to_date']);
				else $end_date = strtotime( date('Ymd', current_time( 'timestamp' ) ) );
				
				$WCMp_Vendor_Frontend_Report->template->get_template( 'reports/sales_by_product_search.php', array( 'start_date' => isset($_GET['product_overview_from_date']) ? $_GET['product_overview_from_date'] : '', 'end_date' => isset($_GET['product_overview_to_date']) ? $_GET['product_overview_to_date'] : ''));
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