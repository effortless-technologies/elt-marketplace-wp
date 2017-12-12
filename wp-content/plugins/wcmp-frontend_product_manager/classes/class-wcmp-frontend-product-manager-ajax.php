<?php
class WCMp_Frontend_Product_Manager_Ajax {

	public function __construct() {
		add_action('wp_ajax_frontend_product_manager', array( &$this, 'frontend_product_manager' ) );
    
    add_action('wp_ajax_generate_taxonomy_attributes', array( &$this, 'generate_taxonomy_attributes' ) );
    
    add_action('wp_ajax_generate_variation_attributes', array( &$this, 'generate_variation_attributes' ) );
    
    add_action('wp_ajax_delete_fpm_product', array( &$this, 'delete_fpm_product' ) );
    
    // Frontend Coupon Manager
    add_action('wp_ajax_frontend_coupon_manager', array( &$this, 'frontend_coupon_manager' ) );
	}
	
	public function generate_taxonomy_attributes() {
		global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager, $wc_product_attributes;
		
		$att_taxonomy = $_POST['taxonomy'];
		$attribute_taxonomy = $wc_product_attributes[ $att_taxonomy ];
		$attributes = array();
		$attributes[0]['term_name'] = $att_taxonomy;
		$attributes[0]['name'] = wc_attribute_label( $att_taxonomy );
		$attributes[0]['value'] = '';
		$attributes[0]['tax_name'] = $att_taxonomy;
	  $attributes[0]['is_taxonomy'] = 1;
		$args = array(
										'orderby'    => 'name',
										'hide_empty' => 0
									);
		$all_terms = get_terms( $att_taxonomy, apply_filters( 'woocommerce_product_attribute_terms', $args ) );
		
		if ( 'select' === $attribute_taxonomy->attribute_type ) {
			if ( $all_terms ) {
				foreach ( $all_terms as $term ) {
					$attributes_option[$term->term_id] = esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) );
				}
			}
			
			$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array(  
																																												"attributes" => array('label' => __('Attributes', 'wcmp_frontend_product_manager') , 'type' => 'multiinput', 'class' => 'regular-text pro_ele simple variable external', 'label_class' => 'pro_title', 'value' => $attributes, 'options' => array(
																																														"term_name" => array( 'type' => 'hidden', 'label_class' => 'pro_title'),
																																														"name" => array('label' => __('Name', 'wcmp_frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external', 'label_class' => 'pro_title'),
																																														"value" => array('label' => __('Value(s):', 'wcmp_frontend_product_manager'), 'type' => 'select', 'attributes' => array('multiple' => 'multiple'), 'class' => 'regular-select pro_ele simple variable external', 'options' => $attributes_option, 'label_class' => 'pro_title'),
																																														"is_visible" => array('label' => __('Visible on the product page', 'wcmp_frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele simple variable external', 'label_class' => 'pro_title checkbox_title'),
																																														"is_variation" => array('label' => __('Use as Variation', 'wcmp_frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele variable variable-subscription', 'label_class' => 'pro_title checkbox_title pro_ele variable variable-subscription'),
																																														"tax_name" => array('type' => 'hidden'),
																																														"is_taxonomy" => array('type' => 'hidden')
																																												))
																																											));
		} else {
			$WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array(  
																																												"attributes" => array('label' => __('Attributes', 'wcmp_frontend_product_manager') , 'type' => 'multiinput', 'class' => 'regular-text pro_ele simple variable external', 'label_class' => 'pro_title', 'value' => $attributes, 'options' => array(
																																														"term_name" => array( 'type' => 'hidden', 'label_class' => 'pro_title'),
																																														"name" => array('label' => __('Name', 'wcmp_frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external', 'label_class' => 'pro_title'),
																																														"value" => array('label' => __('Value(s):', 'wcmp_frontend_product_manager'), 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external', 'placeholder' => __('Enter some text, some attributes by "|" separating values.', 'wcmp_frontend_product_manager'), 'label_class' => 'pro_title'),
																																														"is_visible" => array('label' => __('Visible on the product page', 'wcmp_frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele simple variable external', 'label_class' => 'pro_title checkbox_title'),
																																														"is_variation" => array('label' => __('Use as Variation', 'wcmp_frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele variable variable-subscription', 'label_class' => 'pro_title checkbox_title pro_ele variable variable-subscription'),
																																														"tax_name" => array('type' => 'hidden'),
																																														"is_taxonomy" => array('type' => 'hidden')
																																												))
																																											));
		}
		die();
	}
	
	public function generate_variation_attributes() {
		global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager;
	  
	  $product_manager_form_data = array();
	  parse_str($_POST['product_manager_form'], $product_manager_form_data);
	  
	  if(isset($product_manager_form_data['attributes']) && !empty($product_manager_form_data['attributes'])) {
			$pro_attributes = '{';
			$attr_first = true;
			foreach($product_manager_form_data['attributes'] as $attributes) {
				if(isset($attributes['is_variation'])) {
					if(!empty($attributes['name']) && !empty($attributes['value'])) {
						if(!$attr_first) $pro_attributes .= ',';
						if($attr_first) $attr_first = false;
						
						if($attributes['is_taxonomy']) {
							$pro_attributes .= '"' . $attributes['tax_name'] . '": {';
							if( !is_array($attributes['value']) ) {
								$att_values = explode("|", $attributes['value']);
								$is_first = true;
								foreach($att_values as $att_value) {
									if(!$is_first) $pro_attributes .= ',';
									if($is_first) $is_first = false;
									$pro_attributes .= '"' . sanitize_title($att_value) . '": "' . trim($att_value) . '"';
								}
							} else {
								$att_values = $attributes['value'];
								$is_first = true;
								foreach($att_values as $att_value) {
									if(!$is_first) $pro_attributes .= ',';
									if($is_first) $is_first = false;
									$att_term = get_term( absint($att_value) );
									if( $att_term ) {
										$pro_attributes .= '"' . $att_term->slug . '": "' . $att_term->name . '"';
									} else {
										$pro_attributes .= '"' . sanitize_title($att_value) . '": "' . trim($att_value) . '"';
									}
								}
							}
							$pro_attributes .= '}';
						} else {
							$pro_attributes .= '"' . $attributes['name'] . '": {';
							$att_values = explode("|", $attributes['value']);
							$is_first = true;
							foreach($att_values as $att_value) {
								if(!$is_first) $pro_attributes .= ',';
								if($is_first) $is_first = false;
								$pro_attributes .= '"' . trim($att_value) . '": "' . trim($att_value) . '"';
							}
							$pro_attributes .= '}';
						}
					}
				}
			}
			$pro_attributes .= '}';
			echo $pro_attributes;
		}
		
		die();
	}
	
	public function frontend_product_manager() {
	  global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager;
	  
	  $product_manager_form_data = array();
	  parse_str($_POST['product_manager_form'], $product_manager_form_data);
	  //print_r($product_manager_form_data);
	  $WCMp_fpm_messages = get_forntend_product_manager_messages();
	  $has_error = false;
	  
	  if(isset($product_manager_form_data['title']) && !empty($product_manager_form_data['title'])) {
	  	$is_update = false;
	  	$is_publish = false;
	  	$is_vendor = false;
	  	
	  	$current_user_id = $vendor_id = apply_filters( 'wcmp_current_loggedin_vendor_id', get_current_user_id() );
	  	if( is_user_wcmp_vendor( $current_user_id ) ) $is_vendor = true;
	  	
	  	if(isset($_POST['status']) && ($_POST['status'] == 'draft')) {
	  		$product_status = 'draft';
	  	} else {
				if( $is_vendor ) {	
					if(!current_user_can('publish_products')){
						$product_status = 'pending';
					} else {
						$product_status = 'publish';
					}	  		
				} else {
					$product_status = 'publish';
				}
			}
	  	
	  	// Creating new product
			$new_product = array(
				'post_title'   => wc_clean( $product_manager_form_data['title'] ),
				'post_status'  => $product_status,
				'post_type'    => 'product',
				'post_excerpt' => $product_manager_form_data['excerpt'],
				'post_content' => stripslashes( html_entity_decode( $_POST['description'], ENT_QUOTES, 'UTF-8') ),
				'post_author'  => $vendor_id
				//'post_name' => sanitize_title($product_manager_form_data['title'])
			);
			
			if(isset($product_manager_form_data['pro_id']) && $product_manager_form_data['pro_id'] == 0) {
				if ($product_status != 'draft') {
					$is_publish = true;
				}
				$new_product_id = wp_insert_post( $new_product, true );
			} else { // For Update
				$is_update = true;
				$new_product['ID'] = $product_manager_form_data['pro_id'];
				if( !$is_vendor ) unset( $new_product['post_author'] );
				if( get_post_status( $new_product['ID'] ) != 'draft' ) {
					unset( $new_product['post_status'] );
				} else if( (get_post_status( $new_product['ID'] ) == 'draft') && ($product_status != 'draft') ) {
					$is_publish = true;
				}
				$new_product_id = wp_update_post( $new_product, true );
			}
			
			if(!is_wp_error($new_product_id)) {
				// For Update
				if($is_update) $new_product_id = $product_manager_form_data['pro_id'];
				
				// Set Product SKU
				if(isset($product_manager_form_data['sku']) && !empty($product_manager_form_data['sku'])) {
					update_post_meta( $new_product_id, '_sku', $product_manager_form_data['sku'] );
					$unique_sku = wc_product_has_unique_sku( $new_product_id, $product_manager_form_data['sku'] );
					if ( ! $unique_sku ) {
						update_post_meta( $new_product_id, '_sku', '' );
						echo '{"status": false, "message": "' . $WCMp_fpm_messages['sku_unique'] . '", "id": "' . $new_product_id . '", "redirect": "' . get_permalink( $new_product_id ) . '"}';
						$has_error = true;
					}
				} else {
				  update_post_meta( $new_product_id, '_sku', '' );
				}
				  
				// Set Product Type
				wp_set_object_terms( $new_product_id, $product_manager_form_data['product_type'], 'product_type' );
				
				// Group Products
				$grouped_products = isset( $product_manager_form_data['grouped_products'] ) ? array_filter( array_map( 'intval', (array) $product_manager_form_data['grouped_products'] ) ) : array();
				
				// file paths will be stored in an array keyed off md5(file path)
				$downloadables = array();
				if ( isset( $product_manager_form_data['is_downloadable'] ) && isset( $product_manager_form_data['downloadable_files'] ) ) {
					foreach ( $product_manager_form_data['downloadable_files'] as $downloadable_files ) {
						if ( ! empty( $downloadable_files['file'] ) ) {
							$downloadables[ ] = array(
								'name' => wc_clean( $downloadable_files['name'] ),
								'file' => wp_unslash( trim( $downloadable_files['file'] ) ),
								'previous_hash' => md5( $downloadable_files['file'] ),
							);
						}
					}
				}
				
				// Attributes
				$pro_attributes = array();
				$default_attributes = array();
				if(isset($product_manager_form_data['attributes']) && !empty($product_manager_form_data['attributes'])) {
					foreach($product_manager_form_data['attributes'] as $attributes) {
						if(!empty($attributes['name']) && !empty($attributes['value'])) {
							
							$attribute_name = ( $attributes['term_name'] ) ? $attributes['term_name'] : $attributes['name'];
							
							$is_visible = 0;
							if(isset($attributes['is_visible'])) $is_visible = 1;
							
							$is_variation = 0;
							if(isset($attributes['is_variation'])) $is_variation = 1;
							if( ( $product_manager_form_data['product_type'] != 'variable' ) && ( $product_manager_form_data['product_type'] != 'variable-subscription' ) ) $is_variation = 0;
							
							$is_taxonomy = 0;
							if($attributes['is_taxonomy'] == 1) $is_taxonomy = 1;
							
							$attribute_id   = wc_attribute_taxonomy_id_by_name( $attributes['term_name'] );
							$options = isset( $attributes['value'] ) ? $attributes['value'] : '';
							
							if ( is_array( $options ) ) {
								// Term ids sent as array.
								$options = wp_parse_id_list( $options );
							} else {
								// Terms or text sent in textarea.
								$options = 0 < $attribute_id ? wc_sanitize_textarea( wc_sanitize_term_text_based( $options ) ) : wc_sanitize_textarea( $options );
								$options = wc_get_text_attributes( $options );
							}
			
							if ( empty( $options ) ) {
								continue;
							}
							
							$attribute = new WC_Product_Attribute();
							$attribute->set_id( $attribute_id );
							$attribute->set_name( wc_clean( $attribute_name ) );
							$attribute->set_options( $options );
							//$attribute->set_position( $attribute_position[ $i ] );
							$attribute->set_visible( $is_visible );
							$attribute->set_variation(  $is_variation );
							$pro_attributes[] = $attribute;
							
							if( $is_variation ) {
								//$attribute_key = $attribute_name;
								//$value                        = $attribute->is_taxonomy() ? sanitize_title( $value ) : wc_clean( $value ); // Don't use wc_clean as it destroys sanitized characters in terms.
								//$default_attributes[ $attribute_key ] = $value;
							}
						}
					}
				}
				
				// Set default Attributes
				if( isset( $product_manager_form_data['default_attributes'] ) && !empty( $product_manager_form_data['default_attributes'] ) ) {
					$default_attributes = array();
					if ( $pro_attributes ) {
						foreach ( $pro_attributes as $p_attribute ) {
							if ( $p_attribute->get_variation() ) {
								$attribute_key = sanitize_title( $p_attribute->get_name() );
								
								$value = isset( $product_manager_form_data['default_attributes'][ "attribute_" . $attribute_key ] ) ? stripslashes( $product_manager_form_data['default_attributes'][ "attribute_" . $attribute_key ] ) : '';
			
								$value                        = $p_attribute->is_taxonomy() ? sanitize_title( $value ) : wc_clean( $value ); // Don't use wc_clean as it destroys sanitized characters in terms.
								$default_attributes[ $attribute_key ] = $value;
							}
						}
					}
				}
				
				// Process product type first so we have the correct class to run setters.
				$product_type = empty( $product_manager_form_data['product_type'] ) ? WC_Product_Factory::get_product_type( $new_product_id ) : sanitize_title( stripslashes( $product_manager_form_data['product_type'] ) );
				$classname    = WC_Product_Factory::get_product_classname( $new_product_id, $product_type ? $product_type : 'simple' );
				$product      = new $classname( $new_product_id );
				$errors       = $product->set_props( array(
					'sku'                => isset( $product_manager_form_data['sku'] ) ? wc_clean( $product_manager_form_data['sku'] ) : null,
					'purchase_note'      => wp_kses_post( stripslashes( $product_manager_form_data['purchase_note'] ) ),
					'downloadable'       => isset( $product_manager_form_data['is_downloadable'] ),
					'virtual'            => isset( $product_manager_form_data['is_virtual'] ),
					//'featured'           => isset( $product_manager_form_data['featured'] ),
					//'catalog_visibility' => wc_clean( $product_manager_form_data['visibility'] ),
					'tax_status'         => isset( $product_manager_form_data['tax_status'] ) ? wc_clean( $product_manager_form_data['tax_status'] ) : null,
					'tax_class'          => isset( $product_manager_form_data['tax_class'] ) ? wc_clean( $product_manager_form_data['tax_class'] ) : null,
					'weight'             => wc_clean( $product_manager_form_data['weight'] ),
					'length'             => wc_clean( $product_manager_form_data['length'] ),
					'width'              => wc_clean( $product_manager_form_data['width'] ),
					'height'             => wc_clean( $product_manager_form_data['height'] ),
					'shipping_class_id'  => absint( $product_manager_form_data['shipping_class'] ),
					'sold_individually'  => ! empty( $product_manager_form_data['sold_individually'] ),
					'upsell_ids'         => isset( $product_manager_form_data['upsell_ids'] ) ? array_map( 'intval', (array) $product_manager_form_data['upsell_ids'] ) : array(),
					'cross_sell_ids'     => isset( $product_manager_form_data['crosssell_ids'] ) ? array_map( 'intval', (array) $product_manager_form_data['crosssell_ids'] ) : array(),
					'regular_price'      => wc_clean( $product_manager_form_data['regular_price'] ),
					'sale_price'         => wc_clean( $product_manager_form_data['sale_price'] ),
					'date_on_sale_from'  => wc_clean( $product_manager_form_data['sale_date_from'] ),
					'date_on_sale_to'    => wc_clean( $product_manager_form_data['sale_date_upto'] ),
					'manage_stock'       => ! empty( $product_manager_form_data['manage_stock'] ),
					'backorders'         => wc_clean( $product_manager_form_data['backorders'] ),
					'stock_status'       => wc_clean( $product_manager_form_data['stock_status'] ),
					'stock_quantity'     => wc_stock_amount( $product_manager_form_data['stock_qty'] ),
					'download_limit'     => '' === $product_manager_form_data['download_limit'] ? '' : absint( $product_manager_form_data['download_limit'] ),
					'download_expiry'    => '' === $product_manager_form_data['download_expiry'] ? '' : absint( $product_manager_form_data['download_expiry'] ),
					'downloads'          => $downloadables,
					'product_url'        => esc_url_raw( $product_manager_form_data['product_url'] ),
					'button_text'        => wc_clean( $product_manager_form_data['button_text'] ),
					'children'           => 'grouped' === $product_type ? $grouped_products : null,
					'reviews_allowed'    => ! empty( $product_manager_form_data['enable_reviews'] ),
					'menu_order'        => absint( $product_manager_form_data['menu_order'] ),
					'attributes'         => $pro_attributes,
					'default_attributes' => $default_attributes,
				) );
		
				if ( is_wp_error( $errors ) ) {
					echo '{"status": false, "message": "' . $errors->get_error_message() . '", "id": "' . $new_product_id . '", "redirect": "' . get_permalink( $new_product_id ) . '"}';
					$has_error = true;
				}
				
				
				/**
				 * @since 3.0.0 to set props before save.
				 */
				//do_action( 'woocommerce_admin_process_product_object', $product );
				$product->save();
				
				// Set Product Category
				if(isset($product_manager_form_data['product_cats']) && !empty($product_manager_form_data['product_cats'])) {
					$is_first = true;
					foreach($product_manager_form_data['product_cats'] as $product_cats) {
						if($is_first) {
							$is_first = false;
							wp_set_object_terms( $new_product_id, (int)$product_cats, 'product_cat' );
						} else {
							wp_set_object_terms( $new_product_id, (int)$product_cats, 'product_cat', true );
						}
					}
				}
				
				// Set Product Custom Taxonomies
				if(isset($product_manager_form_data['product_custom_taxonomies']) && !empty($product_manager_form_data['product_custom_taxonomies'])) {
					foreach($product_manager_form_data['product_custom_taxonomies'] as $taxonomy => $taxonomy_values) {
						if( !empty( $taxonomy_values ) ) {
							$is_first = true;
							foreach( $taxonomy_values as $taxonomy_value ) {
								if($is_first) {
									$is_first = false;
									wp_set_object_terms( $new_product_id, (int)$taxonomy_value, $taxonomy );
								} else {
									wp_set_object_terms( $new_product_id, (int)$taxonomy_value, $taxonomy, true );
								}
							}
						}
					}
				}
				
				// Set Product Tags
				if(isset($product_manager_form_data['product_tags']) && !empty($product_manager_form_data['product_tags'])) {
					wp_set_post_terms( $new_product_id, $product_manager_form_data['product_tags'], 'product_tag' );
				}
				
				// Set Product Featured Image
				$wp_upload_dir = wp_upload_dir();
				if(isset($product_manager_form_data['featured_img']) && !empty($product_manager_form_data['featured_img'])) {
					$featured_img_id = $this->fpm_get_image_id($product_manager_form_data['featured_img']);
					set_post_thumbnail( $new_product_id, $featured_img_id );
				} else {
					delete_post_thumbnail( $new_product_id );
				}
				
				// Set Product Image Gallery
				if(isset($product_manager_form_data['gallery_img']) && !empty($product_manager_form_data['gallery_img'])) {
					$gallery = array();
					foreach($product_manager_form_data['gallery_img'] as $gallery_imgs) {
						if(isset($gallery_imgs['image']) && !empty($gallery_imgs['image'])) {
							$gallery_img_id = $this->fpm_get_image_id($gallery_imgs['image']);
							$gallery[] = $gallery_img_id;
						}
					}
					if ( ! empty( $gallery ) ) {
						update_post_meta( $new_product_id, '_product_image_gallery', implode( ',', $gallery ) );
					}
				}
				
				// Set product basic options for simple and external products
				if( ( $product_manager_form_data['product_type'] == 'variable' ) || ( $product_manager_form_data['product_type'] == 'variable-subscription' ) ) {
					// Create Variable Product Variations
					if(isset($product_manager_form_data['variations']) && !empty($product_manager_form_data['variations'])) {
					  foreach($product_manager_form_data['variations'] as $variations) {
					  	$variation_status     = isset( $variations['enable'] ) ? 'publish' : 'private';
					  	
					  	$variation_id = absint ( $variations['id'] );
					  	
					  	// Generate a useful post title
					  	$variation_post_title = sprintf( __( 'Variation #%s of %s', 'woocommerce' ), absint( $variation_id ), esc_html( get_the_title( $new_product_id ) ) );
					  	
					  	if ( ! $variation_id ) { // Adding New Variation
								$variation = array(
									'post_title'   => $variation_post_title,
									'post_content' => '',
									'post_status'  => $variation_status,
									'post_author'  => $current_user_id,
									'post_parent'  => $new_product_id,
									'post_type'    => 'product_variation'
								);
						
								$variation_id = wp_insert_post( $variation );
							}
							
							// Only continue if we have a variation ID
							if ( ! $variation_id ) {
								continue;
							}
							
							// Set Variation Thumbnail
							$variation_img_id = 0;
							if(isset($variations['image']) && !empty($variations['image'])) {
								$variation_img_id = $this->fpm_get_image_id($variations['image']);
							}
							
							// Variation Download options
							$downloadables = array();
							if ( isset( $variations['is_downloadable'] ) && isset( $variations['downloadable_file'] ) && $variations['downloadable_file'] && !empty( $variations['downloadable_file'] ) ) {
								$downloadables[] = array(
									'name' => wc_clean( $variations['downloadable_file_name'] ),
									'file' => wp_unslash( trim( $variations['downloadable_file'] ) ),
									'previous_hash' => md5( $variations['downloadable_file'] ),
								);
							}
							
							// Update Attributes
							$var_attributes = array();
							if ( $pro_attributes ) {
								foreach ( $pro_attributes as $p_attribute ) {
									if ( $p_attribute->get_variation() ) {
										$attribute_key = sanitize_title( $p_attribute->get_name() );
										
										$value = isset( $variations[ "attribute_" . $attribute_key ] ) ? stripslashes( $variations[ "attribute_" . $attribute_key ] ) : '';
					
										$value = $p_attribute->is_taxonomy() ? sanitize_title( $value ) : wc_clean( $value ); // Don't use wc_clean as it destroys sanitized characters in terms.
										$var_attributes[ $attribute_key ] = $value;
									}
								}
							}
							
							$wc_variation    = new WC_Product_Variation( $variation_id );
							$errors       = $wc_variation->set_props( apply_filters( 'fpm_product_variation_data_factory', array(
								//'status'            => 'publish' //isset( $variations['enable'] ) ? 'publish' : 'private',
								'menu_order'        => wc_clean( $variations['menu_order'] ),
								'regular_price'     => wc_clean( $variations['regular_price'] ),
								'sale_price'        => wc_clean( $variations['sale_price'] ),
								'manage_stock'      => isset( $variations['manage_stock'] ),
								'stock_quantity'    => wc_clean( $variations['stock_qty'] ),
								'backorders'        => wc_clean( $variations['backorders'] ),
								'stock_status'      => wc_clean( $variations['stock_status'] ),
								'image_id'          => wc_clean( $variation_img_id ),
								'attributes'        => $var_attributes,
								'sku'               => isset( $variations['sku'] ) ? wc_clean( $variations['sku'] ) : '',
								'virtual'           => isset( $variations['is_virtual'] ),
								'downloadable'      => isset( $variations['is_downloadable'] ),
								'date_on_sale_from' => wc_clean( $variations['sale_price_dates_from'] ),
								'date_on_sale_to'   => wc_clean( $variations['sale_price_dates_to'] ),
								'description'       => wp_kses_post( $variations['description'] ),
								'download_limit'    => wc_clean( $variations['download_limit'] ),
								'download_expiry'   => wc_clean( $variations['download_expiry'] ),
								'downloads'         => $downloadables,
								'weight'            => isset( $variations['weight'] ) ? wc_clean( $variations['weight'] ) : '',
								'length'            => isset( $variations['length'] ) ? wc_clean( $variations['length'] ) : '',
								'width'             => isset( $variations['width'] ) ? wc_clean( $variations['width'] )   : '',
								'height'            => isset( $variations['height'] ) ? wc_clean( $variations['height'] ) : '',
								'shipping_class_id' => wc_clean( $variations['shipping_class'] ),
								'tax_class'         => isset( $variations['tax_class'] ) ? wc_clean( $variations['tax_class'] ) : null,
							), $new_product_id, $variation_id, $variations, $product_manager_form_data ) );
			
							if ( is_wp_error( $errors ) ) {
								echo '{"status": false, "message": "' . $errors->get_error_message() . '", "id": "' . $new_product_id . '", "redirect": "' . get_permalink( $new_product_id ) . '"}';
								$has_error = true;
							}
			
							$wc_variation->save();
						}
					}
					
					// Remove Variations
					if(isset($_POST['removed_variations']) && !empty($_POST['removed_variations'])) {
						foreach($_POST['removed_variations'] as $removed_variations) {
							wp_delete_post($removed_variations, true);
						}
					}
					
					$product->get_data_store()->sync_variation_names( $product, wc_clean( $product_manager_form_data['title'] ), wc_clean( $product_manager_form_data['title'] ) );
				}
				
				// Yoast SEO Support
				if(WCMp_Frontend_Product_Manager_Dependencies::fpm_yoast_plugin_active_check()) {
					if(isset($product_manager_form_data['yoast_wpseo_focuskw_text_input'])) {
						update_post_meta( $new_product_id, '_yoast_wpseo_focuskw_text_input', $product_manager_form_data['yoast_wpseo_focuskw_text_input'] );
						update_post_meta( $new_product_id, '_yoast_wpseo_focuskw', $product_manager_form_data['yoast_wpseo_focuskw_text_input'] );
					}
					if(isset($product_manager_form_data['yoast_wpseo_metadesc'])) {
						update_post_meta( $new_product_id, '_yoast_wpseo_metadesc', strip_tags( $product_manager_form_data['yoast_wpseo_metadesc'] ) );
					}
				}
				
				// WooCommerce Custom Product Tabs Lite Support
				if(WCMp_Frontend_Product_Manager_Dependencies::fpm_wc_tabs_lite_plugin_active_check()) {
					if(isset($product_manager_form_data['product_tabs'])) {
						$frs_woo_product_tabs = array();
						if( !empty( $product_manager_form_data['product_tabs'] ) ) {
							foreach( $product_manager_form_data['product_tabs'] as $frs_woo_product_tab ) {
								if( $frs_woo_product_tab['title'] ) {
									// convert the tab title into an id string
									$tab_id = strtolower( wc_clean( $frs_woo_product_tab['title'] ) );
				
									// remove non-alphas, numbers, underscores or whitespace
									$tab_id = preg_replace( "/[^\w\s]/", '', $tab_id );
				
									// replace all underscores with single spaces
									$tab_id = preg_replace( "/_+/", ' ', $tab_id );
				
									// replace all multiple spaces with single dashes
									$tab_id = preg_replace( "/\s+/", '-', $tab_id );
				
									// prepend with 'tab-' string
									$tab_id = 'tab-' . $tab_id;
									
									$frs_woo_product_tabs[] = array(
																									'title'   => wc_clean( $frs_woo_product_tab['title'] ),
																									'id'      => $tab_id,
																									'content' => $frs_woo_product_tab['content']
																								);
								}
							}
							update_post_meta( $new_product_id, 'frs_woo_product_tabs', $frs_woo_product_tabs );
						} else {
							delete_post_meta( $new_product_id, 'frs_woo_product_tabs' );
						}
					}
				}
				
				// WooCommerce Product Fees Support
				if(WCMp_Frontend_Product_Manager_Dependencies::fpm_wc_product_fees_plugin_active_check()) {
					update_post_meta( $new_product_id, 'product-fee-name', $product_manager_form_data['product-fee-name'] );
					update_post_meta( $new_product_id, 'product-fee-amount', $product_manager_form_data['product-fee-amount'] );
					$product_fee_multiplier = ( $product_manager_form_data['product-fee-multiplier'] ) ? 'yes' : 'no';
					update_post_meta( $new_product_id, 'product-fee-multiplier', $product_fee_multiplier );
				}
				
				// WooCommerce Bulk Discount Support
				if(WCMp_Frontend_Product_Manager_Dependencies::fpm_wc_bulk_discount_plugin_active_check()) {
					$_bulkdiscount_enabled = ( $product_manager_form_data['_bulkdiscount_enabled'] ) ? 'yes' : 'no';
					update_post_meta( $new_product_id, '_bulkdiscount_enabled', $_bulkdiscount_enabled );
					update_post_meta( $new_product_id, '_bulkdiscount_text_info', $product_manager_form_data['_bulkdiscount_text_info'] );
					update_post_meta( $new_product_id, '_bulkdiscounts', $product_manager_form_data['_bulkdiscounts'] );
					
					$bulk_discount_rule_counter = 0;
					foreach( $product_manager_form_data['_bulkdiscounts'] as $bulkdiscount ) {
						$bulk_discount_rule_counter++;
						update_post_meta( $new_product_id, '_bulkdiscount_quantity_'.$bulk_discount_rule_counter, $bulkdiscount['quantity'] );
						update_post_meta( $new_product_id, '_bulkdiscount_discount_'.$bulk_discount_rule_counter, $bulkdiscount['discount'] );
					}
					
					if( $bulk_discount_rule_counter < 5 ) {
						for( $bdrc = ($bulk_discount_rule_counter+1); $bdrc <= 5; $bdrc++ ) {
							update_post_meta( $new_product_id, '_bulkdiscount_quantity_'.$bdrc, '' );
							update_post_meta( $new_product_id, '_bulkdiscount_discount_'.$bdrc, '' );
						}
					}
				}
				
				// Toolset Types Support
				if(WCMp_Frontend_Product_Manager_Dependencies::fpm_toolset_plugin_active_check()) {
					if( isset( $product_manager_form_data['wpcf'] ) && ! empty( $product_manager_form_data['wpcf'] ) ) {
						foreach( $product_manager_form_data['wpcf'] as $toolset_types_filed_key => $toolset_types_filed_value ) {
							update_post_meta( $new_product_id, $toolset_types_filed_key, $toolset_types_filed_value );
							if( is_array( $toolset_types_filed_value ) ) {
								delete_post_meta( $new_product_id, $toolset_types_filed_key );
								foreach( $toolset_types_filed_value as $toolset_types_filed_value_field ) {
									if( isset( $toolset_types_filed_value_field['field'] ) && !empty( $toolset_types_filed_value_field['field'] ) ) {
										add_post_meta( $new_product_id, $toolset_types_filed_key, $toolset_types_filed_value_field['field'] );
									}
								}
							}
						}
					}
				}
				
				do_action('after_wcmp_fpm_meta_save', $new_product_id, $product_manager_form_data);
				
				// Set Product Vendor Data
				if( $is_vendor && !$is_update ) {
					$vendor_term = get_user_meta( $current_user_id, '_vendor_term_id', true );
					$term = get_term( $vendor_term , 'dc_vendor_shop' );
					wp_delete_object_term_relationships( $new_product_id, 'dc_vendor_shop' );
					wp_set_post_terms( $new_product_id, $term->name , 'dc_vendor_shop', true );
				}
				
				// Notify Admin on New Product Creation
				if( $is_publish ) {
					$WCMp->product->on_all_status_transitions($product_status, '', get_post($new_product_id));
				} 
				
				if(!$has_error) {
					if( get_post_status( $new_product_id ) == 'publish' ) {
						if(!$has_error) echo '{"status": true, "message": "' . $WCMp_fpm_messages['product_published'] . '", "redirect": "' . get_permalink( $new_product_id ) . '"}';	
					} else {
						if(!$has_error) echo '{"status": true, "message": "' . $WCMp_fpm_messages['product_saved'] . '", "redirect": "' . add_query_arg('fpm_msg', 'product_saved', add_query_arg('pro_id', $new_product_id, get_forntend_product_manager_page())) . '"}';
					}
				}
				die;
			}
		} else {
			echo '{"status": false, "message": "' . $WCMp_fpm_messages['no_title'] . '"}';
		}
		do_action('after_wcmp_frontend_product_manager_save', $new_product_id, $product_manager_form_data);
	  die;
	}
	
	public function delete_fpm_product() {
		global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager;
		
		$proid = $_POST['proid'];
		
		if($proid) {
			if(wp_delete_post($proid)) {
				echo 'success';
				die;
			}
			die;
		}
	}
	
	function fpm_get_image_id($attachment_url) {
		global $wpdb;
		$upload_dir_paths = wp_upload_dir();
		
		if( class_exists('WPH') ) {
			global $wph;
			$new_upload_path = $wph->functions->get_module_item_setting('new_upload_path');
			$attachment_url = str_replace( $new_upload_path, 'wp-content/uploads', $attachment_url );
		}
		
		// If this is the URL of an auto-generated thumbnail, get the URL of the original image
		if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
			$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
		
			// Remove the upload path base directory from the attachment URL
			$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
			
			// Finally, run a custom database query to get the attachment ID from the modified attachment URL
			$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
		}
		return $attachment_id; 
	}
	
	// Frontend Coupon Manager
	public function frontend_coupon_manager() {
	  global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager;
	  
	  $coupon_manager_form_data = array();
	  parse_str($_POST['coupon_manager_form'], $coupon_manager_form_data);
	  //print_r($coupon_manager_form_data);
	  $WCMp_fpm_coupon_messages = get_forntend_coupon_manager_messages();
	  $has_error = false;
	  
	  if(isset($coupon_manager_form_data['title']) && !empty($coupon_manager_form_data['title'])) {
	  	$is_update = false;
	  	$is_publish = false;
	  	$is_vendor = false;
	  	$current_user_id = $vendor_id = apply_filters( 'wcmp_current_loggedin_vendor_id', get_current_user_id() );
	  	if( is_user_wcmp_vendor( $current_user_id ) ) $is_vendor = true;
	  	
	  	if(isset($_POST['status']) && ($_POST['status'] == 'draft')) {
	  		$coupon_status = 'draft';
	  	} else {
				if( $is_vendor ) {	
					if(!current_user_can('publish_shop_coupons')){
						$coupon_status = 'pending';
					} else {
						$coupon_status = 'publish';
					}	  		
				} else {
					$coupon_status = 'publish';
				}
			}
	  	
	  	// Creating new coupon
			$new_coupon = array(
				'post_title'   => wc_clean( $coupon_manager_form_data['title'] ),
				'post_status'  => $coupon_status,
				'post_type'    => 'shop_coupon',
				'post_excerpt' => $coupon_manager_form_data['description'],
				'post_author'  => $vendor_id
				//'post_name' => sanitize_title($coupon_manager_form_data['title'])
			);
			
			if(isset($coupon_manager_form_data['coupon_id']) && $coupon_manager_form_data['coupon_id'] == 0) {
				if ($coupon_status != 'draft') {
					$is_publish = true;
				}
				$new_coupon_id = wp_insert_post( $new_coupon, true );
			} else { // For Update
				$is_update = true;
				$new_coupon['ID'] = $coupon_manager_form_data['coupon_id'];
				if( !$is_vendor ) unset( $new_coupon['post_author'] );
				if( get_post_status( $new_coupon['ID'] ) != 'draft' ) {
					unset( $new_coupon['post_status'] );
				} else if( (get_post_status( $new_coupon['ID'] ) == 'draft') && ($coupon_status != 'draft') ) {
					$is_publish = true;
				}
				$new_coupon_id = wp_update_post( $new_coupon, true );
			}
			
			if(!is_wp_error($new_coupon_id)) {
				// For Update
				if($is_update) $new_coupon_id = $coupon_manager_form_data['coupon_id'];
				
				// Coupon General
				update_post_meta( $new_coupon_id, 'discount_type', $coupon_manager_form_data['discount_type'] );
				update_post_meta( $new_coupon_id, 'coupon_amount', $coupon_manager_form_data['coupon_amount'] ? $coupon_manager_form_data['coupon_amount'] : '' );
				update_post_meta( $new_coupon_id, 'free_shipping', isset( $coupon_manager_form_data['free_shipping'] ) ? 'yes' : 'no' );
				update_post_meta( $new_coupon_id, 'expiry_date', $coupon_manager_form_data['expiry_date'] ? $coupon_manager_form_data['expiry_date'] : '' );
								
				// Usage Restrictin
				update_post_meta( $new_coupon_id, 'minimum_amount', $coupon_manager_form_data['minimum_amount'] ? $coupon_manager_form_data['minimum_amount'] : '' );
				update_post_meta( $new_coupon_id, 'maximum_amount', $coupon_manager_form_data['maximum_amount'] ? $coupon_manager_form_data['maximum_amount'] : '' );
				update_post_meta( $new_coupon_id, 'individual_use', isset( $coupon_manager_form_data['individual_use'] ) ? 'yes' : 'no' );
				update_post_meta( $new_coupon_id, 'exclude_sale_items', isset( $coupon_manager_form_data['exclude_sale_items'] ) ? 'yes' : 'no' );
				update_post_meta( $new_coupon_id, 'product_ids', $coupon_manager_form_data['product_ids'] ? implode(',', $coupon_manager_form_data['product_ids']) : '' );
				update_post_meta( $new_coupon_id, 'exclude_product_ids', $coupon_manager_form_data['exclude_product_ids'] ? implode(',', $coupon_manager_form_data['exclude_product_ids']) : '' );
				update_post_meta( $new_coupon_id, 'product_categories', isset( $coupon_manager_form_data['product_categories'] ) ? array_map( 'intval', $coupon_manager_form_data['product_categories']) : array() );
				update_post_meta( $new_coupon_id, 'exclude_product_categories', isset( $coupon_manager_form_data['exclude_product_categories'] ) ? array_map( 'intval', $coupon_manager_form_data['exclude_product_categories']) : array() );
				update_post_meta( $new_coupon_id, 'customer_email', $coupon_manager_form_data['customer_email'] ? array_filter( array_map( 'trim', explode( ',', wc_clean( $coupon_manager_form_data['customer_email'] )))) : array() );
				
				// Usage Limits
				update_post_meta( $new_coupon_id, 'usage_limit', $coupon_manager_form_data['usage_limit'] ? $coupon_manager_form_data['usage_limit'] : '' );
				update_post_meta( $new_coupon_id, 'usage_limit_per_user', $coupon_manager_form_data['usage_limit_per_user'] ? $coupon_manager_form_data['usage_limit_per_user'] : '' );
				
				// limit_usage_to_x_items
				
				if(!$has_error) {
					if( get_post_status( $new_coupon_id ) == 'publish' ) {
						if(!$has_error) echo '{"status": true, "message": "' . $WCMp_fpm_coupon_messages['coupon_published'] . '", "redirect": "' . get_vendor_coupons_page() . '"}';	
					} else {
						if(!$has_error) echo '{"status": true, "message": "' . $WCMp_fpm_coupon_messages['coupon_saved'] . '", "redirect": "' . add_query_arg('fpm_msg', 'coupon_saved', add_query_arg('coupon_id', $new_coupon_id, get_frontend_coupon_manager_page())) . '"}';
					}
				}
				die;
			}
		} else {
			echo '{"status": false, "message": "' . $WCMp_fpm_coupon_messages['no_title'] . '"}';
		}
	}

}