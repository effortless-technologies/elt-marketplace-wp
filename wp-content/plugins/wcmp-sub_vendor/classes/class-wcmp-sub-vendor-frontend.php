<?php

class WCMP_Sub_Vendor_Frontend {

    public function __construct() {
        //enqueue scripts
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_scripts'));

        //enqueue styles
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_styles'));

        add_action('wcmp_sub_vendor_frontend_hook', array(&$this, 'wcmp_sub_vendor_frontend_function'), 10, 2);
        $current_user = wp_get_current_user();

        if (in_array('dc_sub_vendor', $current_user->roles)) {
            add_filter('is_wcmp_show_store_settings', array(&$this, 'is_wcmp_show_store_settings_callback'));
            add_filter('is_wcmp_show_product_tab', array(&$this, 'is_wcmp_show_product_tab_callback'));
            add_filter('is_wcmp_show_report_tab', array(&$this, 'is_wcmp_show_report_tab_callback'));
            add_filter('is_wcmp_show_order_tab', array(&$this, 'is_wcmp_show_order_tab_callback'));
            add_filter('is_wcmp_show_payment_tab', array(&$this, 'is_wcmp_show_payment_tab_callback'));
            add_filter('wcmp_show_vendor_announcements', array(&$this, 'wcmp_show_vendor_announcements_callback'));
            add_filter('wcmp_vendor_shop_permalink', array(&$this, 'wcmp_vendor_shop_permalink_callback'));


            add_filter('wcmp_vendor_dashboard_menu_dashboard_capability', array(&$this, 'is_wcmp_show_store_settings_callback'));
            add_filter('wcmp_vendor_dashboard_menu_store_settings_capability', array(&$this, 'is_wcmp_show_store_settings_callback'));
            add_filter('wcmp_vendor_dashboard_menu_manage_staff_capability', array(&$this, 'is_wcmp_show_store_settings_callback'));
            add_filter('wcmp_vendor_dashboard_menu_vendor_report_capability', array(&$this, 'is_wcmp_show_report_tab_callback'));
            add_filter('wcmp_vendor_dashboard_menu_vendor_orders_capability', array(&$this, 'is_wcmp_show_order_tab_callback'));
            add_filter('wcmp_vendor_dashboard_menu_vendor_payments_capability', array(&$this, 'is_wcmp_show_payment_tab_callback'));

            add_filter('is_user_wcmp_vendor', array(&$this, 'is_user_wcmp_vendor_callback'), 20, 2);
            add_filter('wcmp_locate_template', array(&$this, 'change_dashboard_template_callback'), 20, 4);
        }

        add_action('wcmp_vendor_dashboard_header', array(&$this, 'wcmp_vendor_dashboard_header'));

        add_action('wcmp_vendor_dashboard_add-staff_endpoint', array(&$this, 'wcmp_vendor_dashboard_add_staff_endpoint'));
        add_action('wcmp_vendor_dashboard_manage-staff_endpoint', array(&$this, 'wcmp_vendor_dashboard_manage_staff_endpoint'));
    }

    function wcmp_vendor_shop_permalink_callback($permalink) {
        $user_id = get_current_user_id();
        $permalink = wcmp_report_vendor($user_id)->permalink;
        return $permalink;
    }

    function wcmp_show_vendor_announcements_callback($show) {
        return false;
    }

    function is_wcmp_show_payment_tab_callback($show) {
        $current_user = wp_get_current_user();
        if (!user_can($current_user, 'manage_payment')) {
            $show = false;
        }
        return $show;
    }

    function is_wcmp_show_order_tab_callback($show) {
        $current_user = wp_get_current_user();
        if (!user_can($current_user, 'manage_woocommerce_orders')) {
            $show = false;
        }
        return $show;
    }

    function is_wcmp_show_report_tab_callback($show) {
        $current_user = wp_get_current_user();
        if (!user_can($current_user, 'view_woocommerce_reports')) {
            $show = false;
        }
        return $show;
    }

    function is_wcmp_show_product_tab_callback($show) {
        $current_user = wp_get_current_user();
        if (!user_can($current_user, 'manage_product')) {
            $show = false;
        }
        return $show;
    }

    function is_wcmp_show_store_settings_callback($show) {
        return false;
    }

    function frontend_scripts() {
        global $WCMP_Sub_Vendor;
        $frontend_script_path = $WCMP_Sub_Vendor->plugin_url . 'assets/frontend/js/';
        $frontend_script_path = str_replace(array('http:', 'https:'), '', $frontend_script_path);
        $pluginURL = str_replace(array('http:', 'https:'), '', $WCMP_Sub_Vendor->plugin_url);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        // Enqueue your frontend javascript from here
        wp_enqueue_script('staff_js', $pluginURL . 'assets/frontend/js/frontend.js', array('jquery'), $WCMP_Sub_Vendor->version, true);
        wp_localize_script('staff_js', 'vendor_staff_messages', array('add_staff_url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_vendor_staff_endpoint', 'vendor', 'general', 'add-staff')), 'manage_staff_url' => wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_manage_vendor_staff_endpoint', 'vendor', 'general', 'manage-staff'))));
    }

    function frontend_styles() {
        global $WCMP_Sub_Vendor;
        $frontend_style_path = $WCMP_Sub_Vendor->plugin_url . 'assets/frontend/css/';
        $frontend_style_path = str_replace(array('http:', 'https:'), '', $frontend_style_path);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        // Enqueue your frontend stylesheet from here
        wp_enqueue_style('wcmp_vendor_staff_frontend_css', $frontend_style_path . 'frontend.css');
    }

    function dc_wcmp_sub_vendor_frontend_function() {
        // Do your frontend work here
    }

    function is_user_wcmp_vendor_callback($is_user_wcmp_vendor, $user) {
        if (!$is_user_wcmp_vendor) {
            $is_user_wcmp_sub_vendor = ( is_array($user->roles) && in_array('dc_sub_vendor', $user->roles) );
            return $is_user_wcmp_sub_vendor;
        }
        return $is_user_wcmp_vendor;
    }
    
    function change_dashboard_template_callback($template, $template_name, $template_path, $default_path) {
        global $WCMP_Sub_Vendor;
        $plugin_path = $WCMP_Sub_Vendor->plugin_path . 'templates/dc-product-vendor/';
        if (file_exists($plugin_path . $template_name) && !$template) {
            $template = $plugin_path . $template_name;
        }
        return $template;
    }

    public function wcmp_vendor_dashboard_header() {
        global $WCMp, $WCMP_Sub_Vendor;
        echo '<ul>';
        if ($WCMp->endpoints->get_current_endpoint() == "add-staff") {
            echo '<li>' . __('Staff Management', "wcmp-sub_vendor") . '</li>';
            echo '<li class="next"> < </li>';
            echo '<li>' . __('Add / Edit Staff', "wcmp-sub_vendor") . '</li>';
        } else if ($WCMp->endpoints->get_current_endpoint() == "manage-staff") {
            echo '<li>' . __('Staff ', "wcmp-sub_vendor") . '</li>';
            echo '<li class="next"> < </li>';
            echo '<li>' . __('Manage Staff', "wcmp-sub_vendor") . '</li>';
        }
        echo '</ul>';
    }

    function wcmp_vendor_dashboard_add_staff_endpoint() {
        global $WCMp, $WCMP_Sub_Vendor;

        if (isset($_GET['staff_id']) && $_GET['staff_id'] > 0)
            $staff_id = $_GET['staff_id'];
        else
            $staff_id = 0;
        
        $current_user = wp_get_current_user();
        $error_msg = "";
        $valid_user_edit = false;
        if (get_user_meta($staff_id, "_report_vendor", true) == $current_user->ID) {
            $user_details = get_userdata($staff_id);
            $valid_user_edit = true;
        } else {
            $user_details = array();
            if ($staff_id > 0)
                $error_msg = __("You don't have sufficient permission to edit this staff. Please contact Administratior for further clarification.", "wcmp-sub_vendor");
        }
        ?>
        <div class="col-md-12">
            <div class="sub_vendor_success woocommerce-message vendor-msg"></div>
            <div class="sub_vendor_error woocommerce-error vendor-msg"> <?php echo $error_msg; ?> </div>
        </div>
        <div class="col-md-12">
            <form method="post" name="vendor_staff_form" class="wcmp_vendor_staff_form form-horizontal">
                <div class="panel panel-default panel-pading pannel-outer-heading">
                <div class="panel-heading">
                    <?php
                        if ($staff_id > 0)
                        echo '<h3>' . __("Edit Vendor Staff", "wcmp-sub_vendor") . '</h3>';
                        else
                        echo '<h3>' . __("Add Vendor Staff", "wcmp-sub_vendor") . '</h3>';
                    ?>
                </div>


            <?php do_action('wcmp_before_vendor_staff'); ?>
                <div class="wcmp_form1 panel-body panel-content-padding">
                    <div class="form-group">
                        <label class="control-label col-sm-3 col-md-3 "><?php _e('User Name *', 'wcmp-sub_vendor'); ?></label>
                        <div class="col-md-6 col-sm-9">
                            <input class="no_input sub_vendor_username form-control" type="text" <?php echo $valid_user_edit ? 'readonly' : ''; ?> name="sub_vendor_username" value="<?php echo isset($user_details->user_nicename) ? $user_details->user_nicename : ''; ?>" placeholder="<?php _e('Enter your Staff User Name here', 'wcmp-sub_vendor'); ?>" required="">
                            <small>(Usernames cannot be changed.)</small>
                        </div>  
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3 col-md-3 "><?php _e('Email *', 'wcmp-sub_vendor'); ?></label>
                        <div class="col-md-6 col-sm-9">
                            <input class="no_input sub_vendor_email form-control" type="email" name="sub_vendor_email" value="<?php echo isset($user_details->user_email) ? $user_details->user_email : ''; ?>"  placeholder="<?php _e('Enter your Staff Email here', 'wcmp-sub_vendor'); ?>" required="">
                        </div>  
                    </div>
    
                    <div class="form-group">
                        <label class="control-label col-sm-3 col-md-3 "><?php _e('First Name', 'wcmp-sub_vendor'); ?></label>
                        <div class="col-md-6 col-sm-9">
                            <input class="no_input sub_vendor_fname form-control" type="text" name="sub_vendor_fname" value="<?php echo isset($user_details->first_name) ? $user_details->first_name : ''; ?>"  placeholder="<?php _e('Enter your Staff First Name here', 'wcmp-sub_vendor'); ?>">
                        </div>  
                    </div> 

                    <div class="form-group">
                        <label class="control-label col-sm-3 col-md-3 "><?php _e('Last Name', 'wcmp-sub_vendor'); ?></label>
                        <div class="col-md-6 col-sm-9">
                            <input class="no_input sub_vendor_lname form-control" type="text" name="sub_vendor_lname" value="<?php echo isset($user_details->last_name) ? $user_details->last_name : ''; ?>"  placeholder="<?php _e('Enter your Staff Last Name here', 'wcmp-sub_vendor'); ?>">
                        </div>  
                    </div>
    
                    <div class="form-group">
                        <label class="control-label col-sm-3 col-md-3 "><?php _e('New Password *', 'wcmp-sub_vendor'); ?></label>
                        <div class="col-md-6 col-sm-9">
                            <input class="no_input sub_vendor_password form-control" type="password" name="sub_vendor_password" placeholder="<?php _e('Enter your Staff Password here', 'wcmp-sub_vendor'); ?>" required="">
                            <input type="hidden" name="staff_id" id="edit_staff_id" value="<?php echo $staff_id > 0 ? $staff_id : ''; ?>" />
                        </div>  
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3 col-md-3 facebook"><?php _e('Assign Capabilities', 'wcmp-sub_vendor'); ?></label>
                        <div class="col-md-6 col-sm-9">

                            <div class="checkbox-group-addon" id="sub_vendor_capabilities">
                                <?php
                                $capability_list = $WCMp->vendor_caps->get_vendor_caps();
                                if (isset($capability_list) && is_array($capability_list)) {
                                    $sub_vendor = new WCMP_Sub_Vendor_Menu();
                                    $field_list = $sub_vendor->get_capability_mapping($capability_list);
                                    foreach ($field_list as $capability_key => $capability_value) {
                                        if ($capability_value['status'] && ($valid_user_edit || $staff_id == 0)) {
                                            if (user_can($staff_id, $capability_value['cap_to_check']))
                                                echo '<div><input id="' . $capability_key . '" type="checkbox" checked name="' . $capability_key . '" />  <label for="' . $capability_key . '">' . $capability_value['display_name'] . '</label></div>';
                                            else
                                                echo '<div><input id="' . $capability_key . '" type="checkbox" name="' . $capability_key . '" />  <label for="' . $capability_key . '">' . $capability_value['display_name'] . '</label></div>';
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>  
                    </div>
    
                </div>

                </div>
            <?php do_action('wcmp_after_vendor_staff'); ?>
                <div class="action_div_space"> </div>
                <p class="error_wcmp"><?php _e('* This field is required, you must fill some information.', 'wcmp-sub_vendor'); ?></p>
                <div class="wcmp-action-container">
                    <button class="add_sub_vendor wcmp_orange_btn btn btn-default" name="add_sub_vendor"><?php $valid_user_edit ? _e('Update Staff', 'wcmp-sub_vendor') : _e('Add Staff', 'wcmp-sub_vendor'); ?></button>
                    <div class="clear"></div>
                </div>
            </form>
        </div>
        <?php
    }

    function wcmp_vendor_dashboard_manage_staff_endpoint() {
        global $WCMP_Sub_Vendor;

        $current_vendor = wp_get_current_user();

        $user_query = new WP_User_Query(array('role' => 'dc_sub_vendor', 'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_report_vendor',
                    'value' => $current_vendor->ID,
                    'compare' => '=',
                ),
            )
        ));

        if (!empty($user_query->results)) {
            echo '<div class="col-md-12"><div class="wcmp_tab ui-tabs ui-widget ui-widget-content ui-corner-all staff-detail-wrap"><div class="wcmp_table_holder"><table class="table table-bordered"><thead><tr><th>' . __('Staff Name', "wcmp-sub_vendor") . '</th><th>' . __('Email', "wcmp-sub_vendor") . '</th><th>' . __('Action', "wcmp-sub_vendor") . '</th></tr></thead><tbody>';

            foreach ($user_query->results as $user) {

                echo '<tr><td>' . $user->data->display_name . ' (' . $user->data->user_login . ')</td><td>' . $user->data->user_email . '</td><td>';
                echo '<p style="float: left;margin-right: 10px;"><a class="btn btn-default wcmp_ass_btn" href="' . add_query_arg('staff_id', $user->data->ID, wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_add_vendor_staff_endpoint', 'vendor', 'general', 'add-staff'))) . '">' . __('Edit', "wcmp-sub_vendor") . '</a></p>';
                echo '<p style="float: inline-start;"><a class="btn btn-default btn-danger wcmp_ass_btn delete-confrm-dialog-box" data-staff_id="' . $user->data->ID . '" href="#">' . __('Delete', "wcmp-sub_vendor") . '</a></p>';
                echo '</td></tr>';
            }

            echo '</tbody></table></div></div></div>';
        } else {
            ?>
            <div><h4>&nbsp;&nbsp;&nbsp;&nbsp;
            <?php
            _e("You did not added any support staff yet!!!", 'wcmp_vendor_staff_not_found_message');
            ?>
                </h4></div>
            <?php
        }
    }

}
