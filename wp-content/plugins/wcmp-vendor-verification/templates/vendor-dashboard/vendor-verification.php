<?php
/*
 * The template for displaying vendor dashboard
 * Override this template by copying it to yourtheme/wcmp-vendor-verification/vendor-dashboard/vendor-verification.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp/Templates
 * @version   1.0.0
 */
if (!defined('ABSPATH')) {
    // Exit if accessed directly
    exit;
}
global $WCMP_Vendor_Verification,$WCMp;
$user = wp_get_current_user();
$vendor = get_wcmp_vendor($user->ID);
$vendor_verification_settings = get_user_meta($vendor->id, 'wcmp_vendor_verification_settings', true);

// Vendor Verification ID
$id_verification_status = '';
if(isset($vendor_verification_settings['id_verification']['is_verified']) && !empty($vendor_verification_settings['id_verification']['is_verified']))
   $id_verification_status = $vendor_verification_settings['id_verification']['is_verified']; 

$id_disable = '';
if($id_verification_status == 'pending'){
$id_disable = 'disabled';
}

// Vendor Verification Address
$addrs_verification_status = '';
if(isset($vendor_verification_settings['address_verification']['is_verified']) && !empty($vendor_verification_settings['address_verification']['is_verified']))
   $addrs_verification_status = $vendor_verification_settings['address_verification']['is_verified']; 

$address_1 = '';
$address_2 = '';
$country = '';
$state = '';
$city = '';
$postcode = '';
if(isset($vendor_verification_settings['address_verification']['data']['address_1']) )
   $address_1 = $vendor_verification_settings['address_verification']['data']['address_1'];
if(isset($vendor_verification_settings['address_verification']['data']['address_2']) )
   $address_2 = $vendor_verification_settings['address_verification']['data']['address_2'];
if(isset($vendor_verification_settings['address_verification']['data']['country']) )
   $country = $vendor_verification_settings['address_verification']['data']['country'];
if(isset($vendor_verification_settings['address_verification']['data']['state']) )
   $state = $vendor_verification_settings['address_verification']['data']['state'];
if(isset($vendor_verification_settings['address_verification']['data']['city']) )
   $city = $vendor_verification_settings['address_verification']['data']['city'];
if(isset($vendor_verification_settings['address_verification']['data']['postcode']) )
   $postcode = $vendor_verification_settings['address_verification']['data']['postcode'];
$states = WC()->countries->get_states( $country );
$addrs_readonly = '';
$addrs_disable = '';
if($addrs_verification_status == 'pending'){
$addrs_readonly = 'readonly';
$addrs_disable = 'disabled';
}

// Vendor Verification Social
$social_verification_status = '';
if(count($vendor_verification_settings['social_verification']) > 1 ){
    if(count($WCMP_Vendor_Verification->auth->vendor_social_config['providers']) == count($vendor_verification_settings['social_verification']))
        $social_verification_status = 'verified';
    else
        $social_verification_status = 'process';
}
?>
    <div class="wcmp_form1 wcmp_vendor_verification"> 
        <?php do_action('wcmp_before_vendor_verification'); ?> 
        <div class="wcmp_headding2"><?php _e('Vendor Verification', 'wcmp-vendor-verification'); ?></div>

        <div id="verification_accordion" class="verification_accordion">
            <h3 class="vendor_verification_label <?php echo $addrs_verification_status; ?>"><?php _e('Address Verification', 'wcmp-vendor-verification'); ?><span class="status <?php echo $addrs_verification_status; ?>"><?php //echo $addrs_verification_status; ?></span></h3>
            <div class="address_verification_wrap">
                <div class="wcmp_venodr_verification_msg"><?php echo get_vendor_verification_message('address_verification');?></div>
                <form method="post" name="wcmp_vendor_verification_form" class="wcmp_vendor_verification_form">
                    <?php wp_nonce_field( 'wcmp_vendor_verification_Address', 'wcmp_vendor_verification_nonce' ); ?>

                    <input class="" required type="text" placeholder="Address line 1*" name="vendor_verification_address_1" value="<?php echo $address_1; ?>" <?php echo $addrs_readonly; ?>>
                    <input class="" type="text" placeholder="Address line 2" name="vendor_verification_address_2" value="<?php echo $address_2; ?>" <?php echo $addrs_readonly; ?>>
                    <div class="one_third_part">
                        <select required name="vendor_verification_country" id="vendor_verification_country" class="country_to_state user-profile-fields" rel="vendor_verification_country" <?php echo $addrs_readonly; ?>>
                            <option value=""><?php _e( 'Select a country&hellip;', 'woocommerce' ); ?></option>
                            <?php
                                foreach ( WC()->countries->get_shipping_countries() as $key => $value ) {
                                    echo '<option value="' . esc_attr( $key ) . '"' . selected( esc_attr( $country ), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="one_third_part">
                        <select required name="vendor_verification_state" id="vendor_verification_state" class="user-profile-fields" rel="vendor_verification_state" <?php echo $addrs_readonly; ?>>
                            <option value=""><?php esc_html_e( 'Select a state&hellip;', 'woocommerce' ); ?></option>
                            <?php
                                foreach ( $states as $ckey => $cvalue ) {
                                    echo '<option value="' . esc_attr( $ckey ) . '" ' . selected( $state, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="one_third_part">
                        <input class="" required type="text" placeholder="city*"  name="vendor_verification_city" value="<?php echo $city; ?>" <?php echo $addrs_readonly; ?>>
                    </div>
                    <input class="" required type="text" placeholder="Zipcode*" style="width:50%;" name="vendor_verification_postcode" value="<?php echo $postcode; ?>" <?php echo $addrs_readonly; ?>>
                    <div class="finish">
                        <input type="submit" class="submit_button wcmp_black_btn moregap" name="vendor_addrs_proof_sbmt" id="vendor_addrs_proof_sbmt" value="<?php _e('Submit', 'wcmp-vendor-verification') ?>" style="width: 100%;" <?php echo $addrs_disable;?> />
                    </div>
                </form>
            </div>
            <h3 class="vendor_verification_label <?php echo $id_verification_status; ?>"><?php _e('ID Verification', 'wcmp-vendor-verification'); ?><span class="status <?php echo $id_verification_status; ?>"><?php //echo $id_verification_status; ?></span></h3>
            <div class="id_verification_wrap">
                <div class="wcmp_venodr_verification_msg"><?php echo get_vendor_verification_message('id_verification');?></div>
                <form method="post" name="wcmp_vendor_id_verification_form" class="wcmp_vendor_id_verification_form">
                    <?php wp_nonce_field( 'wcmp_vendor_verification_ID', 'wcmp_vendor_verification_nonce' ); ?>
                    <div class="verify_id_type">
                    <?php $ids = $WCMP_Vendor_Verification->auth->vendor_id_verification_list();
                        $vendor_id_type = '';
                        if(isset($vendor_verification_settings['id_verification']['data']['verification_type']) && !empty($vendor_verification_settings['id_verification']['data']['verification_type']))
                        $vendor_id_type = $vendor_verification_settings['id_verification']['data']['verification_type']; 
                        foreach ($ids as $key => $label) { ?>
                        <div class="half_part">
                            <label class="verify_type">
                                <input type="radio" name="vendor_verify_type" value="<?php echo $key; ?>" <?php checked( $vendor_id_type, $key ) ?> <?php echo $id_disable;?>><?php echo $label; ?>
                            </label>
                        </div>   
                    <?php }
                    $vendor_id_file = '';
                    if(isset($vendor_verification_settings['id_verification']['data']['verification_file']) && !empty($vendor_verification_settings['id_verification']['data']['verification_file'])){
                        $file_type = wp_check_filetype($vendor_verification_settings['id_verification']['data']['verification_file']);
                        $img_type = array('image/jpeg', 'image/png', 'image/gif');
                        if(isset($file_type['type']) && in_array($file_type['type'], $img_type)){
                            $vendor_id_file = $vendor_verification_settings['id_verification']['data']['verification_file'];
                        }else{
                            $vendor_id_file = $WCMP_Vendor_Verification->plugin_url . 'assets/images/document.png';
                        }
                    }

                    $display = 'none';
                    if(!empty($vendor_id_file)) $display = 'block';
                    ?>
                    <div class="clear"></div>
                    </div>
                    <div class="verify_id_file full_part">
                        <div class="dc-wp-fields-uploader">
                            <img class="one_third_part" id="vendor_verify_id_image_display" width="300" src="<?php echo $vendor_id_file;?>" class="placeHolder" style="display: <?php echo $display;?>;" />
                            <div id="vendor_verify_id_image_remove_button" class="remove_image" style="display: <?php echo $display;?>;"><a href="#" class="remove_button " data-id="vendor_verify_id_image" data-mime="image" <?php if(!empty($id_disable)) echo $id_disable."='$id_disable'";?>>&times</a></div>
                            <input type="text" name="vendor_verify_id_image" id="vendor_verify_id_image" style="display: none;" class="user-profile-fields" readonly value="<?php echo $vendor_id_file;?>" />
                            <div class="full_part">
                            <input type="button" class="upload_button wcmp_black_btn moregap" name="vendor_image_button" id="vendor_verify_id_image_button" data-mime="image" value="<?php _e('Upload', 'wcmp-vendor-verification') ?>" style="<?php if(!empty($vendor_id_file)) echo 'display:none';?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="finish">
                        <input type="submit" class="submit_button wcmp_black_btn" name="vendor_id_proof_sbmt" id="vendor_id_proof_sbmt" value="<?php _e('Submit', 'wcmp-vendor-verification') ?>" style="width: 100%;" <?php echo $id_disable;?>/>
                    </div>
                </form>
            </div>
            <h3 class="vendor_verification_label <?php echo $social_verification_status; ?>"><?php _e('Social Verification', 'wcmp-vendor-verification'); ?><span class="status <?php echo $social_verification_status; ?>"><?php //echo $social_verification_status; ?></span></h3>
            <div class="social_verification_wrap">
                <div class="wcmp_venodr_verification_msg"></div>
            <?php 
            
            $authenticate_url = wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_vendor_verification_endpoint', 'vendor', 'general', 'vendor-verification'));

            ?>
                <div class="socialButtons">
                <?php 
                if(isset($WCMP_Vendor_Verification->auth->vendor_social_config['providers'])){
                    foreach($WCMP_Vendor_Verification->auth->vendor_social_config['providers'] as $label => $settings) {
                        $key = strtolower(sanitize_text_field($label));
                        if($settings['enabled'] && isset($vendor_verification_settings['social_verification']) && array_key_exists($key, $vendor_verification_settings['social_verification'])){
                            
                            if(is_array($vendor_verification_settings['social_verification'][$key]) && count($vendor_verification_settings['social_verification'][$key]) > 1 ){ 
                            ?>
                            <div class="box">
                                <div class="box-icon">
                                    <a href="<?php echo $vendor_verification_settings['social_verification'][$key]['profileURL']; ?>"><img src="<?php echo $vendor_verification_settings['social_verification'][$key]['photoURL']; ?>" /></a>
                                    <span class="social_ico"><img src="<?php echo $WCMP_Vendor_Verification->plugin_url . 'assets/images/'.$key.'.png'; ?>"/></span>
                                </div>
                                <div class="info">
                                    <h4 class="put_the_name"><?php echo $vendor_verification_settings['social_verification'][$key]['displayName']; ?></h4>
                                    <a class="btn" href="<?php echo $authenticate_url."?auth_out=$key";?>"><?php echo _e('Logout', 'wcmp-vendor-verification'); ?></a>
                                    <div style="clear: both;"></div>
                                    <p><?php echo wp_trim_words( $vendor_verification_settings['social_verification'][$key]['description'], 20, '...' ); ?></p>
                                </div>
                            </div>
                            <?php }else{ 
                                if((!empty($settings['keys']['id']) || !empty($settings['keys']['key'])) && !empty($settings['keys']['secret'])){ ?>
                            <a href="<?php echo $authenticate_url."?auth_in=$key"; ?>" class="social_btn_cnnct button <?php echo $label; ?>Connect rounded large">
                                <em><img src="<?php echo $WCMP_Vendor_Verification->plugin_url . 'assets/images/'.$key.'.png'; ?>" style="height: 95%;"/></em>
                                <span class="buttonText"><?php echo __('Connect to ', 'wcmp-vendor-verification').$label; ?></span>
                            </a>
                            <?php } }
                        }else{ 
                            if($settings['enabled'] && (!empty($settings['keys']['id']) || !empty($settings['keys']['key'])) && !empty($settings['keys']['secret'])){?>
                        <a href="<?php echo $authenticate_url."?auth_in=$key"; ?>" class="social_btn_cnnct button <?php echo $label; ?>Connect rounded large">
                            <em><img src="<?php echo $WCMP_Vendor_Verification->plugin_url . 'assets/images/'.$key.'.png'; ?>" style="height: 95%;"/></em>
                            <span class="buttonText"><?php echo __('Connect to ', 'wcmp-vendor-verification').$label; ?></span>
                        </a>
                    <?php } }
                    }
                }
                ?>
   
                </div>
            </div>
        </div>
        <div class="clear"></div>

        <?php do_action('wcmp_after_vendor_verification'); ?>
    </div>