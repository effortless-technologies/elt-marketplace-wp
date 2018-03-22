<?php

class WCMp_Frontend_Product_Manager_Admin {

  public function __construct() {

    add_action('admin_enqueue_scripts', array(&$this, 'enqueue_admin_script'), 30);
    //filter for adding capability checkboxes for import export in admin settings capabilites products section
    add_filter('settings_capabilities_product_tab_options', array($this, 'add_capabilites_section'), 10, 1);

    //filter for saving capability checkboxes for import export in admin settings capabilites products section
    add_filter("settings_capabilities_product_tab_new_input", array($this, 'save_capabilities_section'), 10, 2);

    add_filter('is_wcmp_backend_disabled',array(&$this,'is_wcmp_backend_disabled'));

    add_filter('wcmp_vendor_product_types', array(&$this, 'add_product_types_callback'), 10, 1);
    //add_action( 'admin_menu', array( $this, 'remove_product_menu' ) );
    
    add_filter('settings_capabilities_product_tab_options', array($this, 'settings_capabilities_product_tab_options_callback'));

  }

  public function add_product_types_callback($types) {
    // Booking
    if( wcmp_forntend_manager_is_booking() ) {
      $types['booking'] = array('title' => __('Booking', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'id' => 'booking', 'label_for' => 'booking', 'name' => 'booking', 'value' => 'Enable'); // Checkbox
    }
    if( wcmp_forntend_manager_is_accommodation_booking() ) {
      $types['accommodation-booking'] = array('title' => __('Accommodation Booking', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'id' => 'accommodation-booking', 'label_for' => 'accommodation-booking', 'name' => 'accommodation-booking', 'value' => 'Enable'); // Checkbox
    }
    if( wcmp_forntend_manager_is_bundle() ) {
      $types['bundle'] = array('title' => __('Product bundle', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'id' => 'bundle', 'label_for' => 'bundle', 'name' => 'bundle', 'value' => 'Enable'); // Checkbox
    }
    if( wcmp_forntend_manager_is_subscription() ) {
      $types['subscription'] = array('title' => __('Simple Subscription', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'id' => 'subscription', 'label_for' => 'subscription', 'name' => 'subscription', 'value' => 'Enable'); // Checkbox
      $types['variable-subscription'] = array('title' => __('Variable Subscription', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'id' => 'variable-subscription', 'label_for' => 'variable-subscription', 'name' => 'variable-subscription', 'value' => 'Enable'); // Checkbox
    }
    if( wcmp_forntend_manager_is_yithauction() || wcmp_forntend_manager_is_wcsauction() ) {
      $types['auction'] = array('title' => __('Auction', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'id' => 'auction', 'label_for' => 'auction', 'name' => 'auction', 'value' => 'Enable'); // Checkbox
    }
    return $types;
  }

  /**
   * Remove product tab from backend
   */

  public function remove_product_menu() {
    $user = wp_get_current_user();
    if (is_user_wcmp_vendor($user)) {
      remove_menu_page( 'edit.php?post_type=product' );
    }
    
  }

 /**
  *adds capability checkboxes for import export in admin settings capabilites products section
  *@access public
  *@return array
  */
  public function add_capabilites_section($settings_tab_options) {
    $settings_tab_options["sections"]["products_capability"]["fields"]["vendor_import_capability"] = array('title' => __('Vendor Import Capability', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'id' => 'vendor_import_capability', 'label_for' => 'vendor_import_capability', 'text' => __('Allow vendors to import products.', 'wcmp-frontend_product_manager'), 'name' => 'vendor_import_capability', 'value' => 'Enable');
    $settings_tab_options["sections"]["products_capability"]["fields"]["vendor_export_capability"] = array('title' => __('Vendor Export Capability', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'id' => 'vendor_export_capability', 'label_for' => 'vendor_export_capability', 'text' => __('Allow vendors to export products.', 'wcmp-frontend_product_manager'), 'name' => 'vendor_export_capability', 'value' => 'Enable');
    // Auction
    /*if( wcmp_forntend_manager_is_yithauction() || wcmp_forntend_manager_is_wcsauction() ) { 
      $settings_tab_options['sections']['products_capability']['fields']['manage_auctions'] = array('title' => __('Manage Auctions', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'id' => 'manage_auctions', 'label_for' => 'manage_auctions', 'name' => 'manage_auctions', 'desc' => __('Allow vendors to manager Auction Product.', 'wcmp-frontend_product_manager'), 'value' => 'Enable');
    }*/
   // Booking
    /*if( wcmp_forntend_manager_is_booking() ) {
      $settings_tab_options['sections']['products_capability']['fields']['manage_bookings'] = array('title' => __('Manage Bookings', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'id' => 'manage_bookings', 'label_for' => 'manage_bookings', 'name' => 'manage_bookings', 'desc' => __('Allow vendors to manager Bookable Product.', 'wcmp-frontend_product_manager'), 'value' => 'Enable');
    }*/
    //accommodation product
    /*if( wcmp_forntend_manager_is_accommodation_booking() ) {
      $settings_tab_options['sections']['products_capability']['fields']['manage_accommodation'] = array('title' => __('Manage Accommodation Booking', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'id' => 'manage_accommodation', 'label_for' => 'manage_accommodation', 'name' => 'manage_accommodation', 'desc' => __('Allow vendors to manage Accommodation Booking Product.', 'wcmp-frontend_product_manager'), 'value' => 'Enable');
    }*/

    //bundle product
    /*if( wcmp_forntend_manager_is_bundle() ) {
      $settings_tab_options['sections']['products_capability']['fields']['manage_bundles'] = array('title' => __('Manage Bundles', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'id' => 'manage_bundles', 'label_for' => 'manage_bundles', 'name' => 'manage_bundles', 'desc' => __('Allow vendors to manager Bundle Product.', 'wcmp-frontend_product_manager'), 'value' => 'Enable');
    }*/

    // Subsctiptions
    /*if( wcmp_forntend_manager_is_subscription() ) {
      $settings_tab_options['sections']['products_capability']['fields']['manage_subscriptions'] = array('title' => __('Manage Subscriptions', 'wcmp-frontend_product_manager'), 'type' => 'checkbox', 'id' => 'manage_subscriptions', 'label_for' => 'manage_subscriptions', 'name' => 'manage_subscriptions', 'desc' => __('Allow vendors to manager Subscription Product.', 'wcmp-frontend_product_manager'), 'value' => 'Enable');
    }*/
    return $settings_tab_options;
  }
    
  /**
  *saves capability checkboxes for import export in admin settings capabilites products section
  *@access public
  *@return array
  */
  public function save_capabilities_section($new_input, $input){
    $vendor_role = get_role( 'dc_vendor' );
    if (isset($input['vendor_import_capability'])) {
      $new_input['vendor_import_capability'] = sanitize_text_field($input['vendor_import_capability']);
    }
    if (isset($input['vendor_export_capability'])) {
      $new_input['vendor_export_capability'] = sanitize_text_field($input['vendor_export_capability']);
    }

    /*if ( isset($input['manage_auctions']) ) {
      $new_input['manage_auctions'] = sanitize_text_field($input['manage_auctions']);
      $vendor_role->add_cap( 'manage_auctions' );
    } else {
      $vendor_role->remove_cap( 'manage_auctions' );
    }*/
    if (isset($input['auction'])) {
      $new_input['auction'] = sanitize_text_field($input['auction']);
      $vendor_role->add_cap( 'manage_auctions' );
    }else{
        $vendor_role->remove_cap( 'manage_auctions' );
    }

    /*if ( isset($input['manage_bookings']) ) {
      $new_input['manage_bookings'] = sanitize_text_field($input['manage_bookings']);
      $vendor_role->add_cap( 'manage_bookings' );
    } else {
      $vendor_role->remove_cap( 'manage_bookings' );
    }*/
    if (isset($input['booking'])) {
      $new_input['booking'] = sanitize_text_field($input['booking']);
      $vendor_role->add_cap( 'manage_bookings' );
    }else{
        $vendor_role->remove_cap( 'manage_bookings' );
    }

    /*if ( isset($input['manage_accommodation']) ) {
      $new_input['manage_accommodation'] = sanitize_text_field($input['manage_accommodation']);     
      $vendor_role->add_cap( 'manage_accommodation' );
    } else {
      $vendor_role->remove_cap( 'manage_accommodation' );
    }*/
    if (isset($input['accommodation-booking'])) {
      $new_input['accommodation-booking'] = sanitize_text_field($input['accommodation-booking']);
    }

    /*if ( isset($input['manage_bundles']) ) {
      $new_input['manage_bundles'] = sanitize_text_field($input['manage_bundles']);
      $vendor_role->add_cap( 'manage_bundles' );
    } else {
      $vendor_role->remove_cap( 'manage_bundles' );
    }*/
    if (isset($input['bundle'])) {
      $new_input['bundle'] = sanitize_text_field($input['bundle']);
    }

    /*if ( isset($input['manage_subscriptions']) ) {
      $new_input['manage_subscriptions'] = sanitize_text_field($input['manage_subscriptions']);
      $vendor_role->add_cap( 'manage_subscriptions' );
    } else {
      $vendor_role->remove_cap( 'manage_subscriptions' );
    }*/
    if (isset($input['subscription'])) {
      $new_input['subscription'] = sanitize_text_field($input['subscription']);
    }
    if (isset($input['variable-subscription'])) {
      $new_input['variable-subscription'] = sanitize_text_field($input['variable-subscription']);
    }
    return $new_input;
  }

  /**
   * Admin Scripts
   */
  public function enqueue_admin_script() {
    global $WCMp, $woocommerce, $WCMp_Frontend_Product_Manager;
    $screen = get_current_screen();  
     if (in_array( $screen->id, array( 'wcmp_page_wcmp-setting-admin' ))) {
      //edited by DCube
      if( wcmp_forntend_manager_is_accommodation_booking() ) {
        wp_enqueue_script('wcmp_accommodation_booking_admin_js', $WCMp_Frontend_Product_Manager->plugin_url.'assets/admin/js/wcmp_accommodation_booking_admin_js.js', array('jquery'), $WCMp_Frontend_Product_Manager->version, true);
      }
    }   
  }
   
  /*
   * enable wcmp backend enable / disabled function
   */
  public function is_wcmp_backend_disabled($option){
      $option['custom_tags'] = array();
      $option['text'] = __('Offer a single frontend dashboard for all vendor purpose and eliminate their backend access requirement.','wcmp-frontend_product_manager');
      return $option;
  } 
  
  public function settings_capabilities_product_tab_options_callback($tab_options){
      $tab_options['sections']['default_settings_section_types']['fields']['simple']['text'] = '';
      $tab_options['sections']['default_settings_section_types']['fields']['variable']['text'] = '';
      $tab_options['sections']['default_settings_section_types']['fields']['grouped']['text'] = '';
      $tab_options['sections']['default_settings_section_types']['fields']['external']['text'] = '';
      return $tab_options;
  }

}
