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
    	global $WCMP_Sub_Vendor, $WCMp;
    	
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

        if (!empty($sub_vendor_username) && !empty($sub_vendor_email)) {
            $user_id = username_exists($sub_vendor_username);
            if (!$user_id && email_exists($sub_vendor_email) == false) {
                $current_vendor = wp_get_current_user();
                $capability_list = $WCMp->vendor_caps->get_vendor_caps();
				if(isset($capability_list) && is_array($capability_list)) {
					$sub_vendor = new WCMP_Sub_Vendor_Menu();
					$field_list = $sub_vendor->get_capability_mapping($capability_list);
				}
                $user_id = wp_insert_user($userdata);
                add_user_meta($user_id, '_report_vendor', $current_vendor->ID);
                $current_vendor_term_id = get_user_meta($current_vendor->ID, '_vendor_term_id', true);
                add_user_meta($user_id, '_vendor_term_id', $current_vendor_term_id);
                
                $user_id_role = new WP_User($user_id);
                $user_id_role->set_role('dc_sub_vendor');
                foreach ($sub_vendor_capabilities as $key => $value) {
                	foreach($field_list[$value]['caps'] as $caps) {
                		$user_id_role->add_cap($caps);
                	} 
                }
                echo "success::" . $user_id;
            } else {
                echo "error";
            }
        } else {
        	echo "required_missing";
        }

        die;
    }

    public function edit_sub_vendor_action_callback() {
    	global $WCMP_Sub_Vendor, $WCMp;
    	
        $sub_vendor_username = $_POST['edited_sub_vendor_username'];
        $sub_vendor_email = $_POST['edited_sub_vendor_email'];
        $sub_vendor_fname = $_POST['edited_sub_vendor_fname'];
        $sub_vendor_lname = $_POST['edited_sub_vendor_lname'];
        $sub_vendor_password = $_POST['edited_sub_vendor_password'];
        $sub_vendor_capabilities = $_POST['edited_sub_vendor_capabilities'];
        $sub_vendor = get_user_by('login', $sub_vendor_username);
        $sub_vendor_existing_email = $sub_vendor->user_email;
        $current_vendor = wp_get_current_user();
        if(!in_array('dc_sub_vendor', $sub_vendor->roles)) {
        	echo "permission_error";
        	die();
        }
        
        if(get_user_meta($sub_vendor->ID, '_report_vendor', true) != $current_vendor->ID) {
        	echo "permission_error";
        	die();
        }

        if ($sub_vendor_existing_email == $sub_vendor_email) {                                          
            $display_name = $sub_vendor_fname . ' ' . $sub_vendor_lname;
            $userdata = array(
                'ID' => $sub_vendor->ID,
                'first_name' => $sub_vendor_fname,
                'last_name' => $sub_vendor_lname,
                'user_email' => $sub_vendor_email,
                'display_name' => $display_name
            );
            if(isset($sub_vendor_password) && $sub_vendor_password != "") $userdata['user_pass'] = $sub_vendor_password;
            
            wp_update_user($userdata);
            $user_id_cap = new WP_User($sub_vendor->ID);

            $capability_list = $WCMp->vendor_caps->get_vendor_caps();
			if(isset($capability_list) && is_array($capability_list)) {
				$sub_vendor = new WCMP_Sub_Vendor_Menu();
				$field_list = $sub_vendor->get_capability_mapping($capability_list);
			}
			foreach($field_list as $key => $value) {
				foreach($value['caps'] as $caps) {
					$user_id_cap->remove_cap($caps);
				} 
			}
			
			foreach($sub_vendor_capabilities as $key => $value) {
				foreach($field_list[$value]['caps'] as $caps) {
					$user_id_cap->add_cap($caps);
				} 
			}
            echo "success";
        } else {
            if (email_exists($sub_vendor_email) == false) {
            	$display_name = $sub_vendor_fname . ' ' . $sub_vendor_lname;
                $userdata = array(
                    'ID' => $sub_vendor->ID,
                    'first_name' => $sub_vendor_fname,
                    'last_name' => $sub_vendor_lname,
                    'user_email' => $sub_vendor_email,
                    'display_name' => $display_name
                );
                if(isset($sub_vendor_password) && $sub_vendor_password != "") $userdata['user_pass'] = $sub_vendor_password;
            
                $user_id = wp_update_user($userdata);
                $user_id_cap = new WP_User($sub_vendor->ID);
                
                $capability_list = $WCMp->vendor_caps->get_vendor_caps();
				if(isset($capability_list) && is_array($capability_list)) {
					$sub_vendor = new WCMP_Sub_Vendor_Menu();
					$field_list = $sub_vendor->get_capability_mapping($capability_list);
				}
				foreach($field_list as $key => $value) {
					foreach($value['caps'] as $caps) {
						$user_id_cap->remove_cap($caps);
					} 
				}
				
				foreach($sub_vendor_capabilities as $key => $value) {
					foreach($field_list[$value]['caps'] as $caps) {
						$user_id_cap->add_cap($caps);
					} 
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
