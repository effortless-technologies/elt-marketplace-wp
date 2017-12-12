<?php

class WCMP_Sub_Vendor_Ajax {

    public function __construct() {
        add_action('wp', array(&$this, 'demo_ajax_method'));
        add_action('wp_ajax_add_sub_vendor_action', array(&$this, 'add_sub_vendor_action_calback'));
        add_action('wp_ajax_edit_sub_vendor_action', array(&$this, 'edit_sub_vendor_action_callback'));
        add_action('wp_ajax_delete_sub_vendor_action', array(&$this, 'delete_sub_vendor_action_callback'));
    }

    public function demo_ajax_method() {
        // Do your ajx job here
    }

    public function add_sub_vendor_action_calback() {
        $sub_vendor_username = $_POST['sub_vendor_username'];
        $sub_vendor_email = $_POST['sub_vendor_email'];
        $sub_vendor_fname = $_POST['sub_vendor_fname'];
        $sub_vendor_lname = $_POST['sub_vendor_lname'];
        $sub_vendor_password = $_POST['sub_vendor_password'];
        $sub_vendor_capabilities = $_POST['sub_vendor_capabilities'];

        $userdata = array(
            'user_login' => $sub_vendor_username,
            'first_name' => $sub_vendor_fname,
            'last_name' => $sub_vendor_lname,
            'user_email' => $sub_vendor_email,
            'user_pass' => $sub_vendor_password
        );

        if (!empty($sub_vendor_username) && !empty($sub_vendor_username)) {
            $user_id = username_exists($sub_vendor_username);
            if (!$user_id and email_exists($sub_vendor_email) == false) {

                $current_vendor = wp_get_current_user();
                $user_id = wp_insert_user($userdata);
                add_user_meta($user_id, '_report_vendor', $current_vendor->ID);
                $user_id_role = new WP_User($user_id);
                $user_id_role->set_role('dc_sub_vendor');
                foreach ($sub_vendor_capabilities as $key => $value) {
                    if ($value == 'manage_product') {

                        $user_id_role->add_cap('manage_product');
                        $user_id_role->add_cap('edit_product');
                        $user_id_role->add_cap('delete_product');
                        $user_id_role->add_cap('edit_products');
                        $user_id_role->add_cap('edit_others_products');
                        $user_id_role->add_cap('delete_published_products');
                        $user_id_role->add_cap('delete_products');
                        $user_id_role->add_cap('delete_others_products');
                        $user_id_role->add_cap('edit_published_products');
                        $user_id_role->add_cap('upload_files');
                        $user_id_role->add_cap('assign_product_terms');
                        add_user_meta($user_id, '_vendor_submit_product', 'Enable');
                    }
                    if ($value == 'manage_reports') {
                        $user_id_role->add_cap('view_woocommerce_reports');
                    }
                    if ($value == 'manage_order') {
                        $user_id_role->add_cap('manage_woocommerce_orders');
                    }
                    if ($value == 'manage_payment') {
                        $user_id_role->add_cap('manage_payment');
                    }
                }
                echo "success";
            } else {
                echo "error";
            }
        }

        die;
    }

    public function edit_sub_vendor_action_callback() {
        $sub_vendor_username = $_POST['edited_sub_vendor_username'];
        $sub_vendor_email = $_POST['edited_sub_vendor_email'];
        $sub_vendor_fname = $_POST['edited_sub_vendor_fname'];
        $sub_vendor_lname = $_POST['edited_sub_vendor_lname'];
        $sub_vendor_password = $_POST['edited_sub_vendor_password'];
        $sub_vendor_capabilities = $_POST['edited_sub_vendor_capabilities'];
        $sub_vendor = get_user_by('login', $sub_vendor_username);
        $sub_vendor_existing_email = $sub_vendor->user_email;

        if ($sub_vendor_existing_email == $sub_vendor_email) {
            $display_name = $sub_vendor_fname . ' ' . $sub_vendor_lname;
            $userdata = array(
                'ID' => $sub_vendor->ID,
                'first_name' => $sub_vendor_fname,
                'last_name' => $sub_vendor_lname,
                'user_email' => $sub_vendor_email,
                'user_pass' => $sub_vendor_password,
                'display_name' => $display_name
            );
            $user_id = wp_update_user($userdata);
            $user_id_cap = new WP_User($sub_vendor->ID);

            $user_id_cap->remove_cap('manage_product');
            delete_user_meta($user_id, '_vendor_submit_product', 'Enable');
            $user_id_cap->remove_cap('edit_product');
            $user_id_cap->remove_cap('delete_products');
            $user_id_cap->remove_cap('delete_product');
            $user_id_cap->remove_cap('edit_products');
            $user_id_cap->remove_cap('edit_others_products');
            $user_id_cap->remove_cap('delete_published_products');
            $user_id_cap->remove_cap('delete_others_products');
            $user_id_cap->remove_cap('edit_published_products');
            $user_id_cap->remove_cap('upload_files');
            $user_id_cap->remove_cap('assign_product_terms');

            $user_id_cap->remove_cap('view_woocommerce_reports');
            $user_id_cap->remove_cap('manage_payment');

            $user_id_cap->remove_cap('manage_woocommerce_orders');

            foreach ($sub_vendor_capabilities as $key => $value) {

                if ($value == 'manage_product') {
                    $user_id_cap->add_cap('manage_product');
                    $user_id_cap->add_cap('edit_product');
                    $user_id_cap->add_cap('delete_product');
                    $user_id_cap->add_cap('edit_products');
                    $user_id_cap->add_cap('edit_others_products');
                    $user_id_cap->add_cap('delete_published_products');
                    $user_id_cap->add_cap('delete_products');
                    $user_id_cap->add_cap('delete_others_products');
                    $user_id_cap->add_cap('edit_published_products');
                    $user_id_cap->add_cap('upload_files');
                    $user_id_cap->add_cap('assign_product_terms');
                    add_user_meta($user_id, '_vendor_submit_product', 'Enable');
                }
                if ($value == 'manage_reports') {
                    $user_id_cap->add_cap('view_woocommerce_reports');
                }
                if ($value == 'manage_order') {
                    $user_id_cap->add_cap('manage_woocommerce_orders');
                }
                if ($value == 'manage_payment') {
                    $user_id_cap->add_cap('manage_payment');
                }
            }

            echo "success";
        } else {
            if (email_exists($sub_vendor_email) == false) {
                $userdata = array(
                    'ID' => $sub_vendor->ID,
                    'first_name' => $sub_vendor_fname,
                    'last_name' => $sub_vendor_lname,
                    'user_email' => $sub_vendor_email,
                    'user_pass' => $sub_vendor_password
                );
                $user_id = wp_update_user($userdata);
                $user_id_cap = new WP_User($sub_vendor->ID);
                foreach ($sub_vendor_capabilities as $key => $value) {
                    $user_id_cap->add_cap($value);
                }
                echo "success";
            } else {
                echo "error";
            }
        }
        die;
    }

    public function delete_sub_vendor_action_callback() {
        $delete_sub_vendor_id = $_POST['delete_sub_vendor_id'];
        wp_delete_user($delete_sub_vendor_id);
        echo "deleted";
        die;
    }

}
