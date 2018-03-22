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
                    add_action('admin_title', array(&$this, 'block_staff_to_view_others_product'));
                    add_filter('views_edit-product',  array(&$this, 'staff_update_product_counter'));
                    add_filter('redirect_post_location',  array(&$this, 'redirect_not_editable_coupons_to_listing_page'), 10, 2 );
                }
                if ($user_role == 'administrator') {
                    add_filter('editable_roles', array(&$this, 'hide_sub_vendor_role_from_admin'));
                    add_filter('users_list_table_query_args', array(&$this, 'remove_sub_vendors_from_user_list'));
                    add_action('admin_init', array(&$this, 'block_admin_to_view_staff'));
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
            <?php _e('Powered by', "wcmp-sub_vendor"); ?> <a href="http://dualcube.com" target="_blank"><img src="<?php echo $WCMP_Sub_Vendor->plugin_url . '/assets/images/dualcube.png'; ?>"></a><?php _e('Dualcube', "wcmp-sub_vendor"); ?> &copy; <?php echo date('Y'); ?>
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
                )
        );
    }
    
    public function change_sub_vendor_author( $post_id ) {
    	
    	$current_vendor = wp_get_current_user();
    	$reporting_vendor = get_user_meta($current_vendor->ID, '_report_vendor', true);
    	
    	$post = get_post($post_id);
        
    	remove_action('save_post', array(&$this, 'change_sub_vendor_author'));

    	if($post->post_author != $reporting_vendor && ($post->post_status == 'pending' || $post->post_status == 'future' || $post->post_status == 'draft' || $post->post_status == 'publish')) {
    		$arg = array(
    			'ID' => $post_id,
    			'post_author' => $reporting_vendor
			);
			wp_update_post($arg);
			$vendor_term = get_user_meta( $reporting_vendor, '_vendor_term_id', true );
			$term = get_term( $vendor_term , 'dc_vendor_shop' );
			wp_delete_object_term_relationships( $post_id, 'dc_vendor_shop' );
			wp_set_post_terms( $post_id, $term->name , 'dc_vendor_shop', true );
		}
		add_action('save_post', array(&$this, 'change_sub_vendor_author'));
		 
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
                    'href' => get_permalink(wcmp_vendor_dashboard_page_id()),
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

        $prefix = $wpdb->prefix;
        $meta_name = "{$prefix}capabilities";

        $user_search['meta_query'] = array(
            'relation' => 'AND',
            array(
                'key' => "{$meta_name}",
                'value' => 'dc_sub_vendor',
                'compare' => 'NOT LIKE'
            )
        );
        return $user_search;
    }

    public function block_admin_to_view_staff() {
        global $pagenow;
        if ($pagenow == 'user-edit.php') {
            $user_id = filter_input(INPUT_GET, 'user_id');
            if (!is_null($user_id)) {
                $user = new WP_User($user_id);
                if (in_array('dc_sub_vendor', $user->roles)) {
                    wp_die(__('Sorry, you are not allowed to edit this user.'));
                }
            }
        }
    }
    
    public function block_staff_to_view_others_product() {
        global $pagenow;
        
        $screen = get_current_screen();
        if ($screen->base == 'post' && $screen->id == 'product' && $screen->post_type == 'product') {
        	$post_id = filter_input(INPUT_GET, 'post');
        	if($post_id > 0) {
				$post_author_id = get_post_field( 'post_author', $post_id );
				
				$reporting_vendor_id = get_user_meta(get_current_user_id(), '_report_vendor', true);
				
				if($post_author_id !== $reporting_vendor_id) {
					$url=  admin_url().'edit.php?post_type=product';
					wp_redirect($url);
					exit;
				}
        	}
        }
    }
    
    function staff_update_product_counter($views) {
    	global $current_user;
    	$reporting_vendor_id = get_user_meta($current_user->ID, '_report_vendor', true);
    	
    	foreach($views as $index => $view ) {
    		$args = array(
    			'post_type'   => 'product',
    			'post_author' => $reporting_vendor_id,
			);
			 
			if( $index == 'all') {
				$args['all_posts'] = 1;
			} else {
				$args['post_status'] = $index;
			}
	
			$result = new WP_Query($args);
			if($result->found_posts > 0) {
				$views[$index] = preg_replace( '/<span class="count">\([0-9]+\)<\/span>/', '<span class="count">(' . $result->found_posts . ')</span>', $view );
			} else {
				unset($views[$index]);
			}
		}
		return $views;
	}
	
	function redirect_not_editable_coupons_to_listing_page($location, $post_id) {
		if ( ! $post = get_post( $post_id ) )
			return;
		if ( 'shop_coupon' === $post->post_type && $location == "?message=6") {
			return admin_url("edit.php?post_type=" . $post->post_type); 
		}
		return $location; 
	}
    
    public function reporting_vendor_name() {
        $current_vendor = wp_get_current_user();
        $reporting_vendor_id = get_user_meta($current_vendor->ID, '_report_vendor');
        $reporting_vendor = get_userdata($reporting_vendor_id[0]);
        $html = '<table class="form-field form-table"><tr> <td> Vendor </td> <td>' . $reporting_vendor->user_nicename . '</td></tr>';
        return $html;
    }
}
