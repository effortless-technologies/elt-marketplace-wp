<?php
class WCMP_Vendor_Vacation_Ajax {

	public function __construct() {
		add_action('wp', array(&$this, 'demo_ajax_method'));
	}

	public function demo_ajax_method() {
	  // Do your ajx job here
	  
	}

}
