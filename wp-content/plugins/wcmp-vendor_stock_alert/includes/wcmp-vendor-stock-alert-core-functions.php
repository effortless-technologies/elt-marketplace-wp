<?php
if(!function_exists('vsa_woocommerce_inactive_notice')) {
	function vsa_woocommerce_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCMp Vendor Stock Alert is inactive.%s The %sWooCommerce plugin%s must be active for the WCMp Vendor Stock Alert to work. Please %sinstall & activate WooCommerce%s', WCMP_VENDOR_STOCK_ALERT_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=woocommerce' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('vsa_wcmp_inactive_notice')) {
	function vsa_wcmp_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCMp Vendor Stock Alert is inactive.%s The %sWC Marketplace%s must be active for the WCMp Vendor Stock Alert to work. Please %sinstall & activate WC Marketplace%s', WCMP_VENDOR_STOCK_ALERT_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/dc-woocommerce-multi-vendor/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=wc+marketplace' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

?>