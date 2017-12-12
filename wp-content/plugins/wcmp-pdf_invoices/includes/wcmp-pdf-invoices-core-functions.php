<?php
if (!function_exists('woocommerce_inactive_notice')) {

    function woocommerce_inactive_notice() {
        ?>
        <div id="message" class="error">
            <p><?php printf(__('%sWCMp Vendor PDF Invoices is inactive.%s The %sWooCommerce%s plugin must be active for the WCMp Vendor PDF Invoices to work. Please %sinstall & activate WooCommerce%s', 'wcmp-pdf_invoices'), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/woocommerce/">', '</a>', '<a href="' . admin_url('plugin-install.php?tab=search&s=woocommerce') . '">', '&nbsp;&raquo;</a>'); ?></p>
        </div>
        <?php
    }

}

if (!function_exists('wcmp_inactive_notice')) {

    function wcmp_inactive_notice() {
        ?>
        <div id="message" class="error">
            <p><?php printf(__('%sWCMp Vendor PDF Invoices is inactive.%s The %sWC Marketplace%s plugin must be active for the WCMp Vendor PDF Invoices to work. Please %sinstall & activate WC Marketplace%s', 'wcmp-pdf_invoices'), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/dc-woocommerce-multi-vendor/">', '</a>', '<a href="' . admin_url('plugin-install.php?tab=search&s=wc+marketplace') . '">', '&nbsp;&raquo;</a>'); ?></p>
        </div>
        <?php
    }

}


if (!function_exists('wpips_inactive_notice')) {

    function wpips_inactive_notice() {
        ?>
        <div id="message" class="error">
            <p><?php printf(__('%sWCMp Vendor PDF Invoices is inactive.%s The %sWooCommerce PDF Invoices & Packing Slips%s plugin must be active for the WCMp Vendor PDF Invoices to work. Please %sinstall & activate WooCommerce PDF Invoices & Packing Slips%s', 'wcmp-pdf_invoices'), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/">', '</a>', '<a href="' . admin_url('plugin-install.php?tab=search&s=WooCommerce+PDF+Invoices+%26+Packing+Slips') . '">', '&nbsp;&raquo;</a>'); ?></p>
        </div>
        <?php
    }

}

if (!function_exists('wcmp_get_vendor_items_from_order')) {

    function wcmp_get_vendor_items_from_order($order_id) {
        $vendors = array();
        $order = new WC_Order($order_id);
        if ($order) {
            $items = $order->get_items('line_item');
            if ($items) {
                foreach ($items as $item_id => $item) {
                    $product_id = wc_get_order_item_meta($item_id, '_product_id', true);

                    if ($product_id) {
                        $product_vendors = get_wcmp_product_vendors($product_id);
                        if (!empty($product_vendors)) {
                            $vendors[$product_vendors->term_id] = get_wcmp_vendor_by_term($product_vendors->term_id);
                        }
                    }
                }
            }
        }
        return $vendors;
    }

}

function activate_wcmp_pdf_invoicess() {
    
}
?>
