<?php
class WCMP_Vendor_Frontend_Report_Shortcode {
	
	public $list_product;

	public function __construct() {
		//shortcodes
		add_shortcode('vendor_frontend_report', array(&$this, 'report_overview'));
		add_shortcode('vendor_report_sales_overview', array(&$this, 'report_sales_overview'));
		add_shortcode('vendor_report_product_overview', array(&$this, 'report_product_overview'));
		add_shortcode('vendor_report_search_by_product', array(&$this, 'report_search_by_product'));
		add_shortcode('vendor_report_stock_low_stock', array(&$this, 'report_stock_low_stock'));
		add_shortcode('vendor_report_stock_out_of_stock', array(&$this, 'report_stock_out_of_stock'));
		add_shortcode('vendor_report_stock_most_stock', array(&$this, 'report_stock_most'));
		add_shortcode('vendor_report_transaction_overview', array(&$this, 'report_transaction_overview'));
	}
	
	

	public function report_overview($attr) {
		global $WCMp_Vendor_Frontend_Report;
		$this->load_class('overview');
		return $this->shortcode_wrapper(array('WCMP_Vendor_Report_Shortcode_Overview', 'output'));
	}
	
	public function report_sales_overview($attr) {
		global $WCMp_Vendor_Frontend_Report;
		$this->load_class('sales-overview');
		return $this->shortcode_wrapper(array('WCMP_Vendor_Report_Shortcode_Sales_Overview', 'output'));
	}
	
	public function report_product_overview($attr) {
		global $WCMp_Vendor_Frontend_Report;
		$this->load_class('product-overview');
		return $this->shortcode_wrapper(array('WCMP_Vendor_Report_Shortcode_Product_Overview', 'output'));
	}
	
	public function report_search_by_product($attr) {
		global $WCMp_Vendor_Frontend_Report;
		$this->load_class('search-by-product');
		return $this->shortcode_wrapper(array('WCMP_Vendor_Report_Shortcode_Search_By_Product', 'output'));
	}
	
	public function report_stock_low_stock($attr) {
		global $WCMp_Vendor_Frontend_Report;
		$this->load_class('low-stock');
		return $this->shortcode_wrapper(array('WCMP_Vendor_Report_Shortcode_Low_Stock', 'output'));
	}
	
	public function report_stock_out_of_stock($attr) {
		global $WCMp_Vendor_Frontend_Report;
		$this->load_class('out-of-stock');
		return $this->shortcode_wrapper(array('WCMP_Vendor_Report_Shortcode_Out_Of_Stock', 'output'));
	}
	
	public function report_stock_most($attr) {
		global $WCMp_Vendor_Frontend_Report;
		$this->load_class('most-stock');
		return $this->shortcode_wrapper(array('WCMP_Vendor_Report_Shortcode_Most_Stock', 'output'));
	}
	
	public function report_transaction_overview($attr) {
		global $WCMp_Vendor_Frontend_Report;
		$this->load_class('transaction-overview');
		return $this->shortcode_wrapper(array('WCMP_Vendor_Report_Shortcode_Transaction_Overview', 'output'));
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
		global $WCMp_Vendor_Frontend_Report;
		if ('' != $class_name && '' != $WCMp_Vendor_Frontend_Report->token) {
			require_once ('shortcode/class-' . esc_attr($WCMp_Vendor_Frontend_Report->token) . '-shortcode-' . esc_attr($class_name) . '.php');
		}
	}

}
?>