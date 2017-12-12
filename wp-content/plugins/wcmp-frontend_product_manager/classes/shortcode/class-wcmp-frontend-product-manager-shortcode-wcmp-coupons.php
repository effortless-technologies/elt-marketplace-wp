<?php
class WCMp_Coupons_Shortcode {

	public function __construct() {

	}

	/**
	 * Output the Frontend Product Manager shortcode.
	 *
	 * @access public
	 * @param array $atts
	 * @return void
	 */
	static public function output( $attr ) {
		global $WCMp, $WCMp_Frontend_Product_Manager;
		$WCMp_Frontend_Product_Manager->nocache();
		
		if( !is_user_logged_in() ) {
			_e('You do not have enough permission to access this page. Please logged in first.', 'wcmp_frontend_product_manager');
    	return;
		}
		
		$current_vendor_id = apply_filters( 'wcmp_current_loggedin_vendor_id', get_current_user_id() );
		?>
		
		<div class="wcmp_remove_div">
			<div class="wcmp_main_page">  <?php 
				do_action( 'wcmp_vendor_dashboard_navigation', array( ) );
				
				?>
				<div class="wcmp_main_holder toside_fix">
					<div class="wcmp_headding1">
						<ul>
							<li><?php _e( 'Promote ', 'wcmp_frontend_product_manager' );?></li>
							<li class="next"> < </li>
							<li><?php _e( 'Coupon(s)', 'wcmp_frontend_product_manager' );?></li>
						</ul>
						<div class="clear"></div>
					</div>
					<?php
					if( is_user_logged_in() && is_user_wcmp_vendor( $current_vendor_id ) ) {
						$args = array(
							'posts_per_page'   => -1,
							'offset'           => 0,
							'category'         => '',
							'category_name'    => '',
							'orderby'          => 'date',
							'order'            => 'DESC',
							'include'          => '',
							'exclude'          => '',
							'meta_key'         => '',
							'meta_value'       => '',
							'post_type'        => 'shop_coupon',
							'post_mime_type'   => '',
							'post_parent'      => '',
							'author'	         => $current_vendor_id,
							'post_status'      => array('publish', 'pending', 'draft'),
							'suppress_filters' => true 
						);
						$coupons_array = get_posts( $args );
						$coupons_list = '';
						if(!empty($coupons_array)) {
							$coupons_list .= '<div class="wcmp_tab ui-tabs ui-widget ui-widget-content ui-corner-all"><div class="wcmp_table_holder"><table><tbody><tr><td>' . __('Coupon(s)', 'wcmp_frontend_product_manager') . '</td><td>' . __('Expiry Date', 'wcmp_frontend_product_manager') . '</td><td>' . __('Action', 'wcmp_frontend_product_manager') . '</td></tr>';
							
							foreach($coupons_array as $coupon_single) {
								$coupons_list .= '<tr><td>' . $coupon_single->post_title . '</td><td>' . get_post_meta( $coupon_single->ID, 'expiry_date', true) . '</td><td><a title="Edit/View" class="" href="' . add_query_arg('coupon_id', $coupon_single->ID, get_frontend_coupon_manager_page()) . '"><img src="' . site_url() . '/wp-content/plugins/wcmp-frontend_product_manager/assets/images/view.png" /></a></td></tr>';
							}
							
							$coupons_list .= '</tbody></table></div></div>';
						} else {
							?>
							<div><h4>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php
							_e( "You do not have any active coupon yet!!!", 'wcmp_frontend_product_manager' );
							?>
							</h4></div>
							<?php
						}
						echo $coupons_list;
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}
}
