<?php
class WCMp_Frontend_Product_Manager_Ajax {

	public function __construct() {
		//ajax call for export
    add_action( 'wp_ajax_wcmp_do_ajax_product_export', array( $this, 'wcmp_do_ajax_product_export' ) );

    //add_action('wp_ajax_generate_taxonomy_attributes', array( &$this, 'generate_taxonomy_attributes' ) );
    
    add_action('wp_ajax_generate_variation_attributes', array( &$this, 'generate_variation_attributes' ) );

    //bundle product
    add_action('wp_ajax_dc_check_product_type',array( &$this, 'dc_check_product_type' ) );
    add_action('wp_ajax_nopriv_dc_check_product_type',array( &$this, 'dc_check_product_type' ) );
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
                                                                                        "attributes" => array('label' => __('Attributes', 'wcmp-frontend_product_manager') , 'type' => 'multiinput', 'class' => 'regular-text pro_ele simple variable external', 'label_class' => 'pro_title', 'value' => $attributes, 'options' => array(
                                                                                            "term_name" => array( 'type' => 'hidden', 'label_class' => 'pro_title'),
                                                                                            "name" => array('label' => __('Name', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external', 'label_class' => 'pro_title'),
                                                                                            "value" => array('label' => __('Value(s):', 'wcmp-frontend_product_manager'), 'type' => 'select', 'attributes' => array('multiple' => 'multiple'), 'class' => 'regular-select pro_ele simple variable external', 'options' => $attributes_option, 'label_class' => 'pro_title'),
                                                                                            "is_visible" => array('label' => __('Visible on the product page', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele simple variable external', 'label_class' => 'pro_title checkbox_title'),
                                                                                            "is_variation" => array('label' => __('Use as Variation', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele variable variable-subscription', 'label_class' => 'pro_title checkbox_title pro_ele variable variable-subscription'),
                                                                                            "tax_name" => array('type' => 'hidden'),
                                                                                            "is_taxonomy" => array('type' => 'hidden')
                                                                                        ))
                                                                                      ));
    } else {
      $WCMp_Frontend_Product_Manager->wcmp_wp_fields->dc_generate_form_field( array(  
                                                                                        "attributes" => array('label' => __('Attributes', 'wcmp-frontend_product_manager') , 'type' => 'multiinput', 'class' => 'regular-text pro_ele simple variable external', 'label_class' => 'pro_title', 'value' => $attributes, 'options' => array(
                                                                                            "term_name" => array( 'type' => 'hidden', 'label_class' => 'pro_title'),
                                                                                            "name" => array('label' => __('Name', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text pro_ele simple variable external', 'label_class' => 'pro_title'),
                                                                                            "value" => array('label' => __('Value(s):', 'wcmp-frontend_product_manager'), 'type' => 'textarea', 'class' => 'regular-textarea pro_ele simple variable external', 'placeholder' => sprintf( esc_attr__( 'Enter some text, or some attributes by "%s" separating values.', 'wcmp-frontend_product_manager' ), WC_DELIMITER ), 'label_class' => 'pro_title'),
                                                                                            "is_visible" => array('label' => __('Visible on the product page', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele simple variable external', 'label_class' => 'pro_title checkbox_title'),
                                                                                            "is_variation" => array('label' => __('Use as Variation', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele variable variable-subscription', 'label_class' => 'pro_title checkbox_title pro_ele variable variable-subscription'),
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
                $att_values = explode( WC_DELIMITER, $attributes['value']);
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
              $att_values = explode( WC_DELIMITER, $attributes['value']);
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
	
	/**
    *wcmp_do_ajax_product_export
    */
  public function wcmp_do_ajax_product_export() {
      global $WCMp_Frontend_Product_Manager;
      check_ajax_referer( 'wc-product-export', 'security' );

      if ( ! current_user_can( 'edit_products' ) ) {
          wp_die( -1 );
      }
      require_once ('class-' . esc_attr($WCMp_Frontend_Product_Manager->token) . '-product_csv_exporter.php');
      $step     = absint( $_POST['step'] );
      $exporter = new WCMP_Product_CSV_Exporter();
      //print_r($exporter);die;
      if ( ! empty( $_POST['columns'] ) ) {
          $exporter->set_column_names( $_POST['columns'] );
      }
      if ( ! empty( $_POST['selected_columns'] ) ) {
          $exporter->set_columns_to_export( $_POST['selected_columns'] );
      }
      if ( ! empty( $_POST['export_meta'] ) ) {
          $exporter->enable_meta_export( true );
      }
      if ( ! empty( $_POST['export_types'] ) ) {
          $exporter->set_product_types_to_export( $_POST['export_types'] );
      }
      $exporter->set_page( $step );
      $exporter->generate_file();
      if ( 100 === $exporter->get_percent_complete() ) {
          wp_send_json_success( array(
              'step'       => 'done',
              'percentage' => 100,
              'url'        => add_query_arg( array( 'nonce' => wp_create_nonce( 'product-csv' ), 'action' => 'download_product_csv' ), admin_url( 'edit.php?post_type=product&page=product_exporter' ) ),
          ) );
      } else {
          wp_send_json_success( array(
              'step'       => ++$step,
              'percentage' => $exporter->get_percent_complete(),
              'columns'    => $exporter->get_column_names(),
          ) );
      }
  }

  /**
    * wc get variations for bundle
    */
  public function dc_check_product_type() { 
    $product_id = $_POST['productid'];
    
    $variable_product_items = array();
    $res = 1;
    $product = wc_get_product( $product_id );
    $variable_product_items['subscription'] = 0;
    if($product->is_type('subscription') || $product->is_type('variable-subscription')) {
      $variable_product_items['subscription'] = 1;
    }
    if ($product->is_type('variable') || $product->is_type('variable-subscription')) {
      $variable_product_items['variableproduct'] = 1;
      $res = 0;
      $variations = get_all_variations( $product_id );
    
      $variation_atts = array();
      $product_attributes = get_post_meta($product_id, '_product_attributes', true);

      /*foreach ($product_attributes as $key => $val) {
        $atr_val = str_replace(' ', '', $val['value']);
        $variation_atts[$key] = explode("|",$atr_val);
      }*/
      $variable_product_items['attr_array'] = $variation_atts;
      //print_r($variable_product_items['attr_array']);
      foreach ( $variations as $variation_id ) {
        $product_variation = wc_get_product( $variation_id );
        $attrs = array_keys($product_variation->get_data()['attributes']);
        //print_r($product_variation->get_variation_attributes());
        foreach ($product_variation->get_variation_attributes() as $attr_key => $attr_value) {
          $variation_atts[substr($attr_key,10)][] = $attr_value;
        }
        $variation         = $product->get_available_variation( $product_variation );
        $variable_product_items["title"][$variation_id] =array(wp_strip_all_tags($product_variation->get_formatted_name())); 

      }
    } else {
      $variable_product_items['variableproduct'] = 0;
    } 
    foreach ($variation_atts as $variation_attr_key => $variation_attr) {
      $variation_atts[$variation_attr_key] = array_values(array_filter(array_unique($variation_attr)));
    }
    /*print_r($variation_atts);
    die;*/
    $variable_product_items['attr_array'] = $variation_atts;
    
    print_r(json_encode($variable_product_items));
    die;
  }

}