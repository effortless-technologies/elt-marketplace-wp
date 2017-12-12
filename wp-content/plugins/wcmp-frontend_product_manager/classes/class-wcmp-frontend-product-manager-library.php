<?php
class WCMp_Frontend_Product_Manager_Library {
  
  public $lib_path;
  
  public $lib_url;
  
  public $php_lib_path;
  
  public $php_lib_url;
  
  public $jquery_lib_path;
  
  public $jquery_lib_url;

	public function __construct() {
	  global $WCMp_Frontend_Product_Manager;
	  
	  $this->lib_path = $WCMp_Frontend_Product_Manager->plugin_path . 'lib/';

    $this->lib_url = $WCMp_Frontend_Product_Manager->plugin_url . 'lib/';
    
    $this->php_lib_path = $this->lib_path . 'php/';
    
    $this->php_lib_url = $this->lib_url . 'php/';
    
    $this->jquery_lib_path = $this->lib_path . 'jquery/';
    
    $this->jquery_lib_url = $this->lib_url . 'jquery/';
	}
	
	/**
	 * PHP WP fields Library
	*/
	public function load_fpm_fields() {
	  global $WCMp, $WCMp_Frontend_Product_Manager;
	  require_once ($this->php_lib_path . 'class-fpm-wp-fields.php');
	  $FPM_WP_Fields = new WCMp_FPM_WP_Fields(); 
	  return $FPM_WP_Fields;
	}
	
	/**
	 * Jquery TinyMCE library
	 */
	public function load_tinymce_lib() {
	  global $WCMp, $WCMp_Frontend_Product_Manager;
	  wp_enqueue_script('tinymce_js', '//cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/tinymce.min.js', array('jquery'), $WCMp_Frontend_Product_Manager->version, true);
	  wp_enqueue_script('jquery_tinymce_js', '//cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/jquery.tinymce.min.js', array('jquery'), $WCMp_Frontend_Product_Manager->version, true);
	}
	
	/**
	 * WP Media library
	*/
	public function load_upload_lib() {
	  global $WCMp, $WCMp_Frontend_Product_Manager;
	  wp_enqueue_media();
	  wp_enqueue_script('upload_js', $this->lib_url . 'upload/media-upload.js', array('jquery'), $WCMp_Frontend_Product_Manager->version, true);
	  wp_enqueue_style('upload_css',  $this->lib_url . 'upload/media-upload.css', array(), $WCMp_Frontend_Product_Manager->version);
	}
	
	/**
	 * Select2 library
	*/
	public function load_select2_lib() {
	  global $WCMp, $WCMp_Frontend_Product_Manager;
	  wp_enqueue_script('select2_js', $this->lib_url . 'select2/select2.js', array('jquery'), $WCMp_Frontend_Product_Manager->version, true);
	  wp_enqueue_style('select2_css',  $this->lib_url . 'select2/select2.css', array(), $WCMp_Frontend_Product_Manager->version);
	}
	
	/**
	 * Jquery Accordian library
	 */
	public function load_accordian_lib() {
	  global $WCMp_Frontend_Product_Manager;
	  wp_enqueue_script('accordian_js', '//code.jquery.com/ui/1.11.4/jquery-ui.js', array('jquery'), $WCMp_Frontend_Product_Manager->version, true);
	  wp_enqueue_style('accordian_css',  '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css', array(), $WCMp_Frontend_Product_Manager->version);
	}
	
}