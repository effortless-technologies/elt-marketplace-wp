<?php

class WCMP_Vendor_Verification_Frontend {

    public function __construct() {
        //enqueue scripts
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_scripts'));
        //enqueue styles
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_styles'));
        // vendor verification section
        //add_action('wcmp_vendor_dashboard_header', array(&$this, 'wcmp_vendor_verification_dashboard_header'),10);
        add_action('wcmp_vendor_dashboard_vendor-verification_endpoint', array(&$this, 'wcmp_vendor_dashboard_vendor_verification_endpoint'));
        add_action('before_wcmp_vendor_dashboard', array(&$this, 'save_vendor_verification_data')); 
        add_action('before_wcmp_vendor_information', array(&$this, 'before_wcmp_vendor_information'));
        add_action('after_sold_by_text_shop_page', array(&$this, 'after_sold_by_text_shop_page'));
        add_action('after_wcmp_singleproductmultivendor_vendor_name', array(&$this, 'wcmp_singleproductmultivendor_table_name'), 10, 2);
    }

//    public function wcmp_vendor_verification_dashboard_header() {
//        global $WCMp, $WCMP_Vendor_Verification;
//        if ($WCMp->endpoints->get_current_endpoint() == 'vendor-verification') {
//            echo '<ul>';
//            echo '<li>' . __('Store Settings ', 'wcmp-vendor-verification') . '</li>';
//            echo '<li class="next"> < </li>';
//            echo '<li>' . __('Verification', 'wcmp-vendor-verification') . '</li>';
//            echo '</ul>';
//        }
//    }

    public function before_wcmp_vendor_information($vendor_id){
        global $WCMP_Vendor_Verification;
        $badge = get_wcmp_vendor_verification_badge($vendor_id, array());
        if(is_user_wcmp_vendor($vendor_id) && $badge){
           echo '<div class="vendor_badge">'.get_wcmp_vendor_verification_badge($vendor_id, array()).'</div>';
        }
    }

    public function wcmp_singleproductmultivendor_table_name($product_id, $morevendor){
        global $WCMP_Vendor_Verification;
        if($product_id){
            $product_vendor = get_wcmp_product_vendors($product_id);
            if($product_vendor){
            	$badge = get_wcmp_vendor_verification_badge($product_vendor->id, array());
	            if($product_vendor && $badge){
	                echo '<a class="vendor_badge" style="display:block;">'.get_wcmp_vendor_verification_badge($product_vendor->id, array()).'</a>';
	            }
            }
        }
    }

    public function after_sold_by_text_shop_page($vendor){
        if($vendor){
            $badge = get_wcmp_vendor_verification_badge($vendor->id, array());
            if($badge){
                echo '<a class="vendor_badge" style="display:block;">'.get_wcmp_vendor_verification_badge($vendor->id, array()).'</a>';
            }
        }
    }

    public function wcmp_vendor_dashboard_vendor_verification_endpoint() {
        global $WCMp, $WCMP_Vendor_Verification;
        $current_user_id = get_current_user_id();
        if(is_user_wcmp_vendor($current_user_id)){
            $WCMP_Vendor_Verification->nocache();
            wp_enqueue_style( 'jquery-ui-style' );
            wp_enqueue_script('jquery-ui-accordion');
            wp_enqueue_script( 'wc-country-select' );
            wp_enqueue_script('verification_front_js', $WCMP_Vendor_Verification->plugin_url . 'assets/frontend/js/frontend.js', array('jquery'), $WCMP_Vendor_Verification->version, true);
            wp_enqueue_style('verification_front_css', $WCMP_Vendor_Verification->plugin_url . 'assets/frontend/css/frontend.css', array(), $WCMP_Vendor_Verification->version);
            $WCMp->library->load_frontend_upload_lib();
            $WCMP_Vendor_Verification->template->get_template('vendor-dashboard/vendor-verification.php', array());
        }
    }

    public function save_vendor_verification_data() {
        global $WCMp, $WCMP_Vendor_Verification;
        $user = wp_get_current_user();
        $vendor = get_wcmp_vendor($user->ID);
        $vendor_verification_settings = get_user_meta($vendor->id, 'wcmp_vendor_verification_settings', true);
        if(!$vendor_verification_settings || !is_array($vendor_verification_settings)){
            $vendor_verification_settings = array();
        }
        if($_SERVER['REQUEST_METHOD'] == 'POST' && $WCMp->endpoints->get_current_endpoint() == 'vendor-verification') { 
            if (isset($_POST['vendor_id_proof_sbmt'])) {
                // check verification nonce 
                if( !isset($_POST['wcmp_vendor_verification_nonce'] ) || !wp_verify_nonce( $_POST['wcmp_vendor_verification_nonce'], 'wcmp_vendor_verification_ID' ) ) {
                    return;
                }
                $vendor_verification_settings['id_verification'] = array();
                $vendor_verification_settings['id_verification']['is_verified'] = 'pending';
                $data = array();
                if(isset($_POST['vendor_verify_type']) && !empty($_POST['vendor_verify_type'])){
                    $data['verification_type'] = sanitize_text_field($_POST['vendor_verify_type']);
                    if(isset($_POST['vendor_verify_id_image']) && !empty($_POST['vendor_verify_id_image'])){
                        $data['verification_file'] = str_replace( array( 'https://', 'http://' ), '//', esc_url($_POST['vendor_verify_id_image']));
                    }
                    $vendor_verification_settings['id_verification']['data'] = $data;
                    if(update_user_meta( $vendor->id, 'wcmp_vendor_verification_settings', $vendor_verification_settings )){
                        wc_add_notice(__('ID Verification submitted successfully!', 'wcmp-vendor-verification'), 'success');
                    }
                }else{
                    wc_add_notice(__('Please select a ID Type for verification', 'wcmp-vendor-verification'), 'error');
                }
            }
            if (isset($_POST['vendor_addrs_proof_sbmt'])) {
            	// check verification nonce 
                if( !isset($_POST['wcmp_vendor_verification_nonce'] ) || !wp_verify_nonce( $_POST['wcmp_vendor_verification_nonce'], 'wcmp_vendor_verification_Address' ) ) {
                    return;
                }
                $vendor_verification_settings['address_verification'] = array();
                $vendor_verification_settings['address_verification']['is_verified'] = 'pending';
                $data = array();
                $data['address_1'] = isset($_POST['vendor_verification_address_1']) ? sanitize_textarea_field($_POST['vendor_verification_address_1']) : '';
                $data['address_2'] = isset($_POST['vendor_verification_address_2']) ? sanitize_textarea_field($_POST['vendor_verification_address_2']) : '';
                $data['country'] = isset($_POST['vendor_verification_country']) ? sanitize_text_field($_POST['vendor_verification_country']) : '';
                $data['state'] = isset($_POST['vendor_verification_state']) ? sanitize_text_field($_POST['vendor_verification_state']) : '';
                $data['city'] = isset($_POST['vendor_verification_city']) ? sanitize_text_field($_POST['vendor_verification_city']) : '';
                $data['postcode'] = isset($_POST['vendor_verification_postcode']) ? sanitize_text_field($_POST['vendor_verification_postcode']) : '';

                $vendor_verification_settings['address_verification']['data'] = $data;
                if(update_user_meta( $vendor->id, 'wcmp_vendor_verification_settings', $vendor_verification_settings ))
                	wc_add_notice(__('Address Verification submitted successfully!', 'wcmp-vendor-verification'), 'success');
            }
        }
    }

    function frontend_scripts() {
        global $WCMP_Vendor_Verification;
        $frontend_script_path = $WCMP_Vendor_Verification->plugin_url . 'assets/frontend/js/';
        $frontend_script_path = str_replace(array('http:', 'https:'), '', $frontend_script_path);
        $pluginURL = str_replace(array('http:', 'https:'), '', $WCMP_Vendor_Verification->plugin_url);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        
        // Enqueue your frontend javascript from here
    }

    function frontend_styles() {
        global $WCMP_Vendor_Verification;
        $frontend_style_path = $WCMP_Vendor_Verification->plugin_url . 'assets/frontend/css/';
        $frontend_style_path = str_replace(array('http:', 'https:'), '', $frontend_style_path);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        
        // Enqueue your frontend stylesheet from here
        wp_enqueue_style( 'dashicons' );
        wp_enqueue_style('verification_front_css', $WCMP_Vendor_Verification->plugin_url . 'assets/frontend/css/frontend.css', array(), $WCMP_Vendor_Verification->version);
    }

}
