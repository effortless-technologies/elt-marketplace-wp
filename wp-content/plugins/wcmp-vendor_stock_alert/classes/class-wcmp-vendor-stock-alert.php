<?php
class WCMp_Vendor_Stock_Alert {

	public $plugin_url;

	public $plugin_path;

	public $version;

	public $token;
	
	public $text_domain;
	
	public $admin;

	public $ajax;

	private $file;

	public $vendor_settings;
	
	public $license;
	
	public $template;
	
	public $wcmp_wp_fields;

	public $wcmp_capabilities;

	public function __construct($file) {

		$this->file = $file;
		$this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
		$this->plugin_path = trailingslashit(dirname($file));
		$this->token = WCMP_VENDOR_STOCK_ALERT_PLUGIN_TOKEN;
		$this->text_domain = WCMP_VENDOR_STOCK_ALERT_TEXT_DOMAIN;
		$this->version = WCMP_VENDOR_STOCK_ALERT_PLUGIN_VERSION;
		// get wcmp product capabilities settings
		$this->wcmp_capabilities = get_wcmp_global_settings();
		
		add_action('wcmp_init', array(&$this, 'init'));
		
		// Woocommerce Email structure
		add_filter('woocommerce_email_classes', array(&$this, 'wcmp_vendor_alert_email' ));
	}
	
	/**
	 * initilize plugin on WP init
	 */
	function init() {
		global $WCMp;
		
		// Init Text Domain
		$this->load_plugin_textdomain();
		
		// Vendor profile settings
		$this->load_class('vendor-settings');
		$this->vendor_settings = new WCMp_Vendor_Stock_Alert_Vendor_Settings();

		// Init ajax
		if(defined('DOING_AJAX')) {
                    $this->load_class('ajax');
                    $this->ajax = new  WCMp_Vendor_Stock_Alert_Ajax();
                  }

		if (is_admin()) {
			$this->load_class('admin');
			$this->admin = new WCMp_Vendor_Stock_Alert_Admin();
		}

		// DC License Activation
		if (is_admin()) {
		  $this->load_class('license');
		  $this->license = WCMp_Vendor_Stock_Alert_LICENSE();
		}

		// init templates
      	$this->load_class('template');
      	$this->template = new WCMp_Vendor_Stock_Alert_Template();

		// DC Wp Fields
		$this->wcmp_wp_fields = $WCMp->wcmp_wp_fields;

		// migrate data
		$this->do_migrate_vendor_stock_alert_settings();

		// add default settings to vendor
		$this->wcmp_vendor_stock_alert_settings();

		// vendor products stock display endpoint
		add_filter( 'wcmp_endpoints_query_vars', array($this, 'add_wcmp_vendor_products_stock_endpoint'),10);
	}
	
	/**
   * Load Localisation files.
   *
   * Note: the first-loaded translation file overrides any following ones if the same translation is present
   *
   * @access public
   * @return void
   */
  	public function load_plugin_textdomain() {
  		$locale = is_admin() && function_exists('get_user_locale') ? get_user_locale() : get_locale();
        $locale = apply_filters('plugin_locale', $locale, 'wcmp-vendor_stock_alert');
        load_textdomain('wcmp-vendor_stock_alert', WP_LANG_DIR . '/wcmp-vendor_stock_alert/wcmp-vendor_stock_alert-' . $locale . '.mo');
        load_plugin_textdomain('wcmp-vendor_stock_alert', false, plugin_basename(dirname(dirname(__FILE__))) . '/languages');
  	}

	public function load_class($class_name = '') {
		if ('' != $class_name && '' != $this->token) {
			require_once ('class-' . esc_attr($this->token) . '-' . esc_attr($class_name) . '.php');
		} // End If Statement
	}// End load_class()
	
	/**
   * Install upon activation.
   *
   * @access public
   * @return void
   */
  static function activate_wcmp_vendor_stock_alert() {
    global $WCMp_Vendor_Stock_Alert;
    
    // License Activation
    $WCMp_Vendor_Stock_Alert->load_class('license');
    WCMp_Vendor_Stock_Alert_LICENSE()->activation();
    
    update_option( 'wcmp_vendor_stock_alert_installed', 1 );
  }
  
  /**
   * UnInstall upon deactivation.
   *
   * @access public
   * @return void
   */
  function deactivate_wcmp_vendor_stock_alert() {
    global $WCMp_Vendor_Stock_Alert;
    delete_option( 'wcmp_vendor_stock_alert_installed' );
    
    // License Deactivation
    $WCMp_Vendor_Stock_Alert->load_class('license');
    WCMp_Vendor_Stock_Alert_LICENSE()->uninstall();
  }
	
	/** Cache Helpers *********************************************************/

	/**
	 * Sets a constant preventing some caching plugins from caching a page. Used on dynamic pages
	 *
	 * @access public
	 * @return void
	 */
	function nocache() {
		if (!defined('DONOTCACHEPAGE'))
			define("DONOTCACHEPAGE", "true");
		// WP Super Cache constant
	}
	
	/**
	 * Add WCMp Vendor Alert Email
	 *
	 * @param emails     default email classes
	 * @return modified email classes
	 */ 
	function wcmp_vendor_alert_email( $emails ) {
		require_once( 'emails/class-wcmp-vendor-low-stock-alert-email.php' );
		$emails['WCMp_Email_Low_Stock_Alert'] = new WCMp_Email_Low_Stock_Alert();
		
		require_once( 'emails/class-wcmp-vendor-out-of-stock-alert-email.php' );
		$emails['WCMp_Email_Out_of_Stock_Alert'] = new WCMp_Email_Out_of_Stock_Alert();
		
		return $emails;
	}

	/**
	 * Add WCMp Vendor Alert Email
	 *
	 * @param emails     default email classes
	 * @return modified email classes
	 */ 
	function add_wcmp_vendor_products_stock_endpoint($endpoint){
		global $WCMp, $WCMp_Vendor_Stock_Alert;
		$endpoint['products-stock'] = array(
                'label' => __('Products Stock', 'wcmp-vendor_stock_alert'),
                'endpoint' => get_wcmp_vendor_settings('wcmp_vendor_product_stock_display', 'vendor', 'general', 'products-stock')
            );
		flush_rewrite_rules();
		if(!get_option('wcmp_vendor_stock_endpoints')){
			update_option('wcmp_vendor_stock_endpoints', 1);
		}
	
		return $endpoint;
	}

	/**
	 * Migrate vendor stock alert settings if wcmp version less than 2.6+
	 */ 
	function do_migrate_vendor_stock_alert_settings() {
		global $WCMp;

		if (get_wcmp_vendor_settings('low_stock_enabled', 'product') && get_wcmp_vendor_settings('low_stock_enabled', 'product') == 'Enable') {
            update_wcmp_vendor_settings('low_stock_enabled', 'Enable', 'capabilities','product');
        }
        delete_wcmp_vendor_settings('low_stock_enabled', 'product');
        if (get_wcmp_vendor_settings('out_of_stock_enabled', 'product') && get_wcmp_vendor_settings('out_of_stock_enabled', 'product') == 'Enable') {
            update_wcmp_vendor_settings('out_of_stock_enabled', 'Enable', 'capabilities','product');
        }
        delete_wcmp_vendor_settings('out_of_stock_enabled', 'product');
        if (get_wcmp_vendor_settings('low_stock_limit', 'product') && get_wcmp_vendor_settings('low_stock_limit', 'product') != '') {
            update_wcmp_vendor_settings('low_stock_limit', get_wcmp_vendor_settings('low_stock_limit', 'product'), 'capabilities','product');
        }
        delete_wcmp_vendor_settings('low_stock_limit', 'product');
        if (get_wcmp_vendor_settings('out_of_stock_limit', 'product') && get_wcmp_vendor_settings('out_of_stock_limit', 'product') != '') {
            update_wcmp_vendor_settings('out_of_stock_limit', get_wcmp_vendor_settings('out_of_stock_limit', 'product'), 'capabilities','product');
        }
        delete_wcmp_vendor_settings('out_of_stock_limit', 'product');
	}

	function wcmp_vendor_stock_alert_settings(){
		global $WCMp, $WCMp_Vendor_Stock_Alert;
		$wcmp_stock_alert_settings = $WCMp_Vendor_Stock_Alert->wcmp_capabilities;
		if(!empty(get_wcmp_vendors())){
			foreach (get_wcmp_vendors() as $key => $vendor) {
				$stock_alert_settings = get_user_meta( $vendor->id, 'wcmp_vendor_stock_alert_settings', true );
				$wcmp_vendor_stock_alert_settings = array();
				if( isset($wcmp_stock_alert_settings['low_stock_enabled']) && $wcmp_stock_alert_settings['low_stock_enabled'] =='Enable'){
					$wcmp_vendor_stock_alert_settings['low_stock_enabled'] = isset($stock_alert_settings['low_stock_enabled']) ? $stock_alert_settings['low_stock_enabled'] : $wcmp_stock_alert_settings['low_stock_enabled'];
					$wcmp_vendor_stock_alert_settings['low_stock_limit'] = isset($stock_alert_settings['low_stock_limit']) ? $stock_alert_settings['low_stock_limit'] : $wcmp_stock_alert_settings['low_stock_limit'];
				}

				if( isset($wcmp_stock_alert_settings['out_of_stock_enabled']) && $wcmp_stock_alert_settings['out_of_stock_enabled'] =='Enable'){
					$wcmp_vendor_stock_alert_settings['out_of_stock_enabled'] = isset($stock_alert_settings['out_of_stock_enabled']) ? $stock_alert_settings['out_of_stock_enabled'] : $wcmp_stock_alert_settings['out_of_stock_enabled'];
					$wcmp_vendor_stock_alert_settings['out_of_stock_limit'] = isset($stock_alert_settings['out_of_stock_limit']) ? $stock_alert_settings['out_of_stock_limit'] : $wcmp_stock_alert_settings['out_of_stock_limit'];
				}
				
				update_user_meta( $vendor->id, 'wcmp_vendor_stock_alert_settings', $wcmp_vendor_stock_alert_settings );
			}
		}
	}

}
?>