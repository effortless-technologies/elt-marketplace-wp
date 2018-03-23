<?php
/**
 * WCMp Frontend Manager plugin views
 *
 * Plugin WC Accommodation Booking Products Manage Views
 *
 * @author 		WC Marketplace
 * @package 	wcmp-frontend_product_manager/templates
 * 
 */
global $wp, $WCMp_Frontend_Product_Manager;

$intervals = array();

$intervals['months'] = array(
	'1'  => __( 'January', 'wcmp-frontend_product_manager' ),
	'2'  => __( 'February', 'wcmp-frontend_product_manager' ),
	'3'  => __( 'March', 'wcmp-frontend_product_manager' ),
	'4'  => __( 'April', 'wcmp-frontend_product_manager' ),
	'5'  => __( 'May', 'wcmp-frontend_product_manager' ),
	'6'  => __( 'June', 'wcmp-frontend_product_manager' ),
	'7'  => __( 'July', 'wcmp-frontend_product_manager' ),
	'8'  => __( 'August', 'wcmp-frontend_product_manager' ),
	'9'  => __( 'September', 'wcmp-frontend_product_manager' ),
	'10' => __( 'October', 'wcmp-frontend_product_manager' ),
	'11' => __( 'November', 'wcmp-frontend_product_manager' ),
	'12' => __( 'December', 'wcmp-frontend_product_manager' )
);

$intervals['days'] = array(
	'1' => __( 'Monday', 'wcmp-frontend_product_manager' ),
	'2' => __( 'Tuesday', 'wcmp-frontend_product_manager' ),
	'3' => __( 'Wednesday', 'wcmp-frontend_product_manager' ),
	'4' => __( 'Thursday', 'wcmp-frontend_product_manager' ),
	'5' => __( 'Friday', 'wcmp-frontend_product_manager' ),
	'6' => __( 'Saturday', 'wcmp-frontend_product_manager' ),
	'7' => __( 'Sunday', 'wcmp-frontend_product_manager' )
);

for ( $i = 1; $i <= 53; $i ++ ) {
	$intervals['weeks'][ $i ] = sprintf( __( 'Week %s', 'wcmp-frontend_product_manager' ), $i );
}

$range_types = array(
											'custom'     => __( 'Date range', 'wcmp-frontend_product_manager' ),
											'months'     => __( 'Range of months', 'wcmp-frontend_product_manager' ),
											'weeks'      => __( 'Range of weeks', 'wcmp-frontend_product_manager' ),
											'days'       => __( 'Range of days', 'wcmp-frontend_product_manager' ),
										
										);


$cost_range_types = array(
											'custom'     => __( 'Range of certain nights', 'wcmp-frontend_product_manager' ),
											'months'     => __( 'Range of months', 'wcmp-frontend_product_manager' ),
											'weeks'      => __( 'Range of weeks', 'wcmp-frontend_product_manager' ),
											'days'       => __( 'Range of nights during the week', 'wcmp-frontend_product_manager' )
										);


$booking_qty = 1;

$min_date      = 0;
$min_date_unit = '';
$max_date = 12;
$max_date_unit = '';

$buffer_period= '';
$apply_adjacent_buffer = '';

$default_date_availability = '';

$check_availability_against = '';
$first_block_time = '';

$availability_rule_values = array();
$availability_default_rules = array(  "type"   => 'custom',
																			"from_custom"  => '',
																			"to_custom"    => '',
																			"from_months"  => '',
																			"to_months"    => '',
																			"from_weeks"   => '',
																			"to_weeks"     => '',
																			"from_days"    => '',
																			"to_days"      => '', 
																			"from_time"    => '',
																			"to_time"      => '', 
																			"bookable"     => '',
																			"priority"     => 10
																		);

$booking_cost = '';
$booking_base_cost = '';
$display_cost = '';

$min_persons_group = 1;
$max_persons_group = '';
$person_cost_multiplier = '';
$person_qty_multiplier = '';
$has_person_types = '';
$person_types = array();

$resource_label = '';
$resources_assignment = '';
$resources = array();
$pricing_value = array();


$hr = '';
$phone = '';
$base_cost = 0;
$product_id = 0;

$has_restricted_days = '';
$restricted_days = array();

if (!empty($pro_id)) {
	$product = wc_get_product((int) $pro_id);
	if($product && !empty($product)) {
		$product_id = $product->get_id();
	}
}
if( !function_exists('get_plugin_data') ){
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
$booking_plugin_data = get_plugin_data(ABSPATH . 'wp-content/plugins/woocommerce-bookings/woocommerce-bookings.php');
if( $product_id ) {
	
	$bookable_product = new WC_Product_Booking( $product_id );
	$pricing = get_post_meta( $product_id, '_wc_booking_pricing', true );
	
	if(version_compare( $booking_plugin_data['Version'], '1.10.7', ">=" )) {
	  $has_restricted_days = $bookable_product->get_has_restricted_days( 'edit' );
	  if($bookable_product->get_restricted_days( 'edit' )) {
	    $restricted_days = $bookable_product->get_restricted_days( 'edit' );
	  } else {
	  	$restricted_days = array();
	  }
	}

	if( !empty( $pricing ) ) {
		foreach ($pricing as $key => $p_value) {

			$pricing_value[$key]['type'] = $p_value['type'];
			if($p_value['type'] == 'custom' ) {
				$pricing_value[$key]['from_custom'] = $p_value['from'];
				$pricing_value[$key]['to_custom'] = $p_value['to'];                 
			}
			if($p_value['type'] == 'months' ) {
				$pricing_value[$key]['from_months'] = $p_value['from'];
				$pricing_value[$key]['to_months'] = $p_value['to'];                 
			}
			if($p_value['type'] == 'weeks' ) {
				$pricing_value[$key]['from_weeks'] = $p_value['from'];
				$pricing_value[$key]['to_weeks'] = $p_value['to'];                 
			}
			if($p_value['type'] == 'days' ) {
				$pricing_value[$key]['from_days'] = $p_value['from'];
				$pricing_value[$key]['to_days'] = $p_value['to'];                 
			}
			$pricing_value[$key]['cost'] = (isset($p_value['override_block'])) ? $p_value['override_block'] : '';
		}
	}
	
	
	
	$booking_qty = $bookable_product->get_qty( 'edit' );
	
	$min_date      = $bookable_product->get_min_date_value( 'edit' );
	$min_date_unit = $bookable_product->get_min_date_unit( 'edit' );
	$max_date      = $bookable_product->get_max_date_value( 'edit' );
	$max_date_unit = $bookable_product->get_max_date_unit( 'edit' );
	
	$buffer_period = esc_attr( $bookable_product->get_buffer_period( 'edit' ) );
	$apply_adjacent_buffer = $bookable_product->get_apply_adjacent_buffer( 'edit' ) ? 'yes' : 'no';
	
	$default_date_availability = $bookable_product->get_default_date_availability( 'edit' );
	
	$check_availability_against = $bookable_product->get_check_start_block_only( 'edit' ) ? 'start' : '';
	$first_block_time = $bookable_product->get_first_block_time( 'edit' );
	
	$availability_rules = $bookable_product->get_availability( 'edit' );
	
	if( !empty( $availability_rules ) ) {
		foreach( $availability_rules as $a_index => $availability_rule ) {
			$availability_rule_values[$a_index] = $availability_default_rules;
			$availability_rule_values[$a_index]['type'] = $availability_rule['type'];
			if($availability_rule['type'] == 'custom' ) {
				$availability_rule_values[$a_index]['from_custom'] = $availability_rule['from'];
				$availability_rule_values[$a_index]['to_custom']   = $availability_rule['to'];
			} elseif($availability_rule['type'] == 'months' ) {
				$availability_rule_values[$a_index]['from_months'] = $availability_rule['from'];
				$availability_rule_values[$a_index]['to_months']   = $availability_rule['to'];
			} elseif($availability_rule['type'] == 'weeks' ) {
				$availability_rule_values[$a_index]['from_weeks'] = $availability_rule['from'];
				$availability_rule_values[$a_index]['to_weeks']   = $availability_rule['to'];
			} elseif($availability_rule['type'] == 'days' ) {
				$availability_rule_values[$a_index]['from_days'] = $availability_rule['from'];
				$availability_rule_values[$a_index]['to_days']   = $availability_rule['to'];
			} elseif($availability_rule['type'] == 'time:range' ) {
				$availability_rule_values[$a_index]['from_custom'] = $availability_rule['from_date'];
				$availability_rule_values[$a_index]['to_custom']   = $availability_rule['to_date'];
				$availability_rule_values[$a_index]['from_time'] = $availability_rule['from'];
				$availability_rule_values[$a_index]['to_time']   = $availability_rule['to'];
			} else {
				$availability_rule_values[$a_index]['from_time'] = $availability_rule['from'];
				$availability_rule_values[$a_index]['to_time']   = $availability_rule['to'];
			}
			$availability_rule_values[$a_index]['bookable'] = $availability_rule['bookable'];
			$availability_rule_values[$a_index]['priority'] = $availability_rule['priority'];
		}
	}
	
	$booking_cost = $bookable_product->get_cost( 'edit' );
	$booking_base_cost = $bookable_product->get_base_cost( 'edit' );
	$display_cost = $bookable_product->get_display_cost( 'edit' );

	$base_cost = get_post_meta( $product_id, '_wc_booking_base_cost', true );
	
	$min_persons_group = $bookable_product->get_min_persons( 'edit' );
	$max_persons_group = $bookable_product->get_max_persons( 'edit' ) ? $bookable_product->get_max_persons( 'edit' ) : '';
	$person_cost_multiplier = $bookable_product->get_has_person_cost_multiplier( 'edit' ) ? 'yes' : 'no';
	$person_qty_multiplier = $bookable_product->get_has_person_qty_multiplier( 'edit' ) ? 'yes' : 'no';
	$has_person_types = $bookable_product->get_has_person_types( 'edit' ) ? 'yes' : 'no';
	$person_types_object = $bookable_product->get_person_types( 'edit' );
	if ( $person_types_object ) {
		foreach ( $person_types_object as $person_type_object ) {
			$person_types[] = array('person_id'   => esc_attr( $person_type_object->get_id() ),
															'person_name' => esc_attr( $person_type_object->get_name( 'edit' ) ),
															'person_description' => esc_attr( $person_type_object->get_description( 'edit' ) ),
															'person_cost' => esc_attr( $person_type_object->get_cost( 'edit' ) ),
															'person_block_cost' => esc_attr( $person_type_object->get_block_cost( 'edit' ) ),
															'person_min' => esc_attr( $person_type_object->get_min( 'edit' ) ),
															'person_max' => esc_attr( $person_type_object->get_max( 'edit' ) )
														);
		}
	}
	
	$resource_label = $bookable_product->get_resource_label( 'edit' );
	$resources_assignment = $bookable_product->get_resources_assignment( 'edit' );
	$product_resources    = $bookable_product->get_resource_ids( 'edit' );
	$resource_base_costs  = $bookable_product->get_resource_base_costs( 'edit' );
	$resource_block_costs = $bookable_product->get_resource_block_costs( 'edit' );
	$loop                 = 0;

	if ( $product_resources ) {
		foreach ( $product_resources as $resource_id ) {
			$resource            = new WC_Product_Booking_Resource( $resource_id );
			$resources[$loop]['resource_id'] = $resource->get_id();
			$resources[$loop]['resource_title'] = $resource->get_title();
			$resources[$loop]['resource_base_cost'] = isset( $resource_base_costs[ $resource_id ] ) ? $resource_base_costs[ $resource_id ] : '';
			$resources[$loop]['resource_block_cost'] = isset( $resource_block_costs[ $resource_id ] ) ? $resource_block_costs[ $resource_id ] : '';
			$loop++;
		}
	}
	
} else {
	$availability_rule_values[0] = $availability_default_rules;
}


$resource_ids       = WC_Data_Store::load( 'product-booking-resource' )->get_bookable_product_resource_ids();
$all_resources = array( -1 => __( 'Choose Resource', 'wcmp-frontend_product_manager' ) );
if ( $resource_ids ) {
	foreach ( $resource_ids as $resource_id ) {
		$resource = new WC_Product_Booking_Resource( $resource_id );
	  $all_resources[esc_attr( $resource->ID )] = esc_html( $resource->post_title );
	}
}
?>

<h3 class="pro_ele_head products_manage_availability accommodation-booking"><?php _e('Availability Options', 'wcmp-frontend_product_manager'); ?></h3>
<div class="pro_ele_block accommodation-booking">
	<?php
	
	$availability_arr1 = array( 
				
				"_wc_accommodation_booking_qty" => array('label' => __('Number of rooms available', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'value' => $booking_qty, 'hints' => __( 'The maximum number of rooms available.', 'wcmp-frontend_product_manager' ), 'attributes' => array( 'min' => '', 'step' => '1' ) ),

				"_wc_accommodation_booking_min_date" => array('label' => __('Bookings can be made starting', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele accommodation-booking regular-number-field', 'label_class' => 'pro_title accommodation-booking', 'value' => $min_date ),

				"_wc_accommodation_booking_min_date_unit" => array('label' => __('Bookings can be made starting type', 'wcmp-frontend_product_manager') , 'type' => 'select', 'options' => array( 'month' => __( 'Month(s)', 'wcmp-frontend_product_manager'), 'day' => __( 'Day(s)', 'wcmp-frontend_product_manager' ), 'week' => __( 'Week(s)', 'wcmp-frontend_product_manager' ) ), 'class' => 'regular-select pro_ele accommodation-booking regular-select-field', 'label_class' => 'nolabel', 'desc' => __( 'into the future', 'wcmp-frontend_product_manager' ),'value' => $min_date_unit ),

				"_wc_accommodation_booking_max_date" => array('label' => __('Bookings can only be made', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele accommodation-booking regular-number-field', 'label_class' => 'pro_title accommodation-booking', 'value' => $max_date ),

				"_wc_accommodation_booking_max_date_unit" => array('label' => __('Bookings can only be made type', 'wcmp-frontend_product_manager') , 'type' => 'select', 'options' => array( 'month' => __( 'Month(s)', 'wcmp-frontend_product_manager'), 'day' => __( 'Day(s)', 'wcmp-frontend_product_manager' ), 'week' => __( 'Week(s)', 'wcmp-frontend_product_manager' ) ), 'class' => 'regular-select pro_ele accommodation-booking regular-select-field', 'label_class' => 'nolabel', 'desc' => __( 'into the future', 'wcmp-frontend_product_manager' ),'value' => $max_date_unit ),

			);

	$availability_arr3 = array("_wc_accommodation_booking_availability_rules" =>     array('label' => __('Rules', 'wcmp-frontend_product_manager') , 'type' => 'multiinput', 'class' => 'regular-text pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'desc' => __( 'Rules with lower priority numbers will override rules with a higher priority (e.g. 9 overrides 10 ). Ordering is only applied within the same priority and higher order overrides lower order.', 'wcmp-frontend_product_manager' ), 'desc_class' => 'avail_rules_desc', 'value' => $availability_rule_values, 'options' => array(
									"type" => array('label' => __('Type', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $range_types, 'class' => 'regular-select pro_ele avail_range_type accommodation-booking', 'label_class' => 'pro_title avail_rules_ele avail_rules_label accommodation-booking' ),
									"from_custom" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'custom_attributes' => array( 'date_format' => 'yy-mm-dd'), 'wrapper_class' => 'avail_rule_custom avail_rule_field','class' => 'regular-text dc_datepicker avail_rule_field avail_rule_custom avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_custom avail_rules_ele avail_rules_label' ),
									"to_custom" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'custom_attributes' => array( 'date_format' => 'yy-mm-dd'), 'wrapper_class' => 'avail_rule_custom avail_rule_field','class' => 'regular-text dc_datepicker avail_rule_field avail_rule_custom avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_custom avail_rules_ele avail_rules_label' ),
									"from_months" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['months'], 'wrapper_class' => 'avail_rule_months avail_rule_field','class' => 'regular-select avail_rule_field avail_rule_months avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_months avail_rules_ele avail_rules_label' ),
									"to_months" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['months'], 'wrapper_class' => 'avail_rule_months avail_rule_field','class' => 'regular-select avail_rule_field avail_rule_months avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_months avail_rules_ele avail_rules_label' ),
									"from_weeks" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['weeks'], 'wrapper_class' => 'avail_rule_weeks avail_rule_field','class' => 'regular-select avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_label' ),
									"to_weeks" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['weeks'], 'wrapper_class' => 'avail_rule_weeks avail_rule_field','class' => 'regular-select avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_label' ),
									"from_days" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['days'], 'wrapper_class' => 'avail_rule_days avail_rule_field','class' => 'regular-select avail_rule_field avail_rule_days avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_days avail_rules_ele avail_rules_label' ),
									"to_days" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['days'], 'wrapper_class' => 'avail_rule_days avail_rule_field','class' => 'regular-select avail_rule_field avail_rule_days avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_days avail_rules_ele avail_rules_label' ),
									
									"bookable" => array('label' => __('Bookable', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => array( 'no' => 'NO', 'yes' => 'YES' ), 'class' => 'regular-select pro_ele avail_rules_ele avail_rules_text accommodation-booking', 'label_class' => 'pro_title avail_rules_ele avail_rules_label', 'hints' => __( 'If not bookable, users won\'t be able to choose this block for their accommodation-booking.', 'wcmp-frontend_product_manager' ) ),
									"priority" => array('label' => __('Priority', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text pro_ele avail_rules_ele avail_rule_priority avail_rules_text accommodation-booking', 'label_class' => 'pro_title avail_rules_ele avail_rules_label accommodation-booking', 'hints' => esc_attr( get_wc_booking_priority_explanation() ) ),
									)	)																					
				) ;
	$availability_arr2 = array();
	if(version_compare( $booking_plugin_data['Version'], '1.10.7', ">=" )) {
		
		$availability_arr2 = array(
			"_wc_accommodation_booking_has_restricted_days" => array('label' => __('Restrict start days?', 'wcmp-frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'value' => 1, 'dfvalue' => $has_restricted_days),
				"accomobooking-sunday-restriction" => array('label' => __('Sunday', 'wcmp-frontend_product_manager') , 'type' => 'checkbox','name'=>'_wc_accommodation_booking_restricted_days[0]', 'wrapper_class'=>'restricted_days_dependency pro_ele_hide','class' => 'regular-checkbox pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'value' => 1, 'dfvalue' => ( in_array(0, $restricted_days) )? 1:'' ),
				"accomobooking-monday-restriction" => array('label' => __('Monday', 'wcmp-frontend_product_manager') , 'type' => 'checkbox','name'=>'_wc_accommodation_booking_restricted_days[1]', 'wrapper_class'=>'restricted_days_dependency pro_ele_hide','class' => 'regular-checkbox pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'value' => 1, 'dfvalue' => ( in_array(1, $restricted_days) )? 1:'' ),
				"accomobooking-tuesday-restriction" => array('label' => __('Tuesday', 'wcmp-frontend_product_manager') , 'type' => 'checkbox','name'=>'_wc_accommodation_booking_restricted_days[2]', 'wrapper_class'=>'restricted_days_dependency pro_ele_hide','class' => 'regular-checkbox pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'value' => 1, 'dfvalue' => ( in_array(2, $restricted_days) )? 1:'' ),
				"accomobooking-wednesday-restriction" => array('label' => __('Wednesday ', 'wcmp-frontend_product_manager') , 'type' => 'checkbox','name'=>'_wc_accommodation_booking_restricted_days[3]', 'wrapper_class'=>'restricted_days_dependency pro_ele_hide','class' => 'regular-checkbox pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'value' => 1, 'dfvalue' => ( in_array(3, $restricted_days) )? 1:'' ),
				"accomobooking-thrusday-restriction" => array('label' => __('Thursday', 'wcmp-frontend_product_manager') , 'type' => 'checkbox','name'=>'_wc_accommodation_booking_restricted_days[4]', 'wrapper_class'=>'restricted_days_dependency pro_ele_hide','class' => 'regular-checkbox pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'value' => 1, 'dfvalue' => ( in_array(4, $restricted_days) )? 1:'' ),
				"accomobooking-friday-restriction" => array('label' => __('Friday', 'wcmp-frontend_product_manager') , 'type' => 'checkbox','name'=>'_wc_accommodation_booking_restricted_days[5]', 'wrapper_class'=>'restricted_days_dependency pro_ele_hide','class' => 'regular-checkbox pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'value' => 1, 'dfvalue' => ( in_array(5, $restricted_days) )? 1:'' ),
				"accomobooking-saturday-restriction" => array('label' => __('Saturday', 'wcmp-frontend_product_manager') , 'type' => 'checkbox','name'=>'_wc_accommodation_booking_restricted_days[6]', 'wrapper_class'=>'restricted_days_dependency  pro_ele_hide','class' => 'regular-checkbox pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'value' => 1, 'dfvalue' => ( in_array(6, $restricted_days) )? 1:'' ),
		);
	}
	$WCMp_Frontend_Product_Manager->wcmp_wp_fields->wcmp_generate_form_field(array_merge($availability_arr1,$availability_arr2,$availability_arr3));
	?>
</div>

<h3 class="pro_ele_head products_manage_rates accommodation-booking"><?php _e('Rates', 'wcmp-frontend_product_manager'); ?></h3>
<div class="pro_ele_block accommodation-booking">
<?php
	$WCMp_Frontend_Product_Manager->wcmp_wp_fields->wcmp_generate_form_field( array(  
		"_wc_accommodation_booking_base_cost" => array('label' => __('Standard room rate', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'value' => $base_cost, 'hints' => __( 'OStandard cost for booking the room.', 'wcmp-frontend_product_manager' ), 'attributes' => array( 'min' => '', 'step' => '0.01' ) ),
		"_wc_accommodation_booking_display_cost" => array('label' => __('Display cost', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'value' => $display_cost, 'hints' => __( 'The cost is displayed to the user on the frontend. Leave blank to have it calculated for you. If a booking has varying costs, this will be prefixed with the word `from:`.', 'wcmp-frontend_product_manager' ), 'attributes' => array( 'min' => '', 'step' => '0.01' ) ),

			"_wc_accommodation_booking_range_rules" =>     array('label' => __('Rules', 'wcmp-frontend_product_manager') , 'type' => 'multiinput', 'class' => 'regular-text pro_ele accommodation-booking', 'label_class' => 'pro_title accommodation-booking', 'desc_class' => 'avail_rules_desc', 'value' => $pricing_value, 'options' => array(
						"type" => array('label' => __('Type', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $cost_range_types, 'class' => 'regular-select pro_ele avail_range_type accommodation-booking', 'label_class' => 'pro_title avail_rules_ele avail_rules_label accommodation-booking' ),
						"from_custom" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'custom_attributes' => array( 'date_format' => 'yy-mm-dd'), 'wrapper_class' => 'avail_rule_custom avail_rule_field','class' => 'regular-text dc_datepicker avail_rule_field avail_rule_custom avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_custom avail_rules_ele avail_rules_label' ),
						"to_custom" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'custom_attributes' => array( 'date_format' => 'yy-mm-dd'), 'wrapper_class' => 'avail_rule_custom avail_rule_field','class' => 'regular-text dc_datepicker avail_rule_field avail_rule_custom avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_custom avail_rules_ele avail_rules_label' ),
						"from_months" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['months'], 'wrapper_class' => 'avail_rule_months avail_rule_field','class' => 'regular-select avail_rule_field avail_rule_months avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_months avail_rules_ele avail_rules_label' ),
						"to_months" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['months'], 'wrapper_class' => 'avail_rule_months avail_rule_field','class' => 'regular-select avail_rule_field avail_rule_months avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_months avail_rules_ele avail_rules_label' ),
						"from_weeks" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['weeks'], 'wrapper_class' => 'avail_rule_weeks avail_rule_field','class' => 'regular-select avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_label' ),
						"to_weeks" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['weeks'], 'wrapper_class' => 'avail_rule_weeks avail_rule_field','class' => 'regular-select avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_label' ),
						"from_days" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['days'], 'wrapper_class' => 'avail_rule_days avail_rule_field','class' => 'regular-select avail_rule_field avail_rule_days avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_days avail_rules_ele avail_rules_label' ),
						"to_days" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['days'], 'wrapper_class' => 'avail_rule_days avail_rule_field','class' => 'regular-select avail_rule_field avail_rule_days avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_days avail_rules_ele avail_rules_label' ),
						"from_time" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'time', 'placeholder' => 'HH:MM', 'wrapper_class' => 'avail_rule_time avail_rule_field','class' => 'regular-text avail_rule_field avail_rule_time avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_time avail_rules_ele avail_rules_label' ),
						"to_time" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'time', 'placeholder' => 'HH:MM', 'wrapper_class' => 'avail_rule_time avail_rule_field','class' => 'regular-text avail_rule_field avail_rule_time avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_time avail_rules_ele avail_rules_label' ),
						
						"cost" => array('label' => __('Cost', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text pro_ele avail_rules_ele avail_rule_priority avail_rules_text accommodation-booking', 'label_class' => 'pro_title avail_rules_ele avail_rules_label accommodation-booking' ),
						)	)

			
																									
			) );
	?>
</div>





