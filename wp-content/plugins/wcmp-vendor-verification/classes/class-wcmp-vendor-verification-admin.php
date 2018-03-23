<?php
class WCMP_Vendor_Verification_Admin {

    public $settings;

    public function __construct() {
        //admin script and style
        add_action('admin_enqueue_scripts', array(&$this, 'enqueue_admin_script'));
        $this->load_class('settings');
        $this->settings = new WCMP_Vendor_Verification_Settings();
        add_action('before_wcmp_to_do_list', array(&$this, 'before_wcmp_to_do_list'));

        add_filter('manage_users_columns', array(&$this, 'column_register_verified_vendor'));
        add_filter('manage_users_custom_column', array(&$this, 'column_display_verified_vendor'), 10, 3);
        add_filter('pre_get_users', array($this, 'manage_verified_vendor_user_query'), 100);
        add_action( 'restrict_manage_users', array(&$this, 'restrict_abc_manage_list') );
    }

    /**
     * ADD vendor verified column on user list
     *
     * @access public
     * @return array
     */
    function column_register_verified_vendor($columns) {
        global $WCMP_Vendor_Verification;
        $user_role = filter_input(INPUT_GET, 'role');
        if( $user_role== 'dc_vendor' || is_null($user_role)){
            $columns['vendor_verified'] = __('Vendor Verified', 'wcmp-vendor-verification');
        }
        return $columns;
    }

    /**
     * Display vendor verified column on user list
     *
     * @access public
     * @return string
     */
    function column_display_verified_vendor($empty, $column_name, $user_id) {
        if ('vendor_verified' != $column_name) {
            return $empty;
        }
        $vendor = get_wcmp_vendor($user_id);
        if ($vendor) {
            $product_count = count($vendor->get_products());
            return "<span>" . get_wcmp_vendor_verification_badge($vendor->id, array()) . "</span>";
        } else {
            return "<span></span>";
        }
    }

    function restrict_abc_manage_list(){
        $user_role = filter_input(INPUT_GET, 'role');
        if( $user_role== 'dc_vendor' || is_null($user_role)){
        	echo '<div class="alignright wcmp-verified actions">';
    			submit_button( __( 'Vendor Verified' ), '', 'wcmp_user_vendor_verified', false );
    		echo '</div>';
        }
    }

    function manage_verified_vendor_user_query($wp_query){

        if ( isset( $_GET['wcmp_user_vendor_verified'] ) ) {
        	$wp_query->set('role', 'dc_vendor');
        	$wp_query->set('meta_key', 'wcmp_vendor_is_verified');
			$wp_query->set('meta_value', 'verified');
		}
    }

    function before_wcmp_to_do_list(){
        global $WCMP_Vendor_Verification,$WCMp;
        // Vendor Verification
        $args = apply_filters('wcmp_vendor_verification_to_do_list_args', array(
            'role' => 'dc_vendor',
            'meta_key' => 'wcmp_vendor_verification_settings',
        ));
        
        $get_verification_vendors = new WP_User_Query($args);
        $get_verification_vendors = $get_verification_vendors->get_results();
        $have_pending_verification = array();
        foreach ($get_verification_vendors as $get_vendor) { 
            $verification_settings = get_user_meta($get_vendor->ID, 'wcmp_vendor_verification_settings', true);
            if(isset($verification_settings['address_verification']['is_verified']) && $verification_settings['address_verification']['is_verified'] == 'pending' || isset($verification_settings['id_verification']['is_verified']) && $verification_settings['id_verification']['is_verified'] == 'pending' ){
                $have_pending_verification[] = 'pending';
            }
        }
        if (in_array('pending', $have_pending_verification) && $get_verification_vendors) {
            ?>
            <h3><?php _e('Vendor Verifications', 'wcmp-vendor-verification'); ?></h3>
            <table class="form-table" id="to_do_list">
                <tbody>
                    <tr>
                        <th><?php _e('Vendor Name', 'wcmp-vendor-verification'); ?></th>
                        <th><?php _e('Address Verification', 'wcmp-vendor-verification'); ?></th>
                        <th><?php _e('ID Verification', 'wcmp-vendor-verification'); ?></th>
                        <th><?php _e('Social Verification', 'wcmp-vendor-verification'); ?></th>
                    </tr>
            <?php
            foreach ($get_verification_vendors as $get_vendor) { 
                $verification_settings = get_user_meta($get_vendor->ID, 'wcmp_vendor_verification_settings', true);
               
                $addrs = array();
                if(isset($verification_settings['address_verification']['data']['address_1']) )
                    $addrs['address_1'] = $verification_settings['address_verification']['data']['address_1'];
                if(isset($verification_settings['address_verification']['data']['address_2']) )
                    $addrs['address_2'] = $verification_settings['address_verification']['data']['address_2'];
                if(isset($verification_settings['address_verification']['data']['country']) )
                    $addrs['country'] = $verification_settings['address_verification']['data']['country'];
                if(isset($verification_settings['address_verification']['data']['state']) )
                    $addrs['state'] = $verification_settings['address_verification']['data']['state'];
                if(isset($verification_settings['address_verification']['data']['city']) )
                    $addrs['city'] = $verification_settings['address_verification']['data']['city'];
                if(isset($verification_settings['address_verification']['data']['postcode']) )
                    $addrs['postcode'] = $verification_settings['address_verification']['data']['postcode'];
                $id_type = '';
                if(isset($verification_settings['id_verification']['data']['verification_type']) )
                    $id_type = $verification_settings['id_verification']['data']['verification_type'];


                if(isset($verification_settings['address_verification']['is_verified']) && $verification_settings['address_verification']['is_verified'] == 'pending' || isset($verification_settings['id_verification']['is_verified']) && $verification_settings['id_verification']['is_verified'] == 'pending' ){
              
                ?>
                    <tr>
                        <td class="wcmp_verification column-username" style="width:30%"><img alt="" src="<?php echo $WCMp->plugin_url . 'assets/images/wp-avatar-frau.jpg'; ?>" class="avatar avatar-32 photo" height="32" width="32"><?php echo $get_vendor->user_login; ?>
                        </td>
                        <td class="wcmp_verification">
                        <?php if($verification_settings['address_verification']['is_verified'] != 'rejected'){ ?>
                            <div class="verification_data">
                            <?php echo WC()->countries->get_formatted_address($addrs); ?>
                                <div class="data-layer_top">
                                    <div class="data-status"><?php echo $verification_settings['address_verification']['is_verified']; ?></div>
                                </div>
                            </div><br>
                            <input class="accept_verification do_verification" type="button" data-verification="address_verification" data-action="verified" data-user_id="<?php echo $get_vendor->ID; ?>" value="Accept" >
                            <input class="reject_verification do_verification" type="button" data-verification="address_verification" data-action="rejected" data-user_id="<?php echo $get_vendor->ID; ?>" value="Reject">
                            <?php } ?>
                        </td>
                        <td class="wcmp_verification">
                        <?php if(isset($verification_settings['id_verification']['is_verified']) && $verification_settings['id_verification']['is_verified'] != 'rejected'){ ?>
                            <div class="verification_data">
                            <?php echo _e('ID Type : ', 'wcmp-vendor-verification').ucwords($id_type); ?>
                            <?php 
                            if(isset($verification_settings['id_verification']['data']['verification_file']) && !empty($verification_settings['id_verification']['data']['verification_file'])){
                                $file_type = wp_check_filetype($verification_settings['id_verification']['data']['verification_file']);
                                $img_type = array('image/jpeg', 'image/png', 'image/gif');
                                if(isset($file_type['type']) && in_array($file_type['type'], $img_type)){
                                    echo '<br><img height="100px" src="'.esc_url($verification_settings['id_verification']['data']['verification_file']).'" />';
                                }else{
                                    echo '<br><img height="100px" src="'.$WCMP_Vendor_Verification->plugin_url . 'assets/images/document.png'.'" />';
                                }
                            }
                            ?>
                                <div class="data-layer_top">
                                    <div class="data-status"><?php echo $verification_settings['id_verification']['is_verified']; ?></div>
                                </div>
                            </div><br>
                            <input class="accept_verification do_verification" type="button" data-verification="id_verification" data-action="verified" data-user_id="<?php echo $get_vendor->ID; ?>" value="Accept" >
                            <input class="reject_verification do_verification" type="button" data-verification="id_verification" data-action="rejected" data-user_id="<?php echo $get_vendor->ID; ?>" value="Reject">
                            <a class="download_verification" href="<?php echo esc_url($verification_settings['id_verification']['data']['verification_file']); ?>" title="Download" download><img src="<?php echo $WCMP_Vendor_Verification->plugin_url . 'assets/images/download.png'; ?>"/></a>
                            <?php } ?>
                        </td>
                        <td class="social_verification">
                            <?php 
                            if(isset($verification_settings['social_verification'])){
                                foreach($verification_settings['social_verification'] as $provider => $profile) {
                                    echo '<img height="35px" src="'.$WCMP_Vendor_Verification->plugin_url . 'assets/images/'.$provider.'.png'.'" style="margin-right:4px;"/>';
                                }
                            }
                            ?>
                        </td>
                    </tr>
            <?php } } ?>
                </tbody>
            </table>
        <?php
        }
    }

    function load_class($class_name = '') {
        global $WCMP_Vendor_Verification;
        if ('' != $class_name) {
            require_once ($WCMP_Vendor_Verification->plugin_path . '/admin/class-' . esc_attr($WCMP_Vendor_Verification->token) . '-' . esc_attr($class_name) . '.php');
        } // End If Statement
    }

    // End load_class()

    /**
     * Admin Scripts
     */
    public function enqueue_admin_script() {
        global $WCMP_Vendor_Verification;
        $screen = get_current_screen();
        // Enqueue admin script and stylesheet from here
        if (in_array($screen->id, array('toplevel_page_wcmp-vendor-verification-setting-admin','wcmp_page_wcmp-to-do','users'))) :
            wp_enqueue_script('wcmp_vv_admin_js', $WCMP_Vendor_Verification->plugin_url . 'assets/admin/js/admin.js', array('jquery'), $WCMP_Vendor_Verification->version, true);
            wp_localize_script( 'wcmp_vv_admin_js', 'admin_ajax',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
            wp_enqueue_style('wcmp_vv_admin_css', $WCMP_Vendor_Verification->plugin_url . 'assets/admin/css/admin.css', array(), $WCMP_Vendor_Verification->version);
        endif;
    }

}
