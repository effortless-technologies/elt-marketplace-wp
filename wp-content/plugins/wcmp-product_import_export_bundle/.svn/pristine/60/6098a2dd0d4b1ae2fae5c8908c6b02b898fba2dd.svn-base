<?php
class WCMp_Product_Import_Export_Bundle_Library {
  
  public $lib_path;
  
  public $lib_url;
  
  public $php_lib_path;
  
  public $php_lib_url;
  
  public $jquery_lib_path; 
  
  public $jquery_lib_url;

	public function __construct() {
	  global $WCMp_Product_Import_Export_Bundle;
	  
	  $this->lib_path = $WCMp_Product_Import_Export_Bundle->plugin_path . 'lib/';

    $this->lib_url = $WCMp_Product_Import_Export_Bundle->plugin_url . 'lib/';
    
    $this->php_lib_path = $this->lib_path . 'php/';
    
    $this->php_lib_url = $this->lib_url . 'php/';
    
    $this->jquery_lib_path = $this->lib_path . 'jquery/';
    
    $this->jquery_lib_url = $this->lib_url . 'jquery/';
	}
	
	/**
	 * PHP WP fields Library
	 */
	public function load_wp_fields() {
	  global $WCMp_Product_Import_Export_Bundle;
	  if ( ! class_exists( 'WCMp_WP_Fields' ) )
	    require_once ($this->php_lib_path . 'class-wcmp-wp-fields.php');
	  $WCMp_WP_Fields = new WCMp_WP_Fields(); 
	  return $WCMp_WP_Fields;
	}

    public function load_export_meta_fields_arr() {
	  global $WCMp_Product_Import_Export_Bundle;
	  if ( ! class_exists( 'WCMp_Product_Export_Fields' ) )
	    require_once ($this->php_lib_path . 'class-wcmp-product-export-fields.php');
	  $WCMp_Export_Fields_arr = new WCMp_Product_Export_Fields(); 
	  return $WCMp_Export_Fields_arr;
	}
	
	public function load_import_meta_fields_arr() {
	  global $WCMp_Product_Import_Export_Bundle;
	  if ( ! class_exists( 'WCMp_Product_Import_Fields' ) )
	    require_once ($this->php_lib_path . 'class-wcmp-product-import-fields.php');
	  $WCMp_Import_Fields_arr = new WCMp_Product_Import_Fields(); 
	  return $WCMp_Import_Fields_arr;
	}
	
	/**
	 * Jquery qTip library
	 */
	public function load_qtip_lib() {
	  global $WCMp_Product_Import_Export_Bundle;
	  wp_enqueue_script('qtip_js', $this->jquery_lib_url . 'qtip/qtip.js', array('jquery'), $WCMp_Product_Import_Export_Bundle->version, true);
		wp_enqueue_style('qtip_css',  $this->jquery_lib_url . 'qtip/qtip.css', array(), $WCMp_Product_Import_Export_Bundle->version);
	}
	
	/**
	 * WP Media library
	 */
	public function load_upload_lib() {
	  global $WCMp_Product_Import_Export_Bundle;
	  wp_enqueue_media();
	  wp_enqueue_script('upload_js', $this->jquery_lib_url . 'upload/media-upload.js', array('jquery'), $WCMp_Product_Import_Export_Bundle->version, true);
	  wp_enqueue_style('upload_css',  $this->jquery_lib_url . 'upload/media-upload.css', array(), $WCMp_Product_Import_Export_Bundle->version);
	}
	
	/**
	 * WP ColorPicker library
	 */
	public function load_colorpicker_lib() {
	  global $WCMp_Product_Import_Export_Bundle;
	  wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'colorpicker_init', $this->jquery_lib_url . 'colorpicker/colorpicker.js', array( 'jquery', 'wp-color-picker' ), $WCMp_Product_Import_Export_Bundle->version, true );
    wp_enqueue_style( 'wp-color-picker' );
	}
	
	/**
	 * WP DatePicker library
	 */
	public function load_datepicker_lib() {
	  global $WCMp_Product_Import_Export_Bundle;
	  wp_enqueue_script('jquery-ui-datepicker');
	  wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	}
}
