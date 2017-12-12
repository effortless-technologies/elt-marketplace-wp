<?php
global  $WCMp_Vendor_Frontend_Report, $WCMp, $woocommerce, $wp_locale;

?>
<div id="poststuff" class="woocommerce-reports-wide">
	
	<div class="transaction_settings">
		<div >
			<form method="get" id="wcmp_transaction_filter" action="#wcmp_report_product_sells" >
				<table>
					<tbody>
						<tr>
							<td style="padding : 0; "><input id="wcmp_product_frontend_product_from_date"  name="product_overview_from_date" placeholder="From" value ="<?php echo $start_date;?>"/></td>
							<td style="padding : 0; "><input id="wcmp_product_frontend_product_to_date"  name="product_overview_to_date" placeholder="To" value ="<?php echo $end_date;?>"/></td>
							<td style="padding : 0 0 0 2px; "><p class="submit"><input type="submit" name="wcmp_product_submit" id="submit" class="button button-primary all_new_btn" value="<?php _e( 'Filter', $WCMp_Vendor_Frontend_Report->text_domain ); ?>"></p></td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
		<div class="sorting_box">
			<div class="product_report_sort_outer">
				<select name="product_report_sort" class="product_report_sort">
					<option value="total_sales"><?php _e( 'Total Sales', $WCMp_Vendor_Frontend_Report->text_domain ); ?></option>
					<option value="admin_earning"><?php _e( 'My Earnings', $WCMp_Vendor_Frontend_Report->text_domain ); ?></option>
				</select>
			</div>
			<input style="display: none;" type="checkbox" class="low_to_high" name="low_to_high" value="checked" />
			<button class="low_to_high_btn_product"><i class="fa fa-arrow-up"></i></button>
			<input style="display: none;" type="checkbox" class="high_to_low" name="high_to_low" value="checked" checked />
			<button class="high_to_low_btn_product"><i class="fa fa-arrow-down"></i></button>
		</div>
	</div>
	<?php if(!$start_date && !$end_date) { ?>
	<?php } ?>
	<div class="product_overview_chart">
		<?php echo $report_html; ?>
	</div>
</div>