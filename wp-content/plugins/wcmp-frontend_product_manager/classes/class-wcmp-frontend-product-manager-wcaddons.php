<?php

/**
 * WCMp Product Types plugin core
 *
 * Auction WC Product Addons Support
 *
 * @author 		WC Marketplace
 * @package 	wcmp-pts/classes
 * @version   1.1.1
 */
 
class WCMp_Frontend_Product_Manager_WC_Addons {
	
	public function __construct() {
    global $WCMp, $WCMp_Frontend_Product_Manager;
    
    if( wcmp_forntend_manager_has_wcaddons() ) {
    	// Auction Product Manage View
			add_action( 'end_wcmp_fpm_products_manage', array( &$this, 'wcaddons_wcmp_pts_form_load_views' ), 110 );
			
			// Auction Product Meta Data Save
			add_action( 'after_wcmp_fpm_meta_save', array( &$this, 'wcaddons_wcmp_pts_meta_save' ), 100, 2 );
		}
	}
	
	/**
   * WC Product Addons load views
   */
  function wcaddons_wcmp_pts_form_load_views( $product_id ) {
		global $WCMp, $WCMp_Frontend_Product_Manager;
	  $WCMp_Frontend_Product_Manager->template->get_template('wcmp-fpm-view-wcaddons.php', array('pro_id' => $product_id));
	}
	
	/**
	 * WC Product Addons Product Meta data save
	 */
	function wcaddons_wcmp_pts_meta_save( $new_product_id, $product_manager_form_data ) {
		global $wpdb, $WCMp, $WCMp_Frontend_Product_Manager, $_POST;
		
		$_product_addons = array();
		
		if( isset( $product_manager_form_data['_product_addons'] ) && !empty( $product_manager_form_data['_product_addons'] ) ) {
		  $_product_addons = $product_manager_form_data['_product_addons'];
		  
		  if( !empty( $_product_addons ) ) {
		  	$loop_index = 0;
		  	foreach( $_product_addons as $_product_addon_index => $_product_addon ) {
		  		$_product_addons[$_product_addon_index]['position'] = $loop_index;
		  		if( isset( $_product_addon['required'] ) ) $_product_addons[$_product_addon_index]['required'] = 1;
		  		else $_product_addons[$_product_addon_index]['required'] = 0;
		  		$loop_index++;
		  	}
		  }
		  $_product_addons = apply_filters('woo_addon_data_save', $_product_addons);
		  update_post_meta( $new_product_id, '_product_addons', $_product_addons );
		}
	}
	
}