<?php
if(!function_exists('get_sub_vendor_settings')) {
  function get_sub_vendor_settings($name = '', $tab = '') {
    if(empty($tab) && empty($name)) return '';
    if(empty($tab)) return get_option($name);
    if(empty($name)) return get_option("dc_{$tab}_settings_name");
    $settings = get_option("dc_{$tab}_settings_name");
    if(!isset($settings[$name])) return '';
    return $settings[$name];
  }
}

if(!function_exists('wcmp_report_vendor')){
    function wcmp_report_vendor($subvendor_id){
        $report_vendor_id = get_user_meta($subvendor_id,'_report_vendor',true);
        $report_vendor = get_wcmp_vendor($report_vendor_id);
        return $report_vendor;
    }
}

/**
 * Error notices for woocommerce plugin not found
 */
if(!function_exists('wcmp_sub_vendor_woocommerce_inactive_notice')) {
	function wcmp_sub_vendor_woocommerce_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCMP Sub Vendor is inactive.%s The %sWooCommerce plugin%s must be active for the WCMP Sub Vendor to work. Please %sinstall & activate WooCommerce%s', WCMP_SUB_VENDOR_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=woocommerce' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}
/**
 * Error notices for wc-marketplace plugin not found
 */
if(!function_exists('wcmp_sub_vendor_wcmp_inactive_notice')) {
	function wcmp_sub_vendor_wcmp_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCMP Sub Vendor is inactive.%s The %sWC Marketplace%s must be active for the WCMP Sub Vendor to work. Please %sinstall & activate WC Marketplace%s', WCMP_SUB_VENDOR_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/dc-woocommerce-multi-vendor/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=wc+marketplace' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}
?>
