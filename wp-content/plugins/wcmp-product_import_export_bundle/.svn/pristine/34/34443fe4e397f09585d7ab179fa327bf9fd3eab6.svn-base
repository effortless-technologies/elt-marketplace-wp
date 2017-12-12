<?php

class WCMp_Product_Import_Export_Bundle_Frontend {

    public function __construct() {
        //enqueue scripts
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_scripts'));
        //enqueue styles
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_styles'));

        add_action('wcmp_product_import_export_bundle_frontend_hook', array(&$this, 'wcmp_product_import_export_bundle_frontend_function'), 10, 2);

        $is_import_export_show = get_option('wcmp_import_export_settings_name');
        if (isset($is_import_export_show['can_add_bulk_products'])) {
            add_filter('wcmp_vendor_dashboard_nav', array(&$this, 'add_import_export_plugin_menu'),20);
        }
    }

    public function add_import_export_plugin_menu($nav) {
        global $WCMp, $WCMp_Product_Import_Export_Bundle;
        if (!class_exists('WCMp_Frontend_Product_Manager')) {
            $nav['vendor-products'] = array(
                'label' => __('Product Manager', $WCMp->text_domain)
                , 'url' => '#'
                , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_products_capability', 'edit_products')
                , 'position' => 20
                , 'submenu' => array(
                    'vendor-products' => array(
                        'label' => __('Add Product', 'wcmp-product-import-export-bundle')
                        , 'url' => apply_filters('wcmp_vendor_submit_product', admin_url('edit.php?post_type=product'))
                        , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_products_capability', 'edit_products')
                        , 'position' => 10
                        , 'link_target' => '_self'
                    ),
                    'vendor-uploads' => array(
                        'label' => __('Upload', 'wcmp-product-import-export-bundle')
                        , 'url' => get_permalink(get_wcmp_vendor_settings('vendor_upload_product', 'vendor', 'general'))
                        , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_uploads_capability', 'edit_products')
                        , 'position' => 20
                        , 'link_target' => '_blank'
                    )
                )
                , 'link_target' => '_self'
                , 'nav_icon' => 'dashicons-cart'
            );
        } else {
            $nav['vendor-products']['submenu']['vendor-uploads'] = array(
                'label' => __('Upload', 'wcmp-product-import-export-bundle')
                , 'url' => get_permalink(get_wcmp_vendor_settings('vendor_upload_product', 'vendor', 'general'))
                , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_uploads_capability', true)
                , 'position' => 30
                , 'link_target' => '_blank'
            );
        }
        return $nav;
    }

    function frontend_scripts() {
        global $WCMp_Product_Import_Export_Bundle;
        $frontend_script_path = $WCMp_Product_Import_Export_Bundle->plugin_url . 'assets/frontend/js/';
        $frontend_script_path = str_replace(array('http:', 'https:'), '', $frontend_script_path);
        $pluginURL = str_replace(array('http:', 'https:'), '', $WCMp_Product_Import_Export_Bundle->plugin_url);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        // Enqueue your frontend javascript from here
    }

    function frontend_styles() {
        global $WCMp_Product_Import_Export_Bundle;
        $frontend_style_path = $WCMp_Product_Import_Export_Bundle->plugin_url . 'assets/frontend/css/';
        $frontend_style_path = str_replace(array('http:', 'https:'), '', $frontend_style_path);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        // Enqueue your frontend stylesheet from here
    }

    function wcmp_wcmp_product_import_export_bundle_frontend_function() {
        // Do your frontend work here
    }

}
