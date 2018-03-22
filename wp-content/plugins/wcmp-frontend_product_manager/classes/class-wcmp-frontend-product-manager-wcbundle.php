<?php

/**
 * WCMp Frontend Manager plugin core
 *
 * Bundle WC Bundle Product Support
 *
 * @author 		WC Marketplace
 * @package 	wcmp-frontend_product_manager/classes
 * 
 */
 
class WCMp_Frontend_Product_Manager_WCBundle extends WC_REST_Products_Controller {
	public function __construct() {
		global $WCMp, $WCMp_Frontend_Product_Manager;
    $vendor_can = $WCMp->vendor_caps->vendor_can('bundle');
		if( wcmp_forntend_manager_is_bundle() ) {
			if ( current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) || $vendor_can ) {
				// Bundle Product Type
				add_filter( 'wcmp_product_types', array( &$this, 'wcbundle_product_types' ), 20 );

				// Bundle Product options
				add_filter( 'wcmp_fpm_fields_inventory', array( &$this, 'wcbundle_product_manage_fields_inventory' ), 10, 2 );
				add_filter( 'wcmp_fpm_manage_fields_advanced', array( &$this, 'wcbundle_product_manage_fields_advanced' ), 10, 2 );


				//product bundle tab add
        add_action('after_wcmp_fpm_general',array(&$this, 'wcbundle_tab_add_callback'));

        //product bundle save
        //add_action('after_wcmp_fpm_meta_save',array(&$this, 'wcbundle_datas_save'),10,2);
        add_action('after_wcmp_fpm_meta_save',array(&$this, 'wcbundle_datas_save'),10,3);

        
        
			}
		}
	}

  

	/**
   * WC Booking Product Type
   */
  function wcbundle_product_types( $pro_types ) {
  	global $WCMp, $WCMp_Frontend_Product_Manager;
    $vendor_can = $WCMp->vendor_caps->vendor_can('bundle');
  	if ( $vendor_can || current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) ) {
  		$pro_types['bundle'] = __( 'Product Bundle', 'wcmp-frontend_product_manager' );
  	}  	
  	return $pro_types;
  }

  /**
	 * WC Product Bundle General options
	 */

  function wcbundle_product_manage_fields_inventory($general_fields, $product_id) {
		global $product_bundle_object;
		$value = 'no';
    if( $product_id ) {
      $bundle_product_object     = wc_get_product( $product_id );
      if ($bundle_product_object->is_type('bundle')){
        $sold_individually         = $bundle_product_object->get_sold_individually( 'edit' );
        $sold_individually_context = $bundle_product_object->get_sold_individually_context( 'edit' );
        if ( $sold_individually ) {
          if ( ! in_array( $sold_individually_context, array( 'configuration', 'product' ) ) ) {
            $value = 'product';
          } else {
            $value = $sold_individually_context;
          }
        }
      }
    }
    
    $general_fields['sold_individually']['class'] .= ' non-bundle';
    $general_fields['sold_individually']['label_class'] .= ' non-bundle';

    $sold_options = array('no'            => __( 'No', 'wcmp-frontend_product_manager' ),'product'       => __( 'Yes', 'wcmp-frontend_product_manager' ),'configuration' => __( 'Matching configurations only', 'wcmp-frontend_product_manager' ));

			// Provide context to the "Sold Individually" option.

    $general_fields = $general_fields + array(  "_wc_pb_sold_individually" => 		
				
				array('label' => sprintf( esc_html__( 'Sold individually', 'wcmp-frontend_product_manager' ) ),'value' => $value,'type' => 'select', 'options' =>$sold_options, 'id' => '_wc_pb_sold_individually','class' => 'regular-text pro_ele bundle', 'label_class' => 'pro_title pro_ele bundle' ),

			);
		
			return $general_fields;

  }

  
  function wcbundle_product_manage_fields_advanced($advanced_fields, $product_id) {
    global $product_bundle_object;
    $wc_pb_edit_in_cart = '';
    $enable_edit_cart = 0;
    $wc_pb_group_mode = '';
    if( $product_id ) {
      $bundle_product_object     = wc_get_product( $product_id );
      if ($bundle_product_object->is_type('bundle')){
        $wc_pb_edit_in_cart = get_post_meta($product_id,'_wc_pb_edit_in_cart',true);
        $wc_pb_group_mode = get_post_meta($product_id,'_wc_pb_group_mode',true);

      }
    }
    if($wc_pb_edit_in_cart == 'yes') {
      $enable_edit_cart = 1;
    }
    
    $wc_pb_group_mode_arr = array('parent' => 'Default','noindent' => 'No indent','none' => 'No parent');
    $advanced_fields = $advanced_fields + array( 
      "_wc_pb_group_mode" => array('label' => __('Group mode', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $wc_pb_group_mode_arr, 'class' => 'regular-select pro_ele bundle wc_pb_group_mode','wrapper_class'=>'pro_ele bundle','label_class' => 'pro_title pro_ele bundle  lbl_select','hints' => __('Modifies the visibility and indentation  of parent/child line items in cart/order templates..', 'wcmp-frontend_product_manager'),'value' => $wc_pb_group_mode ),

      "_wc_pb_edit_in_cart" => array('label' => __('Editing in cart', 'wcmp-frontend_product_manager') , 'type' => 'checkbox', 'wrapper_class'=>'pro_ele bundle non-simple non-variable-subscription non-variable','class' => 'regular-checkbox pro_ele bundle non-simple non-variable-subscription non-variable ', 'wrapper_class' => 'wc_pb_edit_in_cart','value' => 1, 'label_class' => 'pro_title pro_ele checkbox_title bundle non-simple non-variable-subscription non-variable ', 'dfvalue' => $enable_edit_cart),
        
      );
    return $advanced_fields;

  }

  /**
   * WC Product Bundle tab add
   */

  function wcbundle_tab_add_callback( $product_id ) {
    global $WCMp, $WCMp_Frontend_Product_Manager;
    $WCMp_Frontend_Product_Manager->template->get_template('wcmp-fpm-view-wcbundle.php', array('pro_id' => $product_id));
  }

  /**
    * Bundle Product Save
    */

  function wcbundle_datas_save($new_product_id, $product_manager_form_data, $has_error) {

    $product = wc_get_product( $new_product_id );
    session_start();
    if ($product->is_type('bundle')){
      $props = array(
        'layout'                    => 'default',
        'group_mode'                => 'parent',
        'editable_in_cart'          => false,
        'sold_individually'         => false,
        'sold_individually_context' => 'product'
      );

      /*
       * Layout.
       */

      if ( ! empty( $product_manager_form_data[ '_wc_pb_layout_style' ] ) ) {
        $props[ 'layout' ] = wc_clean( $product_manager_form_data[ '_wc_pb_layout_style' ] );
      }

      /*
       * group mode
       */
      $group_mode_pre = $product->get_group_mode( 'edit' );
      if ( ! empty( $product_manager_form_data[ '_wc_pb_group_mode' ] ) ) {
        $props[ 'group_mode' ] = wc_clean( $product_manager_form_data[ '_wc_pb_group_mode' ] );
      }

      

      /*
       * Cart editing option.
       */

      if ( ! empty( $product_manager_form_data[ '_wc_pb_edit_in_cart' ] ) ) {
        $props[ 'editable_in_cart' ] = true;
      }

      /*
       * Extended "Sold Individually" option.
       */

      if ( ! empty( $product_manager_form_data[ '_wc_pb_sold_individually' ] ) ) {

        $sold_individually_context = wc_clean( $product_manager_form_data[ '_wc_pb_sold_individually' ] );

        if ( in_array( $sold_individually_context, array( 'product', 'configuration' ) ) ) {
          $props[ 'sold_individually' ]         = true;
          $props[ 'sold_individually_context' ] = $sold_individually_context;
        }
      }
      $product_manager_form_data_new = array();
      
      foreach ($product_manager_form_data[ 'bundle_data' ] as $bundlekey => $bundlevalue) {
        
        if( $bundlevalue['product_id'] != '' ) {
          $product_manager_form_data_new[ 'bundle_data' ][$bundlekey] = $bundlevalue;
        }
      }
      
      $posted_bundle_data    = isset( $product_manager_form_data_new[ 'bundle_data' ] ) ? $product_manager_form_data_new[ 'bundle_data' ] : false;
      
      //$fpm_msg = __('Please define defaults for variation attributes, to hide from single-product template the item ','wcmp_pts');

      /*
       * Show invalid group mode selection notice.
       */

      
      foreach($posted_bundle_data as $i => $pbd) {
        $posted_bundle_data[$i]['default_variation_attributes'] = $posted_bundle_data[$i]['default_variation_attributes'];
        $posted_bundle_data[$i]['menu_order'] = $i;

        $bundle_item_obj = wc_get_product( $posted_bundle_data[$i]['product_id'] );
        $bundle_item_type  = $bundle_item_obj->get_type();

        if ( !isset($posted_bundle_data[$i]['single_product_visibility']) ) {
        	if ( in_array( $bundle_item_type, array( 'variable', 'variable-subscription' ) ) ) {
        		if ( isset($posted_bundle_data[$i][ 'override_default_variation_attributes' ]) && 'yes' === $posted_bundle_data[$i][ 'override_default_variation_attributes' ] ) {
								if ( ! empty( $posted_bundle_data[$i]['default_variation_attributes'] ) ) {

									foreach ( $posted_bundle_data[$i]['default_variation_attributes'] as $default_name => $default_value ) {
										if ( ! $default_value ) {
											

											$_SESSION["fpm_msg"] = __('To hide item for variable/variable subscription product from the single-product template, please define defaults for its variation attributes.','wcmp-frontend_product_manager');
											
										}
									}
								}
							} else {
								
								$_SESSION["fpm_msg"] = __('To hide item for variable/variable subscription product from the single-product template, please define defaults for its variation attributes.','wcmp-frontend_product_manager');
							}
        	}
        }



      }
      
      $processed_bundle_data = WC_PB_Meta_Box_Product_Data::process_posted_bundle_data( $posted_bundle_data, $new_product_id );
      
      if ( !empty( $processed_bundle_data ) ) {
        foreach ( $processed_bundle_data as $key => $data ) {
          $processed_bundle_data[ $key ] = array(
            'bundled_item_id' => $data[ 'item_id' ],
            'bundle_id'       => $new_product_id,
            'product_id'      => $data[ 'product_id' ],
            //'menu_order'      => $key,
            'menu_order'      => ( !empty($data[ 'menu_order' ]) ) ? $data[ 'menu_order' ] : $key,
            'meta_data'       => array_diff_key( $data, array( 'item_id' => 1, 'product_id' => 1, 'menu_order' => 1 ) )
          );
        }

        $props[ 'bundled_data_items' ] = $processed_bundle_data;
      }

      $product->set_props($props);

      if ( false === $product->validate_group_mode() ) {
        $_SESSION["fpm_msg"] = $_SESSION["fpm_msg"].'<br />'.__('Invalid Group mode selected. No parent is only applicable to unassembled bundles. To make this bundle an unassembled one:1)Under Product Data, enable the Virtual option. 2)Ensure that Regular Price and Sale Price are empty. 3)Go to Bundled Products tab and enable Shipped Individually.','wcmp-frontend_product_manager');
      }
      
      $product->save();
      
			
    }

  }

  function dc_get_bundled_item_attribute_defaults( $bundled_item_data) {
    $default = array();
    $product = wc_get_product( $bundled_item_data->get_product_id() );

    if ( $product && $product->is_type( 'variable' ) ) {
      foreach ( array_filter( (array) $bundled_item_data->get_meta( 'default_variation_attributes' ), 'strlen' ) as $key => $value ) {
        if ( 0 === strpos( $key, 'pa_' ) ) {
          $default[] = array(
            'id'     => wc_attribute_taxonomy_id_by_name( $key ),
            'name'   => $this->get_attribute_taxonomy_name( $key, $product ),
            'option' => $value,
          );
        } else {
          $default[] = array(
            'id'     => 0,
            'name'   => $this->get_attribute_taxonomy_name( $key, $product ),
            'option' => $value,
          );
        }
      }
    }

    return $default;
  }
}