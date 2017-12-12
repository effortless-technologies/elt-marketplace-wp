<?php
class WCMP_Sub_Vendor_Settings_Gneral {
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
    $this->options = get_option( "dc_{$this->tab}_settings_name" );
    $this->settings_page_init();
  }
  
  /**
   * Register and add settings
   */
  public function settings_page_init() {
    global $WCMP_Sub_Vendor;
    
    $settings_tab_options = array("tab" => "{$this->tab}",
                                  "ref" => &$this,
                                  "sections" => array(
                                                      "default_settings_section" => array("title" =>  __('Demo Default Settings', $WCMP_Sub_Vendor->text_domain), // Section one
                                                                                         "fields" => array("id" => array('title' => '', 'type' => 'hidden', 'id' => 'id', 'name' => 'id', 'value' => 999), // Hidden
                                                                                                           "id_number" => array('title' => __('ID Number', $WCMP_Sub_Vendor->text_domain), 'type' => 'text', 'id' => 'id_number', 'label_for' => 'id_number', 'name' => 'id_number', 'hints' => __('Enter your ID Number here.', $WCMP_Sub_Vendor->text_domain), 'desc' => __('It will represent your identification.', $WCMP_Sub_Vendor->text_domain)), // Text
                                                                                                           "about" => array('title' => __('About', $WCMP_Sub_Vendor->text_domain) , 'type' => 'textarea', 'id' => 'about', 'label_for' => 'about', 'name' => 'about', 'rows' => 5, 'placeholder' => __('About you', $WCMP_Sub_Vendor->text_domain), 'desc' => __('It will represent your significant.', $WCMP_Sub_Vendor->text_domain)), // Textarea
                                                                                                           "bio" => array('title' => __('Bio', $WCMP_Sub_Vendor->text_domain), 'type' => 'wpeditor', 'id' => 'bio', 'label_for' => 'bio', 'name' => 'bio'), //Wp Eeditor
                                                                                                           "is_enable" => array('title' => __('Enable', $WCMP_Sub_Vendor->text_domain), 'type' => 'checkbox', 'id' => 'is_enable', 'label_for' => 'is_enable', 'name' => 'is_enable', 'value' => 'Enable'), // Checkbox
                                                                                                           "offday" => array('title' => __('Off Day', $WCMP_Sub_Vendor->text_domain), 'type' => 'radio', 'id' => 'offday', 'label_for' => 'offday', 'name' => 'offday', 'dfvalue' => 'wednesday', 'options' => array('sunday' => 'Sunday', 'monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thrusday' => 'Thrusday'), 'hints' => __('Choose your preferred week offday.', $WCMP_Sub_Vendor->text_domain), 'desc' => __('By default Saterday will be offday.', $WCMP_Sub_Vendor->text_domain)), // Radio
                                                                                                           "preference" => array('title' => __('Preference', $WCMP_Sub_Vendor->text_domain), 'type' => 'select', 'id' => 'preference', 'label_for' => 'preference', 'name' => 'preference', 'options' => array('one' => 'One Time', 'two' => 'Two Time', 'three' => 'Three Time'), 'hints' => __('Choose your preferred occurence count.', $WCMP_Sub_Vendor->text_domain)), // Select
                                                                                                           "logo" => array('title' => __('Logo', $WCMP_Sub_Vendor->text_domain), 'type' => 'upload', 'id' => 'logo', 'label_for' => 'logo', 'name' => 'logo', 'prwidth' => 125, 'hints' => __('Your presentation.', $WCMP_Sub_Vendor->text_domain), 'desc' => __('Represent your graphical signature.', $WCMP_Sub_Vendor->text_domain)), // Upload
                                                                                                           "dc_colorpicker" => array('title' => __('Choose Color', $WCMP_Sub_Vendor->text_domain), 'type' => 'colorpicker', 'id' => 'dc_colorpicker', 'label_for' => 'dc_colorpicker', 'name' => 'dc_colorpicker', 'default' => '000000', 'hints' => __('Choose your color here.', $WCMP_Sub_Vendor->text_domain), 'desc' => __('This lets you choose your desired color.', $WCMP_Sub_Vendor->text_domain)), // Colorpicker
                                                                                                           "dc_datepicker" => array('title' => __('Choose DOB', $WCMP_Sub_Vendor->text_domain), 'type' => 'datepicker', 'id' => 'dc_datepicker', 'label_for' => 'dc_datepicker', 'name' => 'dc_datepicker', 'hints' => __('Choose your DOB here', $WCMP_Sub_Vendor->text_domain), 'desc' => __('This lets you choose your date of birth.', $WCMP_Sub_Vendor->text_domain), 'custom_attributes' => array('date_format' => 'dd-mm-yy')), // Datepicker
                                                                                                           "slider" => array('title' => __('Slider', $WCMP_Sub_Vendor->text_domain) , 'type' => 'multiinput', 'id' => 'slider', 'label_for' => 'slider', 'name' => 'slider', 'options' => array(
                                                                                                               "title" => array('label' => __('Title', $WCMP_Sub_Vendor->text_domain) , 'type' => 'text', 'label_for' => 'title', 'name' => 'title', 'class' => 'regular-text'),
                                                                                                               "content" => array('label' => __('Content', $WCMP_Sub_Vendor->text_domain), 'type' => 'textarea', 'label_for' => 'content', 'name' => 'content', 'cols' => 40),
                                                                                                               "image" => array('label' => __('Image', $WCMP_Sub_Vendor->text_domain), 'type' => 'upload', 'label_for' => 'image', 'name' => 'image', 'prwidth' => 125),
                                                                                                               "url" => array('label' => __('URL', $WCMP_Sub_Vendor->text_domain) , 'type' => 'url', 'label_for' => 'url', 'name' => 'url', 'class' => 'regular-text'),
                                                                                                               "published" => array('label' => __('Published ON', $WCMP_Sub_Vendor->text_domain), 'type' => 'datepicker', 'id' => 'published', 'label_for' => 'published', 'name' => 'published', 'hints' => __('Published Date', $WCMP_Sub_Vendor->text_domain), 'custom_attributes' => array('date_format' => 'dd th M, yy'))
                                                                                                               )
                                                                                                             )
                                                                                                           )
                                                                                         ), 
                                                      "custom_settings_section" => array("title" => "Demo Custom Settings", // Another section
                                                                                         "fields" => array("location" => array('title' => __('Location', $WCMP_Sub_Vendor->text_domain), 'type' => 'text', 'id' => 'location', 'name' => 'location', 'hints' => __('Location', $WCMP_Sub_Vendor->text_domain)),
                                                                                                           "role" => array('title' => __('Role', $WCMP_Sub_Vendor->text_domain), 'type' => 'text', 'id' => 'role', 'name' => 'role', 'hints' => __('Role', $WCMP_Sub_Vendor->text_domain))
                                                                                                          )
                                                                                         )
                                                      )
                                  );
    
    $WCMP_Sub_Vendor->admin->settings->settings_field_init(apply_filters("settings_{$this->tab}_tab_options", $settings_tab_options));
  }

  /**
   * Sanitize each setting field as needed
   *
   * @param array $input Contains all settings fields as array keys
   */
  public function dc_wcmp_sub_vendor_general_settings_sanitize( $input ) {
    global $WCMP_Sub_Vendor;
    $new_input = array();
    
    $hasError = false;
    
    if( isset( $input['id'] ) )
      $new_input['id'] = absint( $input['id'] );
    
    if( isset( $input['id_number'] ) && absint( $input['id_number'] ) != 0 ) {
      $new_input['id_number'] = absint( $input['id_number'] );
    } else {
      add_settings_error(
        "dc_{$this->tab}_settings_name",
        esc_attr( "dc_{$this->tab}_settings_admin_error" ),
        __('Item ID should be an intiger.', $WCMP_Sub_Vendor->text_domain),
        'error'
      );
      $hasError = true;
    }
    
    if( isset( $input['about'] ) )
      $new_input['about'] = sanitize_text_field( $input['about'] );

    if( isset( $input['is_enable'] ) )
      $new_input['is_enable'] = sanitize_text_field( $input['is_enable'] );
    
    if( isset( $input['preference'] ) )
      $new_input['preference'] = sanitize_text_field( $input['preference'] );
    
    if( isset( $input['logo'] ) && !empty($input['logo']) ) {
      $new_input['logo'] = sanitize_text_field( $input['logo'] );
    } else {
      add_settings_error(
        "dc_{$this->tab}_settings_name",
        esc_attr( "dc_{$this->tab}_settings_admin_error" ),
        __('Please upload your unique logo.', $WCMP_Sub_Vendor->text_domain),
        'error'
      );
      $hasError = true;
    }
    
    if( isset( $input['dc_colorpicker'] ) )
      $new_input['dc_colorpicker'] = sanitize_text_field( $input['dc_colorpicker'] );
    
    if( isset( $input['dc_datepicker'] ) )
      $new_input['dc_datepicker'] = sanitize_text_field( $input['dc_datepicker'] );
    
    if( isset( $input['offday'] ) )
      $new_input['offday'] = sanitize_text_field( $input['offday'] );
    
    if( isset( $input['location'] ) )
      $new_input['location'] = sanitize_text_field( $input['location'] );
    
    if( isset( $input['role'] ) )
      $new_input['role'] = sanitize_text_field( $input['role'] );
    
    if( isset( $input['slider'] ) )
      $new_input['slider'] = ( $input['slider'] );
    
    if(!$hasError) {
      add_settings_error(
        "dc_{$this->tab}_settings_name",
        esc_attr( "dc_{$this->tab}_settings_admin_updated" ),
        __('General settings updated', $WCMP_Sub_Vendor->text_domain),
        'updated'
      );
    }

    return $new_input;
  }

  /** 
   * Print the Section text
   */
  public function default_settings_section_info() {
    global $WCMP_Sub_Vendor;
    _e('Enter your default settings below', $WCMP_Sub_Vendor->text_domain);
  }
  
  /** 
   * Print the Section text
   */
  public function custom_settings_section_info() {
    global $WCMP_Sub_Vendor;
    _e('Enter your custom settings below', $WCMP_Sub_Vendor->text_domain);
  }
  
}