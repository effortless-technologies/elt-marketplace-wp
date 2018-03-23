<?php
class WCMP_Vendor_Report_Shortcode_Overview {

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
				
								
				//wp_enqueue_script('jquery-ui-core');
				//wp_enqueue_script('jquery-ui-tabs');
				
				//$frontend_script_path = $WCMp_Vendor_Frontend_Report->plugin_url . 'assets/frontend/js/';
				//wp_enqueue_script('frontend_report_overview_js', $frontend_script_path.'frontend.js', array('jquery','jquery-ui-datepicker'), $WCMp_Vendor_Frontend_Report->version, true);
				
				$WCMp_Vendor_Frontend_Report->nocache();
				
				?>
				<!-- <p><?php _e( 'Please select this page in WCMp page settings in "Advanced Vendor Reports" to make Report plugin work properly.', 'wcmp-vendor_frontend_report' ); ?></p> -->
				<input type="hidden" name="currentTab" value="0"/>
				<div id="wcmp_tabs">
					<ul>
						<li><a href="#wcmp_report_overview"><?php _e( 'Sales Overview', 'wcmp-vendor_frontend_report' ); ?></a></li>
						<li><a href="#wcmp_report_product_sells"><?php _e( 'Sales by Product', 'wcmp-vendor_frontend_report' ); ?></a></li>
						<li><a href="#wcmp_report_stock_overview"><?php _e( 'Stock', 'wcmp-vendor_frontend_report' ); ?></a></li>
						<li><a href="#wcmp_report_transaction"><?php _e( 'Transactions', 'wcmp-vendor_frontend_report' ); ?></a></li>
					</ul>
					<div id="wcmp_report_overview">
						<?php echo do_shortcode('[vendor_report_sales_overview]'); ?>
					</div>
					<div id="wcmp_report_product_sells">
						<ul style="background: none; border: none;">
							<li><a href="#wcmp_report_product_sells_overview" class="current"><?php _e( 'Overview', 'wcmp-vendor_frontend_report' ); ?></a></li><li><a href="#wcmp_report_product_sells_search" class=""><?php _e( 'By Product', 'wcmp-vendor_frontend_report' ); ?></a></li>
						</ul>
						<div id="wcmp_report_product_sells_overview">
							<?php echo do_shortcode('[vendor_report_product_overview]');	?>
						</div>
						<div id="wcmp_report_product_sells_search">
							<?php echo do_shortcode('[vendor_report_search_by_product]'); ?>
						</div>
					</div>
					<div id="wcmp_report_stock_overview">
						<ul style="background: none; border: none;">
							<li><a href="#wcmp_report_low_stock" class="current"><?php _e( 'Low in stock', 'wcmp-vendor_frontend_report' ); ?></a></li>
							<li><a href="#wcmp_report_out_of_stock" class=""><?php _e( 'Out of Stock', 'wcmp-vendor_frontend_report' ); ?></a></li>
							<li><a href="#wcmp_report_most_of_stock" class=""><?php _e( 'Most Stocked', 'wcmp-vendor_frontend_report' ); ?></a></li>
						</ul>
						<div id="wcmp_report_low_stock">
							<?php echo do_shortcode('[vendor_report_stock_low_stock]'); ?>
						</div>
						<div id="wcmp_report_out_of_stock">
							<?php echo do_shortcode('[vendor_report_stock_out_of_stock]'); ?>
						</div>
						<div id="wcmp_report_most_of_stock">
							<?php echo do_shortcode('[vendor_report_stock_most_stock]'); ?>
						</div>
					</div>
					<div id="wcmp_report_transaction">
						<?php echo do_shortcode('[vendor_report_transaction_overview]');	?>
					</div>
				</div>
				<?php
			} else { ?>
				<div>
					<label for="vendor_profile">
						<?php
							_e('Your account is not vendor capable.', 'wcmp-vendor_frontend_report');
						?>
					</label>
				</div>
				<?php
			}
		}
	}
}