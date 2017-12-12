<?php
class WCMp_PDF_Invoices {

    public $plugin_url;
    public $plugin_path;
    public $version;
    public $token;
    public $text_domain;
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
        $this->token = WCMp_PDF_INVOICES_PLUGIN_TOKEN;
        $this->text_domain = WCMp_PDF_INVOICES_TEXT_DOMAIN;
        $this->version = WCMp_PDF_INVOICES_PLUGIN_VERSION;

        add_action('init', array(&$this, 'init'));
        add_filter( 'woocommerce_email_attachments', array( &$this, 'attach_invoice_to_order_email' ), 99, 3 );
        
    }
    function attach_invoice_to_order_email($attachments, $status, $order){
        global $WCMp_PDF_Invoices;
        if (is_object($order) && is_callable(array($order, 'get_id'))) {
            $order_id = $order->get_id();
        } else {
            return $attachments;
        }
        $wcmp_pdf_invoices_settings_name = get_option('wcmp_pdf_invoices_settings_name',true);
        $attach_to_email = array();
        if(!empty($wcmp_pdf_invoices_settings_name)){
            $attach_to_email_inputs = $wcmp_pdf_invoices_settings_name['attach_to_email_input'];
            $attach_to_email[] = $attach_to_email_inputs;
        }
        $file_path = get_post_meta($order_id,'_pdf_invoice_per_order_path',true);
        if(in_array($status, $attach_to_email)){
            $attachments[] = $file_path;
        }
        return $attachments;
        
    }
    /**
     * initilize plugin on WP init
     */
    function init() {
        global $WCMp;
        
        // Init ajax
        if (defined('DOING_AJAX')) {
            $this->load_class('ajax');
            $this->ajax = new WCMp_PDF_Invoices_Ajax();
        }

        if (is_admin()) {
            $this->load_class('admin');
            $this->admin = new WCMp_PDF_Invoices_Admin();
        }

        if (!is_admin() || defined('DOING_AJAX')) {
            $this->load_class('frontend');
            $this->frontend = new WCMp_PDF_Invoices_Frontend();

            // init shortcode
            $this->load_class('shortcode');
            $this->shortcode = new WCMp_PDF_Invoices_Shortcode();

            // init templates
            $this->load_class('template');
            $this->template = new WCMp_PDF_Invoices_Template();
        }

        // DC License Activation
        if (is_admin()) {
            $this->load_class('license');
            $this->license = WCMp_PDF_Invoices_LICENSE();
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
        $locale = is_admin() && function_exists('get_user_locale') ? get_user_locale() : get_locale();
       $locale = apply_filters('plugin_locale', $locale, 'wcmp-pdf_invoices');
       load_textdomain('wcmp-pdf_invoices', WP_LANG_DIR . '/wcmp-pdf_invoices/wcmp-pdf_invoices-' . $locale . '.mo');
       load_plugin_textdomain('wcmp-pdf_invoices', false, plugin_basename(dirname(dirname(__FILE__))) . '/languages');
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
    static function activate_wcmp_pdf_invoices() {
        global $WCMp_PDF_Invoices;

        // License Activation
        $WCMp_PDF_Invoices->load_class('license');
        WCMp_PDF_Invoices_LICENSE()->activation();

        $WCMp_PDF_Invoices->load_class('install');
        new WCMP_Pdf_Invoices_Install();

        update_option('wcmp_pdf_invoices_installed', 1);
    }

    /**
     * UnInstall upon deactivation.
     *
     * @access public
     * @return void
     */
    static function deactivate_wcmp_pdf_invoices() {
        global $WCMp_PDF_Invoices;
        delete_option('wcmp_pdf_invoices_installed');

        // License Deactivation
        $WCMp_PDF_Invoices->load_class('license');
        WCMp_PDF_Invoices_LICENSE()->uninstall();
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
