<?php
/**
 * WCMp Frontend Manager plugin views
 *
 * Plugin WC Variable Product Views
 *
 * @author 		WC Marketplace
 * @package 	wcmp-frontend_product_manager/templates
 */
global $WCMp, $WCMp_Frontend_Product_Manager;
$product_id = 0;
$product = array();
$product_type = '';
$default_attributes = '';
$attributes_select_type = array();
$variations = array();
$is_vendor = false;
$current_user_id = apply_filters('wcmp_current_loggedin_vendor_id', get_current_user_id());
if (is_user_wcmp_vendor($current_user_id))
    $is_vendor = true;
if (!empty($pro_id)) {
    $product = wc_get_product((int) $pro_id);

    $product_type = $product->get_type();
    // Fetching Product Data
    if ($product && !empty($product)) {
        $product_id = $product->get_id();

        $vendor_data = get_wcmp_product_vendors($product_id);
        $product_type = $product->get_type();
    }
    $variation_ids = $product->get_children('edit');
}

// Product Default Attributes
$default_attributes = json_encode((array) get_post_meta($product_id, '_default_attributes', true));
if ($product && !empty($product)) {
    if (( $product_type == 'variable' ) || ( $product_type == 'variable-subscription' )) {
        if (!empty($variation_ids)) {
            foreach ($variation_ids as $variation_id_key => $variation_id) {
                $variation_data = new WC_Product_Variation($variation_id);

                $variations[$variation_id_key]['id'] = $variation_id;
                $variations[$variation_id_key]['enable'] = $variation_data->is_purchasable() ? 'enable' : '';
                $variations[$variation_id_key]['sku'] = get_post_meta($variation_id, '_sku', true);

                // Variation Image
                $variation_img = $variation_data->get_image_id();
                if ($variation_img)
                    $variation_img = wp_get_attachment_url($variation_img);
                else
                    $variation_img = '';
                $variations[$variation_id_key]['image'] = $variation_img;

                // Variation Price
                $variations[$variation_id_key]['regular_price'] = get_post_meta($variation_id, '_regular_price', true);
                $variations[$variation_id_key]['sale_price'] = get_post_meta($variation_id, '_sale_price', true);

                // Variation Stock Data
                $variations[$variation_id_key]['manage_stock'] = $variation_data->managing_stock() ? 'enable' : '';
                $variations[$variation_id_key]['stock_status'] = $variation_data->get_stock_status();
                $variations[$variation_id_key]['stock_qty'] = $variation_data->get_stock_quantity();
                $variations[$variation_id_key]['backorders'] = $variation_data->get_backorders();

                // Variation Virtual Data
                $variations[$variation_id_key]['is_virtual'] = ( 'yes' == get_post_meta($variation_id, '_virtual', true) ) ? 'enable' : '';

                // Variation Downloadable Data
                $variations[$variation_id_key]['is_downloadable'] = ( 'yes' == get_post_meta($variation_id, '_downloadable', true) ) ? 'enable' : '';
                $variations[$variation_id_key]['downloadable_files'] = get_post_meta($variation_id, '_downloadable_files', true);
                $variations[$variation_id_key]['download_limit'] = get_post_meta($variation_id, '_download_limit', true);
                $variations[$variation_id_key]['download_expiry'] = get_post_meta($variation_id, '_download_expiry', true);
                if (!empty($variations[$variation_id_key]['downloadable_files'])) {
                    foreach ($variations[$variation_id_key]['downloadable_files'] as $variations_downloadable_files) {
                        $variations[$variation_id_key]['downloadable_file'] = $variations_downloadable_files['file'];
                        $variations[$variation_id_key]['downloadable_file_name'] = $variations_downloadable_files['name'];
                    }
                }

                // Variation Shipping Data
                $variations[$variation_id_key]['weight'] = $variation_data->get_weight();
                $variations[$variation_id_key]['length'] = $variation_data->get_length();
                $variations[$variation_id_key]['width'] = $variation_data->get_width();
                $variations[$variation_id_key]['height'] = $variation_data->get_height();
                $variations[$variation_id_key]['shipping_class'] = $variation_data->get_shipping_class_id();

                // Variation Tax
                $variations[$variation_id_key]['tax_class'] = $variation_data->get_tax_class();

                // Variation Attributes
                $variations[$variation_id_key]['attributes'] = json_encode($variation_data->get_variation_attributes());

                // Description
                $variations[$variation_id_key]['description'] = get_post_meta($variation_id, '_variation_description', true);

                $variations = apply_filters('wcmp_fpm_variation_edit_data', $variations, $variation_id, $variation_id_key);
            }
        }
    }
}

// Shipping Class List
$product_shipping_class = get_terms('product_shipping_class', array('hide_empty' => 0));
$variation_shipping_option_array = array('-1' => __('Same as parent', 'wcmp-frontend_product_manager'));
$shipping_option_array = array('_no_shipping_class' => __('No shipping class', 'wcmp-frontend_product_manager'));
foreach ($product_shipping_class as $product_shipping) {
    if ($is_vendor) {
        $vendor_id = get_woocommerce_term_meta($product_shipping->term_id, 'vendor_id', true);
        if (!$vendor_id) {
            
        } else {
            if ($vendor_id == $current_user_id) {
                $variation_shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
                $shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
            }
        }
    } else {
        $variation_shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
        $shipping_option_array[$product_shipping->term_id] = $product_shipping->name;
    }
}

// Tax Class List
$tax_classes = WC_Tax::get_tax_classes();
$classes_options = array();
$variation_tax_classes_options['parent'] = __('Same as parent', 'wcmp-frontend_product_manager');
$variation_tax_classes_options[''] = __('Standard', 'wcmp-frontend_product_manager');
$tax_classes_options[''] = __('Standard', 'wcmp-frontend_product_manager');

if (!empty($tax_classes)) {

    foreach ($tax_classes as $class) {
        $tax_classes_options[sanitize_title($class)] = esc_html($class);
        $variation_tax_classes_options[sanitize_title($class)] = esc_html($class);
    }
}
?>
<h3 class="pro_ele_head variable variations variable-subscription"><?php _e('Variations', 'wcmp-frontend_product_manager'); ?></h3>
<div class="pro_ele_block variable variable-subscription">

    <div class="form-group default_attributes_holder">
        <label class="pro_title selectbox_title control-label dflt_frm_value_lbl pro_ele_hide col-sm-3"><?php _e('Default Form Values:', 'wcmp-frontend_product_manager'); ?></label>
        <input type="hidden" name="default_attributes_hidden" data-name="default_attributes_hidden" value="<?php echo esc_attr($default_attributes); ?>" />
    </div>

    <?php
    $WCMp_Frontend_Product_Manager->wcmp_wp_fields->wcmp_generate_form_field(array(
        "variations_options" => array('label' => __('Variations Options', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => array('' => __('Choose option', 'wcmp-frontend_product_manager'), 'set_regular_price' => __('Set regular prices', 'wcmp-frontend_product_manager'), 'regular_price_increase' => __('Regular price increase', 'wcmp-frontend_product_manager'), 'regular_price_decrease' => __('Regular price decrease', 'wcmp-frontend_product_manager'), 'set_sale_price' => __('Set sale prices', 'wcmp-frontend_product_manager'), 'sale_price_increase' => __('Sale price increase', 'wcmp-frontend_product_manager'), 'sale_price_decrease' => __('Sale price decrease', 'wcmp-frontend_product_manager')), 'class' => 'regular-select pro_ele variable-subscription variable', 'label_class' => 'pro_title'),
        "variations" => array('label' => __('Variations', 'wcmp-frontend_product_manager'), 'type' => 'multiinput', 'class' => 'pro_ele variable variable-subscription', 'label_class' => 'pro_title', 'value' => $variations, 'options' => apply_filters('wcmp_fpm_manage_fields_variations', array(
                "id" => array('type' => 'hidden', 'class' => 'variation_id'),
                "enable" => array('label' => __('Enable', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'regular-checkbox pro_ele variable-subscription variable', 'label_class' => 'pro_title checkbox_title'),
                "is_virtual" => array('label' => __('Virtual', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => (is_user_wcmp_vendor($current_user_id) && !$WCMp->vendor_caps->vendor_can('virtual')) ? 'regular-checkbox pro_ele variable-subscription variable variation_is_virtual_ele vendor_hidden' : 'regular-checkbox pro_ele variable-subscription variable variation_is_virtual_ele', 'label_class' => (is_user_wcmp_vendor($current_user_id) && !$WCMp->vendor_caps->vendor_can('virtual')) ? 'pro_title checkbox_title vendor_hidden' : 'pro_title checkbox_title'),
                "is_downloadable" => array('label' => __('Downloadable', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => (is_user_wcmp_vendor($current_user_id) && !$WCMp->vendor_caps->vendor_can('downloadable')) ? 'regular-checkbox pro_ele variable-subscription variable variation_is_downloadable_ele vendor_hidden' : 'regular-checkbox pro_ele variable-subscription variable variation_is_downloadable_ele', 'label_class' => (is_user_wcmp_vendor($current_user_id) && !$WCMp->vendor_caps->vendor_can('downloadable')) ? 'pro_title checkbox_title vendor_hidden' : 'pro_title checkbox_title'),
                "image" => array('label' => __('Image', 'wcmp-frontend_product_manager'), 'type' => 'upload', 'class' => 'regular-text pro_ele variable-subscription variable', 'label_class' => 'pro_title'),
                "sku" => array('label' => __('SKU', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => (apply_filters("variation_sku", false)) ? 'regular-text pro_ele variable-subscription variable vendor_hidden' : 'regular-text pro_ele variable-subscription variable', 'label_class' => (apply_filters("variation_sku", false)) ? 'pro_title vendor_hidden' : 'pro_title'),
                "regular_price" => array('label' => __('Regular Price', 'wcmp-frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')', 'type' => 'text', 'class' => 'regular-text pro_ele variable', 'label_class' => 'pro_title pro_ele variable'),
                "sale_price" => array('label' => __('Sale Price', 'wcmp-frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')', 'type' => 'text', 'class' => 'regular-text pro_ele variable-subscription variable', 'label_class' => 'pro_title'),
                "manage_stock" => array('label' => __('Manage Stock', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'value' => 'enable', 'value' => 'enable', 'class' => ( apply_filters("variation_manage_stock", false) ) ? 'regular-checkbox pro_ele variable-subscription variable variation_manage_stock_ele vendor_hidden' : 'regular-checkbox pro_ele variable-subscription variable variation_manage_stock_ele', 'label_class' => ( apply_filters("variation_manage_stock", false) ) ? 'pro_title checkbox_title vendor_hidden' : 'pro_title checkbox_title'),
                "stock_qty" => array('label' => __('Stock Qty', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => ( apply_filters("variation_manage_stock", false) ) ? 'regular-text pro_ele variable-subscription variable variation_non_manage_stock_ele vendor_hidden' : 'regular-text pro_ele variable-subscription variable variation_non_manage_stock_ele', 'wrapper_class' => ( apply_filters("variation_manage_stock", false) ) ? 'regular-text pro_ele variable-subscription variable variation_non_manage_stock_ele vendor_hidden' : 'regular-text pro_ele variable-subscription variable variation_non_manage_stock_ele', 'attributes' => array('min' => '0', 'step' => '1'), 'label_class' => ( apply_filters("variation_manage_stock", false) ) ? 'pro_title variation_non_manage_stock_ele vendor_hidden' : 'pro_title variation_non_manage_stock_ele'),
                "backorders" => array('label' => __('Allow Backorders?', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => array('no' => __('Do not Allow', 'wcmp-frontend_product_manager'), 'notify' => __('Allow, but notify customer', 'wcmp-frontend_product_manager'), 'yes' => __('Allow', 'wcmp-frontend_product_manager')), 'class' => ( apply_filters("variation_manage_stock", false) ) ? 'regular-select pro_ele variable-subscription variable variation_non_manage_stock_ele vendor_hidden' : 'regular-select pro_ele variable-subscription variable variation_non_manage_stock_ele', 'wrapper_class' => ( apply_filters("variation_manage_stock", false) ) ? 'pro_ele variable-subscription variable variation_non_manage_stock_ele vendor_hidden' : 'pro_ele variable-subscription variable variation_non_manage_stock_ele', 'label_class' => ( apply_filters("variation_manage_stock", false) ) ? 'pro_title variation_non_manage_stock_ele vendor_hidden' : 'pro_title variation_non_manage_stock_ele'),
                "stock_status" => array('label' => __('Stock status', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => array('instock' => __('In stock', 'wcmp-frontend_product_manager'), 'outofstock' => __('Out of stock', 'wcmp-frontend_product_manager')), 'class' => ( apply_filters("variation_manage_stock", false) ) ? 'regular-select pro_ele variable-subscription variable vendor_hidden' : 'regular-select pro_ele variable-subscription variable', 'label_class' => ( apply_filters("variation_manage_stock", false) ) ? 'pro_title vendor_hidden' : 'pro_title'),
                "weight" => array('label' => __('Weight', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => ( apply_filters("vendor_product_shipping_hide", false) ) ? 'regular-text pro_ele variable-subscription variable variation_non_virtual_ele vendor_hidden' : 'regular-text pro_ele variable-subscription variable variation_non_virtual_ele', 'label_class' => ( apply_filters("vendor_product_shipping_hide", false) ) ? 'pro_title variation_non_virtual_ele vendor_hidden' : 'pro_title variation_non_virtual_ele'),
                "length" => array('label' => __('Length', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => ( apply_filters("vendor_product_shipping_hide", false) ) ? 'regular-text pro_ele variable-subscription variable variation_non_virtual_ele vendor_hidden' : 'regular-text pro_ele variable-subscription variable variation_non_virtual_ele', 'label_class' => ( apply_filters("vendor_product_shipping_hide", false) ) ? 'pro_title variation_non_virtual_ele vendor_hidden' : 'pro_title variation_non_virtual_ele'),
                "width" => array('label' => __('Width', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => ( apply_filters("vendor_product_shipping_hide", false) ) ? 'regular-text pro_ele variable-subscription variable variation_non_virtual_ele vendor_hidden' : 'regular-text pro_ele variable-subscription variable variation_non_virtual_ele', 'label_class' => ( apply_filters("vendor_product_shipping_hide", false) ) ? 'pro_title variation_non_virtual_ele vendor_hidden' : 'pro_title variation_non_virtual_ele'),
                "height" => array('label' => __('Height', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => ( apply_filters("vendor_product_shipping_hide", false) ) ? 'regular-text pro_ele variable-subscription variable variation_non_virtual_ele vendor_hidden' : 'regular-text pro_ele variable-subscription variable variation_non_virtual_ele', 'label_class' => ( apply_filters("vendor_product_shipping_hide", false) ) ? 'pro_title variation_non_virtual_ele vendor_hidden' : 'pro_title variation_non_virtual_ele'),
                "downloadable_file" => array('label' => __('File', 'wcmp-frontend_product_manager'), 'type' => 'upload', 'mime' => 'doc', 'class' => 'regular-text pro_ele variable-subscription variable variation_downloadable_ele', 'label_class' => 'pro_title variation_downloadable_ele'),
                "downloadable_file_name" => array('label' => __('File Name', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text pro_ele variable-subscription variable variation_downloadable_ele', 'label_class' => 'pro_title variation_downloadable_ele'),
                "download_limit" => array('label' => __('Download Limit', 'wcmp-frontend_product_manager'), 'type' => 'number', 'placeholder' => __('Unlimited', 'wcmp-frontend_product_manager'), 'class' => 'regular-text pro_ele variable-subscription variable variation_downloadable_ele', 'label_class' => 'pro_title variation_downloadable_ele'),
                "download_expiry" => array('label' => __('Download Expiry', 'wcmp-frontend_product_manager'), 'type' => 'number', 'placeholder' => __('Never', 'wcmp-frontend_product_manager'), 'class' => 'regular-text pro_ele variable-subscription variable variation_downloadable_ele', 'label_class' => 'pro_title variation_downloadable_ele'),
                "shipping_class" => array('label' => __('Shipping class', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $variation_shipping_option_array, 'class' => ( apply_filters("vendor_product_shipping_hide", false) ) ? 'regular-select pro_ele variable-subscription variable variation_non_virtual_ele vendor_hidden' : 'regular-select pro_ele variation_non_virtual_ele variable-subscription variable', 'label_class' => ( apply_filters("vendor_product_shipping_hide", false) ) ? 'pro_title vendor_hidden variation_non_virtual_ele' : 'pro_title variation_non_virtual_ele'),
                "tax_class" => array('label' => __('Tax class', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => $variation_tax_classes_options, 'class' => ( wc_tax_enabled() && apply_filters("vendor_product_tax_hide", false) ) ? 'regular-select pro_ele variable-subscription variable vendor_hidden' : 'regular-select pro_ele variable-subscription variable', 'label_class' => ( wc_tax_enabled() && apply_filters("vendor_product_tax_hide", false) ) ? 'pro_title vendor_hidden' : 'pro_title'),
                "description" => array('label' => __('Description', 'wcmp-frontend_product_manager'), 'type' => 'textarea', 'class' => 'regular-textarea pro_ele variable-subscription variable', 'label_class' => 'pro_title'),
                "attributes" => array('type' => 'hidden')
                    ), $variations, $variation_shipping_option_array, $variation_tax_classes_options))
    ));
    ?>
</div>