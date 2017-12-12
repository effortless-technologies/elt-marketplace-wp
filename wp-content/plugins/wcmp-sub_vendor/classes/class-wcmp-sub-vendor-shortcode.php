<?php
class WCMP_Sub_Vendor_Shortcode {

	public $list_product;

	public function __construct() {
		//shortcodes
		add_shortcode('demo_shortcode', array(&$this, 'demo_shortcode'));
	}

	public function demo_shortcode($attr) {
		global $WCMP_Sub_Vendor;
		$this->load_class('demo-shortcode');
		return $this->shortcode_wrapper(array('WC_Demo_Shortcode', 'output'));
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
		global $WCMP_Sub_Vendor;
		if ('' != $class_name && '' != $WCMP_Sub_Vendor->token) {
			require_once ('shortcode/class-' . esc_attr($WCMP_Sub_Vendor->token) . '-shortcode-' . esc_attr($class_name) . '.php');
		}
	}

}
?>
