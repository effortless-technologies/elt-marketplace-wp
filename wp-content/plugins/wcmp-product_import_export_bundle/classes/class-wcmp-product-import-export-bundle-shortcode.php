<?php
class WCMp_Product_Import_Export_Bundle_Shortcode {


	public function __construct() {
		//shortcodes
		add_shortcode( 'upload_products', array($this,'do_upload_products') );
	}


    public function do_upload_products($attr) {
		global $WCMp_Product_Import_Export_Bundle;
		$this->load_class('upload-product');
		return $this->shortcode_wrapper(array('WCMp_Upload_Product_Shortcode', 'execute_shortcode'));
	}
	/**
	 * Shortcode Wrapper
	 *
	 * @access public
	 * @param mixed $function
	 * @param array $atts (default: array())
	 * @return string
	 */
	public function shortcode_wrapper($function, $atts = array()) {
		ob_start();
		call_user_func($function, $atts);
		return ob_get_clean();
	}

	/**
	 * Shortcode CLass Loader
	 *
	 * @access public
	 * @param mixed $class_name
	 * @return void
	 */
	public function load_class($class_name = '') {
		global $WCMp_Product_Import_Export_Bundle;

		if ('' != $class_name && '' != $WCMp_Product_Import_Export_Bundle->token) {
			require_once ('shortcode/class-' . esc_attr($WCMp_Product_Import_Export_Bundle->token) . '-shortcode-' . esc_attr($class_name) . '.php');
		}
	}


}
?>
