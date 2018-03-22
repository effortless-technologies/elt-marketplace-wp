<?php
/**
 * WCMp Frontend Manager plugin views
 *
 * Plugin WC Product Bundle Views
 *
 * @author 		WC Marketplace
 * @package 	wcmp-frontend_product_manager/templates
 */
global $WCMp, $WCMp_Frontend_Product_Manager;
$bundles = array();
$product_id = 0;
if (!empty($pro_id)) {
    $product = wc_get_product((int) $pro_id);
    if ($product && !empty($product)) {
        $product_id = $product->get_id();
    }
}
$layoutoptions = array('default' => __('Standard', 'wcmp-frontend_product_manager'), 'tabular' => __('Tabular', 'wcmp-frontend_product_manager'));
if ($product_id != 0) {
    $choose_layout = get_post_meta($product_id, '_wc_pb_layout_style', true);
} else {
    $choose_layout = 'default';
}

$current_vendor_id = apply_filters('wcmp_current_loggedin_vendor_id', get_current_user_id());
$args = array(
    'posts_per_page' => -1,
    'post_type' => 'product',
    'post_status' => array('publish'),
    'suppress_filters' => true
);
if (current_user_can('dc_vendor') || current_user_can('vendor_staff')) {
    $args['author'] = $current_vendor_id;
}
$products_array = get_posts($args);
$prod_arr = array('' => __('Select One', 'wcmp-frontend_product_manager'));
if ($products_array) {
    foreach ($products_array as $products_single) {
        $bundle_product_object = wc_get_product(esc_attr($products_single->ID));
        if (!$bundle_product_object->is_type('bundle') && !$bundle_product_object->is_type('booking') && !$bundle_product_object->is_type('accommodation-booking')) {
            $prod_arr[esc_attr($products_single->ID)] = esc_html($products_single->post_title);
        }
    }
}



$value = array();
$sel_array = array();

if ($product_id) {

    $product = wc_get_product($product_id);
    if ($product && !empty($product)) {

        $bundleargs = array(
            'bundle_id' => $product_id,
            'return' => 'objects',
            'order_by' => array('menu_order' => 'ASC')
        );

        $data_items = WC_PB_DB::query_bundled_items($bundleargs);
        $bundle_class = new WCMp_Frontend_Product_Manager_WCBundle;

        if (!empty($data_items)) {
            foreach ($data_items as $data_item_key => $data_item) {
                $value[$data_item_key] = array(
                    'bundled_item_id' => $data_item->get_id(),
                    'product_id' => $data_item->get_product_id(),
                    'selected_product_id' => $data_item->get_product_id(),
                    'bundled_menu_order' => $data_item->get_menu_order(),
                    'quantity_min' => $data_item->get_meta('quantity_min'),
                    'quantity_max' => $data_item->get_meta('quantity_max'),
                    'shipped_individually' => $data_item->get_meta('shipped_individually'),
                    'priced_individually' => $data_item->get_meta('priced_individually'),
                    'override_title' => $data_item->get_meta('override_title'),
                    'title' => $data_item->get_meta('title'),
                    'override_description' => $data_item->get_meta('override_description'),
                    'description' => $data_item->get_meta('description'),
                    'optional' => $data_item->get_meta('optional'),
                    'hide_thumbnail' => $data_item->get_meta('hide_thumbnail'),
                    'discount' => $data_item->get_meta('discount'),
                    'override_variations' => $data_item->get_meta('override_variations'),
                    'allowed_variations' => (array) $data_item->get_meta('allowed_variations'),
                    'override_default_variation_attributes' => $data_item->get_meta('override_default_variation_attributes'),
                    'default_variation_attributes' => $bundle_class->dc_get_bundled_item_attribute_defaults($data_item),
                    'single_product_visibility' => $data_item->get_meta('single_product_visibility'),
                    'cart_visibility' => $data_item->get_meta('cart_visibility'),
                    'order_visibility' => $data_item->get_meta('order_visibility'),
                    'single_product_price_visibility' => $data_item->get_meta('single_product_price_visibility'),
                    'cart_price_visibility' => $data_item->get_meta('cart_price_visibility'),
                    'order_price_visibility' => $data_item->get_meta('order_price_visibility'),
                );
                $prod_id = $data_item->get_product_id();
                $variations = get_all_variations($prod_id);

                $sel_array[$data_item->get_id()]['selected_var'] = $data_item->get_meta('allowed_variations');
                $default_var[$data_item->get_id()] = $bundle_class->dc_get_bundled_item_attribute_defaults($data_item);

                foreach ($variations as $variation_id) {
                    $product_variation = wc_get_product($variation_id);
                    $var_title = wp_strip_all_tags($product_variation->get_formatted_name());
                    $sel_array[$data_item->get_id()]['all_var'][$variation_id] = $var_title;
                }
            }
            wp_localize_script('wcmp_fpm_bundle_js', 'variations_prod', array('var' => json_encode($sel_array), 'default' => json_encode($default_var)));
        }
    }
    $single_product_visibility_val = array();
} else {
    $value[] = array(
        'bundled_item_id' => '',
        'product_id' => '',
        'selected_product_id' => '',
        'bundled_menu_order' => '',
        'quantity_min' => 1,
        'quantity_max' => 1,
        'shipped_individually' => '',
        'priced_individually' => '',
        'override_title' => '',
        'title' => '',
        'override_description' => '',
        'description' => '',
        'optional' => '',
        'hide_thumbnail' => '',
        'discount' => '',
        'override_variations' => '',
        'allowed_variations' => '',
        'override_default_variation_attributes' => '',
        'default_variation_attributes' => array(),
        'single_product_visibility' => '',
        'cart_visibility' => '',
        'order_visibility' => '',
        'single_product_price_visibility' => '',
        'cart_price_visibility' => '',
        'order_price_visibility' => '',
    );
    $single_product_visibility_val = array('checked' => 'checked');
}
?>
<h3 class="pro_ele_head bundle"><?php _e('Bundled Products', 'wcmp-frontend_product_manager'); ?></h3>
<div class="pro_ele_block bundle">
    <p>
<?php
$WCMp_Frontend_Product_Manager->wcmp_wp_fields->wcmp_generate_form_field(array(
    "_wc_pb_layout_style" => array('label' => __('Layout', 'wcmp-frontend_product_manager'), 'type' => 'select', 'value' => $choose_layout, 'id' => '_wc_pb_layout_style', 'label_for' => '_wc_pb_layout_style', 'name' => '_wc_pb_layout_style', 'class' => '_wc_pb_layout_style regular-select pro_ele bundle', 'label_class' => 'pro_title bundle', 'options' => $layoutoptions, 'hints' => __('Select the Tabular option to have the thumbnails, descriptions and quantities of bundled products arranged in a table. Recommended for displaying multiple bundled products with configurable quantities.', 'wcmp-frontend_product_manager')),
));
?>
    </p>
    <p>
<?php
$WCMp_Frontend_Product_Manager->wcmp_wp_fields->wcmp_generate_form_field(array(
    "bundle_data" => array('label' => __('Add Products in bundle', 'wcmp-frontend_product_manager'), 'type' => 'multiinput', 'value' => $value, 'class' => 'regular-text pro_ele bundle', 'label_class' => 'pro_title bundle to_fix_width full', 'desc' => 'Please select a simple product, a variable product, or a simple/variable subscription.', 'options' => array(
            "product_id" => array('label' => __('Product Name', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $prod_arr, 'class' => ' regular-text pro_ele  bundle every_input_field bundle_item pro_name', 'label_for' => '', 'id' => '', 'name' => 'product_id', 'label_class' => 'pro_title pro_ele bundle', 'hints' => __('Select a product and add it to this bundle by clicking its name in the results list.', 'wcmp-frontend_product_manager')),
            "override_variations" => array('label' => __('Filter Variations', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'hints' => __('Check to enable only a subset of the available variations.', 'wcmp-frontend_product_manager'), 'wrapper_class' => 'pro_ele bundle filter_variation hide_variation', 'class' => 'regular-checkbox pro_ele bundle checkbox filter_variation ovrride_var_chkbx hide_variation override_variations_checkbox', 'value' => 'yes', 'label_class' => 'pro_title pro_ele checkbox_title bundle ovrride_var_chkbx filter_variation to_fix_width hide_variation', 'dfvalue' => 0),
            "bundled_item_id" => array('type' => 'hidden', 'class' => "bundled_item_id"),
            "selected_product_id" => array('type' => 'hidden', 'class' => "selected_product_id"),
            "bundled_menu_order" => array('type' => 'hidden', 'class' => "bundled_menu_order"),
            "allowed_variations" => array('label' => __('', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => array(), 'class' => 'regular-select pro_ele bundle bundle_dynamic_field every_input_field hide_variation allowed_variations', 'wrapper_class' => 'pro_ele bundle allowed_variations', 'label_class' => 'pro_title pro_ele bundle to_fix_width hide_variation lbl_select allowed_variations', 'attributes' => array('multiple' => 'multiple'),),
            "override_default_variation_attributes" => array('label' => __('Override Default Selections', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'hints' => __('In effect for this bundle only. The available options are in sync with the filtering settings above. Always save any changes made above before configuring this section.', 'wcmp-frontend_product_manager'), 'wrapper_class' => 'pro_ele bundle filter_variation hide_variation', 'class' => 'regular-checkbox pro_ele bundle checkbox filter_variation dflt_atts hide_variation override_default_variation_attribute_checkbox', 'value' => 'yes', 'label_class' => 'pro_title pro_ele checkbox_title bundle dflt_atts filter_variation hide_variation to_fix_width ', 'dfvalue' => 0),
            "default_variation_attributes" => array('label' => __('', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => array(), 'class' => 'regular-select form-control  pro_ele bundle every_input_field hide_variation default_ovrride_attr multiSelectBox-right variation_attr_hide dflt_items_datas ', 'label_class' => 'pro_title pro_ele bundle hide_variation variation_attr_hide default_ovrride_attr'),
            "optional" => array('label' => __('Optional', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'hints' => __('Check this option to mark the bundled product as optional.', 'wcmp-frontend_product_manager'), 'class' => 'regular-checkbox pro_ele bundle ', 'value' => 'yes', 'label_class' => 'pro_title pro_ele checkbox_title bundle'),
            "quantity_min" => array('label' => __('Quantity Min', 'wcmp-frontend_product_manager'), 'type' => 'number', 'value' => 1, 'attributes' => array('min' => 0), 'class' => 'regular-text pro_ele bundle bundle_quantity_min', 'label_class' => 'pro_ele pro_title bundle'),
            "quantity_max" => array('label' => __('Quantity Max', 'wcmp-frontend_product_manager'), 'type' => 'number', 'value' => 1, 'attributes' => array('min' => 0), 'class' => 'regular-text pro_ele bundle bundle_quantity_max', 'label_class' => 'pro_ele pro_title bundle '),
            "shipped_individually" => array('label' => __('Shipped Individually', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'hints' => __('Check this option if this bundled item is shipped separately from the bundle.', 'wcmp-frontend_product_manager'), 'class' => 'regular-checkbox pro_ele bundle ', 'value' => 'yes', 'label_class' => 'pro_title pro_ele checkbox_title bundle'),
            "priced_individually" => array('label' => __('Priced Individually', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'hints' => __('Check this option to have the price of this bundled item added to the base price of the bundle.', 'wcmp-frontend_product_manager'), 'class' => 'regular-checkbox pro_ele bundle checkbox priced_individually_checkbox ', 'value' => 'yes', 'label_class' => 'pro_title pro_ele checkbox_title bundle', 'dfvalue' => 0),
            "discount" => array('label' => __('Discount %', 'wcmp-frontend_product_manager'), 'type' => 'text', 'wrapper_class' => 'pro_ele bundle dependscheckbox', 'class' => 'regular-text pro_ele bundle discount_field dependscheckbox every_input_field hide_visiblity', 'hints' => __('Discount applied to the regular price of this bundled product when Priced Individually is checked. If a Discount is applied to a bundled product which has a sale price defined, the sale price will be overridden.', 'wcmp-frontend_product_manager'), 'label_class' => 'pro_title pro_ele checkbox_title bundle dependscheckbox hide_visiblity'),
            "single_product_visibility" => array('label' => __('Visibility Of Product details', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'hints' => __('Controls the visibility of the bundled item in the single-product template of this bundle.', 'wcmp-frontend_product_manager'), 'class' => 'regular-checkbox pro_ele bundle checkbox visibility_product checked_on_load', 'value' => 'visible', 'label_class' => 'pro_title pro_ele checkbox_title bundle', 'dfvalue' => 1, 'custom_tags' => $single_product_visibility_val),
            "cart_visibility" => array('label' => __('Visibility Of Cart/checkout', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'hints' => __('Controls the visibility of the bundled item in cart/checkout templates.', 'wcmp-frontend_product_manager'), 'class' => 'regular-checkbox pro_ele bundle checkbox visibility_cart checked_on_load', 'value' => 'visible', 'label_class' => 'pro_title pro_ele checkbox_title bundle', 'dfvalue' => 1, 'custom_tags' => $single_product_visibility_val),
            "order_visibility" => array('label' => __('Visibility Of Order details', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'hints' => 'Controls the visibility of the bundled item in order details & e-mail templates.', 'class' => 'regular-checkbox pro_ele bundle checkbox visibility_order checked_on_load', 'value' => 'visible', 'label_class' => 'pro_title pro_ele checkbox_title bundle', 'dfvalue' => 1, 'custom_tags' => $single_product_visibility_val),
            "single_product_price_visibility" => array('label' => __('Price Visibility Of Product details', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'hints' => __('Controls the visibility of the bundled-item price in the single-product template of this bundle.', 'wcmp-frontend_product_manager'), 'wrapper_class' => 'pro_ele bundle dependscheckbox', 'class' => 'regular-checkbox pro_ele bundle price_visibility_product dependscheckbox checked_on_load hide_visiblity', 'value' => 'visible', 'label_class' => 'pro_title pro_ele checkbox_title bundle dependscheckbox hide_visiblity', 'dfvalue' => 1, 'custom_tags' => $single_product_visibility_val),
            "cart_price_visibility" => array('label' => __('Price Visibility Of Cart/checkout', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'hints' => __('Controls the visibility of the bundled-item price in cart/checkout templates.', 'wcmp-frontend_product_manager'), 'wrapper_class' => 'pro_ele bundle dependscheckbox', 'class' => 'regular-checkbox pro_ele bundle checkbox price_visibility_cart dependscheckbox checked_on_load hide_visiblity', 'value' => 'visible', 'label_class' => 'pro_title pro_ele checkbox_title bundle dependscheckbox hide_visiblity', 'dfvalue' => 1, 'custom_tags' => $single_product_visibility_val),
            "order_price_visibility" => array('label' => __('Price Visibility Of Order details', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'hints' => 'Controls the visibility of the bundled-item price in order details & e-mail templates.', 'wrapper_class' => 'pro_ele bundle dependscheckbox', 'class' => 'regular-checkbox pro_ele bundle checkbox price_visibility_order dependscheckbox checked_on_load hide_visiblity', 'value' => 'visible', 'label_class' => 'pro_title pro_ele checkbox_title bundle dependscheckbox hide_visiblity', 'dfvalue' => 1, 'custom_tags' => $single_product_visibility_val),
            "hide_thumbnail" => array('label' => __('Hide Thumbnail', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'hints' => __('Check this option to hide the thumbnail image of this bundled product.', 'wcmp-frontend_product_manager'), 'class' => 'regular-checkbox  checked_on_load_product_des', 'wrapper_class' => 'pro_ele bundle dependsonproductdetails', 'value' => 'yes', 'label_class' => 'pro_title pro_ele checkbox_title bundle checked_on_load_product_des', 'dfvalue' => 0),
            "override_title" => array('label' => __('Override Title', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'hints' => __('Check this option to override the default product title.', 'wcmp-frontend_product_manager'), 'wrapper_class' => 'pro_ele bundle dependsonproductdetails', 'class' => 'regular-checkbox  bundle override_title_check  checked_on_load_product_des', 'value' => 'yes', 'label_class' => 'pro_title  checkbox_title bundle  checked_on_load_product_des', 'dfvalue' => 0),
            "title" => array('label' => __('', 'wcmp-frontend_product_manager'), 'type' => 'textarea', 'wrapper_class' => 'override_title_feild', 'class' => 'regular-checkbox pro_ele bundle override_title_feild hide_variation to_fix_width multiple_dependency', 'label_class' => 'pro_title pro_ele bundle override_title_feild hide_variation multiple_dependency'),
            "override_description" => array('label' => __('Override Short Description', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'hints' => __('Check this option to override the default short product description.', 'wcmp-frontend_product_manager'), 'wrapper_class' => 'pro_ele bundle dependsonproductdetails', 'class' => 'regular-checkbox  bundle override_description_check  checked_on_load_product_des', 'value' => 'yes', 'label_class' => 'pro_title pro_ele checkbox_title bundle  checked_on_load_product_des', 'dfvalue' => 0),
            "description" => array('label' => __('', 'wcmp-frontend_product_manager'), 'type' => 'textarea', 'wrapper_class' => 'override_description_feild', 'class' => 'regular-checkbox pro_ele bundle override_description_feild hide_variation to_fix_width multiple_dependency', 'label_class' => 'pro_title pro_ele bundle override_description_feild hide_variation multiple_dependency'),
        ))
));
?>
    </p>
</div>