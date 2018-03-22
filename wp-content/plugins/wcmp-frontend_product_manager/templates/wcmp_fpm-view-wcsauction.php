<?php
/**
 * WCMp Product Types plugin views
 *
 * Plugin WC Simple Auction Products Manage Views
 *
 * @author 		WC Marketplace
 * @package 	wcmp-pts/views
 * @version   1.0.3
 */
global $wp, $WCMp_Frontend_Product_Manager;

$product_id = 0;
$_auction_item_condition = 'new';
$_auction_type = 'normal';
$_auction_proxy = '';
$_auction_start_price = '';
$_auction_bid_increment = '';
$_auction_reserved_price = '';
$_regular_price = '';
$_auction_dates_from = '';
$_auction_dates_to = '';

$_auction_automatic_relist = '';
$_auction_relist_fail_time = '';
$_auction_relist_not_paid_time = '';
$_auction_relist_duration = '';

if (!empty($pro_id)) {
	$product = wc_get_product((int) $pro_id);
	if($product && !empty($product)) {
		$product_id = $product->get_id();
	}
}


if( $product_id ) {
	$_auction_item_condition = get_post_meta( $product_id, '_auction_item_condition', true );
	$_auction_type = get_post_meta( $product_id, '_auction_type', true );
	$_auction_proxy = get_post_meta( $product_id, '_auction_proxy', true );
	$_auction_start_price = get_post_meta( $product_id, '_auction_start_price', true );
	$_auction_bid_increment = get_post_meta( $product_id, '_auction_bid_increment', true );
	$_auction_reserved_price = get_post_meta( $product_id, '_auction_reserved_price', true );
	$_regular_price = get_post_meta( $product_id, '_regular_price', true );
	$_auction_dates_from = get_post_meta( $product_id, '_auction_dates_from', true );
	$_auction_dates_to = get_post_meta( $product_id, '_auction_dates_to', true );
	
	$_auction_automatic_relist = get_post_meta( $product_id, '_auction_automatic_relist', true );
	$_auction_relist_fail_time = get_post_meta( $product_id, '_auction_relist_fail_time', true );
	$_auction_relist_not_paid_time = get_post_meta( $product_id, '_auction_relist_not_paid_time', true );
	$_auction_relist_duration = get_post_meta( $product_id, '_auction_relist_duration', true );
}


?>

<h3 class="pro_ele_head products_manage_wcs_auction auction"><?php _e('Auction', 'wcmp-frontend_product_manager'); ?></h3>
<div class="pro_ele_block auction">
	<?php
	$WCMp_Frontend_Product_Manager->wcmp_wp_fields->wcmp_generate_form_field( array( 
		"_auction_item_condition" => array( 'label' => __('Item condition', 'wcmp-frontend_product_manager') , 'type' => 'select', 'class' => 'regular-select pro_ele auction', 'label_class' => 'pro_title auction', 'options' => array( 'new' => __( 'New', 'wcmp-frontend_product_manager' ), 'used' => __( 'Used', 'wcmp-frontend_product_manager' ) ), 'value' => $_auction_item_condition ),
		"_auction_type" => array( 'label' => __('Auction type', 'wcmp-frontend_product_manager') , 'type' => 'select', 'class' => 'regular-select pro_ele auction', 'label_class' => 'pro_title auction', 'options' => array( 'normal' => __( 'Normal', 'wcmp-frontend_product_manager' ), 'reverse' => __( 'Reverse', 'wcmp-frontend_product_manager' ) ), 'value' => $_auction_type ),
		"_auction_proxy" => array( 'label' => __('Proxy bidding?', 'wcmp-frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele auction', 'label_class' => 'pro_title auction', 'value' => 'yes', 'dfvalue' => $_auction_proxy ),
		"_auction_start_price" => array( 'label' => __('Starting Price', 'wcmp-frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')' , 'type' => 'text', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_auction_start_price ),
		"_auction_bid_increment" => array( 'label' => __('Bid increment', 'wcmp-frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')' , 'type' => 'text', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_auction_bid_increment ),
		"_auction_reserved_price" => array( 'label' => __('Reserve price', 'wcmp-frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')' , 'type' => 'text', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_auction_reserved_price, 'hints' => __( 'A reserve price is the lowest price at which you are willing to sell your item. If you don\'t want to sell your item below a certain price, you can set a reserve price. The amount of your reserve price is not disclosed to your bidders, but they will see that your auction has a reserve price and whether or not the reserve has been met. If a bidder does not meet that price, you are not obligated to sell your item.', 'wcmp-frontend_product_manager' ) ),
		"_regular_price" => array( 'label' => __('Buy it now price', 'wcmp-frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')' , 'type' => 'text', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_regular_price, 'hints' => __( 'Buy it now disappears when bid exceeds the Buy now price for normal auction, or is lower than reverse auction', 'wcmp-frontend_product_manager' ) ),
		"_auction_dates_from" => array( 'label' => __('Auction Date From', 'wcmp-frontend_product_manager') , 'type' => 'text', 'placeholder' => 'YYYY-MM-DD hh:mm:ss', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_auction_dates_from ),
		"_auction_dates_to" => array( 'label' => __('Auction Date To', 'wcmp-frontend_product_manager') , 'type' => 'text', 'placeholder' => 'YYYY-MM-DD hh:mm:ss', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_auction_dates_to ),
		
		"_auction_automatic_relist" => array( 'label' => __('Automatic relist auction', 'wcmp-frontend_product_manager') , 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele auction', 'label_class' => 'pro_title auction', 'value' => 'yes', 'dfvalue' => $_auction_automatic_relist ),
		"_auction_relist_fail_time" => array( 'label' => __('Relist if fail after n hours', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_auction_relist_fail_time ),
		"_auction_relist_not_paid_time" => array( 'label' => __('Relist if not paid after n hours', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_auction_relist_not_paid_time ),
		"_auction_relist_duration" => array( 'label' => __('Relist auction duration in h', 'wcmp-frontend_product_manager') , 'type' => 'number', 'class' => 'regular-text pro_ele auction', 'label_class' => 'pro_title auction', 'value' => $_auction_relist_duration ),
		) );
	?>
</div>