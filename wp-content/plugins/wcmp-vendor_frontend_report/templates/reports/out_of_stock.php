<?php
global $wpdb, $WCMp_Vendor_Frontend_Report, $WCMp, $woocommerce, $wp_locale;

?>
<div class="wcmp_frontend_low_in_stock_report">
<table class="out_of_stock_report">
	<thead>
		<tr>
			<th scope="col" id="product" class="manage-column column-product column-primary"><?php _e( 'Product', 'wcmp-vendor_frontend_report' ); ?></th>
			<th scope="col" id="parent" class="manage-column column-parent"><?php _e( 'Parent', 'wcmp-vendor_frontend_report' ); ?></th>
			<th scope="col" id="stock_status" class="manage-column column-stock_status"><?php _e( 'Stock status', 'wcmp-vendor_frontend_report' ); ?></th>
			<th scope="col" id="stock_level" class="manage-column column-stock_level"><?php _e( 'Units in stock', 'wcmp-vendor_frontend_report' ); ?></th>
			<th scope="col" id="wc_actions" class="manage-column column-wc_actions"><?php _e( 'Actions', 'wcmp-vendor_frontend_report' ); ?></th>	
		</tr>
	</thead>
	<tbody id="the-list" data-wp-lists="list:stock">
		<?php
			$WCMp_Vendor_Frontend_Report->template->get_template( 'reports/stock_items.php', array('items' => $items) );
		?>
	</tbody>
</table>
<div>
	<?php if($show_more_button) { ?>
		<input type="button" class="all_new_btn" style="float: right;" id="more_out_of_stock" data-show="<?php echo $current_page;?>" value="<?php _e( 'More Products', 'wcmp-vendor_frontend_report' ); ?>"/>
	<?php } ?>
	<div style="clear: both;"></div>
</div>
</div>