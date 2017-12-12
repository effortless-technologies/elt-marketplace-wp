<?php
class Frontend_Coupon_Manager_Shortcode {

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
		$current_user_id = $current_vendor_id;
		$wcmp_capabilities_settings_name = get_option('wcmp_capabilities_product_settings_name');
		$_vendor_submit_coupon = 'yes'; //get_user_meta($current_user_id, '_vendor_submit_coupon', true);
		
		
		// If vendor does not have product submission cap then show message
    if(is_user_logged_in() && is_user_wcmp_vendor( $current_user_id ) && (!isset($wcmp_capabilities_settings_name['is_submit_coupon']) || empty($_vendor_submit_coupon))) {
    	_e('You do not have enough permission to submit a new coupon. Please contact site administrator.', 'wcmp_frontend_product_manager');
    	return;
    }
    
    $coupon_id = 0;
    $title = '';
    $description = '';
    $discount_type = '';
    $coupon_amount = 0;
    $free_shipping = '';
    $expiry_date = '';
    
    $minimum_amount = '';
    $maximum_amount = '';
    $individual_use = '';
    $exclude_sale_items = '';
    $product_ids = '';
    $exclude_product_ids = '';
    $product_categories = '';
    $exclude_product_categories = '';
    $customer_email = '';
    
    $usage_limit = '';
    $limit_usage_to_x_items = '';
    $usage_limit_per_user = '';
    
    if(isset($_REQUEST['coupon_id'])) {
			$coupon_post = get_post( $_REQUEST['coupon_id'] );
			// Fetching Coupon Data
			if($coupon_post && !empty($coupon_post)) {
				$coupon_id = $_REQUEST['coupon_id'];
				
				$title = $coupon_post->post_title;
				$description = $coupon_post->post_excerpt;
				$discount_type = get_post_meta( $coupon_id, 'discount_type', true);
				$coupon_amount = get_post_meta( $coupon_id, 'coupon_amount', true);
				$free_shipping = ( get_post_meta( $coupon_id, 'free_shipping', true) == 'yes' ) ? 'enable' : '';
				$expiry_date = get_post_meta( $coupon_id, 'expiry_date', true);
				
				$minimum_amount = get_post_meta( $coupon_id, 'minimum_amount', true);
				$maximum_amount = get_post_meta( $coupon_id, 'maximum_amount', true);
				$individual_use = ( get_post_meta( $coupon_id, 'individual_use', true) == 'yes' ) ? 'enable' : '';
				$exclude_sale_items = ( get_post_meta( $coupon_id, 'exclude_sale_items', true) == 'yes' ) ? 'enable' : '';
				$product_ids = get_post_meta( $coupon_id, 'product_ids', true);
				$exclude_product_ids = get_post_meta( $coupon_id, 'exclude_product_ids', true);
				$product_categories = (array) get_post_meta( $coupon_id, 'product_categories', true);
				$exclude_product_categories = (array) get_post_meta( $coupon_id, 'exclude_product_categories', true);
				$customer_email = get_post_meta( $coupon_id, 'customer_email', true);
				
				if($customer_email) $customer_email = implode(',', $customer_email);
				
				$usage_limit = get_post_meta( $coupon_id, 'usage_limit', true);
				$limit_usage_to_x_items = get_post_meta( $coupon_id, 'limit_usage_to_x_items', true);
				$usage_limit_per_user = get_post_meta( $coupon_id, 'usage_limit_per_user', true);
			}
		}
    
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
			'author'	         => $current_user_id,
			'post_status'      => array('publish', 'pending', 'draft'),
			'suppress_filters' => true 
		);
		$products_array = get_posts( $args );
		$categories   = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
						
		?>
		<form id="coupon_manager_form" class="woocommerce">
			<?php
			if(isset($_REQUEST['fpm_msg']) && !empty($_REQUEST['fpm_msg'])) {
				$WCMp_fpm_coupon_messages = get_forntend_coupon_manager_messages();
				?>
				<div class="woocommerce-message" tabindex="-1"><?php echo $WCMp_fpm_coupon_messages[$_REQUEST['fpm_msg']]; ?></div>
				<?php
			}
			?>
			
			<div id="frontend_coupon_manager_accordion">
				<h3 class="pro_ele_head"><?php _e('General', 'wcmp_frontend_product_manager'); ?></h3>
				<div class="pro_ele_block">
					<p>
						<?php
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array(  "title" => array('label' => __('Title', 'wcmp_frontend_product_manager') , 'type' => 'text', 'class' => 'regular-text pro_ele', 'label_class' => 'pro_title pro_ele', 'value' => $title),
																																															"description" => array('label' => __('Description', 'wcmp_frontend_product_manager') , 'type' => 'textarea', 'class' => 'regular-textarea pro_ele', 'label_class' => 'pro_title', 'value' => $description),
																																															"discount_type" => array('label' => __('Discount Type', 'wcmp_frontend_product_manager'), 'type' => 'select', 'options' => array( 'fixed_product' => __('Fixed Product Discount', 'wcmp_frontend_product_manager') ), 'class' => 'regular-select pro_ele', 'label_class' => 'pro_ele pro_title', 'value' => $discount_type),
																																															"coupon_amount" => array('label' => __('Coupon Amount', 'wcmp_frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text pro_ele', 'label_class' => 'pro_ele pro_title', 'value' => $coupon_amount),
																																															//"free_shipping" => array('label' => __('Allow free shipping', 'wcmp_frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele', 'value' => 'enable', 'label_class' => 'pro_title checkbox_title', 'hints' => __('Check this box if the coupon grants free shipping. The free shipping method must be enabled and be set to require "a valid free shipping coupon" (see the "Free Shipping Requires" setting).', 'wcmp_frontend_product_manager'), 'dfvalue' => $free_shipping),
																																															"expiry_date" => array('label' => __('Coupon expiry date', 'wcmp_frontend_product_manager'), 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'class' => 'regular-text pro_ele', 'label_class' => 'pro_ele pro_title', 'value' => $expiry_date),
																																															"coupon_id" => array('type' => 'hidden', 'value' => $coupon_id)
																																											));
						?>
					</p>
				</div>
				<h3 class="pro_ele_head"><?php _e('Usage Restriction', 'wcmp_frontend_product_manager'); ?></h3>
				<div class="pro_ele_block">
					<p>
						<?php
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array( 	"minimum_amount" => array('label' => __('Minimum spend', 'wcmp_frontend_product_manager'), 'type' => 'text', 'placeholder' => 'No Minimum', 'class' => 'regular-text pro_ele', 'label_class' => 'pro_ele pro_title', 'value' => $minimum_amount),
																																															"maximum_amount" => array('label' => __('Maximum spend', 'wcmp_frontend_product_manager'), 'type' => 'text', 'placeholder' => 'No Maximum', 'class' => 'regular-text pro_ele', 'label_class' => 'pro_ele pro_title', 'value' => $maximum_amount),
																																															"individual_use" => array('label' => __('Individual use only', 'wcmp_frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele', 'value' => 'enable', 'label_class' => 'pro_title checkbox_title', 'hints' => __('Check this box if the coupon cannot be used in conjunction with other coupons.', 'wcmp_frontend_product_manager'), 'dfvalue' => $individual_use),
																																															"exclude_sale_items" => array('label' => __('Exclude sale items', 'wcmp_frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele', 'value' => 'enable', 'label_class' => 'pro_title checkbox_title', 'hints' => __('Check this box if the coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are no sale items in the cart.', 'wcmp_frontend_product_manager'), 'dfvalue' => $exclude_sale_items)
																																										));
						?>
						
						<p class="product_ids pro_ele pro_title"><strong>Products</strong><span class="img_tip" data-desc="Products which need to be in the cart to use this coupon or, for 'Product Discounts', which products are discounted."></span></p>
						<label class="screen-reader-text" for="product_ids">Products</label>
						<select id="product_ids" name="product_ids[]" class="regular-select pro_ele" multiple="multiple" style="width: 60%;">
						  <?php
								$product_ids = array_filter( array_map( 'absint', explode( ',', $product_ids )));
		
								if ( $products_array ) foreach($products_array as $products_single) {
									echo '<option value="' . esc_attr( $products_single->ID ) . '"' . selected( in_array( $products_single->ID, $product_ids ), true, false ) . '>' . esc_html( $products_single->post_title ) . '</option>';
								}
							?>
						</select>
						
						<p class="exclude_product_ids pro_ele pro_title"><strong>Exclude products</strong><span class="img_tip" data-desc="Products which must not be in the cart to use this coupon or, for 'Product Discounts', which products are not discounted."></span></p>
						<label class="screen-reader-text" for="exclude_product_ids">Exclude products</label>
						<select id="exclude_product_ids" name="exclude_product_ids[]" class="regular-select pro_ele" multiple="multiple" style="width: 60%;">
						  <?php
								$exclude_product_ids = array_filter( array_map( 'absint', explode( ',', $exclude_product_ids )));
		
								if ( $products_array ) foreach($products_array as $products_single) {
									echo '<option value="' . esc_attr( $products_single->ID ) . '"' . selected( in_array( $products_single->ID, $exclude_product_ids ), true, false ) . '>' . esc_html( $products_single->post_title ) . '</option>';
								}
							?>
						</select>
						
						<p class="product_categories pro_ele pro_title"><strong>Product categories</strong><span class="img_tip" data-desc="A product must be in this category for the coupon to remain valid or, for 'Product Discounts', products in these categories will be discounted."></span></p>
						<label class="screen-reader-text" for="product_categories">Product categories</label>
						<select id="product_categories" name="product_categories[]" class="regular-select pro_ele" multiple="multiple" style="width: 60%;">
						  <?php
								$category_ids = (array) $product_categories;
								$categories   = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
		
								if ( $categories ) foreach ( $categories as $cat ) {
									echo '<option value="' . esc_attr( $cat->term_id ) . '"' . selected( in_array( $cat->term_id, $category_ids ), true, false ) . '>' . esc_html( $cat->name ) . '</option>';
								}
							?>
						</select>
						
						
						<p class="exclude_product_categories pro_ele pro_title"><strong>Exclude categories</strong><span class="img_tip" data-desc="Product must not be in this category for the coupon to remain valid or, for 'Product Discounts', products in these categories will not be discounted."></span></p>
						<label class="screen-reader-text" for="exclude_product_categories">Exclude categories</label>                                                                                                                                                                                   
						<select id="exclude_product_categories" name="exclude_product_categories[]" class="regular-select pro_ele" multiple="multiple" style="width: 60%;">
						  <?php
								$category_ids = (array) $exclude_product_categories;
								$categories   = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
		
								if ( $categories ) foreach ( $categories as $cat ) {
									echo '<option value="' . esc_attr( $cat->term_id ) . '"' . selected( in_array( $cat->term_id, $category_ids ), true, false ) . '>' . esc_html( $cat->name ) . '</option>';
								}
							?>
						</select>
						
						<?php
						  $WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array(  
																																															"customer_email" => array('label' => __('Email restrictions', 'wcmp_frontend_product_manager'), 'type' => 'text', 'placeholder' => 'No restictions', 'class' => 'regular-text pro_ele', 'label_class' => 'pro_ele pro_title', 'hints' => __('List of allowed emails to check against the customer\'s billing email when an order is placed. Separate email addresses with commas.', 'wcmp_frontend_product_manager'), 'value' => $customer_email)
																																															
																																										));
						?>
					</p>
				</div>
				<h3 class="pro_ele_head simple variable external"><?php _e('Usage Limit', 'wcmp_frontend_product_manager'); ?></h3>
				<div class="pro_ele_block simple variable external">
					<p>
						<?php
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array(  "usage_limit" => array('label' => __('Usage limit per coupon', 'wcmp_frontend_product_manager'), 'type' => 'number', 'placeholder' => 'Unlimited usage', 'class' => 'regular-text regular-text-limit pro_ele', 'label_class' => 'pro_ele pro_title limit_title', 'attributes' => array('min' => 0, 'steps' => 1), 'hints' => __('How many times this coupon can be used before it is void.', 'wcmp_frontend_product_manager'), 'value' => $usage_limit),
																																															"limit_usage_to_x_items" => array('label' => __('Limit usage to X items', 'wcmp_frontend_product_manager'), 'type' => 'number', 'placeholder' => 'Apply to all qualifying items in cart', 'class' => 'regular-text regular-text-limit pro_ele', 'label_class' => 'pro_ele pro_title limit_title', 'attributes' => array('min' => 0, 'steps' => 1), 'hints' => __('The maximum number of individual items this coupon can apply to when using product discounts. Leave blank to apply to all qualifying items in cart.', 'wcmp_frontend_product_manager'), 'value' => $limit_usage_to_x_items),
																																															"usage_limit_per_user" => array('label' => __('Usage limit per user', 'wcmp_frontend_product_manager'), 'type' => 'number', 'placeholder' => 'Unlimited usage', 'class' => 'regular-text regular-text-limit pro_ele', 'label_class' => 'pro_ele pro_title limit_title', 'attributes' => array('min' => 0, 'steps' => 1), 'hints' => __('How many times this coupon can be used by an invidual user. Uses billing email for guests, and user ID for logged in users.', 'wcmp_frontend_product_manager'), 'value' => $usage_limit_per_user)
																																										));
						?>
					</p>
				</div>
			</div>
			<div id="coupon_manager_submit">
				<input type="submit" name="submit-data" value="<?php _e('Submit', 'wcmp_frontend_product_manager'); ?>" id="coupon_manager_submit_button" />
				<?php if(!isset($_REQUEST['coupon_id']) || (isset($_REQUEST['coupon_id']) && (get_post_status($_REQUEST['coupon_id']) == 'draft'))) { ?>
				<input type="submit" name="draft-data" value="<?php _e('Draft', 'wcmp_frontend_product_manager'); ?>" id="coupon_manager_draft_button" />
				<?php } ?>
			</div>
		</form>
		<?php
		
		do_action('wcmp-frontend-coupon-manager_template');

	}
}
