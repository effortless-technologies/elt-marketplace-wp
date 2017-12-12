<?php

/**
 * 
 * Plugin install script which adds default pages, taxonomies, and database tables to WordPress. Runs on activation and upgrade.
 *
 * @author 		Dualcube
 * @package 	wcmp/Admin/Install
 * @version    0.0.1
 */
class WCMp_Product_Import_Export_Bundle_Install {

    public function __construct() {
        if (!get_option("wcmp_product_vendor_import_export_plugin_page_install")) {
            $this->wcmp_product_vendor_plugin_create_pages();
        }
        update_option("wcmp_product_vendor_import_export_plugin_page_install", 1);
    }

    /**
     * Create pages that the plugin relies on, storing page id's in variables.
     *
     * @access public
     * @return void
     */
    public function wcmp_product_vendor_plugin_create_pages() {
        global $WCMp, $WCMp_Product_Import_Export_Bundle;
        if (!class_exists('WCMp_Install')) {
            require_once ( $WCMp->plugin_path . 'includes/class-wcmp-install.php' );
        }
        $WCMp_Install = new WCMp_Install();
        $WCMp_Install->wcmp_product_vendor_plugin_create_page(esc_sql(_x('upload_products', 'page_slug', 'wcmp-product-import-export-bundle')), 'wcmp_product_vendor_upload_product_page_id', __('Upload Products', 'wcmp-product-import-export-bundle'), '[upload_products]');
        if (function_exists('update_wcmp_vendor_settings')) {
            update_wcmp_vendor_settings('vendor_upload_product', get_option('wcmp_product_vendor_upload_product_page_id'), 'vendor', 'general');
        }
    }

}