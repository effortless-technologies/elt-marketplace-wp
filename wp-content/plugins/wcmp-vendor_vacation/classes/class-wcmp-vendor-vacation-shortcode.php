<?php
class WCMP_Vendor_Vacation_Shortcode {

	public $list_product;

	public function __construct() {
		//shortcodes
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
		global $WCMP_Vendor_Vacation;
		if ('' != $class_name && '' != $WCMP_Vendor_Vacation->token) {
			require_once ('shortcode/class-' . esc_attr($WCMP_Vendor_Vacation->token) . '-shortcode-' . esc_attr($class_name) . '.php');
		}
	}

}
?>
