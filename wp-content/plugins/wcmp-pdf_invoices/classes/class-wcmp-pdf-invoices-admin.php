<?php

class WCMP_Pdf_Invoices_Admin {

    public $settings;

    public function __construct() {
        //admin script and style
        add_action('admin_enqueue_scripts', array(&$this, 'enqueue_admin_script'));

        add_action('wcmp_pdf_invoices_dualcube_admin_footer', array(&$this, 'dualcube_admin_footer_for_wcmp_pdf_invoices'));
        add_action('add_meta_boxes', array(&$this, 'wcmp_admin_pdf_meta_box'));

        add_filter('wcmp_tabs', array(&$this, 'wcmp_pdf_invoices_tab'));
        add_action('settings_page_pdf_invoices_tab_init', array(&$this, 'pdf_invoices_tab_init'), 10, 1);
        add_action('wcmp_pdf_invoices_settings_before_submit', array(&$this, 'pdf_invoices_settings_subtabs'), 10);

        add_filter('settings_vendor_general_tab_options', array(&$this, "pdf_invoices_settings_option"));
        add_filter('settings_vendor_general_tab_new_input', array(&$this, "pdf_invoices_settings_sanitize"), 10, 2);
    }

    /**
     * Settings panel of WCMp PDf Invoice Pages Settings
     *
     * @param settings_tab_options     WCMp Settings fields product tab fields
     * @return WCMp PDf Invoicesettings fields are merged
     */
    function pdf_invoices_settings_option($settings_tab_options) {
        global $WCMp_PDF_Invoices;
        $pages = get_pages();
        $woocommerce_pages = array(wc_get_page_id('shop'), wc_get_page_id('cart'), wc_get_page_id('checkout'), wc_get_page_id('myaccount'));
        foreach ($pages as $page) {
            if (!in_array($page->ID, $woocommerce_pages)) {
                $pages_array[$page->ID] = $page->post_title;
            }
        }
        
        if(get_option('WCMP_Pdf_Invoices_Vendor_Edit_Page_Id') && !get_wcmp_vendor_settings('wcmp_vendor_edit_invoice', 'vendor', 'general') && function_exists('update_wcmp_vendor_settings')){
            update_wcmp_vendor_settings('wcmp_vendor_edit_invoice', get_option('WCMP_Pdf_Invoices_Vendor_Edit_Page_Id'), 'vendor', 'general');
        }
        $settings_tab_options["sections"]["wcmp_pages_section"]["fields"]["wcmp_vendor_edit_invoice"] = array('title' => __('Vendor Edit Invoice Template', 'wcmp-pdf_invoices'
), 'type' => 'select', 'options' => $pages_array, 'hints' => __('Choose your preferred page for Vendor Frontend Invoice Settings.', 'wcmp-pdf_invoices'
));
        return $settings_tab_options;
    }

    /**
     * Save settings of WCMp PDf Invoice Settings
     *
     * @param new_input	input     WCMp Settings inputs
     * @return WCMp PDf Invoice inputs are merged
     */
    function pdf_invoices_settings_sanitize($new_input, $input) {
        global $WCMp, $WCMp_PDF_Invoices;
        if (isset($input['wcmp_vendor_edit_invoice'])) {
            $new_input['wcmp_vendor_edit_invoice'] = sanitize_text_field($input['wcmp_vendor_edit_invoice']);
        }
        return $new_input;
    }

    function wcmp_pdf_invoices_tab($tabs) {
        global $WCMp_PDF_Invoices;
        $tabs['pdf_invoices'] = __('PDF Invoices', 'wcmp-pdf_invoices'
);
        return $tabs;
    }

    function pdf_invoices_tab_init($tab) {
        global $WCMp, $WCMp_PDF_Invoices;
        $this->load_class("settings-{$tab}");
        new WCMp_Settings_PDF_Invoices($tab);
    }

    function pdf_invoices_settings_subtabs() {
        global $WCMp, $WCMp_PDF_Invoices;
        $tab = 'pdf_invoices';
        $this->load_class("settings-admin-{$tab}");
        new WCMp_Settings_Admin_PDF_Invoices($tab);
        $this->load_class("settings-vendor-{$tab}");
        new WCMp_Settings_Vendor_PDF_Invoices($tab);
        $this->load_class("settings-customer-{$tab}");
        new WCMp_Settings_Customer_PDF_Invoices($tab);
    }

    function load_class($class_name = '') {
        global $WCMp_PDF_Invoices;
        if ('' != $class_name) {
            require_once ($WCMp_PDF_Invoices->plugin_path . '/admin/class-' . esc_attr($WCMp_PDF_Invoices->token) . '-' . esc_attr($class_name) . '.php');
        } // End If Statement
    }

// End load_class()

    /**
     * Admin Scripts
     */
    public function enqueue_admin_script() {
        global $WCMp_PDF_Invoices;
        $screen = get_current_screen();
        // Enqueue admin script and stylesheet from here
        if (in_array($screen->id, array('shop_order'))) :
            wp_enqueue_script('wcmp_admin_order_js', $WCMp_PDF_Invoices->plugin_url . 'assets/admin/js/admin.js', array('jquery'), $WCMp_PDF_Invoices->version, true);
        endif;
    }

    public function wcmp_admin_pdf_meta_box() {
        global $WCMp_PDF_Invoices;
        add_meta_box(
                'wcmp-admin-pdf-invoice', __('PDF Invoice', 'wcmp-pdf_invoices'
), array($this, 'wcmp_pdf_invoice'), 'shop_order', 'side', 'high'
        );
    }

    public function wcmp_pdf_invoice($post) {
        global $WCMp_PDF_Invoices;
        $current_user_id = get_current_user_id();

        $order_pdf = get_post_meta($post->ID, '_pdf_invoice_per_order', true);
        $html = '<table><tbody>';
        $html .= '<tr><td><label><strong>Per Order - </strong></label></td>';
        if (!$order_pdf)
            $html .= '<td><input type="button"  value="Create" class="wcmp_create_per_order_pdf_invoice button"  data-user_id="' . $current_user_id . '" data-id ="' . $post->ID . '"  /></td>';
        else
            $html .= '<td><a href="' . $order_pdf . '" download class="button" >View</a></td><td><input type="button" style="cursor:pointer" value="Cancel" class="wcmp_cancel_per_order_pdf_invoice button"  data-id ="' . $post->ID . '"  /></td>';
        $html .= '</tr>';

        $vendors_in_order = wcmp_get_vendor_items_from_order($post->ID);
        $select_options = '';
        if (!empty($vendors_in_order)) {
            foreach ($vendors_in_order as $vendor) {
                $vendor_order_pdf = get_post_meta($post->ID, '_pdf_invoice_' . sanitize_title($vendor->user_data->data->display_name), true);
                if ($vendor_order_pdf) {
                    $select_options .= '<tr><td>' . $vendor->user_data->data->display_name . ' :</td><td><a href="' . $vendor_order_pdf . '" download class="button" >View</a></td><td><input type="button" style="cursor:pointer" value="Cancel" class="wcmp_cancel_per_vendor_pdf_invoice button "  data-vendor="' . sanitize_title($vendor->user_data->data->display_name) . '" data-id ="' . $post->ID . '"  /></td>';
                } else {
                    $select_options .= '<tr><td>' . $vendor->user_data->data->display_name . ' :</td><td> <input type="button" class="wcmp_create_per_vendor_pdf_invoice button" value="Create" data-user_id="' . $vendor->term_id . '" data-id ="' . $post->ID . '" /></td></tr>';
                }
            }
        }
        if (!empty($select_options)) {
            $html .= '<tr><td><label><strong>Per Vendor - </strong></label></td></tr>';
            $html .= $select_options;
        }
        $html .= '</table></tbody>';
        echo $html;
    }

}
