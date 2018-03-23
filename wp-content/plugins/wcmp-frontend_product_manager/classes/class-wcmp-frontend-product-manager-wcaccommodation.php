<?php
/**
 * WCMp Frontend Manager plugin core
 *
 * Booking WC Accommodation Booking Support
 *
 * @author    WC Marketplace
 * @package   wcmp-frontend_product_manager/classes 
 */

class WCMp_Frontend_Product_Manager_WCAccommodationBookings {
  public function __construct() {
    global $WCMp, $WCMp_Frontend_Product_Manager;
    if( wcmp_forntend_manager_is_accommodation_booking() ) {
      add_filter( 'wcmp_product_types', array( &$this, 'wcaccommodation_product_types' ), 20 );

      // Bookable Product options
      add_filter( 'wcmp_fpm_fields_general', array( &$this, 'wcaccommodation_product_manage_fields_general' ), 10, 2 );

      //Booking Product Resources
      add_filter( 'wcmp_fpm_resource_fields', array( &$this, 'wcaccommodation_product_manage_fields_resource' ), 10, 2 );
      add_filter( 'wcmp_booking_resource_fields', array( &$this, 'wcaccommodation_fields_resource' ), 10 );
      
      //Booking Product Persons
      add_filter( 'wcmp_fpm_persons_fields', array( &$this, 'wcaccommodation_product_manage_fields_persons' ), 10, 2 );
      add_filter( 'wcmp_fpm_persons_fields_options', array( &$this, 'wcaccommodation_fields_person_options' ), 10 );

      // Booking General Block
      add_action( 'after_wcmp_fpm_general', array( &$this, 'wcaccommodation_product_manage_general' ) );

      // Booking Product Manage View
      add_action( 'end_wcmp_fpm_products_manage', array( &$this, 'wcaccommodation_wcmp_fpm_form_load_views' ), 20 );

      // Booking Product Meta Data Save
      add_action( 'after_wcmp_fpm_meta_save', array( &$this, 'wcaccommodation_wcmp_fpm_meta_save' ), 25, 2 );

      //Addon field add 
      add_filter( 'wcmp_fpm_addon_fields', array( &$this, 'wcab_product_manage_fields_addon_fields' ), 10, 2 );
      

     
      
    }
  }

 

  function wcaccommodation_wcmp_fpm_form_load_views( $product_id ) {
    global $WCMp, $WCMp_Frontend_Product_Manager,$DC_Accommodation_Listing_Support;
    $WCMp_Frontend_Product_Manager->template->get_template('wcmp-fpm-view-wcaccommodationbookings.php', array('pro_id' => $product_id));
  }

  function wcaccommodation_product_types($pro_types) {
    global $WCMp, $WCMp_Frontend_Product_Manager;
    $vendor_can = $WCMp->vendor_caps->vendor_can('accommodation-booking');
    if ( ($vendor_can && current_user_can( 'manage_bookings' )) || current_user_can( 'administrator' ) || current_user_can( 'shop_manager' )) {
      $pro_types['accommodation-booking'] = __( 'Accommodation product', 'wcmp-frontend_product_manager' );
    }
    
    return $pro_types;
  }

  function wcaccommodation_product_manage_fields_persons($general_fields, $product_id) {

    $general_fields['_wc_booking_min_persons_group']['class'] .= ' accommodation-booking';
    $general_fields['_wc_booking_min_persons_group']['label_class'] .= ' accommodation-booking';

    $general_fields['_wc_booking_max_persons_group']['class'] .= ' accommodation-booking';
    $general_fields['_wc_booking_max_persons_group']['label_class'] .= ' accommodation-booking';

    $general_fields['_wc_booking_person_cost_multiplier']['class'] .= ' accommodation-booking';
    $general_fields['_wc_booking_person_cost_multiplier']['label_class'] .= ' accommodation-booking';

    $general_fields['_wc_booking_person_qty_multiplier']['class'] .= ' accommodation-booking';
    $general_fields['_wc_booking_person_qty_multiplier']['label_class'] .= ' accommodation-booking';

    $general_fields['_wc_booking_has_person_types']['class'] .= ' accommodation-booking';
    $general_fields['_wc_booking_has_person_types']['label_class'] .= ' accommodation-booking';

    return $general_fields;
  }

  function wcaccommodation_fields_person_options($person_options) {
    $person_options['person_name']['class'] .= ' accommodation-booking';
    $person_options['person_name']['label_class'] .= ' accommodation-booking';

    $person_options['person_cost']['class'] .= ' accommodation-booking';
    $person_options['person_cost']['label_class'] .= ' accommodation-booking';

    $person_options['person_min']['class'] .= ' accommodation-booking';
    $person_options['person_min']['label_class'] .= ' accommodation-booking';

    $person_options['person_description']['class'] .= ' accommodation-booking';
    $person_options['person_description']['label_class'] .= ' accommodation-booking';

    $person_options['person_block_cost']['class'] .= ' accommodation-booking';
    $person_options['person_block_cost']['label_class'] .= ' accommodation-booking';

    $person_options['person_max']['class'] .= ' accommodation-booking';
    $person_options['person_max']['label_class'] .= ' accommodation-booking';

    return $person_options;
  }

  function wcaccommodation_product_manage_fields_resource($general_fields, $product_id){
    $general_fields['_wc_booking_resource_label']['class'] .= ' accommodation-booking';
    $general_fields['_wc_booking_resource_label']['label_class'] .= ' accommodation-booking';

    $general_fields['_wc_booking_resources_assignment']['class'] .= ' accommodation-booking';
    $general_fields['_wc_booking_resources_assignment']['label_class'] .= ' accommodation-booking';

    $general_fields['_wc_booking_all_resources']['class'] .= ' accommodation-booking';
    $general_fields['_wc_booking_all_resources']['label_class'] .= ' accommodation-booking';

    $general_fields['_wc_booking_resources']['class'] .= ' accommodation-booking';
    $general_fields['_wc_booking_resources']['label_class'] .= ' accommodation-booking';

    return $general_fields;
  }

  function wcaccommodation_fields_resource($resources) {

    $resources['resource_title']['class'] .= ' accommodation-booking';
    $resources['resource_title']['label_class'] .= ' accommodation-booking';

    $resources['resource_base_cost']['class'] .= ' accommodation-booking';
    $resources['resource_base_cost']['label_class'] .= ' accommodation-booking';

    $resources['resource_block_cost']['class'] .= ' accommodation-booking';
    $resources['resource_block_cost']['label_class'] .= ' accommodation-booking';

    return $resources;

  }

  function wcaccommodation_product_manage_fields_general($general_fields, $product_id) {
    global $WCMp, $WCMp_Frontend_Product_Manager;
    
    // Has Resource
    $has_resources = ( get_post_meta( $product_id, '_wc_booking_has_resources', true) ) ? 'yes' : '';
    
    // Has Persons
    $has_persons = ( get_post_meta( $product_id, '_wc_booking_has_persons', true) ) ? 'yes' : '';

    $general_fields['regular_price']['class'] .= ' non-accommodation-booking';
    $general_fields['regular_price']['label_class'] .= ' non-accommodation-booking';
     
    $general_fields['sale_price']['class'] .= ' non-accommodation-booking';
    $general_fields['sale_price']['label_class'] .= ' non-accommodation-booking';

    $general_fields['sale_date_from']['class'] .= ' non-accommodation-booking';
    $general_fields['sale_date_from']['label_class'] .= ' non-accommodation-booking';

    $general_fields['sale_date_upto']['class'] .= ' non-accommodation-booking';
    $general_fields['sale_date_upto']['label_class'] .= ' non-accommodation-booking';

       
    $general_fields = array("_wc_accommodation_booking_has_resources" => array('label' => __('Has Resouces', 'wcmp-frontend_product_manager') , 'type' => 'checkbox','wrapper_class'=>'pro_ele accommodation-booking', 'class' => 'regular-checkbox pro_ele accommodation-booking', 'name' => '_wc_booking_has_resources','label_class' => 'pro_title pro_ele checkbox_title accommodation-booking', 'value' => 'yes', 'dfvalue' => $has_resources),
      "_wc_accommodation_booking_has_persons" => array('label' => __('Has Persons', 'wcmp-frontend_product_manager') , 'type' => 'checkbox', 'wrapper_class'=>'pro_ele accommodation-booking','class' => 'regular-checkbox pro_ele accommodation-booking', 'label_class' => 'pro_title pro_ele checkbox_title accommodation-booking', 'value' => 'yes', 'dfvalue' => $has_persons),
                ) + $general_fields;
 
     
    return $general_fields;
  }


  /**
   * WC Booking Product Addon options
   */
  function wcab_product_manage_fields_addon_fields( $addon_fields, $product_id ) {
    global $WCMp, $WCMp_Frontend_Product_Manager;
    $addon_fields = array_slice($addon_fields, 0, 1, true) +
                                  array("addon_wc_accommodation_booking_person_qty_multiplier" => array('label' => __('Bookings: Multiply cost by person count', 'wcmp-frontend_product_manager') , 'type' => 'checkbox', 'wrapper_class' => 'pro_ele accommodation-booking','class' => 'regular-checkbox pro_ele accommodation-booking', 'label_class' => 'pro_title pro_ele checkbox_title accommodation-booking', 'value' => 1),
                                        
                                    "addon_wc_accommodation_booking_block_qty_multiplier" => array('label' => __('Bookings: Multiply cost by number of nights', 'wcmp-frontend_product_manager') , 'type' => 'checkbox', 'wrapper_class' => 'pro_ele accommodation-booking ','class' => 'regular-checkbox pro_ele accommodation-booking', 'label_class' => 'pro_title pro_ele checkbox_title accommodation-booking', 'value' => 1),
                                        ) +
                                  array_slice($addon_fields, 1, count($addon_fields) - 1, true) ;
    return $addon_fields;
  }

  function wcab_product_manage_fields_addon_fields_save( $_product_addons ) {
    global $WCMp, $WCMp_Frontend_Product_Manager;   
      
    if( !empty( $_product_addons ) ) {
      $loop_index = 0;
      foreach( $_product_addons as $_product_addon_index => $_product_addon ) {
        $_product_addons[$_product_addon_index]['position'] = $loop_index;

        if( isset( $_product_addon['addon_wc_accommodation_booking_person_qty_multiplier'] ) ) $_product_addons[$_product_addon_index]['addon_wc_accommodation_booking_person_qty_multiplier'] = 1;
        else $_product_addons[$_product_addon_index]['addon_wc_accommodation_booking_person_qty_multiplier'] = 0;

        if( isset( $_product_addon['addon_wc_accommodation_booking_block_qty_multiplier'] ) ) $_product_addons[$_product_addon_index]['addon_wc_accommodation_booking_block_qty_multiplier'] = 1;
        else $_product_addons[$_product_addon_index]['addon_wc_accommodation_booking_block_qty_multiplier'] = 0;

        $loop_index++;
      }
      return $_product_addons;
    }

  }
  /**
    * Accommodation booking field add
    */

  function wcaccommodation_product_manage_general( $product_id ) {
    global $WCMp, $WCMp_Frontend_Product_Manager;
    
    $bookable_product = new WC_Product_Booking( $product_id );
    
    $duration_type = $bookable_product->get_duration_type( 'edit' );
    $min_duration = ( !empty( get_post_meta( $product_id, '_wc_booking_min_duration', true ) )) ? absint( get_post_meta( $product_id, '_wc_booking_min_duration', true ) ) : 1 ;
    $max_duration = ( !empty( get_post_meta( $product_id, '_wc_booking_max_duration', true ) )) ? absint( get_post_meta( $product_id, '_wc_booking_max_duration', true ) ) : 7 ;
    
    $enable_range_picker = $bookable_product->get_enable_range_picker( 'edit' ) ? 'yes' : 'no';
    
    $calendar_display_mode = $bookable_product->get_calendar_display_mode( 'edit' );
    $requires_confirmation = $bookable_product->get_requires_confirmation( 'edit' ) ? 'yes' : 'no';
    
    $user_can_cancel = $bookable_product->get_user_can_cancel( 'edit' ) ? 'yes' : 'no';
    $cancel_limit = $bookable_product->get_cancel_limit( 'edit' );
    $cancel_limit_unit = $bookable_product->get_cancel_limit_unit( 'edit' );

   
    ?>
    <!-- collapsible Booking 1 -->
    <h3 class="pro_ele_head accommodation-booking"><?php _e('Booking Options', 'wcmp-frontend_product_manager'); ?></h3>
    <div class="pro_ele_block accommodation-booking">
      <p>
        <?php
          $WCMp_Frontend_Product_Manager->wcmp_wp_fields->wcmp_generate_form_field( array(  
            "_wc_accommodation_booking_min_duration" => array('label' => __('Minimum number of nights allowed in a booking', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'hints' => __( 'The minimum allowed duration the user can stay.', 'wcmp-frontend_product_manager' ) ,'value' => $min_duration ),

            "_wc_accommodation_booking_max_duration" => array('label' => __('Maximum number of nights allowed in a booking', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'hints' => __( 'The maximum allowed duration the user can stay.', 'wcmp-frontend_product_manager' ),'value' => $max_duration ),

            "_wc_accommodation_booking_calendar_display_mode" => array('label' => __('Calendar display mode', 'wcmp-frontend_product_manager') , 'type' => 'select', 'options' => array( '' => __( 'Display calendar on click', 'wcmp-frontend_product_manager'), 'always_visible' => __( 'Calendar always visible', 'wcmp-frontend_product_manager' ) ), 'class' => 'regular-select pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'hints' => __( 'Choose how the calendar is displayed on the booking form.', 'wcmp-frontend_product_manager' ),'value' => $calendar_display_mode ),
            "_wc_accommodation_booking_requires_confirmation" => array('label' => __('Requires confirmation?', 'wcmp-frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'value' => 'yes', 'dfvalue' => $requires_confirmation, 'hints' => __( 'Check this box if the accommodation-booking requires admin approval/confirmation. Payment will not be taken during checkout.', 'wcmp-frontend_product_manager' ) ),
            "_wc_accommodation_booking_user_can_cancel" => array('label' => __('Can be cancelled?', 'wcmp-frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'value' => 'yes', 'dfvalue' => $user_can_cancel, 'hints' => __( 'Check this box if the accommodation-booking can be cancelled by the customer after it has been purchased. A refund will not be sent automatically.', 'wcmp-frontend_product_manager' ) ),
            "_wc_accommodation_booking_cancel_limit" => array('label' => __('Cancellation up till', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele accommodation-booking regular-number-field', 'label_class' => 'pro_title accommodation-booking', 'value' => $cancel_limit ),
            "_wc_accommodation_booking_cancel_limit_unit" => array('label' => __('Cancellation up till type', 'wcmp-frontend_product_manager') , 'type' => 'select', 'options' => array( 'month' => __( 'Month(s)', 'wcmp-frontend_product_manager'), 'day' => __( 'Day(s)', 'wcmp-frontend_product_manager' ), 'hour' => __( 'Hour(s)', 'wcmp-frontend_product_manager' ), 'minute' => __( 'Minute(s)', 'wcmp-frontend_product_manager' ) ), 'class' => 'regular-select pro_ele accommodation-booking regular-select-field', 'label_class' => 'nolabel accommodation-booking', 'desc_class' => 'accommodation-booking', 'value' => $cancel_limit_unit, 'desc' => __( 'before check-in.', 'wcmp-frontend_product_manager' ) )
            
          ) );
        
        ?>
      </p>
    </div>
    <?php

  }

  /**
    * Accommodation Booking Product Save
    */

  function wcaccommodation_wcmp_fpm_meta_save( $new_product_id, $product_manager_form_data ) {
    global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager, $_POST;
    $product_type = empty( $product_manager_form_data['product_type'] ) ? WC_Product_Factory::get_product_type( $new_product_id ) : sanitize_title( stripslashes( $product_manager_form_data['product_type'] ) );
    $classname    = WC_Product_Factory::get_product_classname( $new_product_id, $product_type ? $product_type : 'simple' );
    $product      = new $classname( $new_product_id );
    
    // Only set props if the product is a bookable product.
    if ( 'accommodation-booking' !== $product_type ) {
      return;
    }
    
    // Availability Rules
    $availability_rule_index = 0;
    $availability_rules = array();
    $availability_default_rules = array(  "type"       => 'custom',
                                          "from"       => '',
                                          "to"         => '',
                                          "bookable"   => '',
                                          "priority"   => 10
                                        );

    // Availability Rules
   
    if( isset($product_manager_form_data['_wc_accommodation_booking_availability_rules']) && !empty($product_manager_form_data['_wc_accommodation_booking_availability_rules']) ) {
      foreach( $product_manager_form_data['_wc_accommodation_booking_availability_rules'] as $availability_rule ) {
        $availability_rules[$availability_rule_index] = $availability_default_rules;
        $availability_rules[$availability_rule_index]['type'] = $availability_rule['type'];
        if( $availability_rule['type'] == 'custom' ) {
          $availability_rules[$availability_rule_index]['from'] = $availability_rule['from_custom'];
          $availability_rules[$availability_rule_index]['to']   = $availability_rule['to_custom'];
        } elseif( $availability_rule['type'] == 'months' ) {
          $availability_rules[$availability_rule_index]['from'] = $availability_rule['from_months'];
          $availability_rules[$availability_rule_index]['to']   = $availability_rule['to_months'];
        } elseif($availability_rule['type'] == 'weeks' ) {
          $availability_rules[$availability_rule_index]['from'] = $availability_rule['from_weeks'];
          $availability_rules[$availability_rule_index]['to']   = $availability_rule['to_weeks'];
        } elseif($availability_rule['type'] == 'days' ) {
          $availability_rules[$availability_rule_index]['from'] = $availability_rule['from_days'];
          $availability_rules[$availability_rule_index]['to']   = $availability_rule['to_days'];
        } elseif($availability_rule['type'] == 'time:range' ) {
          $availability_rules[$availability_rule_index]['from_date'] = $availability_rule['from_custom'];
          $availability_rules[$availability_rule_index]['to_date']   = $availability_rule['to_custom'];
          $availability_rules[$availability_rule_index]['from'] = $availability_rule['from_time'];
          $availability_rules[$availability_rule_index]['to']   = $availability_rule['to_time'];
        } else {
          $availability_rules[$availability_rule_index]['from'] = $availability_rule['from_time'];
          $availability_rules[$availability_rule_index]['to']   = $availability_rule['to_time'];
        }
        $availability_rules[$availability_rule_index]['bookable'] = $availability_rule['bookable'];
        $availability_rules[$availability_rule_index]['priority'] = $availability_rule['priority'];
        $availability_rule_index++;
      }
    }
    
    // Person Types
    $person_type_index = 0;
    $person_types = array();
    if( isset( $product_manager_form_data['_wc_accommodation_booking_has_persons'] ) && isset($product_manager_form_data['_wc_booking_person_types']) && !empty($product_manager_form_data['_wc_booking_person_types']) ) {
      foreach( $product_manager_form_data['_wc_booking_person_types'] as $booking_person_types ) {
        $loop    = intval( $person_type_index );
    
        $person_type_id = ( isset( $booking_person_types['person_id'] ) ) ? $booking_person_types['person_id'] : 0;
        if( !$person_type_id ) {
          $person_type = new WC_Product_Booking_Person_Type();
          $person_type->set_parent_id( $product->get_id() );
          $person_type->set_sort_order( $loop );
          $person_type_id = $person_type->save();
        } else {
          $person_type = new WC_Product_Booking_Person_Type( $person_type_id );
        }
        
        $person_type->set_props( array(
          'name'        => wc_clean( stripslashes( $booking_person_types['person_name'] ) ),
          'description' => wc_clean( stripslashes( $booking_person_types['person_description'] ) ),
          'sort_order'  => absint( $person_type_index ),
          'cost'        => wc_clean( $booking_person_types['person_cost'] ),
          'block_cost'  => wc_clean( $booking_person_types['person_block_cost'] ),
          'min'         => wc_clean( $booking_person_types['person_min'] ),
          'max'         => wc_clean( $booking_person_types['person_max'] ),
          'parent_id'   => $product->get_id(),
        ) );
        $person_types[] = $person_type;
        $person_type_index++;
      }
    }
    
    // Resources
    $resource_index = 0;
    $resources = array();

    if( isset( $product_manager_form_data['_wc_booking_has_resources'] ) && isset($product_manager_form_data['_wc_booking_resources']) && !empty($product_manager_form_data['_wc_booking_resources']) ) {
      foreach( $product_manager_form_data['_wc_booking_resources'] as $booking_resources ) {
        if( $booking_resources[ 'resource_title' ] ) {
          $loop    = intval( $resource_index );
          $resource_id = ( isset( $booking_resources['resource_id'] ) ) ? absint( $booking_resources['resource_id'] ) : 0;
          // Creating new Resource
          if( !$resource_id ) {
            $nresource = new WC_Product_Booking_Resource();
            $nresource->set_name( $booking_resources[ 'resource_title' ] );
            $resource_id = $nresource->save();
          }
          $resources[ $resource_id ] = array(
            'base_cost'  => wc_clean( $booking_resources[ 'resource_base_cost' ] ),
            'block_cost' => wc_clean( $booking_resources[ 'resource_block_cost' ] ),
          );


          $resource_index++;
        }
      }
     
    }

  
    // Remove Deleted Person Types
    if(isset($_POST['removed_person_types']) && !empty($_POST['removed_person_types'])) {
      foreach($_POST['removed_person_types'] as $removed_person_type) {
        wp_delete_post($removed_person_type, true);
      }
    }

    // Rates
    $pricing = array();
 
  
    $i = 0; 
    foreach ($product_manager_form_data[ '_wc_accommodation_booking_range_rules' ] as $key => $accommodation_booking_range) {
      $pricing[ $i ]['type'] = $accommodation_booking_range['type'];
      $pricing[ $i ]['base_cost'] = $pricing[ $i ]['cost'] = 0;
      $pricing[ $i ]['base_modifier'] = $pricing[$i]['modifier'] = 'plus';
      $pricing[ $i ]['override_block'] = $accommodation_booking_range['cost'];

      switch ( $pricing[ $i ]['type'] ) {
        case 'custom' :
          $pricing[ $i ]['from'] = wc_clean( $accommodation_booking_range['from_custom'] );
          $pricing[ $i ]['to']   = wc_clean( $accommodation_booking_range['to_custom'] );
        break;
        case 'months' :
          $pricing[ $i ]['from'] = wc_clean( $accommodation_booking_range['from_months'] );
          $pricing[ $i ]['to']   = wc_clean( $accommodation_booking_range['to_months'] );
        break;
        case 'weeks' :
          $pricing[ $i ]['from'] = wc_clean( $accommodation_booking_range['from_weeks'] );
          $pricing[ $i ]['to']   = wc_clean( $accommodation_booking_range['to_weeks'] );
        break;
        case 'days' :
          $pricing[ $i ]['from'] = wc_clean( $accommodation_booking_range['from_days'] );
          $pricing[ $i ]['to']   = wc_clean( $accommodation_booking_range['to_days'] );
        break;
      }
      $i++;
      
    }
    
    $wc_booking_restricted_days = '';
    if(isset($product_manager_form_data['_wc_accommodation_booking_restricted_days']) && !empty($product_manager_form_data['_wc_accommodation_booking_restricted_days'])) {
      $wc_booking_restricted_days = array_combine(array_keys($product_manager_form_data['_wc_accommodation_booking_restricted_days']),array_keys($product_manager_form_data['_wc_accommodation_booking_restricted_days']));
    } else {
      $wc_booking_restricted_days = '';
    }
    
    $errors = $product->set_props( apply_filters( 'wcmp_pts_accommodation_booking_data_factory', array(
      'min_duration'              =>  (int)$product_manager_form_data['_wc_accommodation_booking_min_duration'] ,
      'max_duration'              =>  (int)$product_manager_form_data['_wc_accommodation_booking_max_duration'] ,
      'calendar_display_mode'      => wc_clean( $product_manager_form_data['_wc_accommodation_booking_calendar_display_mode'] ),
     'requires_confirmation'      => ( $product_manager_form_data['_wc_accommodation_booking_requires_confirmation'] == 'yes') ? 'yes'  : '',
      'user_can_cancel'            => ( $product_manager_form_data['_wc_accommodation_booking_user_can_cancel'] == 'yes') ? 'yes'  : '',
      //'user_can_cancel'            => isset( $product_manager_form_data['_wc_accommodation_booking_user_can_cancel'] ),
      'cancel_limit_unit'          => wc_clean( $product_manager_form_data['_wc_accommodation_booking_cancel_limit_unit'] ),
      'cancel_limit'               => wc_clean( $product_manager_form_data['_wc_accommodation_booking_cancel_limit'] ),
      'availability'               => $availability_rules,
      'qty'                        => wc_clean( $product_manager_form_data['_wc_accommodation_booking_qty'] ),
      'min_date_unit'              => wc_clean( $product_manager_form_data['_wc_accommodation_booking_min_date_unit'] ),
      'min_date_value'                   => (int) $product_manager_form_data['_wc_accommodation_booking_min_date'] ,
      'max_date_unit'              => wc_clean( $product_manager_form_data['_wc_accommodation_booking_max_date_unit'] ),
      'max_date_value'             => (int) $product_manager_form_data['_wc_accommodation_booking_max_date'] ,
      'base_cost'                  => wc_clean( $product_manager_form_data['_wc_accommodation_booking_base_cost'] ),
      'display_cost'               => wc_clean( $product_manager_form_data['_wc_accommodation_booking_display_cost'] ),

      'has_restricted_days'        => isset($product_manager_form_data['_wc_accommodation_booking_has_restricted_days']) ? wc_clean( $product_manager_form_data['_wc_accommodation_booking_has_restricted_days'] ) : '',
      'restricted_days'            => isset($product_manager_form_data['_wc_accommodation_booking_has_restricted_days']) ? $wc_booking_restricted_days : '',

      'has_person_cost_multiplier' => isset( $product_manager_form_data['_wc_booking_person_cost_multiplier'] ),
      'has_person_qty_multiplier'  => isset( $product_manager_form_data['_wc_booking_person_qty_multiplier'] ),
      'has_person_types'           => isset( $product_manager_form_data['_wc_booking_has_person_types'] ),
      'has_persons'                => isset( $product_manager_form_data['_wc_accommodation_booking_has_persons'] ),
      'has_resources'              => isset( $product_manager_form_data['_wc_booking_has_resources'] ),
      'max_persons'                => wc_clean( $product_manager_form_data['_wc_booking_max_persons_group'] ),
      'min_persons'                => wc_clean( $product_manager_form_data['_wc_booking_min_persons_group'] ),
      'person_types'               => $person_types,
      //'pricing'                    => $this->get_posted_pricing(),
      'resource_label'             => wc_clean( $product_manager_form_data['_wc_booking_resource_label'] ),
      'resource_base_costs'        => wp_list_pluck( $resources, 'base_cost' ),
      'resource_block_costs'       => wp_list_pluck( $resources, 'block_cost' ),
      'resource_ids'               => array_keys( $resources ),
      'resources_assignment'       => wc_clean( $product_manager_form_data['_wc_booking_resources_assignment'] ),
    ), $new_product_id, $product, $product_manager_form_data ) );
    
    if ( is_wp_error( $errors ) ) {
      
    }
    
    $product->save();
    update_post_meta( $new_product_id, '_wc_booking_pricing', $pricing );
    
    
  }
}