<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! $order = wc_get_order( $order_id ) ) {
	return;
}

$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads             = $order->get_downloadable_items();
$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();

// TODO: important

if ( $show_downloads ) {
	wc_get_template( 'order/order-downloads.php', array( 'downloads' => $downloads, 'show_title' => true ) );
}
?>
<section class="woocommerce-order-details">
	<h2 class="woocommerce-order-details__title"><?php _e( 'Order details', 'woocommerce' ); ?></h2>

	<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">

		<thead>
			<tr>
				<th class="woocommerce-table__product-name product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
				<th class="woocommerce-table__product-table product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php
				foreach ( $order_items as $item_id => $item ) {
					$product = apply_filters( 'woocommerce_order_item_product', $item->get_product(), $item );

					wc_get_template( 'order/order-details-item.php', array(
						'order'			     => $order,
						'item_id'		     => $item_id,
						'item'			     => $item,
						'show_purchase_note' => $show_purchase_note,
						'purchase_note'	     => $product ? $product->get_purchase_note() : '',
						'product'	         => $product,
					) );
				}
			?>
			<?php do_action( 'woocommerce_order_items_table', $order ); ?>
		</tbody>

		<tfoot>
			<?php
				foreach ( $order->get_order_item_totals() as $key => $total ) {
					?>
					<tr>
						<th scope="row"><?php echo $total['label']; ?></th>
						<td><?php echo $total['value']; ?></td>
					</tr>
					<?php
				}
			?>
			<?php if ( $order->get_customer_note() ) : ?>
				<tr>
					<th><?php _e( 'Note:', 'woocommerce' ); ?></th>
					<td><?php echo wptexturize( $order->get_customer_note() ); ?></td>
				</tr>
			<?php endif; ?>
		</tfoot>
	</table>

	<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>

	<?php

	//if(count($amz_cart_items)){
	//	echo'<script>
	// document.addEventListener("DOMContentLoaded", function(_e) {
	//		var _cd = document.getElementById("amazon-count-down");
	//		var _start = (new Date()).getTime();
	//		var _from = 13;
	//		var _last = _from+0;
	//		function _tick(){
	//			var _cdl = document.getElementById("amazon_checkout_redirect");
	//			if(!_cdl){return;}
	//			var _now = Math.floor(_from - (((new Date()).getTime() - _start)/1000));
	//				if(_now < _last){
	//					_cd.innerHTML = _now;
	//					_last = _now;
	//					if(_now <= 0){
	//						_cdl.click();
	//						return;
	//					}
	//				}
	//				setTimeout(()=>{_tick();}, 1000/30);
	//		};
	//		_tick();
	// });
	//</script>';
	//}
	?>

	<?php
	if ( $show_customer_details ) {
		wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
	}
	?>

	<?php
        if(count($_SESSION['AMZ_PRODUCT_KEYS'])) {
	        do_action('theme_set_had_amz_products_key_store', true);
	        echo "<h3>Products to be fulfilled by Amazon</h3>";
//	        do_action('woocommerce_amazon_checkout_cart_review');
	        do_action( 'woocommerce_non_amazon_checkout_cart_review' );
            echo '<div id="amazon-redirect-block" style="font-size:1.2em;display:block; width:100%;">'.
				'<a href="#" id="amazon_checkout_redirect" onclick="amazon_checkout_redirect()">Continue to Amazon Checkout</a>'.
				'</div>';


			}
	?>
    
</section>
