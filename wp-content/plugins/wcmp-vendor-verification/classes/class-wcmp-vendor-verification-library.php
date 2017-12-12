<?php
class WCMP_Vendor_Verification_Library {
  
  	public $lib_path;
  	public $lib_url;
  	public $php_lib_path;
  	public $php_lib_url;
  	public $jquery_lib_path;
  	public $jquery_lib_url;
  	public $hybridauth_lib_path;
  	public $hybridauth_lib_url;

	public function __construct() {
	  	global $WCMP_Vendor_Verification;
	  
	  	$this->lib_path = $WCMP_Vendor_Verification->plugin_path . 'lib/';
    	$this->lib_url = $WCMP_Vendor_Verification->plugin_url . 'lib/';
    	$this->php_lib_path = $this->lib_path . 'php/';
    	$this->php_lib_url = $this->lib_url . 'php/';
    	$this->hybridauth_lib_path = $this->lib_path . 'Hybrid/';
    	$this->hybridauth_lib_url = $this->lib_url . 'Hybrid/';
    	$this->jquery_lib_path = $this->lib_path . 'jquery/';
    	$this->jquery_lib_url = $this->lib_url . 'jquery/';
	}
	
	/**
	 * PHP WP fields Library
	 */
	public function load_wp_fields() {
	  global $WCMP_Vendor_Verification;
	  if ( ! class_exists( 'DC_WP_Fields' ) )
	    require_once ($this->php_lib_path . 'class-dc-wp-fields.php');
	  $DC_WP_Fields = new DC_WP_Fields(); 
	  return $DC_WP_Fields;
	}
	
	/**
	 * Jquery qTip library
	 */
	public function load_qtip_lib() {
	  global $WCMP_Vendor_Verification;
	  wp_enqueue_script('qtip_js', $this->jquery_lib_url . 'qtip/qtip.js', array('jquery'), $WCMP_Vendor_Verification->version, true);
		wp_enqueue_style('qtip_css',  $this->jquery_lib_url . 'qtip/qtip.css', array(), $WCMP_Vendor_Verification->version);
	}
	
	/**
	 * WP Media library
	 */
	public function load_upload_lib() {
	  global $WCMP_Vendor_Verification;
	  wp_enqueue_media();
	  wp_enqueue_script('upload_js', $this->jquery_lib_url . 'upload/media-upload.js', array('jquery'), $WCMP_Vendor_Verification->version, true);
	  wp_enqueue_style('upload_css',  $this->jquery_lib_url . 'upload/media-upload.css', array(), $WCMP_Vendor_Verification->version);
	}
	
	/**
	 * WP ColorPicker library
	 */
	public function load_colorpicker_lib() {
	  global $WCMP_Vendor_Verification;
	  wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'colorpicker_init', $this->jquery_lib_url . 'colorpicker/colorpicker.js', array( 'jquery', 'wp-color-picker' ), $WCMP_Vendor_Verification->version, true );
    wp_enqueue_style( 'wp-color-picker' );
	}
	
	/**
	 * WP DatePicker library
	 */
	public function load_datepicker_lib() {
	  global $WCMP_Vendor_Verification,$wp_scripts;
	  wp_enqueue_script('jquery-ui-datepicker');
	  if(wp_style_is( 'jquery-ui-style', 'registered' )){
	  	wp_enqueue_style( 'jquery-ui-style' );
	  }else{
	  	$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.11.4';
		wp_register_style( 'jquery-ui-style', '//code.jquery.com/ui/' . $jquery_version . '/themes/smoothness/jquery-ui.min.css', array(), $jquery_version );
		wp_enqueue_style( 'jquery-ui-style' );
	  }
	}
}
