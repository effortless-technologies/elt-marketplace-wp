<?php
/**
 * WCMp Product Types plugin views
 *
 * Plugin YITH Auction Products Manage Views
 *
 * @author 		WC Marketplace
 * @package 	wcmp-pts/views
 * @version   1.0.3
 */
global $wp, $WCMp_Frontend_Product_Manager;

$product_id = 0;
$_yith_auction_start_price = '';
$_yith_auction_bid_increment = '';
$_yith_auction_minimum_increment_amount = '';
$_yith_auction_reserve_price = '';
$_yith_auction_buy_now = '';
$_yith_auction_for = '';
$_yith_auction_to = '';
$_yith_check_time_for_overtime_option = '';
$_yith_overtime_option = '';
$_yith_wcact_auction_automatic_reschedule = '';
$_yith_wcact_automatic_reschedule_auction_unit = '';
$_yith_wcact_upbid_checkbox = '';
$_yith_wcact_overtime_checkbox = '';

if (!empty($pro_id)) {
	$product = wc_get_product((int) $pro_id);
	if($product && !empty($product)) {
		$product_id = $product->get_id();
	}
}


if( $product_id ) {
	$_yith_auction_start_price = get_post_meta( $product_id, '_yith_auction_start_price', true );
	$_yith_auction_bid_increment = get_post_meta( $product_id, '_yith_auction_bid_increment', true );
	$_yith_auction_minimum_increment_amount = get_post_meta( $product_id, '_yith_auction_minimum_increment_amount', true );
	$_yith_auction_reserve_price = get_post_meta( $product_id, '_yith_auction_reserve_price', true );
	$_yith_auction_buy_now = get_post_meta( $product_id, '_yith_auction_buy_now', true );
	$_yith_auction_for = get_post_meta( $product_id, '_yith_auction_for', true );
	$_yith_auction_to = get_post_meta( $product_id, '_yith_auction_to', true );
	$_yith_check_time_for_overtime_option = get_post_meta( $product_id, '_yith_check_time_for_overtime_option', true );
	$_yith_overtime_option = get_post_meta( $product_id, '_yith_overtime_option', true );
	$_yith_wcact_auction_automatic_reschedule = get_post_meta( $product_id, '_yith_wcact_auction_automatic_reschedule', true );
	$_yith_wcact_automatic_reschedule_auction_unit = get_post_meta( $product_id, '_yith_wcact_automatic_reschedule_auction_unit', true );
	$_yith_wcact_upbid_checkbox = get_post_meta( $product_id, '_yith_wcact_upbid_checkbox', true );
	$_yith_wcact_overtime_checkbox = get_post_meta( $product_id, '_yith_wcact_overtime_checkbox', true );
	
	if( $_yith_auction_for ) $_yith_auction_for = date( 'Y-m-d h:i:s', $_yith_auction_for);
	if( $_yith_auction_to ) $_yith_auction_to = date( 'Y-m-d h:i:s', $_yith_auction_to);
	
}


?>

<h3 class="pro_ele_head products_manage_yith_auction auction"><?php _e('Auction', 'wcmp-frontend_product_manager'); ?></h3>
<div class="pro_ele_block auction">
	<?php
	$WCMp_Frontend_Product_Manager->wcmp_wp_fields->wcmp_generate_form_field( array( 
		"_yith_auction_start_price" => array( 'label' => __('Starting Price', 'wcmp-frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')' , 'type' => 'text', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_yith_auction_start_price ),
		"_yith_auction_bid_increment" => array( 'label' => __('Bid up', 'wcmp-frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')' , 'type' => 'text', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_yith_auction_bid_increment ),
		"_yith_auction_minimum_increment_amount" => array( 'label' => __('Minimum increment amount', 'wcmp-frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')' , 'type' => 'text', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_yith_auction_minimum_increment_amount ),
		"_yith_auction_reserve_price" => array( 'label' => __('Reserve price', 'wcmp-frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')' , 'type' => 'text', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_yith_auction_reserve_price ),
		"_yith_auction_buy_now" => array( 'label' => __('Buy it now price', 'wcmp-frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')' , 'type' => 'text', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_yith_auction_buy_now ),
		"_yith_auction_for" => array( 'label' => __('Auction Date From', 'wcmp-frontend_product_manager') , 'type' => 'text', 'placeholder' => 'YYYY-MM-DD hh:mm:ss', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_yith_auction_for ),
		"_yith_auction_to" => array( 'label' => __('Auction Date To', 'wcmp-frontend_product_manager') , 'type' => 'text', 'placeholder' => 'YYYY-MM-DD hh:mm:ss', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_yith_auction_to ),
		"_yith_check_time_for_overtime_option" => array( 'label' => __('Time to add overtime', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_yith_check_time_for_overtime_option, 'hints' => __( 'Number of minutes before auction ends to check if overtime added. (Override the settings option)', 'wcmp-frontend_product_manager' ) ),
		"_yith_overtime_option" => array( 'label' => __('Overtime', 'wcmp-frontend_product_manager') , 'type' => 'text', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_yith_overtime_option, 'hints' => __( 'Number of minutes by which the auction will be extended. (Overrride the settings option)', 'wcmp-frontend_product_manager' ) ),
		"_yith_wcact_auction_automatic_reschedule" => array( 'label' => __('Time value for automatic rescheduling', 'wcmp-frontend_product_manager') , 'type' => 'text', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_yith_wcact_auction_automatic_reschedule ),
		"_yith_wcact_automatic_reschedule_auction_unit" => array( 'label' => __('Select unit for automatic rescheduling', 'wcmp-frontend_product_manager') , 'type' => 'select', 'class' => 'regular-select pro_ele auction', 'label_class' => 'pro_title auction', 'options' => array( 'days' => __( 'days', 'wcmp-frontend_product_manager' ), 'hours' => __( 'hours', 'wcmp-frontend_product_manager' ), 'minutes' => __( 'minutes', 'wcmp-frontend_product_manager' ) ), 'value' => $_yith_wcact_automatic_reschedule_auction_unit ),
		"_yith_wcact_upbid_checkbox" => array( 'label' => __('Show bid up', 'wcmp-frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele auction', 'label_class' => 'pro_title auction', 'value' => 'yes', 'dfvalue' => $_yith_wcact_upbid_checkbox ),
		"_yith_wcact_overtime_checkbox" => array( 'label' => __('Show overtime', 'wcmp-frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele auction', 'label_class' => 'pro_title auction', 'value' => 'yes', 'dfvalue' => $_yith_wcact_overtime_checkbox ),
		
		) );
	?>
</div>