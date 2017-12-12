<?php
class WCMp_Product_Import_Export_Bundle_Admin {
  
  public $settings;

	public function __construct() {
		//add_action('admin_enqueue_scripts', array(&$this, 'enqueue_admin_script'));
		
		$this->load_class('settings');
		$this->settings = new WCMp_Product_Import_Export_Bundle_Settings();
	}

	function load_class($class_name = '') {
	  global $WCMp_Product_Import_Export_Bundle;
		if ('' != $class_name) {
			require_once ($WCMp_Product_Import_Export_Bundle->plugin_path . '/admin/class-' . esc_attr($WCMp_Product_Import_Export_Bundle->token) . '-' . esc_attr($class_name) . '.php');
		} // End If Statement
	}// End load_class() 
	
	/**
	 * Admin Scripts
	 */

	public function enqueue_admin_script() {
		global $WCMp, $WCMp_Product_Import_Export_Bundle;
		$screen = get_current_screen();
		
		// Enqueue admin script and stylesheet from here
		if (in_array( $screen->id, array( 'toplevel_page_wcmp-product-import-export-bundle-setting-admin' ))) :   
		  $WCMp->library->load_qtip_lib();
		  $WCMp->library->load_upload_lib();
		  $WCMp->library->load_colorpicker_lib();
		  $WCMp->library->load_datepicker_lib();
		  wp_enqueue_script('admin_js', $WCMp->plugin_url.'assets/admin/js/admin.js', array('jquery'), $WCMp_Product_Import_Export_Bundle->version, true);
		  wp_enqueue_style('admin_css',  $WCMp->plugin_url.'assets/admin/css/admin.css', array(), $WCMp_Product_Import_Export_Bundle->version);
	  endif;
	}
}