<?php
class WCMp_Product_Import_Export_Bundle_Settings_Import_Export {
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
    $this->options = get_option( "wcmp_{$this->tab}_settings_name" );
    $this->settings_page_init();
  }
   
  /**
   * Register and add settings
   */
  public function settings_page_init() {
    global $WCMp_Product_Import_Export_Bundle;
    
    $settings_tab_options = array("tab" => "{$this->tab}",
                                  "ref" => &$this,
                                  "sections" => array(
                                                      "import_export_settings_section" => array("title" =>  '', // Section one
                                                                                         "fields" => array(
                                                                                                           "can_add_bulk_products" => array('title' => __('Vendor Can Add Bulk Products From Frontend', 'wcmp-product-import-export-bundle'), 
                                                                                                           'type' => 'checkbox', 
                                                                                                           'id' => 'can_add_bulk_products', 
                                                                                                           'label_for' => 'can_add_bulk_products', 
                                                                                                           'name' => 'can_add_bulk_products', 
                                                                                                           'hints' => __('Select if a  vendor can add bulk product from frontend.', 'wcmp-product-import-export-bundle'), 
                                                                                                           'desc' => __('Checking this box will turn on the global setting. If you want to remove this feature from an individual vendor, please uncheck the relevant box in the Edit Vendor tab. To visit the vendor tab, go to users>edit users.', 'wcmp-product-import-export-bundle'),
                                                                                                            'value' => 'Enable'

                                                                                                            )
                                                                                                          )
                                                                                          )
                                                      )
                                  );
    
    $WCMp_Product_Import_Export_Bundle->admin->settings->settings_field_init(apply_filters("settings_{$this->tab}_tab_options", $settings_tab_options));
  }

  /**
   * Sanitize each setting field as needed
   *
   * @param array $input Contains all settings fields as array keys
   */
  public function wcmp_import_export_settings_sanitize( $input ) {
    global $WCMp_Product_Import_Export_Bundle;
    $new_input = array();
    
    $hasError = false;


    if( isset( $input['can_add_bulk_products'] ) ){
      $new_input['can_add_bulk_products'] = sanitize_text_field( $input['can_add_bulk_products'] );

    }
     
    return $new_input;
  }

  /** 
   * Print the Section text
   */
  public function import_export_settings_section_info() {
    global $WCMp_Product_Import_Export_Bundle;
  }
  
}