<?php
/**
 * Vendor low stock Email
 *
 * Override this template by copying it to yourtheme/wcmp-vendor_stock_alert/emails/vendor_low_stock_alert_email.php
 *
 * @author 		WC Marketplace
 * @package 	wcmp-vendor_stock_alert/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCMp_Vendor_Stock_Alert;

do_action( 'woocommerce_email_header', $email_heading ); ?>

<p><?php printf( __( "Hi there. A Product is low in stock. Product details are shown below for your reference:", 'wcmp-vendor_stock_alert' ) );

$product_obj = wc_get_product( $product_id );

if( $product_obj->is_type('variation') ) {
	$wp_obj = new WC_Product( $product_id );
	$parent_id = $wp_obj->get_parent_id();
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

?>
<h3>Product Details</h3>
<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'wcmp-vendor_stock_alert' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Available stock', 'wcmp-vendor_stock_alert' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( $product_name, 'wcmp-vendor_stock_alert' ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( $product_stock, 'wcmp-vendor_stock_alert' ); ?></th>
		</tr>
	</tbody>
</table>

<p style="margin-top: 15px !important;"><?php printf( __( "Following is the product link : ", 'wcmp-vendor_stock_alert' ) ); ?><a href="<?php echo $product_link; ?>"><?php echo $product_name; ?></a></p>

</p>
<?php do_action( 'woocommerce_email_footer' ); ?>
