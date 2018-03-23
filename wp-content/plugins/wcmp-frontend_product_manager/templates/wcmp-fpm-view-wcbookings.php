<?php
/**
 * WCMp Frontend Manager plugin view
 *
 * Plugin WC Booking Products Manage Views
 *
 * @author 		WC Marketplace
 * @package 	wcmp-frontend_product_manager/templates
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
											'time'       => '&nbsp;&nbsp;&nbsp;' .  __( 'Time Range (all week)', 'wcmp-frontend_product_manager' ),
											'time:range' => '&nbsp;&nbsp;&nbsp;' . __( 'Date Range with time', 'wcmp-frontend_product_manager' )
										);
$option_types = array(
											'custom'     => __( 'Date range', 'wcmp-frontend_product_manager' ),
											'months'     => __( 'Range of months', 'wcmp-frontend_product_manager' ),
											'weeks'      => __( 'Range of weeks', 'wcmp-frontend_product_manager' ),
											'days'       => __( 'Range of days', 'wcmp-frontend_product_manager' ),
											'persons'    => __( 'Person count', 'wcmp-frontend_product_manager' ),
											'blocks'    => __( 'Block count', 'wcmp-frontend_product_manager' ),
											'time'       => '&nbsp;&nbsp;&nbsp;' .  __( 'Time Range (all week)', 'wcmp-frontend_product_manager' ),
											'time:range' => '&nbsp;&nbsp;&nbsp;' . __( 'Date Range with time', 'wcmp-frontend_product_manager' )
										);

$modifier_types = array(
											''     => __( '+', 'wcmp-frontend_product_manager' ),
											'minus'     => __( '-', 'wcmp-frontend_product_manager' ),
											'times'      => __( '×', 'wcmp-frontend_product_manager' ),
											'divide'       => __( '÷', 'wcmp-frontend_product_manager' ),
											'equals'       => __( '=', 'wcmp-frontend_product_manager' )								
										);
foreach ( $intervals['days'] as $key => $label ) :
	$range_types['time:' . $key] = '&nbsp;&nbsp;&nbsp;' . $label;
	$option_types['time:' . $key] = '&nbsp;&nbsp;&nbsp;' . $label;
endforeach;

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
$product_pricing_values = array();
$product_pricing_default_values = array(  "type"   => 'custom',
																			    "from_date"  => '',
																				  "to_date"    => '',
																					"from_month"  => '',
																					"to_month"    => '',
																					"from_week"   => '',
																					"to_week"     => '',
																					"from_day_of_week"    => '',
																					"to_day_of_week"      => '', 
																					"from_time"    => '',
																					"to_time"      => '',
																					"from"    => '',
																					"to"      => '',
																					"base_cost_modifier"	=> '',
																					"base_cost"				=> '',
																					"cost_modifier"				=> '',
																					"cost"				=> ''
																			
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
	
	$booking_qty = $bookable_product->get_qty( 'edit' );

	$product_pricing = get_post_meta( $product_id, '_wc_booking_pricing', true );
	
	
	
	if(version_compare( $booking_plugin_data['Version'], '1.10.7', ">=" )) {
	  $has_restricted_days = $bookable_product->get_has_restricted_days( 'edit' );
	  if($bookable_product->get_restricted_days( 'edit' )) {
	    $restricted_days = $bookable_product->get_restricted_days( 'edit' );
	  } else {
	  	$restricted_days = array();
	  }
	}
	
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
	
	//booking cost pricing 
	if( !empty($product_pricing)) {
		foreach ($product_pricing as $p_index => $value) {
			$product_pricing_values[$p_index] = $product_pricing_default_values;
			$product_pricing_values[$p_index]['type'] = $value['type'];
			if($value['type'] == 'custom' ) {
				$product_pricing_values[$p_index]['from_date'] = $value['from'];
				$product_pricing_values[$p_index]['to_date']   = $value['to'];
			} elseif($value['type'] == 'months' ) {
				$product_pricing_values[$p_index]['from_month'] = $value['from'];
				$product_pricing_values[$p_index]['to_month']   = $value['to'];
			} elseif($value['type'] == 'weeks' ) {
				$product_pricing_values[$p_index]['from_week'] = $value['from'];
				$product_pricing_values[$p_index]['to_week']   = $value['to'];
			} elseif($value['type'] == 'days' ) {
				$product_pricing_values[$p_index]['from_day_of_week'] = $value['from'];
				$product_pricing_values[$p_index]['to_day_of_week']   = $value['to'];
			} elseif($value['type'] == 'time:range') {
				$product_pricing_values[$p_index]['from_date'] = $value['from_date'];
				$product_pricing_values[$p_index]['to_date']   = $value['to_date'];
				$product_pricing_values[$p_index]['from_time'] = $value['from'];
				$product_pricing_values[$p_index]['to_time']   = $value['to'];
			}	elseif($value['type'] == 'persons' || $value['type'] == 'blocks') {
				$product_pricing_values[$p_index]['from'] = $value['from'];
				$product_pricing_values[$p_index]['to']   = $value['to'];
			}else {
				$product_pricing_values[$p_index]['from_time'] = $value['from'];
				$product_pricing_values[$p_index]['to_time']   = $value['to'];
				
			}
			$product_pricing_values[$p_index]['base_cost_modifier'] = $value['base_modifier'];
			$product_pricing_values[$p_index]['base_cost']   = $value['base_cost'];
			$product_pricing_values[$p_index]['cost_modifier'] = $value['modifier'];
			$product_pricing_values[$p_index]['cost']   = $value['cost'];
		}
	}

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

<h3 class="pro_ele_head products_manage_availability booking"><?php _e('Availability Options', 'wcmp-frontend_product_manager'); ?></h3>
<div class="pro_ele_block booking">
	<?php
	

	$availability_arr1 =	array( 
				
				"_wc_booking_qty" => array('label' => __('Max bookings per block', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele booking', 'label_class' => 'pro_title booking', 'value' => $booking_qty, 'hints' => __( 'The maximum bookings allowed for each block. Can be overridden at resource level.', 'wcmp-frontend_product_manager' ), 'attributes' => array( 'min' => 0, 'step' => '1' ) ),
				"_wc_booking_min_date" => array('label' => __('Minimum block bookable', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele booking', 'label_class' => 'pro_title booking', 'value' => $min_date,'attributes' => array( 'min' => 0, 'step' => '1' ) ),
				"_wc_booking_min_date_unit" => array('label' => __('Minimum block bookable type', 'wcmp-frontend_product_manager') , 'type' => 'select', 'options' => array( 'month' => __( 'Month(s)', 'wcmp-frontend_product_manager'), 'day' => __( 'Day(s)', 'wcmp-frontend_product_manager' ), 'hour' => __( 'Hour(s)', 'wcmp-frontend_product_manager' ), 'minute' => __( 'Minute(s)', 'wcmp-frontend_product_manager' ) ), 'class' => 'regular-select pro_ele booking', 'label_class' => 'nolabel', 'value' => $min_date_unit ),
				"_wc_booking_max_date" => array('label' => __('Maximum block bookable', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele booking', 'label_class' => 'pro_title booking ', 'attributes' => array( 'min' => 0, 'step' => '1' ),'value' => $max_date ),
				"_wc_booking_max_date_unit" => array('label' => __('Maximum block bookable type', 'wcmp-frontend_product_manager') , 'type' => 'select', 'options' => array( 'month' => __( 'Month(s)', 'wcmp-frontend_product_manager'), 'day' => __( 'Day(s)', 'wcmp-frontend_product_manager' ), 'hour' => __( 'Hour(s)', 'wcmp-frontend_product_manager' ), 'minute' => __( 'Minute(s)', 'wcmp-frontend_product_manager' ) ), 'class' => 'regular-select pro_ele booking', 'label_class' => 'pro_title booking nolabel', 'value' => $max_date_unit ),
				"_wc_booking_buffer_period" => array('label' => __('Require a buffer period of', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele booking', 'label_class' => 'pro_title booking', 'attributes' => array( 'min' => 0, 'step' => '1' ),'value' => $buffer_period, 'desc' => '<span class="_wc_booking_buffer_period_unit"></span>' . __( 'between bookings', 'wcmp-frontend_product_manager' ) ),
				"_wc_booking_apply_adjacent_buffer" => array('label' => __('Adjacent Buffering?', 'wcmp-frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele booking', 'label_class' => 'pro_title booking', 'value' => 'yes', 'dfvalue' => $apply_adjacent_buffer, 'hints' => __( 'By default buffer period applies forward into the future of a booking. Enabling this option will apply adjacently ( Before and After Bookings).', 'wcmp-frontend_product_manager' ) ),
				"_wc_booking_default_date_availability" => array('label' => __('All dates are...', 'wcmp-frontend_product_manager') , 'type' => 'select', 'options' => array( 'available' => __( 'available by default', 'wcmp-frontend_product_manager'), 'non-available' => __( 'not-available by default', 'wcmp-frontend_product_manager' ) ), 'class' => 'regular-select pro_ele booking', 'label_class' => 'pro_title booking', 'value' => $default_date_availability, 'hints' => __( 'This option affects how you use the rules below.', 'wcmp-frontend_product_manager' ) ),
				"_wc_booking_check_availability_against" => array('label' => __('Check rules against...', 'wcmp-frontend_product_manager') , 'type' => 'select', 'options' => array( '' => __( 'All blocks being booked', 'wcmp-frontend_product_manager'), 'start' => __( 'The starting block only', 'wcmp-frontend_product_manager' ) ), 'class' => 'regular-select pro_ele booking', 'label_class' => 'pro_title booking', 'value' => $check_availability_against, 'hints' => __( 'This option affects how bookings are checked for availability.', 'wcmp-frontend_product_manager' ) ),
				/*"_wc_booking_first_block_time" => array('label' => __('First block starts at...', 'wcmp-frontend_product_manager') , 'type' => 'time', 'class' => 'regular-text pro_ele booking', 'label_class' => 'pro_title booking', 'placeholder' => "HH:MM", 'value' => $first_block_time ),*/

			);	
			$availability_arr3 =	array("_wc_booking_availability_rules" =>  array('label' => __('Rules', 'wcmp-frontend_product_manager') , 'type' => 'multiinput', 'class' => 'regular-text pro_ele booking', 'label_class' => 'pro_title booking', 'desc' => __( 'Rules with lower priority numbers will override rules with a higher priority (e.g. 9 overrides 10 ). Ordering is only applied within the same priority and higher order overrides lower order.', 'wcmp-frontend_product_manager' ), 'desc_class' => 'avail_rules_desc', 'value' => $availability_rule_values, 'options' => array(
									"type" => array('label' => __('Type', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $range_types, 'class' => 'regular-select pro_ele avail_range_type booking', 'label_class' => 'pro_title avail_rules_ele avail_rules_label booking' ),
									"from_custom" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'custom_attributes' => array( 'date_format' => 'yy-mm-dd'), 'wrapper_class' => 'avail_rule_custom avail_rule_field','class' => 'regular-text dc_datepicker avail_rule_field avail_rule_custom avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_custom avail_rules_ele avail_rules_label' ),
									"to_custom" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'custom_attributes' => array( 'date_format' => 'yy-mm-dd'), 'wrapper_class' => 'avail_rule_custom avail_rule_field','class' => 'regular-text dc_datepicker avail_rule_field avail_rule_custom avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_custom avail_rules_ele avail_rules_label' ),
									"from_months" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['months'], 'class' => 'regular-select avail_rule_field avail_rule_months avail_rules_ele avail_rules_text', 'wrapper_class' => 'avail_rule_months avail_rule_field','label_class' => 'pro_title avail_rule_field avail_rule_months avail_rules_ele avail_rules_label' ),
									"to_months" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['months'], 'class' => 'regular-select avail_rule_field avail_rule_months avail_rules_ele avail_rules_text', 'wrapper_class' => 'avail_rule_months avail_rule_field','label_class' => 'pro_title avail_rule_field avail_rule_months avail_rules_ele avail_rules_label' ),
									"from_weeks" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['weeks'], 'class' => 'regular-select avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_text', 'wrapper_class' => 'avail_rule_weeks avail_rule_field','label_class' => 'pro_title avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_label' ),
									"to_weeks" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['weeks'], 'class' => 'regular-select avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_text', 'wrapper_class' => 'avail_rule_weeks avail_rule_field','label_class' => 'pro_title avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_label' ),
									"from_days" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['days'], 'class' => 'regular-select avail_rule_field avail_rule_days avail_rules_ele avail_rules_text','wrapper_class' => 'avail_rule_days avail_rule_field', 'label_class' => 'pro_title avail_rule_field avail_rule_days avail_rules_ele avail_rules_label' ),
									"to_days" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['days'], 'class' => 'regular-select avail_rule_field avail_rule_days avail_rules_ele avail_rules_text','wrapper_class' => 'avail_rule_days avail_rule_field', 'label_class' => 'pro_title avail_rule_field avail_rule_days avail_rules_ele avail_rules_label' ),
									"from_time" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'time', 'placeholder' => 'HH:MM', 'class' => 'regular-text avail_rule_field avail_rule_time avail_rules_ele avail_rules_text','wrapper_class' => 'avail_rule_time avail_rule_field', 'label_class' => 'pro_title avail_rule_field avail_rule_time avail_rules_ele avail_rules_label' ),
									"to_time" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'time', 'placeholder' => 'HH:MM', 'class' => 'regular-text avail_rule_field avail_rule_time avail_rules_ele avail_rules_text', 'wrapper_class' => 'avail_rule_time avail_rule_field','label_class' => 'pro_title avail_rule_field avail_rule_time avail_rules_ele avail_rules_label' ),
									"bookable" => array('label' => __('Bookable', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => array( 'no' => 'NO', 'yes' => 'YES' ), 'class' => 'regular-select pro_ele avail_rules_ele avail_rules_text booking', 'label_class' => 'pro_title avail_rules_ele avail_rules_label', 'hints' => __( 'If not bookable, users won\'t be able to choose this block for their booking.', 'wcmp-frontend_product_manager' ) ),
									"priority" => array('label' => __('Priority', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text pro_ele avail_rules_ele avail_rule_priority avail_rules_text booking', 'label_class' => 'pro_title avail_rules_ele avail_rules_label booking', 'hints' => esc_attr( get_wc_booking_priority_explanation() ) ),
									)	)																					
				) ;
	$availability_arr2 = array();
	if(version_compare( $booking_plugin_data['Version'], '1.10.7', ">=" )) {
		
		$availability_arr2 = array(
			"_wc_booking_has_restricted_days" => array('label' => __('Restrict start days?', 'wcmp-frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele booking', 'label_class' => 'pro_title booking', 'value' => 1, 'dfvalue' => $has_restricted_days),
				"booking-sunday-restriction" => array('label' => __('Sunday', 'wcmp-frontend_product_manager') , 'type' => 'checkbox','name'=>'_wc_booking_restricted_days[0]', 'wrapper_class'=>'restricted_days_dependency pro_ele_hide','class' => 'regular-checkbox pro_ele booking', 'label_class' => 'pro_title booking', 'value' => 1, 'dfvalue' => ( in_array(0, $restricted_days) )? 1:'' ),
				"booking-monday-restriction" => array('label' => __('Monday', 'wcmp-frontend_product_manager') , 'type' => 'checkbox','name'=>'_wc_booking_restricted_days[1]', 'wrapper_class'=>'restricted_days_dependency pro_ele_hide','class' => 'regular-checkbox pro_ele booking', 'label_class' => 'pro_title booking', 'value' => 1, 'dfvalue' => ( in_array(1, $restricted_days) )? 1:'' ),
				"booking-tuesday-restriction" => array('label' => __('Tuesday', 'wcmp-frontend_product_manager') , 'type' => 'checkbox','name'=>'_wc_booking_restricted_days[2]', 'wrapper_class'=>'restricted_days_dependency pro_ele_hide','class' => 'regular-checkbox pro_ele booking', 'label_class' => 'pro_title booking', 'value' => 1, 'dfvalue' => ( in_array(2, $restricted_days) )? 1:'' ),
				"booking-wednesday-restriction" => array('label' => __('Wednesday ', 'wcmp-frontend_product_manager') , 'type' => 'checkbox','name'=>'_wc_booking_restricted_days[3]', 'wrapper_class'=>'restricted_days_dependency pro_ele_hide','class' => 'regular-checkbox pro_ele booking', 'label_class' => 'pro_title booking', 'value' => 1, 'dfvalue' => ( in_array(3, $restricted_days) )? 1:'' ),
				"booking-thrusday-restriction" => array('label' => __('Thursday', 'wcmp-frontend_product_manager') , 'type' => 'checkbox','name'=>'_wc_booking_restricted_days[4]', 'wrapper_class'=>'restricted_days_dependency pro_ele_hide','class' => 'regular-checkbox pro_ele booking', 'label_class' => 'pro_title booking', 'value' => 1, 'dfvalue' => ( in_array(4, $restricted_days) )? 1:'' ),
				"booking-friday-restriction" => array('label' => __('Friday', 'wcmp-frontend_product_manager') , 'type' => 'checkbox','name'=>'_wc_booking_restricted_days[5]', 'wrapper_class'=>'restricted_days_dependency pro_ele_hide','class' => 'regular-checkbox pro_ele booking', 'label_class' => 'pro_title booking', 'value' => 1, 'dfvalue' => ( in_array(5, $restricted_days) )? 1:'' ),
				"booking-saturday-restriction" => array('label' => __('Saturday', 'wcmp-frontend_product_manager') , 'type' => 'checkbox','name'=>'_wc_booking_restricted_days[6]', 'wrapper_class'=>'restricted_days_dependency  pro_ele_hide','class' => 'regular-checkbox pro_ele booking', 'label_class' => 'pro_title booking', 'value' => 1, 'dfvalue' => ( in_array(6, $restricted_days) )? 1:'' ),
		);
	}
	
	$WCMp_Frontend_Product_Manager->wcmp_wp_fields->wcmp_generate_form_field(array_merge($availability_arr1,$availability_arr2,$availability_arr3));
	?>
</div>

<h3 class="pro_ele_head products_manage_costs booking"><?php _e('Costs', 'wcmp-frontend_product_manager'); ?></h3>
<div class="pro_ele_block booking">
	<?php
	$WCMp_Frontend_Product_Manager->wcmp_wp_fields->wcmp_generate_form_field( apply_filters( 'wcmp_fpm_booking_cost_renge_fields',array(  
				
				"_wc_booking_cost" => array('label' => __('Base cost', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele booking', 'label_class' => 'pro_title booking', 'value' => $booking_cost, 'hints' => __( 'One-off cost for the booking as a whole.', 'wcmp-frontend_product_manager' ), 'attributes' => array( 'min' => '', 'step' => '0.01' ) ),
				"_wc_booking_base_cost" => array('label' => __('Block cost', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele booking', 'label_class' => 'pro_title booking', 'value' => $booking_base_cost, 'hints' => __( 'This is the cost per block booked. All other costs (for resources and persons) are added to this.', 'wcmp-frontend_product_manager' ), 'attributes' => array( 'min' => '', 'step' => '0.01' ) ),
				"_wc_display_cost" => array('label' => __('Display cost', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele booking', 'label_class' => 'pro_title booking', 'value' => $display_cost, 'hints' => __( 'The cost is displayed to the user on the frontend. Leave blank to have it calculated for you. If a booking has varying costs, this will be prefixed with the word `from:`.', 'wcmp-frontend_product_manager' ), 'attributes' => array( 'min' => '', 'step' => '0.01' ) ),
				"_wc_booking_cost_range_types" => array('label' => __('Pricing Range', 'wcmp-frontend_product_manager') , 'type' => 'multiinput', 'class' => 'regular-text pro_ele booking', 'label_class' => 'pro_title', 'value' => $product_pricing_values, 'options' => apply_filters( 'wcmp_fpm_cost_each range_fields_options', array(
						"type" => array('label' => __('Type', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $option_types, 'class' => 'regular-select pro_ele wc_booking_pricing_type booking', 'label_class' => 'pro_title booking' ),																	
						"from_date" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'custom_attributes' => array( 'date_format' => 'yy-mm-dd'), 'wrapper_class' => 'wc_booking_pricing_custom wc_booking_pricing_field','class' => 'regular-text dc_datepicker wc_booking_pricing_custom wc_booking_pricing_field', 'label_class' => 'pro_title  wc_booking_pricing_custom  wc_booking_pricing_field' ),
						"to_date" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'custom_attributes' => array( 'date_format' => 'yy-mm-dd'), 'wrapper_class' => 'wc_booking_pricing_custom wc_booking_pricing_field','class' => 'regular-text dc_datepicker  wc_booking_pricing_custom wc_booking_pricing_field', 'label_class' => 'pro_title  wc_booking_pricing_custom wc_booking_pricing_field' ),
						"from_month" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['months'], 'class' => 'regular-select ', 'wrapper_class' => 'wc_booking_pricing_months wc_booking_pricing_field','label_class' => 'pro_title  wc_booking_pricing_months wc_booking_pricing_field' ),
						"to_month" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['months'], 'class' => 'regular-select ', 'wrapper_class' => 'wc_booking_pricing_months wc_booking_pricing_field','label_class' => 'pro_title  wc_booking_pricing_months wc_booking_pricing_field' ),
						"from_week" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['weeks'], 'class' => 'regular-select ', 'wrapper_class' => 'wc_booking_pricing_weeks wc_booking_pricing_field','label_class' => 'pro_title  wc_booking_pricing_weeks  wc_booking_pricing_field' ),
						"to_week" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['weeks'], 'class' => 'regular-select ', 'wrapper_class' => 'wc_booking_pricing_weeks wc_booking_pricing_field','label_class' => 'pro_title wc_booking_pricing_weeks wc_booking_pricing_field ' ),
						"from_day_of_week" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['days'], 'class' => 'regular-select ','wrapper_class' => 'wc_booking_pricing_days wc_booking_pricing_field', 'label_class' => 'pro_title  wc_booking_pricing_days wc_booking_pricing_field ' ),
						"to_day_of_week" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $intervals['days'], 'class' => 'regular-select ','wrapper_class' => 'wc_booking_pricing_days wc_booking_pricing_field', 'label_class' => 'pro_title  wc_booking_pricing_days  wc_booking_pricing_field' ),
						"from_time" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'time', 'placeholder' => 'HH:MM', 'class' => 'regular-text ','wrapper_class' => 'wc_booking_pricing_time wc_booking_pricing_field', 'label_class' => 'pro_title  wc_booking_pricing_time  wc_booking_pricing_field' ),
						"to_time" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'time', 'placeholder' => 'HH:MM', 'class' => 'regular-text    ', 'wrapper_class' => 'wc_booking_pricing_time wc_booking_pricing_field','label_class' => 'pro_title  wc_booking_pricing_time  wc_booking_pricing_field' ),
						"from" => array('label' => __('From ', 'wcmp-frontend_product_manager'), 'type' => 'number',  'class' => 'regular-text ','wrapper_class' => 'wc_booking_pricing_persons wc_booking_pricing_blocks wc_booking_pricing_field', 'label_class' => 'pro_title  wc_booking_pricing_blocks wc_booking_pricing_persons wc_booking_pricing_field ' ),
						"to" => array('label' => __('To ', 'wcmp-frontend_product_manager'), 'type' => 'number',  'class' => 'regular-text ','wrapper_class' => 'wc_booking_pricing_persons wc_booking_pricing_blocks wc_booking_pricing_field', 'label_class' => 'pro_title  wc_booking_pricing_persons  wc_booking_pricing_blocks wc_booking_pricing_field' ),
						"base_cost_modifier" => array('label' => __('Base cost', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $modifier_types, 'class' => 'regular-select pro_ele booking', 'label_class' => 'pro_title booking' ),
						"base_cost" => array('label' => __('Base cost', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele booking', 'label_class' => 'pro_title booking', 'value' => $booking_cost, 'attributes' => array( 'min' => '', 'step' => '0.01' ) ),
						"cost_modifier" => array('label' => __('Block cost', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $modifier_types, 'class' => 'regular-select pro_ele booking', 'label_class' => 'pro_title booking' ),
						"cost" => array('label' => __('Block cost', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele booking', 'label_class' => 'pro_title booking', 'value' => $booking_cost, 'attributes' => array( 'min' => '', 'step' => '0.01' ) )
																								)	)	)
																									
																													)) );
	?>
</div>

<h3 class="pro_ele_head products_manage_persons persons booking"><?php _e('Persons', 'wcmp-frontend_product_manager'); ?></h3>
<div class="pro_ele_block persons booking">
	<?php
	$WCMp_Frontend_Product_Manager->wcmp_wp_fields->wcmp_generate_form_field( apply_filters( 'wcmp_fpm_persons_fields',array(  
				
				"_wc_booking_min_persons_group" => array('label' => __('Min persons', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele booking', 'label_class' => 'pro_title booking', 'value' => $min_persons_group, 'hints' => __( 'The minimum number of persons per booking.', 'wcmp-frontend_product_manager' ), 'attributes' => array( 'min' => '1', 'step' => '1' ) ),
				"_wc_booking_max_persons_group" => array('label' => __('Max persons', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele booking', 'label_class' => 'pro_title booking', 'value' => $max_persons_group, 'hints' => __( 'The maximum number of persons per booking.', 'wcmp-frontend_product_manager' ), 'attributes' => array( 'min' => '1', 'step' => '1' ) ),
				"_wc_booking_person_cost_multiplier" => array('label' => __('Multiply all costs by person count', 'wcmp-frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele booking', 'label_class' => 'pro_title booking', 'value' => 'yes', 'dfvalue' => $person_cost_multiplier, 'hints' => __( 'Enable this to multiply the entire cost of the booking (block and base costs) by the person count.', 'wcmp-frontend_product_manager' ) ),
				"_wc_booking_person_qty_multiplier" => array('label' => __('Count persons as bookings', 'wcmp-frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele booking', 'label_class' => 'pro_title booking', 'value' => 'yes', 'dfvalue' => $person_qty_multiplier, 'hints' => __( 'Enable this to count each person as a booking until the max bookings per block (in availability) is reached.', 'wcmp-frontend_product_manager' ) ),
				"_wc_booking_has_person_types" => array('label' => __('Enable person types', 'wcmp-frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele booking', 'label_class' => 'pro_title booking', 'value' => 'yes', 'dfvalue' => $has_person_types, 'hints' => __( 'Person types allow you to offer different booking costs for different types of individuals, for example, adults and children.', 'wcmp-frontend_product_manager' ) ),
				"_wc_booking_person_types" =>     array('label' => __('Person Types', 'wcmp-frontend_product_manager') , 'type' => 'multiinput', 'class' => 'regular-text pro_ele person_types', 'label_class' => 'pro_title person_types', 'value' => $person_types, 'options' => apply_filters( 'wcmp_fpm_persons_fields_options', array(
																								"person_name" => array('label' => __('Type Name', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text person_types_text', 'label_class' => 'pro_title person_types_label' ),
																								"person_cost" => array('label' => __('Base Cost', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text person_types_text', 'label_class' => 'pro_title person_types_label' ),
																								"person_block_cost" => array('label' => __('Block Cost', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text person_types_text', 'label_class' => 'pro_title person_types_label' ),
																								
																								"person_description" => array('label' => __('Description', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text person_types_text', 'label_class' => 'pro_title person_types_label' ),
																								"person_min" => array('label' => __('Min', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text person_types_text', 'label_class' => 'pro_title person_types_label' ),
																								"person_max" => array('label' => __('Max', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text person_types_text', 'label_class' => 'pro_title person_types_label' ),
																								"person_id" => array('type' => 'hidden', 'class' => 'person_id' )
																								)	)	)																		
																										), $product_id ));
	?>
</div>

<h3 class="pro_ele_head products_manage_resources resources booking"><?php _e('Resources', 'wcmp-frontend_product_manager'); ?></h3>
<div class="pro_ele_block resources booking">
	<?php
	$WCMp_Frontend_Product_Manager->wcmp_wp_fields->wcmp_generate_form_field( apply_filters( 'wcmp_fpm_resource_fields',array(  
				
				"_wc_booking_resource_label" => array( 'label' => __('Label', 'wcmp-frontend_product_manager'), 'placeholder' => __('Type', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text pro_ele booking', 'label_class' => 'pro_title booking', 'value' => $resource_label, 'hints' => __( 'The label shown on the frontend if the resource is customer defined.', 'wcmp-frontend_product_manager' ) ),
				"_wc_booking_resources_assignment" => array( 'label' => __('Resources are...', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => array( 'customer' => __( 'Customer selected', 'wcmp-frontend_product_manager'), 'automatic' => __( 'Automatically assigned', 'wcmp-frontend_product_manager' ) ), 'class' => 'regular-select pro_ele booking', 'label_class' => 'pro_title booking', 'value' => $resources_assignment, 'hints' => __( 'Customer selected resources allow customers to choose one from the booking form.', 'wcmp-frontend_product_manager' ) ),
				"_wc_booking_all_resources" => array( 'label' => __('Available for Resources', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $all_resources, 'class' => 'regular-select pro_ele booking', 'label_class' => 'pro_title booking', 'hints' => __( 'Resources are used if you have multiple bookable items, e.g. room types, instructors or ticket types. Availability for resources is global across all bookable products. Choose to associate with your product.', 'wcmp-frontend_product_manager' ) ),
				"_wc_booking_resources" =>     array('label' => __('Resources', 'wcmp-frontend_product_manager') , 'type' => 'multiinput', 'class' => 'regular-text pro_ele resource_types booking', 'label_class' => 'pro_title resource_types booking', 'value' => $resources, 'options' => apply_filters( 'wcmp_booking_resource_fields',array(
																								"resource_title" => array('label' => __('Title', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text pro_ele resource_types_text booking', 'label_class' => 'pro_title resource_types_label booking' ),
																								"resource_base_cost" => array('label' => __('Base Cost', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text pro_ele resource_types_text booking', 'label_class' => 'pro_title resource_types_label booking' ),
																								"resource_block_cost" => array('label' => __('Block Cost', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text pro_ele resource_types_text booking', 'label_class' => 'pro_title resource_types_label booking' ),
																								"resource_id" => array('type' => 'hidden', 'class' => 'resource_id' )
																								)) )	), $product_id) );
	?>
</div>