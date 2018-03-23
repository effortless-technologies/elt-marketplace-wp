<?php

class WCMP_Pdf_Invoices_Ajax {

    public function __construct() {
        $options_all = get_option( 'wcmp_pdf_invoices_settings_name' );
        add_action('wp_ajax_create_per_order_pdf', array(&$this, 'create_per_order_pdf'));

        add_action('wp_ajax_create_per_vendor_pdf', array(&$this, 'create_per_vendor_pdf'));

        add_action('wp_ajax_wcmp_get_vendor_pdf', array(&$this, 'wcmp_get_vendor_pdf'));

        if(isset($options_all['is_packing_slip_customer'])) {
            add_action('wp_ajax_wcmp_get_packing_slip_pdf', array(&$this, 'wcmp_get_packing_slip_pdf'));
        }
        add_action('wp_ajax_wcmp_get_customer_pdf', array(&$this, 'wcmp_get_customer_pdf'));
        
        
        add_action('wp_ajax_cancel_per_order_pdf', array(&$this, 'cancel_per_order_pdf'));
        add_action('wp_ajax_cancel_per_vendor_pdf', array(&$this, 'cancel_per_vendor_pdf'));
    }

    public function wcmp_get_customer_pdf() {
        global $WCMp_PDF_Invoices;

        if (isset($_GET['action']) && isset($_GET['order_id']) && isset($_GET['nonce'])) {
            $action = $_GET['action'];
            $order_id = $_GET['order_id'];
            $nonce = $_REQUEST["nonce"];

            if (!wp_verify_nonce($nonce, $action))
                die('Invalid request');

            $wp_get_current_user = wp_get_current_user();
            $order = new WC_Order($order_id);

            $general_settings = get_option('wcmp_pdf_invoices_settings_name');

            $template = isset($general_settings['choose_invoice_template']) ? $general_settings['choose_invoice_template'] : 'wcmp_pdf_invoice_first_template';

            $upload_dir = wp_upload_dir();
            $base_path2 = trailingslashit($upload_dir['basedir']) . 'wcmp_pdf_invoice/' . $order_id . '/customer';
            $file_to_save = $base_path2 . '/' . $wp_get_current_user->user_login . '_' . $order_id . '.pdf';

            if (!file_exists($base_path2)) {
                mkdir($base_path2, 0777, true);
            }
            
            ob_start();
            $WCMp_PDF_Invoices->template->get_template($template . '.php', array('order' => $order, 'general_settings' => $general_settings, 'user_type' => 'customer'));
            $ob_get_clean = ob_get_clean();
            if ($ob_get_clean) {

               $pdf_maker = wcpdf_get_pdf_maker($ob_get_clean, array());
                file_put_contents($file_to_save, $pdf_maker->output());
                $base_url = trailingslashit($upload_dir['baseurl']) . 'wcmp_pdf_invoice/' . $order_id . '/customer';
                $file_url = $base_url . '/' . $wp_get_current_user->user_login . '_' . $order_id . '.pdf';
                update_post_meta($order_id, '_pdf_invoice_by_customer' . $wp_get_current_user->user_login, $file_url);

                if (file_exists($file_to_save)) {
                    wcpdf_pdf_headers( $order_id.'.pdf', 'download', $pdf_maker->output() );
                    @readfile($file_to_save);
                    exit;
                } else {
                    echo 'fail';
                }
            } else {
                echo 'fail';
            }
            die;
        }
    }

    public function wcmp_get_vendor_pdf() {
        global $WCMp_PDF_Invoices;

        if (isset($_GET['action']) && isset($_GET['order_id']) && isset($_GET['nonce'])) {
            $action = $_GET['action'];
            $order_id = $_GET['order_id'];
            $nonce = $_REQUEST["nonce"];

            if (!wp_verify_nonce($nonce, $action))
                die('Invalid request');
            $order = new WC_Order($order_id);

            $vendor = get_wcmp_vendor(get_current_user_id());
            if (!$vendor)
                echo 'fail';

            $general_settings = get_option('wcmp_pdf_invoices_settings_name');
            $get_user_settings = get_user_meta(get_current_user_id(), 'wcmp_pdf_invoices_settings', true);

            if ($get_user_settings) {
                $template = $get_user_settings['choose_preferred_template'];
            } else {
                $template = $general_settings['choose_invoice_template'];
            }
            $upload_dir = wp_upload_dir();
            $base_path2 = trailingslashit($upload_dir['basedir']) . 'wcmp_pdf_invoice/' . $order_id;
            $file_to_save = $base_path2 . '/vendor_' . sanitize_title($vendor->user_data->data->display_name) . '_' . $order_id . '.pdf';

            if (!file_exists($base_path2)) {
                mkdir($base_path2, 0777, true);
            }
            ob_start();

            $WCMp_PDF_Invoices->template->get_template($template . '.php', array('order' => $order, 'general_settings' => $general_settings, 'vendor' => $vendor, 'user_type' => 'vendor'));

            $ob_get_clean = ob_get_clean();
            if ($ob_get_clean) {
                $pdf_maker = wcpdf_get_pdf_maker($ob_get_clean, array());
                file_put_contents($file_to_save, $pdf_maker->output());
                $base_url = trailingslashit($upload_dir['baseurl']) . 'wcmp_pdf_invoice/' . $order_id;
                $file_url = $base_url . '/vendor_' . sanitize_title($vendor->user_data->data->display_name) . '_' . $order_id . '.pdf';
                update_post_meta($order_id, '_pdf_invoice_by_vendor' . sanitize_title($vendor->user_data->data->display_name), $file_url);

                if (file_exists($file_to_save)) {
                    wcpdf_pdf_headers( $order_id.'.pdf', 'download', $pdf_maker->output() );
                    @readfile($file_to_save);
                    exit;
                } else {
                    echo 'fail';
                }
            } else {
                echo 'fail';
            }

            die;
        }
    }





    public function wcmp_get_packing_slip_pdf() {
        global $WCMp_PDF_Invoices;
    
        if (isset($_GET['action']) && isset($_GET['order_id']) && isset($_GET['nonce'])) {
            $action = $_GET['action'];
            $order_id = $_GET['order_id'];
            $nonce = $_REQUEST["nonce"];

            if (!wp_verify_nonce($nonce, $action))
                die('Invalid request');
            $order = new WC_Order($order_id);

            $vendor = get_wcmp_vendor(get_current_user_id());
            if (!$vendor)
                echo 'fail';

            $general_settings = get_option('wcmp_pdf_invoices_settings_name');

            /*if ($get_user_settings) {
                $template = $get_user_settings['choose_preferred_template'];
            } else {
                $template = $general_settings['choose_invoice_template'];
            }*/
            $upload_dir = wp_upload_dir();
            $base_path2 = trailingslashit($upload_dir['basedir']) . 'wcmp_pdf_invoice/' . $order_id;
            $file_to_save = $base_path2 . '/packing_slip_' . $order_id . '.pdf';

            if (!file_exists($base_path2)) {
                mkdir($base_path2, 0777, true);
            }
            ob_start();
            $WCMp_PDF_Invoices->template->get_template('wcmp_packing_slip_first_template.php',array('order' => $order, 'order_id' => $order_id, 'vendor' => $vendor, 'user_type' => 'vendor', 'general_settings' => $general_settings));
            $ob_get_clean = ob_get_clean();
            if ($ob_get_clean) {
               $pdf_maker = wcpdf_get_pdf_maker($ob_get_clean, array());
                file_put_contents($file_to_save, $pdf_maker->output());
                $base_url = trailingslashit($upload_dir['baseurl']) . 'wcmp_pdf_invoice/' . $order_id;
                $file_url = $base_url . '/packing_slip_' . $order_id . '.pdf';
                update_post_meta($order_id, '_pdf_packing_slip_by_vendor'. sanitize_title($vendor->user_data->data->display_name), $file_url);

                if (file_exists($file_to_save)) {
                    wcpdf_pdf_headers( $order_id.'.pdf', 'download', $pdf_maker->output() );
                    @readfile($file_to_save);
                    exit;
                } else {
                    echo 'fail';
                }
            } else {
                echo 'fail';
            }

            die;
        }
    }



    public function create_per_order_pdf() {
        global $WCMp_PDF_Invoices;

        $order_id = $_POST['order_id'];
        $order = new WC_Order($order_id);

        $general_settings = get_option('wcmp_pdf_invoices_settings_name');
        $template = $general_settings['choose_invoice_template'];
        $upload_dir = wp_upload_dir();
        $base_path2 = trailingslashit($upload_dir['basedir']) . 'wcmp_pdf_invoice/' . $order_id;
        $file_to_save = $base_path2 . '/admin_' . $order_id . '.pdf';

        if (!file_exists($base_path2)) {
            mkdir($base_path2, 0777, true);
        }
        ob_start();
        $WCMp_PDF_Invoices->template->get_template($template . '.php', array('order' => $order, 'general_settings' => $general_settings, 'user_type' => 'admin'));
        $ob_get_clean = ob_get_clean();
        if ($ob_get_clean) {
            $pdf_maker = wcpdf_get_pdf_maker($ob_get_clean, array());
            file_put_contents($file_to_save, $pdf_maker->output());
            $base_url = trailingslashit($upload_dir['baseurl']) . 'wcmp_pdf_invoice/' . $order_id;
            $file_url = $base_url . '/admin_' . $order_id . '.pdf';
            if (file_exists($file_to_save)) {
                echo 'success';
            } else {
                echo 'fail';
            }
        } else {
            echo 'fail';
        }
        update_post_meta($order_id, '_pdf_invoice_per_order', $file_url);
        die;
    }

    

    public function create_per_vendor_pdf() {
        global $WCMp_PDF_Invoices;

        $order_id = $_POST['order_id'];
        $order = new WC_Order($order_id);
        $user_term_id = $_POST['user_id'];

        $vendor = get_wcmp_vendor_by_term($user_term_id);

        $general_settings = get_option('wcmp_pdf_invoices_settings_name');
        $template = $general_settings['choose_invoice_template'];
        $upload_dir = wp_upload_dir();
        $base_path2 = trailingslashit($upload_dir['basedir']) . 'wcmp_pdf_invoice/' . $order_id;
        $file_to_save = $base_path2 . '/' . sanitize_title($vendor->user_data->data->display_name) . '.pdf';

        if (!file_exists($base_path2)) {
            mkdir($base_path2, 0777, true);
        }
        ob_start();
        $WCMp_PDF_Invoices->template->get_template($template . '.php', array('order' => $order, 'general_settings' => $general_settings, 'vendor' => $vendor, 'user_type' => 'admin'));
        $ob_get_clean = ob_get_clean();
        if ($ob_get_clean) {
            $pdf_maker = wcpdf_get_pdf_maker($ob_get_clean, array());
                file_put_contents($file_to_save, $pdf_maker->output());
            $base_url = trailingslashit($upload_dir['baseurl']) . 'wcmp_pdf_invoice/' . $order_id;
            $file_url = $base_url . '/' . sanitize_title($vendor->user_data->data->display_name) . '.pdf';
            update_post_meta($order_id, '_pdf_invoice_' . sanitize_title($vendor->user_data->data->display_name), $file_url);

            if (file_exists($file_to_save)) {
                echo 'success';
            } else {
                echo 'fail';
            }
        } else {
            echo 'fail';
        }
        die;
    }

    public function cancel_per_order_pdf() {
        global $WCMp_PDF_Invoices;
        $order_id = $_POST['order_id'];
        delete_post_meta($order_id, '_pdf_invoice_per_order');
        die;
    }

    public function cancel_per_vendor_pdf() {
        global $WCMp_PDF_Invoices;
        $order_id = $_POST['order_id'];
        $vendor_name = $_POST['vendor_name'];
        delete_post_meta($order_id, '_pdf_invoice_' . $vendor_name);
        die;
    }

}
