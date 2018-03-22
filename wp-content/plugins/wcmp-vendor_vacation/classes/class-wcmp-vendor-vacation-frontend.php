<?php

class WCMP_Vendor_Vacation_Frontend {

    public function __construct() {
        //enqueue scripts
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_scripts'));
        //enqueue styles
        add_action('wp_enqueue_scripts', array(&$this, 'frontend_styles'));
        add_filter('woocommerce_loop_add_to_cart_link', array($this, 'woocommerce_loop_add_to_cart_link'), 10, 2);
        add_filter('woocommerce_product_single_add_to_cart_text', array(&$this, 'woocommerce_product_single_add_to_cart_text_callback'), 30, 1);
        add_action('woocommerce_single_product_summary', array(&$this, 'wcmp_single_product_summary'), 29);
        add_action('woocommerce_single_product_summary', array(&$this, 'custom_add_to_cart_notification'), 28);
        add_action('wcmp_vendor_dashboard_header', array(&$this, 'wcmp_vendor_dashboard_header'));
        add_action('wcmp_vendor_dashboard_vendor-vacation_endpoint', array(&$this, 'wcmp_vendor_dashboard_vendor_vacation_endpoint'));
        add_action('before_wcmp_vendor_dashboard', array(&$this, 'save_vendor_vacation_data')); 
    }

    function wcmp_single_product_summary() {
        global $product;
        $vendor_product = get_wcmp_product_vendors($product->get_id());
        if ($vendor_product) {
            $vendor_vacation_set_up = get_user_meta($vendor_product->id, '_vacation_include_dates', true);
            $holidays = isset($vendor_vacation_set_up['include_dates_array']) ? $vendor_vacation_set_up['include_dates_array'] : false;
            $enable_store_time = isset($vendor_vacation_set_up['is_enable_store_time']) ? $vendor_vacation_set_up['is_enable_store_time'] : false;
            $local_time = localtime(time(), true);
            $current_hour = $local_time['tm_hour'];
            $current_minute = $local_time['tm_min'];
            $open_hour = isset($vendor_vacation_set_up['open_time']['hour']) ? $vendor_vacation_set_up['open_time']['hour'] : '00';
            $open_minute = isset($vendor_vacation_set_up['open_time']['minute']) ? $vendor_vacation_set_up['open_time']['minute'] : '00';
            $close_hour = isset($vendor_vacation_set_up['close_time']['hour']) ? $vendor_vacation_set_up['close_time']['hour'] : '00';
            $close_minute = isset($vendor_vacation_set_up['close_time']['minute']) ? $vendor_vacation_set_up['close_time']['minute'] : '00';
            $add_to_cart_text = isset($vendor_vacation_set_up['add_to_cart_text']) ? $vendor_vacation_set_up['add_to_cart_text'] : esc_html( $product->single_add_to_cart_text() );
            if(empty($add_to_cart_text))
                $add_to_cart_text = esc_html( $product->single_add_to_cart_text() );

            if ($holidays && !empty($holidays)) {
                $current_dt = date('j/n/Y');
                if (in_array($current_dt, $holidays)) { 
                    if (isset($vendor_vacation_set_up['avoid_purchase'])) {
                        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
                        add_action('woocommerce_single_product_summary', array($this, 'wcmp_vacation_avoid_add_to_cart_msg'), 31);
                    } else { 
                        if($enable_store_time){
                            $open_from = $open_hour.':'.$open_minute;
                            $open_to = $close_hour.':'.$close_minute;
                            // now check if the current time is before or after opening hours
                            if (date("H:i") < $open_from || date("H:i") > $open_to ) {
                                remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
                                echo $vendor_vacation_set_up['shop_closed_text'];
                            }else {
                                add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
                            }
                        }else{
                            add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
                        }
                    }
                }else{ 
                    if($enable_store_time){
                        $open_from = $open_hour.':'.$open_minute;
                        $open_to = $close_hour.':'.$close_minute;
                        // now check if the current time is before or after opening hours
                        if (date("H:i") < $open_from || date("H:i") > $open_to ) {
                            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
                            echo $vendor_vacation_set_up['shop_closed_text'];
                        }else {
                            add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
                        }
                    }else{
                        add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
                    }
                }
            }else{ 
                if($enable_store_time){ 
                    $open_from = $open_hour.':'.$open_minute;
                    $open_to = $close_hour.':'.$close_minute;
                    // now check if the current time is before or after opening hours
                    if (date("H:i") < $open_from || date("H:i") > $open_to ) {
                        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30); 
                        echo $vendor_vacation_set_up['shop_closed_text'];
                    }else {
                        add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
                    }
                }else{
                    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
                }
            }
        }
    }

    function woocommerce_product_single_add_to_cart_text_callback($text) {
        global $WCMP_Vendor_Vacation, $product;
        $vendor_product = get_wcmp_product_vendors($product->get_id());
        if ($vendor_product) {
            $vendor_vacation_set_up = get_user_meta($vendor_product->id, '_vacation_include_dates', true);
            if ($vendor_vacation_set_up) {
                $current_dt = date('j/n/Y');
                $holidays = $vendor_vacation_set_up['include_dates_array'];
                $add_to_cart_text = isset($vendor_vacation_set_up['add_to_cart_text']) ? $vendor_vacation_set_up['add_to_cart_text'] : $text;
                if(empty($add_to_cart_text)){
                    $add_to_cart_text = $text;
                }
                if ($holidays && !empty($holidays)) {
                    if (in_array($current_dt, $holidays)) {
                        return $add_to_cart_text;
                    }else{
                        return $add_to_cart_text;
                    }
                }else{
                    return $add_to_cart_text;
                }
            }else{
                return $text;
            }
        }
        return $text;
    }

    function wcmp_vacation_avoid_add_to_cart_msg() {
        global $product;
        $vendor_product = get_wcmp_product_vendors($product->get_id());
        if ($vendor_product) {
            $vendor_vacation_set_up = get_user_meta($vendor_product->id, '_vacation_include_dates', true);
            if(isset($vendor_vacation_set_up['custom_notification_avoid_puchase']) && !empty($vendor_vacation_set_up['custom_notification_avoid_puchase'])){
                echo '<p class="wcmp_add_to_cart_message">' . $vendor_vacation_set_up['custom_notification_avoid_puchase'] . '</p>';
            }
        }
    }

    function custom_add_to_cart_notification() {
        global $product;
        $vendor_product = get_wcmp_product_vendors($product->get_id());
        if ($vendor_product) {
            $vendor_vacation_set_up = get_user_meta($vendor_product->id, '_vacation_include_dates', true);
            if(isset($vendor_vacation_set_up['custom_notification']) && !empty($vendor_vacation_set_up['custom_notification']) && !isset($vendor_vacation_set_up['avoid_purchase'])){
                echo '<p class="wcmp_add_to_cart_message">' . $vendor_vacation_set_up['custom_notification'] . '</p>';
            }
        }
    }

    function woocommerce_loop_add_to_cart_link($link, $product) {
        $args = array();
        if ( $product ) {
            $defaults = array(
                'quantity' => 1,
                'class'    => implode( ' ', array_filter( array(
                        'button',
                        'product_type_' . $product->get_type(),
                        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                        $product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
                ) ) ),
            );

            $args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );
        }

        $vendor_product = get_wcmp_product_vendors($product->get_id());

        if ($vendor_product) { 
            $vendor_vacation_set_up = get_user_meta($vendor_product->id, '_vacation_include_dates', true);
            if ($vendor_vacation_set_up) {
                $current_dt = date('j/n/Y');
                $holidays = $vendor_vacation_set_up['include_dates_array'];
                $enable_store_time = isset($vendor_vacation_set_up['is_enable_store_time']) ? $vendor_vacation_set_up['is_enable_store_time'] : false;
                $local_time = localtime(time(), true);
                $current_hour = $local_time['tm_hour'];
                $current_minute = $local_time['tm_min'];
                $open_hour = isset($vendor_vacation_set_up['open_time']['hour']) ? $vendor_vacation_set_up['open_time']['hour'] : '00';
                $open_minute = isset($vendor_vacation_set_up['open_time']['minute']) ? $vendor_vacation_set_up['open_time']['minute'] : '00';
                $close_hour = isset($vendor_vacation_set_up['close_time']['hour']) ? $vendor_vacation_set_up['close_time']['hour'] : '00';
                $close_minute = isset($vendor_vacation_set_up['close_time']['minute']) ? $vendor_vacation_set_up['close_time']['minute'] : '00';
                $add_to_cart_text = isset($vendor_vacation_set_up['add_to_cart_text']) ? $vendor_vacation_set_up['add_to_cart_text'] : $product->add_to_cart_text();
                if(empty($add_to_cart_text))
                    $add_to_cart_text = esc_html( $product->add_to_cart_text() );
                
                if ($holidays && !empty($holidays)) { 
                    if (in_array($current_dt, $holidays)) {
                        if (isset($vendor_vacation_set_up['avoid_purchase'])) { 
                           
                            $this->wcmp_vacation_avoid_add_to_cart_msg();
                        }else{
                            if($enable_store_time){
                                $open_from = $open_hour.':'.$open_minute;
                                $open_to = $close_hour.':'.$close_minute;
                                // now check if the current time is before or after opening hours
                                if (date("H:i") < $open_from || date("H:i") > $open_to ) {
                                    return '<p>'.$vendor_vacation_set_up['shop_closed_text'].'</p>';
                                }else {
                                    return sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="%s">%s</a>', esc_url(get_permalink($product->get_id())), esc_attr($product->get_id()), esc_attr($product->get_sku()), esc_attr(isset($quantity) ? $quantity : 1 ), esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ), $add_to_cart_text);
                                }
                            }else{
                                return sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="%s">%s</a>', esc_url(get_permalink($product->get_id())), esc_attr($product->get_id()), esc_attr($product->get_sku()), esc_attr(isset($quantity) ? $quantity : 1 ), esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ), $add_to_cart_text);
                            }
                        }
                    }else{
             
                        if($enable_store_time){
                            $open_from = $open_hour.':'.$open_minute;
                            $open_to = $close_hour.':'.$close_minute;
                            // now check if the current time is before or after opening hours
                            if (date("H:i") < $open_from || date("H:i") > $open_to ) {
                                return '<p>'.$vendor_vacation_set_up['shop_closed_text'].'</p>';
                            }else {
                                return sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="%s">%s</a>', esc_url(get_permalink($product->get_id())), esc_attr($product->get_id()), esc_attr($product->get_sku()), esc_attr(isset($quantity) ? $quantity : 1 ), esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ), $add_to_cart_text);
                            }
                        }else{
                            return sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="%s">%s</a>', esc_url(get_permalink($product->get_id())), esc_attr($product->get_id()), esc_attr($product->get_sku()), esc_attr(isset($quantity) ? $quantity : 1 ), esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ), $add_to_cart_text);
                        }
                    }
                }else{
                
                    if($enable_store_time){
                        $open_from = $open_hour.':'.$open_minute;
                        $open_to = $close_hour.':'.$close_minute;
                        // now check if the current time is before or after opening hours
                        if (date("H:i") < $open_from || date("H:i") > $open_to ) {
                            return '<p>'.$vendor_vacation_set_up['shop_closed_text'].'</p>';
                        }else {
                            return sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="%s">%s</a>', esc_url(get_permalink($product->get_id())), esc_attr($product->get_id()), esc_attr($product->get_sku()), esc_attr(isset($quantity) ? $quantity : 1 ), esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ), $add_to_cart_text);
                        }
                    }else{
                        return sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="%s">%s</a>', esc_url(get_permalink($product->get_id())), esc_attr($product->get_id()), esc_attr($product->get_sku()), esc_attr(isset($quantity) ? $quantity : 1 ), esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ), $add_to_cart_text);
                    }
                }
            }else{
                echo $link;
            }
        }else{ 
            echo $link;
        }
    }

    public function save_vendor_vacation_data() {
        global $WCMp;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            if ($WCMp->endpoints->get_current_endpoint() == 'vendor-vacation') {
                $wcmp_vendor_vacation = array();
                $wcmp_vendor_vacation['include_dates_array'] = json_decode(stripslashes($_POST['include_dates_array']));
                if (isset($_POST['avoid_purchase']) && !empty($_POST['avoid_purchase'])) {
                    $wcmp_vendor_vacation['avoid_purchase'] = 'true';
                }
                if (isset($_POST['is_enable_store_time']) && !empty($_POST['is_enable_store_time'])) {
                    $wcmp_vendor_vacation['is_enable_store_time'] = 'true';
                }
                $wcmp_vendor_vacation['add_to_cart_text'] = isset($_POST['add_to_cart_text']) ? sanitize_text_field($_POST['add_to_cart_text']) : '';
                $wcmp_vendor_vacation['custom_notification'] = isset($_POST['custom_notification']) ? sanitize_textarea_field($_POST['custom_notification']) : '';
                $wcmp_vendor_vacation['custom_notification_avoid_puchase'] = isset($_POST['custom_notification_avoid_puchase']) ? sanitize_textarea_field($_POST['custom_notification_avoid_puchase']) : '';
                $wcmp_vendor_vacation['open_time']['hour'] = isset($_POST['open_time_hh']) ? sprintf("%02d", $_POST['open_time_hh']) : '';
                $wcmp_vendor_vacation['open_time']['minute'] = isset($_POST['open_time_mm']) ? sprintf("%02d", $_POST['open_time_mm']) : '';
                $wcmp_vendor_vacation['close_time']['hour'] = isset($_POST['close_time_hh']) ? sprintf("%02d", $_POST['close_time_hh']) : '';
                $wcmp_vendor_vacation['close_time']['minute'] = isset($_POST['close_time_mm']) ? sprintf("%02d", $_POST['close_time_mm']) : '';
                $shop_closed_text = isset($_POST['shop_closed_text']) ? sanitize_text_field($_POST['shop_closed_text']) : __('Shop is Closed now!', 'wcmp-vendor_vacation');
                if(empty($shop_closed_text))
                    $shop_closed_text = __('Shop is Closed now!', 'wcmp-vendor_vacation');
                $wcmp_vendor_vacation['shop_closed_text'] = $shop_closed_text;
                if (update_user_meta(get_current_user_id(), '_vacation_include_dates', $wcmp_vendor_vacation)) {
                    wc_add_notice(__('All options saved', $WCMp->text_domain), 'success');
                }
            }
        }
    }

    public function wcmp_vendor_dashboard_header() {
        global $WCMp, $WCMP_Vendor_Vacation;
        if ($WCMp->endpoints->get_current_endpoint() == 'vendor-vacation') {
            echo '<ul>';
            echo '<li>' . __('Store Settings ', 'wcmp-vendor_vacation') . '</li>';
            echo '<li class="next"> < </li>';
            echo '<li>' . __('Vacation', 'wcmp-vendor_vacation') . '</li>';
            echo '</ul>';
        }
    }

    public function wcmp_vendor_dashboard_vendor_vacation_endpoint() {
        global $WCMP_Vendor_Vacation;
        $current_user_id = get_current_user_id();
        $WCMP_Vendor_Vacation->nocache();
        wp_enqueue_script('calender_js', $WCMP_Vendor_Vacation->plugin_url . 'assets/frontend/js/calendar.js', array('jquery'), '0.0.1', true);
        wp_enqueue_style('calender_css', $WCMP_Vendor_Vacation->plugin_url . 'assets/frontend/css/calendar.css', array(), '0.0.1');
        $wcmp_vendor_vacation = get_user_meta($current_user_id, '_vacation_include_dates', true);
        $db_selected_dates = isset($wcmp_vendor_vacation['include_dates_array']) ? $wcmp_vendor_vacation['include_dates_array'] : false;
        $year = date('Y');
        $month = date('n');
        $calender_html = '';
        for ($i = $month; $i <= 12; $i++) {
            $calender_html .= wcmp_vacation_showMonth($i, $year, $db_selected_dates);
        }
        if ($db_selected_dates) {
            $selected_dates = stripslashes(json_encode($db_selected_dates));
        } else {
            $selected_dates = json_encode(array());
        }
        $WCMP_Vendor_Vacation->template->get_template('wcmp_vendor_vacation_settings_template.php', array('wcmp_vendor_vacation' => $wcmp_vendor_vacation, 'calender_html' => $calender_html, 'selected_dates' => $selected_dates));
    }

    function frontend_scripts() {
        global $WCMP_Vendor_Vacation;
        $frontend_script_path = $WCMP_Vendor_Vacation->plugin_url . 'assets/frontend/js/';
        $frontend_script_path = str_replace(array('http:', 'https:'), '', $frontend_script_path);
        $pluginURL = str_replace(array('http:', 'https:'), '', $WCMP_Vendor_Vacation->plugin_url);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        wp_enqueue_script('vacation_front_js', $WCMP_Vendor_Vacation->plugin_url . 'assets/frontend/js/frontend.js', array('jquery'), $WCMP_Vendor_Vacation->version, true);
        // Enqueue your frontend javascript from here
    }

    function frontend_styles() {
        global $WCMP_Vendor_Vacation;
        $frontend_style_path = $WCMP_Vendor_Vacation->plugin_url . 'assets/frontend/css/';
        $frontend_style_path = str_replace(array('http:', 'https:'), '', $frontend_style_path);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        // Enqueue your frontend stylesheet from here
    }

}
