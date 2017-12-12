<?php
global  $WCMp_Vendor_Frontend_Report, $WCMp, $woocommerce, $wp_locale;
$report_html = __('-- Choose a product first.', $WCMp_Vendor_Frontend_Report->text_domain);
?>
<div id="poststuff" class="woocommerce-reports-wide">
	<?php	
		if(!isset($_GET['product_serach_from_date'])) $_GET['product_serach_from_date'] = '';
		if(!isset($_GET['product_serach_to_date'])) $_GET['product_serach_to_date'] = '';
	?>
	<div class="transaction_settings"  style="display: block; width: 100%;">
		<form method="get" id="wcmp_transaction_filter">
			<table>
				<tbody>
					<tr>
						<td style="padding : 0; "><input style="width : 150px" id="wcmp_frontend_product_search_from_date"  name="product_serach_from_date" placeholder="<?php _e( 'From', $WCMp_Vendor_Frontend_Report->text_domain ); ?>" value ="<?php echo $_GET['product_serach_from_date'];?>"/></td>
						<td style="padding : 0; "><input style="width : 150px" id="wcmp_frontend_product_search_to_date" name="product_serach_to_date" placeholder="<?php _e( 'To', $WCMp_Vendor_Frontend_Report->text_domain ); ?>" value ="<?php echo $_GET['product_serach_to_date'];?>"/></td>
						<td style="padding : 0; " >
							<?php $option = '<option>Select a product</option>'; ?>
							<select id="search_product" name="search_product" class="ajax_chosen_select_products_and_variations" data-placeholder="<?php _e( 'Search for a Product', $WCMp_Vendor_Frontend_Report->text_domain ); ?>" style="min-width:150px;">
								<?php echo $option; ?>
							</select> 
						</td>
						<td style="padding : 0; " >
							<input type="button" style="vertical-align: top;" class="product_report_search all_new_btn" value="<?php _e( 'Show', $WCMp_Vendor_Frontend_Report->text_domain ); ?>" />
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
				
	<div class="product_sort_chart">
		<?php echo $report_html; ?>
	</div>
</div>