<?php
/*
 * The template for displaying vendor dashboard
 * Override this template by copying it to yourtheme/dc-product-vendor/vendor-dashboard/dashboard.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp/Templates
 * @version   2.4.5
 */
if (!defined('ABSPATH')) {
    // Exit if accessed directly
    exit;
}
global $woocommerce, $WCMp;
$user = wp_get_current_user();
$vendor = get_wcmp_vendor($user->ID);

if (is_user_wcmp_vendor($user->ID)) :
            ?>
            <div class="vendor_non_configuration_msg">
                <?php _e('<h4>Welcome to Staff Dashboard!</h4>', 'wcmp-sub_vendor'); ?>
            </div>
    <div class="wcmp_tab">
    </div>
<?php endif; ?>
