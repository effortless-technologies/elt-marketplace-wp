<?php
class WCMP_Vendor_Verification_Ajax {

	public function __construct() {
		add_action( 'wp_ajax_wcmp_vendor_verification_approval', array(&$this, 'wcmp_vendor_verification_approval') );
		add_action( 'wp_ajax_wcmp_vendor_is_verified_approval', array(&$this, 'wcmp_vendor_is_verified_approval') );
	}

	public function wcmp_vendor_verification_approval() {
		global $WCMP_Vendor_Verification;
		$status = 0;
		if(!empty($_POST['user_id'])){
			$user_id = (int)$_POST['user_id'];
			$verification_settings = get_user_meta($user_id, 'wcmp_vendor_verification_settings', true);
			if(!empty($_POST['verification_type']) && !empty($_POST['data_action'])){
				$v_type = $_POST['verification_type'];
				$action = $_POST['data_action'];
				if($action == 'rejected'){
					$verification_settings[$v_type]['is_verified'] = $action;
					$verification_settings[$v_type]['data'] = '';
					delete_user_meta($user_id, 'wcmp_vendor_is_verified');
				}else{
					$verification_settings[$v_type]['is_verified'] = $action;
				}
			}
			if(update_user_meta($user_id, 'wcmp_vendor_verification_settings', $verification_settings)){
				
				$status = 1;
			}
		}
		echo $status;
		die();
	}

	public function wcmp_vendor_is_verified_approval() {
		global $WCMP_Vendor_Verification;
		$status = 0;
		if(!empty($_POST['user_id'])){
			$user_id = (int)$_POST['user_id'];
			$verification_settings = get_user_meta($user_id,'wcmp_vendor_verification_settings',true);
			if($verification_settings['address_verification']['is_verified'] == 'verified' && $verification_settings['id_verification']['is_verified'] == 'verified'){
				update_user_meta($user_id, 'wcmp_vendor_is_verified', 'verified');
				$status = 1;
			}else{
				delete_user_meta($user_id, 'wcmp_vendor_is_verified');
			}
		}
		echo $status;
		die();
	}
}
