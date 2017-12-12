<?php
require_once ABSPATH . '/wp-content/plugins/woocommerce-pdf-invoices-packing-slips/includes/class-wcpdf-pdf-maker.php';
use \WPO\WC\PDF_Invoices\PDF_Maker;
class WCMp_PDF_Invoices_Frontend {

    public function __construct() {
        $options_all = get_option( 'wcmp_pdf_invoices_settings_name' );
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_scripts'));
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_styles'));

        add_filter('wcmp_my_account_my_orders_actions', array($this, 'wcmp_my_account_my_orders_actions'), 10, 2);
        add_filter('wcmp_vendor_dashboard_nav', array(&$this, 'add_invoice_tab_to_wcmp_vendor_dashboard_nav'));
        add_filter('woocommerce_my_account_my_orders_actions', array($this, 'woocommerce_my_account_my_orders_actions'), 30, 2);
        add_action('woocommerce_checkout_order_processed', array(&$this, 'create_pdf_invoice_per_order'), 40, 3);
        if(isset($options_all['is_packing_slip_customer'])) {
            add_action('woocommerce_checkout_order_processed', array(&$this, 'create_pdf_packing_slip_per_order'), 40, 3);
        }
    }

    function create_pdf_invoice_per_order($order_id, $posted_data, $order) {
        global $WCMp_PDF_Invoices;
        $order = new WC_Order($order_id);
        $general_settings = get_option('wcmp_pdf_invoices_settings_name');
        $template = isset($general_settings['choose_invoice_template']) ? $general_settings['choose_invoice_template'] : 'wcmp_pdf_invoice_first_template';
        $upload_dir = wp_upload_dir();
        $base_path2 = trailingslashit($upload_dir['basedir']) . 'wcmp_pdf_invoice/' . $order_id;
        $file_to_save = $base_path2 . '/admin_' . $order_id . '.pdf';

        if (!file_exists($base_path2)) {
            mkdir($base_path2, 0777, true);
        }
        ob_start();
        $WCMp_PDF_Invoices->template->get_template($template . '.php', array('order' => $order, 'general_settings' => $general_settings, 'user_type' => 'admin', 'vendor' => ''));
        $ob_get_clean = ob_get_clean();
        if ($ob_get_clean) {
            $abcd = new PDF_Maker($ob_get_clean,array());
            file_put_contents($file_to_save, $abcd->output());
            $base_url = trailingslashit($upload_dir['baseurl']) . 'wcmp_pdf_invoice/' . $order_id;
            $base_path = $upload_dir['basedir'] . '/wcmp_pdf_invoice/' . $order_id;
            $file_url = $base_url . '/admin_' . $order_id . '.pdf';
            $file_path = $base_path . '/admin_' . $order_id . '.pdf';
            update_post_meta($order_id, '_pdf_invoice_per_order', $file_url);
            update_post_meta($order_id, '_pdf_invoice_per_order_path', $file_path);
        }
    }

    function create_pdf_packing_slip_per_order($order_id, $posted_data, $order) {
        
        global $WCMp_PDF_Invoices;
        $general_settings = get_option('wcmp_pdf_invoices_settings_name');
        $upload_dir = wp_upload_dir();
        $base_path2 = trailingslashit($upload_dir['basedir']) . 'wcmp_pdf_invoice/' . $order_id;
        $file_to_save = $base_path2 . '/packing_slip_' . $order_id . '.pdf';
        $vendor = get_wcmp_vendor(get_current_user_id());
        if (!file_exists($base_path2)) {
            mkdir($base_path2, 0777, true);
        }
        ob_start();
        $WCMp_PDF_Invoices->template->get_template('wcmp_packing_slip_first_template.php', array('order' => $order,  'order_id' => $order_id, 'user_type' => 'vendor','general_settings' => $general_settings));
        $ob_get_clean = ob_get_clean();
        if ($ob_get_clean) {
             $abcd = new PDF_Maker($ob_get_clean,array());
            file_put_contents($file_to_save, $abcd->output());
            $base_url = trailingslashit($upload_dir['baseurl']) . 'wcmp_pdf_invoice/' . $order_id;
            $base_path = $upload_dir['basedir'] . '/wcmp_pdf_invoice/' . $order_id;
            $file_url = $base_url . '/packing_slip_' . $order_id . '.pdf';
            $file_path = $base_path . '/packing_slip_' . $order_id . '.pdf';
            update_post_meta($order_id, '_pdf_packing_slip_per_order', $file_url);
            update_post_meta($order_id, '_pdf_packing_slip_per_order_path', $file_path);
        }
    }


    function add_vendor_edit_invoice_report_callback($vendor, $selected_item) {
        global $WCMp, $WCMp_PDF_Invoices;
        $pages = get_option('wcmp_pages_settings_name');
        ?>
        <li><a <?php
            if ($selected_item == "vendor_edit_invoice") {
                echo 'class="selected_menu"';
            }
            ?> data-menu_item="vendor_edit_invoice" target="_blank" href="<?php echo apply_filters('wcmp_edit_vendor_pdf_invoice', get_permalink($pages['wcmp_vendor_edit_invoice'])); ?>"><?php _e('- Edit Invoice', 'wcmp-pdf_invoices'); ?></a></li>
        <?php
    }

    public function add_invoice_tab_to_wcmp_vendor_dashboard_nav($nav) {
        global $WCMp_PDF_Invoices;
        $nav['vendor-report']['submenu']['vendor-invoice'] = array(
            'label' => __('Edit Invoice', 'wcmp-pdf_invoices')
            , 'url' => get_permalink(get_wcmp_vendor_settings('wcmp_vendor_edit_invoice', 'vendor', 'general'))
            , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_invoice_capability', true)
            , 'position' => 20
            , 'link_target' => '_blank'
        );
        return $nav;
    }

    function frontend_scripts() {
        global $WCMp_PDF_Invoices;
        $frontend_script_path = $WCMp_PDF_Invoices->plugin_url . 'assets/frontend/js/';
        $frontend_script_path = str_replace(array('http:', 'https:'), '', $frontend_script_path);
        $pluginURL = str_replace(array('http:', 'https:'), '', $WCMp_PDF_Invoices->plugin_url);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        // Enqueue your frontend javascript from here
    }

    function frontend_styles() {
        global $WCMp_PDF_Invoices;
        $frontend_style_path = $WCMp_PDF_Invoices->plugin_url . 'assets/frontend/css/';
        $frontend_style_path = str_replace(array('http:', 'https:'), '', $frontend_style_path);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        // Enqueue your frontend stylesheet from here
    }

    function wcmp_my_account_my_orders_actions($actions, $order) {
        global $WCMp_PDF_Invoices;
        $options_all = get_option( 'wcmp_pdf_invoices_settings_name' );
        $get_vendor_pdf_url = admin_url('admin-ajax.php?action=wcmp_get_vendor_pdf&order_id=' . $order . '&nonce=' . wp_create_nonce('wcmp_get_vendor_pdf'));

        $actions['pdf_download'] = array(
            'url' => $get_vendor_pdf_url,
            'img' => $WCMp_PDF_Invoices->plugin_url . 'assets/images/pdf.png',
            'title' => 'Download PDF Invoice',
        );

        if(isset($options_all['is_packing_slip_customer'])) {
            $get_packing_slip_pdf_url = admin_url('admin-ajax.php?action=wcmp_get_packing_slip_pdf&order_id=' . $order . '&nonce=' . wp_create_nonce('wcmp_get_packing_slip_pdf'));
            
            $actions['packing_slip_download'] = array(
                'url' => $get_packing_slip_pdf_url,
                'img' => $WCMp_PDF_Invoices->plugin_url.'assets/images/pdf.png',
                'title' => 'Download Packing Slip Invoice',
            );
        }
        return $actions;
    }

    function woocommerce_my_account_my_orders_actions($actions, $order) {
        $wcmp_get_pdf_settings = get_option('wcmp_pdf_invoices_settings_name');
        $valid_order_status = array();
        if (!empty($wcmp_get_pdf_settings['status_to_download_invoice'])) {
            $valid_order_status[] = $wcmp_get_pdf_settings['status_to_download_invoice'];
            if (in_array('wc-' . $order->get_status('edit'), $valid_order_status)) {
                $actions['pdf_download'] = array(
                    'url' => admin_url('admin-ajax.php?action=wcmp_get_customer_pdf&order_id=' . $order->get_id() . '&nonce=' . wp_create_nonce('wcmp_get_customer_pdf')),
                    'name' => __('Download Invoice', 'woocommerce')
                );
            }
        }
        return $actions;
    }

}
