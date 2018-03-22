<?php

class WCMp_Frontend_Product_Manager_Frontend {

    public function __construct() {

        // Grid Plus Fix
        add_filter('mce_external_plugins', array($this, 'grid_custom_editor_register_tinymce_javascript'), 100);

        //enqueue scripts
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_scripts'), 100);
        //enqueue styles
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_styles'), 1000);

        //add_filter('is_wcmp_vendor_dashboard', array(&$this, 'is_wcmp_vendor_dashboard'));
        //filter for adding header to import export pages in frontend by endpoint
        //add_action('wcmp_vendor_dashboard_header', array(&$this, 'wcmp_vendor_dashboard_header'));
        //filter for fetching the template of frontend export 
        add_action('wcmp_vendor_dashboard_product-export_endpoint', array(&$this, 'wcmp_vendor_dashboard_product_export_endpoint'));

        //filter for fetching the template of frontend import 
        add_action('wcmp_vendor_dashboard_product-import_endpoint', array(&$this, 'wcmp_vendor_dashboard_product_import_endpoint'));

        $user_id = get_current_user_id();
        $vendor = get_wcmp_vendor($user_id);
        if ($vendor) {
            add_filter('product_type_selector', array(&$this, 'product_type_selector_callback'));

            //filter for overriding the woocommerce csv importer class
            add_filter('woocommerce_product_csv_importer_class', array(&$this, 'woocommerce_product_csv_importer_class'));

            //filter for inserting data to database after import
            add_action('woocommerce_product_import_inserted_product_object', array(&$this, 'process_import'), 10, 2);

            add_filter('wcmp_disable_other_product_type', '__return_false');
        }
    }

    /**
     * adds header to import export pages in frontend by endpoint
     */
    public function wcmp_vendor_dashboard_header() {
        global $WCMp;
        if ($WCMp->endpoints->get_current_endpoint() == 'product-import') {
            echo '<ul>';
            echo '<li>' . __('Product Manager ', 'wcmp-frontend_product_manager') . '</li>';
            echo '<li class="next"> < </li>';
            echo '<li>' . __('Import', 'wcmp-frontend_product_manager') . '</li>';
            echo '</ul>';
        }
        if ($WCMp->endpoints->get_current_endpoint() == 'product-export') {
            echo '<ul>';
            echo '<li>' . __('Product Manager ', 'wcmp-frontend_product_manager') . '</li>';
            echo '<li class="next"> < </li>';
            echo '<li>' . __('Export', 'wcmp-frontend_product_manager') . '</li>';
            echo '</ul>';
        }
    }

    /**
     * fetches the template of frontend export 
     */
    public function wcmp_vendor_dashboard_product_export_endpoint() {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        wp_enqueue_style('woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION);
        // Add RTL support for admin styles
        // wp_style_add_data( 'woocommerce_admin_styles', 'rtl', 'replace' );
        include_once( WC_ABSPATH . 'includes/export/class-wc-product-csv-exporter.php' );
        $user_id = get_current_user_id();
        $vendor = get_wcmp_vendor($user_id);
        $user = new WP_User($user_id);
        if ($vendor && $user->has_cap('edit_products')) {

            //$WCMP_Product_Import_Export_Bundle->template->get_template('product-export.php');
            echo '<div class="col-md-12">';
            include_once( WC_ABSPATH . 'includes/export/class-wc-product-csv-exporter.php' );
            include_once( WC_ABSPATH . 'includes/admin/views/html-admin-page-product-export.php' );
            echo '</div>';
        } else {
            $WCMp_Frontend_Product_Manager->template->get_template('nocap.php');
        }
    }

    /**
     * fetches the template of frontend import 
     */
    public function wcmp_vendor_dashboard_product_import_endpoint() {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        wp_enqueue_style('woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION);
        // Add RTL support for admin styles
        // wp_style_add_data( 'woocommerce_admin_styles', 'rtl', 'replace' );
        $user_id = get_current_user_id();
        $vendor = get_wcmp_vendor($user_id);
        $user = new WP_User($user_id);
        if ($vendor && $user->has_cap('edit_products')) {
            include_once( WC_ABSPATH . 'includes/import/class-wc-product-csv-importer.php' );
            $WCMp_Frontend_Product_Manager->import_controller->dispatch();
        } else {
            $WCMp_Frontend_Product_Manager->template->get_template('nocap.php');
        }
    }

    /**
     * add product types
     */
    public function product_type_selector_callback($product_type) {
        if (wcmp_forntend_manager_is_bundle()) {
            $product_type['bundle'] = __('Product Bundle', 'wcmp-frontend_product_manager');
        }
        if (wcmp_forntend_manager_is_subscription()) {
            $product_type['subscription'] = __('Simple subscription', 'wcmp-frontend_product_manager');
            $product_type['variable-subscription'] = __('Variable subscription', 'wcmp-frontend_product_manager');
        }
        if (wcmp_forntend_manager_is_booking()) {
            $product_type['booking'] = __('Bookable product', 'wcmp-frontend_product_manager');
        }
        if (wcmp_forntend_manager_is_accommodation_booking()) {
            $product_type['accommodation-booking'] = __('Accommodation product', 'wcmp-frontend_product_manager');
        }
        if (wcmp_forntend_manager_is_yithauction() || wcmp_forntend_manager_is_wcsauction()) {
            $product_type['auction'] = __('Auction', 'wcmp-frontend_product_manager');
        }
        if (wcmp_forntend_manager_is_rental()) {
            $product_type['redq_rental'] = __('Rental Product', 'wcmp-frontend_product_manager');
        }

        return $product_type;
    }

    /**
     * overrides the woocommerce csv importer class
     * @return string
     */
    public function woocommerce_product_csv_importer_class() {
        include_once( dirname(__FILE__) . '/import/class-wcmp-frontend-product-manager-csv-importer.php' );
        return 'WCMP_Product_CSV_Importer';
    }

    /**
     * inserts data to database after import
     */
    function process_import($object, $data) {
        $user_id = get_current_user_id();
        $vendor = get_wcmp_vendor($user_id);
        $user = new WP_User($user_id);
        if ($vendor) {
            $term_id = get_user_meta($user_id, '_vendor_term_id', true);
            $term = get_term($term_id, 'dc_vendor_shop');
            wp_set_post_terms($object->get_id(), $term->name, 'dc_vendor_shop', false);

            if ($user->has_cap('is_published_product')) {
                $my_post = array(
                    'ID' => $object->get_id(),
                    'post_status' => 'pending',
                );

                // Update the post into the database
                wp_update_post($my_post);
                update_post($object->get_id(), 'vendor_username', $data['vendor_username']);
            }
        }
    }

    public function grid_custom_editor_register_tinymce_javascript($plugin_array) {
        unset($plugin_array['grid_custom_editor']);
        return $plugin_array;
    }

    function frontend_scripts() {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        $frontend_script_path = $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/js/';
        $frontend_script_path = str_replace(array('http:', 'https:'), '', $frontend_script_path);
        $pluginURL = str_replace(array('http:', 'https:'), '', $WCMp_Frontend_Product_Manager->plugin_url);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        // Product Manager Script 
        /* if(is_forntend_product_manager_page()) {  

          } */

        if (class_exists('woocommerce')) {
            wp_dequeue_style('select2');
            wp_deregister_style('select2');

            wp_dequeue_script('select2');
            wp_deregister_script('select2');
        }
        $WCMp->library->load_select2_lib();
        wp_enqueue_script('fpm_variable_js', $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/js/wcmp_fpm_variable.js', array('product_manager_js'), $WCMp_Frontend_Product_Manager->version, true);
        wp_enqueue_script('fpm_grouped_js', $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/js/wcmp_fpm_grouped.js', array('product_manager_js'), $WCMp_Frontend_Product_Manager->version, true);

        /* wp_register_style('wcmp-datatable1-bs-style', '//cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css');
          wp_register_script('wcmp-datatable1-script', '//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js', array('jquery'));
          wp_register_script('wcmp-datatable1-bs-script', '//cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js', array('jquery'));
          wp_enqueue_style( 'wcmp-datatable1-bs-style');
          wp_enqueue_script( 'wcmp-datatable1-script');
          wp_enqueue_script( 'wcmp-datatable1-bs-script'); */
        $WCMp->library->load_dataTable_lib();

        wp_enqueue_script('dc-product-export', $frontend_script_path . 'export.js', array('jquery', 'select2_js'), $WCMp_Frontend_Product_Manager->version, true);
        wp_register_script('dc-product-import', $frontend_script_path . 'import.js', array('jquery'), $WCMp_Frontend_Product_Manager->version, true);

        $endpoint = $WCMp->endpoints->get_current_endpoint();
        if ($endpoint == 'product-export') {
            wp_localize_script('dc-product-export', 'wc_product_export_params', array(
                'export_nonce' => wp_create_nonce('wc-product-export'), 'ajax_url' => WC()->ajax_url()
            ));
        }

        //Auction
        if (wcmp_forntend_manager_is_yithauction() || wcmp_forntend_manager_is_wcsauction()) {
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('wcmp_fpm_timepicker_js', $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/js/timepicker.js', array('jquery', 'jquery-ui-datepicker'), $WCMp_Frontend_Product_Manager->version, true);
        }

        if (wcmp_forntend_manager_is_yithauction()) {
            wp_enqueue_script('wcmp_fpm_yithauction_js', $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/js/wcmp_fpm_yithauction.js', array('jquery', 'product_manager_js', 'wcmp_fpm_timepicker_js', 'jquery-ui-datepicker'), $WCMp_Frontend_Product_Manager->version, true);
        }

        if (wcmp_forntend_manager_is_wcsauction()) {
            wp_enqueue_script('wcmp_fpm_wcsauction_js', $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/js/wcmp_fpm_wcsauction.js', array('jquery', 'product_manager_js', 'wcmp_fpm_timepicker_js', 'jquery-ui-datepicker'), $WCMp_Frontend_Product_Manager->version, true);
        }

        if (wcmp_forntend_manager_has_wcaddons()) {
            wp_enqueue_script('wcmp_fpm_wcaddons_js', $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/js/wcmp_fpm_wcaddons.js', array('jquery', 'product_manager_js'), $WCMp_Frontend_Product_Manager->version, true);
        }
        if (wcmp_forntend_manager_is_booking()) {
            wp_enqueue_script('wcmp_woocommerce_jquery_tiptip', plugins_url('assets/js/jquery-tiptip/jquery.tipTip.min.js', WC_PLUGIN_FILE), array('jquery'), WOOCOMMERCE_VERSION, true);
            wp_enqueue_script('wcmp_fpm_booking_js', $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/js/wcmp_fpm_wcbookings.js', array('jquery', 'product_manager_js'), $WCMp_Frontend_Product_Manager->version, true);
        }

        if (wcmp_forntend_manager_is_accommodation_booking()) {
            wp_enqueue_script('wcmp_accommodation_fpm_booking_js', $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/js/wcmp_fpm_wcaccommodationbookings.js', array('jquery', 'product_manager_js', 'wcmp_fpm_booking_js'), $WCMp_Frontend_Product_Manager->version, true);
        }

        if (wcmp_forntend_manager_is_rental()) {
            wp_enqueue_script('wcmp_fpm_rental_js', $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/js/wcmp_fpm_wcrental.js', array('jquery', 'product_manager_js'), $WCMp_Frontend_Product_Manager->version, true);
        }

        if (wcmp_forntend_manager_is_bundle()) {
            wp_enqueue_script('wcmp_fpm_bundle_js', $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/js/wcmp_fpm_wcbundle.js', array('jquery', 'product_manager_js'), $WCMp_Frontend_Product_Manager->version, true);
            $default_txt = __('No Default', 'wcmp-frontend_product_manager');
            wp_localize_script('wcmp_fpm_bundle_js', 'dc_frontend_ajax_url', array('ajax_url' => admin_url('admin-ajax.php')));
            wp_localize_script('wcmp_fpm_bundle_js', 'default_attribute', array('default_txt' => $default_txt));
        }

        if (wcmp_forntend_manager_is_subscription()) {
            wp_enqueue_script('wcmp_fpm_subscription_js', $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/js/wcmp_fpm_wcsubscriptions.js', array('jquery', 'product_manager_js'), $WCMp_Frontend_Product_Manager->version, true);
        }
    }

    function frontend_styles() {
        global $WCMp_Frontend_Product_Manager;
        $frontend_style_path = $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/css/';
        $frontend_style_path = str_replace(array('http:', 'https:'), '', $frontend_style_path);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';


        wp_enqueue_style('product_manager_edit_css', $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/css/product_manager.css', array(), $WCMp_Frontend_Product_Manager->version);
    }

    public function is_wcmp_vendor_dashboard($is_vendor_dashboard) {
        if (is_page((int) get_wcmp_vendor_settings('wcmp_pending_products', 'vendor', 'general'))) {
            $is_vendor_dashboard = true;
        }
        if (is_page((int) get_wcmp_vendor_settings('wcmp_coupons', 'vendor', 'general'))) {
            $is_vendor_dashboard = true;
        }
        return $is_vendor_dashboard;
    }

}
