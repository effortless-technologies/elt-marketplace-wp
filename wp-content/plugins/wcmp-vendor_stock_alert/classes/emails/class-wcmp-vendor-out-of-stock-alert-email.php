<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WCMp_Email_Out_of_Stock_Alert' ) ) :

/**
 * Email for Vendor Stock Alert
 *
 * An email will be sent to all Vendors of a product when the product is out of stock
 *
 * @class 		WCMp_Email_Out_of_Stock_Alert
 * @extends 	WC_Email
 */
class WCMp_Email_Out_of_Stock_Alert extends WC_Email {
	public $product_id;

	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
		
		global $WCMp_Vendor_Stock_Alert;
		
		$this->id 				= 'vendor_out_of_stock_alert';
		$this->title 			= __( 'Vendor Out of Stock Alert', 'wcmp-vendor_stock_alert' );
		$this->description		= __( 'Alert vendors when their product is out of stock', 'wcmp-vendor_stock_alert' );

		$this->template_html 	= 'emails/vendor_out_of_stock_alert_email.php';
		$this->template_plain 	= 'emails/plain/vendor_out_of_stock_alert_email.php';

		$this->subject 			= __( 'A Product on {site_title} is out of stock', 'wcmp-vendor_stock_alert' );
		$this->heading      	= __( 'Welcome to {site_title}', 'wcmp-vendor_stock_alert' );
		$this->template_base = $WCMp_Vendor_Stock_Alert->plugin_path . 'templates/';
		
		// Call parent constuctor
		parent::__construct();
	}

	/**
	 * trigger function.
	 *
	 * @access public
	 * @return void
	 */
	function trigger( $recipient, $product_id ) {
		
		$this->recipient = $recipient;
		$this->product_id = $product_id;
		
		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}
			
		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}
	
	/**
	 * get_subject function.
	 *
	 * @access public
	 * @return string
	 */
	function get_subject() {
			return apply_filters( 'woocommerce_email_subject_stock_alert', $this->format_string( $this->subject ), $this->object );
	}

	/**
	 * get_heading function.
	 *
	 * @access public
	 * @return string
	 */
	function get_heading() {
			return apply_filters( 'woocommerce_email_heading_stock_alert', $this->format_string( $this->heading ), $this->object );
	}

	/**
	 * get_content_html function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_html() {
		ob_start();
		wc_get_template( $this->template_html, array(
			'email_heading' => $this->get_heading(),
			'product_id' => $this->product_id,
			'sent_to_admin' => false,
			'plain_text' => false
		), '', $this->template_base);
		return ob_get_clean();
	}

	/**
	 * get_content_plain function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_plain() {
		ob_start();
		wc_get_template( $this->template_plain, array(
			'email_heading' => $this->get_heading(),
			'product_id' => $this->product_id,
			'sent_to_admin' => false,
			'plain_text' => true
		) ,'', $this->template_base );
		return ob_get_clean();
	}
}

endif;

return new WCMp_Email_Out_of_Stock_Alert();
