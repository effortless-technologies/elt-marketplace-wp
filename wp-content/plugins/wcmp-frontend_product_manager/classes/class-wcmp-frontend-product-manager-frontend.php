<?php
class WCMp_Frontend_Product_Manager_Frontend {

	public function __construct() {
		add_action( 'woocommerce_before_shop_loop_item', array(&$this, 'forntend_product_edit'), 5 );
		
		add_action( 'woocommerce_before_single_product_summary', array(&$this, 'forntend_product_edit'), 5 );
		
		add_action( 'template_redirect', array(&$this, 'template_redirect' ));
		
		add_filter( 'wcmp_vendor_submit_product', array(&$this, 'wcmp_vendor_submit_product_url') );
		
		add_filter( 'wcmp_vendor_submit_coupon', array(&$this, 'wcmp_vendor_submit_coupon_url') );
		
		add_filter( 'wcmp_vendor_coupons', array(&$this, 'wcmp_vendor_coupons_url') );
		
		add_filter( 'wcmp_vendor_dashboard_nav', array( &$this, 'fpm_vendor_dashboard_nav' ) );
		
		add_filter( 'wcmp_vendor_dashboard_nav_item_css_class', array(&$this, 'wcmp_fpm_vendor_dashboard_nav_item_css_class'), 10, 2 );
                
    add_action('after_vendor_report',array(&$this,'advance_vendor_report'),10,2);
		
		add_filter( 'wcmp_plugin_pages_redirect', array(&$this, 'is_fpm_vendor_page') );
		
		// Grid Plus Fix
    add_filter( 'mce_external_plugins', array($this, 'grid_custom_editor_register_tinymce_javascript'), 100 );
		
		//enqueue scripts
		add_action('wp_enqueue_scripts', array(&$this, 'frontend_scripts'));
		//enqueue styles
		add_action('wp_enqueue_scripts', array(&$this, 'frontend_styles'));
                
    add_filter('is_wcmp_vendor_dashboard', array(&$this, 'is_wcmp_vendor_dashboard'));

	}
	
	// WCMp menu
  function fpm_vendor_dashboard_nav( $vendor_nav ) {
  	global $WCMp_Frontend_Product_Manager;
  	
  	// Migrating to WCMp 2.6.0
  	$array_pages = get_option('wcmp_vendor_general_settings_name');

		// Product Manager Pages
		if (!isset($array_pages['frontend_product_manager']) || !isset($array_pages['wcmp_pending_products'])) {
				$array_pages['frontend_product_manager'] = get_option('wcmp_frontend_product_manager_page_id');
				$array_pages['wcmp_pending_products'] = get_option('wcmp_pending_products_page_id');
				update_option('wcmp_vendor_general_settings_name', $array_pages);
		}

		// Coupon Manager Pages
		if (!isset($array_pages['frontend_coupon_manager']) || !isset($array_pages['wcmp_coupons'])) {
				$array_pages['frontend_coupon_manager'] = get_option('wcmp_frontend_coupon_manager_page_id');
				$array_pages['wcmp_coupons'] = get_option('wcmp_coupons_page_id');
				update_option('wcmp_vendor_general_settings_name', $array_pages);
		}
  	
  	// WCMp Products Menu
  	if( current_user_can( 'edit_products' ) ) {
  		$vendor_nav['vendor-products']['url'] = '#';
  		$vendor_nav['vendor-products']['submenu'] = array(
																												'add-new-product' => array(
																														'label' => __('Add Product', 'wcmp_frontend_product_manager')
																														, 'url' => get_forntend_product_manager_page()
																														, 'capability' => apply_filters('wcmp_vendor_dashboard_menu_add_new_product_capability', 'edit_products')
																														, 'position' => 10
																														, 'link_target' => '_self'
																												),
																												'products' => array(
																														'label' => __('Products', 'wcmp_frontend_product_manager')
																														, 'url' => get_vendor_products_page()
																														, 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_products_capability', 'edit_products')
																														, 'position' => 20
																														, 'link_target' => '_self'
																												)
																										);
  	}
  	                
  	// WCMp Coupons Menu
  	if( current_user_can( 'edit_shop_coupons' ) ) {
  		$vendor_nav['vendor-promte']['url'] = '#';
  		$vendor_nav['vendor-promte']['submenu']['add-new-coupon']['url'] = get_frontend_coupon_manager_page();
  		$vendor_nav['vendor-promte']['submenu']['coupons']['url'] = get_vendor_coupons_page();
  	}
  	
  	
  	
  	return $vendor_nav;
  }
  
  function wcmp_fpm_vendor_dashboard_nav_item_css_class( $cssClass, $endpoint ) {
  	global $wp;
  	if( ($endpoint == 'dashboard') && ( is_vendor_coupons_page() || is_vendor_products_page() ) ) {
  			if(($key = array_search('active', $cssClass)) !== false) {
					unset($cssClass[$key]);
				}
  	}
  	if( is_vendor_coupons_page() && ( $endpoint == 'vendor-promte' )) $cssClass[] = 'active';
  	if(  is_vendor_coupons_page() && ( $endpoint == 'coupons' ) ) $cssClass[] = 'active';
  	
  	if( is_vendor_products_page() && ( $endpoint == 'vendor-products' )) $cssClass[] = 'active';
  	if( is_vendor_products_page() && ( $endpoint == 'products' ) ) $cssClass[] = 'active';
  	
  	return $cssClass;
  }
	
	/*
	 * Output advance vendor report
	 */
	function advance_vendor_report($vendor, $selected_item){
			global $WCMp, $WCMp_Frontend_Product_Manager; 
			if(!class_exists('WCMP_Vendor_Frontend_Report')){
			?>
			<li><a class="show_addons_msg" data-popup-target="#show_addons_msg" data-time_out="30" href="#"><?php _e('- Reports', $WCMp->text_domain); ?></a></li>
	<?php } 
			}


 /**
	* check if fpm vendor pages
	* @return boolean
	*/
	function is_fpm_vendor_page($return) {
	
		$return = false;
		
		$pages = get_option("wcmp_vendor_general_settings_name");
		
		if(is_page( ( $pages['frontend_product_manager'] ) ) ) $return = true;
		
		if(is_page( ( $pages['wcmp_pending_products'] ) ) ) $return = true;
		
		if(is_page( ( $pages['wcmp_coupons'] ) ) ) $return = true;
		
		if(is_page( ( $pages['wcmp_vendor'] ) ) ) $return = true;
		
		if(is_page( ( $pages['vendor_registration'] ) ) ) $return = true;
		
		return $return;
	}
	
	function forntend_product_edit() {
		global $WCMp, $WCMp_Frontend_Product_Manager, $post, $woocommerce_loop;

		if( !is_user_logged_in() ) return;
		$current_vendor_id = apply_filters( 'wcmp_current_loggedin_vendor_id', get_current_user_id() );
		if( !current_user_can( 'administrator' ) && !get_wcmp_product_vendors( $post->ID ) ) return;
		
		$vendor_data = get_wcmp_product_vendors( $post->ID );
		if(!current_user_can( 'administrator' ) && $vendor_data && ($vendor_data->id != $current_vendor_id)) return;
		
		if(is_user_wcmp_vendor( $current_vendor_id ) && !current_user_can('edit_products')) return;
		
		$pro_id = $post->ID;
		
		?>
		<div class="wcmp_fpm_buttons">
		  <?php if( current_user_can( 'edit_published_products' ) ) { ?>
				<a class="wcmp_fpm_button" href="<?php echo add_query_arg('pro_id', $pro_id, get_forntend_product_manager_page()); ?>">
					<img width="16" height="16" src="<?php echo $WCMp_Frontend_Product_Manager->plugin_url; ?>/assets/images/edit.png" />
				</a>
			<?php } ?>
			<?php if( current_user_can( 'delete_published_products' ) ) { ?>
				<span class="wcmp_fpm_button_separator">--</span>
				<a class="wcmp_fpm_button wcmp_fpm_delete" href="#" data-proid="<?php echo $pro_id; ?>">
					<img width="16" height="16" src="<?php echo $WCMp_Frontend_Product_Manager->plugin_url; ?>/assets/images/trash.png" />
				</a>
			<?php } ?>
		</div>
		<?php
		
	}
	
	/**
	 * Template redirect function
	 * @return void
	*/
	function template_redirect() {
		global $WCMp, $WCMp_Frontend_Product_Manager;
		
		$pages = get_option("wcmp_pages_settings_name");
		$current_vendor_id = apply_filters( 'wcmp_current_loggedin_vendor_id', get_current_user_id() );
		
		// If user not loggedin then reirect to MyAccount page
		if( !is_user_logged_in() && (is_forntend_product_manager_page() || is_vendor_coupons_page() || is_frontend_coupon_manager_page()) && ! is_page( wc_get_page_id( 'myaccount' ) ) ) {
      wp_safe_redirect( get_permalink( wc_get_page_id( 'myaccount' ) ) );
      exit();
    }
    
    // If loggedin user non Vendor then redirect to Shop page
    if( is_user_logged_in() && (is_forntend_product_manager_page() || is_vendor_coupons_page() || is_frontend_coupon_manager_page()) && ! is_user_wcmp_vendor( $current_vendor_id ) && !current_user_can( 'administrator' ) ) {
    	wp_safe_redirect( get_permalink( wc_get_page_id( 'shop' ) ) );
    }
	}
	
	function wcmp_vendor_submit_product_url() {
		return get_forntend_product_manager_page();
	}
	
	function wcmp_vendor_coupons_url() {
		return get_vendor_coupons_page();
	}
	
	function wcmp_vendor_submit_coupon_url() {
		return get_frontend_coupon_manager_page();
	}
	
	public function grid_custom_editor_register_tinymce_javascript( $plugin_array ) {
		unset( $plugin_array['grid_custom_editor'] );
		return $plugin_array;
	}

	function frontend_scripts() {
		global $WCMp, $WCMp_Frontend_Product_Manager;
		$frontend_script_path = $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/js/';
		$frontend_script_path = str_replace( array( 'http:', 'https:' ), '', $frontend_script_path );
		$pluginURL = str_replace( array( 'http:', 'https:' ), '', $WCMp_Frontend_Product_Manager->plugin_url );
		$suffix 				= defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		// Product Manager Script 
		if(is_forntend_product_manager_page()) {  
			$WCMp->library->load_qtip_lib();
		  $WCMp->library->load_colorpicker_lib();
		  $WCMp->library->load_datepicker_lib();
		  $WCMp_Frontend_Product_Manager->library->load_upload_lib();
		  $WCMp_Frontend_Product_Manager->library->load_accordian_lib();
		  $WCMp_Frontend_Product_Manager->library->load_select2_lib();
		  $WCMp_Frontend_Product_Manager->library->load_tinymce_lib();
			
			wp_enqueue_script('product_manager_js', $WCMp_Frontend_Product_Manager->plugin_url.'assets/frontend/js/product_manager.js', array('jquery', 'accordian_js'), $WCMp_Frontend_Product_Manager->version, true);
			
			$WCMp_fpm_messages = get_forntend_product_manager_messages();
			wp_localize_script( 'product_manager_js', 'product_manager_messages', $WCMp_fpm_messages );
		}
		
		// Coupon Manager Script 
		if(is_frontend_coupon_manager_page()) {  
			$WCMp->library->load_qtip_lib();
		  $WCMp->library->load_datepicker_lib();
		  $WCMp_Frontend_Product_Manager->library->load_accordian_lib();
		  $WCMp_Frontend_Product_Manager->library->load_select2_lib();
		  
			wp_enqueue_script('coupon_manager_js', $WCMp_Frontend_Product_Manager->plugin_url.'assets/frontend/js/coupon_manager.js', array('jquery', 'accordian_js'), $WCMp_Frontend_Product_Manager->version, true);
			
			$WCMp_fpm_messages = get_forntend_coupon_manager_messages();
			wp_localize_script( 'coupon_manager_js', 'coupon_manager_messages', $WCMp_fpm_messages );
		}
		
		wp_enqueue_script('fpm_js', $WCMp_Frontend_Product_Manager->plugin_url.'assets/frontend/js/fpm.js', array('jquery'), $WCMp_Frontend_Product_Manager->version, true);
		wp_localize_script( 'fpm_js', 'fpm_messages', array( 'shop_url' => get_permalink( wc_get_page_id( 'shop' ) ) ) );
	}

	function frontend_styles() {
		global $WCMp_Frontend_Product_Manager;
		$frontend_style_path = $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/css/';
		$frontend_style_path = str_replace( array( 'http:', 'https:' ), '', $frontend_style_path );
		$suffix 				= defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Product Manager Style
		if(is_forntend_product_manager_page()) {
			wp_enqueue_style('product_manager_css',  $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/css/product_manager.css', array(), $WCMp_Frontend_Product_Manager->version);
		}
		
		// Coupon Manager Style
		if(is_frontend_coupon_manager_page()) {
			wp_enqueue_style('coupon_manager_css',  $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/css/coupon_manager.css', array(), $WCMp_Frontend_Product_Manager->version);
		}
		
		wp_enqueue_style('product_manager_edit_css',  $WCMp_Frontend_Product_Manager->plugin_url . 'assets/frontend/css/product_manager_edit.css', array(), $WCMp_Frontend_Product_Manager->version);
	}
        
        public function is_wcmp_vendor_dashboard($is_vendor_dashboard){
            if(is_page((int) get_wcmp_vendor_settings('wcmp_pending_products', 'vendor', 'general'))){
                $is_vendor_dashboard = true;
            }
            if(is_page((int) get_wcmp_vendor_settings('wcmp_coupons', 'vendor', 'general'))){
                $is_vendor_dashboard = true;
            }
            return $is_vendor_dashboard;
        }
	
}