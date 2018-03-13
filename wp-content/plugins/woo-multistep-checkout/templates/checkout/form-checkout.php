<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

?>

<?php 
	$thwmscf_settings = get_option('THWMSC_SETTINGS'); 	

	$thwmscf_tab_align = !empty($thwmscf_settings['tab_align']) ? $thwmscf_settings['tab_align'] : '';
	$thwmscf_title_login = !empty($thwmscf_settings['title_login']) ? wptexturize($thwmscf_settings['title_login']) : "Login";
	$thwmscf_title_billing = !empty($thwmscf_settings['title_billing']) ? wptexturize($thwmscf_settings['title_billing']) : "Billing";
	$thwmscf_title_shipping = !empty($thwmscf_settings['title_shipping']) ? wptexturize($thwmscf_settings['title_shipping']) : "Shipping";
	$thwmscf_title_order_review = !empty($thwmscf_settings['title_order_review']) ? wptexturize($thwmscf_settings['title_order_review']) : "Your order";
?>

<div id="thwmscf_wrapper" class="thwmscf-wrapper">  
	<ul id="thwmscf-tabs" class="thwmscf-tabs <?php echo $thwmscf_tab_align; ?>">	
		<?php 
		$step1_class = 'first active';
		$enable_login_reminder = false;	
		if(!is_user_logged_in() && 'yes' === get_option( 'woocommerce_enable_checkout_login_reminder')){
			$enable_login_reminder = true;
			$step1_class = '';	
		?>
			<li class="thwmscf-tab"><a href="javascript:void(0)" id="step-0" data-step="0" class="first active"><?php echo $thwmscf_title_login ; ?></a></li>
		<?php 	
		}
		?>	
			
		<li class="thwmscf-tab"><a href="javascript:void(0)" id="step-1" data-step="1" class="<?php echo $step1_class; ?>"><?php echo $thwmscf_title_billing; ?></a></li> 
		<li class="thwmscf-tab"><a href="javascript:void(0)" id="step-2" data-step="2"><?php echo $thwmscf_title_shipping; ?></a></li>
		<li class="thwmscf-tab"><a href="javascript:void(0)" id="step-3" data-step="3" class="last"><?php echo $thwmscf_title_order_review; ?></a></li>
<!--        <li class="thwmscf-tab"><a href="javascript:void(0)" id="step-4" data-step="4" class="last">--><?php //echo $thwmscf_title_test; ?><!--</a></li>-->
	</ul>
	<div id="thwmscf-tab-panels" class="thwmscf-tab-panels">
	<?php 
	if($enable_login_reminder){
	?>
		<div class="thwmscf-tab-panel" id="thwmscf-tab-panel-0">
		<?php do_action( 'thwmscf_before_checkout_form' ); ?>
		</div>
	<?php 
	}
	?>
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<!--<div class="col2-set" id="customer_details">-->
			<div class="thwmscf-tab-panel" id="thwmscf-tab-panel-1">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="thwmscf-tab-panel" id="thwmscf-tab-panel-2">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
		<!--</div>-->

		<?php do_action( 'woozone_woo_cart_store_amazon_prods' ); ?>
<!--		--><?php //do_action( 'woozone_woo_cart_amazon_redirect' ); ?>

	<?php endif; ?>

<div class="thwmscf-tab-panel" id="thwmscf-tab-panel-3">
<!--	<h3 id="order_review_heading">--><?php //_e( 'Your order', 'woocommerce' ); ?><!--</h3>-->

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<?php
	$cart = WC()->cart->get_cart();
	do_action('woozone_woo_cart_amazon_parse_cart_items', $cart);
	$amz_cart_items = '';
	$amz_cart_items = apply_filters( 'woozone_woo_cart_amazon_get_products', $cart);
	$non_amz_cart_items = apply_filters('woozone_woo_cart_amazon_remove_amz_products', $cart, $amz_cart_items);

	?>

<!--    // TODO: this is where the logic lies that changes for the 3 uses cases of the cart (amz vs elt vs amx/elt) -->

	<div id="order_review" class="woocommerce-checkout-review-order">
       <?php
		if(count($non_amz_cart_items)){
			echo "<h3>Products to be fulfilled by Effortless Marketplace</h3>";
			do_action( 'woocommerce_non_amazon_checkout_cart_review' );
		}		 ?>
<!--		--><?php //do_action('woocommerce_amazon_checkout_cart_review'); ?>
        <?php 
		if(count($amz_cart_items)){
			do_action('theme_set_had_amz_products_key_store', true);
			echo "<h3>Products to be fulfilled by Amazon</h3>";
			do_action('woocommerce_amazon_checkout_cart_review');
		}
		?>

	<?php

	
	if(count($non_amz_cart_items) && count($amz_cart_items)){
	    echo '<table class="shop_table woocommerce-amazon-checkout-review-order-table"><tfoot>';
//	echo '<tr class="order-total">';
//		echo '<th style="width:90%; text-align:right;">';
//		_e( 'Total', 'woocommerce' );
//		echo '</th>';
//		echo '<td style="width:10%; text-align:left;">';
//		wc_cart_totals_order_total_html();
//		echo '</td>';
//	echo '</tr>';
	}
	?>


	</tfoot>
	</table>	
    </div>
<?php
	if(!count($non_amz_cart_items) && count($amz_cart_items)){
		echo '<div id="amazon-redirect-block" style="font-size:1.2em;display:block; width:100%;">'.
		'<a href="#amazon-redirect-block" id="amazon_checkout_redirect" onclick="amazon_checkout_redirect()">Continue to Amazon Checkout</a>'.
		'</div>';		
	}else{
		do_action('woocommerce_checkout_order_review');
		do_action( 'woocommerce_checkout_after_order_review' );
	}
	?>
</div>

</form>
	</div>
	<div class="thwmscf-buttons">
		<input type="button" id="action-prev" class="button-prev" value="<?php _e( 'Previous', 'thwmsc' ); ?>">
		<input type="button" id="action-next" class="button-next" value="<?php _e( 'Next', 'thwmsc' ); ?>">
	</div>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<script>
 document.addEventListener("DOMContentLoaded", function(_e) { 

	 function _listen(){
		var watch = document.getElementById('thwmscf-tab-panel-3');
			if((watch) && watch.style.display != 'none'){	 
				var _cd = document.getElementById('amazon-count-down');
				var _start = (new Date()).getTime();
				var _from = 13;
				var _last = _from+0;
				function _tick(){
				var _cdl = document.getElementById('amazon_checkout_redirect');
					if(!_cdl || watch.style.display == 'none'){return;}
					var _now = Math.floor(_from - (((new Date()).getTime() - _start)/1000));
					if(_now < _last){
						_cd.innerHTML = _now;
						_last = _now;
						if(_now <= 0){						
							_cdl.click();
							return;
						}
					}
				setTimeout(()=>{_tick();}, 1000/30);
				};
			_tick();
			}else{			
			setTimeout(()=>{_listen();}, 1000/30);
		}		
	 };	 
	 
	//_listen();	
		
 });
</script>
