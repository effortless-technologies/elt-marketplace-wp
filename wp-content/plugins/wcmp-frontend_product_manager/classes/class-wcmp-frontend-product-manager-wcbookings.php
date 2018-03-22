<?php

/**
 * WCMp Frontend Manager plugin core
 *
 * Booking WC Booking Support
 *
 * @author 		WC Marketplace
 * @package 	wcmp-frontend_product_manager/classes 
 */
class WCMp_Frontend_Product_Manager_WCBookings {

    public function __construct() {
        global $WCMp, $WCMp_Frontend_Product_Manager;

        if (wcmp_forntend_manager_is_booking()) {
            if (current_user_can('manage_bookings')) {

                // Bookable Product Type
                add_filter('wcmp_product_types', array(&$this, 'wcb_product_types'), 20);

                // Bookable Product options
                add_filter('wcmp_fpm_fields_general', array(&$this, 'wcb_product_manage_fields_general'), 10, 2);

                // Booking General Block
                add_action('after_wcmp_fpm_general', array(&$this, 'wcb_product_manage_general'));

                // Booking Product Manage View
                add_action('end_wcmp_fpm_products_manage', array(&$this, 'wcb_wcmp_pts_form_load_views'), 20);

                // Booking Product Meta Data Save
                add_action('after_wcmp_fpm_meta_save', array(&$this, 'wcb_wcmp_pts_meta_save'), 20, 2);

                // Booking Dashboard
                add_filter('wcmp_vendor_dashboard_nav', array(&$this, 'wcb_wcmp_pts_bookings_nav'));

                add_action('wcmp_vendor_dashboard_booking-calendar_endpoint', array(&$this, 'wcmp_vendor_dashboard_booking_calendar_endpoint'));
                add_action('wcmp_vendor_dashboard_all-booking_endpoint', array(&$this, 'wcb_wcmp_pts_bookings_endpoint'));

                // Booking Details

                add_action('wcmp_vendor_dashboard_booking-details_endpoint', array(&$this, 'wcb_wcmp_pts_booking_details_endpoint'));

                //Addon field add 
                add_filter('wcmp_fpm_addon_fields', array(&$this, 'wcb_product_manage_fields_addon_fields'), 10, 2);
                add_filter('woo_addon_data_save', array(&$this, 'wcb_product_manage_fields_addon_fields_save'), 10);
            }
        }

        // Add vendor email for confirm booking email
        add_filter('woocommerce_email_recipient_new_booking', array($this, 'wcb_wcmp_pts_filter_booking_emails'), 20, 2);

        // Add vendor email for cancelled booking email
        add_filter('woocommerce_email_recipient_booking_cancelled', array($this, 'wcb_wcmp_pts_filter_booking_emails'), 20, 2);
    }

    /**
     * WC Booking Product Type
     */
    function wcb_product_types($pro_types) {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        $vendor_can = $WCMp->vendor_caps->vendor_can('booking');

        if (current_user_can('manage_bookings') && $vendor_can) {
            $pro_types['booking'] = __('Bookable product', 'wcmp-frontend_product_manager');
        }

        return $pro_types;
    }

    /**
     * WC Booking Product General options
     */
    function wcb_product_manage_fields_general($general_fields, $product_id) {
        global $WCMp, $WCMp_Frontend_Product_Manager;

        // Has Resource
        $has_resources = ( get_post_meta($product_id, '_wc_booking_has_resources', true) ) ? 'yes' : '';

        // Has Persons
        $has_persons = ( get_post_meta($product_id, '_wc_booking_has_persons', true) ) ? 'yes' : '';

        $general_fields = array_slice($general_fields, 0, 1, true) +
                array("_wc_booking_has_resources" => array('label' => __('Has Resouces', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele booking', 'wrapper_class' => 'pro_ele booking', 'label_class' => 'pro_title pro_ele checkbox_title booking', 'value' => 'yes', 'dfvalue' => $has_resources),
                    "_wc_booking_has_persons" => array('label' => __('Has Persons', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele booking', 'wrapper_class' => 'pro_ele booking', 'label_class' => 'pro_title pro_ele checkbox_title booking', 'value' => 'yes', 'dfvalue' => $has_persons),
                ) +
                array_slice($general_fields, 1, count($general_fields) - 1, true);
        return $general_fields;
    }

    /**
     * WC Booking Product Addon options
     */
    function wcb_product_manage_fields_addon_fields($addon_fields, $product_id) {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        $addon_fields = array_slice($addon_fields, 0, 1, true) +
                array("addon_wc_booking_person_qty_multiplier" => array('label' => __('Bookings: Multiply cost by person count', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'wrapper_class' => 'pro_ele booking', 'class' => 'regular-checkbox pro_ele booking', 'label_class' => 'pro_title pro_ele checkbox_title booking', 'value' => 1),
                    "addon_wc_booking_block_qty_multiplier" => array('label' => __('Bookings: Multiply cost by block count', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'wrapper_class' => 'pro_ele booking ', 'class' => 'regular-checkbox pro_ele booking', 'label_class' => 'pro_title pro_ele checkbox_title booking', 'value' => 1),
                ) +
                array_slice($addon_fields, 1, count($addon_fields) - 1, true);
        return $addon_fields;
    }

    function wcb_product_manage_fields_addon_fields_save($_product_addons) {
        global $WCMp, $WCMp_Frontend_Product_Manager;

        if (!empty($_product_addons)) {
            $loop_index = 0;
            foreach ($_product_addons as $_product_addon_index => $_product_addon) {
                $_product_addons[$_product_addon_index]['position'] = $loop_index;

                if (isset($_product_addon['addon_wc_booking_person_qty_multiplier']))
                    $_product_addons[$_product_addon_index]['addon_wc_booking_person_qty_multiplier'] = 1;
                else
                    $_product_addons[$_product_addon_index]['addon_wc_booking_person_qty_multiplier'] = 0;

                if (isset($_product_addon['addon_wc_booking_block_qty_multiplier']))
                    $_product_addons[$_product_addon_index]['addon_wc_booking_block_qty_multiplier'] = 1;
                else
                    $_product_addons[$_product_addon_index]['addon_wc_booking_block_qty_multiplier'] = 0;

                $loop_index++;
            }
            return $_product_addons;
        }
    }

    /**
     * WC Booking Product General Options
     */
    function wcb_product_manage_general($product_id) {
        global $WCMp, $WCMp_Frontend_Product_Manager;

        $bookable_product = new WC_Product_Booking($product_id);

        $duration_type = $bookable_product->get_duration_type('edit');
        $duration = $bookable_product->get_duration('edit');
        $duration_unit = $bookable_product->get_duration_unit('edit');

        $min_duration = $bookable_product->get_min_duration('edit');
        $max_duration = $bookable_product->get_max_duration('edit');
        $enable_range_picker = $bookable_product->get_enable_range_picker('edit') ? 'yes' : 'no';

        $calendar_display_mode = $bookable_product->get_calendar_display_mode('edit');
        $requires_confirmation = $bookable_product->get_requires_confirmation('edit') ? 'yes' : 'no';

        $user_can_cancel = $bookable_product->get_user_can_cancel('edit') ? 'yes' : 'no';
        $cancel_limit = $bookable_product->get_cancel_limit('edit');
        $cancel_limit_unit = $bookable_product->get_cancel_limit_unit('edit');
        ?>
        <!-- collapsible Booking 1 -->
        <h3 class="pro_ele_head booking"><?php _e('Booking Options', 'wcmp-frontend_product_manager'); ?></h3>
        <div class="pro_ele_block booking">

            <?php
            $WCMp_Frontend_Product_Manager->wcmp_wp_fields->wcmp_generate_form_field(array(
                "_wc_booking_duration_type" => array('label' => __('Booking Duration', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => array('fixed' => __('Fixed blocks of', 'wcmp-frontend_product_manager'), 'customer' => __('Customer defined blocks of', 'wcmp-frontend_product_manager')), 'class' => 'regular-select pro_ele booking', 'label_class' => 'pro_title booking', 'value' => $duration_type),
                "_wc_booking_duration" => array('label' => __('booking duration', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text pro_ele booking', 'label_class' => 'nolabel', 'attributes' => array('min' => 1, 'step' => '1'), 'value' => $duration),
                "_wc_booking_duration_unit" => array('label' => __('booking duration type', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => array('month' => __('Month(s)', 'wcmp-frontend_product_manager'), 'day' => __('Day(s)', 'wcmp-frontend_product_manager'), 'hour' => __('Hour(s)', 'wcmp-frontend_product_manager'), 'minute' => __('Minute(s)', 'wcmp-frontend_product_manager')), 'class' => 'regular-select pro_ele booking', 'label_class' => 'nolabel', 'value' => $duration_unit),
                "_wc_booking_min_duration" => array('label' => __('Minimum duration', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text pro_ele duration_type_customer_ele booking', 'wrapper_class' => 'duration_type_customer_ele', 'label_class' => 'pro_title duration_type_customer_ele booking', 'value' => $min_duration, 'hints' => __('The minimum allowed duration the user can input.', 'wcmp-frontend_product_manager'), 'attributes' => array('min' => '', 'step' => '1')),
                "_wc_booking_max_duration" => array('label' => __('Maximum duration', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text pro_ele duration_type_customer_ele booking', 'wrapper_class' => 'duration_type_customer_ele', 'label_class' => 'pro_title duration_type_customer_ele booking', 'value' => $max_duration, 'hints' => __('The maximum allowed duration the user can input.', 'wcmp-frontend_product_manager'), 'attributes' => array('min' => '', 'step' => '1')),
                "_wc_booking_enable_range_picker" => array('label' => __('Enable Calendar Range Picker?', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele duration_type_customer_ele booking', 'wrapper_class' => 'duration_type_customer_ele', 'label_class' => 'pro_title duration_type_customer_ele booking', 'value' => 'yes', 'dfvalue' => $enable_range_picker, 'hints' => __('Lets the user select a start and end date on the calendar - duration will be calculated automatically.', 'wcmp-frontend_product_manager')),
                "_wc_booking_calendar_display_mode" => array('label' => __('Calendar display mode', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => array('' => __('Display calendar on click', 'wcmp-frontend_product_manager'), 'always_visible' => __('Calendar always visible', 'wcmp-frontend_product_manager')), 'class' => 'regular-select pro_ele booking', 'label_class' => 'pro_title booking', 'value' => $calendar_display_mode),
                "_wc_booking_requires_confirmation" => array('label' => __('Requires confirmation?', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele booking', 'label_class' => 'pro_title booking', 'value' => 'yes', 'dfvalue' => $requires_confirmation, 'hints' => __('Check this box if the booking requires admin approval/confirmation. Payment will not be taken during checkout.', 'wcmp-frontend_product_manager')),
                "_wc_booking_user_can_cancel" => array('label' => __('Can be cancelled?', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'class' => 'regular-checkbox pro_ele booking', 'label_class' => 'pro_title booking', 'value' => 'yes', 'dfvalue' => $user_can_cancel, 'hints' => __('Check this box if the booking can be cancelled by the customer after it has been purchased. A refund will not be sent automatically.', 'wcmp-frontend_product_manager')),
                "_wc_booking_cancel_limit" => array('label' => __('Booking can be cancelled until', 'wcmp-frontend_product_manager'), 'type' => 'number', 'class' => 'regular-text pro_ele can_cancel_ele booking', 'label_class' => 'pro_title can_cancel_ele booking', 'value' => $cancel_limit, 'attributes' => array('min' => 1, 'step' => '1')),
                "_wc_booking_cancel_limit_unit" => array('label' => __('Booking cancelled type', 'wcmp-frontend_product_manager'), 'type' => 'select', 'options' => array('month' => __('Month(s)', 'wcmp-frontend_product_manager'), 'day' => __('Day(s)', 'wcmp-frontend_product_manager'), 'hour' => __('Hour(s)', 'wcmp-frontend_product_manager'), 'minute' => __('Minute(s)', 'wcmp-frontend_product_manager')), 'class' => 'regular-select pro_ele can_cancel_ele booking', 'label_class' => 'nolabel pro_title can_cancel_ele booking', 'desc_class' => 'can_cancel_ele booking', 'value' => $cancel_limit_unit, 'desc' => __('before the start date.', 'wcmp-frontend_product_manager'))
            ));
            ?>

        </div>
            <?php
        }

        /**
         * WC Booking load views
         */
        function wcb_wcmp_pts_form_load_views($product_id) {
            global $WCMp, $WCMp_Frontend_Product_Manager;
            $WCMp_Frontend_Product_Manager->template->get_template('wcmp-fpm-view-wcbookings.php', array('pro_id' => $product_id));
        }

        /**
         * WC Booking Product Meta data save
         */
        function wcb_wcmp_pts_meta_save($new_product_id, $product_manager_form_data) {
            global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager, $_POST;

            $product_type = empty($product_manager_form_data['product_type']) ? WC_Product_Factory::get_product_type($new_product_id) : sanitize_title(stripslashes($product_manager_form_data['product_type']));
            $classname = WC_Product_Factory::get_product_classname($new_product_id, $product_type ? $product_type : 'simple');
            $product = new $classname($new_product_id);

            // Only set props if the product is a bookable product.
            if ('booking' !== $product_type) {
                return;
            }

            // Availability Rules
            $availability_rule_index = 0;
            $availability_rules = array();
            $availability_default_rules = array("type" => 'custom',
                "from" => '',
                "to" => '',
                "bookable" => '',
                "priority" => 10
            );
            if (isset($product_manager_form_data['_wc_booking_availability_rules']) && !empty($product_manager_form_data['_wc_booking_availability_rules'])) {
                foreach ($product_manager_form_data['_wc_booking_availability_rules'] as $availability_rule) {
                    $availability_rules[$availability_rule_index] = $availability_default_rules;
                    $availability_rules[$availability_rule_index]['type'] = $availability_rule['type'];
                    if ($availability_rule['type'] == 'custom') {
                        $availability_rules[$availability_rule_index]['from'] = $availability_rule['from_custom'];
                        $availability_rules[$availability_rule_index]['to'] = $availability_rule['to_custom'];
                    } elseif ($availability_rule['type'] == 'months') {
                        $availability_rules[$availability_rule_index]['from'] = $availability_rule['from_months'];
                        $availability_rules[$availability_rule_index]['to'] = $availability_rule['to_months'];
                    } elseif ($availability_rule['type'] == 'weeks') {
                        $availability_rules[$availability_rule_index]['from'] = $availability_rule['from_weeks'];
                        $availability_rules[$availability_rule_index]['to'] = $availability_rule['to_weeks'];
                    } elseif ($availability_rule['type'] == 'days') {
                        $availability_rules[$availability_rule_index]['from'] = $availability_rule['from_days'];
                        $availability_rules[$availability_rule_index]['to'] = $availability_rule['to_days'];
                    } elseif ($availability_rule['type'] == 'time:range') {
                        $availability_rules[$availability_rule_index]['from_date'] = $availability_rule['from_custom'];
                        $availability_rules[$availability_rule_index]['to_date'] = $availability_rule['to_custom'];
                        $availability_rules[$availability_rule_index]['from'] = $availability_rule['from_time'];
                        $availability_rules[$availability_rule_index]['to'] = $availability_rule['to_time'];
                    } else {
                        $availability_rules[$availability_rule_index]['from'] = $availability_rule['from_time'];
                        $availability_rules[$availability_rule_index]['to'] = $availability_rule['to_time'];
                    }
                    $availability_rules[$availability_rule_index]['bookable'] = $availability_rule['bookable'];
                    $availability_rules[$availability_rule_index]['priority'] = $availability_rule['priority'];
                    $availability_rule_index++;
                }
            }

            //booking pricing
            $product_pricing_index = 0;
            $product_pricing_values = array();
            $product_pricing_default_values = array("type" => 'custom',
                "from_date" => '',
                "to_date" => '',
                "from_month" => '',
                "to_month" => '',
                "from_week" => '',
                "to_week" => '',
                "from_day_of_week" => '',
                "to_day_of_week" => '',
                "from_time" => '',
                "to_time" => '',
                "from" => '',
                "to" => '',
                "base_cost_modifier" => '',
                "base_cost" => '',
                "cost_modifier" => '',
                "cost" => ''
            );

            if (isset($product_manager_form_data['_wc_booking_cost_range_types']) && !empty($product_manager_form_data['_wc_booking_cost_range_types'])) {
                foreach ($product_manager_form_data['_wc_booking_cost_range_types'] as $value) {
                    $product_pricing_values[$product_pricing_index] = $product_pricing_default_values;
                    $product_pricing_values[$product_pricing_index]['type'] = $value['type'];
                    if ($value['type'] == 'custom') {
                        $product_pricing_values[$product_pricing_index]['from'] = $value['from_date'];
                        $product_pricing_values[$product_pricing_index]['to'] = $value['to_date'];
                    } elseif ($value['type'] == 'months') {
                        $product_pricing_values[$product_pricing_index]['from'] = $value['from_month'];
                        $product_pricing_values[$product_pricing_index]['to'] = $value['to_month'];
                    } elseif ($value['type'] == 'weeks') {
                        $product_pricing_values[$product_pricing_index]['from'] = $value['from_week'];
                        $product_pricing_values[$product_pricing_index]['to'] = $value['to_week'];
                    } elseif ($value['type'] == 'days') {
                        $product_pricing_values[$product_pricing_index]['from'] = $value['from_day_of_week'];
                        $product_pricing_values[$product_pricing_index]['to'] = $value['to_day_of_week'];
                    } elseif ($value['type'] == 'time:range') {
                        $product_pricing_values[$product_pricing_index]['from_date'] = $value['from_date'];
                        $product_pricing_values[$product_pricing_index]['to_date'] = $value['to_date'];
                        $product_pricing_values[$product_pricing_index]['from'] = $value['from_time'];
                        $product_pricing_values[$product_pricing_index]['to'] = $value['to_time'];
                    } elseif ($value['type'] == 'persons' || $value['type'] == 'blocks') {
                        $product_pricing_values[$product_pricing_index]['from'] = $value['from'];
                        $product_pricing_values[$product_pricing_index]['to'] = $value['to'];
                    } else {
                        $product_pricing_values[$product_pricing_index]['from'] = $value['from_time'];
                        $product_pricing_values[$product_pricing_index]['to'] = $value['to_time'];
                    }
                    $product_pricing_values[$product_pricing_index]['base_modifier'] = $value['base_cost_modifier'];
                    $product_pricing_values[$product_pricing_index]['base_cost'] = $value['base_cost'];
                    $product_pricing_values[$product_pricing_index]['modifier'] = $value['cost_modifier'];
                    $product_pricing_values[$product_pricing_index]['cost'] = $value['cost'];
                    $product_pricing_index++;
                }
            }

            // Person Types
            $person_type_index = 0;
            $person_types = array();
            if (isset($product_manager_form_data['_wc_booking_has_persons']) && isset($product_manager_form_data['_wc_booking_person_types']) && !empty($product_manager_form_data['_wc_booking_person_types'])) {
                foreach ($product_manager_form_data['_wc_booking_person_types'] as $booking_person_types) {
                    $loop = intval($person_type_index);

                    $person_type_id = ( isset($booking_person_types['person_id']) ) ? $booking_person_types['person_id'] : 0;
                    if (!$person_type_id) {
                        $person_type = new WC_Product_Booking_Person_Type();
                        $person_type->set_parent_id($product->get_id());
                        $person_type->set_sort_order($loop);
                        $person_type_id = $person_type->save();
                    } else {
                        $person_type = new WC_Product_Booking_Person_Type($person_type_id);
                    }

                    $person_type->set_props(array(
                        'name' => wc_clean(stripslashes($booking_person_types['person_name'])),
                        'description' => wc_clean(stripslashes($booking_person_types['person_description'])),
                        'sort_order' => absint($person_type_index),
                        'cost' => wc_clean($booking_person_types['person_cost']),
                        'block_cost' => wc_clean($booking_person_types['person_block_cost']),
                        'min' => wc_clean($booking_person_types['person_min']),
                        'max' => wc_clean($booking_person_types['person_max']),
                        'parent_id' => $product->get_id(),
                    ));
                    $person_types[] = $person_type;
                    $person_type_index++;
                }
            }

            // Resources
            $resource_index = 0;
            $resources = array();
            if (isset($product_manager_form_data['_wc_booking_has_resources']) && isset($product_manager_form_data['_wc_booking_resources']) && !empty($product_manager_form_data['_wc_booking_resources'])) {
                foreach ($product_manager_form_data['_wc_booking_resources'] as $booking_resources) {
                    if ($booking_resources['resource_title']) {
                        $loop = intval($resource_index);
                        $resource_id = ( isset($booking_resources['resource_id']) ) ? absint($booking_resources['resource_id']) : 0;
                        // Creating new Resource
                        if (!$resource_id) {
                            $nresource = new WC_Product_Booking_Resource();
                            $nresource->set_name($booking_resources['resource_title']);
                            $resource_id = $nresource->save();
                        }
                        $resources[$resource_id] = array(
                            'base_cost' => wc_clean($booking_resources['resource_base_cost']),
                            'block_cost' => wc_clean($booking_resources['resource_block_cost']),
                        );
                        $resource_index++;
                    }
                }
            }

            // Remove Deleted Person Types
            if (isset($_POST['removed_person_types']) && !empty($_POST['removed_person_types'])) {
                foreach ($_POST['removed_person_types'] as $removed_person_type) {
                    wp_delete_post($removed_person_type, true);
                }
            }

            $wc_booking_restricted_days = '';
            if (isset($product_manager_form_data['_wc_booking_restricted_days']) && !empty($product_manager_form_data['_wc_booking_restricted_days'])) {
                $wc_booking_restricted_days = array_combine(array_keys($product_manager_form_data['_wc_booking_restricted_days']), array_keys($product_manager_form_data['_wc_booking_restricted_days']));
            } else {
                $wc_booking_restricted_days = '';
            }

            $errors = $product->set_props(apply_filters('wcmp_pts_booking_data_factory', array(
                'apply_adjacent_buffer' => isset($product_manager_form_data['_wc_booking_apply_adjacent_buffer']),
                'base_cost' => wc_clean($product_manager_form_data['_wc_booking_base_cost']),
                'buffer_period' => wc_clean($product_manager_form_data['_wc_booking_buffer_period']),
                'calendar_display_mode' => wc_clean($product_manager_form_data['_wc_booking_calendar_display_mode']),
                'cancel_limit_unit' => wc_clean($product_manager_form_data['_wc_booking_cancel_limit_unit']),
                'cancel_limit' => wc_clean($product_manager_form_data['_wc_booking_cancel_limit']),
                'cost' => wc_clean($product_manager_form_data['_wc_booking_cost']),
                'has_restricted_days' => isset($product_manager_form_data['_wc_booking_has_restricted_days']) ? wc_clean($product_manager_form_data['_wc_booking_has_restricted_days']) : '',
                'restricted_days' => isset($product_manager_form_data['_wc_booking_has_restricted_days']) ? $wc_booking_restricted_days : '',
                'default_date_availability' => wc_clean($product_manager_form_data['_wc_booking_default_date_availability']),
                'display_cost' => wc_clean($product_manager_form_data['_wc_display_cost']),
                'duration_type' => wc_clean($product_manager_form_data['_wc_booking_duration_type']),
                'duration_unit' => wc_clean($product_manager_form_data['_wc_booking_duration_unit']),
                'duration' => wc_clean($product_manager_form_data['_wc_booking_duration']),
                'enable_range_picker' => isset($product_manager_form_data['_wc_booking_enable_range_picker']),
                'max_date_unit' => wc_clean($product_manager_form_data['_wc_booking_max_date_unit']),
                'max_date_value' => wc_clean($product_manager_form_data['_wc_booking_max_date']),
                'max_duration' => wc_clean($product_manager_form_data['_wc_booking_max_duration']),
                'min_date_unit' => wc_clean($product_manager_form_data['_wc_booking_min_date_unit']),
                'min_date_value' => wc_clean($product_manager_form_data['_wc_booking_min_date']),
                'min_duration' => wc_clean($product_manager_form_data['_wc_booking_min_duration']),
                'qty' => wc_clean($product_manager_form_data['_wc_booking_qty']),
                'requires_confirmation' => isset($product_manager_form_data['_wc_booking_requires_confirmation']),
                'user_can_cancel' => isset($product_manager_form_data['_wc_booking_user_can_cancel']),
                'check_start_block_only' => 'start' === $product_manager_form_data['_wc_booking_check_availability_against'],
                'first_block_time' => wc_clean($product_manager_form_data['_wc_booking_first_block_time']),
                'availability' => $availability_rules,
                'has_person_cost_multiplier' => isset($product_manager_form_data['_wc_booking_person_cost_multiplier']),
                'has_person_qty_multiplier' => isset($product_manager_form_data['_wc_booking_person_qty_multiplier']),
                'has_person_types' => isset($product_manager_form_data['_wc_booking_has_person_types']),
                'has_persons' => isset($product_manager_form_data['_wc_booking_has_persons']),
                'has_resources' => isset($product_manager_form_data['_wc_booking_has_resources']),
                'max_persons' => wc_clean($product_manager_form_data['_wc_booking_max_persons_group']),
                'min_persons' => wc_clean($product_manager_form_data['_wc_booking_min_persons_group']),
                'person_types' => $person_types,
                'pricing' => $product_pricing_values, //$this->get_posted_pricing(),
                'resource_label' => wc_clean($product_manager_form_data['_wc_booking_resource_label']),
                'resource_base_costs' => wp_list_pluck($resources, 'base_cost'),
                'resource_block_costs' => wp_list_pluck($resources, 'block_cost'),
                'resource_ids' => array_keys($resources),
                'resources_assignment' => wc_clean($product_manager_form_data['_wc_booking_resources_assignment']),
                            ), $new_product_id, $product, $product_manager_form_data));

            if (is_wp_error($errors)) {
                //echo '{"status": false, "message": "' . $errors->get_error_message() . '", "id": "' . $new_product_id . '", "redirect": "' . get_permalink( $new_product_id ) . '"}';
            }

            $product->save();
        }

        public function wcb_wcmp_pts_bookings_nav($nav) {
            global $WCMp, $WCMp_Frontend_Product_Manager;

            $nav['vendor-bookings'] = array(
                'label' => __('Bookings', 'wcmp-frontend_product_manager')
                , 'url' => '#'
                , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_bookings_capability', true)
                , 'position' => 21
                , 'submenu' => array(
                    'all-booking' => array(
                        'label' => __('All Bookings', 'wcmp-frontend_product_manager')
                        , 'url' => wcmp_get_vendor_dashboard_endpoint_url('all-booking')
                        , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_all-booking_capability', true)
                        , 'position' => 15
                        , 'link_target' => '_self'
                        , 'nav_icon' => 'menu-sprite menu-sprite-submenu-all-bookings-icon'
                    ),
                    'booking-calendar' => array(
                        'label' => __('Calendar', 'wcmp-frontend_product_manager')
                        , 'url' => wcmp_get_vendor_dashboard_endpoint_url('booking-calendar')
                        , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_booking_manage_capability', true)
                        , 'position' => 20
                        , 'link_target' => '_self'
                        , 'nav_icon' => 'menu-sprite menu-sprite-submenu-calendar-icon'
                    ),
                )
                , 'link_target' => '_self'
                , 'nav_icon' => 'wcmp-font ico-booking-icon'
            );

            return $nav;
        }

        public function wcmp_vendor_dashboard_booking_calendar_endpoint() {
            global $WCMp_Frontend_Product_Manager;
            $WCMp_Frontend_Product_Manager->load_class('wcbookings-calendar');
            $vendor_booking_calendar = new WCMp_Frontend_Product_Manager_WCBookings_Calendar();
            $vendor_booking_calendar->output();
        }

        function get_vendor_booking_array() {
            global $WCMp, $wpdb, $WCMp_Frontend_Product_Manager;

            $vendor_id = get_current_user_id();

            $include_bookings = array(0);
            $products = array();
            if ($vendor_id) {
                $vendor = get_wcmp_vendor($vendor_id);
                if ($vendor)
                    $vendor_products = $vendor->get_products();
                if (!empty($vendor_products)) {
                    foreach ($vendor_products as $vendor_product) {
                        $products[] = $vendor_product->ID;
                        if ($vendor_product->post_type == 'product_variation')
                            $products[] = $vendor_product->post_parent;
                    }
                }
            }

            if (empty($products))
                $include_bookings = array(0);

            $query = "SELECT ID FROM {$wpdb->posts} as posts
							INNER JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id
							WHERE 1=1
							AND posts.post_type IN ( 'wc_booking' )
							AND posts.post_status NOT IN ('was-in-cart', 'in-cart')
							AND postmeta.meta_key = '_booking_product_id' AND postmeta.meta_value in (" . implode(',', $products) . ")";

            $vendor_bookings = $wpdb->get_results($query);
            if (empty($vendor_bookings))
                $include_bookings = array(0);
            $vendor_bookings_arr = array();
            foreach ($vendor_bookings as $vendor_booking) {
                $vendor_bookings_arr[] = get_post($vendor_booking->ID);
            }

            return $vendor_bookings_arr;
        }

        public function wcb_wcmp_pts_bookings_endpoint() {
            global $WCMp, $wpdb;

            $vendor_id = get_current_user_id();

            $wcmp_pts_bookings_array = $this->get_vendor_booking_array();

            $booking_details_url = wcmp_get_vendor_dashboard_endpoint_url('booking-details');
            if (!empty($wcmp_pts_bookings_array)) {
                echo '<div class="col-md-12"><div class="panel panel-default panel-pading"><div class="wcmp_tab ui-tabs ui-widget ui-widget-content ui-corner-all"><div class="wcmp_table_holder"><table class="wcmp_booking_tbl table table-striped table-bordered"><thead><tr><th>' . __('Bookings', 'wcmp-frontend_product_manager') . '</th><th>' . __('Status', 'wcmp-frontend_product_manager') . '</th><th>' . __('Start Date', 'wcmp-frontend_product_manager') . '</th><th>' . __('End Date', 'wcmp-frontend_product_manager') . '</th><th>' . __('Action', 'wcmp-frontend_product_manager') . '</th></tr></thead><tbody>';
                foreach ($wcmp_pts_bookings_array as $wcmp_pts_bookings_single) {
                    $the_booking = get_wc_booking($wcmp_pts_bookings_single->ID);

                    echo '<tr><td>#<a class="" href="' . $booking_details_url . $wcmp_pts_bookings_single->ID . '"><strong>' . $wcmp_pts_bookings_single->ID . '</strong></a></td>';
                    echo '<td>' . ucfirst(sanitize_title($the_booking->get_status())) . '</td>';
                    echo '<td>' . date('Y-m-d H:i A', $the_booking->get_start('edit')) . '</td>';
                    echo '<td>' . date('Y-m-d H:i A', $the_booking->get_end('edit')) . '</td>';
                    echo '<td><a class="wcmp_table_action" href="' . $booking_details_url . $wcmp_pts_bookings_single->ID . '/">' . __('Details', 'wcmp-frontend_product_manager') . '</a></td></tr>';
                }
                echo '</tbody></table></div></div></div></div>';
            } else {
                ?>
            <div class="col-md-12"><div class="panel panel-default panel-padding text-center">
            <?php
            _e("You do not have any Bookings yet!!!", 'wcmp-frontend_product_manager');
            ?>
                </div></div>
            <?php
        }
    }

    public function wcb_wcmp_pts_booking_details_endpoint() {
        global $wp, $WCMp, $thebooking;

        if (!is_object($thebooking)) {
            if (isset($wp->query_vars['booking-details']) && !empty($wp->query_vars['booking-details'])) {
                $thebooking = get_wc_booking($wp->query_vars['booking-details']);
            }
        }

        $booking_id = $wp->query_vars['booking-details'];
        $wcmp_pts_bookings_array = $this->get_vendor_booking_array();
        $booking_ids_arr = array();
        foreach ($wcmp_pts_bookings_array as $wcmp_pts_bookings_value) {
            $booking_ids_arr[] = $wcmp_pts_bookings_value->ID;
        }

        if (in_array($booking_id, $booking_ids_arr)) {


            $post = get_post($booking_id);
            $booking = new WC_Booking($post->ID);
            $order = $booking->get_order();
            $product_id = $booking->get_product_id('edit');
            $resource_id = $booking->get_resource_id('edit');
            $customer_id = $booking->get_customer_id('edit');
            $product = $booking->get_product($product_id);
            $resource = new WC_Product_Booking_Resource($resource_id);
            $customer = $booking->get_customer();
            $statuses = array_unique(array_merge(get_wc_booking_statuses(null, true), get_wc_booking_statuses('user', true), get_wc_booking_statuses('cancel', true)));
            ?>
            <div class="col-md-12">
                <div class="panel panel-default panel-pading">
                    <div class="wcmp_pts-collapse" id="wcmp_pts_booking_details">

                        <div class="wcmp_pts-collapse-content">
                            <div class="wcmp_pts-container">
                                <div id="bookings_details_general_expander" class="wcmp_pts-content">

                                    <p class="form-field form-field-wide">
                                        <label for="booking_date"><?php _e('Booking Created:', 'wcmp-frontend_product_manager') ?></label>
            <?php echo date_i18n('Y-m-d', $booking->get_date_created()); ?> @<?php echo date_i18n('H', $booking->get_date_created()); ?>:<?php echo date_i18n('i A', $booking->get_date_created()); ?>
                                    </p>

                                    <p class="form-field form-field-wide">
                                        <label for="booking_date"><?php _e('Order Number:', 'wcmp-frontend_product_manager') ?></label>
            <?php
            if ($order) {
                echo '#' . $order->get_order_number() . ' - ' . esc_html(wc_get_order_status_name($order->get_status()));
            } else {
                echo '-';
            }
            ?>
                                    </p>

                                    <p class="form-field form-field-wide">
                                        <label for="wcmp_pts_booking_status"><?php _e('Booking Status:', 'wcmp-frontend_product_manager'); ?></label>
                                        <?php echo ucfirst($post->post_status); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="wcmp_pts_clearfix"></div>
                            <br />
                            <!-- collapsible End -->

                            <!-- collapsible -->
                            <div class="page_collapsible bookings_details_booking" id="wcmp_pts_booking_options">
                                <div class="panel-heading">
                                    <h3><?php _e('Booking', 'wcmp-frontend_product_manager'); ?></h3>
                                </div>
                            </div>
                            <div class="wcmp_pts-container">
                                <div id="bookings_details_booking_expander" class="wcmp_pts-content">

                                    <p class="form-field form-field-wide">
                                        <label for="booked_product"><?php _e('Booked Product:', 'wcmp-frontend_product_manager') ?></label>
            <?php
            if ($product) {
                $product_post = get_post($product->get_ID());
                echo $product_post->post_title;
            } else {
                echo '-';
            }
            ?>
                                    </p>

                                        <?php if ($resource_id) { ?>
                                        <p class="form-field form-field-wide">
                                            <label for="booked_product"><?php _e('Resource:', 'wcmp-frontend_product_manager') ?></label>
                                            <?php
                                            echo esc_html($resource->post_title);
                                            ?>
                                        </p>
                                        <?php } ?>

                                        <?php
                                        if ($product && is_callable(array($product, 'get_person_types'))) {
                                            $person_types = $product->get_person_types();
                                            $person_counts = $booking->get_person_counts();
                                            if (!empty($person_types) && is_array($person_types)) {
                                                ?>
                                            <p class="form-field form-field-wide">
                                                <label for="booked_product"><?php _e('Person(s):', 'wcmp-frontend_product_manager') ?></label>
                                                <?php
                                                $pfirst = true;
                                                foreach ($person_types as $person_type) {
                                                    if (!$pfirst)
                                                        echo ', ';
                                                    echo $pfirst = false;
                                                    echo $person_type->get_name() . ' (';
                                                    if (isset($person_counts[$person_type->get_id()])) {
                                                        echo $person_counts[$person_type->get_id()];
                                                    } else {
                                                        echo '0';
                                                    }
                                                    echo ')';
                                                }
                                                ?>
                                            </p>
                                            <?php
                                            }
                                        }
                                        ?>

                                    <p class="form-field form-field-wide">
                                        <label for="booking_date"><?php _e('Booking Start Date:', 'wcmp-frontend_product_manager') ?></label>
                                    <?php echo date('Y-m-d H:i A', $booking->get_start('edit')); ?>
                                    </p>

                                    <p class="form-field form-field-wide">
                                        <label for="booking_date"><?php _e('Booking End Date:', 'wcmp-frontend_product_manager') ?></label>
                                        <?php echo date('Y-m-d H:i A', $booking->get_end('edit')); ?>
                                    </p>
                                    <p class="form-field form-field-wide">
                                        <label for="booking_date"><?php _e('All day booking:', 'wcmp-frontend_product_manager') ?></label>
            <?php echo $booking->get_all_day('edit') ? 'YES' : 'NO'; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="wcmp_pts_clearfix"></div>
                            <br />

                            <div class="page_collapsible bookings_details_customer" id="wcmp_pts_customer_options">
                                <div class="panel-heading">
                                    <h3><?php _e('Customer', 'wcmp-frontend_product_manager'); ?></h3>
                                </div>
                            </div>
                            <div class="wcmp_pts-container">
                                <div id="bookings_details_customer_expander" class="wcmp_pts-content">
            <?php
            $customer_id = get_post_meta($post->ID, '_booking_customer_id', true);
            $order_id = $post->post_parent;
            $has_data = false;

            echo '<table class="booking-customer-details table">';

            if ($customer_id && ( $user = get_user_by('id', $customer_id) )) {
                echo '<tr>';
                echo '<th>' . __('Name:', 'wcmp-frontend_product_manager') . '</th>';
                echo '<td>';
                if ($user->last_name && $user->first_name) {
                    echo $user->first_name . ' ' . $user->last_name;
                } else {
                    echo '-';
                }
                echo '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<th>' . __('User Email:', 'wcmp-frontend_product_manager') . '</th>';
                echo '<td>';
                echo '<a href="mailto:' . esc_attr($user->user_email) . '">' . esc_html($user->user_email) . '</a>';
                echo '</td>';
                echo '</tr>';

                $has_data = true;
            }

            if ($order_id && ( $order = wc_get_order($order_id) )) {
                echo '<tr>';
                echo '<th>' . __('Address:', 'wcmp-frontend_product_manager') . '</th>';
                echo '<td>';
                if ($order->get_formatted_billing_address()) {
                    echo wp_kses($order->get_formatted_billing_address(), array('br' => array()));
                } else {
                    echo __('No billing address set.', 'wcmp-frontend_product_manager');
                }
                echo '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<th>' . __('Email:', 'wcmp-frontend_product_manager') . '</th>';
                echo '<td>';
                echo '<a href="mailto:' . esc_attr($order->get_billing_email()) . '">' . esc_html($order->get_billing_email()) . '</a>';
                echo '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<th>' . __('Phone:', 'wcmp-frontend_product_manager') . '</th>';
                echo '<td>';
                echo esc_html($order->get_billing_phone());
                echo '</td>';
                echo '</tr>';

                $has_data = true;
            }

            if (!$has_data) {
                echo '<tr>';
                echo '<td colspan="2">' . __('N/A', 'wcmp-frontend_product_manager') . '</td>';
                echo '</tr>';
            }

            echo '</table>';
            ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {

            _e('You are not allowed to view this booking.', 'wcmp-frontend_product_manager');
        }
    }

    /**
     * Add vendor email to booking admin emails
     */
    public function wcb_wcmp_pts_filter_booking_emails($recipients, $this_email) {
        global $WCMp;

        if (!empty($this_email)) {
            $product = get_post($this_email->product_id);
            $vendor_id = $product->post_author;
            if (is_user_wcmp_vendor($vendor_id)) {
                $vendor_data = get_userdata($vendor_id);
                if (!empty($vendor_data)) {
                    if (isset($recipients)) {
                        $recipients .= ',' . $vendor_data->user_email;
                    } else {
                        $recipients = $vendor_data->user_email;
                    }
                }
            }
        }

        return $recipients;
    }

}
