<?php

class WCMp_Settings_PDF_Invoices {

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $tab;

    /**
     * Start up
     */
    public function __construct($tab) {
        $this->tab = $tab;
        $this->options = get_option("wcmp_{$this->tab}_settings_name");
        $this->settings_page_init();
    }

    /**
     * Register and add settings
     */
    public function settings_page_init() {
        global $WCMp, $WCMp_PDF_Invoices;


        $available_emails = apply_filters('woocommerce_resend_order_emails_available', array('new_order', 'cancelled_order', 'customer_processing_order', 'customer_completed_order', 'customer_invoice', 'customer_refunded_order'));
        if (!empty($available_emails)) {
            foreach ($available_emails as $available_email) {
                $available_emails_filtered[$available_email] = str_replace('_', ' ', $available_email);
            }
        }
        $option = '<option>select an order status</option>';
        if (function_exists('wc_get_order_statuses')) {
            $wc_get_order_statuses = wc_get_order_statuses();
            if (empty($wc_get_order_statuses)) {
                $wc_get_order_statuses = array();
            }
        }

        $template_array = array(
            'wcmp_pdf_invoice_first_template' => 'template1',
            'wcmp_pdf_invoice_second_template' => 'template2',
            'wcmp_pdf_invoice_third_template' => 'template3',
            'wcmp_pdf_invoice_forth_template' => 'template4',
            'wcmp_pdf_invoice_fifth_template' => 'template5'
        );
        $settings_tab_options = array("tab" => "{$this->tab}",
            "ref" => &$this,
            "sections" => array(
                "header_settings_section" => array("title" => __('General Settings', 'wcmp-pdf_invoices'), // Section one
                    "fields" => array(
//                        "attach_to_email_input" => array('title' => __('Attach to Email', 'wcmp-pdf_invoices'), 'type' => 'multiinput', 'id' => 'attach_to_email_input', 'label_for' => 'attach_to_email_input', 'name' => 'attach_to_email_input', 'options' => array(// Multi Input
//                                "attach_to_email" => array('label' => __('Attach to Email', 'wcmp-pdf_invoices'), 'type' => 'select', 'id' => 'attach_to_email', 'label_for' => 'attach_to_email', 'name' => 'attach_to_email', 'options' => $available_emails_filtered, 'desc' => __('Select Order status to attach invoice.', 'wcmp-pdf_invoices')), // select
//                            ),
//                        ),
                        "attach_to_email_input" => array('title' => __('Attach to Email', 'wcmp-pdf_invoices'), 'type' => 'select', 'id' => 'attach_to_email_input', 'label_for' => 'attach_to_email_input', 'name' => 'attach_to_email_input', 'options' => $available_emails_filtered, 'desc' => __('Select Order status to attach invoice.', 'wcmp-pdf_invoices')), // select
                        "choose_invoice_template" => array('title' => __('Select Default Invoice Template', 'wcmp-pdf_invoices'), 'type' => 'select', 'id' => 'choose_invoice_template', 'label_for' => 'choose_invoice_template', 'name' => 'choose_invoice_template', 'options' => $template_array), // select
                        "company_name" => array('title' => __('Company Name', 'wcmp-pdf_invoices'), 'type' => 'text', 'id' => 'company_name', 'label_for' => 'company_name', 'name' => 'company_name', 'hints' => __('Enter your Company Name here.', 'wcmp-pdf_invoices'), 'desc' => __('It will represent your identification.', 'wcmp-pdf_invoices')), // Text
                        "company_logo" => array('title' => __('Company Logo', 'wcmp-pdf_invoices'), 'type' => 'upload', 'id' => 'company_logo', 'label_for' => 'company_logo', 'name' => 'company_logo', 'prwidth' => 125, 'hints' => __('Your presentation.', 'wcmp-pdf_invoices'), 'desc' => __('Represent your graphical signature ( JPEG Only ).', 'wcmp-pdf_invoices')), // Upload
                        "company_address" => array('title' => __('Company Address', 'wcmp-pdf_invoices'), 'type' => 'textarea', 'id' => 'company_address', 'label_for' => 'company_address', 'name' => 'company_address', 'rows' => 5, 'placeholder' => __('About you', 'wcmp-pdf_invoices'), 'desc' => __('It will represent your significant.', 'wcmp-pdf_invoices')), // Textarea
                        "company_email" => array('title' => __('Company Email', 'wcmp-pdf_invoices'), 'type' => 'text', 'id' => 'company_email', 'label_for' => 'company_email', 'name' => 'company_email', 'hints' => __('Enter your Company Email here.', 'wcmp-pdf_invoices'), 'desc' => __('It will represent your identification.', 'wcmp-pdf_invoices')), // Text
                        "company_ph_no" => array('title' => __('Company Ph. no', 'wcmp-pdf_invoices'), 'type' => 'text', 'id' => 'company_ph_no', 'label_for' => 'company_ph_no', 'name' => 'company_ph_no', 'hints' => __('Enter your Company Ph no here.', 'wcmp-pdf_invoices'), 'desc' => __('It will represent your identification.', 'wcmp-pdf_invoices')), // Text
                        "spcl_note_from_admin" => array('title' => __('Special Notes from Admin', 'wcmp-pdf_invoices'), 'type' => 'wpeditor', 'id' => 'spcl_note_from_admin', 'label_for' => 'spcl_note_from_admin', 'name' => 'spcl_note_from_admin'), //Wp Eeditor
                    )
                ),
            ),
        );

        $WCMp->admin->settings->settings_field_init(apply_filters("settings_{$this->tab}_tab_options", $settings_tab_options));
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function wcmp_pdf_invoices_settings_sanitize($input) {
        global $WCMp_PDF_Invoices;
        $new_input = array();

        if (isset($input['choose_invoice_template'])) {
            $new_input['choose_invoice_template'] = $input['choose_invoice_template'];
        }

        if (isset($input['company_name'])) {
            $new_input['company_name'] = $input['company_name'];
        }

        if (isset($input['attach_to_email_input'])) {
            $new_input['attach_to_email_input'] = $input['attach_to_email_input'];
        }

        if (isset($input['spcl_note_from_admin'])) {
            $new_input['spcl_note_from_admin'] = $input['spcl_note_from_admin'];
        }

        if (isset($input['company_logo'])) {
            $new_input['company_logo'] = $input['company_logo'];
        }

        if (isset($input['company_address'])) {
            $new_input['company_address'] = $input['company_address'];
        }

        if (isset($input['company_email'])) {
            $new_input['company_email'] = $input['company_email'];
        }

        if (isset($input['company_ph_no'])) {
            $new_input['company_ph_no'] = $input['company_ph_no'];
        }

        if (isset($input['intro_text_customer'])) {
            $new_input['intro_text_customer'] = $input['intro_text_customer'];
        }

        if (isset($input['is_sku_customer'])) {
            $new_input['is_sku_customer'] = $input['is_sku_customer'];
        }

        if (isset($input['is_subtotal_customer'])) {
            $new_input['is_subtotal_customer'] = $input['is_subtotal_customer'];
        }

        if (isset($input['is_discount_customer'])) {
            $new_input['is_discount_customer'] = $input['is_discount_customer'];
        }

        if (isset($input['is_tax_customer'])) {
            $new_input['is_tax_customer'] = $input['is_tax_customer'];
        }

        if (isset($input['is_shipping_customer'])) {
            $new_input['is_shipping_customer'] = $input['is_shipping_customer'];
        }

        if (isset($input['is_packing_slip_customer'])) {
            $new_input['is_packing_slip_customer'] = $input['is_packing_slip_customer'];
        }

        if (isset($input['term_and_conditions_customer'])) {
            $new_input['term_and_conditions_customer'] = $input['term_and_conditions_customer'];
        }

        if (isset($input['is_customer_note_customer'])) {
            $new_input['is_customer_note_customer'] = $input['is_customer_note_customer'];
        }

        if (isset($input['status_to_download_invoice'])) {
            $new_input['status_to_download_invoice'] = $input['status_to_download_invoice'];
        }


        if (isset($input['intro_text_vendor'])) {
            $new_input['intro_text_vendor'] = $input['intro_text_vendor'];
        }

        if (isset($input['is_sku_vendor'])) {
            $new_input['is_sku_vendor'] = $input['is_sku_vendor'];
        }

        if (isset($input['is_subtotal_vendor'])) {
            $new_input['is_subtotal_vendor'] = $input['is_subtotal_vendor'];
        }

        if (isset($input['is_discount_vendor'])) {
            $new_input['is_discount_vendor'] = $input['is_discount_vendor'];
        }

        if (isset($input['is_tax_vendor'])) {
            $new_input['is_tax_vendor'] = $input['is_tax_vendor'];
        }

        if (isset($input['is_shipping_vendor'])) {
            $new_input['is_shipping_vendor'] = $input['is_shipping_vendor'];
        }

        if (isset($input['term_and_conditions_vendor'])) {
            $new_input['term_and_conditions_vendor'] = $input['term_and_conditions_vendor'];
        }

        if (isset($input['is_customer_note_vendor'])) {
            $new_input['is_customer_note_vendor'] = $input['is_customer_note_vendor'];
        }

        if (isset($input['intro_text_admin'])) {
            $new_input['intro_text_admin'] = $input['intro_text_admin'];
        }

        if (isset($input['is_sku_admin'])) {
            $new_input['is_sku_admin'] = $input['is_sku_admin'];
        }

        if (isset($input['is_subtotal_admin'])) {
            $new_input['is_subtotal_admin'] = $input['is_subtotal_admin'];
        }

        if (isset($input['is_discount_admin'])) {
            $new_input['is_discount_admin'] = $input['is_discount_admin'];
        }

        if (isset($input['is_tax_admin'])) {
            $new_input['is_tax_admin'] = $input['is_tax_admin'];
        }

        if (isset($input['is_shipping_admin'])) {
            $new_input['is_shipping_admin'] = $input['is_shipping_admin'];
        }

        if (isset($input['term_and_conditions_admin'])) {
            $new_input['term_and_conditions_admin'] = $input['term_and_conditions_admin'];
        }

        if (isset($input['is_customer_note_admin'])) {
            $new_input['is_customer_note_admin'] = $input['is_customer_note_admin'];
        }

        if (isset($input['is_payment_method_admin'])) {
            $new_input['is_payment_method_admin'] = $input['is_payment_method_admin'];
        }

        if (isset($input['is_payment_method_vendor'])) {
            $new_input['is_payment_method_vendor'] = $input['is_payment_method_vendor'];
        }

        if (isset($input['is_payment_method_customer'])) {
            $new_input['is_payment_method_customer'] = $input['is_payment_method_customer'];
        }



        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function header_settings_section_info() {
        global $WCMp_PDF_Invoices;
        //_e('Enter your default settings below', 'wcmp-pdf_invoices');
    }

    /**
     * Print the Section text
     */
    public function body_settings_section_info() {
        global $WCMp_PDF_Invoices;
        //_e('Enter your default settings below', 'wcmp-pdf_invoices');
    }

    /**
     * Print the Section text
     */
    public function footer_settings_section_info() {
        global $WCMp_PDF_Invoices;
        //_e('Enter your custom settings below', 'wcmp-pdf_invoices');
    }

    /**
     * Print the Section text
     */
    public function body_settings_sectionn_info() {
        global $WCMp_PDF_Invoices;
        //_e('Enter your default settings below', 'wcmp-pdf_invoices');
    }

    /**
     * Print the Section text
     */
    public function footer_settings_sectionn_info() {
        global $WCMp_PDF_Invoices;
        //_e('Enter your custom settings below', 'wcmp-pdf_invoices');
    }

    /**
     * Print the Section text
     */
    public function body_settings_sectionc_info() {
        global $WCMp_PDF_Invoices;
        //_e('Enter your default settings below', 'wcmp-pdf_invoices');
    }

    /**
     * Print the Section text
     */
    public function footer_settings_sectionc_info() {
        global $WCMp_PDF_Invoices;
        //_e('Enter your custom settings below', 'wcmp-pdf_invoices');
    }

}
