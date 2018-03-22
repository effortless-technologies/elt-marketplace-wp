<?php
/**
 * WCMp Frontend Manager plugin core
 * WC Variable Product Support
 */
 
class WCMp_Frontend_Product_Manager_Variable extends WC_REST_Products_Controller {
	public function __construct() {
		global $WCMp, $WCMp_Frontend_Product_Manager;
		add_filter( 'wcmp_product_types', array( &$this, 'wcvariable_product_types' ), 20 );

    add_filter( 'wcmp_fpm_product_attributes', array( &$this, 'wcmp_fpm_product_attributes_callback' ), 10, 3);

		//product variation add
    add_action('after_wcmp_fpm_attributes',array(&$this, 'wcvariation_tab_add_callback'));

    // Product variations Meta Data Save
    add_action( 'after_wcmp_fpm_data_meta_save', array( &$this, 'wcvariation_meta_save' ), 10, 3 );
	}

  /**
   * WC attributes set variations 
   */
  function wcmp_fpm_product_attributes_callback($pro_attributes, $new_product_id, $product_manager_form_data) {
   
    foreach ($product_manager_form_data['attributes'] as $attr_key => $attr_value) {
      $is_variation = 0;
      if( isset($attr_value['is_variation']) ) {
        $is_variation = 1;
        foreach($pro_attributes as $key => $attributes) {        
          if( ($attributes->get_name() == $attr_value['name']) || ($attributes->get_name() == $attr_value['term_name']) ) {
            $pro_attributes[$key]->set_variation( $is_variation );
          }
          
        }
      }
    }
    return $pro_attributes;
  }

	/**
   * WC variable Product Type
   */
  function wcvariable_product_types( $product_types ) {
  	global $WCMp, $WCMp_Frontend_Product_Manager;
  	$current_user_id = apply_filters( 'wcmp_current_loggedin_vendor_id', get_current_user_id() );
  	
    if ( is_user_wcmp_vendor( $current_user_id ) || current_user_can( 'administrator' ) || current_user_can( 'shop_manager' )) {
  		$product_types['variable'] = __( 'Variable', 'wcmp-frontend_product_manager' );
  	}

  	return $product_types;
  }

  
  /**
   * WC Product variation tab add
   */

  function wcvariation_tab_add_callback() {
    global $WCMp, $WCMp_Frontend_Product_Manager,$wp;
    
    $pro_id = $wp->query_vars[get_wcmp_vendor_settings('wcmp_add_product_endpoint', 'vendor', 'general', 'add-product')];
    $WCMp_Frontend_Product_Manager->template->get_template('wcmp-fpm-view-wcvariable.php', array('pro_id' => $pro_id));
  }

  /**
  * Product variation Save
  */

  function wcvariation_meta_save( $new_product_id, $product_manager_form_data, $pro_attributes ) {
  
    $current_user_id = apply_filters( 'wcmp_current_loggedin_vendor_id', get_current_user_id() );
    if( ( $product_manager_form_data['product_type'] == 'variable' ) || ( $product_manager_form_data['product_type'] == 'variable-subscription' ) ) {
      // Create Variable Product Variations
      $product_obj = wc_get_product($new_product_id);
      foreach ($product_manager_form_data['variations'] as $variationkey => $variationvalue) {
        $flag = 0;
        foreach ($product_obj->get_attributes() as $attrkey => $attrvalue) {
          if( $variationvalue['attribute_'.$attrkey] != '' ) {
            $flag = 1;
            break;
          }
        }
        if( $flag == 0) {
          unset($product_manager_form_data['variations'][$variationkey]);
        }
        
      }
     
      
      if(isset($product_manager_form_data['variations']) && !empty($product_manager_form_data['variations'])) {
        foreach($product_manager_form_data['variations'] as $variations) {
          $variation_status     = isset( $variations['enable'] ) ? 'publish' : 'private';
          
          $variation_id = absint ( $variations['id'] );
          
          // Generate a useful post title
          $variation_post_title = sprintf( __( 'Variation #%s of %s', 'wcmp-frontend_product_manager' ), absint( $variation_id ), esc_html( get_the_title( $new_product_id ) ) );
          
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
            $variation_img_id = $this->fpm_variation_get_image_id($variations['image']);
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
            'status'            => isset( $variations['enable'] ) ? 'publish' : 'private',
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
      
      $product = wc_get_product($new_product_id);
      $product->get_data_store()->sync_variation_names( $product, wc_clean( $product_manager_form_data['title'] ), wc_clean( $product_manager_form_data['title'] ) );
    }
  }

  function fpm_variation_get_image_id($attachment_url) {
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
}