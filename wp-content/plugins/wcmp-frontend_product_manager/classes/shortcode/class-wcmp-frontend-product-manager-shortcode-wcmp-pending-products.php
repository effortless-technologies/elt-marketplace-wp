<?php
class WCMp_Pending_Products_Shortcode {

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
		if( !is_user_logged_in() ) {
			_e('You do not have enough permission to access this page. Please logged in first.', 'wcmp_frontend_product_manager');
    	return;
		}
		$WCMp_Frontend_Product_Manager->nocache();
		$current_vendor_id = apply_filters( 'wcmp_current_loggedin_vendor_id', get_current_user_id() );
		?>
		
		<div class="wcmp_remove_div">
			<div class="wcmp_main_page">  <?php 
				do_action( 'wcmp_vendor_dashboard_navigation', array( ) );
				
				?>
				<div class="wcmp_main_holder toside_fix">
					<div class="wcmp_headding1">
						<ul>
							<li><?php _e( 'Product Manager ', 'wcmp_frontend_product_manager' );?></li>
							<li class="next"> < </li>
							<li><?php _e( 'Product(s)', 'wcmp_frontend_product_manager' );?></li>
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
							'post_type'        => 'product',
							'post_mime_type'   => '',
							'post_parent'      => '',
							//'author'	         => $current_vendor_id,
							'post_status'      => array('publish', 'pending', 'draft'),
							'suppress_filters' => true 
						);
						
						$vendor_term = absint( get_user_meta( $current_vendor_id, '_vendor_term_id', true ) );
						$args['tax_query'][] = array(
																					'taxonomy' => 'dc_vendor_shop',
																					'field' => 'term_id',
																					'terms' => $vendor_term,
																				);
						
						$prodycts_array = get_posts( $args );
						$pending_product_list = '';
						if(!empty($prodycts_array)) {
							$pending_product_list .= '<div class="wcmp_tab ui-tabs ui-widget ui-widget-content ui-corner-all"><div class="wcmp_table_holder"><table><tbody><tr><td>' . __('Product', 'wcmp_frontend_product_manager') . '</td><td>' . __('Status', 'wcmp_frontend_product_manager') . '</td><td>' . __('Action', 'wcmp_frontend_product_manager') . '</td></tr>';
							
							foreach($prodycts_array as $prodycts_single) {
								$pending_product_list .= '<tr><td>' . $prodycts_single->post_title . ' (' . get_post_meta($prodycts_single->ID, '_sku', true) . ')</td><td>' . ucfirst($prodycts_single->post_status) . '</td><td>';
								if( current_user_can( 'edit_published_products' ) ) {
									$pending_product_list .= '<a class="wcmp_ass_btn" href="' . add_query_arg('pro_id', $prodycts_single->ID, get_forntend_product_manager_page()) . '">' . __('Edit', 'wcmp_frontend_product_manager') . '</a>';
								}
								$pending_product_list .= '</td></tr>';
							}
							
							$pending_product_list .= '</tbody></table></div></div>';
						}  else {
							?>
							<div><h4>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php
							_e( "No Product(s) yet!!!", 'wcmp_frontend_product_manager' );
							?>
							</h4></div>
							<?php
						}
						echo $pending_product_list;
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}
}
