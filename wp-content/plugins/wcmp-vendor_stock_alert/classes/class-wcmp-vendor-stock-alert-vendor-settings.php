<?php
class WCMp_Vendor_Stock_Alert_Vendor_Settings {
	
	public function __construct() {

		//enqueue scripts
		add_action('wp_enqueue_scripts', array(&$this, 'scripts'));
		
		// Vendor stock alert settings backend
		add_filter( 'wcmp_vendor_fields', array($this, 'vendor_stock_alert_settings_admin') );
		add_filter( 'dc_vendor_fields', array($this, 'vendor_stock_alert_settings_admin') ); //obsolete
		
		//save additional user fields backend
    	add_action( 'personal_options_update', array( &$this, 'save_vendor_stock_alert_settings') );
    	add_action( 'edit_user_profile_update', array( &$this, 'save_vendor_stock_alert_settings') );
    
    	// Vendor stock alert settings frontend display
    	add_action( 'wcmp_after_shop_front', array( $this, 'vendor_stock_alert_settings_frontend_display' ) );
    
    	// Vendor stock alert settings frontend save
    	add_action( 'wp', array( $this, 'vendor_stock_alert_settings_frontend_save' ) );

    	
	    // Vendor Product Stock Display
	    add_filter( 'wcmp_vendor_dashboard_nav', array($this, 'add_vendor_stock_display_in_vendor_dash'),90);
		add_action('wcmp_vendor_dashboard_header', array($this, 'wcmp_vendor_stock_page_header'),10);
		add_action('wcmp_vendor_dashboard_products-stock_endpoint', array($this, 'wcmp_vendor_stock_display_endpoint'));
    
	}

	/**
	 * Settings panel of WCMp Vendor Stock Alert User Edit page
	 *
	 * @param fields     WCMp Settings fields
	 * @return WCMp Vendor Stock Alert settings fields merged
	 */
	function vendor_stock_alert_settings_admin( $fields ) {
		
		global $WCMp_Vendor_Stock_Alert;
		$stock_alert_fields = array();
		$user_id = get_current_user_id();
		$vendor_id = '';
		if(current_user_can('administrator')){
			$vendor_id = isset($_REQUEST['user_id']) ? absint($_REQUEST['user_id']) : '';
		}else{
			$vendor_id = $user_id;
		}
		if(is_user_wcmp_vendor($vendor_id))
			$stock_alert_settings = get_user_meta( $vendor_id, 'wcmp_vendor_stock_alert_settings', true );

		$wcmp_stock_alert_settings = $WCMp_Vendor_Stock_Alert->wcmp_capabilities;
		
		if( isset($wcmp_stock_alert_settings['low_stock_enabled']) && $wcmp_stock_alert_settings['low_stock_enabled'] =='Enable'){
			$low_stock_enabled = isset($stock_alert_settings['low_stock_enabled']) ? $stock_alert_settings['low_stock_enabled'] : '';
			$low_stock_limit = isset($stock_alert_settings['low_stock_limit']) ? $stock_alert_settings['low_stock_limit'] : '';
			$stock_alert_fields['low_stock_enabled'] = array(
				'label' => __('Enable low stock alert', 'wcmp-vendor_stock_alert'),
				'type' => 'checkbox',
				'hints' => __('Enable low stock alert', 'wcmp-vendor_stock_alert'),
				'dfvalue' => $low_stock_enabled,
				'value' => 'Enable',
				'class'	=> "user-profile-fields"
			);
			$stock_alert_fields['low_stock_limit'] = array(
				'label' => __('Low stock alert limit', 'wcmp-vendor_stock_alert'),
				'type' => 'text',
				'hints' => __('Low stock alert limit', 'wcmp-vendor_stock_alert'),
				'value' => $low_stock_limit,
				'class'	=> "user-profile-fields"
			);
		}
		else{
			$low_stock_enabled = '';
			$low_stock_limit = '';
		}

		if( isset($wcmp_stock_alert_settings['out_of_stock_enabled']) && $wcmp_stock_alert_settings['out_of_stock_enabled'] =='Enable' ){
			$out_of_stock_enabled = isset($stock_alert_settings['out_of_stock_enabled']) ? $stock_alert_settings['out_of_stock_enabled'] : '';
			$out_of_stock_limit = isset($stock_alert_settings['out_of_stock_limit']) ? $stock_alert_settings['out_of_stock_limit'] : '';
			$stock_alert_fields['out_of_stock_enabled'] = array(
				'label' => __('Enable out of stock alert', 'wcmp-vendor_stock_alert'),
				'type' => 'checkbox',
				'hints' => __('Enable out of stock alert', 'wcmp-vendor_stock_alert'),
				'dfvalue' => $out_of_stock_enabled,
				'value' => 'Enable',
				'class'	=> "user-profile-fields"
			);
			$stock_alert_fields['out_of_stock_limit'] = array(
				'label' => __('Out of stock alert limit', 'wcmp-vendor_stock_alert'),
				'type' => 'text',
				'hints' => __('Low stock alert limit', 'wcmp-vendor_stock_alert'),
				'value' => $out_of_stock_limit,
				'class'	=> "user-profile-fields"
			);
		}
		else{
			$out_of_stock_enabled = '';
			$out_of_stock_limit = '';
		}
		
		return array_merge( $fields, $stock_alert_fields );
	}
	
	/**
	 * Save settings of WCMp Vendor Stock Alert User Edit page
	 *
	 * @param user_id     WCMp Vendor id
	 * @return
	 */
	function save_vendor_stock_alert_settings($user_id) {
		global $WCMp_Vendor_Stock_Alert;
		
		$stock_alert_settings = array(
			'low_stock_enabled' => isset($_POST['low_stock_enabled']) ? sanitize_text_field($_POST['low_stock_enabled']) : '',
			'out_of_stock_enabled' => isset($_POST['out_of_stock_enabled']) ? sanitize_text_field($_POST['out_of_stock_enabled']) : '',
			'low_stock_limit' => isset($_POST['low_stock_limit']) ? absint($_POST['low_stock_limit']) : '',
			'out_of_stock_limit' => isset($_POST['out_of_stock_limit']) ? absint($_POST['out_of_stock_limit']) : ''
		);
		
		if( isset($stock_alert_settings) && !empty($stock_alert_settings) ) {
			update_user_meta( $user_id, 'wcmp_vendor_stock_alert_settings', $stock_alert_settings );
		}
	}
	
	
	/**
	 * Display settings of WCMp Vendor Stock Alert Shop Settings page
	 *
	 */
	function vendor_stock_alert_settings_frontend_display() {
		global $WCMp_Vendor_Stock_Alert;
		
		$user_id = get_current_user_id();
		$stock_alert_settings = get_user_meta( $user_id, 'wcmp_vendor_stock_alert_settings', true );
		$wcmp_stock_alert_settings = $WCMp_Vendor_Stock_Alert->wcmp_capabilities;
		
		if( isset($wcmp_stock_alert_settings['low_stock_enabled']) && $wcmp_stock_alert_settings['low_stock_enabled'] =='Enable' && isset($stock_alert_settings['low_stock_enabled']) && $stock_alert_settings['low_stock_enabled'] == 'Enable'){
			$low_stock_enabled = isset($stock_alert_settings['low_stock_enabled']) ? $stock_alert_settings['low_stock_enabled'] : '';
			$low_stock_limit = isset($stock_alert_settings['low_stock_limit']) ? $stock_alert_settings['low_stock_limit'] : '';
		}
		else{
			$low_stock_enabled = '';
			$low_stock_limit = '';
		}

		if( isset($wcmp_stock_alert_settings['out_of_stock_enabled']) && $wcmp_stock_alert_settings['out_of_stock_enabled'] =='Enable' && isset($stock_alert_settings['out_of_stock_enabled']) && $stock_alert_settings['out_of_stock_enabled'] == 'Enable'){
			$out_of_stock_enabled = isset($stock_alert_settings['out_of_stock_enabled']) ? $stock_alert_settings['out_of_stock_enabled'] : '';
			$out_of_stock_limit = isset($stock_alert_settings['out_of_stock_limit']) ? $stock_alert_settings['out_of_stock_limit'] : '';
		}
		else{
			$out_of_stock_enabled = '';
			$out_of_stock_limit = '';
		}
		?>
		<?php
            if( isset($wcmp_stock_alert_settings['low_stock_enabled']) || isset($wcmp_stock_alert_settings['out_of_stock_enabled']) ) { ?>
                <div class="panel panel-default pannel-outer-heading">
                    <div class="panel-heading">
                        <h3><?php _e('Stock Management', 'wcmp-vendor_stock_alert') ?></h3>
                    </div>
                    <div class="panel-body panel-content-padding form-horizontal">
                        <div class="wcmp_media_block">
                        <?php if(isset($wcmp_stock_alert_settings['low_stock_enabled'])) { ?>
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-md-3"><?php _e('Enable low stock alert', 'wcmp-vendor_stock_alert') ?></label>
                                <div class="col-md-6 col-sm-9">
                                    <input type="checkbox" id="low_stock_enabled" <?php if($low_stock_enabled == 'Enable') echo 'checked=checked'; ?> name="low_stock_enabled" class="user-profile-fields" value="Enable">
                                </div>  
                            </div>
                        <?php } if(isset($wcmp_stock_alert_settings['out_of_stock_enabled'])) { ?>   
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-md-3"><?php _e('Enable out of stock alert', 'wcmp-vendor_stock_alert') ?></label>
                                <div class="col-md-6 col-sm-9">
                                    <input type="checkbox" id="out_of_stock_enabled" <?php if($out_of_stock_enabled == 'Enable') echo 'checked=checked'; ?> name="out_of_stock_enabled" class="user-profile-fields" value="Enable">
                                </div>  
                            </div>
                        <?php } if(isset($wcmp_stock_alert_settings['low_stock_enabled'])) { ?>
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-md-3"><?php _e('Low stock alert limit', 'wcmp-vendor_stock_alert') ?></label>
                                <div class="col-md-6 col-sm-9">
                                    <input type="text" id="low_stock_limit" name="low_stock_limit" class="user-profile-fields form-control" value="<?php echo $low_stock_limit; ?>" placeholder=""  />
                                </div>  
                            </div>
                        <?php }	if(isset($wcmp_stock_alert_settings['out_of_stock_enabled'])) { ?>
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-md-3"><?php _e('Out of stock alert limit', 'wcmp-vendor_stock_alert') ?></label>
                                <div class="col-md-6 col-sm-9">
                                    <input type="text" id="out_of_stock_limit" name="out_of_stock_limit" class="user-profile-fields form-control" value="<?php echo $out_of_stock_limit; ?>" placeholder=""  />
                                </div>  
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                </div>

		<?php } 
	}
	
	/**
	 * Save settings of WCMp Vendor Stock Alert Shop Settings page
	 *
	 */
	function vendor_stock_alert_settings_frontend_save() {
		global $WCMp_Vendor_Stock_Alert;
		
		$user_id = get_current_user_id();
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(isset($_POST['store_save'])) {
				
				$stock_alert_settings = array(
					'low_stock_enabled' => isset($_POST['low_stock_enabled']) ? sanitize_text_field($_POST['low_stock_enabled']) : '',
					'out_of_stock_enabled' => isset($_POST['out_of_stock_enabled']) ? sanitize_text_field($_POST['out_of_stock_enabled']) : '',
					'low_stock_limit' => isset($_POST['low_stock_limit']) ? absint($_POST['low_stock_limit']) : '',
					'out_of_stock_limit' => isset($_POST['out_of_stock_limit']) ? absint($_POST['out_of_stock_limit']) : ''
				);
				
				if( isset($stock_alert_settings) && !empty($stock_alert_settings) ) {
					update_user_meta( $user_id, 'wcmp_vendor_stock_alert_settings', $stock_alert_settings );
				}
			}
		}
	}

	/**
	 * WCMp Vendor Stock Alert add vendor product stock menu in vendor dashboard
	 *
	 */
	public function add_vendor_stock_display_in_vendor_dash($vendor_nav){
		global $WCMp, $WCMp_Vendor_Stock_Alert;

		if( current_user_can( 'edit_products' ) ) {
			
				// WCMp Products Menu
				$vendor_nav['vendor-products']['submenu']['products-stock'] = array(
			               'label' => __('Products Stock', 'wcmp-vendor_stock_alert'),
			               'url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_product_stock_display', 'vendor', 'general', 'products-stock')),
			               'capability' => apply_filters( 'wcmp_vendor_product_stock_display_capability', true),
			               'position' => 35,
			               'link_target' => '_self'
			           );
			
		}

		return $vendor_nav;
	}

	public function wcmp_vendor_stock_page_header(){
		global $WCMp, $WCMp_Vendor_Stock_Alert;
        switch ($WCMp->endpoints->get_current_endpoint()) {
            case 'products-stock':
                echo '<ul>';
                echo '<li>' . __('Product Manager', 'wcmp-vendor_stock_alert') . '</li>';
                echo '<li class="next"> < </li>';
                echo '<li>' . __('Products Stock', 'wcmp-vendor_stock_alert') . '</li>';
                echo '</ul>';
                break;
        }
    }

    public function wcmp_vendor_stock_display_endpoint(){
    	global $WCMp, $WCMp_Vendor_Stock_Alert;
    	$user_id = get_current_user_id();
		$vendor = get_wcmp_vendor($user_id);
                $WCMp->library->load_dataTable_lib();
//		wp_enqueue_style('wcmp_vsa_dataTable_css',  $WCMp_Vendor_Stock_Alert->plugin_url.'assets/frontend/css/jquery.dataTables.min.css', array(), $WCMp_Vendor_Stock_Alert->version);
//		wp_enqueue_style('wcmp_vsa_display_css',  $WCMp_Vendor_Stock_Alert->plugin_url.'assets/frontend/css/wcmp_vsa_display.css', array(), $WCMp_Vendor_Stock_Alert->version);
//		wp_enqueue_script('wcmp_vsa_dataTable_js', $WCMp_Vendor_Stock_Alert->plugin_url.'assets/frontend/js/jquery.dataTables.min.js', array('jquery'), $WCMp_Vendor_Stock_Alert->version, false);
		wp_enqueue_script('wcmp_vsa_display_js', $WCMp_Vendor_Stock_Alert->plugin_url.'assets/frontend/js/wcmp_vsa_display.js', array('jquery'), $WCMp_Vendor_Stock_Alert->version, false);
		wp_localize_script(
			'wcmp_vsa_display_js', 
			'wcmp_vsa_display',	
		array(
			'table_init' => apply_filters( 'wcmp_vendor_products_stock_display_list_table', array('show_no_of_products' => 6,'search_data' => false, 'order_data' => false)),	
		));
		// If vendor does not have permission to access catalog settings
	    if($vendor && !apply_filters( 'wcmp_vendor_product_stock_display_capability', true)) {
	    	_e('You do not have permission to access the Products Stock. Please contact site administrator.', 'wcmp-vendor_stock_alert');
	    	return;
	    }
        $WCMp_Vendor_Stock_Alert->template->get_template('vendor-dashboard/wcmp_vendor_products_stock.php');
    }

	/**
	 * Enqueue WCMp Vendor Stock Alert scripts
	 *
	 */
	function scripts() {
		global $WCMp, $WCMp_Vendor_Stock_Alert;
		$script_path = $WCMp_Vendor_Stock_Alert->plugin_url . 'assets/frontend/js/';
		$script_path = str_replace( array( 'http:', 'https:' ), '', $script_path );
		$pluginURL = str_replace( array( 'http:', 'https:' ), '', $WCMp_Vendor_Stock_Alert->plugin_url );
		$suffix 				= defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			
		wp_enqueue_script('wcmp_vsa_frontend_js', $WCMp_Vendor_Stock_Alert->plugin_url.'assets/frontend/js/frontend.js', array('jquery'), $WCMp_Vendor_Stock_Alert->version, true);
	}
}

?>
