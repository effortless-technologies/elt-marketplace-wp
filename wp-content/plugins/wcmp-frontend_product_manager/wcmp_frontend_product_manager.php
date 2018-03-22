<?php

/**
 * Plugin Name: Advanced Frontend Manager
 * Plugin URI: https://wc-marketplace.com/product/wcmp-frontend-product-manager/
 * Description: Allow your vendors to manage their individual shops from the front end using the Advanced Frontend Manager.
 * Author: WC Marketplace, Arim Ghosh
 * Version: 2.2.2
 * Author URI: https://wc-marketplace.com
 *
 * Text Domain: wcmp-frontend_product_manager
 * Domain Path: /languages/
 *
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
if (!function_exists('wp_handle_upload')) {
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
}

if (!class_exists('WCMp_Frontend_Product_Manager_Dependencies')) {
    require_once 'includes/class-wcmp-frontend-product-manager-dependencies.php';
}
require_once 'includes/wcmp-frontend-product-manager-core-functions.php';
require_once 'frontend_product_manager_config.php';

if (!defined('WCMP_FRONTEND_PRODUCT_MANAGER_PLUGIN_TOKEN'))
    exit;
if (!defined('WCMP_FRONTEND_PRODUCT_MANAGER_TEXT_DOMAIN'))
    exit;


if (!WCMp_Frontend_Product_Manager_Dependencies::woocommerce_plugin_active_check()) {
    add_action('admin_notices', 'fpm_woocommerce_inactive_notice');
}

if (!WCMp_Frontend_Product_Manager_Dependencies::wc_marketplace_plugin_active_check()) {
    add_action('admin_notices', 'fpm_wcmp_inactive_notice');
}

$environment = WCMp_Frontend_Product_Manager_Dependencies::fpm_environment_check();
if ($environment['pass']) {
    if (class_exists('WCMp')) {

        if (!class_exists('wcmp-frontend_product_manager')) {
            require_once( 'classes/class-wcmp-frontend-product-manager.php' );
            global $WCMp_Frontend_Product_Manager;
            $WCMp_Frontend_Product_Manager = new WCMp_Frontend_Product_Manager(__FILE__);
            $GLOBALS['WCMp_Frontend_Product_Manager'] = $WCMp_Frontend_Product_Manager;
            // Activation Hooks
            register_activation_hook(__FILE__, array('wcmp-frontend_product_manager', 'activate_wcmp_frontend_product_manager'));
            register_activation_hook(__FILE__, 'flush_rewrite_rules');

            register_activation_hook(ABSPATH . 'wp-content/plugins/woocommerce-bookings/woocommerce-bookings.php', array('wcmp-frontend_product_manager', 'woo_booking_activate'));
            register_activation_hook(__FILE__, 'flush_rewrite_rules');

            // Deactivation Hooks
            register_deactivation_hook(__FILE__, array('wcmp-frontend_product_manager', 'deactivate_wcmp_frontend_product_manager'));

            register_deactivation_hook(ABSPATH . 'wp-content/plugins/woocommerce-bookings/woocommerce-bookings.php', array('wcmp-frontend_product_manager', 'deactivate_woo_booking'));
        }
    } else {
        add_action('admin_notices', 'fpm_wcmp_inactive_notice');
    }
} else {
    unset($environment['pass']);
    foreach ($environment as $notice => $pass) {
        if (!$pass) {
            add_action('admin_notices', $notice.'_notice');
        }
    }
}