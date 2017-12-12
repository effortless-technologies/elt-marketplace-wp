<?php

class WCMP_Vendor_Frontend_Report_Frontend {

    public function __construct() {
        //enqueue scripts
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_scripts'));
        //enqueue styles
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_styles'));

        //add_action('wcmp_others_vendor_pages_link', array(&$this, 'wcmp_others_vendor_pages_link'));

        //add_action('after_vendor_report', array(&$this, 'advance_vendor_report'), 10, 2);

        add_filter('wcmp_vendor_dashboard_nav', array(&$this, 'add_menu_to_wcmp_vendor_dashboard_nav'));
    }

    function advance_vendor_report($vendor, $selected_item) {
        global $WCMp, $WCMp_Vendor_Frontend_Report;
        $pages = get_option('wcmp_pages_settings_name');
        ?>
        <li><a <?php
            if ($selected_item == "advance_vendor_report") {
                echo 'class="selected_menu"';
            }
            ?> data-menu_item="advance_vendor_report" target="_blank" href="<?php echo apply_filters('wcmp_advance_vendor_report', get_permalink($pages['frontend_vendor_reports'])); ?>"><?php _e('- Reports', $WCMp_Vendor_Frontend_Report->text_domain); ?></a></li>
            <?php
        }

        function wcmp_others_vendor_pages_link($vendor) {
            global $WCMp_Vendor_Frontend_Report;
            $pages = get_option("wcmp_pages_settings_name");
            ?>
        <td><a class="frontend_vendor_reports button"  href=<?php echo get_permalink($pages['frontend_vendor_reports']); ?>><strong><?php _e('Reports', $WCMp_Vendor_Frontend_Report->text_domain); ?></strong></a></td>
        <?php
    }

    function add_menu_to_wcmp_vendor_dashboard_nav($nav) {
        global $WCMp_Vendor_Frontend_Report;
        $nav['vendor-report']['submenu']['advance-vendor-report'] = array(
            'label' => __('Reports', $WCMp_Vendor_Frontend_Report->text_domain)
            , 'url' => get_permalink(get_wcmp_vendor_settings('frontend_vendor_reports', 'vendor', 'general'))
            , 'capability' => apply_filters('wcmp_vendor_dashboard_menu_vendor_advance_report_capability', true)
            , 'position' => 20
            , 'link_target' => '_blank'
        );
        return $nav;
    }

    function frontend_scripts() {
        global $WCMp_Vendor_Frontend_Report, $woocommerce;
        $frontend_script_path = $WCMp_Vendor_Frontend_Report->plugin_url . 'assets/frontend/js/';
        $frontend_script_path = str_replace(array('http:', 'https:'), '', $frontend_script_path);
        $pluginURL = str_replace(array('http:', 'https:'), '', $WCMp_Vendor_Frontend_Report->plugin_url);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        $suffix = '';
        if (is_vendor_frontend_report_page()) {
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-tabs');
            //wp_enqueue_script('chosen');
            wp_enqueue_script('dc-chosen', $frontend_script_path . 'chosen.jquery' . $suffix . '.js', array('jquery'), WC_VERSION);
            wp_register_script('jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip' . $suffix . '.js', array('jquery'), WC_VERSION, true);
            wp_enqueue_script('flot', $frontend_script_path . 'jquery.flot' . $suffix . '.js', array('jquery'), WC_VERSION);
            wp_enqueue_script('flot-resize', $frontend_script_path . 'jquery.flot.resize' . $suffix . '.js', array('jquery', 'flot'), WC_VERSION);
            wp_enqueue_script('flot-time', $frontend_script_path . 'jquery.flot.time' . $suffix . '.js', array('jquery', 'flot'), WC_VERSION);
            wp_enqueue_script('flot-pie', $frontend_script_path . 'jquery.flot.pie' . $suffix . '.js', array('jquery', 'flot'), WC_VERSION);
            wp_enqueue_script('flot-stack', $frontend_script_path . 'jquery.flot.stack' . $suffix . '.js', array('jquery', 'flot'), WC_VERSION);
            wp_enqueue_script('frontend_report_js', $frontend_script_path . 'frontend_report.js', array('jquery', 'jquery-ui-datepicker'), $WCMp_Vendor_Frontend_Report->version, true);
            wp_enqueue_script('frontend_trans_report_js', $frontend_script_path . 'transaction_report.js', array('jquery', 'jquery-ui-datepicker'), $WCMp_Vendor_Frontend_Report->version, true);
            wp_enqueue_script('wcmp_frontend_report_low_stock_js', $frontend_script_path . 'low_in_stock.js', array('jquery'), $WCMp_Vendor_Frontend_Report->version, true);
            wp_enqueue_script('frontend_report_out_of_stock_js', $frontend_script_path . 'most_stocked.js', array('jquery'), $WCMp_Vendor_Frontend_Report->version, true);
            wp_enqueue_script('frontend_report_overview_js', $frontend_script_path . 'frontend.js', array('jquery', 'jquery-ui-datepicker'), $WCMp_Vendor_Frontend_Report->version, true);
            wp_enqueue_script('dc_product_overview_js', $frontend_script_path . 'product_overview.js', array('jquery', 'jquery-ui-datepicker'), $WCMp_Vendor_Frontend_Report->version, true);
            wp_enqueue_script('ajax-chosen', $frontend_script_path . 'ajax-chosen.jquery' . $suffix . '.js', array('jquery', 'dc-chosen'), $WCMp_Vendor_Frontend_Report->version, true);
            wp_enqueue_script('dc_product_search_js', $frontend_script_path . 'product_search.js', array('jquery', 'jquery-ui-datepicker'), $WCMp_Vendor_Frontend_Report->version, true);
        }
    }

    function frontend_styles() {
        global $WCMp_Vendor_Frontend_Report;
        $frontend_style_path = $WCMp_Vendor_Frontend_Report->plugin_url . 'assets/frontend/css/';
        $frontend_style_path = str_replace(array('http:', 'https:'), '', $frontend_style_path);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        // Enqueue your frontend stylesheet from here
        if (is_vendor_frontend_report_page()) {
            wp_enqueue_style('vendor_report_frontend_jquery_ui_css', $frontend_style_path . 'jquery-ui.css', array(), $WCMp_Vendor_Frontend_Report->version);
            wp_enqueue_style('vendor_report_frontend_css', $frontend_style_path . 'frontend.css', array(), $WCMp_Vendor_Frontend_Report->version);
            wp_enqueue_style('product_overview.css', $WCMp_Vendor_Frontend_Report->plugin_url . 'assets/frontend/css/product_overview.css', array(), $WCMp_Vendor_Frontend_Report->version);
            wp_enqueue_style('font_awesome_css', 'http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
            wp_enqueue_style('woocommerce_admin_print_reports_styles', WC()->plugin_url() . '/assets/css/reports-print.css', array(), WC_VERSION, 'print');
            wp_enqueue_style('vendor_frontend_css', $WCMp_Vendor_Frontend_Report->plugin_url . 'assets/frontend/css/frontend.css', array(), $WCMp_Vendor_Frontend_Report->version);
            wp_enqueue_style('woocommerce_admin_print_reports_styles', WC()->plugin_url() . '/assets/css/reports-print.css', array(), WC_VERSION, 'print');
            wp_enqueue_style('woocommerce_chosen_styles', $WCMp_Vendor_Frontend_Report->plugin_url . 'assets/frontend/css/chosen.css', array(), $WCMp_Vendor_Frontend_Report->version);
        }
    }

}
