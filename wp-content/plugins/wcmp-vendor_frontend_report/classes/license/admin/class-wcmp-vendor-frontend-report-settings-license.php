<?php
class WCMP_Vendor_Frontend_Report_Settings_License {
  /**
   * Holds the values to be used in the fields callbacks
   */
  private $options;
  
  private $tab;
  
  private $api_manager_license_key;

  /**
   * Start up
   */
  public function __construct($tab) {
    global $WCMp_Vendor_Frontend_Report;
    
    $this->tab = $tab;
    $this->options = get_option( "wcmp_{$this->tab}_settings_name" );
    $this->settings_page_init();
    
    $this->api_manager_license_key = $WCMp_Vendor_Frontend_Report->license->api_manager_license_key;
  }
  
  /**
   * Register and add settings
   */
  public function settings_page_init() {
    global $WCMp, $WCMp_Vendor_Frontend_Report;
    
    $license_api_key = '';
    $license_activation_email = '';
    if(isset($this->options[$WCMp_Vendor_Frontend_Report->license->license_api_key])) $license_api_key = $this->options[$WCMp_Vendor_Frontend_Report->license->license_api_key];
    if(isset($this->options[$WCMp_Vendor_Frontend_Report->license->license_activation_email])) $license_activation_email = $this->options[$WCMp_Vendor_Frontend_Report->license->license_activation_email];
    
    if ( $this->options && is_array($this->options) && $this->options[$WCMp_Vendor_Frontend_Report->license->license_api_key] ) {
			$api_key_ico = "<span class='icon-pos'><img src='" . $WCMp_Vendor_Frontend_Report->plugin_url . "classes/license/assets/images/complete.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
		} else {
			$api_key_ico = "<span class='icon-pos'><img src='" . $WCMp_Vendor_Frontend_Report->plugin_url . "classes/license/assets/images/warn.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
		}
		
		if ( $this->options && is_array($this->options) && $this->options[$WCMp_Vendor_Frontend_Report->license->license_activation_email] ) {
			$api_email_ico = "<span class='icon-pos'><img src='" . $WCMp_Vendor_Frontend_Report->plugin_url . "classes/license/assets/images/complete.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
		} else {
			$api_email_ico = "<span class='icon-pos'><img src='" . $WCMp_Vendor_Frontend_Report->plugin_url . "classes/license/assets/images/warn.png' title='' style='padding-bottom: 4px; vertical-align: middle; margin-right:3px;' /></span>";
		}
    
    $settings_tab_options = array("tab" => "{$this->tab}",
                                  "ref" => &$this,
                                  "sections" => array(
                                                      "activation_settings_section" => array("title" =>  __('License Activation', $WCMp_Vendor_Frontend_Report->text_domain),
                                                                                         "fields" => array($WCMp_Vendor_Frontend_Report->license->license_api_key => array('title' => __('API License Key', $WCMp_Vendor_Frontend_Report->text_domain), 'type' => 'text', 'value' => $license_api_key, 'desc' => $api_key_ico),
                                                                                                           $WCMp_Vendor_Frontend_Report->license->license_activation_email => array('title' => __('API License email', $WCMp_Vendor_Frontend_Report->text_domain), 'type' => 'text', 'value' => $license_activation_email, 'desc' => $api_email_ico),
                                                                                                           )
                                                                                         ),
                                                      "deactivation_settings_section" => array("title" =>  __('License Deactivation', $WCMp_Vendor_Frontend_Report->text_domain),
                                                                                         "fields" => array($WCMp_Vendor_Frontend_Report->license->license_deactivate_checkbox => array('title' => __('Deactivate API License Key', $WCMp_Vendor_Frontend_Report->text_domain), 'type' => 'checkbox', 'id' => $WCMp_Vendor_Frontend_Report->license->license_deactivate_checkbox, 'name' => $WCMp_Vendor_Frontend_Report->license->license_deactivate_checkbox, 'value' => 'on', 'desc' => __( 'Deactivates an API License Key so it can be used on another blog.', $WCMp_Vendor_Frontend_Report->text_domain ))
                                                                                                           )
                                                                                         )
                                                      )
                                  );
    
    $WCMp->admin->settings->settings_field_init(apply_filters("settings_{$this->tab}_tab_options", $settings_tab_options));
  }

  /**
   * Sanitize each setting field as needed
   *
   * @param array $input Contains all settings fields as array keys
   */
  public function wcmp_wcmp_vendor_frontend_report_license_settings_sanitize( $input ) {
    global $WCMp_Vendor_Frontend_Report;
	  
		// Load existing options, validate, and update with changes from input before returning
		$new_input = array();
		
		$hasError = false;
		
		if(!isset($input[$WCMp_Vendor_Frontend_Report->license->license_deactivate_checkbox])) $input[$WCMp_Vendor_Frontend_Report->license->license_deactivate_checkbox] = 'off';
		$new_input[$WCMp_Vendor_Frontend_Report->license->license_api_key] = trim( $input[$WCMp_Vendor_Frontend_Report->license->license_api_key] );
		$new_input[$WCMp_Vendor_Frontend_Report->license->license_activation_email] = trim( $input[$WCMp_Vendor_Frontend_Report->license->license_activation_email] );
		$new_input[$WCMp_Vendor_Frontend_Report->license->license_deactivate_checkbox] = ( $input[$WCMp_Vendor_Frontend_Report->license->license_deactivate_checkbox] == 'on' ? 'on' : 'off' );
		
		$api_email = trim( $input[$WCMp_Vendor_Frontend_Report->license->license_activation_email] );
		$api_key = trim( $input[$WCMp_Vendor_Frontend_Report->license->license_api_key] );
		
		if($api_key == '') {
		  add_settings_error(
        "wcmp_{$this->tab}_settings_name",
        esc_attr( "wcmp_{$this->tab}_settings_admin_error" ),
        __('Please insert your license key.', $WCMp_Vendor_Frontend_Report->text_domain),
        'error'
      );
      $hasError = true;
		}
		
		if($api_email == '') {
		  add_settings_error(
        "wcmp_{$this->tab}_settings_name",
        esc_attr( "wcmp_{$this->tab}_settings_admin_error" ),
        __('Please insert your license email.', $WCMp_Vendor_Frontend_Report->text_domain),
        'error'
      );
      $hasError = true;
		}

		if(!$hasError) {

      $activation_status = get_option( $WCMp_Vendor_Frontend_Report->license->license_activated_key );
      $checkbox_status = get_option( $WCMp_Vendor_Frontend_Report->license->license_deactivate_checkbox );
      $current_api_key = $this->options[$WCMp_Vendor_Frontend_Report->license->license_api_key];
		
		  $args = array(
        'email' => $api_email,
        'licence_key' => $api_key,
      );
      
      if ( 'off' == $new_input[$WCMp_Vendor_Frontend_Report->license->license_deactivate_checkbox] ) {
  
        // Plugin Activation
        if ( $activation_status == 'Deactivated' || $activation_status == '' || $checkbox_status == 'on' || $current_api_key != $api_key  ) {

          if ( $current_api_key != $api_key )
            $this->replace_license_key( $current_api_key );
  
          $activate_results = json_decode( $this->api_manager_license_key->activate( $args ), true );
          
          if ( $activate_results['activated'] == true ) {
          	if(!isset($activate_results['message'])) $activate_results['message'] = '';
            add_settings_error( "wcmp_{$this->tab}_settings_name", esc_attr( "wcmp_{$this->tab}_settings_admin_error" ), __( 'Plugin activated. ', $WCMp_Vendor_Frontend_Report->text_domain ) . "{$activate_results['message']}.", 'updated' );
            update_option( $WCMp_Vendor_Frontend_Report->license->license_activated_key, 'Activated' );
            update_option( $WCMp_Vendor_Frontend_Report->license->license_deactivate_checkbox, 'off' );
            
            //$WCMp_Vendor_Frontend_Report->license->wcmp_plugin_tracker('license_activate', $api_key, $api_email);
          }
  
          if ( $activate_results == false ) {
            add_settings_error( "wcmp_{$this->tab}_settings_name", esc_attr( "wcmp_{$this->tab}_settings_admin_error" ), __( 'Connection failed to the License Key API server. Try again later.', $WCMp_Vendor_Frontend_Report->text_domain ), 'error' );
            $new_input[$WCMp_Vendor_Frontend_Report->license->license_api_key] = '';
            $new_input[$WCMp_Vendor_Frontend_Report->license->license_activation_email] = '';
            update_option( $WCMp_Vendor_Frontend_Report->license->license_activated_key, 'Deactivated' );
          }
  
          if ( isset( $activate_results['code'] ) ) {
          	if(!isset($activate_results['additional info'])) $activate_results['additional info'] = '';
          	
            switch ( $activate_results['code'] ) {
              case '100':
                add_settings_error( "wcmp_{$this->tab}_settings_name", esc_attr( "wcmp_{$this->tab}_settings_admin_error" ), "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
                $new_input[$WCMp_Vendor_Frontend_Report->license->license_activation_email] = '';
                $new_input[$WCMp_Vendor_Frontend_Report->license->license_api_key] = '';
                update_option( $WCMp_Vendor_Frontend_Report->license->license_activated_key, 'Deactivated' );
              break;
              case '101':
                add_settings_error( "wcmp_{$this->tab}_settings_name", esc_attr( "wcmp_{$this->tab}_settings_admin_error" ), "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
                $new_input[$WCMp_Vendor_Frontend_Report->license->license_api_key] = '';
                $new_input[$WCMp_Vendor_Frontend_Report->license->license_activation_email] = '';
                update_option( $WCMp_Vendor_Frontend_Report->license->license_activated_key, 'Deactivated' );
              break;
              case '102':
                add_settings_error( "wcmp_{$this->tab}_settings_name", esc_attr( "wcmp_{$this->tab}_settings_admin_error" ), "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
                $new_input[$WCMp_Vendor_Frontend_Report->license->license_api_key] = '';
                $new_input[$WCMp_Vendor_Frontend_Report->license->license_activation_email] = '';
                update_option( $WCMp_Vendor_Frontend_Report->license->license_activated_key, 'Deactivated' );
              break;
              case '103':
                  add_settings_error( "wcmp_{$this->tab}_settings_name", esc_attr( "wcmp_{$this->tab}_settings_admin_error" ), "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
                  $new_input[$WCMp_Vendor_Frontend_Report->license->license_api_key] = '';
                  $new_input[$WCMp_Vendor_Frontend_Report->license->license_activation_email] = '';
                  update_option( $WCMp_Vendor_Frontend_Report->license->license_activated_key, 'Deactivated' );
              break;
              case '104':
                  add_settings_error( "wcmp_{$this->tab}_settings_name", esc_attr( "wcmp_{$this->tab}_settings_admin_error" ), "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
                  $new_input[$WCMp_Vendor_Frontend_Report->license->license_api_key] = '';
                  $new_input[$WCMp_Vendor_Frontend_Report->license->license_activation_email] = '';
                  update_option( $WCMp_Vendor_Frontend_Report->license->license_activated_key, 'Deactivated' );
              break;
              case '105':
                  add_settings_error( "wcmp_{$this->tab}_settings_name", esc_attr( "wcmp_{$this->tab}_settings_admin_error" ), "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
                  $new_input[$WCMp_Vendor_Frontend_Report->license->license_api_key] = '';
                  $new_input[$WCMp_Vendor_Frontend_Report->license->license_activation_email] = '';
                  update_option( $WCMp_Vendor_Frontend_Report->license->license_activated_key, 'Deactivated' );
              break;
              case '106':
                  add_settings_error( "wcmp_{$this->tab}_settings_name", esc_attr( "wcmp_{$this->tab}_settings_admin_error" ), "{$activate_results['error']}. {$activate_results['additional info']}", 'error' );
                  $new_input[$WCMp_Vendor_Frontend_Report->license->license_api_key] = '';
                  $new_input[$WCMp_Vendor_Frontend_Report->license->license_activation_email] = '';
                  update_option( $WCMp_Vendor_Frontend_Report->license->license_activated_key, 'Deactivated' );
              break;
            }
          }
        } // End Plugin Activation
      } else {
        if ( $activation_status == 'Activated' ) {
          $reset = $this->api_manager_license_key->deactivate( $args ); // reset license key activation
   
          if ( $reset == true ) {
            $new_input[$WCMp_Vendor_Frontend_Report->license->license_api_key] = '';
            $new_input[$WCMp_Vendor_Frontend_Report->license->license_activation_email] = '';
            update_option( $WCMp_Vendor_Frontend_Report->license->license_activated_key, 'Deactivated' );
            //$WCMp_Vendor_Frontend_Report->license->wcmp_plugin_tracker('license_deactivate', $api_key, $api_email);
    
            add_settings_error( "wcmp_{$this->tab}_settings_name", esc_attr( "wcmp_{$this->tab}_settings_admin_error" ), __( 'Plugin license deactivated.', $WCMp_Vendor_Frontend_Report->text_domain ), 'updated' );
          }
        }
      }
    }

    unset($new_input[$WCMp_Vendor_Frontend_Report->license->license_deactivate_checkbox]);
    return $new_input;
  }
  
  // Deactivate the current license key before activating the new license key
	public function replace_license_key( $current_api_key ) {
	  global $WCMp_Vendor_Frontend_Report;
	  
		$args = array(
			'email' => $this->options[$WCMp_Vendor_Frontend_Report->license->license_activation_email],
			'licence_key' => $current_api_key,
			);

		$reset = $this->api_manager_license_key->deactivate( $args ); // reset license key activation

		if ( $reset == true )
			return true;

		return add_settings_error( "wcmp_{$this->tab}_settings_name", esc_attr( "wcmp_{$this->tab}_settings_admin_error" ), __( 'The license could not be deactivated. Use the License Deactivation tab to manually deactivate the license before activating a new license.', $WCMp_Vendor_Frontend_Report->text_domain ), 'updated' );
	}
  
  /** 
   * Print the Section text
   */
  public function activation_settings_section_info() {
    global $WCMp_Vendor_Frontend_Report;
    //_e('Enter your default settings below', $WCMp_Vendor_Frontend_Report->text_domain);
  }
  
  /** 
   * Print the Section text
   */
  public function deactivation_settings_section_info() {
    global $WCMp_Vendor_Frontend_Report;
    //_e('Enter your custom settings below', $WCMp_Vendor_Frontend_Report->text_domain);
  }
}