<?php

class WCMP_Vendor_Verification_Settings_Gneral {

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

    public function social_redirect_url($provider = ''){
        global $WCMp;
        $url ='';
        if(!empty($provider)) {
            $url = wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_verification_endpoint', 'vendor', 'general', 'vendor-verification'))."?hauth.done=$provider";
        }
        return $url;
    }

    public function wcmp_vendor_verification_settings_fields(){
        global $WCMp, $WCMP_Vendor_Verification;
        $verification_settings_fields = array();
        $verification_settings_fields['verification_badge'] = array(
            'title' => __('Verified Badge', 'wcmp-vendor-verification'),
            'fields' => array(
                'badge_img' => array('title' => __('Upload Badge', 'wcmp-vendor-verification'), 'type' => 'upload', 'id' => 'badge_img', 'label_for' => 'badge_img', 'name' => 'badge_img', 'prwidth' => 125, 'desc' => __('upload (32px height) size badge image for best view.', 'wcmp-vendor-verification'), 'hints' => __('Upload verified badge for vendor.', 'wcmp-vendor-verification'))
                )
            );
        if(isset($WCMP_Vendor_Verification->auth->vendor_social_config['providers'])){
            foreach ($WCMP_Vendor_Verification->auth->vendor_social_config['providers'] as $label => $settings) {
                $key = strtolower(sanitize_text_field($label));
                $id_label = '';
                $secret_label = '';
                $app_lnk = '';
                switch ($key) {
                    case 'google':
                        $id_label       = __('Client ID', 'wcmp-vendor-verification');
                        $secret_label   = __('Client Secret', 'wcmp-vendor-verification');
                        $app_lnk        = '<a target="_blank" href="https://console.developers.google.com/project">' . __( 'Create an App', 'wcmp-vendor-verification' ) . '</a>';
                        break;
                    case 'facebook':
                        $id_label       = __('App ID', 'wcmp-vendor-verification');
                        $secret_label   = __('App Secret', 'wcmp-vendor-verification');
                        $app_lnk        = '<a target="_blank" href="https://developers.facebook.com/apps/">' . __( 'Create an App', 'wcmp-vendor-verification' ) . '</a>';
                        break;
                    case 'twitter':
                        $id_label       = __('Consumer Key', 'wcmp-vendor-verification');
                        $secret_label   = __('Consumer Secret', 'wcmp-vendor-verification');
                        $app_lnk        = '<a target="_blank" href="https://apps.twitter.com/">' . __( 'Create an App', 'wcmp-vendor-verification' ) . '</a>';
                        break;
                    case 'linkedin':
                        $id_label       = __('Client ID', 'wcmp-vendor-verification');
                        $secret_label   = __('Client Secret', 'wcmp-vendor-verification');
                        $app_lnk        = '<a target="_blank" href="https://www.linkedin.com/developer/apps">' . __( 'Create an App', 'wcmp-vendor-verification' ) . '</a>';
                        break;
                    default:
                        $id_label       = __('Client ID', 'wcmp-vendor-verification');
                        $secret_label   = __('Client Secret', 'wcmp-vendor-verification');
                        $app_lnk        = '';
                        break;
                }

                $id_label   = apply_filters("wcmp_vendor_verification_settings_provider_{$key}_id_label", $id_label );
                $secret_label  = apply_filters("wcmp_vendor_verification_settings_provider_{$key}_secret_label", $secret_label );
                $app_lnk  = apply_filters("wcmp_vendor_verification_settings_provider_{$key}_app_create_anchor_link", $app_lnk );
            
                $enable = array('title' => __('Enable', 'wcmp-vendor-verification'), 'type' => 'checkbox', 'id' => $key.'_enable', 'label_for' => $key.'_enable', 'name' => $key.'_enable', 'value' => 'Enable', 'hints' => __('Enable this social verification for vendor.', 'wcmp-vendor-verification'));
                $redirect_url = array('title' => __('Redirect URI', 'wcmp-vendor-verification'), 'type' => 'text', 'id' => $key.'_redirect_url', 'label_for' => $key.'_redirect_url', 'name' => $key.'_redirect_url', 'value' => $this->social_redirect_url($label), 'attributes'=> array('readonly' => true), 'hints' => __('User redirect URL after successfully authenticated.', 'wcmp-vendor-verification'));
                $client_id = array('title' => $id_label, 'type' => 'text', 'id' => $key.'_client_id', 'label_for' => $key.'_client_id', 'name' => $key.'_client_id', 'hints' => __("Enter app generated $id_label here.", 'wcmp-vendor-verification'));
                $client_secret = array('title' => $secret_label, 'type' => 'text', 'id' => $key.'_client_secret', 'label_for' => $key.'_client_secret', 'name' => $key.'_client_secret', 'hints' => __("Enter app generated $secret_label here.", 'wcmp-vendor-verification'), 'desc' => '<br>** '.$app_lnk.__(' to get your above '.$id_label.' and '.$secret_label.'.','wcmp-vendor-verification'));
                $provider_settings = array();
                $provider_fields = array();
                $provider_settings['title'] = $label;
                $provider_fields[$key.'_enable'] = $enable;
                $provider_fields[$key.'_redirect_url'] = $redirect_url;
                $provider_fields[$key.'_client_id'] = $client_id;
                $provider_fields[$key.'_client_secret'] = $client_secret;
                $provider_settings['fields'] = $provider_fields;
                $verification_settings_fields[$key.'_config'] = $provider_settings;
            }
        }
 
        return $verification_settings_fields;
    }

    /**
     * Register and add settings
     */
    public function settings_page_init() {
        global $WCMp, $WCMP_Vendor_Verification;

        $settings_tab_options = array("tab" => "{$this->tab}",
            "ref" => &$this,
            "sections" => apply_filters('wcmp_vendor_verification_admin_social_config', $this->wcmp_vendor_verification_settings_fields()
            )
        );

        $WCMp->admin->settings->settings_field_init(apply_filters("settings_{$this->tab}_tab_options", $settings_tab_options));
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function wcmp_vendor_verification_settings_sanitize($input) {
        global $WCMP_Vendor_Verification;
        $new_input = array();
        $hasError = false;
        if (isset($input['badge_img'])) {
            $new_input['badge_img'] = $input['badge_img'];
        }
        if(isset($WCMP_Vendor_Verification->auth->vendor_social_config['providers'])){
            foreach ($WCMP_Vendor_Verification->auth->vendor_social_config['providers'] as $label => $settings) {
                $key = strtolower(sanitize_text_field($label));
                if (isset($input[$key.'_enable'])) {
                    $new_input[$key.'_enable'] = sanitize_text_field($input[$key.'_enable']);
                }
                if (isset($input[$key.'_redirect_url'])) {
                    $new_input[$key.'_redirect_url'] = sanitize_text_field($input[$key.'_redirect_url']);
                }
                if (isset($input[$key.'_client_id'])) {
                    $new_input[$key.'_client_id'] = sanitize_text_field($input[$key.'_client_id']);
                }
                if (isset($input[$key.'_client_secret'])) {
                    $new_input[$key.'_client_secret'] = sanitize_text_field($input[$key.'_client_secret']);
                }
            }
        }

        if (!$hasError) {
            add_settings_error(
                    "wcmp_{$this->tab}_settings_name", esc_attr("wcmp_{$this->tab}_settings_admin_updated"), __('Vendor Verification settings updated', 'wcmp-vendor-verification'), 'updated'
            );
        }
        return $new_input;
    }


}
