<?php
class WCMP_Vendor_Vacation_Admin {

    public $settings;

    public function __construct() {
        //admin script and style
        add_action('admin_enqueue_scripts', array(&$this, 'enqueue_admin_script'));

    }

    // End load_class()

    /**
     * Admin Scripts
     */
    public function enqueue_admin_script() {
        global $WCMP_Vendor_Vacation;
        $screen = get_current_screen();

        // Enqueue admin script and stylesheet from here
        if (in_array($screen->id, array('toplevel_page_wcmp-vendor-vacation-setting-admin'))) :
            $WCMP_Vendor_Vacation->library->load_qtip_lib();
            $WCMP_Vendor_Vacation->library->load_upload_lib();
            $WCMP_Vendor_Vacation->library->load_colorpicker_lib();
            $WCMP_Vendor_Vacation->library->load_datepicker_lib();
            wp_enqueue_script('admin_js', $WCMP_Vendor_Vacation->plugin_url . 'assets/admin/js/admin.js', array('jquery'), $WCMP_Vendor_Vacation->version, true);
            wp_enqueue_style('admin_css', $WCMP_Vendor_Vacation->plugin_url . 'assets/admin/css/admin.css', array(), $WCMP_Vendor_Vacation->version);
        endif;
    }

}
