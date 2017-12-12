<?php

class WCMp_Frontend_Product_Manager {

    public $plugin_url;
    public $plugin_path;
    public $version;
    public $token;
    public $text_domain;
    public $library;
    public $shortcode;
    public $admin;
    public $frontend;
    public $template;
    public $ajax;
    private $file;
    public $settings;
    public $license;
    public $wcmp_wp_fields;

    public function __construct($file) {

        $this->file = $file;
        $this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
        $this->plugin_path = trailingslashit(dirname($file));
        $this->token = WCMP_FRONTEND_PRODUCT_MANAGER_PLUGIN_TOKEN;
        $this->text_domain = WCMP_FRONTEND_PRODUCT_MANAGER_TEXT_DOMAIN;
        $this->version = WCMP_FRONTEND_PRODUCT_MANAGER_PLUGIN_VERSION;

        add_action('init', array(&$this, 'init'));
    }

    /**
     * initilize plugin on WP init
     */
    function init() {
        global $WCMp;

        // Init Text Domain
        $this->load_plugin_textdomain();

        // Init library
        $this->load_class('library');
        $this->library = new WCMp_Frontend_Product_Manager_Library();

        // Init ajax
        if (defined('DOING_AJAX')) {
            $this->load_class('ajax');
            $this->ajax = new WCMp_Frontend_Product_Manager_Ajax();
        }

        if (is_admin()) {
            $this->load_class('admin');
            $this->admin = new WCMp_Frontend_Product_Manager_Admin();
        }

        if (!is_admin() || defined('DOING_AJAX')) {
            $this->load_class('frontend');
            $this->frontend = new WCMp_Frontend_Product_Manager_Frontend();

            // init shortcode
            $this->load_class('shortcode');
            $this->shortcode = new WCMp_Frontend_Product_Manager_Shortcode();

            // init templates
            $this->load_class('template');
            $this->template = new WCMp_Frontend_Product_Manager_Template();
        }

        // DC License Activation
        if (is_admin()) {
            $this->load_class('license');
            $this->license = WCMp_Frontend_Product_Manager_LICENSE();
        }
        
        $this->restrict_wp_backend();

        // DC Wp Fields
        $this->wcmp_wp_fields = $this->library->load_fpm_fields();
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
        $locale = apply_filters('plugin_locale', get_locale(), $this->token);

        load_textdomain($this->text_domain, WP_LANG_DIR . "/wcmp-frontend-product-manager/wcmp-frontend-product-manager-$locale.mo");
        load_textdomain($this->text_domain, $this->plugin_path . "/languages/wcmp-frontend-product-manager-$locale.mo");
    }

    public function load_class($class_name = '') {
        if ('' != $class_name && '' != $this->token) {
            require_once ('class-' . esc_attr($this->token) . '-' . esc_attr($class_name) . '.php');
        } // End If Statement
    }

// End load_class()

    /**
     * Install upon activation.
     *
     * @access public
     * @return void
     */
    static function activate_wcmp_frontend_product_manager() {
        global $WCMp, $WCMp_Frontend_Product_Manager;

        // License Activation
        $WCMp_Frontend_Product_Manager->load_class('license');
        WCMp_Frontend_Product_Manager_LICENSE()->activation();

        require_once ( $WCMp->plugin_path . 'includes/class-wcmp-install.php' );
        $WCMp_Install = new WCMp_Install();
        $array_pages = get_option('wcmp_vendor_general_settings_name');

        // Product Manager Pages
        $WCMp_Install->wcmp_product_vendor_plugin_create_page(esc_sql(_x('wcmp_frontend_product_manager', 'page_slug', 'wcmp_frontend_product_manager')), 'wcmp_frontend_product_manager_page_id', __('Product Manager', 'wcmp_frontend_product_manager'), '[wcmp_frontend_product_manager]');
        $WCMp_Install->wcmp_product_vendor_plugin_create_page(esc_sql(_x('wcmp_products', 'page_slug', 'wcmp_frontend_product_manager')), 'wcmp_pending_products_page_id', __('Products', 'wcmp_frontend_product_manager'), '[wcmp_pending_products]');
        $array_pages['frontend_product_manager'] = get_option('wcmp_frontend_product_manager_page_id');
        $array_pages['wcmp_pending_products'] = get_option('wcmp_pending_products_page_id');

        // Coupon Manager Pages
        $WCMp_Install->wcmp_product_vendor_plugin_create_page(esc_sql(_x('wcmp_frontend_coupon_manager', 'page_slug', 'wcmp_frontend_product_manager')), 'wcmp_frontend_coupon_manager_page_id', __('Coupon Manager', 'wcmp_frontend_product_manager'), '[wcmp_frontend_coupon_manager]');
        $WCMp_Install->wcmp_product_vendor_plugin_create_page(esc_sql(_x('wcmp_coupons', 'page_slug', 'wcmp_frontend_product_manager')), 'wcmp_coupons_page_id', __('Coupons', 'wcmp_frontend_product_manager'), '[wcmp_coupons]');
        $array_pages['frontend_coupon_manager'] = get_option('wcmp_frontend_coupon_manager_page_id');
        $array_pages['wcmp_coupons'] = get_option('wcmp_coupons_page_id');


        update_option('wcmp_vendor_general_settings_name', $array_pages);

        update_option('wcmp_frontend_product_manager_installed', 1);
    }

    /**
     * UnInstall upon deactivation.
     *
     * @access public
     * @return void
     */
    static function deactivate_wcmp_frontend_product_manager() {
        global $WCMp_Frontend_Product_Manager;
        delete_option('wcmp_frontend_product_manager_installed');

        // License Deactivation
        $WCMp_Frontend_Product_Manager->load_class('license');
        WCMp_Frontend_Product_Manager_LICENSE()->uninstall();
    }
    
    static function restrict_wp_backend(){
        global $WCMp, $WCMp_Frontend_Product_Manager;
        if(is_user_logged_in()){
            $general_settings = get_option('wcmp_general_settings_name');
            $current_vendor_id = apply_filters( 'wcmp_current_loggedin_vendor_id', get_current_user_id() );
            if(is_user_wcmp_vendor($current_vendor_id) && is_admin() && isset($general_settings['is_backend_diabled']) && !defined('DOING_AJAX')){
                $pages = get_option("wcmp_vendor_general_settings_name");
                wp_redirect(get_permalink(wcmp_vendor_dashboard_page_id()));
                exit;
            }
        }
    }
    
    // Generate Taxonomy HTML
		function generateTaxonomyHTML( $taxonomy, $product_categories, $categories, $nbsp = '' ) {
			global $WCMp, $WCMp_Frontend_Product_Manager;
			
			foreach ( $product_categories as $cat ) {
        if(apply_filters('is_visible_wcmp_frontend_product_cat', true, $cat->term_id, $taxonomy)){
        	echo '<option value="' . esc_attr( $cat->term_id ) . '"' . selected( in_array( $cat->term_id, $categories ), true, false ) . '>' . $nbsp . esc_html( $cat->name ) . '</option>';
        }
				$product_child_categories   = get_terms( $taxonomy, 'orderby=name&hide_empty=0&parent=' . absint( $cat->term_id ) );
				if ( $product_child_categories ) {
					$this->generateTaxonomyHTML( $taxonomy, $product_child_categories, $categories, $nbsp . '&nbsp;&nbsp;' );
				}
			}
		}

    /** Cache Helpers ******************************************************** */

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

}
