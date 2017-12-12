<?php
/**
 * Error notices for wc-marketplace plugin not found
 */
if(!function_exists('wcmp_vendor_verification_wcmp_inactive_notice')) {
	function wcmp_vendor_verification_wcmp_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCMp Vendor Verification is inactive.%s The %sWC Marketplace%s must be active for the WCMp Vendor Verification to work. Please %sinstall & activate WC Marketplace%s', WCMP_VENDOR_VERIFICATION_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/dc-woocommerce-multi-vendor/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=wc+marketplace' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('get_vendor_verification_message')) {
  function get_vendor_verification_message($verification_type, $user_id='') {
    if(!empty($verification_type)){
    	if(empty($user_id))
    		$user_id = get_current_user_id();
    	$is_verified='';
    	if(is_user_wcmp_vendor($user_id)){
    		$verification_settings = get_user_meta($user_id, 'wcmp_vendor_verification_settings', true);
    		$is_verified = $verification_settings[$verification_type]['is_verified'];
    	}
    	$verification_name = '';
    	switch ($verification_type) {
    		case 'id_verification':
    			$verification_name = __('ID verification', 'wcmp-vendor-verification');
    			break;
    		
    		case 'address_verification':
    			$verification_name = __('Address verification', 'wcmp-vendor-verification');
    			break;
    	}
    	$msg = '';
    	$msg_html = '';
    	switch ($is_verified) {
    		case 'pending':
    			$msg = __("Your $verification_name request is in pending mode.", 'wcmp-vendor-verification');
    			$msg_html = "<div class='notice pending'><p>$msg</p></div>";
    			break;
    		case 'verified':
    			$msg = __("Your $verification_name request is verified by administrator.", 'wcmp-vendor-verification');
    			$msg_html = "<div class='notice verified'><p>$msg</p></div>";
    			break;
    		case 'process':
    			$msg = __("Your $verification_name request is in process mode.", 'wcmp-vendor-verification');
    			$msg_html = "<div class='notice process'><p>$msg</p></div>";
    			break;
    		case 'rejected':
    			$msg = __("Your $verification_name request is rejected by administrator.", 'wcmp-vendor-verification');
    			$msg_html = "<div class='notice rejected'><p>$msg</p></div>";
    			break;
    		default:
    			$msg = '';
    			$msg_html = '';
    			break;
    	}
        $msg_html = apply_filters( 'wcmp_vendor_verification_message', $msg_html, $verification_type, $is_verified );
    	return $msg_html;
    }
  }
}

if(!function_exists('get_wcmp_vendor_verification_badge')) {
  function get_wcmp_vendor_verification_badge($vendor_id = '', $args = array()) {
    global $WCMP_Vendor_Verification;
    $current_user_id='';
    if(empty($vendor_id))
        $current_user_id = get_current_user_id();
    else
        $current_user_id = $vendor_id;
    $vendor = get_wcmp_vendor($current_user_id);

    if($vendor){
        $badge = '';
        $vendor_verification_settings = get_user_meta($vendor->id, 'wcmp_vendor_verification_settings', true);
        $default = array(
            'height'        => 32,
            'width'         => 32,
            'class'         => 'vendor_badge_img ',
            'extra_attr'    => '',
            );
        $args = wp_parse_args( $args, $default );

        if(isset($vendor_verification_settings['address_verification']['is_verified']) && $vendor_verification_settings['address_verification']['is_verified'] == 'verified' && isset($vendor_verification_settings['id_verification']['is_verified']) && $vendor_verification_settings['id_verification']['is_verified'] == 'verified' ){

            $badge_img = get_vendor_verification_settings('badge_img');
            if(isset($badge_img) && !empty($badge_img)){
                $badge = sprintf(
                    "<img src='%s' class='%s' height='%d' width='%d' %s/>",
                    esc_url( $badge_img ),
                    esc_attr( $args['class'] ),
                    (int) $args['height'],
                    (int) $args['width'],
                    $args['extra_attr']
                );
            }else{
                $badge = sprintf(
                    "<span class='%s' %s/>%s</span>",
                    esc_attr( $args['class'] ),
                    $args['extra_attr'],
                    '<i class="dashicons dashicons-awards"></i>'
                );
            }

            $badge = apply_filters( 'get_wcmp_vendor_verification_verified_user_badge', $badge, $current_user_id, $args );
            return $badge;
        }
    }
  }
}

if(!function_exists('get_vendor_verification_settings')) {
  function get_vendor_verification_settings($key = '') {
    $settings = get_option('wcmp_vendor_verification_settings_name');
    if(empty($key)) return $settings;
    if(!empty($key) && isset($settings[$key])) return $settings[$key];
  }
}
?>
