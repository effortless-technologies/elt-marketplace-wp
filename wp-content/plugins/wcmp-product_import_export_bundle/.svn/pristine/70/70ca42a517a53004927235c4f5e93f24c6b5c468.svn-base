<?php

class WCMp_Product_Import_Export_Bundle_Settings {

    private $tabs = array();
    private $options;

    /**
     * Start up
     */
    public function __construct() {
        // Admin menu
        add_action('admin_init', array($this, 'settings_page_init'));
        add_action('show_user_profile', array(&$this, 'additional_user_fields_for_import_export'));
        add_action('edit_user_profile', array(&$this, 'additional_user_fields_for_import_export'));
        add_action('edit_user_profile_update', array(&$this, 'import_export_save_vendor_data'));
        add_action('personal_options_update', array(&$this, 'import_export_save_vendor_data'));
        add_filter("wcmp_tabs", array($this, 'add_cap_tab'), 10, 1);
        add_action('settings_page_import_export_tab_init', array(&$this, 'import_export_tab_init'), 10, 1);
        add_filter('settings_vendor_general_tab_new_input', array(&$this, 'import_export_tab_new_input'), 99, 2);
        add_filter("settings_vendor_general_tab_options", array($this, 'wcmp_add_pages_tab'), 10, 1);
    }

    public function import_export_tab_new_input($new_input, $input) {
        if (isset($input['vendor_upload_product']))             {
            $new_input['vendor_upload_product'] = sanitize_text_field($input['vendor_upload_product']);
        }
        return $new_input;
    }

    public function wcmp_add_pages_tab($args) {
        global $WCMp, $WCMp_Product_Import_Export_Bundle;
        $pages = get_pages();
        $woocommerce_pages = array(wc_get_page_id('shop'), wc_get_page_id('cart'), wc_get_page_id('checkout'), wc_get_page_id('myaccount'));
        foreach ($pages as $page) {
            if (!in_array($page->ID, $woocommerce_pages)) {
                $pages_array[$page->ID] = $page->post_title;
            }
        }
        if(get_wcmp_vendor_settings('vendor_upload_product', 'pages') && function_exists('update_wcmp_vendor_settings') && function_exists('delete_wcmp_vendor_settings')){
            update_wcmp_vendor_settings('vendor_upload_product', get_wcmp_vendor_settings('vendor_upload_product', 'pages'), 'vendor', 'general');
            delete_wcmp_vendor_settings('vendor_upload_product', 'pages');
        }
        if(!get_wcmp_vendor_settings('vendor_upload_product', 'vendor', 'general')){
            update_wcmp_vendor_settings('vendor_upload_product', get_option('wcmp_product_vendor_upload_product_page_id'), 'vendor', 'general');
        }
        $args['sections']['wcmp_pages_section']['fields']['vendor_upload_product'] = array('title' => __('Upload Products', 'wcmp-product-import-export-bundle'), 'type' => 'select', 'id' => 'vendor_upload_product', 'label_for' => 'vendor_upload_product', 'name' => 'vendor_upload_product', 'options' => $pages_array, 'hints' => __('Choose your preferred page for Uploading Bulk Products', 'wcmp-product-import-export-bundle'));

        return $args;
    }

    public function add_cap_tab($tabs) {

        global $WCMp_Product_Import_Export_Bundle;
        $tabs['import_export'] = __('Import/Export', 'wcmp-product-import-export-bundle');
        return $tabs;
    }

    /**
     * Add options page
     */
    public function import_export_save_vendor_data($user_id) {
        if (!current_user_can('manage_options', get_current_user_id()))
            return false;
        if (isset($_POST['_add_product_from_frontend'])) {
            update_user_meta($user_id, '_add_product_from_frontend', $_POST['_add_product_from_frontend']);
        } else {
            update_user_meta($user_id, '_add_product_from_frontend', 'Disable');
        }
    }

    public function additional_user_fields_for_import_export($user) {
        global $WCMp_Product_Import_Export_Bundle;
        $roles = implode(' ', $user->roles);
        if (stripos($roles, "vendor") !== false) {
            $checked = get_user_meta($user->ID, '_add_product_from_frontend', true);
            if ($checked != 'Disable') {
                $checked = 'Enable';
            }
            ?>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th>
                            <label for="View import export" > <?php _e('Upload Frontend products ?', 'wcmp-product-import-export-bundle'); ?></label>
                        </th>
                        <td>
                            <?php if (current_user_can('manage_options', get_current_user_id())) { ?>
                                <input type="checkbox" name="_add_product_from_frontend" value="Enable" <?php if ($checked != 'Disable') echo 'checked'; ?> />
                                <?php
                            }
                            else {
                                if ($checked == 'Enable' || $checked == false) {
                                    echo '<strong style="color:#0B6121;">' . __('Enabled', 'wcmp-product-import-export-bundle') . '</strong>';
                                } elseif ($checked == 'Disable') {
                                    echo '<strong style="color:#B40404;">' . __('Disabled', 'wcmp-product-import-export-bundle') . '</strong>';
                                }
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php
        }
    }

    /**
     * Register and add settings
     */
    public function settings_page_init() {
        do_action('befor_settings_page_init');

        // Register each tab settings
        foreach ($this->tabs as $tab => $name) :
            do_action("settings_page_{$tab}_tab_init", $tab);
        endforeach;

        do_action('after_settings_page_init');
    }

    /**
     * Register and add settings fields
     */
    public function settings_field_init($tab_options) {
        global $WCMp_Product_Import_Export_Bundle;



        if (!empty($tab_options) && isset($tab_options['tab']) && isset($tab_options['ref']) && isset($tab_options['sections'])) {
            // Register tab options
            register_setting(
                    "wcmp_{$tab_options['tab']}_settings_group", // Option group
                    "wcmp_{$tab_options['tab']}_settings_name", // Option name
                    array($tab_options['ref'], "wcmp_{$tab_options['tab']}_settings_sanitize") // Sanitize
            );

            foreach ($tab_options['sections'] as $sectionID => $section) {
                // Register section
                add_settings_section(
                        $sectionID, // ID
                        $section['title'], // Title
                        array($tab_options['ref'], "{$sectionID}_info"), // Callback
                        "wcmp-{$tab_options['tab']}-settings-admin" // Page
                );

                // Register fields
                if (isset($section['fields'])) {
                    foreach ($section['fields'] as $fieldID => $field) {
                        if (isset($field['type'])) {
                            $field = $WCMp_Product_Import_Export_Bundle->wcmp_wp_fields->check_field_id_name($fieldID, $field);
                            $field['tab'] = $tab_options['tab'];
                            $callbak = $this->get_field_callback_type($field['type']);
                            if (!empty($callbak)) {
                                add_settings_field(
                                        $fieldID, $field['title'], array($this, $callbak), "wcmp-{$tab_options['tab']}-settings-admin", $sectionID, $field
                                );
                            }
                        }
                    }
                }
            }
        }
    }

    function import_export_tab_init($tab) {
        global $WCMp_Product_Import_Export_Bundle;
        $WCMp_Product_Import_Export_Bundle->admin->load_class("settings-{$tab}", $WCMp_Product_Import_Export_Bundle->plugin_path, $WCMp_Product_Import_Export_Bundle->token);
        new WCMp_Product_Import_Export_Bundle_Settings_Import_Export($tab);
    }

    function get_field_callback_type($fieldType) {
        $callBack = '';
        switch ($fieldType) {
            case 'input':
            case 'text':
            case 'email':
            case 'number':
            case 'file':
            case 'url':
                $callBack = 'text_field_callback';
                break;
            case 'checkbox':
                $callBack = 'checkbox_field_callback';
                break;

            default:
                $callBack = '';
                break;
        }

        return $callBack;
    }

    /**
     * Get the checkbox field display
     */
    public function checkbox_field_callback($field) {

        global $WCMp_Product_Import_Export_Bundle;

        if ($field['id'] == 'can_add_bulk_products' || $field['id'] == 'can_add_bulk_product_from_backend ')
            $this->options = get_option("wcmp_import_export_settings_name");


        $field['value'] = isset($field['value']) ? esc_attr($field['value']) : '';
        $field['value'] = isset($this->options[$field['name']]) ? esc_attr($this->options[$field['name']]) : $field['value'];
        $field['dfvalue'] = isset($this->options[$field['name']]) ? esc_attr($this->options[$field['name']]) : '';
        $field['name'] = "wcmp_{$field['tab']}_settings_name[{$field['name']}]";

        $WCMp_Product_Import_Export_Bundle->wcmp_wp_fields->checkbox_input($field);
    }

    /**
     * Get the text field display
     */
    public function text_field_callback($field) {
        global $WCMp_Product_Import_Export_Bundle;
        $field['value'] = isset($field['value']) ? esc_attr($field['value']) : '';
        $field['value'] = isset($this->options[$field['name']]) ? esc_attr($this->options[$field['name']]) : $field['value'];
        $field['name'] = "wcmp_{$field['tab']}_settings_name[{$field['name']}]";
        $WCMp_Product_Import_Export_Bundle->wcmp_wp_fields->text_input($field);
    }

}
