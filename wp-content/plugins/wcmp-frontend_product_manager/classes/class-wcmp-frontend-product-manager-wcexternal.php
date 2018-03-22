<?php

/**
 * WCMp Frontend Manager plugin core
 * WC Grouped Product Support
 */
class WCMp_Frontend_Product_Manager_External extends WC_REST_Products_Controller {

    public function __construct() {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        add_filter('wcmp_product_types', array(&$this, 'wcexternal_product_types'), 20);

        //product external add
        add_filter('wcmp_fpm_fields_general', array(&$this, 'wcexternal_product_manage_fields_general'), 10, 2);

        //product save
        add_action('after_wcmp_fpm_data_meta_save', array(&$this, 'wcexternal_datas_save'), 15, 3);
    }

    /**
     * WC Grouped Product Type
     */
    function wcexternal_product_types($product_types) {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        $current_user_id = apply_filters('wcmp_current_loggedin_vendor_id', get_current_user_id());
        if (is_user_wcmp_vendor($current_user_id) || current_user_can('administrator') || current_user_can('shop_manager')) {
            $product_types['external'] = __('External/Affiliate product', 'wcmp-frontend_product_manager');
        }

        return $product_types;
    }

    /**
     * WC Product external feild add
     */
    function wcexternal_product_manage_fields_general($general_fields, $product_id) {
        global $WCMp, $WCMp_Frontend_Product_Manager, $wp;

        $product_url = '';
        $button_text = '';

        $pro_id = $wp->query_vars[get_wcmp_vendor_settings('wcmp_add_product_endpoint', 'vendor', 'general', 'add-product')];

        if (isset($pro_id)) {
            $product = wc_get_product($pro_id);
            if ($product && !empty($product)) {
                $product_id = $pro_id;
                // External product option
                $product_url = get_post_meta($product_id, '_product_url', true);
                $button_text = get_post_meta($product_id, '_button_text', true);
            }
        }

        $general_fields = array_slice($general_fields, 0, 1, true) + array("product_url" => array('label' => __('Product URL', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text', 'wrapper_class' => 'external pro_ele', 'label_class' => 'pro_ele pro_title external', 'value' => $product_url, 'hints' => __('Enter the external URL to the product.', 'woocommerce')),
            "button_text" => array('label' => __('Button Text', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text', 'label_class' => 'pro_ele pro_title external', 'wrapper_class' => 'external pro_ele', 'value' => $button_text, 'hints' => __('This text will be shown on the button linking to the external product.', 'woocommerce')),
                ) + array_slice($general_fields, 1, count($general_fields) - 1, true);


        return $general_fields;
    }

    /**
     * Grouprd Product Save
     */
    function wcexternal_datas_save($new_product_id, $product_manager_form_data, $pro_attributes) {

        $product_type = empty($product_manager_form_data['product_type']) ? WC_Product_Factory::get_product_type($new_product_id) : sanitize_title(stripslashes($product_manager_form_data['product_type']));
        $classname = WC_Product_Factory::get_product_classname($new_product_id, $product_type ? $product_type : 'simple');
        //print_r($classname);die;
        $product = new $classname($new_product_id);
        if ($product_type == 'external') {

            $errors = $product->set_props(array(
                'product_url' => esc_url_raw($product_manager_form_data['product_url']),
                'button_text' => wc_clean($product_manager_form_data['button_text'])
                    ));

            $product->save();
        }
    }

}
