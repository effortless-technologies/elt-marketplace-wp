<?php

/**
 * WCMp Product Types plugin core
 *
 * WC Subscriptions Support
 *
 * @author 		WC Marketplace
 * @package 	wcmp-pts/classes
 * @version   1.0.0
 */
class WCMp_Frontend_Product_Manager_WCSubscriptions {

    public function __construct() {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        $vendor_can_subscription = $WCMp->vendor_caps->vendor_can('subscription');
        $vendor_can_variable_subscription = $WCMp->vendor_caps->vendor_can('variable-subscription');
        if (wcmp_forntend_manager_is_subscription()) {
            if ($vendor_can_subscription || $vendor_can_variable_subscription || current_user_can('administrator')) {
                // Subscriptions Product Type
                add_filter('wcmp_product_types', array(&$this, 'wcs_product_types'), 20);

                // Subscriptions Product options
                add_filter('wcmp_fpm_fields_general', array(&$this, 'wcs_product_manage_fields_general'), 10, 2);
                add_filter('wcmp_fpm_fields_shipping', array(&$this, 'wcs_product_manage_fields_shipping'), 10, 2);
                add_filter('wcmp_fpm_manage_fields_advanced', array(&$this, 'wcs_product_manage_fields_advanced'), 10, 2);
                add_filter('wcmp_fpm_manage_fields_variations', array(&$this, 'wcs_product_manage_fields_variations'), 10, 4);

                // Subscriptions Product Meta Data Save
                add_action('after_wcmp_fpm_meta_save', array(&$this, 'wcs_wcmp_pts_meta_save'), 20, 2);
                add_filter('fpm_product_variation_data_factory', array(&$this, 'wcs_product_variation_save'), 20, 5);

                // Subscription Product Date Edit
                add_filter('wcmp_fpm_variation_edit_data', array(&$this, 'wcs_product_data_variations'), 10, 3);
            }
        }
    }

    /**
     * WC Subscriptions Product Type
     */
    function wcs_product_types($pro_types) {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        $vendor_can_subscription = $WCMp->vendor_caps->vendor_can('subscription');
        $vendor_can_variable_subscription = $WCMp->vendor_caps->vendor_can('variable-subscription');
        if ($vendor_can_subscription || current_user_can('administrator')) {
            $pro_types['subscription'] = __('Simple subscription', 'wcmp-frontend_product_manager');
        }
        if ($vendor_can_variable_subscription || current_user_can('administrator')) {

            $pro_types['variable-subscription'] = __('Variable subscription', 'wcmp-frontend_product_manager');
        }

        return $pro_types;
    }

    /**
     * WC Subscriptions Product General options
     */
    function wcs_product_manage_fields_general($general_fields, $product_id) {
        global $WCMp, $WCMp_Frontend_Product_Manager;

        $chosen_price = '';
        $chosen_interval = 1;
        $chosen_period = 'month';
        $subscription_length = '';
        $sign_up_fee = '';
        $chosen_trial_length = 0;
        $chosen_trial_period = '';

        if ($product_id) {
            $chosen_price = get_post_meta($product_id, '_subscription_price', true);
            $chosen_interval = get_post_meta($product_id, '_subscription_period_interval', true);
            $chosen_period = get_post_meta($product_id, '_subscription_period', true);
            $subscription_length = get_post_meta($product_id, '_subscription_length', true);
            $sign_up_fee = get_post_meta($product_id, '_subscription_sign_up_fee', true);
            $chosen_trial_length = WC_Subscriptions_Product::get_trial_length($product_id);
            $chosen_trial_period = WC_Subscriptions_Product::get_trial_period($product_id);
        }

        $general_fields = array_slice($general_fields, 0, 3, true) +
                array("_subscription_price" => array('label' => sprintf(esc_html__('Subscription price (%s)', 'wcmp-frontend_product_manager'), esc_html(get_woocommerce_currency_symbol())), 'type' => 'text', 'class' => 'regular-text pro_ele subscription_price_ele subscription', 'wrapper_class' => 'pro_ele subscription', 'label_class' => 'pro_title pro_ele subscription', 'hints' => __('Choose the subscription price, billing interval and period.', 'wcmp-frontend_product_manager'), 'value' => $chosen_price),
                    "_subscription_period_interval" => array('label' => 'subscription period interval', 'subscription period interval' => 'deep', 'type' => 'select', 'options' => wcs_get_subscription_period_interval_strings(), 'class' => 'regular-select pro_ele subscription_price_ele subscription', 'wrapper_class' => 'pro_ele subscription', 'label_class' => 'nolabel pro_title pro_ele subscription', 'value' => $chosen_interval),
                    "_subscription_period" => array('label' => 'subscription period', 'type' => 'select', 'options' => wcs_get_subscription_period_strings(), 'class' => 'regular-select pro_ele subscription_price_ele subscription', 'wrapper_class' => 'pro_ele subscription', 'label_class' => 'nolabel pro_title pro_ele subscription', 'value' => $chosen_period),
                    "_subscription_length_day" => array('label' => __('Subscription length', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => wcs_get_subscription_ranges('day'), 'class' => 'regular-select pro_ele subscription_length_ele subscription_length_day subscription', 'wrapper_class' => 'pro_ele subscription', 'label_class' => 'pro_title pro_ele subscription_length_ele subscription_length_day subscription', 'hints' => __('Automatically expire the subscription after this length of time. This length is in addition to any free trial or amount of time provided before a synchronised first renewal date.', 'wcmp-frontend_product_manager'), 'value' => $subscription_length),
                    "_subscription_length_week" => array('label' => __('Subscription length', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => wcs_get_subscription_ranges('week'), 'class' => 'regular-select pro_ele subscription_length_ele subscription_length_week subscription', 'wrapper_class' => 'pro_ele subscription', 'label_class' => 'pro_title pro_ele subscription_length_ele subscription_length_week subscription', 'hints' => __('Automatically expire the subscription after this length of time. This length is in addition to any free trial or amount of time provided before a synchronised first renewal date.', 'wcmp-frontend_product_manager'), 'value' => $subscription_length),
                    "_subscription_length_month" => array('label' => __('Subscription length', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => wcs_get_subscription_ranges('month'), 'class' => 'regular-select pro_ele subscription_length_ele subscription_length_month subscription', 'wrapper_class' => 'pro_ele subscription', 'label_class' => 'pro_title pro_ele subscription_length_ele subscription_length_month subscription', 'hints' => __('Automatically expire the subscription after this length of time. This length is in addition to any free trial or amount of time provided before a synchronised first renewal date.', 'wcmp-frontend_product_manager'), 'value' => $subscription_length),
                    "_subscription_length_year" => array('label' => __('Subscription length', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => wcs_get_subscription_ranges('year'), 'class' => 'regular-select pro_ele subscription_length_ele subscription_length_year subscription', 'wrapper_class' => 'pro_ele subscription', 'label_class' => 'pro_title pro_ele subscription_length_ele subscription_length_year subscription', 'hints' => __('Automatically expire the subscription after this length of time. This length is in addition to any free trial or amount of time provided before a synchronised first renewal date.', 'wcmp-frontend_product_manager'), 'value' => $subscription_length),
                    "_subscription_sign_up_fee" => array('label' => sprintf(esc_html__('Sign-up fee (%s)', 'wcmp-frontend_product_manager'), esc_html(get_woocommerce_currency_symbol())), 'type' => 'text', 'placeholder' => 'e.g. 9.90', 'class' => 'regular-text pro_ele subscription', 'wrapper_class' => 'pro_ele subscription', 'label_class' => 'pro_title pro_ele subscription', 'hints' => __('Optionally include an amount to be charged at the outset of the subscription. The sign-up fee will be charged immediately, even if the product has a free trial or the payment dates are synced.', 'wcmp-frontend_product_manager'), 'value' => $sign_up_fee),
                    "_subscription_trial_length" => array('label' => esc_html__('Free Trial', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text pro_ele subscription_price_ele subscription', 'wrapper_class' => 'pro_ele subscription', 'label_class' => 'pro_title pro_ele subscription', 'hints' => __('An optional period of time to wait before charging the first recurring payment. Any sign up fee will still be charged at the outset of the subscription.', 'wcmp-frontend_product_manager'), 'value' => $chosen_trial_length),
                    "_subscription_trial_period" => array('label' => esc_html__('Trial type', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => wcs_get_available_time_periods(), 'class' => 'regular-select pro_ele subscription_price_ele subscription', 'wrapper_class' => 'pro_ele subscription', 'label_class' => 'nolabel pro_title pro_ele subscription', 'value' => $chosen_trial_period),
                ) +
                array_slice($general_fields, 3, count($general_fields) - 2, true);
        return $general_fields;
    }

    /**
     * WC Subscriptions Product Shipping options
     */
    function wcs_product_manage_fields_shipping($shipping_fields, $product_id) {
        global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager;

        $one_time_shipping = 'no';

        if ($product_id) {
            $one_time_shipping = get_post_meta($product_id, '_subscription_one_time_shipping', true) ? get_post_meta($product_id, '_subscription_one_time_shipping', true) : 'no';
        }

        $shipping_fields = array_slice($shipping_fields, 0, 5, true) +
                array(
                    "_subscription_one_time_shipping" => array('label' => esc_html__('One time shipping', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele subscription variable-subscription', 'wrapper_class' => 'pro_ele subscription variable-subscription', 'label_class' => 'pro_title pro_ele subscription variable-subscription', 'hints' => __('Shipping for subscription products is normally charged on the initial order and all renewal orders. Enable this to only charge shipping once on the initial order. Note: for this setting to be enabled the subscription must not have a free trial or a synced renewal date.', 'wcmp-frontend_product_manager'), 'value' => 'yes', 'dfvalue' => $one_time_shipping)
                ) +
                array_slice($shipping_fields, 5, count($shipping_fields) - 1, true);
        return $shipping_fields;
    }

    /**
     * WC Subscriptions Product Advanced options
     */
    function wcs_product_manage_fields_advanced($advanced_fields, $product_id) {
        global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager;

        $subscription_limit = '';

        if ($product_id) {
            $subscription_limit = get_post_meta($product_id, '_subscription_limit', true);
        }

        $advanced_fields = array_slice($advanced_fields, 0, 3, true) +
                array(
                    "_subscription_limit" => array('label' => esc_html__('Limit subscription', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => array('no' => __('Do not limit', 'wcmp-frontend_product_manager'), 'active' => __('Limit to one active subscription', 'wcmp-frontend_product_manager'), 'any' => __('Limit to one of any status', 'wcmp-frontend_product_manager')), 'class' => 'regular-select pro_ele subscription variable-subscription', 'wrapper_class' => 'pro_ele subscription variable-subscription', 'label_class' => 'pro_title pro_ele subscription variable-subscription', 'hints' => __('Only allow a customer to have one subscription to this product.', 'wcmp-frontend_product_manager'), 'value' => $subscription_limit)
                ) +
                array_slice($advanced_fields, 3, count($advanced_fields) - 1, true);
        return $advanced_fields;
    }

    /**
     * WC Subscriptions Variation aditional options
     */
    function wcs_product_manage_fields_variations($variation_fileds, $variations, $variation_shipping_option_array, $variation_tax_classes_options) {
        global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager;

        $variation_fileds = array_slice($variation_fileds, 0, 6, true) +
                array("_subscription_price" => array('label' => sprintf(esc_html__('Subscription price (%s)', 'wcmp-frontend_product_manager'), esc_html(get_woocommerce_currency_symbol())), 'type' => 'text', 'wrapper_class' => 'pro_ele subscription_price_ele variable-subscription', 'class' => 'regular-text pro_ele subscription_price_ele variable-subscription', 'label_class' => 'pro_title pro_ele variable-subscription', 'hints' => __('Choose the subscription price, billing interval and period.', 'wcmp-frontend_product_manager')),
                    "_subscription_period_interval" => array('label' => __('subscription period interval strings', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => wcs_get_subscription_period_interval_strings(), 'class' => 'regular-select pro_ele subscription_price_ele variable-subscription subscription_period_interval ', 'wrapper_class' => 'pro_ele subscription_price_ele variable-subscription', 'label_class' => 'nolabel pro_title pro_ele variable-subscription'),
                    "_subscription_period" => array('label' => __('Subscription length', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => wcs_get_subscription_period_strings(), 'wrapper_class' => 'pro_ele subscription_price_ele variable-subscription', 'class' => 'regular-select pro_ele subscription_price_ele variable-subscription_period variable-subscription', 'label_class' => 'nolabel pro_title pro_ele variable-subscription'),
                    "_subscription_length_day" => array('label' => __('Subscription length', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => wcs_get_subscription_ranges('day'), 'wrapper_class' => 'pro_ele variable-subscription_length_ele variable-subscription_length_day variable-subscription', 'class' => 'regular-select pro_ele variable-subscription_length_ele variable-subscription_length_day variable-subscription', 'label_class' => 'pro_title pro_ele variable-subscription_length_ele variable-subscription_length_day variable-subscription', 'hints' => __('Automatically expire the subscription after this length of time. This length is in addition to any free trial or amount of time provided before a synchronised first renewal date.', 'wcmp-frontend_product_manager')),
                    "_subscription_length_week" => array('label' => __('Subscription length', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => wcs_get_subscription_ranges('week'), 'wrapper_class' => 'pro_ele variable-subscription_length_ele variable-subscription_length_week variable-subscription', 'class' => 'regular-select pro_ele variable-subscription_length_ele variable-subscription_length_week variable-subscription', 'label_class' => 'pro_title pro_ele variable-subscription_length_ele variable-subscription_length_week variable-subscription', 'hints' => __('Automatically expire the subscription after this length of time. This length is in addition to any free trial or amount of time provided before a synchronised first renewal date.', 'wcmp-frontend_product_manager')),
                    "_subscription_length_month" => array('label' => __('Subscription length', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => wcs_get_subscription_ranges('month'), 'wrapper_class' => 'pro_ele variable-subscription_length_ele variable-subscription_length_month variable-subscription', 'class' => 'regular-select pro_ele variable-subscription_length_ele variable-subscription_length_month variable-subscription', 'label_class' => 'pro_title pro_ele variable-subscription_length_ele variable-subscription_length_month variable-subscription', 'hints' => __('Automatically expire the subscription after this length of time. This length is in addition to any free trial or amount of time provided before a synchronised first renewal date.', 'wcmp-frontend_product_manager')),
                    "_subscription_length_year" => array('label' => __('Subscription length', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => wcs_get_subscription_ranges('year'), 'wrapper_class' => 'pro_ele variable-subscription_length_ele variable-subscription_length_year variable-subscription', 'class' => 'regular-select pro_ele variable-subscription_length_ele variable-subscription_length_year variable-subscription', 'label_class' => 'pro_title pro_ele variable-subscription_length_ele variable-subscription_length_year variable-subscription', 'hints' => __('Automatically expire the subscription after this length of time. This length is in addition to any free trial or amount of time provided before a synchronised first renewal date.', 'wcmp-frontend_product_manager')),
                    "_subscription_sign_up_fee" => array('label' => sprintf(esc_html__('Sign-up fee (%s)', 'wcmp-frontend_product_manager'), esc_html(get_woocommerce_currency_symbol())), 'type' => 'text', 'placeholder' => 'e.g. 9.90', 'wrapper_class' => 'pro_ele variable-subscription', 'class' => 'regular-text pro_ele variable-subscription', 'label_class' => 'pro_title pro_ele variable-subscription', 'hints' => __('Optionally include an amount to be charged at the outset of the subscription. The sign-up fee will be charged immediately, even if the product has a free trial or the payment dates are synced.', 'wcmp-frontend_product_manager')),
                    "_subscription_trial_length" => array('label' => esc_html__('Free Trial', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text pro_ele subscription_price_ele variable-subscription', 'wrapper_class' => 'pro_ele subscription_price_ele variable-subscription', 'label_class' => 'pro_title pro_ele variable-subscription', 'hints' => __('An optional period of time to wait before charging the first recurring payment. Any sign up fee will still be charged at the outset of the subscription.', 'wcmp-frontend_product_manager')),
                    "_subscription_trial_period" => array('label' => esc_html__('available time periods', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => wcs_get_available_time_periods(), 'class' => 'regular-select pro_ele subscription_price_ele variable-subscription subscription_trial_period', 'label_class' => 'nolabel pro_title pro_ele variable-subscription ', 'wrapper_class' => 'pro_ele subscription_price_ele variable-subscription subscription_trial_period'),
                ) +
                array_slice($variation_fileds, 6, count($variation_fileds) - 1, true);

        return $variation_fileds;
    }

    /**
     * WC Subscriptions Product Meta data save
     */
    function wcs_wcmp_pts_meta_save($new_product_id, $product_manager_form_data) {
        global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager, $_POST;

        if ($product_manager_form_data['product_type'] == 'subscription') {
            $subscription_price = isset($product_manager_form_data['_subscription_price']) ? wc_format_decimal($product_manager_form_data['_subscription_price']) : '';
            $sale_price = wc_format_decimal($product_manager_form_data['sale_price']);

            update_post_meta($new_product_id, '_subscription_price', $subscription_price);

            // Set sale details - these are ignored by WC core for the subscription product type
            update_post_meta($new_product_id, '_regular_price', $subscription_price);
            update_post_meta($new_product_id, '_sale_price', $sale_price);

            $date_from = ( isset($product_manager_form_data['sale_date_from']) ) ? wcs_date_to_time($product_manager_form_data['sale_date_from']) : '';
            $date_to = ( isset($product_manager_form_data['sale_date_upto']) ) ? wcs_date_to_time($product_manager_form_data['sale_date_upto']) : '';

            $now = gmdate('U');

            if (!empty($date_to) && empty($date_from)) {
                $date_from = $now;
            }

            update_post_meta($new_product_id, '_sale_price_dates_from', $date_from);
            update_post_meta($new_product_id, '_sale_price_dates_to', $date_to);

            // Update price if on sale
            if (!empty($sale_price) && ( ( empty($date_to) && empty($date_from) ) || ( $date_from < $now && ( empty($date_to) || $date_to > $now ) ) )) {
                $price = $sale_price;
            } else {
                $price = $subscription_price;
            }

            update_post_meta($new_product_id, '_price', stripslashes($price));

            // Make sure trial period is within allowable range
            $subscription_ranges = wcs_get_subscription_ranges();

            $max_trial_length = count($subscription_ranges[$product_manager_form_data['_subscription_trial_period']]) - 1;

            $product_manager_form_data['_subscription_trial_length'] = absint($product_manager_form_data['_subscription_trial_length']);

            if ($product_manager_form_data['_subscription_trial_length'] > $max_trial_length) {
                $product_manager_form_data['_subscription_trial_length'] = $max_trial_length;
            }

            update_post_meta($new_product_id, '_subscription_trial_length', $product_manager_form_data['_subscription_trial_length']);

            $product_manager_form_data['_subscription_sign_up_fee'] = wc_format_decimal($product_manager_form_data['_subscription_sign_up_fee']);
            $product_manager_form_data['_subscription_one_time_shipping'] = isset($product_manager_form_data['_subscription_one_time_shipping']) ? 'yes' : 'no';

            $subscription_fields = array(
                '_subscription_sign_up_fee',
                '_subscription_period',
                '_subscription_period_interval',
                //'_subscription_length',
                '_subscription_trial_period',
                '_subscription_limit',
                '_subscription_one_time_shipping',
            );

            foreach ($subscription_fields as $field_name) {
                if (isset($product_manager_form_data[$field_name])) {
                    update_post_meta($new_product_id, $field_name, stripslashes($product_manager_form_data[$field_name]));
                }
            }

            update_post_meta($new_product_id, '_subscription_length', stripslashes($product_manager_form_data['_subscription_length_' . $product_manager_form_data['_subscription_period']]));
        }
    }

    /**
     * WC Subscriptions Variation Data Save
     */
    function wcs_product_variation_save($wcmp_fpm_variation_data, $new_product_id, $variation_id, $variations, $product_manager_form_data) {
        global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager, $_POST;

        if ($product_manager_form_data['product_type'] == 'variable-subscription') {
            $subscription_price = isset($variations['_subscription_price']) ? wc_format_decimal($variations['_subscription_price']) : '';
            update_post_meta($variation_id, '_subscription_price', $subscription_price);
            update_post_meta($variation_id, '_regular_price', $subscription_price);
            update_post_meta($variation_id, '_price', $subscription_price);
            update_post_meta($new_product_id, '_price', $subscription_price);

            $subscription_fields = array(
                '_subscription_period',
                '_subscription_period_interval',
                '_subscription_sign_up_fee',
                '_subscription_trial_period',
                '_subscription_trial_length'
            );

            foreach ($subscription_fields as $field_name) {
                if (isset($variations[$field_name])) {
                    update_post_meta($variation_id, $field_name, stripslashes($variations[$field_name]));
                }
            }

            update_post_meta($variation_id, '_subscription_length', stripslashes($variations['_subscription_length_' . $variations['_subscription_period']]));
        }

        return $wcmp_fpm_variation_data;
    }

    /**
     * WC Subscriptions Variaton edit data
     */
    function wcs_product_data_variations($variations, $variation_id, $variation_id_key) {
        global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager, $_POST;

        if ($variation_id) {
            $variations[$variation_id_key]['_subscription_price'] = get_post_meta($variation_id, '_subscription_price', true);
            $variations[$variation_id_key]['_subscription_period'] = get_post_meta($variation_id, '_subscription_period', true);
            $variations[$variation_id_key]['_subscription_period_interval'] = get_post_meta($variation_id, '_subscription_period_interval', true);
            $variations[$variation_id_key]['_subscription_sign_up_fee'] = get_post_meta($variation_id, '_subscription_sign_up_fee', true);
            $variations[$variation_id_key]['_subscription_trial_period'] = get_post_meta($variation_id, '_subscription_trial_period', true);
            $variations[$variation_id_key]['_subscription_trial_length'] = get_post_meta($variation_id, '_subscription_trial_length', true);
            $variations[$variation_id_key]['_subscription_length_day'] = get_post_meta($variation_id, '_subscription_length', true);
            $variations[$variation_id_key]['_subscription_length_week'] = get_post_meta($variation_id, '_subscription_length', true);
            $variations[$variation_id_key]['_subscription_length_month'] = get_post_meta($variation_id, '_subscription_length', true);
            $variations[$variation_id_key]['_subscription_length_year'] = get_post_meta($variation_id, '_subscription_length', true);
        }

        return $variations;
    }

}
