<?php
if (!function_exists('get_vendor_frontend_report_settings')) {

    function get_vendor_frontend_report_settings($name = '', $tab = '') {
        if (empty($tab) && empty($name))
            return '';
        if (empty($tab))
            return get_option($name);
        if (empty($name))
            return get_option("dc_{$tab}_settings_name");
        $settings = get_option("dc_{$tab}_settings_name");
        if (!isset($settings[$name]))
            return '';
        return $settings[$name];
    }

}
if (!function_exists('is_vendor_frontend_report_page')) {

    function is_vendor_frontend_report_page() {
        if (!empty(get_wcmp_vendor_settings('frontend_vendor_reports', 'vendor', 'general'))) {
            return is_page((int)get_wcmp_vendor_settings('frontend_vendor_reports', 'vendor', 'general')) ? true : false;
        }
        return false;
    }

}
if (!function_exists('dc_vendor_frontend_prepare_chart_data')) {

    function dc_vendor_frontend_prepare_chart_data($data, $date_key, $data_key, $interval, $start_date, $group_by) {
        $prepared_data = array();

        // Ensure all days (or months) have values first in this range
        for ($i = 0; $i <= $interval; $i ++) {
            switch ($group_by) {
                case 'day' :
                    $time = strtotime(date('Ymd', strtotime("+{$i} DAY", $start_date))) . '000';
                    break;
                case 'month' :
                default :
                    $time = strtotime(date('Ym', strtotime("+{$i} MONTH", $start_date)) . '01') . '000';
                    break;
            }

            if (!isset($prepared_data[$time])) {
                $prepared_data[$time] = array(esc_js($time), 0);
            }
        }

        foreach ($data as $d) {
            switch ($group_by) {
                case 'day' :
                    $time = strtotime(date('Ymd', strtotime($d->$date_key))) . '000';
                    break;
                case 'month' :
                default :
                    $time = strtotime(date('Ym', strtotime($d->$date_key)) . '01') . '000';
                    break;
            }

            if (!isset($prepared_data[$time])) {
                continue;
            }

            if ($data_key) {
                $prepared_data[$time][1] += $d->$data_key;
            } else {
                $prepared_data[$time][1] ++;
            }
        }

        return $prepared_data;
    }

}
if (!function_exists('dcvendorArrayToObject')) {

    function dcvendorArrayToObject($d) {
        if (is_array($d)) {
            /*
             * Return array converted to object
             * Using __FUNCTION__ (Magic constant)
             * for recursive call
             */
            return (object) array_map(__FUNCTION__, $d);
        } else {
            // Return object
            return $d;
        }
    }

}
if (!function_exists('dcvendor_get_currency_tooltip')) {

    function dcvendor_get_currency_tooltip() {
        switch (get_option('woocommerce_currency_pos')) {
            case 'right':
                $currency_tooltip = 'append_tooltip: "' . get_woocommerce_currency_symbol() . '"';
                break;
            case 'right_space':
                $currency_tooltip = 'append_tooltip: "&nbsp;' . get_woocommerce_currency_symbol() . '"';
                break;
            case 'left':
                $currency_tooltip = 'prepend_tooltip: "' . get_woocommerce_currency_symbol() . '"';
                break;
            case 'left_space':
            default:
                $currency_tooltip = 'prepend_tooltip: "' . get_woocommerce_currency_symbol() . '&nbsp;"';
                break;
        }

        return $currency_tooltip;
    }

}

if (!function_exists('dc_vendor_frontend_round_chart_totals')) {

    function dc_vendor_frontend_round_chart_totals($amount) {
        if (is_array($amount)) {
            return array($amount[0], wc_format_decimal($amount[1], wc_get_price_decimals()));
        } else {
            return wc_format_decimal($amount, wc_get_price_decimals());
        }
    }

}

if (!function_exists('vfr_woocommerce_inactive_notice')) {

    function vfr_woocommerce_inactive_notice() {
        ?>
        <div id="message" class="error">
            <p><?php printf(__('%sWCMp Vendor Frontend Report is inactive.%s The %sWooCommerce plugin%s must be active for the WCMp Vendor Frontend Report to work. Please %sinstall & activate WooCommerce%s', WCMP_VENDOR_FRONTEND_REPORT_TEXT_DOMAIN), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url('plugin-install.php?tab=search&s=woocommerce') . '">', '&nbsp;&raquo;</a>'); ?></p>
        </div>
        <?php
    }

}

if (!function_exists('vfr_wcmp_inactive_notice')) {

    function vfr_wcmp_inactive_notice() {
        ?>
        <div id="message" class="error">
            <p><?php printf(__('%sWCMp Vendor Frontend Report is inactive.%s The %sWC Marketplace%s must be active for the WCMp Vendor Frontend Report to work. Please %sinstall & activate WC Marketplace%s', WCMP_VENDOR_FRONTEND_REPORT_TEXT_DOMAIN), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/dc-woocommerce-multi-vendor/">', '</a>', '<a href="' . admin_url('plugin-install.php?tab=search&s=wc+marketplace') . '">', '&nbsp;&raquo;</a>'); ?></p>
        </div>
        <?php
    }

}
?>