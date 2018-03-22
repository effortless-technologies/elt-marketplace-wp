<?php

/**
 * WCMp Frontend Manager plugin core
 *
 * Booking WC Rental & Booking Support
 *
 * @author 		WC Marketplace
 * @package 	wcmp-frontend_product_manager/classes
 */
class WCMp_Frontend_Product_Manager_WCRental {

    public function __construct() {
        global $WCMp, $WCMp_Frontend_Product_Manager;

        if (wcmp_forntend_manager_is_rental()) {
            // Bookable Product Type
            add_filter('wcmp_product_types', array(&$this, 'wcr_product_types'), 30);

            // Rental Product Manage View
            add_action('end_wcmp_fpm_products_manage', array(&$this, 'wcr_wcmp_pts_form_load_views'), 30);

            // Rental Product Meta Data Save
            add_action('after_wcmp_fpm_meta_save', array(&$this, 'wcr_wcmp_pts_meta_save'), 30, 2);
        }
    }

    /**
     * WC Rental Product Type
     */
    function wcr_product_types($pro_types) {
        global $WCMp, $WCMp_Frontend_Product_Manager;

        $pro_types['redq_rental'] = __('Rental product', 'wcmp-frontend_product_manager');

        return $pro_types;
    }

    /**
     * WC Rental load views
     */
    function wcr_wcmp_pts_form_load_views($product_id) {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        $WCMp_Frontend_Product_Manager->template->get_template('wcmp-fpm-view-wcrental.php', array('pro_id' => $product_id));
    }

    /**
     * WC Rental Product Meta data save
     */
    function wcr_wcmp_pts_meta_save($new_product_id, $product_manager_form_data) {
        global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager, $_POST;

        if ($product_manager_form_data['product_type'] == 'redq_rental') {

            $rental_fields = array(
                'pricing_type',
                'hourly_price',
                'general_price',
                'friday_price',
                'saterday_price',
                'sunday_price',
                'monday_price',
                'tuesday_price',
                'wednesday_price',
                'thrusday_price',
                'january_price',
                'february_price',
                'march_price',
                'april_price',
                'may_price',
                'june_price',
                'july_price',
                'august_price',
                'september_price',
                'october_price',
                'november_price',
                'december_price',
                'redq_rental_availability'
            );

            foreach ($rental_fields as $field_name) {
                if (isset($product_manager_form_data[$field_name])) {
                    $rental_fields[$field_name] = $product_manager_form_data[$field_name];
                    update_post_meta($new_product_id, $field_name, $product_manager_form_data[$field_name]);
                }
            }

            update_post_meta($new_product_id, 'redq_all_data', $rental_fields);
        }
    }

}
