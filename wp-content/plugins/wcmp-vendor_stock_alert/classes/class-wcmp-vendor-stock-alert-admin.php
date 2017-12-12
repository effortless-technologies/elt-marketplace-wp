<?php
class WCMp_Vendor_Stock_Alert_Admin {
  
  public $settings;

	public function __construct() {

		add_action('admin_enqueue_scripts', array(&$this, 'enqueue_admin_script'), 30);
	
		add_filter("settings_capabilities_product_tab_options", array($this, "inventory_settings_option")); 
		
		add_filter("settings_capabilities_product_tab_new_input", array($this, "inventory_settings_sanitize"), 10, 2);
	}
	
	/**
	 * Settings panel of WCMp Vendor Stock Alert Product Settings
	 *
	 * @param settings_tab_options     WCMp Settings fields product tab fields
	 * @return WCMp Vendor Stock Alert settings fields are merged
	 */
	function inventory_settings_option($settings_tab_options) {
		global $WCMp, $WCMp_Vendor_Stock_Alert;
		
		$settings_tab_options["sections"]["inventory_settings_section"] = array( "title" =>  __('Inventory', 'wcmp-vendor_stock_alert'),
																				 "ref" => &$this,
																				 "fields" => array( "low_stock_enabled" => array('title' => __('Enable low stock alert for Vendors', 'wcmp-vendor_stock_alert'), 'type' => 'checkbox', 'name' => 'low_stock_enabled', 'value' => 'Enable', 'desc' => __('It will enable low stock limit', 'wcmp-vendor_stock_alert') ),
																														"out_of_stock_enabled" => array('title' => __('Enable out of stock alert for Vendors', 'wcmp-vendor_stock_alert'), 'type' => 'checkbox', 'name' => 'out_of_stock_enabled', 'value' => 'Enable', 'desc' => __('It will enable out of stock limit', 'wcmp-vendor_stock_alert') ),
																														"low_stock_limit" => array('title' => __('Low stock alert limit for Vendors', 'wcmp-vendor_stock_alert'), 'type' => 'text', 'desc' => __('It will represent low stock limit', 'wcmp-vendor_stock_alert') ),
																														"out_of_stock_limit" => array('title' => __('Out of stock alert limit for Vendors', 'wcmp-vendor_stock_alert'), 'type' => 'text', 'desc' => __('It will represent out of stock limit', 'wcmp-vendor_stock_alert') )
																														)
																				 );
		return $settings_tab_options;
	}
	
	/**
	 * Save settings of WCMp Vendor Stock Alert Product Settings
	 *
	 * @param new_input	input     WCMp Settings inputs
	 * @return WCMp Vendor Stock Alert settings inputs are merged
	 */
	function inventory_settings_sanitize($new_input, $input) {
		global $WCMp, $WCMp_Vendor_Stock_Alert;
		
		if( isset( $input['low_stock_enabled'] ) )
      $new_input['low_stock_enabled'] = sanitize_text_field( $input['low_stock_enabled'] );
    
    if( isset( $input['out_of_stock_enabled'] ) )
      $new_input['out_of_stock_enabled'] = sanitize_text_field( $input['out_of_stock_enabled'] );
    
    if( isset( $input['low_stock_limit'] ) )
      $new_input['low_stock_limit'] = absint( $input['low_stock_limit'] );
    
    if( isset( $input['out_of_stock_limit'] ) )
      $new_input['out_of_stock_limit'] = absint( $input['out_of_stock_limit'] );
    
		return $new_input;
	}


	/**
     * Admin Scripts
     */
    public function enqueue_admin_script() {
        global $WCMp, $WCMp_Vendor_Stock_Alert;
        $screen = get_current_screen();
        
        if (in_array($screen->id, array('wcmp_page_wcmp-setting-admin','profile','user-edit'))) {
        	wp_enqueue_script('wcmp_vsa_admin_js', $WCMp_Vendor_Stock_Alert->plugin_url . 'assets/admin/js/admin.js', array('jquery'), $WCMp_Vendor_Stock_Alert->version, true);
        }
    }
	
	/** 
   * Print the Section text
   */
  public function inventory_settings_section_info() {
    global $WCMp, $WCMp_Vendor_Stock_Alert;
  }
	
}