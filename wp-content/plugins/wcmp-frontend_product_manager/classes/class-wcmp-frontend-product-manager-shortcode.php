<?php
class WCMp_Frontend_Product_Manager_Shortcode {

	public $list_product;

	public function __construct() {
		
		// Product Manager Shortcodes

	}



	/**
	 * Helper Functions
	 */

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
		global $WCMp_Frontend_Product_Manager;
		if ('' != $class_name && '' != $WCMp_Frontend_Product_Manager->token) {
			require_once ('shortcode/class-' . esc_attr($WCMp_Frontend_Product_Manager->token) . '-shortcode-' . esc_attr($class_name) . '.php');
		}
	}

}
?>