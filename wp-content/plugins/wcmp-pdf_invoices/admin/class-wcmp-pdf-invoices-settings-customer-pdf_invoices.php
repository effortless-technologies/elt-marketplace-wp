<?php

class WCMp_Settings_Customer_PDF_Invoices {

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $tab;

    /**
     * Start up
     */
    public function __construct($tab) {
        $this->tab = 'pdf_invoices';
        $this->options = get_option("wcmp_{$this->tab}_settings_name");
        $this->settings_page_init();
    }

    public function settings_page_init() {
        global $WCMp, $WCMp_PDF_Invoices;

        $option = '<option>select an order status</option>';
        if (function_exists('wc_get_order_statuses')) {
            $wc_get_order_statuses = wc_get_order_statuses();
            if (empty($wc_get_order_statuses)) {
                $wc_get_order_statuses = array();
            }
        }

        $settings_tab_options = array("tab" => "{$this->tab}",
            "ref" => new WCMp_Settings_PDF_Invoices($this->tab),
            "sections" => array(
                "body_settings_sectionc" => array("title" => "Customer Template Settings", // Another section
                    "fields" => array(
//                        "status_to_download_invoice" => array('title' => __('Customer can download invoice at', 'wcmp-pdf_invoices'), 'type' => 'multiinput', 'id' => 'status_to_download_invoice', 'label_for' => 'status_to_download_invoice', 'name' => 'status_to_download_invoice', 'options' => array(// Multi Input
//                                "order_status" => array('label' => __('Customer can download invoice at', 'wcmp-pdf_invoices'), 'type' => 'select', 'id' => 'order_status', 'label_for' => 'order_status', 'name' => 'order_status', 'options' => $wc_get_order_statuses, 'desc' => __('Set order status for customer to download invoice.', 'wcmp-pdf_invoices')), // select
//                            ),
//                        ),
                        "status_to_download_invoice" => array('title' => __('Customer can download invoice at', 'wcmp-pdf_invoices'), 'type' => 'select', 'id' => 'status_to_download_invoice', 'label_for' => 'status_to_download_invoice', 'name' => 'status_to_download_invoice', 'options' => $wc_get_order_statuses, 'desc' => __('Set order status for customer to download invoice.', 'wcmp-pdf_invoices')), // select
                        "is_sku_customer" => array('title' => __('SKU', 'wcmp-pdf_invoices'), 'type' => 'checkbox', 'id' => 'is_sku_customer', 'label_for' => 'is_sku_customer', 'name' => 'is_sku_customer', 'value' => 'Enable'), // Checkbox
                        "is_subtotal_customer" => array('title' => __('Subtotal', 'wcmp-pdf_invoices'), 'type' => 'checkbox', 'id' => 'is_subtotal_customer', 'label_for' => 'is_subtotal_customer', 'name' => 'is_subtotal_customer', 'value' => 'Enable'), // Checkbox
                        "is_discount_customer" => array('title' => __('Discount', 'wcmp-pdf_invoices'), 'type' => 'checkbox', 'id' => 'is_discount_customer', 'label_for' => 'is_discount_customer', 'name' => 'is_discount_customer', 'value' => 'Enable'), // Checkbox
                        "is_tax_customer" => array('title' => __('Tax', 'wcmp-pdf_invoices'), 'type' => 'checkbox', 'id' => 'is_tax_customer', 'label_for' => 'is_tax_customer', 'name' => 'is_tax_customer', 'value' => 'Enable'), // Checkbox
                        "is_shipping_customer" => array('title' => __('Shipping', 'wcmp-pdf_invoices'), 'type' => 'checkbox', 'id' => 'is_shipping_customer', 'label_for' => 'is_shipping_customer', 'name' => 'is_shipping_customer', 'value' => 'Enable'), // Checkbox
                        "is_payment_method_customer" => array('title' => __('Payment Method', 'wcmp-pdf_invoices'), 'type' => 'checkbox', 'id' => 'is_payment_method_customer', 'label_for' => 'is_payment_method_customer', 'name' => 'is_payment_method_customer', 'value' => 'Enable'), // Checkbox
                        "intro_text_customer" => array('title' => __('Introduction Text', 'wcmp-pdf_invoices'), 'type' => 'wpeditor', 'id' => 'intro_text_customer', 'label_for' => 'intro_text_customer', 'name' => 'intro_text_customer'), //Wp Eeditor
                        "term_and_conditions_customer" => array('title' => __('Term and conditions', 'wcmp-pdf_invoices'), 'type' => 'wpeditor', 'id' => 'term_and_conditions_customer', 'label_for' => 'term_and_conditions_customer', 'name' => 'term_and_conditions_customer'), //Wp Eeditor
                        "is_customer_note_customer" => array('title' => __('Show Customer Note', 'wcmp-pdf_invoices'), 'type' => 'checkbox', 'id' => 'is_customer_note_customer', 'label_for' => 'is_customer_note_customer', 'name' => 'is_customer_note_customer', 'value' => 'Enable'), 
                        "is_packing_slip_customer" => array('title' => __('Enable Packikng Slip', $WCMp_PDF_Invoices->text_domain), 'type' => 'checkbox', 'id' => 'is_packing_slip_customer', 'label_for' => 'is_packing_slip_customer', 'name' => 'is_packing_slip_customer', 'value' => 'Enable'),// Checkbox
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
        return $new_input;
    }

}

?>