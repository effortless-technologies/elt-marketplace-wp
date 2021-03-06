<?php
/**
 * WooZone checkout template
 *
 * Functions for the amazon checkout system.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<table class="shop_table woocommerce-amazon-checkout-review-order-table">
	<thead>
	<tr>
		<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
		<th class="product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	do_action( 'woocommerce_review_order_before_cart_contents' );

	$cart = WC()->cart->get_cart();
	do_action('woozone_woo_cart_amazon_parse_cart_items', $cart);
	$amz_cart_items = '';
	$amz_cart_items = apply_filters( 'woozone_woo_cart_amazon_get_products', $amz_cart_items);
	$cart_price = apply_filters('woozone_woo_cart_get_cart_price', $amz_cart_items);

	foreach ( $amz_cart_items as $cart_item_key => $cart_item ) {
		$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

		if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
			?>
			<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
				<td class="product-name">
					<?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;'; ?>
					<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
					<?php echo WC()->cart->get_item_data( $cart_item ); ?>
				</td>
				<td class="product-total">
					<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
				</td>
			</tr>
			<?php
		}
	}

	do_action( 'woocommerce_review_order_after_cart_contents' );
	?>
	</tbody>
	<tfoot>

<!--	<tr class="cart-subtotal">-->
<!--		<th>--><?php //_e( 'Subtotal', 'woocommerce' ); ?><!--</th>-->
<!--		<td>--><?php //echo $cart_price; ?><!--</td>-->
<!--	</tr>-->

	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
		<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
			<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
			<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
		</tr>
	<?php endforeach; ?>

<!--	--><?php //if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
<!---->
<!--		--><?php //do_action( 'woocommerce_review_order_before_shipping' ); ?>
<!---->
<!--		--><?php //wc_cart_totals_shipping_html(); ?>
<!---->
<!--		--><?php //do_action( 'woocommerce_review_order_after_shipping' ); ?>
<!---->
<!--	--><?php //endif; ?>

	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<tr class="fee">
			<th><?php echo esc_html( $fee->name ); ?></th>
			<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
		</tr>
	<?php endforeach; ?>

	<?php if ( wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart ) : ?>
		<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
			<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
				<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
					<th><?php echo esc_html( $tax->label ); ?></th>
					<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr class="tax-total">
				<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
				<td><?php wc_cart_totals_taxes_total_html(); ?></td>
			</tr>
		<?php endif; ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

	<tr class="order-total">
		<th><?php _e( 'Subtotal', 'woocommerce' ); ?></th>
		<td><?php echo $cart_price; ?></td>
	</tr>

    <div>Note: Taxes and shipping fees may apply when checking out with Amazon</div>

	<?php
        do_action( 'woocommerce_review_order_after_order_total' );
	    $items = apply_filters('theme_get_had_amz_products_key_store', $items);

	    echo '<script>console.log("'.$items.'")</script>';
	    echo '<script>console.log("Hello World")</script>';
    ?>

	</tfoot>
</table>

