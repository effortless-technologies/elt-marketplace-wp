<?php

class WCMP_Sub_Vendor_Admin {

    public $settings;

    public function __construct() {
        global $current_user, $wpdb;
        //admin script and style
        add_action('admin_enqueue_scripts', array(&$this, 'enqueue_admin_script'));

        add_action('wcmp_sub_vendor_dualcube_admin_footer', array(&$this, 'dualcube_admin_footer_for_wcmp_sub_vendor'));
        add_action('admin_init', array(&$this, 'dc_sub_vendor_role'));

        $current_user = wp_get_current_user();
        foreach ($current_user->roles as $key => $user_role) {
            if (isset($user_role)) {
                if ($user_role == 'dc_sub_vendor') {
                    add_action('save_post', array(&$this, 'change_sub_vendor_author'));
                    add_action('pre_get_posts', array(&$this, 'modify_post_list'));
                    add_action('admin_bar_menu', array(&$this, 'add_sub_vendor_toolbar_items'), 80);
                    add_action('admin_menu', array(&$this, 'remove_admin_menu_items'));
                    add_filter('wcmp_additional_fields_product_vendor_tab', array(&$this, 'reporting_vendor_name'));
                }
                if ($user_role == 'administrator') {
                    add_filter('editable_roles', array(&$this, 'hide_sub_vendor_role_from_admin'));
                    add_action('pre_user_query', array(&$this, 'remove_sub_vendors_from_user_list'));
                }
            }
        }

        $this->load_class('settings');
        $this->settings = new WCMP_Sub_Vendor_Settings();
    }

    function load_class($class_name = '') {
        global $WCMP_Sub_Vendor;
        if ('' != $class_name) {
            require_once ($WCMP_Sub_Vendor->plugin_path . '/admin/class-' . esc_attr($WCMP_Sub_Vendor->token) . '-' . esc_attr($class_name) . '.php');
        }
    }

    function dualcube_admin_footer_for_wcmp_sub_vendor() {
        global $WCMP_Sub_Vendor;
        ?>
        <div style="clear: both"></div>
        <div id="dc_admin_footer">
            <?php _e('Powered by', $WCMP_Sub_Vendor->text_domain); ?> <a href="http://dualcube.com" target="_blank"><img src="<?php echo $WCMP_Sub_Vendor->plugin_url . '/assets/images/dualcube.png'; ?>"></a><?php _e('Dualcube', $WCMP_Sub_Vendor->text_domain); ?> &copy; <?php echo date('Y'); ?>
        </div>
        <?php
    }

    /**
     * Admin Scripts
     */
    public function enqueue_admin_script() {
        global $WCMP_Sub_Vendor;
        $screen = get_current_screen();

        // Enqueue admin script and stylesheet from here
        if (in_array($screen->id, array('toplevel_page_wcmp-sub-vendor-setting-admin'))) :
            $WCMP_Sub_Vendor->library->load_qtip_lib();
            $WCMP_Sub_Vendor->library->load_upload_lib();
            $WCMP_Sub_Vendor->library->load_colorpicker_lib();
            $WCMP_Sub_Vendor->library->load_datepicker_lib();
            wp_enqueue_script('admin_js', $WCMP_Sub_Vendor->plugin_url . 'assets/admin/js/admin.js', array('jquery'), $WCMP_Sub_Vendor->version, true);

            wp_enqueue_style('admin_css', $WCMP_Sub_Vendor->plugin_url . 'assets/admin/css/admin.css', array(), $WCMP_Sub_Vendor->version);
        endif;
        wp_enqueue_script('sub_vendor_js', $WCMP_Sub_Vendor->plugin_url . 'assets/admin/js/sub_vendor.js', array('jquery'), $WCMP_Sub_Vendor->version, true);
        wp_localize_script('sub_vendor_js', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    public function dc_sub_vendor_role() {
        add_role('dc_sub_vendor', 'Vendor Staff', array('read' => true, // true allows this capability
            'edit_posts' => true,
            'edit_pages' => false,
            'edit_others_posts' => false,
            'create_posts' => false,
            'manage_categories' => false,
            'publish_posts' => false,
            'edit_themes' => false,
            'install_plugins' => false,
            'update_plugin' => false,
            'update_core' => false,
            'log_in' => false
                )
        );
    }

    public function change_sub_vendor_author() {
        $current_vendor = wp_get_current_user();
        $reporting_vendor = get_user_meta($current_vendor->ID, '_report_vendor');

        $args = array(
            'author' => $current_vendor->ID,
            'orderby' => 'post_date',
            'order' => 'ASC',
            'post_type' => 'product',
            'post_status' => 'pending'
        );


        $current_user_posts = get_posts($args);

        foreach ($current_user_posts as $key => $value) {

            $arg = array(
                'ID' => $value->ID,
                'post_author' => $reporting_vendor[0],
            );
            wp_update_post($arg);
        }
    }

    public function modify_post_list($query) {

        $current_vendor = wp_get_current_user();
        $reporting_vendor = get_user_meta($current_vendor->ID, '_report_vendor');
        if ($query->query['post_type'] == 'post' || $query->query['post_type'] == 'product') {
            if ($query->is_main_query()) {
                $query->set('author', $reporting_vendor[0]);
            }
        }
    }

    public function add_sub_vendor_toolbar_items($admin_bar) {
        $plugin_pages = get_option('wcmp_pages_settings_name');
        $user = wp_get_current_user();

        $admin_bar->add_menu(
                array(
                    'id' => 'vendor_dashboard',
                    'title' => __('Frontend  Dashboard', ''),
                    'href' => get_permalink($plugin_pages['vendor_dashboard']),
                    'meta' => array(
                        'title' => __('Frontend Dashboard', ''),
                        'target' => '_blank',
                        'class' => 'shop-settings'
                    ),
                )
        );
    }

    public function remove_admin_menu_items() {
        remove_menu_page('edit.php?post_type=dc_commission');
        remove_menu_page('tools.php');
        remove_menu_page('edit.php');
        remove_menu_page('edit-comments.php');
        remove_menu_page('upload.php');
    }

    public function hide_sub_vendor_role_from_admin($editable_roles) {
        unset($editable_roles['dc_sub_vendor']);
        return $editable_roles;
    }

    public function remove_sub_vendors_from_user_list($user_search) {
        global $wpdb;
        $user = wp_get_current_user();
        $where = 'WHERE 1=1';

        // Temporarily remove this hook otherwise we might be stuck in an infinite loop
        remove_action('pre_user_query', array(&$this, 'remove_sub_vendors_from_user_list'));

        $user_query = new WP_User_Query(array('role' => 'dc_sub_vendor'));
        $sub_vendors = $user_query->get_results();
        if(!empty($sub_vendors) && is_array($sub_vendors)){
            $sub_vendor_ids = array();
            foreach ($sub_vendors as $sub_vendor) {
                $sub_vendor_ids[] = $sub_vendor->ID;
            }

            $where .= ' AND ' . $wpdb->users . '.ID NOT IN (' . implode(',', $sub_vendor_ids) . ')';

            $user_search->query_where = str_replace(
                    'WHERE 1=1', $where, $user_search->query_where
            );
        }

        //Re-add the hook
        add_action('pre_user_query', array(&$this, 'remove_sub_vendors_from_user_list'));
    }

    public function reporting_vendor_name() {

        $current_vendor = wp_get_current_user();
        $reporting_vendor_id = get_user_meta($current_vendor->ID, '_report_vendor');
        $reporting_vendor = get_userdata($reporting_vendor_id[0]);
        $html = '<table class="form-field form-table"><tr> <td> Vendor </td> <td>' . $reporting_vendor->user_nicename . '</td></tr>';
        return $html;
    }

}
