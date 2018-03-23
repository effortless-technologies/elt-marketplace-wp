<?php

/**
 * WCMp Frontend Manager plugin core
 * WC Grouped Product Support
 */
class WCMp_Frontend_Product_Manager_Grouped extends WC_REST_Products_Controller {

    public function __construct() {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        add_filter('wcmp_product_types', array(&$this, 'wcgrouped_product_types'), 20);

        //product grouped add
        add_action('after_wcmp_fpm_attributes', array(&$this, 'wcgrouped_tab_add_callback'));

        //product save
        add_action('after_wcmp_fpm_data_meta_save', array(&$this, 'wcgrouped_datas_save'), 15, 3);
    }

    /**
     * WC Grouped Product Type
     */
    function wcgrouped_product_types($product_types) {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        $current_user_id = apply_filters('wcmp_current_loggedin_vendor_id', get_current_user_id());
        if (is_user_wcmp_vendor($current_user_id) || current_user_can('administrator') || current_user_can('shop_manager')) {
            $product_types['grouped'] = __('Grouped', 'wcmp-frontend_product_manager');
        }

        return $product_types;
    }

    /**
     * WC Product Grouped tab add
     */
    function wcgrouped_tab_add_callback() {
        global $WCMp, $WCMp_Frontend_Product_Manager, $wp;
        //require_once( $WCMp_Frontend_Product_Manager->plugin_path . 'templates/wcmp-fpm-view-wcgrouped.php' );
        $pro_id = $wp->query_vars[get_wcmp_vendor_settings('wcmp_add_product_endpoint', 'vendor', 'general', 'add-product')];
        $WCMp_Frontend_Product_Manager->template->get_template('wcmp-fpm-view-wcgrouped.php', array('pro_id' => $pro_id));
    }

    /**
     * Grouprd Product Save
     */
    function wcgrouped_datas_save($new_product_id, $product_manager_form_data, $pro_attributes) {
        /* print_r($new_product_id);
          print_r($product_manager_form_data);die; */

        $product_type = empty($product_manager_form_data['product_type']) ? WC_Product_Factory::get_product_type($new_product_id) : sanitize_title(stripslashes($product_manager_form_data['product_type']));
        $classname = WC_Product_Factory::get_product_classname($new_product_id, $product_type ? $product_type : 'simple');
        //print_r($classname);die;
        $product = new $classname($new_product_id);

        $product_type = $product_manager_form_data['product_type'];
        // Group Products
        $grouped_products = isset($product_manager_form_data['children']) ? array_filter(array_map('intval', (array) $product_manager_form_data['children'])) : array();

        if ($product_type == 'grouped') {
            /* sanitize_title( stripslashes( $product_manager_form_data['product_type'] ) ); */
            $errors = $product->set_props(array(
                'children' => $grouped_products
                    ));
            //$product->set_children( $grouped_products );
            //print_r($product);die;
            $product->save();

            //update_post_meta($new_product_id,'_children',$grouped_products);
        }
    }

}
