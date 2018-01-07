<?php
/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
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

$_cart_items = "";
$_cart_items = apply_filters('theme_get_key_amz_products_keys_store', $_cart_items);
$_json = json_encode($_cart_items);

$_cart = WC()->cart->get_cart();
$_cart_json = json_encode($_cart);

$_amz_product_keys = null;
$_amz_product_keys = apply_filters('theme_get_key_amz_products_keys_store', $_amz_product_keys);

if(count($_amz_product_keys != count($_cart))) {
	foreach($_amz_product_keys as $key=>$_amz_product_key) {
		$_bool = WC()->cart->restore_cart_item($_amz_product_key);
		$_json_ = json_encode($_bool);
		trigger_error(sprintf($_json_));
	}
}

$_cart = WC()->cart->get_cart();
$json = json_encode($_cart);
trigger_error(sprintf($json));

?>
<section class="woocommerce-customer-details">

	<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) : ?>

		<section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">

			<div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">

				<?php endif; ?>

				<h2 class="woocommerce-column__title"><?php _e( 'Billing address', 'woocommerce' ); ?></h2>

				<address>
					<?php echo ( $address = $order->get_formatted_billing_address() ) ? $address : __( 'N/A', 'woocommerce' ); ?>
					<?php if ( $order->get_billing_phone() ) : ?>
						<p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_billing_phone() ); ?></p>
					<?php endif; ?>
					<?php if ( $order->get_billing_email() ) : ?>
						<p class="woocommerce-customer-details--email"><?php echo esc_html( $order->get_billing_email() ); ?></p>
					<?php endif; ?>
				</address>

				<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) : ?>

			</div><!-- /.col-1 -->

			<div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">

				<h2 class="woocommerce-column__title"><?php _e( 'Shipping address', 'woocommerce' ); ?></h2>

				<address>
					<?php echo ( $address = $order->get_formatted_shipping_address() ) ? $address : __( 'N/A', 'woocommerce' ); ?>
				</address>

			</div><!-- /.col-2 -->

		</section><!-- /.col2-set -->

    <?php endif; ?>

    <?php echo $_json; ?>
	<?php echo $json; ?>

</section>
