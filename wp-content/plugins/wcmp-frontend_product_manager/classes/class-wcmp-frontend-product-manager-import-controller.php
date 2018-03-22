<?php

if (!class_exists('WC_Product_CSV_Importer_Controller')) {
    include_once( WC_ABSPATH . 'includes/admin/importers/class-wc-product-csv-importer-controller.php' );
}

class WCMP_Product_Import_Export_Bundle_Import_Controller extends WC_Product_CSV_Importer_Controller {

    function __construct() {
        global $WCMp;
        parent::__construct();
        $vendor_can = $WCMp->vendor_caps->vendor_can('bundle');

        if (wcmp_forntend_manager_is_bundle() && $vendor_can) {
            add_filter('woocommerce_csv_product_import_mapping_options', array(&$this, 'dc_map_columns'));
            add_filter('woocommerce_csv_product_import_mapping_default_columns', array(&$this, 'dc_add_columns_to_mapping_screen'));
        }

        add_filter( 'woocommerce_product_importer_parsed_data', array( &$this, 'wcmp_product_importer_parsed_data' ), 10, 2 );
        add_filter( 'woocommerce_product_import_pre_insert_product_object', array( &$this, 'wcmp_import_pre_insert_product_object' ), 10, 2 );
        // Set bundle-type props.
        //add_filter( 'woocommerce_product_import_pre_insert_product_object', array( &$this, 'dc_set_bundle_props' ), 10, 2 );

    }

    protected function mapping_form() {
        $args = array(
            'lines' => 1,
            'delimiter' => $this->delimiter,
        );

        $importer = self::get_importer($this->file, $args);
        $headers = $importer->get_raw_keys();
        $mapped_items = $this->auto_map_columns($headers);
        $sample = current($importer->get_raw_data());
        if (($key = array_search('Vendor Username', $mapped_items)) !== false) {
            unset($mapped_items[$key]);
            unset($headers[$key]);
        }
        if (($key = array_search('Fixed Commission', $mapped_items)) !== false) {
            unset($mapped_items[$key]);
            unset($headers[$key]);
        }
        if (($key = array_search('Commission Percentage', $mapped_items)) !== false) {
            unset($mapped_items[$key]);
            unset($headers[$key]);
        }
        if (($key = array_search('Fixed Width Percentage', $mapped_items)) !== false) {
            unset($mapped_items[$key]);
            unset($headers[$key]);
        }
        if (($key = array_search('Fixed Width Percent Quantity', $mapped_items)) !== false) {
            unset($mapped_items[$key]);
            unset($headers[$key]);
        }
        if (empty($sample)) {
            $this->add_error(__('The file is empty, please try again with a new file.', 'wcmp-frontend_product_manager'));
            return;
        }
        include_once( WC_ABSPATH . 'includes/admin/importers/views/html-csv-import-mapping.php' );
    }

    public static function dc_map_columns($options) {

        $options['wc_pb_bundled_items'] = __('Bundled Items (JSON-encoded)', 'wcmp-frontend_product_manager');
        $options['wc_pb_layout'] = __('Bundle Layout', 'wcmp-frontend_product_manager');
        $options['wc_pb_group_mode'] = __('Bundle Group Mode', 'wcmp-frontend_product_manager');
        $options['wc_pb_editable_in_cart'] = __('Bundle Cart Editing', 'wcmp-frontend_product_manager');
        $options['wc_pb_sold_individually_context'] = __('Bundle Sold Individually', 'wcmp-frontend_product_manager');

        return $options;
    }

    public static function dc_add_columns_to_mapping_screen($columns) {

        $columns[__('Bundled Items (JSON-encoded)', 'wcmp-frontend_product_manager')] = 'wc_pb_bundled_items';
        $columns[__('Bundle Layout', 'wcmp-frontend_product_manager')] = 'wc_pb_layout';
        $columns[__('Bundle Group Mode', 'wcmp-frontend_product_manager')] = 'wc_pb_group_mode';
        $columns[__('Bundle Cart Editing', 'wcmp-frontend_product_manager')] = 'wc_pb_editable_in_cart';
        $columns[__('Bundle Sold Individually', 'wcmp-frontend_product_manager')] = 'wc_pb_sold_individually_context';

        // Always add English mappings.
        $columns['Bundled Items (JSON-encoded)'] = 'wc_pb_bundled_items';
        $columns['Bundle Layout'] = 'wc_pb_layout';
        $columns['Bundle Group Mode'] = 'wc_pb_group_mode';
        $columns['Bundle Cart Editing'] = 'wc_pb_editable_in_cart';
        $columns['Bundle Sold Individually'] = 'wc_pb_sold_individually_context';

        return $columns;
    }

    /**
     * Decode bundled data items and parse relative IDs.
     *
     * @param  array                    $parsed_data
     * @param  WC_Product_CSV_Importer  $importer
     * @return array
     */
    public static function wcmp_product_importer_parsed_data( $parsed_data, $importer) {
        global $WCMp;
//        if (!empty($parsed_data['wc_pb_bundled_items'])) {
//            $bundled_data_items = json_decode($parsed_data['wc_pb_bundled_items'], true);
//            unset($parsed_data['wc_pb_bundled_items']);
//            if (is_array($bundled_data_items)) {
//                $parsed_data['wc_pb_bundled_items'] = array();
//                foreach ($bundled_data_items as $bundled_data_item_key => $bundled_data_item) {
//                    $bundled_product_id = $bundled_data_items[$bundled_data_item_key]['product_id'];
//                    $parsed_data['wc_pb_bundled_items'][$bundled_data_item_key] = $bundled_data_item;
//                    $parsed_data['wc_pb_bundled_items'][$bundled_data_item_key]['product_id'] = $importer->parse_relative_field($bundled_product_id);
//                }
//            }
//        }
        if(is_user_wcmp_vendor(get_current_user_id())){
            if(!$WCMp->vendor_caps->vendor_can('is_published_product')){
                $parsed_data['status'] = 0;
            }
            if(!$WCMp->vendor_caps->vendor_can($parsed_data['type'])){
                $parsed_data['type'] = 'simple';
            }
            if(!$WCMp->vendor_caps->vendor_can('is_upload_files')){
                $parsed_data['raw_image_id'] = '';
                $parsed_data['raw_gallery_image_ids'] = array();
            }
        }
        return $parsed_data;
    }
    
    public function wcmp_import_pre_insert_product_object($object, $data){
        global $WCMp;
        if(is_user_wcmp_vendor(get_current_user_id())){
            if(!$WCMp->vendor_caps->vendor_can('is_published_product')){
                $object->set_status('draft');
            }
        }
        return $object;
    }

    /**
     * Set bundle-type props.
     *
     * @param  array  $parsed_data
     * @return array
     */
    /* public static function dc_set_bundle_props( $product, $data ) {

      if ( is_a( $product, 'WC_Product' ) && $product->is_type( 'bundle' ) ) {

      $bundled_data_items = ! empty( $data[ 'wc_pb_bundled_items' ] ) ? $data[ 'wc_pb_bundled_items' ] : array();

      $props = array(
      'editable_in_cart'          => isset( $data[ 'wc_pb_editable_in_cart' ] ) && 1 === intval( $data[ 'wc_pb_editable_in_cart' ] ) ? 'yes' : 'no',
      'layout'                    => isset( $data[ 'wc_pb_layout' ] ) ? $data[ 'wc_pb_layout' ] : 'default',
      'group_mode'                => isset( $data[ 'wc_pb_group_mode' ] ) ? $data[ 'wc_pb_group_mode' ] : 'parent',
      'sold_individually_context' => isset( $data[ 'sold_individually_context' ] ) ? $data[ 'sold_individually_context' ] : 'product',
      'bundled_data_items'        => $bundled_data_items
      );

      $product->set_props( $props );
      }

      return $product;
      } */

    public function upload_form_handler() {
        global $WCMp, $WCMP_Product_Import_Export_Bundle;
        wp_verify_nonce('woocommerce-csv-importer');
        $file = $this->handle_upload();
        if (is_wp_error($file)) {
            $this->add_error($file->get_error_message());
            return;
        } else {
            $this->file = $file;
        }
        $this->wcmp_exoprt_redirect(esc_url_raw($this->get_next_step_link()));
    }

    public function wcmp_exoprt_redirect($link) {
        wp_add_inline_script('wcmp_new_vandor_dashboard_js', 'jQuery(document).ready(function (){ window.location.href =  "' . $link . '"});');
    }

    protected function done() {
        $imported = isset($_GET['products-imported']) ? absint($_GET['products-imported']) : 0;
        $updated = isset($_GET['products-updated']) ? absint($_GET['products-updated']) : 0;
        $failed = isset($_GET['products-failed']) ? absint($_GET['products-failed']) : 0;
        $skipped = isset($_GET['products-skipped']) ? absint($_GET['products-skipped']) : 0;
        $errors = array_filter((array) get_user_option('product_import_error_log'));

        include_once( dirname(__FILE__) . '/../templates/html-csv-import-done.php' );
    }

    public function get_next_step_link($step = '') {
        global $WCMp;
        if (!$step) {
            $step = $this->step;
        }

        $keys = array_keys($this->steps);

        if (end($keys) === $step) {

            return admin_url();
        }

        $step_index = array_search($step, $keys);

        if (false === $step_index) {
            return '';
        }

        $params = array(
            'step' => $keys[$step_index + 1],
            'file' => str_replace(DIRECTORY_SEPARATOR, '/', $this->file),
            'delimiter' => $this->delimiter,
            'update_existing' => $this->update_existing,
            '_wpnonce' => wp_create_nonce('woocommerce-csv-importer'),
        );
        $endpoint = wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_product_import_endpoint', 'vendor', 'general', 'product-import'));
        $new_query = add_query_arg($params, $endpoint);
        return $new_query;
    }

    public function import() {
        global $WCMP_Product_Import_Export_Bundle;
        if (!is_file($this->file)) {
            $this->add_error(__('The file does not exist, please try again.', 'wcmp-frontend_product_manager'));
            return;
        }

        if (!empty($_POST['map_to'])) {
            $mapping_from = wp_unslash($_POST['map_from']);
            $mapping_to = wp_unslash($_POST['map_to']);
        } else {
            wp_redirect(esc_url_raw($this->get_next_step_link('upload')));
            exit;
        }
        wp_localize_script('dc-product-import', 'wc_product_import_params', array(
            'import_nonce' => wp_create_nonce('wc-product-import'), 'ajax_url' => admin_url('admin-ajax.php'),
            'mapping' => array(
                'from' => $mapping_from,
                'to' => $mapping_to,
            ),
            'file' => $this->file,
            'update_existing' => $this->update_existing,
            'delimiter' => $this->delimiter,
        ));
        wp_enqueue_script('dc-product-import');
        include_once( WC()->plugin_path() . '/includes/admin/importers/views/html-csv-import-progress.php' );
    }

}
