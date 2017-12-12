<?php
class WCMP_Vendor_Verification_Auth {
	public $vendor_social_config;
	public function __construct() {
		$this->vendor_social_config();
		$this->vendor_auth_requests();
		$this->vendor_auth_params();
	}

	public function wcmp_vendor_verification_default_providers(){
		$default_providers = array ( 
		       "Google"   => array(
                    "enabled" => true,
                    "keys"    => array( "id" => "", "secret" => "" ),
                    "scope"   => "https://www.googleapis.com/auth/userinfo.profile "
                ),
		       "Facebook" => array(
                    "enabled" => true,
                    "keys"    => array( "id" => "", "secret" => "" ),
                    "scope"   => "email, public_profile, user_friends",
                    "trustForwarded" => true
                ),
		       "Twitter"  => array(
                    "enabled" => true,
                    "keys"    => array( "key" => "", "secret" => "" ),
                ),
		       "LinkedIn" => array(
                    "enabled" => true,
                    "keys"    => array( "key" => "", "secret" => "" ),
                )
		    );
		return apply_filters( 'wcmp_vendor_verification_default_providers', $default_providers );
	}

	public function vendor_auth_params(){
		global $WCMp;
		$params = apply_filters('wcmp_vendor_verification_auth_params', array(
  			'hauth_return_to' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_verification_endpoint', 'vendor', 'general', 'vendor-verification'))
  			));
		return $params;
	}


	public function vendor_id_verification_list(){
		$default_ids = array(
			"national_id" => __('National ID Card', 'wcmp-vendor-verification'),
			"driving_license" => __('Driving License', 'wcmp-vendor-verification'),
			"passport" => __('Passport', 'wcmp-vendor-verification'),
			);
		return apply_filters( 'wcmp_vendor_id_verification_list', $default_ids );
	}

	public function vendor_social_config(){
		global $WCMP_Vendor_Verification,$WCMp;
		$base_url = wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_verification_endpoint', 'vendor', 'general', 'vendor-verification'));

		$wcmp_vendor_verification_providers = $this->wcmp_vendor_verification_default_providers();
		if($wcmp_vendor_verification_providers){
			foreach ($wcmp_vendor_verification_providers as $provider => $settings) {
				$key = strtolower(sanitize_text_field($provider));
				$settings['enabled'] = (get_vendor_verification_settings($key.'_enable') == 'Enable') ? true : false;
				$settings['keys'] = array( 
					"id" => get_vendor_verification_settings($key.'_client_id'), 
					"secret" => get_vendor_verification_settings($key.'_client_secret'),
					"key" => get_vendor_verification_settings($key.'_client_id') 
					);
				$wcmp_vendor_verification_providers[$provider] = $settings;
			}
		}
		$auth_config = array( 
		   "base_url" 	=> $base_url,
		   "debug_mode" => false ,
		   "debug_file" => $WCMP_Vendor_Verification->library->hybridauth_lib_path."hybridauth.log",
		   "providers"  => $wcmp_vendor_verification_providers
		);

		$this->vendor_social_config = apply_filters( 'wcmp_vendor_verification_auth_config', $auth_config, get_vendor_verification_settings());

		return $this->vendor_social_config;
	}

	public function vendor_auth_requests() {
		global $WCMP_Vendor_Verification,$WCMp,$current_user;
	
  		$hybridauth = new Hybrid_Auth( $this->vendor_social_config );
  		$params = $this->vendor_auth_params();

		if(isset($_REQUEST['hauth_start'])){
			Hybrid_Endpoint::process();
		}

		if(isset($_REQUEST['hauth_done'])){
			if(isset($_REQUEST['code']) || isset($_REQUEST['oauth_token'])){
				Hybrid_Endpoint::process();
			}elseif(isset($_REQUEST['oauth_problem']) || isset($_REQUEST['denied']) || isset($_REQUEST['error']) || isset($_REQUEST['error_code'])){
				if(isset($params['hauth_return_to']))
    				$hybridauth->redirect($params['hauth_return_to']);
			}
		}

		if(isset($_REQUEST['auth_out'])){
    		$provider_logout = sanitize_text_field( $_GET['auth_out'] );
    		try{
				$adapter = $hybridauth->authenticate( $provider_logout, $params );
				$vendor = get_wcmp_vendor( $current_user->ID );
      			$vendor_verification = get_user_meta( $vendor->id, 'wcmp_vendor_verification_settings', true );
      			unset($vendor_verification['social_verification'][$provider_logout]);
    			update_user_meta( $vendor->id, 'wcmp_vendor_verification_settings', $vendor_verification );
    			$adapter->logout();
    			
			}catch( Exception $e ){
				wc_add_notice($e->getMessage(), 'error');
			}
    	}

		if(isset($_REQUEST['hybridauth'])){

	    }

		if(isset($_REQUEST['auth_in'])){
      		$provider = sanitize_text_field( $_GET['auth_in'] );
      		
			if(!empty($provider)){
				try{
    				$adapter = $hybridauth->authenticate( $provider );
	                $user_profile = $adapter->getUserProfile();
	                $vendor = get_wcmp_vendor( $current_user->ID );
	                $vendor_verification = get_user_meta( $vendor->id, 'wcmp_vendor_verification_settings', true );
	                $vendor_verification['social_verification'][$provider] = (array) $user_profile;
	                update_user_meta( $vendor->id, 'wcmp_vendor_verification_settings', $vendor_verification );
	                if(isset($params['hauth_return_to']))
    					$hybridauth->redirect($params['hauth_return_to']);
	            }catch( Exception $e ){
					wc_add_notice($e->getMessage(), 'error');
				}
			}
		}
		
	}
}
