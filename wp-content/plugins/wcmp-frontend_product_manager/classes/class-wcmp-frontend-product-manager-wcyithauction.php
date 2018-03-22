<?php

/**
 * WCMp Product Types plugin core
 *
 * Booking YITH Auction Support
 *
 * @author 		WC Marketplace
 * @package 	wcmp-pts/classes
 * @version   1.0.3
 */
class WCMp_Frontend_Product_Manager_YTH_Auction {

    public function __construct() {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        $vendor_can = $WCMp->vendor_caps->vendor_can('auction');
        if (wcmp_forntend_manager_is_yithauction()) {

            if ($vendor_can || current_user_can('administrator')) {

                // Auction Product Type
                add_filter('wcmp_product_types', array(&$this, 'yithauction_product_types'), 30);

                // Auction Product Manage View
                add_action('end_wcmp_fpm_products_manage', array(&$this, 'yithauction_wcmp_pts_form_load_views'), 30);

                // Auction Product Meta Data Save
                add_action('after_wcmp_fpm_meta_save', array(&$this, 'yithauction_wcmp_pts_meta_save'), 30, 2);

                add_filter('auction_classes', array(&$this, 'auction_classes'));
            }
        }
    }

    /**
     * Add class for virtual checbox hide
     */
    function auction_classes($classes) {
        $classes = $classes . ' non-auction';
        return $classes;
    }

    /**
     * YITH Auction Product Type
     */
    function yithauction_product_types($pro_types) {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        $vendor_can = $WCMp->vendor_caps->vendor_can('auction');
        if ($vendor_can || current_user_can('administrator')) {
            $pro_types['auction'] = __('Auction', 'wcmp-frontend_product_manager');
        }

        return $pro_types;
    }

    /**
     * YITH Auction load views
     */
    function yithauction_wcmp_pts_form_load_views($product_id) {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        $WCMp_Frontend_Product_Manager->template->get_template('wcmp_fpm-view-yithauction.php', array('pro_id' => $product_id));
    }

    /**
     * YITH Auction Product Meta data save
     */
    function yithauction_wcmp_pts_meta_save($new_product_id, $product_manager_form_data) {
        global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager, $_POST;

        if ($product_manager_form_data['product_type'] == 'auction') {

            $aution_fields = array(
                '_yith_auction_start_price',
                '_yith_auction_bid_increment',
                '_yith_auction_minimum_increment_amount',
                '_yith_auction_reserve_price',
                '_yith_auction_buy_now',
                '_yith_auction_for',
                '_yith_auction_to',
                '_yith_check_time_for_overtime_option',
                '_yith_overtime_option',
                '_yith_wcact_auction_automatic_reschedule',
                '_yith_wcact_automatic_reschedule_auction_unit',
                '_yith_wcact_upbid_checkbox',
                '_yith_wcact_overtime_checkbox'
            );

            $product_manager_form_data['_yith_auction_for'] = ( $product_manager_form_data['_yith_auction_for'] ) ? strtotime($product_manager_form_data['_yith_auction_for']) : '';
            $product_manager_form_data['_yith_auction_to'] = ( $product_manager_form_data['_yith_auction_to'] ) ? strtotime($product_manager_form_data['_yith_auction_to']) : '';


            $product_manager_form_data['_yith_wcact_upbid_checkbox'] = ( $product_manager_form_data['_yith_wcact_upbid_checkbox'] ) ? 'yes' : 'no';
            $product_manager_form_data['_yith_wcact_overtime_checkbox'] = ( $product_manager_form_data['_yith_wcact_overtime_checkbox'] ) ? 'yes' : 'no';

            foreach ($aution_fields as $field_name) {
                if (isset($product_manager_form_data[$field_name])) {
                    $rental_fields[$field_name] = $product_manager_form_data[$field_name];
                    update_post_meta($new_product_id, $field_name, $product_manager_form_data[$field_name]);
                }
            }
        }
    }

}
