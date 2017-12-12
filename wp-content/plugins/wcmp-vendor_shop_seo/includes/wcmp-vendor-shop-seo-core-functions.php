<?php
if(!function_exists('get_vendor_shop_seo_settings')) {
  function get_vendor_shop_seo_settings($name = '', $tab = '') {
    if(empty($tab) && empty($name)) return '';
    if(empty($tab)) return get_option($name);
    if(empty($name)) return get_option("dc_{$tab}_settings_name");
    $settings = get_option("dc_{$tab}_settings_name");
    if(!isset($settings[$name])) return '';
    return $settings[$name];
  }
}

if(!function_exists('vss_woocommerce_inactive_notice')) {
	function vss_woocommerce_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCMp Google Analytics and Seo is inactive.%s The %sWooCommerce plugin%s must be active for the WCMp Google Analytics and Seo to work. Please %sinstall & activate WooCommerce%s', WCMP_VENDOR_SHOP_SEO_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=woocommerce' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('vss_wcmp_inactive_notice')) {
	function vss_wcmp_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCMp Google Analytics and Seo is inactive.%s The %sWC Marketplace%s must be active for the WCMp Google Analytics and Seo to work. Please %sinstall & activate WC Marketplace%s', WCMP_VENDOR_SHOP_SEO_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/dc-woocommerce-multi-vendor/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=wc+marketplace' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}
?>
