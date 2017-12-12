<?php
class WCMp_Frontend_Product_Manager_Shortcode {

	public $list_product;

	public function __construct() {
		
		// Product Manager Shortcodes
		add_shortcode('wcmp_frontend_product_manager', array(&$this, 'frontend_product_manager'));
		
		add_shortcode('wcmp_pending_products', array(&$this, 'wcmp_pending_products'));
		
		// Coupon Manager Shortcodes
		add_shortcode('wcmp_frontend_coupon_manager', array(&$this, 'frontend_coupon_manager'));
		
		add_shortcode('wcmp_coupons', array(&$this, 'wcmp_coupons'));
	}

	public function frontend_product_manager($attr) {
		global $WCMp_Frontend_Product_Manager;
		$this->load_class('frontend-product-manager');
		return $this->shortcode_wrapper(array('Frontend_Product_Manager_Shortcode', 'output'));
	}
	
	public function wcmp_pending_products($attr) {
		global $WCMp_Frontend_Product_Manager;
		$this->load_class('wcmp-pending-products');
		return $this->shortcode_wrapper(array('WCMp_Pending_Products_Shortcode', 'output'));
	}
	
	public function frontend_coupon_manager($attr) {
		global $WCMp_Frontend_Product_Manager;
		$this->load_class('frontend-coupon-manager');
		return $this->shortcode_wrapper(array('Frontend_Coupon_Manager_Shortcode', 'output'));
	}
	
	public function wcmp_coupons($attr) {
		global $WCMp_Frontend_Product_Manager;
		$this->load_class('wcmp-coupons');
		return $this->shortcode_wrapper(array('WCMp_Coupons_Shortcode', 'output'));
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