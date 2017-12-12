<?php

class WCMp_Settings_Vendor_PDF_Invoices {

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
        $settings_tab_options = array("tab" => "{$this->tab}",
            "ref" => new WCMp_Settings_PDF_Invoices($this->tab),
            "sections" => array(
                "body_settings_sectionn" => array("title" => "Vendor Template Settings", // Another section
                    "fields" => array(
                        "is_sku_vendor" => array('title' => __('SKU', 'wcmp-pdf_invoices'), 'type' => 'checkbox', 'id' => 'is_sku_vendor', 'label_for' => 'is_sku_vendor', 'name' => 'is_sku_vendor', 'value' => 'Enable'), // Checkbox
                        "is_subtotal_vendor" => array('title' => __('Subtotal', 'wcmp-pdf_invoices'), 'type' => 'checkbox', 'id' => 'is_subtotal_vendor', 'label_for' => 'is_subtotal_vendor', 'name' => 'is_subtotal_vendor', 'value' => 'Enable'), // Checkbox
                        "is_discount_vendor" => array('title' => __('Discount', 'wcmp-pdf_invoices'), 'type' => 'checkbox', 'id' => 'is_discount_vendor', 'label_for' => 'is_discount_vendor', 'name' => 'is_discount_vendor', 'value' => 'Enable'), // Checkbox
                        "is_tax_vendor" => array('title' => __('Tax', 'wcmp-pdf_invoices'), 'type' => 'checkbox', 'id' => 'is_tax_vendor', 'label_for' => 'is_tax_vendor', 'name' => 'is_tax_vendor', 'value' => 'Enable'), // Checkbox
                        "is_shipping_vendor" => array('title' => __('Shipping', 'wcmp-pdf_invoices'), 'type' => 'checkbox', 'id' => 'is_shipping_vendor', 'label_for' => 'is_shipping_vendor', 'name' => 'is_shipping_vendor', 'value' => 'Enable'), // Checkbox
                        "is_payment_method_vendor" => array('title' => __('Payment Method', 'wcmp-pdf_invoices'), 'type' => 'checkbox', 'id' => 'is_payment_method_vendor', 'label_for' => 'is_payment_method_vendor', 'name' => 'is_payment_method_vendor', 'value' => 'Enable'), // Checkbox
                        "intro_text_vendor" => array('title' => __('Introduction Text', 'wcmp-pdf_invoices'), 'type' => 'wpeditor', 'id' => 'intro_text_vendor', 'label_for' => 'intro_text_vendor', 'name' => 'intro_text_vendor'), //Wp Eeditor
                        "term_and_conditions_vendor" => array('title' => __('Term and conditions', 'wcmp-pdf_invoices'), 'type' => 'wpeditor', 'id' => 'term_and_conditions_vendor', 'label_for' => 'term_and_conditions_vendor', 'name' => 'term_and_conditions_vendor'), //Wp Eeditor
                        "is_customer_note_vendor" => array('title' => __('Show Customer Note', 'wcmp-pdf_invoices'), 'type' => 'checkbox', 'id' => 'is_customer_note_vendor', 'label_for' => 'is_customer_note_vendor', 'name' => 'is_customer_note_vendor', 'value' => 'Enable'), // Checkbox
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