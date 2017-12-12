<?php
/**
 * Vendor Out of stock Email
 *
 * Override this template by copying it to yourtheme/wcmp-vendor_stock_alert/emails/plain/vendor_out_of_stock_alert_email.php
 *
 * @author 		WC Marketplace
 * @package 	wcmp-vendor_stock_alert/Templates
 * @version     1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCMp_Vendor_Stock_Alert;

echo $email_heading . "\n\n";

echo sprintf( __( "Hi there. A Product is out of stock. Product details are shown below for your reference:", 'wcmp-vendor_stock_alert' ) ) . "\n\n";

echo "\n****************************************************\n\n";

$product_obj = wc_get_product( $product_id );

if( $product_obj->is_type('variation') ) {
	$wp_obj = new WC_Product( $product_id );
	$parent_id = $wp_obj->get_parent();
	$parent_obj = new WC_Product( $parent_id );
	$product_link = admin_url( 'post.php?post=' . $parent_id . '&action=edit' );
	$product_name = $wp_obj->post->post_title;
	$product_stock = get_post_meta( $product_id, '_stock', true );
} else {
	$wp_obj = new WC_Product( $product_id );
	$product_link = admin_url( 'post.php?post=' . $product_id . '&action=edit' );
	$product_name = $wp_obj->get_formatted_name();
	$product_stock = get_post_meta( $product_id, '_stock', true );
}

echo "\n Product Name : ".$product_name;

echo "\n\n Product available stock : ".$product_stock;

echo "\n\n Product link : ".$product_link;

echo "\n\n\n****************************************************\n\n";

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
