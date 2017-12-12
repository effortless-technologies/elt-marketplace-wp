<?php

class WCMP_Sub_Vendor_Menu {

    public function __construct() {
        //admin script and style
        global $current_user;

        $user_roles = $current_user->roles;
        $user_role = array_shift($user_roles);

        if ($user_role == 'dc_vendor') {
            add_action('admin_menu', array($this, 'sub_vendors'));
            add_action('admin_menu', array($this, 'add_sub_vendor'));
        }
    }

    public function sub_vendors() {
        global $WCMP_Sub_Vendor;
        $page_title = __('Vendor Staff', $WCMP_Sub_Vendor->text_domain);
        $menu_title = __('Vendor Staff', $WCMP_Sub_Vendor->text_domain);
        $capability = 'edit_posts';
        $menu_slug = 'sub-vendors_page';
        $function = array(&$this, 'list_sub_vendor');
        //$icon_url = plugin_dir_url().'wcmp-sub_vendor/assets/images/s_v.jpg'; //use icon url
        $icon_url = 'dashicons-networking';
        $position = 100;
        add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
    }

    public function add_sub_vendor() {
        global $WCMP_Sub_Vendor;
        $parent_slug = __('sub-vendors_page', $WCMP_Sub_Vendor->text_domain);
        $page_title = __('Vendor Staff', $WCMP_Sub_Vendor->text_domain);
        $menu_title = 'Add Vendor Staff';
        $capability = 'edit_posts';
        $menu_slug = 'sub_vendor_details';
        $function = array(&$this, 'sub_vendor_details');
        add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
    }

    /**
     * Display the list_sub_vendor
     *
     * @return Void
     */
    public function list_sub_vendor() {
        global $WCMP_Sub_Vendor;
        $sub_vendor_table = new Sub_Vendor_List_Table();
        $sub_vendor_table->prepare_items();
        ?>
        <div class="wrap">
            <div id="icon-users" class="icon32"></div>
            <h2><?php _e('Vendor Staff', $WCMP_Sub_Vendor->text_domain); ?></h2>
            <h1><a href="<?php echo get_site_url() . '/wp-admin/admin.php?page=sub_vendor_details' ?>" class="page-title-action">Add New</a></h1>

            <form method="post" name="admin_form">
                <?php $sub_vendor_table->display(); ?>
            </form>

        </div>
        <?php
    }

    public function sub_vendor_details() {
        global $WCMP_Sub_Vendor;

        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if ($action == 'edit') {
                $user_id = $_GET['userid'];
                $user_details = get_userdata($user_id);
                ?>
                <h2 id="add-new-sub_vendor"><?php _e('Edit Vendor Staff', $WCMP_Sub_Vendor->text_domain); ?></h2>
                <div class="sub_vendor_success" style="color: #0073AA; font-size: 17px;"></div>
                <div class="sub_vendor_error1"  style="color: #ff0000; font-size: 17px;"> </div>
                <form action="" id="edit_sub_vendor" method="post">
                    <input type="hidden" name="id" value="<?php echo esc_attr($id) ?>" />
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="sub_vendor_username"><?php _e('Username', $WCMP_Sub_Vendor->text_domain) ?></label></th>
                            <td><input type="text" class="regular-text" name="sub_vendor_username]" id="sub_vendor_username" value="<?php echo $user_details->user_nicename; ?>" required disabled/> <span class="description">Usernames cannot be changed.</span></td>

                        </tr>
                        <tr>
                            <th scope="row"><label for="sub_vendor_email"><?php _e('Email', $WCMP_Sub_Vendor->text_domain) ?></label></th>
                            <td><input type="email" class="regular-text" name="sub_vendor_email" id="sub_vendor_email" value="<?php echo $user_details->user_email; ?>" required /></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="sub_vendor_fname"><?php _e('First Name ', $WCMP_Sub_Vendor->text_domain) ?></label></th>
                            <td><input type="text" class="regular-text" name="sub_vendor_fname" id="sub_vendor_fname" value="<?php echo $user_details->first_name; ?>" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="sub_vendor_lname"><?php _e('Last Name ', $WCMP_Sub_Vendor->text_domain) ?></label></th>
                            <td><input type="text" class="regular-text" name="sub_vendor_lname" id="sub_vendor_lname" value="<?php echo $user_details->last_name; ?>" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="sub_vendor_password"><?php _e('New Password ', $WCMP_Sub_Vendor->text_domain) ?></label></th>
                            <td><input type="password" class="regular-text" name="sub_vendor_password" id="sub_vendor_password"  /></td>
                        </tr>

                        <tr class="form-field">
                            <th scope="row"><label for="sub_vendor_capabilities"><?php _e('Assign capabilities', $WCMP_Sub_Vendor->text_domain) ?></label></th>
                            <td>
                                <div id = "sub_vendor_capabilities">
                                    <?php
                                    $reporting_vendor = wcmp_report_vendor($user_id);
                                    $reporting_vendor_userdata = $reporting_vendor->user_data;
                                    if (isset($reporting_vendor_userdata->allcaps['manage_product'])) {
                                        if (isset($user_details->allcaps['manage_product'])) {
                                            ?>
                                            <input id="manage_product" type="checkbox" name="manage_product"  checked="checked" /><?php _e('Manage Product &nbsp &nbsp', $WCMP_Sub_Vendor->text_domain); ?>
                                            <?php
                                        } else {
                                            ?>
                                            <input id="manage_product" type="checkbox" name="manage_product"  /><?php _e('Manage Product &nbsp &nbsp', $WCMP_Sub_Vendor->text_domain); ?>
                                            <?php
                                        }
                                    }
                                    //if (isset($reporting_vendor_userdata->allcaps['manage_woocommerce_orders'])) {
                                        if (isset($user_details->allcaps['manage_woocommerce_orders'])) {
                                            ?> 
                                            <input id="manage_order" type="checkbox" name="manage_order"  checked="checked"/><?php _e('Manage Order &nbsp&nbsp', $WCMP_Sub_Vendor->text_domain); ?>
                                            <?php
                                        } else {
                                            ?>
                                            <input id="manage_order" type="checkbox" name="manage_order" /><?php _e('Manage Order&nbsp &nbsp', $WCMP_Sub_Vendor->text_domain); ?>
                                            <?php
                                        }
                                    //}
                                    //if (isset($reporting_vendor_userdata->allcaps['manage_payment'])) {
                                        if (isset($user_details->allcaps['manage_payment'])) {
                                            ?>
                                            <input id="manage_payment" type="checkbox" name="manage_payment" checked="checked" /><?php _e('Manage Payment &nbsp &nbsp', $WCMP_Sub_Vendor->text_domain); ?>
                                            <?php
                                        } else {
                                            ?>
                                            <input id="manage_payment" type="checkbox" name="manage_payment"  /><?php _e('Manage Payment &nbsp &nbsp', $WCMP_Sub_Vendor->text_domain); ?>
                                            <?php
                                        }
                                    //}
                                    if (isset($reporting_vendor_userdata->allcaps['view_woocommerce_reports'])) {
                                        if (isset($user_details->allcaps['view_woocommerce_reports'])) {
                                            ?>
                                            <input id="manage_reports" type="checkbox" name="manage_reports" checked="checked" /><?php _e('Manage Reports', $WCMP_Sub_Vendor->text_domain); ?>
                                            <?php
                                        } else {
                                            ?>
                                            <input id="manage_reports" type="checkbox" name="manage_reports"  /><?php _e('Manage Reports', $WCMP_Sub_Vendor->text_domain); ?>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <input type="submit" id = 'submit_add_Sub_Vendor' value = "Save Staff" class="button button-primary">
                    <img src="<?php echo plugins_url() ?>/wcmp-sub_vendor/assets/images/load.gif" height = "30px" width = "30px" class = "sv_ajax_loader" style = "display: none;">

                </form>


                <?php
            } else if ($action == 'delete') {
                $user_id = $_GET['userid'];
                $user_details = get_user_by('id', $user_id);
                ?>
                <div class="wrap">
                    <h1><?php _e('Delete Staff', $WCMP_Sub_Vendor->text_domain); ?></h1>

                    <p><?php _e('You have specified this Staff for deletion:', $WCMP_Sub_Vendor->text_domain); ?></p>

                    <ul>
                        <li><input name="users[]" value="<?php echo $user_id ?>" type="hidden">ID #<?php echo $user_id ?>: <?php echo $user_details->user_nicename; ?></li>
                    </ul>
                    <input name="delete_option" value="delete" type="hidden">
                    <input name="action" value="dodelete" type="hidden">

                    <p class="submit"><button class="button button-primary" id ="delete_sub_vendor" value="<?php echo $user_id ?>">Confirm Deletion</button></p>
                </div>
                <?php
            }
        } else {
            $user_id = get_current_user_id();
            $user_details = get_userdata($user_id);
            ?>
            <h2 id="add-new-sub_vendor"><?php _e('Add New Vendor Staff'); ?></h2>
            <div class="sub_vendor_success" style="color: #0073AA; font-size: 17px;"></div>
            <div class="sub_vendor_error1"  style="color: #ff0000; font-size: 17px;"> </div>
            <form action="" id="newsub_vendor" method="post">
                <input type="hidden" name="id" value="<?php echo esc_attr($id) ?>" />
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="sub_vendor_username"><?php _e('Username', $WCMP_Sub_Vendor->text_domain) ?></label><span class="description">&nbsp(required)</span></th>
                        <td><input type="text" class="regular-text" name="sub_vendor_username]" id="sub_vendor_username" required /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sub_vendor_email"><?php _e('Email', $WCMP_Sub_Vendor->text_domain) ?></label><span class="description">&nbsp(required)</span></th>
                        <td><input type="email" class="regular-text" name="sub_vendor_email" id="sub_vendor_email" required /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sub_vendor_fname"><?php _e('First Name ', $WCMP_Sub_Vendor->text_domain) ?></label></th>
                        <td><input type="text" class="regular-text" name="sub_vendor_fname" id="sub_vendor_fname" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sub_vendor_lname"><?php _e('Last Name ', $WCMP_Sub_Vendor->text_domain) ?></label></th>
                        <td><input type="text" class="regular-text" name="sub_vendor_lname" id="sub_vendor_lname" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="sub_vendor_password"><?php _e('Password ', $WCMP_Sub_Vendor->text_domain) ?></label></th>
                        <td><input type="password" class="regular-text" name="sub_vendor_password" id="sub_vendor_password" required /></td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="sub_vendor_capabilities"><?php _e('Assign capabilities') ?></label></th>
                        <td>
                            <div id = "sub_vendor_capabilities">
                                <?php if (isset($user_details->allcaps['manage_product'])) { ?>
                                <input id="manage_product" type="checkbox" name="manage_product" /> <?php _e('Manage Product &nbsp &nbsp', $WCMP_Sub_Vendor->text_domain); ?>
                                <?php } ?>
                                <input id="manage_order" type="checkbox" name="manage_order" />     <?php _e('Manage Order &nbsp &nbsp', $WCMP_Sub_Vendor->text_domain); ?>
                                <input id="manage_payment" type="checkbox" name="manage_payment"  /><?php _e('Manage Payment &nbsp &nbsp', $WCMP_Sub_Vendor->text_domain); ?>
                                <?php if (isset($user_details->allcaps['view_woocommerce_reports'])) { ?>
                                <input id="manage_reports" type="checkbox" name="manage_reports"  /><?php _e('Manage Reports', $WCMP_Sub_Vendor->text_domain); ?>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                </table>

                <input type="submit" id = 'submit_add_Sub_Vendor' value = "Add New Staff" class="button button-primary" >
                <img src="<?php echo plugins_url() ?>/wcmp-sub_vendor/assets/images/load.gif" height = "30px" width = "30px" class = "sv_ajax_loader" style = "display: none;">

            </form>
            <?php
        }
    }

}

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Sub_Vendor_List_Table extends WP_List_Table {

    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->process_bulk_action();
        $data = $this->table_data();
        usort($data, array(&$this, 'sort_data'));

        $this->_column_headers = array($columns, $hidden, $sortable);


        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'username' => 'Username',
            'name' => 'Name',
            'email' => 'Email',
        );

        return $columns;
    }

    public function get_hidden_columns() {
        return array('year', 'director');
    }

    public function get_sortable_columns() {
        $sortable_columns = array(
            'username' => array('username', true),
            'email' => array('email', false),
            'name' => array('name', false)
        );
        return $sortable_columns;
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data() {
        $data = array();
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


        foreach ($user_query->results as $key => $value) {

            $username = $value->data->user_login;
            $name = $value->data->display_name;
            $email = $value->data->user_email;

            $data[] = array(
                'username' => $username,
                'name' => $name,
                'email' => $email
            );
        }
        return $data;
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'username':
            case 'name':
            case 'email':

                return $item[$column_name];

            default:
                return print_r($item, true);
        }
    }

    private function sort_data($a, $b) {
        // Set defaults
        $orderby = 'username';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
        }

        $result = strnatcmp($a[$orderby], $b[$orderby]);

        if ($order === 'asc') {
            return $result;
        }

        return -$result;
    }

    public function column_username($item) {

        $user_id = get_user_by('login', $item['username']);
        $actions = array(
            'edit' => sprintf('<a href="?page=sub_vendor_details&action=%s&userid=%s">Edit</a>', 'edit', $user_id->ID),
            'delete' => sprintf('<a href="?page=sub_vendor_details&action=%s&userid=%s">Delete</a>', 'delete', $user_id->ID),
        );

        return sprintf('%1$s %2$s', $item['username'], $this->row_actions($actions));
    }

    function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="sub_vendors[]" value="%s" />', $item['username']
        );
    }

    function get_bulk_actions() {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    public function process_bulk_action() {

        // security check!
        if (isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce'])) {

            $nonce = filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING);
            $action = 'bulk-' . $this->_args['plural'];

            if (!wp_verify_nonce($nonce, $action))
                wp_die('Nope! Security check failed!');
        }

        $action = $this->get_bulk_actions();
        if (isset($_REQUEST["action"])) {
            if ($_REQUEST["action"] == 'delete') {
                $delete_sub_vendors = $_REQUEST["sub_vendors"];
                foreach ($delete_sub_vendors as $key => $value) {
                    $user_details = get_user_by('login', $value);
                    wp_delete_user($user_details->ID);
                }
            }
        }

        return;
    }

}
