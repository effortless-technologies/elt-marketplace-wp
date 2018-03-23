<?php

class WCMP_Vendor_Frontend_Report {

    public $plugin_url;
    public $plugin_path;
    public $version;
    public $token;
    public $text_domain;
    public $shortcode;
    public $admin;
    public $frontend;
    public $template;
    public $ajax;
    private $file;
    public $settings;
    public $license;
    public $wcmp_wp_fields;

    public function __construct($file) {

        $this->file = $file;
        $this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
        $this->plugin_path = trailingslashit(dirname($file));
        $this->token = WCMP_VENDOR_FRONTEND_REPORT_PLUGIN_TOKEN;
        $this->text_domain = WCMP_VENDOR_FRONTEND_REPORT_TEXT_DOMAIN;
        $this->version = WCMP_VENDOR_FRONTEND_REPORT_PLUGIN_VERSION;

        add_action('init', array(&$this, 'init'), 30);
    }

    /**
     * initilize plugin on WP init
     */
    function init() {
        global $WCMp;

        $this->wcmp_vendor_frontend_report_export();

        // Init Text Domain
        $this->load_plugin_textdomain();

        // Init ajax
        if (defined('DOING_AJAX')) {
            $this->load_class('ajax');
            $this->ajax = new WCMP_Vendor_Frontend_Report_Ajax();
        }

        if (is_admin()) {
            $this->load_class('admin');
            $this->admin = new WCMP_Vendor_Frontend_Report_Admin();
        }

        if (!is_admin() || defined('DOING_AJAX')) {
            $this->load_class('frontend');
            $this->frontend = new WCMP_Vendor_Frontend_Report_Frontend();

            // init shortcode
            $this->load_class('shortcode');
            $this->shortcode = new WCMP_Vendor_Frontend_Report_Shortcode();

            // init templates
            $this->load_class('template');
            $this->template = new WCMP_Vendor_Frontend_Report_Template();
        }

        // DC License Activation
        if (is_admin()) {
            $this->load_class('license');
            $this->license = WCMP_Vendor_Frontend_Report_LICENSE();
        }

        // DC Wp Fields
        $this->wcmp_wp_fields = $WCMp->wcmp_wp_fields;
    }

    /**
     * Load Localisation files.
     *
     * Note: the first-loaded translation file overrides any following ones if the same translation is present
     *
     * @access public
     * @return void
     */
    public function load_plugin_textdomain() {
        $locale = apply_filters('plugin_locale', get_locale(), $this->token);

        load_textdomain($this->text_domain, WP_LANG_DIR . "/wcmp-vendor-frontend-report/wcmp-vendor-frontend-report-$locale.mo");
        load_textdomain($this->text_domain, $this->plugin_path . "/languages/wcmp-vendor-frontend-report-$locale.mo");
    }

    public function load_class($class_name = '') {
        if ('' != $class_name && '' != $this->token) {
            require_once ('class-' . esc_attr($this->token) . '-' . esc_attr($class_name) . '.php');
        } // End If Statement
    }

// End load_class()

    /**
     * Install upon activation.
     *
     * @access public
     * @return void
     */
    static function activate_wcmp_vendor_frontend_report() {
        global $WCMp, $WCMp_Vendor_Frontend_Report;

        // License Activation
        $WCMp_Vendor_Frontend_Report->load_class('license');
        WCMP_Vendor_Frontend_Report_LICENSE()->activation();

        require_once ( $WCMp->plugin_path . 'includes/class-wcmp-install.php' );
        $WCMp_Install = new WCMp_Install();
        $WCMp_Install->wcmp_product_vendor_plugin_create_page(esc_sql(_x('frontend_vendor_reports', 'page_slug', 'wcmp-vendor_frontend_report')), 'wcmp_vendor_frontend_report_page_id', __('Reports', 'wcmp-vendor_frontend_report'), '[vendor_frontend_report]');
        $wcmp_vendor_frontend_report_page_id = get_option('wcmp_vendor_frontend_report_page_id');
        update_wcmp_vendor_settings('frontend_vendor_reports', $wcmp_vendor_frontend_report_page_id, 'vendor', 'general');

        update_option('wcmp_vendor_frontend_report_installed', 1);
    }

    /**
     * UnInstall upon deactivation.
     *
     * @access public
     * @return void
     */
    static function deactivate_wcmp_vendor_frontend_report() {
        global $WCMp_Vendor_Frontend_Report;
        delete_option('wcmp_vendor_frontend_report_installed');

        // License Deactivation
        $WCMp_Vendor_Frontend_Report->load_class('license');
        WCMP_Vendor_Frontend_Report_LICENSE()->uninstall();
    }

    /** Cache Helpers ******************************************************** */

    /**
     * Sets a constant preventing some caching plugins from caching a page. Used on dynamic pages
     *
     * @access public
     * @return void
     */
    function nocache() {
        if (!defined('DONOTCACHEPAGE'))
            define("DONOTCACHEPAGE", "true");
        // WP Super Cache constant
    }

    function wcmp_vendor_frontend_report_export() {
        global $WCMp;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $vendor_id = get_current_user_id();

            if (isset($_POST['export_sales_report_orders'])) {
                $give_tax_to_vendor = get_wcmp_vendor_settings('give_tax','payment');
                $give_shipping_to_vendor = get_wcmp_vendor_settings('give_shipping','payment');
                $date = date('d-m-Y');
                $filename = 'salesReport-' . $date . '.csv';
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename={$filename}");
                header("Content-Transfer-Encoding: binary");
                header("Content-Type: charset=UTF-8");

                $headers = array(
                    'date' => __('Date', $WCMp->text_domain),
                    'gross_sale' => __('Gross Sale', $WCMp->text_domain),
                    'avg_sale' => __('Average Sale', $WCMp->text_domain),
                    'no_of_orders' => __('No of orders', $WCMp->text_domain),
                    'my_earnings' => __('My earnings', $WCMp->text_domain),
                );

                $total_order_count = 0;
                $total_earnings = 0;
                $total_sales = 0;
                $total_avg_sales = 0;
                $total_vendor_earnings = 0;
                $paid_amt = 0;
                $chart_data = array();

                if (isset($_GET['from_date']))
                    $start_date = strtotime($_GET['from_date']);
                else
                    $start_date = strtotime(date('Ymd', strtotime(date('Ym', current_time('timestamp')) . '01')));

                if (isset($_GET['to_date']))
                    $end_date = strtotime($_GET['to_date']);
                else
                    $end_date = strtotime(date('Ymd', current_time('timestamp')));
                $order_datas = array();
                for ($date = $start_date; $date <= $end_date; $date = strtotime('+1 day', $date)) {

                    $year = date('Y', $date);
                    $month = date('n', $date);
                    $day = date('j', $date);

                    $line_total = $sales = $comm_amount = $vendor_earnings = $earnings = $order_count = 0;

                    $args = array(
                        'post_type' => 'shop_order',
                        'posts_per_page' => -1,
                        'post_status' => array('wc-pending', 'wc-processing', 'wc-on-hold', 'wc-completed', 'wc-cancelled', 'wc-refunded', 'wc-failed'),
                        'meta_query' => array(
                            array(
                                'key' => '_commissions_processed',
                                'value' => 'yes',
                                'compare' => '='
                            )
                        ),
                        'date_query' => array(
                            array(
                                'year' => $year,
                                'month' => $month,
                                'day' => $day,
                            ),
                        )
                    );

                    $qry = new WP_Query($args);

                    $orders = apply_filters('wcmp_filter_orders_report_overview', $qry->get_posts());
                    if (!empty($orders)) {
                        foreach ($orders as $order_obj) {

                            $order = new WC_Order($order_obj->ID);
                            $items = $order->get_items('line_item');
                            $shipping_items = $order->get_items('shipping');
                            $commission_array = array();

                            foreach ($items as $item_id => $item) {

                                $comm_pro_id = $product_id = wc_get_order_item_meta($item_id, '_product_id', true);
                                $line_total = wc_get_order_item_meta($item_id, '_line_total', true);
                                $variation_id = wc_get_order_item_meta($item_id, '_variation_id', true);
                                $line_tax = wc_get_order_item_meta($item_id, '_line_tax', true);
                                if ($variation_id)
                                    $comm_pro_id = $variation_id;

                                if ($product_id && $line_total) {

                                    $product_vendors = get_wcmp_product_vendors($product_id);

                                    if ($product_vendors) {

                                        if ($vendor_id != $product_vendors->id)
                                            continue;

                                        if ($give_tax_to_vendor == 'Enable') {
                                            $sales += ($line_total + $line_tax);
                                            $total_sales += ($line_total + $line_tax);
                                        } else {
                                            $sales += $line_total;
                                            $total_sales += $line_total;
                                        }

                                        $args = array(
                                            'post_type' => 'dc_commission',
                                            'post_status' => array('publish', 'private'),
                                            'posts_per_page' => -1,
                                            'meta_query' => array(
                                                array(
                                                    'key' => '_commission_vendor',
                                                    'value' => absint($product_vendors->term_id),
                                                    'compare' => '='
                                                ),
                                                array(
                                                    'key' => '_commission_order_id',
                                                    'value' => absint($order_obj->ID),
                                                    'compare' => '='
                                                ),
                                                array(
                                                    'key' => '_commission_product',
                                                    'value' => absint($comm_pro_id),
                                                    'compare' => 'LIKE'
                                                ),
                                            ),
                                        );
                                        $commissions = get_posts($args);
                                        $comm_amount = 0;
                                        if (!empty($commissions)) {
                                            foreach ($commissions as $commission) {


                                                if (in_array($commission->ID, $commission_array))
                                                    continue;

                                                $comm_amount += (float) get_post_meta($commission->ID, '_commission_amount', true);

                                                $commission_status = get_post_meta($commission->ID, '_paid_status', true);

                                                if ($commission_status == 'paid')
                                                    $paid_amt += $comm_amount;

                                                $commission_array[] = $commission->ID;
                                            }
                                        }

                                        if($give_tax_to_vendor == 'Enable'){
                                            $vendor_earnings += ($comm_amount + $line_tax);
                                            $total_vendor_earnings += ($comm_amount + $line_tax);
                                            $earnings += (( $line_total - $comm_amount ) + $line_tax);
                                            $total_earnings += (( $line_total - $comm_amount ) + $line_tax);
                                        } else{
                                            $vendor_earnings += $comm_amount;
                                            $total_vendor_earnings += $comm_amount;
                                            $earnings += ( $line_total - $comm_amount );
                                            $total_earnings += ( $line_total - $comm_amount );
                                        }
                                    }
                                }
                            }
                            $vendor_shipping_amount = 0;
                            if(!empty($shipping_items) && $give_shipping_to_vendor == 'Enable'){
                               foreach ($shipping_items as $shipping_id => $shipping){
                                    $vendor_shipping_amount = (float)wc_get_order_item_meta($shipping_id,'vendor_cost_'.$vendor_id,true);
                                    $vendor_shipping_tax_array = wc_get_order_item_meta($shipping_id,'vendor_tax_'.$vendor_id,true);
                                    $vendor_shipping_tax = 0;
                                    foreach ($vendor_shipping_tax_array as $shipping_tax){
                                        $vendor_shipping_tax += (float)$shipping_tax;
                                    }
                                    $total_sales += ($vendor_shipping_amount + $vendor_shipping_tax);
                                    $sales += ($vendor_shipping_amount + $vendor_shipping_tax);
                                    $vendor_earnings += ($vendor_shipping_amount + $vendor_shipping_tax);
                                    $total_vendor_earnings += ($vendor_shipping_amount + $vendor_shipping_tax);
                                    $earnings += ($vendor_shipping_amount + $vendor_shipping_tax);
                                    $total_earnings += ($vendor_shipping_amount + $vendor_shipping_tax);
                                } 
                            }
                            ++$order_count;
                            ++$total_order_count;
                        }
                    }

                    if ($order_count > 0)
                        $avg_sales = $sales / $order_count;
                    else
                        $avg_sales = 0;
                    $order_datas[] = array('date' => date("Y-m-d", $date), 'gross_sale' => $sales, 'avg_sale' => $avg_sales, 'no_of_orders' => $order_count, 'my_earnings' => $vendor_earnings);
                }


                // Initiate output buffer and open file
                ob_start();
                $file = fopen("php://output", 'w');

                // Add headers to file
                fputcsv($file, $headers);
                // Add data to file
                if (!empty($order_datas)) {
                    foreach ($order_datas as $order_data) {
                        fputcsv($file, $order_data);
                    }
                } else {
                    fputcsv($file, array('Sorry. no transaction data is available upon your selection'));
                }

                // Close file and get data from output buffer
                fclose($file);
                $csv = ob_get_clean();

                // Send CSV to browser for download
                echo $csv;
                die();
            }

            if (isset($_POST['export_transaction_report_orders'])) {
                $user_id = get_current_user_id();
                $vendor = get_wcmp_vendor($user_id);
                $order_datas = array();
                $date = date('d-m-Y');
                $filename = 'transactionReport-' . $date . '.csv';
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Disposition: attachment;filename={$filename}");
                header("Content-Transfer-Encoding: binary");
                header("Content-Type: charset=UTF-8");

                $headers = array(
                    'date' => __('Date', $WCMp->text_domain),
                    'transaction_amount' => __('Gross Credit', $WCMp->text_domain),
                    'avg_transaction_amount' => __('Average daily Credit', $WCMp->text_domain),
                    'transfer_charge' => __('Debit for transfer charge', $WCMp->text_domain),
                    'transaction_count' => __('No of transactions', $WCMp->text_domain),
                    'order_count' => __('No of unit ordered', $WCMp->text_domain),
                );


                $total_transaction_amount = $toatl_transfer_charge = $total_order_countt = $total_transaction_count = 0;

                if (isset($_GET['transaction_from_date']))
                    $start_date = strtotime($_GET['transaction_from_date']);
                else
                    $start_date = strtotime(date('Ymd', strtotime(date('Ym', current_time('timestamp')) . '01')));

                if (isset($_GET['transaction_to_date']))
                    $end_date = strtotime($_GET['transaction_to_date']);
                else
                    $end_date = strtotime(date('Ymd', current_time('timestamp')));

                for ($date = $start_date; $date <= $end_date; $date = strtotime('+1 day', $date)) {

                    $transaction_amount = $transfer_charge = $order_count = $transaction_count = 0;

                    $transactions = apply_filters('wcmp_filter_transactions_report_overview', $WCMp->transaction->get_transactions($vendor->term_id, date('j-n-Y', $date)));

                    if (!empty($transactions)) {
                        foreach ($transactions as $transaction) {
                            $transaction_id = $transaction_amount += $transaction['amount'];
                            $transfer_charge += $transaction['transfer_charge'];
                            if (!empty($transaction['commission_details'])) {
                                foreach ($transaction['commission_details'] as $order_id) {
                                    $order_count++;
                                    $total_order_countt++;
                                }
                            }
                            $transaction_count++;
                            $total_transaction_count++;
                            $total_transaction_amount += $transaction_amount;
                            $toatl_transfer_charge += $transfer_charge;
                        }
                    }
                    if ($transaction_count > 0)
                        $avg_transactions = $transaction_amount / $transaction_count;
                    else
                        $avg_transactions = 0;
                    $order_datas[] = array('date' => date("Y-m-d", $date), 'transaction_amount' => $transaction_amount, 'avg_transaction_amount' => $avg_transactions, 'transfer_charge' => $transfer_charge, 'transaction_count' => $transaction_count, 'order_count' => $order_count);
                }
                // Initiate output buffer and open file
                ob_start();
                $file = fopen("php://output", 'w');

                // Add headers to file
                fputcsv($file, $headers);
                // Add data to file
                if (!empty($order_datas)) {
                    foreach ($order_datas as $order_data) {
                        fputcsv($file, $order_data);
                    }
                } else {
                    fputcsv($file, array('Sorry. no transaction data is available upon your selection'));
                }

                // Close file and get data from output buffer
                fclose($file);
                $csv = ob_get_clean();

                // Send CSV to browser for download
                echo $csv;
                die();
            }
        }
    }

}
