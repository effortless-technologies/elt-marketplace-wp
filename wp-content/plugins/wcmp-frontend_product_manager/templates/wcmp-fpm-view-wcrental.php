<?php
/**
 * WCMp Frontend Manager plugin views
 *
 * Plugin WC Rental & Booking Products Manage Views
 *
 * @author 		WC Marketplace
 * @package 	wcmp-frontend_product_manager/templates
 */
global $wp, $WCMp_Frontend_Product_Manager;

$pricing_type = '';
$hourly_price = '';
$general_price = '';

$friday_price = '';
$saterday_price = '';
$sunday_price = '';
$monday_price = '';
$tuesday_price = '';
$wednesday_price = '';
$thrusday_price = '';

$january_price = '';
$february_price = '';
$march_price = '';
$april_price = '';
$may_price = '';
$june_price = '';
$july_price = '';
$august_price = '';
$september_price = '';
$october_price = '';
$november_price = '';
$december_price = '';


$redq_rental_availability = array();
$product_id = 0;
if (!empty($pro_id)) {
    $product = wc_get_product((int) $pro_id);
    if ($product && !empty($product)) {
        $product_id = $product->get_id();
    }
}

if ($product_id) {
    $pricing_type = get_post_meta($product_id, 'pricing_type', true);
    $hourly_price = get_post_meta($product_id, 'hourly_price', true);
    $general_price = get_post_meta($product_id, 'general_price', true);

    $friday_price = get_post_meta($product_id, 'friday_price', true);
    $saterday_price = get_post_meta($product_id, 'saterday_price', true);
    $sunday_price = get_post_meta($product_id, 'sunday_price', true);
    $monday_price = get_post_meta($product_id, 'monday_price', true);
    $tuesday_price = get_post_meta($product_id, 'tuesday_price', true);
    $wednesday_price = get_post_meta($product_id, 'wednesday_price', true);
    $thrusday_price = get_post_meta($product_id, 'thrusday_price', true);

    $january_price = get_post_meta($product_id, 'january_price', true);
    $february_price = get_post_meta($product_id, 'february_price', true);
    $march_price = get_post_meta($product_id, 'march_price', true);
    $april_price = get_post_meta($product_id, 'april_price', true);
    $may_price = get_post_meta($product_id, 'may_price', true);
    $june_price = get_post_meta($product_id, 'june_price', true);
    $july_price = get_post_meta($product_id, 'july_price', true);
    $august_price = get_post_meta($product_id, 'august_price', true);
    $september_price = get_post_meta($product_id, 'september_price', true);
    $october_price = get_post_meta($product_id, 'october_price', true);
    $november_price = get_post_meta($product_id, 'november_price', true);
    $december_price = get_post_meta($product_id, 'december_price', true);

    $redq_rental_availability = (array) get_post_meta($product_id, 'redq_rental_availability', true);
}
?>


<h3 class="pro_ele_head products_manage_rental_pricing redq_rental"><?php _e('Price Calculation', 'wcmp-frontend_product_manager'); ?></h3>
<div class="pro_ele_block redq_rental">
    <?php
    $WCMp_Frontend_Product_Manager->wcmp_wp_fields->wcmp_generate_form_field(array(
        "pricing_type" => array('label' => __('Set Price Type', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => array('general_pricing' => __('General Pricing', 'wcmp-frontend_product_manager')), 'class' => 'regular-select pro_ele redq_rental', 'label_class' => 'pro_title redq_rental', 'value' => $pricing_type, 'hints' => __('Choose a price type - this controls the schema.', 'wcmp-frontend_product_manager')),
        "hourly_price" => array('label' => __('Hourly Price', 'wcmp-frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')', 'type' => 'number', 'class' => 'regular-text pro_ele redq_rental', 'label_class' => 'pro_title redq_rental', 'value' => $hourly_price, 'hints' => __('Hourly price will be applicabe if booking or rental days min 1day', 'wcmp-frontend_product_manager'), 'placeholder' => __('Enter price here', 'wcmp-frontend_product_manager')),
        "general_price" => array('label' => __('General Price', 'wcmp-frontend_product_manager') . '(' . get_woocommerce_currency_symbol() . ')', 'type' => 'number', 'class' => 'regular-text pro_ele rentel_pricing rental_general_pricing redq_rental', 'label_class' => 'pro_title rentel_pricing rental_general_pricing redq_rental', 'value' => $general_price, 'placeholder' => __('Enter price here', 'wcmp-frontend_product_manager'))
    ));
    ?>
</div>

<h3 class="pro_ele_head products_manage_rental_availability redq_rental"><?php _e('Availability', 'wcmp-frontend_product_manager'); ?></h3>
<div class="pro_ele_block redq_rental">
    <?php
    $WCMp_Frontend_Product_Manager->wcmp_wp_fields->wcmp_generate_form_field(array(
        "redq_rental_availability" => array('label' => __('Product Availabilities', 'wcmp-frontend_product_manager'), 'type' => 'multiinput', 'class' => 'regular-text pro_ele redq_rental', 'label_class' => 'pro_title redq_rental', 'desc' => __('Please select the date range to be disabled for the product.', 'wcmp-frontend_product_manager'), 'desc_class' => 'avail_rules_desc', 'value' => $redq_rental_availability, 'options' => array(
                "type" => array('label' => __('Type', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => array('custom_date' => __('Custom Date', 'wcmp-frontend_product_manager')), 'class' => 'regular-select pro_ele avail_range_type redq_rental', 'label_class' => 'pro_title avail_rules_ele avail_rules_label redq_rental'),
                "from" => array('label' => __('From', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text dc_datepicker avail_rule_field avail_rule_custom avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_custom avail_rules_ele avail_rules_label'),
                "to" => array('label' => __('To', 'wcmp-frontend_product_manager'), 'type' => 'text', 'class' => 'regular-text dc_datepicker avail_rule_field avail_rule_custom avail_rules_ele avail_rules_text', 'label_class' => 'pro_title avail_rule_field avail_rule_custom avail_rules_ele avail_rules_label'),
                "rentable" => array('label' => __('Bookable', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => array('no' => 'NO'), 'class' => 'regular-select pro_ele avail_rules_ele avail_rules_text redq_rental', 'label_class' => 'pro_title avail_rules_ele avail_rules_label'),
            ))
    ));
    ?>
</div>