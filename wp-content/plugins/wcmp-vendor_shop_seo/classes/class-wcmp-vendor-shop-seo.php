<?php
class WCMp_Vendor_Shop_SEO {
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
    public $option_settings;

    public function __construct($file) {

        $this->file = $file;
        $this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
        $this->plugin_path = trailingslashit(dirname($file));
        $this->option_settings = get_wcmp_vendor_settings('','general');
        $this->token = WCMP_VENDOR_SHOP_SEO_PLUGIN_TOKEN;
        $this->text_domain = WCMP_VENDOR_SHOP_SEO_TEXT_DOMAIN;
        $this->version = WCMP_VENDOR_SHOP_SEO_PLUGIN_VERSION;

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
        $this->library = $WCMp->library;


        if (is_admin()) {
            $this->load_class('admin');
            $this->admin = new WCMp_Vendor_Shop_Seo_Admin();
        }

        if (!is_admin() || defined('DOING_AJAX')) {
            $this->load_class('frontend');
            $this->frontend = new WCMp_Vendor_Shop_Seo_Frontend();


        }

        // DC License Activation
        if (is_admin()) {
          $this->load_class('license');
          $this->license = WCMp_Vendor_Shop_Seo_LICENSE();
        }

        // DC Wp Fields
        $this->wcmp_wp_fields = $WCMp->wcmp_wp_fields;
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
    $locale = apply_filters( 'plugin_locale', get_locale(), $this->token );

    load_textdomain( $this->text_domain, WP_LANG_DIR . "/wcmp-vendor-shop-seo/wcmp-vendor-shop-seo-$locale.mo" );
    load_textdomain( $this->text_domain, $this->plugin_path . "/languages/wcmp-vendor-shop-seo-$locale.mo" );
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
  static function activate_wcmp_vendor_shop_seo() {
    global $WCMp_Vendor_Shop_SEO;

    // License Activation
    $WCMp_Vendor_Shop_SEO->load_class('license');
    WCMp_Vendor_Shop_Seo_LICENSE()->activation();

    update_option( 'wcmp_vendor_shop_seo_installed', 1 );
  }

  /**
   * UnInstall upon deactivation.
   *
   * @access public
   * @return void
   */
  function deactivate_wcmp_vendor_shop_seo() {
    global $WCMp_Vendor_Shop_SEO;
    delete_option( 'wcmp_vendor_shop_seo_installed' );

    // License Deactivation
    $WCMp_Vendor_Shop_SEO->load_class('license');
    WCMp_Vendor_Shop_Seo_LICENSE()->uninstall();
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

}