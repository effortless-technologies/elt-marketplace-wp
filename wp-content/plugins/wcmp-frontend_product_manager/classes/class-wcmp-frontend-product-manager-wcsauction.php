<?php

/**
 * WCMp Product Types plugin core
 *
 * Auction WC Simple Auction Support
 *
 * @author 		WC Marketplace
 * @package 	wcmp-pts/classes
 * @version   1.0.3
 */
class WCMp_Frontend_Product_Manager_WCS_Auction {

    public function __construct() {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        $vendor_can = $WCMp->vendor_caps->vendor_can('auction');
        if (wcmp_forntend_manager_is_wcsauction()) {
            if ($vendor_can || current_user_can('administrator')) {
                // Auction Product Type
                add_filter('wcmp_product_types', array(&$this, 'wcsauction_product_types'), 30);

                // Auction Product Manage View
                add_action('end_wcmp_fpm_products_manage', array(&$this, 'wcsauction_wcmp_pts_form_load_views'), 30);

                // Auction Product Meta Data Save
                add_action('after_wcmp_fpm_meta_save', array(&$this, 'wcsauction_wcmp_pts_meta_save'), 30, 2);

                // Auction Dashboard
                add_filter('wcmp_vendor_dashboard_nav', array(&$this, 'wcsauction_wcmp_pts_auctions_nav'));
                add_action('wcmp_vendor_dashboard_header', array(&$this, 'wcsauction_wcmp_pts_auctions_header'));
                add_action('wcmp_vendor_dashboard_auctions_endpoint', array(&$this, 'wcsauction_wcmp_pts_auctions_endpoint'));
            }
        }
    }

    /**
     * YITH Auction Product Type
     */
    function wcsauction_product_types($pro_types) {
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
    function wcsauction_wcmp_pts_form_load_views($product_id) {
        global $WCMp, $WCMp_Frontend_Product_Manager;
        $WCMp_Frontend_Product_Manager->template->get_template('wcmp_fpm-view-wcsauction.php', array('pro_id' => $product_id));
    }

    /**
     * YITH Auction Product Meta data save
     */
    function wcsauction_wcmp_pts_meta_save($new_product_id, $product_manager_form_data) {
        global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager, $_POST;

        if ($product_manager_form_data['product_type'] == 'auction') {

            $aution_fields = array(
                '_auction_item_condition',
                '_auction_type',
                '_auction_start_price',
                '_auction_bid_increment',
                '_auction_reserved_price',
                '_regular_price',
                '_auction_dates_from',
                '_auction_dates_to',
                '_auction_relist_fail_time',
                '_auction_relist_not_paid_time',
                '_auction_relist_duration'
            );

            foreach ($aution_fields as $field_name) {
                if (isset($product_manager_form_data[$field_name])) {
                    $rental_fields[$field_name] = $product_manager_form_data[$field_name];
                    update_post_meta($new_product_id, $field_name, $product_manager_form_data[$field_name]);
                }
            }

            if (isset($product_manager_form_data['_auction_proxy'])) {
                update_post_meta($new_product_id, '_auction_proxy', 'yes');
            } else {
                delete_post_meta($new_product_id, '_auction_proxy');
            }

            if (isset($product_manager_form_data['_auction_automatic_relist'])) {
                update_post_meta($new_product_id, '_auction_automatic_relist', 'yes');
            } else {
                delete_post_meta($new_product_id, '_auction_automatic_relist');
            }

            // Stock Update
            update_post_meta($new_product_id, '_manage_stock', 'yes');
            update_post_meta($new_product_id, '_stock_status', 'instock');
            update_post_meta($new_product_id, '_stock', 1);
        }
    }

    public function wcsauction_wcmp_pts_auctions_nav($nav) {
        global $WCMp;

        $nav['auctions'] = array(
            'label' => __('Auctions', 'wcmp-frontend_product_manager')
            , 'url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_report_endpoint', 'vendor', 'general', 'auctions'))
            , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_auctions_capability', true)
            , 'position' => 22
            , 'submenu' => array()
            , 'link_target' => '_self'
            , 'nav_icon' => 'dashicons-calendar-alt'
        );
        return $nav;
    }

    public function wcsauction_wcmp_pts_auctions_header() {
        global $WCMp;

        if ($WCMp->endpoints->get_current_endpoint() == 'auctions') {
            echo '<ul>';
            echo '<li>' . __('Auctions', 'wcmp-frontend_product_manager') . '</li>';
            echo '</ul>';
        }
    }

    public function wcsauction_wcmp_pts_auctions_endpoint() {
        global $WCMp, $wpdb;

        $vendor_id = get_current_user_id();

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
            $products = array(0);

        $query = "SELECT * FROM {$wpdb->prefix}simple_auction_log as auctions";
        if (!empty($products))
            $query .= " WHERE auctions.auction_id in (" . implode(',', $products) . ")";

        $vendor_auctions = $wpdb->get_results($query);

        if (!empty($vendor_auctions)) {
            echo '<div class="col-md-12"><div class="wcmp_tab ui-tabs ui-widget ui-widget-content ui-corner-all"><div class="wcmp_table_holder"><table class="wcmp_auction_tbl table table-striped table-bordered"><thead><tr><td>' . __('Auctions', 'wcmp-frontend_product_manager') . '</td><td>' . __('User', 'wcmp-frontend_product_manager') . '</td><td>' . __('Product', 'wcmp-frontend_product_manager') . '</td><td>' . __('Bid', 'wcmp-frontend_product_manager') . '</td><td style="text-align: right;">' . __('Date', 'wcmp-frontend_product_manager') . '</td></tr></thead><tbody>';
            foreach ($vendor_auctions as $vendor_auction) {
                $userdata = get_userdata($vendor_auction->userid);
                echo '<tr><td>#<strong style="color: #fc482f; text-decoration: underline;">' . $vendor_auction->id . '</strong></td>';
                echo '<td>' . esc_attr($userdata->user_nicename) . '</td>';
                echo '<td>' . get_the_title($vendor_auction->auction_id) . '</td>';
                echo '<td>' . wc_price($vendor_auction->bid) . '</td>';
                echo '<td>' . date('Y-m-d H:i A', strtotime($vendor_auction->date)) . '</td>';
            }
            echo '</tbody></table></div></div></div>';
        } else {
            ?>
            <div class="col-md-12"><h4>&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                    _e("You do not have any Auctions yet!!!", 'wcmp-frontend_product_manager');
                    ?>
                </h4></div>
            <?php
        }
    }

}
