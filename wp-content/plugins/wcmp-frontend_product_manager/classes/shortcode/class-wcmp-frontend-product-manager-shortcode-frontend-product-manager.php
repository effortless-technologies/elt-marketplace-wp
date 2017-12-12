<?php
class Frontend_Product_Manager_Shortcode {

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
		global $WCMp, $WCMp_Frontend_Product_Manager, $wc_product_attributes;
		
		if( !is_user_logged_in() ) {
			_e('You do not have enough permission to access this page. Please logged in first.', 'wcmp_frontend_product_manager');
    	return;
		}
		
		$WCMp_Frontend_Product_Manager->nocache();
		
		$current_vendor_id = apply_filters( 'wcmp_current_loggedin_vendor_id', get_current_user_id() );
		
		// If vendor does not have product submission cap then show message
    if( is_user_logged_in() && is_user_wcmp_vendor( $current_vendor_id ) && !current_user_can('edit_products') ) {
    	_e('You do not have enough permission to submit a new product. Please contact site administrator.', 'wcmp_frontend_product_manager');
    	return;
    }
		
		$product_id = 0;
		$product = array();
		$product_type = '';
		$is_virtual = '';
		$title = '';
		$sku = '';
		$excerpt = '';
		$description = '';
		$regular_price = '';
		$sale_price = '';
		$sale_date_from = '';
		$sale_date_upto = '';
		$product_url = '';
		$button_text = '';
		$visibility = 'visible';
		$is_downloadable = '';
		$downloadable_files = array();
		$download_limit = '';
		$download_expiry = '';
		$featured_img = '';
		$gallery_img_ids = array();
		$gallery_img_urls = array();
		$categories = array();
		$product_tags = '';
		$manage_stock = '';
		$stock_qty = 0;
		$backorders = '';
		$stock_status = ''; 
		$sold_individually = '';
		$weight = '';
		$length = '';
		$width = '';
		$height = '';
		$shipping_class = '';
		$tax_status = '';
		$tax_class = '';
		$attributes = array();
		$default_attributes = '';
		$attributes_select_type = array();
		$variations = array();
		
		$upsell_ids = array();
		$crosssell_ids = array();
		$children = array();
		
		$enable_reviews = '';
		$menu_order = '';
		$purchase_note = '';
		
		// Yoast SEO Support
		$yoast_wpseo_focuskw_text_input = '';
		$yoast_wpseo_metadesc = '';
		
		// WooCommerce Custom Product Tabs Lite Support
		$product_tabs = array();
		
		// WooCommerce Product Fees Support
		$product_fee_name = '';
		$product_fee_amount = '';
		$product_fee_multiplier = 'no';
		
		// WooCommerce Bulk Discount Support
		$_bulkdiscount_enabled = 'no';
		$_bulkdiscount_text_info = '';
		$_bulkdiscounts = array();
		
		
		if(isset($_REQUEST['pro_id'])) {
			$product = wc_get_product( $_REQUEST['pro_id'] );
			
			// Fetching Product Data
			if($product && !empty($product)) {
				$product_id = $_REQUEST['pro_id'];
				
				$vendor_data = get_wcmp_product_vendors( $product_id );
				if( !current_user_can( 'administrator' ) && $vendor_data && ( $vendor_data->id != $current_vendor_id ) ) {
					_e('You do not have enough permission to access this product.', 'wcmp_frontend_product_manager');
					return;
				}
				
				$product_type = $product->get_type();
				$title = $product->get_title();
				$sku = $product->get_sku();
				$excerpt = $product->get_short_description();
				$description = $product->get_description();
				$regular_price = $product->get_regular_price();
				$sale_price = $product->get_sale_price();
				
				$sale_date_from = ( $date = get_post_meta( $product_id, '_sale_price_dates_from', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
				$sale_date_upto = ( $date = get_post_meta( $product_id, '_sale_price_dates_to', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
				
				// External product option
				$product_url = get_post_meta( $product_id, '_product_url', true);
				$button_text = get_post_meta( $product_id, '_button_text', true);
				
				// Product Visibility
				$visibility = get_post_meta( $product_id, '_visibility', true);
				
				// Virtual
				$is_virtual = ( get_post_meta( $product_id, '_virtual', true) == 'yes' ) ? 'enable' : '';
				
				// Download ptions
				$is_downloadable = ( get_post_meta( $product_id, '_downloadable', true) == 'yes' ) ? 'enable' : '';
				if($is_downloadable == 'enable') {
					$downloadable_files = get_post_meta( $product_id, '_downloadable_files', true);
					if(!$downloadable_files) $downloadable_files = array();
					$download_limit = get_post_meta( $product_id, '_download_limit', true);
					$download_expiry = get_post_meta( $product_id, '_download_expiry', true);
				}
				
				// Product Images
				$featured_img = ($product->get_image_id()) ? $product->get_image_id() : '';
				if($featured_img) $featured_img = wp_get_attachment_url($featured_img);
				$gallery_img_ids = $product->get_gallery_image_ids();
				if(!empty($gallery_img_ids)) {
					foreach($gallery_img_ids as $gallery_img_id) {
						$gallery_img_urls[]['image'] = wp_get_attachment_url($gallery_img_id);
					}
				}
				
				// Product Categories
				$pcategories = get_the_terms( $product_id, 'product_cat' );
				if( !empty($pcategories) ) {
					foreach($pcategories as $pkey => $pcategory) {
						$categories[] = $pcategory->term_id;
					}
				} else {
					$categories = array();
				}
				
				// Product Tags
				$product_tag_list = wp_get_post_terms($product_id, 'product_tag', array("fields" => "names"));
				$product_tags = implode(',', $product_tag_list);
				
				// Product Stock options
				$manage_stock = $product->managing_stock() ? 'enable' : '';
				$stock_qty = $product->get_stock_quantity();
				$backorders = $product->get_backorders();
				$stock_status = $product->get_stock_status();  
				$sold_individually = $product->is_sold_individually() ? 'enable' : '';
				
				// Product Shipping Data
				$weight = $product->get_weight();
				$length = $product->get_length();
				$width = $product->get_width();
				$height = $product->get_height();
				$shipping_class = $product->get_shipping_class_id();
				
				// Product Tax Data
				$tax_status = $product->get_tax_status();
				$tax_class = $product->get_tax_class();
				
				// Product Attributes
				$pro_attributes = get_post_meta( $product_id, '_product_attributes', true );
				if(!empty($pro_attributes)) {
					$acnt = 0;
					foreach($pro_attributes as $pro_attribute) {
						
						if ( $pro_attribute['is_taxonomy'] ) {
							$att_taxonomy = $pro_attribute['name'];

							if ( ! taxonomy_exists( $att_taxonomy ) ) {
								continue;
							}
							
							$attribute_taxonomy = $wc_product_attributes[ $att_taxonomy ];
					
							$attributes[$acnt]['term_name'] = $att_taxonomy;
							$attributes[$acnt]['name'] = wc_attribute_label( $att_taxonomy );
							$attributes[$acnt]['attribute_taxonomy'] = $attribute_taxonomy;
							$attributes[$acnt]['tax_name'] = $att_taxonomy;
							$attributes[$acnt]['is_taxonomy'] = 1;
							
							if ( 'select' === $attribute_taxonomy->attribute_type ) {
								$args = array(
												'orderby'    => 'name',
												'hide_empty' => 0
											);
								$all_terms = get_terms( $att_taxonomy, apply_filters( 'wc_product_attribute_terms', $args ) );
								$attributes_option = array();
								if ( $all_terms ) {
									foreach ( $all_terms as $term ) {
										$attributes_option[$term->term_id] = esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) );
									}
								}
								$attributes[$acnt]['attribute_type'] = 'select';
								$attributes[$acnt]['option_values'] = $attributes_option;
								$attributes[$acnt]['value'] = wp_get_post_terms( $product_id, $att_taxonomy, array( 'fields' => 'ids' ) );
							} else {
								$attributes[$acnt]['attribute_type'] = 'text';
								$attributes[$acnt]['value'] = esc_attr( implode( ' ' . WC_DELIMITER . ' ', wp_get_post_terms( $product_id, $att_taxonomy, array( 'fields' => 'names' ) ) ) );
							}
						} else {
							$attributes[$acnt]['term_name'] = apply_filters( 'woocommerce_attribute_label', $pro_attribute['name'], $pro_attribute['name'], $product );
							$attributes[$acnt]['name'] = apply_filters( 'woocommerce_attribute_label', $pro_attribute['name'], $pro_attribute['name'], $product );
							$attributes[$acnt]['value'] = $pro_attribute['value'];
							$attributes[$acnt]['tax_name'] = '';
							$attributes[$acnt]['is_taxonomy'] = 0;
							$attributes[$acnt]['attribute_type'] = 'text';
						}
						
						$attributes[$acnt]['is_visible'] = $pro_attribute['is_visible'] ? 'enable' : '';
						$attributes[$acnt]['is_variation'] = $pro_attribute['is_variation'] ? 'enable' : '';
						
						if( 'select' === $attributes[$acnt]['attribute_type'] ) {
							$attributes_select_type[$acnt] = $attributes[$acnt];
							unset($attributes[$acnt]);
						}
						$acnt++;
					}
				}
				
				// Product Default Attributes
				$default_attributes = json_encode( (array) get_post_meta( $product_id, '_default_attributes', true ) );
				
				// Variable Product Variations
				$variation_ids = $product->get_children();
				if(!empty($variation_ids)) {
					foreach($variation_ids as $variation_id_key => $variation_id) {
						$variation_data = new WC_Product_Variation($variation_id);
						
						$variations[$variation_id_key]['id'] = $variation_id;
						$variations[$variation_id_key]['enable'] = $variation_data->is_purchasable() ? 'enable' : '';
						$variations[$variation_id_key]['sku'] = get_post_meta($variation_id, '_sku', true);
						
						// Variation Image
						$variation_img = $variation_data->get_image_id();
						if($variation_img) $variation_img = wp_get_attachment_url($variation_img);
						else $variation_img = '';
						$variations[$variation_id_key]['image'] = $variation_img;
						
						// Variation Price
						$variations[$variation_id_key]['regular_price'] = get_post_meta($variation_id, '_regular_price', true);
						$variations[$variation_id_key]['sale_price'] = get_post_meta($variation_id, '_sale_price', true);
						
						// Variation Stock Data
						$variations[$variation_id_key]['manage_stock'] = $variation_data->managing_stock() ? 'enable' : '';
						$variations[$variation_id_key]['stock_status'] = $variation_data->get_stock_status();
						$variations[$variation_id_key]['stock_qty'] = $variation_data->get_stock_quantity();
						$variations[$variation_id_key]['backorders'] = $variation_data->get_backorders();
						
						// Variation Virtual Data
						$variations[$variation_id_key]['is_virtual'] = ( 'yes' == get_post_meta($variation_id, '_virtual', true) ) ? 'enable' : '';
						
						// Variation Downloadable Data
						$variations[$variation_id_key]['is_downloadable'] = ( 'yes' == get_post_meta($variation_id, '_downloadable', true) ) ? 'enable' : '';
						$variations[$variation_id_key]['downloadable_files'] = get_post_meta($variation_id, '_downloadable_files', true);
						$variations[$variation_id_key]['download_limit'] = get_post_meta($variation_id, '_download_limit', true);
						$variations[$variation_id_key]['download_expiry'] = get_post_meta($variation_id, '_download_expiry', true);
						if(!empty($variations[$variation_id_key]['downloadable_files'])) {
							foreach($variations[$variation_id_key]['downloadable_files'] as $variations_downloadable_files) {
								$variations[$variation_id_key]['downloadable_file'] = $variations_downloadable_files['file'];
								$variations[$variation_id_key]['downloadable_file_name'] = $variations_downloadable_files['name'];
							}
						}
						
						// Variation Shipping Data
						$variations[$variation_id_key]['weight'] = $variation_data->get_weight();
						$variations[$variation_id_key]['length'] = $variation_data->get_length();
						$variations[$variation_id_key]['width'] = $variation_data->get_width();
						$variations[$variation_id_key]['height'] = $variation_data->get_height();
						$variations[$variation_id_key]['shipping_class'] = $variation_data->get_shipping_class_id();
						
						// Variation Tax
						$variations[$variation_id_key]['tax_class'] = $variation_data->get_tax_class();
						
						// Variation Attributes
						$variations[$variation_id_key]['attributes'] = json_encode( $variation_data->get_variation_attributes() );
						
						// Description
						$variations[$variation_id_key]['description'] = get_post_meta($variation_id, '_variation_description', true);
						
						$variations = apply_filters( 'wcmp_fpm_variation_edit_data', $variations, $variation_id, $variation_id_key );
					}
				}
				
				$upsell_ids = get_post_meta( $product_id, '_upsell_ids', true ) ? get_post_meta( $product_id, '_upsell_ids', true ) : array();
				$crosssell_ids = get_post_meta( $product_id, '_crosssell_ids', true ) ? get_post_meta( $product_id, '_crosssell_ids', true ) : array();
				$children = get_post_meta( $product_id, '_children', true ) ? get_post_meta( $product_id, '_children', true ) : array();
				
				// Product Advance Options
				$product_post = get_post( $product_id );
				$enable_reviews = ( $product_post->comment_status == 'open' ) ? 'enable' : '';
				$menu_order = $product_post->menu_order;
				$purchase_note = get_post_meta( $product_id, '_purchase_note', true );
				
				// Yoast SEO Support
				if(WCMp_Frontend_Product_Manager_Dependencies::fpm_yoast_plugin_active_check()) {
					$yoast_wpseo_focuskw_text_input = get_post_meta( $product_id, '_yoast_wpseo_focuskw_text_input', true );
					$yoast_wpseo_metadesc = get_post_meta( $product_id, '_yoast_wpseo_metadesc', true );
				}
				
				// WooCommerce Custom Product Tabs Lite Support
				if(WCMp_Frontend_Product_Manager_Dependencies::fpm_wc_tabs_lite_plugin_active_check()) {
					$product_tabs = (array) get_post_meta( $product_id, 'frs_woo_product_tabs', true );
				}
				
				// WooCommerce Product Fees Support
				if(WCMp_Frontend_Product_Manager_Dependencies::fpm_wc_product_fees_plugin_active_check()) {
					$product_fee_name = get_post_meta( $product_id, 'product-fee-name', true );
					$product_fee_amount = get_post_meta( $product_id, 'product-fee-amount', true );
					$product_fee_multiplier = get_post_meta( $product_id, 'product-fee-multiplier', true );
				}
				
				// WooCommerce Bulk Discount Support
				if(WCMp_Frontend_Product_Manager_Dependencies::fpm_wc_bulk_discount_plugin_active_check()) {
					$_bulkdiscount_enabled = get_post_meta( $product_id, '_bulkdiscount_enabled', true );
					$_bulkdiscount_text_info = get_post_meta( $product_id, '_bulkdiscount_text_info', true );
					$_bulkdiscounts = (array) get_post_meta( $product_id, '_bulkdiscounts', true );
				}
			}
		}
		
		$is_vendor = false;
		$current_user_id = $current_vendor_id;
		if( is_user_wcmp_vendor( $current_user_id ) ) $is_vendor = true;
		
		// Shipping Class List
		$product_shipping_class = get_terms( 'product_shipping_class', array('hide_empty' => 0));
		$variation_shipping_option_array = array('-1' => __('Same as parent', 'wcmp_frontend_product_manager'));
		$shipping_option_array = array('_no_shipping_class' => __('No shipping class', 'wcmp_frontend_product_manager'));
		foreach($product_shipping_class as $product_shipping) {
			if( $is_vendor ) {
				$vendor_id = get_woocommerce_term_meta( $product_shipping->term_id, 'vendor_id', true );
				if(!$vendor_id)	{
					//$variation_shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
					//$shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
				} else {
					if($vendor_id == $current_user_id) {
						$variation_shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
						$shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
					}
				}
			} else {
				$variation_shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
				$shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
			}
		}
		
		// Tax Class List
		$tax_classes         = WC_Tax::get_tax_classes();
		$classes_options     = array();
		$variation_tax_classes_options['parent'] = __( 'Same as parent', 'wcmp_frontend_product_manager' );
		$variation_tax_classes_options[''] = __( 'Standard', 'wcmp_frontend_product_manager' );
		$tax_classes_options[''] = __( 'Standard', 'wcmp_frontend_product_manager' );

		if ( ! empty( $tax_classes ) ) {

			foreach ( $tax_classes as $class ) {
				$tax_classes_options[ sanitize_title( $class ) ] = esc_html( $class );
				$variation_tax_classes_options[ sanitize_title( $class ) ] = esc_html( $class );
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
			'author'	   => $current_vendor_id,
			'post_status'      => array('publish'),
			'suppress_filters' => true 
		);
		$products_array = get_posts( $args );
		
		$product_categories   = get_terms( 'product_cat', 'orderby=name&hide_empty=0&parent=0' );
		$product_categories   = apply_filters( 'wcmp_frontend_product_cat_filter', $product_categories );
		global $wc_product_attributes;
	
		// Array of defined attribute taxonomies
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		?>
		<form id="product_manager_form" class="woocommerce">
			<?php
			if(isset($_REQUEST['fpm_msg']) && !empty($_REQUEST['fpm_msg'])) {
				$WCMp_fpm_messages = get_forntend_product_manager_messages();
				?>
				<div class="woocommerce-message" tabindex="-1"><?php echo $WCMp_fpm_messages[$_REQUEST['fpm_msg']]; ?></div>
				<?php
			}
			?>
			<div class="frontend_product_manager_product_types">
				<?php
				$product_types = array();
				if( is_user_wcmp_vendor( $current_user_id ) ) {
					if( $WCMp->vendor_caps->vendor_can('simple') ) $product_types['simple'] = __('Simple', 'wcmp_frontend_product_manager');
					if( $WCMp->vendor_caps->vendor_can('variable') ) $product_types['variable'] = __('Variable', 'wcmp_frontend_product_manager');
					if( $WCMp->vendor_caps->vendor_can('grouped') ) $product_types['grouped'] = __('Grouped', 'wcmp_frontend_product_manager');
					if( $WCMp->vendor_caps->vendor_can('external') ) $product_types['external'] = __('External/Affiliate', 'wcmp_frontend_product_manager');
				} else {
					$product_types = array('simple' => __('Simple', 'wcmp_frontend_product_manager'), 'variable' => __('Variable', 'wcmp_frontend_product_manager'), 'grouped' => __('Grouped', 'wcmp_frontend_product_manager'), 'external' => __('External/Affiliate', 'wcmp_frontend_product_manager'));
				}
				$product_types = apply_filters( 'wcmp_product_types', $product_types );
				
				if(!empty($product_types)) {
					if(!$product_id) {
						$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array( "product_type" => array('label' => __('Product Type', 'wcmp_frontend_product_manager') , 'type' => 'select', 'options' => $product_types, 'class' => 'regular-select', 'label_class' => 'pro_title') ));
						if( is_user_wcmp_vendor( $current_user_id ) ) {
							if($WCMp->vendor_caps->vendor_can('virtual') || $WCMp->vendor_caps->vendor_can('downloadable')) $WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array( "is_virtual_downloadable" => array('type' => 'text') ));
							if($WCMp->vendor_caps->vendor_can('virtual')) $WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array( "is_virtual" => array('desc' => __('Virtual', 'wcmp_frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele simple non-booking non-variable-subscription non-redq_rental non-auction', 'value' => 'enable', 'desc_class' => 'pro_ele simple non-booking non-variable-subscription non-redq_rental non-auction') ));
							if($WCMp->vendor_caps->vendor_can('downloadable')) $WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array( "is_downloadable" => array('desc' => __('Downloadable', 'wcmp_frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele simple non-booking non-variable-subscription non-redq_rental non-auction', 'value' => 'enable', 'desc_class' => 'pro_ele simple non-booking non-variable-subscription non-redq_rental non-auction') ));
						} else {
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array( "is_virtual_downloadable" => array('type' => 'text') ));
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array( "is_virtual" => array('desc' => __('Virtual', 'wcmp_frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele simple non-booking non-variable-subscription non-redq_rental non-auction', 'desc_class' => 'pro_ele simple non-booking non-variable-subscription non-redq_rental non-auction', 'value' => 'enable') ));
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array( "is_downloadable" => array('desc' => __('Downloadable', 'wcmp_frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele simple non-booking non-variable-subscription non-redq_rental non-auction', 'desc_class' => 'pro_ele simple non-booking non-variable-subscription non-redq_rental non-auction', 'value' => 'enable') ));
						}
					} else {
						$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array( "product_type" => array('type' => 'hidden', 'value' => $product_type) ) );
						if($is_virtual) $WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array( "is_virtual" => array('type' => 'hidden', 'class' => 'is_virtual_hidden', 'value' => $is_virtual ) ) );
						if($is_downloadable) $WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array( "is_downloadable" => array('type' => 'hidden', 'class' => 'is_downloadable_hidden', 'value' => $is_downloadable ) ) );
					}
				} else {
					_e('You do not have enough permission to submit a new product. Please contact site administrator.', 'wcmp_frontend_product_manager');
				}
				?>
			</div>
			
			<div id="frontend_product_manager_accordion">
			  <?php do_action('before_wcmp_fpm_template'); ?>
				<h3 class="pro_ele_head simple variable external grouped"><?php _e('General', 'wcmp_frontend_product_manager'); ?></h3>
				<div class="pro_ele_block simple variable external grouped">
					<p>
						<?php
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( apply_filters( 'wcmp_fpm_fields_general', array(  "title" => array('label' => __('Title', 'wcmp_frontend_product_manager') , 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped', 'label_class' => 'pro_title pro_ele simple variable external grouped', 'value' => $title),
																																															"sku" => array('label' => __('SKU', 'wcmp_frontend_product_manager') , 'type' => 'text', 'class' => (is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('sku')) ? 'regular-text pro_ele simple variable external grouped vendor_hidden' : 'regular-text pro_ele simple variable external grouped', 'label_class' => (is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('sku')) ? 'pro_title vendor_hidden' : 'pro_title', 'value' => $sku, 'hints' => __( 'SKU refers to a Stock-keeping unit, a unique identifier for each distinct product and service that can be purchased.', 'woocommerce' )),
																																															"product_url" => array('label' => __('Product URL', 'wcmp_frontend_product_manager') , 'type' => 'text', 'class' => 'regular-text pro_ele external', 'label_class' => 'pro_ele pro_title external', 'value' => $product_url, 'hints' => __( 'Enter the external URL to the product.', 'woocommerce' )),
																																															"button_text" => array('label' => __('Button Text', 'wcmp_frontend_product_manager') , 'type' => 'text', 'class' => 'regular-text pro_ele external', 'label_class' => 'pro_ele pro_title external', 'value' => $button_text, 'hints' => __( 'This text will be shown on the button linking to the external product.', 'woocommerce' )),
																																															"regular_price" => array('label' => __('Price', 'wcmp_frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')', 'type' => 'text', 'class' => 'regular-text pro_ele simple external non-booking non-subscription non-variable-subscription non-redq_rental non-auction', 'label_class' => 'pro_ele pro_title simple external non-booking non-subscription non-variable-subscription non-redq_rental non-auction', 'value' => $regular_price),
																																															"sale_price" => array('label' => __('Sale Price', 'wcmp_frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')', 'type' => 'text', 'class' => 'regular-text pro_ele simple external non-booking non-variable-subscription non-redq_rental non-auction', 'label_class' => 'pro_ele pro_title simple external non-booking non-variable-subscription non-redq_rental non-auction', 'value' => $sale_price),
																																															"sale_date_from" => array('label' => __('Sale Date From', 'wcmp_frontend_product_manager'), 'type' => 'text', 'placeholder' => __( 'From... YYYY-MM-DD', 'wcmp_frontend_product_manager' ), 'class' => 'regular-text pro_ele simple external non-booking non-variable-subscription non-redq_rental non-auction', 'label_class' => 'pro_ele pro_title simple external non-booking non-variable-subscription non-redq_rental non-auction', 'value' => $sale_date_from),
																																															"sale_date_upto" => array('label' => __('Sale Date Upto', 'wcmp_frontend_product_manager'), 'type' => 'text', 'placeholder' => __( 'To... YYYY-MM-DD', 'wcmp_frontend_product_manager' ), 'class' => 'regular-text pro_ele simple external non-booking non-variable-subscription non-redq_rental non-auction', 'label_class' => 'pro_ele pro_title simple external non-booking non-variable-subscription non-redq_rental non-auction', 'value' => $sale_date_upto),
																																															"visibility" => array('label' => __('Visibility', 'wcmp_frontend_product_manager'), 'type' => 'select', 'options' => array('visible' => __('Catalog/Search', 'wcmp_frontend_product_manager'), 'catalog' => __('Catalog', 'wcmp_frontend_product_manager'), 'search' => __('Search', 'wcmp_frontend_product_manager'), 'hidden' => __('Hidden', 'wcmp_frontend_product_manager')), 'class' => 'regular-select pro_ele simple variable external grouped', 'label_class' => 'pro_ele pro_title simple variable external grouped', 'value' => $visibility, 'hints' => __('Choose where this product should be displayed in your catalog. The product will always be accessible directly.', 'wcmp_frontend_product_manager')),
																																															"excerpt" => array('label' => __('Short Description', 'wcmp_frontend_product_manager') , 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external grouped' , 'label_class' => 'pro_title grouped', 'value' => $excerpt),
																																															"description" => array('label' => __('Description', 'wcmp_frontend_product_manager') , 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external grouped', 'label_class' => 'pro_title grouped', 'value' => $description),
																																															"pro_id" => array('type' => 'hidden', 'value' => $product_id)
																																											) , $product_id ) );
						?>
					</p>
				</div>
				<?php do_action( 'after_wcmp_fpm_general', $product_id ); ?>
				<?php if( !is_user_wcmp_vendor( $current_user_id ) || ( is_user_wcmp_vendor( $current_user_id ) && $WCMp->vendor_caps->vendor_can('downloadable') ) ) { ?>
				<h3 class="pro_ele_head simple downlodable non-booking non-variable-subscription non-redq_rental"><?php _e('Downloadable Options', 'wcmp_frontend_product_manager'); ?></h3>
				<div class="pro_ele_block simple downlodable non-booking non-variable-subscription non-redq_rental">
					<p>
						<?php
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array(  "downloadable_files" => array('label' => __('Files', 'wcmp_frontend_product_manager') , 'type' => 'multiinput', 'class' => 'regular-text pro_ele simple downlodable', 'label_class' => 'pro_title', 'value' => $downloadable_files, 'options' => array(
																																																  "name" => array('label' => __('Name', 'wcmp_frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text pro_ele simple downlodable', 'label_class' => 'pro_ele pro_title simple downlodable'),
																																																	"file" => array('label' => __('File', 'wcmp_frontend_product_manager'), 'type' => 'upload', 'mime' => 'Uploads', 'class' => 'regular-text pro_ele simple downlodable', 'label_class' => 'pro_ele pro_title simple downlodable')
																																															)),
																																															"download_limit" => array('label' => __('Download Limit', 'wcmp_frontend_product_manager'), 'type' => 'number', 'value' => $download_limit, 'placeholder' => __('Unlimited', 'wcmp_frontend_product_manager'), 'class' => 'regular-text pro_ele simple external', 'label_class' => 'pro_ele pro_title simple downlodable'),
																																															"download_expiry" => array('label' => __('Download Expiry', 'wcmp_frontend_product_manager'), 'type' => 'number', 'value' => $download_expiry, 'placeholder' => __('Never', 'wcmp_frontend_product_manager'), 'class' => 'regular-text pro_ele simple external', 'label_class' => 'pro_ele pro_title simple downlodable')
																																										));
						?>
					</p>
				</div>
				<?php } ?>
				<h3 class="pro_ele_head simple variable external grouped"><?php _e('Featured Image and Gallery', 'wcmp_frontend_product_manager'); ?></h3>
				<div class="pro_ele_block simple variable external grouped">
					<p>
						<?php
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array(  "featured_img" => array('label' => __('Featured Image', 'wcmp_frontend_product_manager') , 'type' => 'upload', 'class' => 'regular-text pro_ele simple variable external grouped', 'label_class' => 'pro_title', 'value' => $featured_img),
																																															"gallery_img" => array('label' => __('Gallery Images', 'wcmp_frontend_product_manager') , 'type' => 'multiinput', 'class' => 'regular-text pro_ele simple variable external grouped', 'label_class' => 'pro_title', 'value' => $gallery_img_urls, 'options' => array(
																																																	"image" => array('label' => __('Image', 'wcmp_frontend_product_manager'), 'type' => 'upload', 'prwidth' => 125),
																																															))
																																										));
						?>
					</p>
				</div>
				<h3 class="pro_ele_head simple variable external grouped"><?php _e('Category & Tags', 'wcmp_frontend_product_manager'); ?></h3>
				<div class="pro_ele_block simple variable external grouped">
					<p>
						<p class="pro_title"><strong><?php _e( 'Categories', 'wcmp_frontend_product_manager' ); ?></strong></p><label class="screen-reader-text" for="product_cats"><?php _e( 'Categories', 'wcmp_frontend_product_manager' ); ?></label>
						<select id="product_cats" name="product_cats[]" class="regular-select pro_ele_head simple variable external grouped" multiple="multiple" style="width: 60%; margin-bottom: 10px;">
							<?php
								if ( $product_categories ) {
									$WCMp_Frontend_Product_Manager->generateTaxonomyHTML( 'product_cat', $product_categories, $categories );
								}
							?>
						</select>
				  	<?php
				  	
						$product_taxonomies = get_object_taxonomies( 'product', 'objects' );
						if( !empty( $product_taxonomies ) ) {
							foreach( $product_taxonomies as $product_taxonomy ) {
								if( !in_array( $product_taxonomy->name, array( 'product_cat', 'product_tag' ) ) ) {
									if( $product_taxonomy->public && $product_taxonomy->show_ui && $product_taxonomy->meta_box_cb ) {
										// Fetching Saved Values
										$taxonomy_values_arr = array();
										if($product && !empty($product)) {
											$taxonomy_values = get_the_terms( $product_id, $product_taxonomy->name );
											if( !empty($taxonomy_values) ) {
												foreach($taxonomy_values as $pkey => $ptaxonomy) {
													$taxonomy_values_arr[] = $ptaxonomy->term_id;
												}
											}
										}
										?>
										<p class="pro_title"><strong><?php _e( $product_taxonomy->label, 'wcmp_frontend_product_manager' ); ?></strong></p><label class="screen-reader-text" for="<?php echo $product_taxonomy->name; ?>"><?php _e( $product_taxonomy->label, 'wcmp_frontend_product_manager' ); ?></label>
										<select id="<?php echo $product_taxonomy->name; ?>" name="product_custom_taxonomies[<?php echo $product_taxonomy->name; ?>][]" class="regular-select product_taxonomies pro_ele simple variable external grouped" multiple="multiple" style="width: 60%; margin-bottom: 10px;">
											<?php
												$product_taxonomy_terms   = get_terms( $product_taxonomy->name, 'orderby=name&hide_empty=0&parent=0' );
												if ( $product_taxonomy_terms ) {
													$WCMp_Frontend_Product_Manager->generateTaxonomyHTML( $product_taxonomy->name, $product_taxonomy_terms, $taxonomy_values_arr );
												}
											?>
										</select>
										<?php
									}
								}
							}
						}
						
						$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array(  "product_tags" => array('label' => __('Tags', 'wcmp_frontend_product_manager') , 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external grouped', 'label_class' => 'pro_title', 'value' => $product_tags, 'desc' => __('Separate Product Tags with commas', 'wcmp_frontend_product_manager'))
																																										));
						?>
					</p>
				</div>
				<h3 class="pro_ele_head simple variable grouped non-redq_rental non-booking <?php if(is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('inventory')) echo ' vendor_hidden'; ?>"><?php _e('Inventory', 'wcmp_frontend_product_manager'); ?></h3>
				<div class="pro_ele_block simple variable grouped non-redq_rental non-booking <?php if(is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('inventory')) echo ' vendor_hidden'; ?>">
					<p>
						<?php
								$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array(  "manage_stock" => array('label' => __('Manage Stock?', 'wcmp_frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele simple variable external manage_stock_ele', 'value' => 'enable', 'label_class' => 'pro_title checkbox_title pro_ele simple variable external manage_stock_ele', 'hints' => __('Enable stock management at product level', 'wcmp_frontend_product_manager'), 'dfvalue' => $manage_stock),
																																																"stock_qty" => array('label' => __('Stock Qty', 'wcmp_frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele simple variable external non_manage_stock_ele', 'label_class' => 'pro_title non_manage_stock_ele', 'value' => $stock_qty, 'hints' => __( 'Stock quantity. If this is a variable product this value will be used to control stock for all variations, unless you define stock at variation level.', 'woocommerce' )),
																																																"backorders" => array('label' => __('Allow Backorders?', 'wcmp_frontend_product_manager') , 'type' => 'select', 'options' => array('no' => __('Do not Allow', 'wcmp_frontend_product_manager'), 'notify' => __('Allow, but notify customer', 'wcmp_frontend_product_manager'), 'yes' => __('Allow', 'wcmp_frontend_product_manager')), 'class' => 'regular-select pro_ele simple variable external non_manage_stock_ele', 'label_class' => 'pro_title non_manage_stock_ele', 'value' => $backorders, 'hints' => __( 'If managing stock, this controls whether or not backorders are allowed. If enabled, stock quantity can go below 0.', 'woocommerce' )),
																																																"stock_status" => array('label' => __('Stock status', 'wcmp_frontend_product_manager') , 'type' => 'select', 'options' => array('instock' => __('In stock', 'wcmp_frontend_product_manager'), 'outofstock' => __('Out of stock', 'wcmp_frontend_product_manager')), 'class' => 'regular-select pro_ele simple grouped non-variable-subscription', 'label_class' => 'pro_ele pro_title simple grouped non-variable-subscription', 'value' => $stock_status, 'hints' => __( 'Controls whether or not the product is listed as "in stock" or "out of stock" on the frontend.', 'woocommerce' )),
																																																"sold_individually" => array('label' => __('Sold Individually', 'wcmp_frontend_product_manager') , 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele simple variable external', 'hints' => __('Enable this to only allow one of this item to be bought in a single order', 'wcmp_frontend_product_manager'), 'label_class' => 'pro_title checkbox_title pro_ele simple variable external manage_stock_ele', 'dfvalue' => $sold_individually)
																																												));
							?>
						</p>
				</div>
				<h3 class="pro_ele_head simple variable nonvirtual <?php if(is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('shipping')) echo ' vendor_hidden'; ?>"><?php _e('Shipping', 'wcmp_frontend_product_manager'); ?></h3>
				<div class="pro_ele_block simple variable nonvirtual <?php if(is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('shipping')) echo ' vendor_hidden'; ?>">
					<p>
						<?php
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( apply_filters( 'wcmp_fpm_fields_shipping', array(  "weight" => array('label' => __('Weight', 'wcmp_frontend_product_manager') . ' ('.get_option( 'woocommerce_weight_unit', 'kg' ).')' , 'type' => 'text', 'class' => 'regular-text pro_ele simple variable', 'label_class' => 'pro_title', 'value' => $weight),
																																															"length" => array('label' => __('Length', 'wcmp_frontend_product_manager') . ' ('.get_option( 'woocommerce_dimension_unit', 'cm' ).')', 'type' => 'text', 'class' => 'regular-text pro_ele simple variable', 'label_class' => 'pro_title', 'value' => $length),
																																															"width" => array('label' => __('Width', 'wcmp_frontend_product_manager') . ' ('.get_option( 'woocommerce_dimension_unit', 'cm' ).')', 'type' => 'text', 'class' => 'regular-text pro_ele simple variable', 'label_class' => 'pro_title', 'value' => $width),
																																															"height" => array('label' => __('Height', 'wcmp_frontend_product_manager') . ' ('.get_option( 'woocommerce_dimension_unit', 'cm' ).')', 'type' => 'text', 'class' => 'regular-text pro_ele simple variable', 'label_class' => 'pro_title', 'value' => $height),
																																															"shipping_class" => array('label' => __('Shipping class', 'wcmp_frontend_product_manager') , 'type' => 'select', 'options' => $shipping_option_array, 'class' => 'regular-select pro_ele simple variable', 'label_class' => 'pro_title', 'value' => $shipping_class)
																																										), $product_id ) );
						?>
					</p>
				</div>
				<?php if ( wc_tax_enabled() ) { ?>
					<h3 class="pro_ele_head simple variable <?php if(is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('taxes')) echo ' vendor_hidden'; ?>"><?php _e('Tax', 'wcmp_frontend_product_manager'); ?></h3>
					<div class="pro_ele_block simple variable <?php if(is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('taxes')) echo ' vendor_hidden'; ?>">
					  <p>
					    <?php
					      $WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array(  
																																															"tax_status" => array('label' => __('Tax Status', 'wcmp_frontend_product_manager') , 'type' => 'select', 'options' => array( 'taxable' => __( 'Taxable', 'wcmp_frontend_product_manager' ), 'shipping' => __( 'Shipping only', 'wcmp_frontend_product_manager' ), 'none' => _x( 'None', 'Tax status', 'wcmp_frontend_product_manager' ) ), 'class' => 'regular-select pro_ele simple variable', 'label_class' => 'pro_title', 'value' => $tax_status, 'hints' => __( 'Define whether or not the entire product is taxable, or just the cost of shipping it.', 'woocommerce' )),
																																															"tax_class" => array('label' => __('Tax Class', 'wcmp_frontend_product_manager') , 'type' => 'select', 'options' => $tax_classes_options, 'class' => 'regular-select pro_ele simple variable', 'label_class' => 'pro_title', 'value' => $tax_class, 'hints' => __( 'Choose a tax class for this product. Tax classes are used to apply different tax rates specific to certain types of product.', 'woocommerce' ))
																																										));
					    ?>
					  </p>
					</div>
				<?php } ?>
				<h3 class="pro_ele_head simple variable external grouped <?php if(is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('attribute')) echo ' vendor_hidden'; ?>"><?php _e('Attributes', 'wcmp_frontend_product_manager'); ?></h3>
				<div class="pro_ele_block simple variable external grouped <?php if(is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('attribute')) echo ' vendor_hidden'; ?>">
				
				  <p>
				    <select name="fpm_attribute_taxonomy" class="fpm_attribute_taxonomy">
							<option value=""><?php _e( 'Custom product attribute', 'woocommerce' ); ?></option>
							<?php
								if ( ! empty( $attribute_taxonomies ) ) {
									foreach ( $attribute_taxonomies as $tax ) {
										$attribute_taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name );
										$label = $tax->attribute_label ? $tax->attribute_label : $tax->attribute_name;
										echo '<option value="' . esc_attr( $attribute_taxonomy_name ) . '">' . esc_html( $label ) . '</option>';
									}
								}
							?>
						</select>
						<button type="button" class="button fpm_add_attribute"><?php _e( 'Add', 'woocommerce' ); ?></button>
				  </p>
				 
					<p>
						<?php
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array(  
																																															"attributes" => array('label' => __('Attributes', 'wcmp_frontend_product_manager') , 'type' => 'multiinput', 'class' => 'regular-text pro_ele simple variable external grouped', 'label_class' => 'pro_title', 'value' => $attributes, 'options' => array(
																																																  "term_name" => array( 'type' => 'hidden', 'label_class' => 'pro_title'),
																																																	"name" => array('label' => __('Name', 'wcmp_frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped', 'label_class' => 'pro_title'),
																																																	"value" => array('label' => __('Value(s):', 'wcmp_frontend_product_manager'), 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external grouped', 'placeholder' => __('Enter some text, some attributes by "|" separating values.', 'wcmp_frontend_product_manager'), 'label_class' => 'pro_title'),
																																																	"is_visible" => array('label' => __('Visible on the product page', 'wcmp_frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele simple variable external grouped', 'label_class' => 'pro_title checkbox_title'),
																																																	"is_variation" => array('label' => __('Use as Variation', 'wcmp_frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele variable variable-subscription', 'label_class' => 'pro_title checkbox_title pro_ele variable variable-subscription'),
																																																	"tax_name" => array('type' => 'hidden'),
																																																	"is_taxonomy" => array('type' => 'hidden')
																																															))
																																										));
							
							if( !empty($attributes_select_type) ) {
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( apply_filters( 'product_simple_fields_attributes', array(  
																																														"select_attributes" => array('type' => 'multiinput', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $attributes_select_type, 'options' => array(
																																															  "term_name" => array('type' => 'hidden'),
																																																"name" => array('label' => __('Name', 'wcmp_frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title'),
																																																"value" => array('label' => __('Value(s):', 'wcmp_frontend_product_manager'), 'type' => 'select', 'attributes' => array( 'multiple' => 'multiple', 'style' => 'width: 60%;' ), 'class' => 'regular-select pro_ele simple variable external grouped booking', 'label_class' => 'pro_title'),
																																																"is_visible" => array('label' => __('Visible on the product page', 'wcmp_frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele simple variable external grouped booking', 'label_class' => 'pro_title checkbox_title'),
																																																"is_variation" => array('label' => __('Use as Variation', 'wcmp_frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele variable variable-subscription', 'label_class' => 'pro_title checkbox_title pro_ele variable variable-subscription'),
																																																"tax_name" => array('type' => 'hidden'),
																																																"is_taxonomy" => array('type' => 'hidden')
																																														))
																																									)) );
						}
						?>
					</p>
				</div>
				<h3 class="pro_ele_head variable variations variable-subscription"><?php _e('Variations', 'wcmp_frontend_product_manager'); ?></h3>
				<div class="pro_ele_block variable variable-subscription">
				  <p>
						<div class="default_attributes_holder">
							<p class="pro_title selectbox_title"><strong><?php _e( 'Default Form Values:', 'wcmp_frontend_product_manager' ); ?></strong></p>
							<input type="hidden" name="default_attributes_hidden" data-name="default_attributes_hidden" value="<?php echo esc_attr( $default_attributes ); ?>" />
						</div>
					</p>
					<p>
					  <?php
					    $WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array(  
					    	                                                                              "variations_options" => array('label' => __('Variations Options', 'wcmp_frontend_product_manager') , 'type' => 'select', 'options' => array('' => __( 'Choose option', 'wcmp_frontend_product_manager' ), 'set_regular_price' => __('Set regular prices', 'wcmp_frontend_product_manager'), 'regular_price_increase' => __('Regular price increase', 'wcmp_frontend_product_manager'), 'regular_price_decrease' => __('Regular price decrease', 'wcmp_frontend_product_manager'), 'set_sale_price' => __('Set sale prices', 'wcmp_frontend_product_manager'), 'sale_price_increase' => __('Sale price increase', 'wcmp_frontend_product_manager'), 'sale_price_decrease' => __('Sale price decrease', 'wcmp_frontend_product_manager')), 'class' => 'regular-select pro_ele variable-subscription variable', 'label_class' => 'pro_title'),
																																															"variations" => array('label' => __('Variations', 'wcmp_frontend_product_manager') , 'type' => 'multiinput', 'class' => 'pro_ele variable variable-subscription', 'label_class' => 'pro_title', 'value' => $variations, 'options' => apply_filters( 'wcmp_fpm_manage_fields_variations', array(
																																																  "id" => array('type' => 'hidden', 'class' => 'variation_id'),
																																																  "enable" => array('label' => __('Enable', 'wcmp_frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele variable-subscription variable', 'label_class' => 'pro_title checkbox_title'),
																																																  "is_virtual" => array('label' => __('Virtual', 'wcmp_frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => (is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('virtual')) ? 'regular-checkbox pro_ele variable-subscription variable variation_is_virtual_ele vendor_hidden' : 'regular-checkbox pro_ele variable-subscription variable variation_is_virtual_ele', 'label_class' => (is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('virtual')) ? 'pro_title checkbox_title vendor_hidden' : 'pro_title checkbox_title'),
																																																  "is_downloadable" => array('label' => __('Downloadable', 'wcmp_frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => (is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('downloadable')) ? 'regular-checkbox pro_ele variable-subscription variable variation_is_downloadable_ele vendor_hidden' : 'regular-checkbox pro_ele variable-subscription variable variation_is_downloadable_ele', 'label_class' => (is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('virtual')) ? 'pro_title checkbox_title vendor_hidden' : 'pro_title checkbox_title'),
																																																  "image" => array('label' => __('Image', 'wcmp_frontend_product_manager'), 'type' => 'upload', 'class' => 'regular-text pro_ele variable-subscription variable', 'label_class' => 'pro_title'),
																																																	"sku" => array('label' => __('SKU', 'wcmp_frontend_product_manager'), 'type' => 'text', 'class' => (is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('sku')) ? 'regular-text pro_ele variable-subscription variable vendor_hidden' : 'regular-text pro_ele variable-subscription variable', 'label_class' => (is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('sku')) ? 'pro_title vendor_hidden' : 'pro_title'),
																																																	"regular_price" => array('label' => __('Regular Price', 'wcmp_frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')', 'type' => 'text', 'class' => 'regular-text pro_ele variable', 'label_class' => 'pro_title pro_ele variable'),
																																																	"sale_price" => array('label' => __('Sale Price', 'wcmp_frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')', 'type' => 'text', 'class' => 'regular-text pro_ele variable-subscription variable', 'label_class' => 'pro_title'),
																																																	"manage_stock" => array('label' => __('Manage Stock', 'wcmp_frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'value' => 'enable', 'class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('inventory') ) ? 'regular-checkbox pro_ele variable-subscription variable variation_manage_stock_ele vendor_hidden' : 'regular-checkbox pro_ele variable-subscription variable variation_manage_stock_ele', 'label_class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('inventory') ) ? 'pro_title checkbox_title vendor_hidden' : 'pro_title checkbox_title'),
																																																	"stock_qty" => array('label' => __('Stock Qty', 'wcmp_frontend_product_manager') , 'type' => 'number', 'class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('inventory') ) ? 'regular-text pro_ele variable-subscription variable variation_non_manage_stock_ele vendor_hidden' : 'regular-text pro_ele variable-subscription variable variation_non_manage_stock_ele', 'label_class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('inventory') ) ? 'pro_title variation_non_manage_stock_ele vendor_hidden' : 'pro_title variation_non_manage_stock_ele'),
																																																	"backorders" => array('label' => __('Allow Backorders?', 'wcmp_frontend_product_manager') , 'type' => 'select', 'options' => array('no' => __('Do not Allow', 'wcmp_frontend_product_manager'), 'notify' => __('Allow, but notify customer', 'wcmp_frontend_product_manager'), 'yes' => __('Allow', 'wcmp_frontend_product_manager')), 'class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('inventory') ) ? 'regular-select pro_ele variable-subscription variable variation_non_manage_stock_ele vendor_hidden' : 'regular-select pro_ele variable-subscription variable variation_non_manage_stock_ele', 'label_class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('inventory') ) ? 'pro_title variation_non_manage_stock_ele vendor_hidden' : 'pro_title variation_non_manage_stock_ele'),
																																																	"stock_status" => array('label' => __('Stock status', 'wcmp_frontend_product_manager') , 'type' => 'select', 'options' => array('instock' => __('In stock', 'wcmp_frontend_product_manager'), 'outofstock' => __('Out of stock', 'wcmp_frontend_product_manager')), 'class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('inventory') ) ? 'regular-select pro_ele variable-subscription variable vendor_hidden' : 'regular-select pro_ele variable-subscription variable', 'label_class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('inventory') ) ? 'pro_title vendor_hidden': 'pro_title'),
																																																	"weight" => array('label' => __('Weight', 'wcmp_frontend_product_manager') , 'type' => 'text', 'class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('shipping') ) ? 'regular-text pro_ele variable-subscription variable variation_non_virtual_ele vendor_hidden' : 'regular-text pro_ele variable-subscription variable variation_non_virtual_ele', 'label_class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('shipping') ) ? 'pro_title variation_non_virtual_ele vendor_hidden' : 'pro_title variation_non_virtual_ele'),
																																																	"length" => array('label' => __('Length', 'wcmp_frontend_product_manager') , 'type' => 'text', 'class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('shipping') ) ? 'regular-text pro_ele variable-subscription variable variation_non_virtual_ele vendor_hidden' : 'regular-text pro_ele variable-subscription variable variation_non_virtual_ele', 'label_class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('shipping') ) ? 'pro_title variation_non_virtual_ele vendor_hidden' : 'pro_title variation_non_virtual_ele'),
																																																	"width" => array('label' => __('Width', 'wcmp_frontend_product_manager') , 'type' => 'text', 'class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('shipping') ) ? 'regular-text pro_ele variable-subscription variable variation_non_virtual_ele vendor_hidden' : 'regular-text pro_ele variable-subscription variable variation_non_virtual_ele', 'label_class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('shipping') ) ? 'pro_title variation_non_virtual_ele vendor_hidden' : 'pro_title variation_non_virtual_ele'),
																																																	"height" => array('label' => __('Height', 'wcmp_frontend_product_manager') , 'type' => 'text', 'class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('shipping') ) ? 'regular-text pro_ele variable-subscription variable variation_non_virtual_ele vendor_hidden' : 'regular-text pro_ele variable-subscription variable variation_non_virtual_ele', 'label_class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('shipping') ) ? 'pro_title variation_non_virtual_ele vendor_hidden' : 'pro_title variation_non_virtual_ele'),
																																																	"downloadable_file" => array('label' => __('File', 'wcmp_frontend_product_manager'), 'type' => 'upload', 'mime' => 'doc', 'class' => 'regular-text pro_ele variable-subscription variable variation_downloadable_ele', 'label_class' => 'pro_title variation_downloadable_ele'),
																																																	"downloadable_file_name" => array('label' => __('File Name', 'wcmp_frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text pro_ele variable-subscription variable variation_downloadable_ele', 'label_class' => 'pro_title variation_downloadable_ele'),
																																																	"download_limit" => array('label' => __('Download Limit', 'wcmp_frontend_product_manager') , 'type' => 'number', 'placeholder' => __('Unlimited', 'wcmp_frontend_product_manager'), 'class' => 'regular-text pro_ele variable-subscription variable variation_downloadable_ele', 'label_class' => 'pro_title variation_downloadable_ele'),
																																																	"download_expiry" => array('label' => __('Download Expiry', 'wcmp_frontend_product_manager') , 'type' => 'number', 'placeholder' => __('Never', 'wcmp_frontend_product_manager'), 'class' => 'regular-text pro_ele variable-subscription variable variation_downloadable_ele', 'label_class' => 'pro_title variation_downloadable_ele'),
																																																	"shipping_class" => array('label' => __('Shipping class', 'wcmp_frontend_product_manager') , 'type' => 'select', 'options' => $variation_shipping_option_array, 'class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('shipping') ) ? 'regular-select pro_ele variable-subscription variable vendor_hidden' : 'regular-select pro_ele variable-subscription variable', 'label_class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('shipping') ) ? 'pro_title vendor_hidden' : 'pro_title'),
																																																	"tax_class" => array('label' => __('Tax class', 'wcmp_frontend_product_manager') , 'type' => 'select', 'options' => $variation_tax_classes_options, 'class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('tax') ) ? 'regular-select pro_ele variable-subscription variable vendor_hidden' : 'regular-select pro_ele variable-subscription variable', 'label_class' => ( is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('tax') ) ? 'pro_title vendor_hidden' : 'pro_title'),
																																																	"description" => array('label' => __('Description', 'wcmp_frontend_product_manager') , 'type' => 'textarea', 'class' => 'regular-textarea pro_ele variable-subscription variable', 'label_class' => 'pro_title'),
																																																	"attributes" => array('type' => 'hidden')
																																															), $variations, $variation_shipping_option_array, $variation_tax_classes_options ) )
																																										));
					  ?>
					</p>
				</div>
				
				<h3 class="pro_ele_head simple variable external grouped <?php if(is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('linked_products')) echo ' vendor_hidden'; ?>"><?php _e('Linked Products', 'wcmp_frontend_product_manager'); ?></h3>
				<div class="pro_ele_block simple variable external grouped <?php if(is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('linked_products')) echo ' vendor_hidden'; ?>">
					<p>
						<p class="upsell_ids pro_ele pro_title simple variable external grouped"><strong><?php _e('Up-sells', 'wcmp_frontend_product_manager'); ?></strong><span class="img_tip" data-desc="Up-sells are products which you recommend instead of the currently viewed product, for example, products that are more profitable or better quality or more expensive."></span></p>
						<label class="screen-reader-text" for="upsell_ids"><?php _e('Up-sells', 'wcmp_frontend_product_manager'); ?></label>
						<select id="upsell_ids" name="upsell_ids[]" class="regular-select pro_ele simple variable external grouped" multiple="multiple" style="width: 60%;">
						  <?php
								if ( $products_array ) foreach($products_array as $products_single) {
									echo '<option value="' . esc_attr( $products_single->ID ) . '"' . selected( in_array( $products_single->ID, $upsell_ids ), true, false ) . '>' . esc_html( $products_single->post_title ) . '</option>';
								}
							?>
						</select>
						
						<p class="crosssell_ids pro_ele pro_title simple variable external grouped"><strong><?php _e('Cross-sells', 'wcmp_frontend_product_manager'); ?></strong><span class="img_tip" data-desc="Cross-sells are products which you promote in the cart, based on the current product."></span></p>
						<label class="screen-reader-text" for="crosssell_ids"><?php _e('Cross-sells', 'wcmp_frontend_product_manager'); ?></label>
						<select id="crosssell_ids" name="crosssell_ids[]" class="regular-select pro_ele simple variable external grouped" multiple="multiple" style="width: 60%;">
						  <?php
								if ( $products_array ) foreach($products_array as $products_single) {
									echo '<option value="' . esc_attr( $products_single->ID ) . '"' . selected( in_array( $products_single->ID, $crosssell_ids ), true, false ) . '>' . esc_html( $products_single->post_title ) . '</option>';
								}
							?>
						</select>
					</p>
				</div>
				
				<h3 class="pro_ele_head grouped"><?php _e('Grouped Products', 'wcmp_frontend_product_manager'); ?></h3>
				<div class="pro_ele_block grouped">
					<p>
						<p class="children pro_ele pro_title grouped"><strong><?php _e('Grouped Products', 'wcmp_frontend_product_manager'); ?></strong><span class="img_tip" data-desc="This lets you choose which products are part of this group."></span></p>
						<label class="screen-reader-text" for="children"><?php _e('Grouped Products', 'wcmp_frontend_product_manager'); ?></label>
						<select id="children" name="children[]" class="regular-select pro_ele grouped" multiple="multiple" style="width: 60%;">
						  <?php
								if ( $products_array ) foreach($products_array as $products_single) {
									echo '<option value="' . esc_attr( $products_single->ID ) . '"' . selected( in_array( $products_single->ID, $children ), true, false ) . '>' . esc_html( $products_single->post_title ) . '</option>';
								}
							?>
						</select>
					</p>
				</div>
				
				<h3 class="pro_ele_head simple variable external grouped booking <?php if(is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('advanced')) echo ' vendor_hidden'; ?>"><?php _e('Advanced', 'wcmp_frontend_product_manager'); ?></h3>
				<div class="pro_ele_block simple variable external grouped booking <?php if(is_user_wcmp_vendor( $current_user_id ) && !$WCMp->vendor_caps->vendor_can('advanced')) echo ' vendor_hidden'; ?>">
					<p>
						<?php
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( apply_filters( 'wcmp_fpm_manage_fields_advanced', array(  
																																														"enable_reviews" => array('label' => __('Enable reviews', 'wcmp_frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele simple variable external grouped', 'value' => 'enable', 'label_class' => 'pro_title checkbox_title grouped', 'dfvalue' => $enable_reviews),
																																														"menu_order" => array('label' => __('Menu Order', 'wcmp_frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele simple variable external grouped', 'label_class' => 'pro_title grouped', 'value' => $menu_order, 'hints' => __( 'Custom ordering position.', 'woocommerce' )),
																																														"purchase_note" => array('label' => __('Purchase Note', 'wcmp_frontend_product_manager') , 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable grouped', 'label_class' => 'pro_ele pro_title simple variable grouped', 'value' => $purchase_note, 'hints' => __( 'Enter an optional note to send the customer after purchase.', 'woocommerce' ))
																																									), $product_id ) );
						?>
					</p>
				</div>
				
				<?php if(WCMp_Frontend_Product_Manager_Dependencies::fpm_yoast_plugin_active_check()) { ?>
					<h3 class="pro_ele_head pro_ele_yoast_head simple variable external grouped booking"><?php _e('Yoast SEO', 'wcmp_frontend_product_manager'); ?></h3>
					<div class="pro_ele_block pro_ele_yoast_block simple variable external grouped booking">
						<p>
							<?php
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( apply_filters( 'product_manage_fields_yoast', array(  
																																																	"yoast_wpseo_focuskw_text_input" => array('label' => __('Enter a focus keyword', 'wcmp_frontend_product_manager') , 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title pro_ele simple variable external grouped booking', 'value' => $yoast_wpseo_focuskw_text_input, 'hints' => __( 'It should appear in title and first paragraph of the copy.', 'wcmp_frontend_product_manager' )),
																																																	"yoast_wpseo_metadesc" => array('label' => __('Meta description', 'wcmp_frontend_product_manager') , 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external grouped booking', 'label_class' => 'pro_ele pro_title simple variable external grouped booking', 'value' => $yoast_wpseo_metadesc, 'hints' => __( 'It should not be more than 156 characters.', 'wcmp_frontend_product_manager' ))
																																												)) );
							?>
						</p>
					</div>
				<?php } ?>
				
				<?php if(WCMp_Frontend_Product_Manager_Dependencies::fpm_wc_tabs_lite_plugin_active_check()) { ?>
					<h3 class="pro_ele_head simple variable external grouped booking"><?php _e('Custom Tabs', 'wcmp_frontend_product_manager'); ?></h3>
					<div class="pro_ele_block simple variable external grouped booking">
						<p>
							<?php
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( apply_filters( 'product_manage_fields_wc_tabs_lite', array( 
																																															"product_tabs" => array('label' => __('Tabs', 'wcmp_frontend_product_manager') , 'type' => 'multiinput', 'class' => 'pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $product_tabs, 'options' => array(  
																																																	"title" => array('label' => __('Title', 'wcmp_frontend_product_manager') , 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title pro_ele simple variable external grouped booking', 'hints' => __( 'Required for tab to be visible', 'wcmp_frontend_product_manager' )),
																																																	"content" => array('label' => __('Content', 'wcmp_frontend_product_manager') , 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external grouped booking', 'label_class' => 'pro_ele pro_title simple variable external grouped booking', 'placeholder' => __( 'HTML or Text to display ...', 'wcmp_frontend_product_manager' ))
																																															) ) 
																																														) ) );
						?>
						</p>
					</div>
				<?php } ?>
				
				<?php if(WCMp_Frontend_Product_Manager_Dependencies::fpm_wc_product_fees_plugin_active_check()) { ?>
					<h3 class="pro_ele_head simple variable external grouped booking"><?php _e('Product Fees', 'wcmp_frontend_product_manager'); ?></h3>
					<div class="pro_ele_block simple variable external grouped booking">
						<p>
							<?php
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( apply_filters( 'product_manage_fields_yoast', array(  
																																																	"product-fee-name" => array('label' => __('Fee Name', 'wcmp_frontend_product_manager') , 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title pro_ele simple variable external grouped booking', 'value' => $product_fee_name, 'hints' => __( 'This will be shown at the checkout description the added fee.', 'wcmp_frontend_product_manager' )),
																																																	"product-fee-amount" => array('label' => __('Fee Amount', 'wcmp_frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')' , 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_ele pro_title simple variable external grouped booking', 'value' => $product_fee_amount, 'hints' => __( 'Enter a monetary decimal without any currency symbols or thousand separator. This field also accepts percentages.', 'wcmp_frontend_product_manager' )),
																																																	"product-fee-multiplier" => array('label' => __('Multiple Fee by Quantity', 'wcmp_frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele simple variable external grouped booking', 'value' => 'yes', 'label_class' => 'pro_title checkbox_title simple variable external grouped booking', 'hints' => __( 'Multiply the fee by the quantity of this product that is added to the cart.', 'wcmp_frontend_product_manager' ), 'dfvalue' => $product_fee_multiplier ),
																																												)) );
							?>
						</p>
					</div>
				<?php } ?>
				
				<?php if(WCMp_Frontend_Product_Manager_Dependencies::fpm_wc_bulk_discount_plugin_active_check()) { ?>
					<h3 class="pro_ele_head simple variable external grouped booking"><?php _e('Bulk Discount', 'wcmp_frontend_product_manager'); ?></h3>
					<div class="pro_ele_block simple variable external grouped booking">
						<p>
							<?php
							$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( apply_filters( 'product_manage_fields_yoast', array(  
																																																	"_bulkdiscount_enabled" => array('label' => __('Bulk Discount enabled', 'wcmp_frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele simple variable external grouped booking', 'value' => 'yes', 'label_class' => 'pro_title checkbox_title simple variable external grouped booking', 'dfvalue' => $_bulkdiscount_enabled ),
																																																	"_bulkdiscount_text_info" => array('label' => __('Bulk discount special offer text in product description', 'wcmp_frontend_product_manager') , 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external grouped booking', 'label_class' => 'pro_ele pro_title simple variable external grouped booking', 'value' => $_bulkdiscount_text_info ),
																																																	"_bulkdiscounts" => array('label' => __('Discount Rules', 'wcmp_frontend_product_manager') , 'type' => 'multiinput', 'custom_attributes' => array( 'limit' => 5 ), 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $_bulkdiscounts, 'options' => array(
																																																					"quantity" => array('label' => __('Quantity (min.)', 'wcmp_frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title'),
																																																					"discount" => array('label' => __('Discount (%)', 'wcmp_frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title'),
																																																			))
																																												)) );
							?>
						</p>
					</div>
				<?php } ?>
				
				<?php if(WCMp_Frontend_Product_Manager_Dependencies::fpm_mappress_plugin_active_check()) { ?>
					
				<?php } ?>
				
				<?php if(WCMp_Frontend_Product_Manager_Dependencies::fpm_toolset_plugin_active_check()) { ?>
					<?php
					include_once( WPCF_EMBEDDED_ABSPATH . '/includes/fields-post.php' );
					$product_post = get_post();
					$product_post->post_type = 'product';
					$field_groups = wpcf_admin_post_get_post_groups_fields( $product_post );
					
					if( !empty( $field_groups )) {
						foreach( $field_groups as $field_group_index => $field_group ) {
							//If Access plugin activated
							if ( function_exists( 'wpcf_access_register_caps' ) ) {
								//If user can't view own profile fields
								if ( !current_user_can( 'view_fields_in_edit_page_' . $field_group['slug'] ) ) {
									continue;
								}
								//If user can modify current group in own profile
								if ( !current_user_can( 'modify_fields_in_edit_page_' . $field_group['slug'] ) ) {
									continue;
								}
							}
							if ( isset( $group['__show_meta_box'] ) && $group['__show_meta_box'] == false ) continue;
							$field_group_load = Types_Field_Group_Post_Factory::load( $field_group['slug'] );
							if( null === $field_group_load ) continue;
							
							// WooCommerce Filter Views discard
							if( $field_group['slug'] == 'woocommerce-views-filter-fields' ) continue;
							
							if ( !empty( $field_group['fields'] ) ) { 
								?>
								<h3 class="pro_ele_head simple variable external grouped booking"><?php echo $field_group['name']; ?></h3>
								<div class="pro_ele_block simple variable external grouped booking">
								  <p>
										<?php
										if ( !empty( $field_group['fields'] ) ) {
											foreach( $field_group['fields'] as $field_group_field ) {
												//print_r($field_group_field);
												$field_value = '';
												if( $product_id ) $field_value = get_post_meta( $product_id, $field_group_field['meta_key'], true );
												if ( wpcf_admin_is_repetitive( $field_group_field ) ) {
													$field_value = array();
													$field_value_repetatives = (array) get_post_meta( $product_id, $field_group_field['meta_key'] );
													if( !empty( $field_value_repetatives ) ) {
														foreach( $field_value_repetatives as $field_value_repetative ) {
															$field_value[] = array( 'field' => $field_value_repetative );
														}
													}
												}
												switch( $field_group_field['type'] ) {
													case 'url':
													case 'phone':
													case 'textfield':
													case 'google_address':
														if ( wpcf_admin_is_repetitive( $field_group_field ) ) {
															$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'type' => 'multiinput', 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'label_class' => 'pro_title', 'value' => $field_value, 'options' => array (
																																																							'field' => array( 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped booking' )
																																																						) ) ) );
														} else {
															$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $field_value ) ) );
														}
													break;
													
													case 'numeric':
														if ( wpcf_admin_is_repetitive( $field_group_field ) ) {
															$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'type' => 'multiinput', 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'label_class' => 'pro_title', 'value' => $field_value, 'options' => array (
																																																							'field' => array( 'type' => 'number', 'class' => 'regular-text pro_ele simple variable external grouped booking' )
																																																						) ) ) );
														} else {
															$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'number', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $field_value ) ) );
														}
													break;
													
													case 'wysiwyg':
													case 'textarea':
														if ( wpcf_admin_is_repetitive( $field_group_field ) ) {
															$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'type' => 'multiinput', 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'label_class' => 'pro_title', 'value' => $field_value, 'options' => array (
																																																							'field' => array( 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external grouped booking' )
																																																						) ) ) );
														} else {
															$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $field_value ) ) );
														}
													break;
													
													case 'date':
														if ( wpcf_admin_is_repetitive( $field_group_field ) ) {
															$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'type' => 'multiinput', 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'label_class' => 'pro_title', 'value' => $field_value, 'options' => array (
																																																							'field' => array( 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'class' => 'regular-text pro_ele dc_datepicker simple variable external grouped booking' )
																																																						) ) ) );
														} else {
															$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'class' => 'regular-text pro_ele dc_datepicker simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $field_value ) ) );
														}
													break;
													
													case 'timepicker':
														if ( wpcf_admin_is_repetitive( $field_group_field ) ) {
															$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'type' => 'multiinput', 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'label_class' => 'pro_title', 'value' => $field_value, 'options' => array (
																																																							'field' => array( 'type' => 'time', 'class' => 'regular-text pro_ele simple variable external grouped booking' )
																																																						) ) ) );
														} else {
															$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'time', 'class' => 'regular-text pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $field_value ) ) );
														}
													break;
													
													case 'checkbox':
														$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele simple variable external grouped booking', 'label_class' => 'pro_title checkbox_title', 'value' => $field_group_field['data']['set_value'], 'dfvalue' => $field_value ) ) );
													break;
													
													case 'radio':
														$radio_opt_vals = array();
														if( !empty ( $field_group_field['data']['options'] ) ) {
															foreach( $field_group_field['data']['options'] as $radio_option ) {
																if( !empty($radio_option) && isset( $radio_option['value'] ) && isset( $radio_option['title'] ) ) {
																	$radio_opt_vals[$radio_option['value']] = $radio_option['title'];
																}
															}
														}
														$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'] , 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'radio', 'class' => 'regular-select pro_ele', 'label_class' => 'pro_title', 'options' => $radio_opt_vals, 'value' => $field_value ) ) );
													break;
													
													case 'select':
														$select_opt_vals = array( '' => __( '--- not set ---', 'wcmp_frontend_product_manager' ) );
														if( !empty ( $field_group_field['data']['options'] ) ) {
															foreach( $field_group_field['data']['options'] as $select_option ) {
																if( !empty($select_option) && isset( $select_option['value'] ) && isset( $select_option['title'] ) ) {
																	$select_opt_vals[$select_option['value']] = $select_option['title'];
																}
															}
														}
														
														$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'] , 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'select', 'class' => 'regular-select pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'options' => $select_opt_vals, 'value' => $field_value ) ) );
													break;
													
													case 'image':
														if ( wpcf_admin_is_repetitive( $field_group_field ) ) {
															$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'type' => 'multiinput', 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'class' => 'pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $field_value, 'options' => array (
																																																							'field' => array( 'type' => 'upload' )
																																																						) ) ) );
														} else {
															$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'upload', 'class' => 'pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $field_value ) ) );
														}
													break;
													
													case 'file':
													case 'audio':
													case 'video':
														if ( wpcf_admin_is_repetitive( $field_group_field ) ) {
															$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'type' => 'multiinput', 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'class' => 'pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $field_value, 'options' => array (
																																																							'field' => array( 'type' => 'upload', 'mime' => 'Uploads' )
																																																						) ) ) );
														} else {
															$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field(  array( $field_group_field['meta_key'] => array( 'label' => $field_group_field['name'], 'desc' => $field_group_field['description'], 'name' => 'wpcf[' . $field_group_field['meta_key'] . ']', 'type' => 'upload', 'mime' => 'Uploads', 'class' => 'pro_ele simple variable external grouped booking', 'label_class' => 'pro_title', 'value' => $field_value ) ) );
														}
													break;
												}
											}
										}
										?>
									</p>
								</div>
								<?php
							}
						}
					}
					?>
				<?php } ?>
				
				<?php do_action( 'end_wcmp_fpm_products_manage', $product_id ); ?>
				
				<?php do_action('after_wcmp_fpm_template'); ?> 
			</div>
			<?php if(!empty($product_types)) { ?>
				<div id="product_manager_submit">
					<input type="submit" name="submit-data" value="<?php _e('Submit', 'wcmp_frontend_product_manager'); ?>" id="pruduct_manager_submit_button" />
					<?php if(!isset($_REQUEST['pro_id']) || (isset($_REQUEST['pro_id']) && (get_post_status($_REQUEST['pro_id']) == 'draft'))) { ?>
					<input type="submit" name="draft-data" value="<?php _e('Draft', 'wcmp_frontend_product_manager'); ?>" id="pruduct_manager_draft_button" />
					<?php } ?>
				</div>
			<?php } ?>
		</form>
		<?php
		
		do_action('wcmp-frontend-product-manager_template');

	}
}
