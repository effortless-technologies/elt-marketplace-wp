<?php
if(!function_exists('get_product_import_export_bundle_settings')) {
  function get_product_import_export_bundle_settings($name = '', $tab = '') {
    if(empty($tab) && empty($name)) return '';
    if(empty($tab)) return get_option($name);
    if(empty($name)) return get_option("wcmp_{$tab}_settings_name");
    $settings = get_option("wcmp_{$tab}_settings_name");
    if(!isset($settings[$name])) return '';
    return $settings[$name];
  }
}

if( ! function_exists( 'wcmp_import_export_action_links' ) ) {
  function wcmp_import_export_action_links($links) {
		global $WCMp;
		$plugin_links = array(
    '<a href="' . admin_url( 'admin.php?page=wcmp-setting-admin&tab=import_export' ) . '">' . __( 'Settings', $WCMp->text_domain ) . '</a>'  );
    return array_merge( $plugin_links, $links );
	}
}

if(!function_exists('pie_woocommerce_inactive_notice')) {
	function pie_woocommerce_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCMp Product Import Export Bundle is inactive.%s The %sWooCommerce plugin%s must be active for the WCMp Product Import Export Bundle to work. Please %sinstall & activate WooCommerce%s', WCMP_FRONTEND_PRODUCT_MANAGER_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=woocommerce' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('pie_wcmp_inactive_notice')) {
	function pie_wcmp_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCMp Product Import Export Bundle is inactive.%s The %sWC Marketplace%s must be active for the WCMp Product Import Export Bundle to work. Please %sinstall & activate WC Marketplace%s', WCMP_FRONTEND_PRODUCT_MANAGER_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/dc-woocommerce-multi-vendor/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=wc+marketplace' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}
?>
