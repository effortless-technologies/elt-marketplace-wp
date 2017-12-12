<?php
/**
 * The template for displaying vendor products stock data
 *
 * Override this template by copying it to yourtheme/wcmp-vendor_stock_alert/vendor-dashboard/wcmp_vendor_products_stock.php
 * @author 		WC Marketplace
 * @package 	WCMp Vendor Stock Alert/Templates
 * @version   	1.0.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCMp_Vendor_Stock_Alert,$WCMp;
$user = wp_get_current_user();
$vendor = get_wcmp_vendor($user->ID);

$stock_alert_settings = get_user_meta( $vendor->id, 'wcmp_vendor_stock_alert_settings', true );
$wcmp_stock_alert_settings = $WCMp_Vendor_Stock_Alert->wcmp_capabilities;

if( isset($wcmp_stock_alert_settings['low_stock_enabled']) && $wcmp_stock_alert_settings['low_stock_enabled'] =='Enable' && isset($stock_alert_settings['low_stock_enabled']) && $stock_alert_settings['low_stock_enabled'] == 'Enable'){
	$low_stock_enabled = isset($stock_alert_settings['low_stock_enabled']) ? $stock_alert_settings['low_stock_enabled'] : '';
	$low_stock_limit = isset($stock_alert_settings['low_stock_limit']) ? $stock_alert_settings['low_stock_limit'] : '';
}
else{
	$low_stock_enabled = '';
	$low_stock_limit = '';
}

if( isset($wcmp_stock_alert_settings['out_of_stock_enabled']) && $wcmp_stock_alert_settings['out_of_stock_enabled'] =='Enable' && isset($stock_alert_settings['out_of_stock_enabled']) && $stock_alert_settings['out_of_stock_enabled'] == 'Enable'){
	$out_of_stock_enabled = isset($stock_alert_settings['out_of_stock_enabled']) ? $stock_alert_settings['out_of_stock_enabled'] : '';
	$out_of_stock_limit = isset($stock_alert_settings['out_of_stock_limit']) ? $stock_alert_settings['out_of_stock_limit'] : '';
}
else{
	$out_of_stock_enabled = '';
	$out_of_stock_limit = '';
}

$products_stock = array();
$vendor_products = array();
if(count($vendor->get_products()) > 0){
	foreach( $vendor->get_products() as $vendor_pro ) {
		$product = wc_get_product( $vendor_pro->ID );
		if( $product->is_type('variable') ) {
			if( $product->has_child() ) {
				$child_ids = $product->get_children();
				if( isset($child_ids) && !empty($child_ids) ) {
					foreach( $child_ids as $child_id ) {
						$vendor_products[] = $child_id;
					}
				}
			}
		} else {
			$vendor_products[] = $vendor_pro->ID;
		}
	}
}

if(count($vendor_products) > 0){
	foreach($vendor_products as $vendor_pro_id){
		$product = wc_get_product($vendor_pro_id);

		$stock_quantity = $product->get_stock_quantity();
		$stock_status = $product->get_stock_status();
		
		if($stock_status == 'instock'){
			// low stock and out of stock
			if( $low_stock_enabled == 'Enable' || $out_of_stock_enabled == 'Enable' ) {
				if( $stock_quantity <= $low_stock_limit && $stock_quantity > $out_of_stock_limit ) {
					$products_stock['lowstock'][] = $vendor_pro_id;
					$products_stock['instock'][] = $vendor_pro_id;
				}elseif($stock_quantity <= $out_of_stock_limit){
					$products_stock['outstock'][] = $vendor_pro_id;
				}else{
					$products_stock['instock'][] = $vendor_pro_id;
				}
			}
			
		}elseif ($stock_status == 'outofstock') {
			$products_stock['outstock'][] = $vendor_pro_id;
		}
	}
}
?>
<?php do_action( 'wcmp_vendor_dash_before_vendor_stock_display' ); ?>
<div class="wcmp_tab vendor_products_stock_details">
	<ul>
		<li><a href="#instock" id="instock_click"><?php _e( 'In Stock', 'wcmp-vendor_stock_alert' );?></a></li>
	<?php if( isset($wcmp_stock_alert_settings['low_stock_enabled']) && $wcmp_stock_alert_settings['low_stock_enabled'] =='Enable' && isset($stock_alert_settings['low_stock_enabled']) && $stock_alert_settings['low_stock_enabled'] == 'Enable'){ ?>
		<li><a href="#lowstock" id="lowstock_click" ><?php _e('Low Stock', 'wcmp-vendor_stock_alert');?></a></li>
	<?php } if( isset($wcmp_stock_alert_settings['out_of_stock_enabled']) && $wcmp_stock_alert_settings['out_of_stock_enabled'] =='Enable' && isset($stock_alert_settings['out_of_stock_enabled']) && $stock_alert_settings['out_of_stock_enabled'] == 'Enable'){ ?>
		<li><a href="#outstock" id="outstock_click" ><?php _e('Out of Stock', 'wcmp-vendor_stock_alert');?></a></li>
	<?php } ?>
	</ul>
	<div class="wcmp_tabbody" id="instock">
		<?php
			if(!empty($products_stock['instock'])) { ?>
				<form name="wcmp_vendor_products_stock_instock" method="post" >
					<div class="wcmp_table_loader"> <?php _e( 'Total Results', 'wcmp-vendor_stock_alert' );?><span> 
						<?php echo '<span>'.count($products_stock['instock']).'</span>';?>
					</div>
					<div class="wcmp_table">
						<table id="wcmp_vendor_products_stock_instock_table" width="100%" border="0" cellspacing="0" class="wcmp_vendor_products_stock_instock_table" cellpadding="0">
						<thead>
							<tr>
								<td align="center" valign="top" ><?php _e( 'Product', 'wcmp-vendor_stock_alert' );?></td>
								<td  align="center" valign="top" ><?php _e( 'Stock Status', 'wcmp-vendor_stock_alert' );?></td>
								<td align="center"  valign="top" ><?php _e( 'Stock Quantity', 'wcmp-vendor_stock_alert' );?> </td>
							</tr>
						</thead>
						<tbody>
							<?php 
							$product_instock = $products_stock['instock'];
							if(!empty($product_instock)) { 
								foreach($product_instock as $pro_id) { 
									$product = wc_get_product( $pro_id );
									if( $product->is_type('variation') ) {
										$parent_id = $product->get_parent_id();
										$edit_pro_id = $parent_id;
									} else {
										$edit_pro_id = $pro_id;
									}
									if(class_exists('WCMp_Frontend_Product_Manager')) {
										$product_link = get_permalink( absint(get_wcmp_vendor_settings('frontend_product_manager', 'vendor', 'general'))).'?pro_id='.$edit_pro_id;
									}else{
										$product_link = get_edit_post_link( $edit_pro_id );
									}	
							?>
							<tr>
								<td align="" class="img bind_wdth"><?php echo $product->get_image(array(64,64)).'<a href="'.$product_link.'"><span>'. $product->get_name(). '</span></a>'; ?> </td>
								<td align="center" ><?php echo $product->get_stock_status(); ?></td>
								<td class="no_display" align="center" ><?php echo $product->get_stock_quantity(); ?></td>
							</tr>
							<?php }
							}
							?>
						</tbody>
						</table>
					</div>
					
				</form>
			<?php } else { ?>
			<div class="wcmp_table_loader"> <?php _e( 'Total Results', 'wcmp-vendor_stock_alert' );?><span> 0 </span></div> 
		<?php } ?>
	</div>
<?php if( isset($wcmp_stock_alert_settings['low_stock_enabled']) && $wcmp_stock_alert_settings['low_stock_enabled'] =='Enable' && isset($stock_alert_settings['low_stock_enabled']) && $stock_alert_settings['low_stock_enabled'] == 'Enable'){ ?>
	<div class="wcmp_tabbody" id="lowstock">
		<?php
			if(!empty($products_stock['lowstock'])) { ?>
				<form name="wcmp_vendor_products_stock_lowstock" method="post" >
					<div class="wcmp_table_loader"> <?php _e( 'Total Results', 'wcmp-vendor_stock_alert' );?><span> 
						<?php echo '<span>'.count($products_stock['lowstock']).'</span>'; ?>
					</div>
					<div class="wcmp_table">
						<table width="100%" border="0" cellspacing="0" id="wcmp_vendor_products_stock_lowstock_table" class="wcmp_vendor_products_stock_lowstock_table" cellpadding="0">
						<thead>
							<tr>
								<td align="center" valign="top" ><?php _e( 'Product', 'wcmp-vendor_stock_alert' );?></td>
								<td  align="center" valign="top" ><?php _e( 'Stock Status', 'wcmp-vendor_stock_alert' );?></td>
								<td align="center"  valign="top" ><?php _e( 'Stock Quantity', 'wcmp-vendor_stock_alert' );?> </td>
							</tr>
						</thead>
						<tbody>
							<?php 
							$product_instock = $products_stock['lowstock'];
							if(!empty($product_instock)) { 
								foreach($product_instock as $pro_id) { 
									$product = wc_get_product( $pro_id );
									if( $product->is_type('variation') ) {
										$parent_id = $product->get_parent_id();
										$edit_pro_id = $parent_id;
									} else {
										$edit_pro_id = $pro_id;
									}
									if(class_exists('WCMp_Frontend_Product_Manager')) {
										$product_link = get_permalink( absint(get_wcmp_vendor_settings('frontend_product_manager', 'vendor', 'general'))).'?pro_id='.$edit_pro_id;
									}else{
										$product_link = get_edit_post_link( $edit_pro_id );
									}	
							?>
							<tr>
								<td align="" class="img bind_wdth"><?php echo $product->get_image(array(64,64)).'<a href="'.$product_link.'"><span>'. $product->get_name(). '</span></a>'; ?> </td>
								<td align="center" ><?php echo $product->get_stock_status(); ?></td>
								<td class="no_display" align="center" ><?php echo $product->get_stock_quantity(); ?></td>
							</tr>
							<?php }
							}
							?>
						</tbody>
						</table>
					</div>
				
				</form>
			<?php } else { ?>
			<div class="wcmp_table_loader"> <?php _e( 'Total Results', 'wcmp-vendor_stock_alert' );?><span> 0 </span></div> 
		<?php } ?>
	</div>
<?php } if( isset($wcmp_stock_alert_settings['out_of_stock_enabled']) && $wcmp_stock_alert_settings['out_of_stock_enabled'] =='Enable' && isset($stock_alert_settings['out_of_stock_enabled']) && $stock_alert_settings['out_of_stock_enabled'] == 'Enable'){ ?>
	<div class="wcmp_tabbody" id="outstock">
	<?php
		if(!empty($products_stock['outstock'])) { ?>
			<form name="wcmp_vendor_products_stock_outstock" method="post" >
				<div class="wcmp_table_loader"> <?php _e( 'Total Results', 'wcmp-vendor_stock_alert' );?><span> 
					<?php echo '<span>'.count($products_stock['outstock']).'</span>';?>
				</div>
				<div class="wcmp_table">
					<table width="100%" border="0" cellspacing="0" id="wcmp_vendor_products_stock_outstock_table" class="wcmp_vendor_products_stock_outstock_table" cellpadding="0">
						<thead>
							<tr>
								<td align="center" valign="top" ><?php _e( 'Product', 'wcmp-vendor_stock_alert' );?></td>
								<td  align="center" valign="top" ><?php _e( 'Stock Status', 'wcmp-vendor_stock_alert' );?></td>
								<td align="center"  valign="top" ><?php _e( 'Stock Quantity', 'wcmp-vendor_stock_alert' );?> </td>
							</tr>
						</thead>
						<tbody>
							<?php 
							$product_instock = $products_stock['outstock'];
							if(!empty($product_instock)) { 
								foreach($product_instock as $pro_id) { 
									$product = wc_get_product( $pro_id );
									if( $product->is_type('variation') ) {
										$parent_id = $product->get_parent_id();
										$edit_pro_id = $parent_id;
									} else {
										$edit_pro_id = $pro_id;
									}
									if(class_exists('WCMp_Frontend_Product_Manager')) {
										$product_link = get_permalink( absint(get_wcmp_vendor_settings('frontend_product_manager', 'vendor', 'general'))).'?pro_id='.$edit_pro_id;
									}else{
										$product_link = get_edit_post_link( $edit_pro_id );
									}	
							?>
							<tr>
								<td align="" class="img bind_wdth"><?php echo $product->get_image(array(64,64)).'<a href="'.$product_link.'"><span>'. $product->get_name(). '</span></a>'; ?> </td>
								<td align="center" ><?php echo $product->get_stock_status(); ?></td>
								<td class="no_display" align="center" ><?php echo $product->get_stock_quantity(); ?></td>
							</tr>
							<?php }
							}
							?>
						</tbody>
						</table>
				</div>
			</form>
		<?php } else { ?>
			<div class="wcmp_table_loader"> <?php _e( 'Total Results', 'wcmp-vendor_stock_alert' );?><span> 0 </span></div> 
		<?php } ?>
	</div>
<?php } ?>
</div>
<?php do_action( 'wcmp_vendor_dash_after_vendor_stock_display' ); ?>