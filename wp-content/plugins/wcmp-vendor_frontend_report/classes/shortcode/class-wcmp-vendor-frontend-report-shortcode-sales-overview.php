<?php

class WCMP_Vendor_Report_Shortcode_Sales_Overview {

    public function __construct() {
        
    }

    /**
     * Output the demo shortcode.
     *
     * @access public
     * @param array $atts
     * @return void
     */
    public static function output($attr) {
        global $WCMp_Vendor_Frontend_Report, $WCMp, $woocommerce, $wp_locale;
        $give_tax_to_vendor = get_wcmp_vendor_settings('give_tax','payment');
        $give_shipping_to_vendor = get_wcmp_vendor_settings('give_shipping','payment');
        $vendor_id = get_current_user_id();
        if (function_exists('is_user_wcmp_vendor')) {
            if (is_user_wcmp_vendor($vendor_id)) {
                $total_order_count = 0;
                $total_earnings = 0;
                $total_sales = 0;
                $total_avg_sales = 0;
                $total_vendor_earnings = 0;
                $paid_amt = 0;
                $shipping_amount = 0;

                $chart_data = array();

                if (!isset($_GET['from_date']))
                    $_GET['from_date'] = '';
                if (!isset($_GET['to_date']))
                    $_GET['to_date'] = '';
                ?>
                <div class="transaction_settings">
                    <form method="get"  id="wcmp_transaction_filter" class="" style="margin-bottom: 10px; float: left; display: block;">
                        <table  style="float: left; width: 57%;">
                            <tbody>
                                <tr>
                                    <td><input id="wcmp_frontend_from_date" name="from_date" placeholder="<?php _e('From', 'wcmp-vendor_frontend_report'); ?>" value ="<?php echo $_GET['from_date']; ?>"/></td>
                                    <td><input id="wcmp_frontend_to_date" name="to_date" placeholder="<?php _e('To', 'wcmp-vendor_frontend_report'); ?>" value ="<?php echo $_GET['to_date']; ?>"/></td>
                                    <td><p class="submit"><input type="submit" name="order_export_submit" id="submit" class="all_new_btn button button-primary" value="<?php _e('Filter', 'wcmp-vendor_frontend_report'); ?>"></p></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    <form method="post" name="export_sales_report_orders_from">
                        <input type="submit"
                               class="all_new_btn button button-primary"
                               style=" float: right;  margin-top: 17px;"
                               name="export_sales_report_orders"
                               value="<?php _e('Export CSV', 'wcmp-vendor_frontend_report'); ?>" 
                               />
                    </form>
                </div>
                <?php
                if ($_GET['from_date'])
                    $start_date = strtotime($_GET['from_date']);
                else
                    $start_date = strtotime(date('Ymd', strtotime(date('Ym', current_time('timestamp')) . '01')));

                if ($_GET['to_date'])
                    $end_date = strtotime($_GET['to_date']);
                else
                    $end_date = strtotime(date('Ymd', current_time('timestamp')));


                for ($date = $start_date; $date <= $end_date; $date = strtotime('+1 day', $date)) {

                    $year = date('Y', $date);
                    $month = date('n', $date);
                    $day = date('j', $date);

                    $line_total = $sales = $comm_amount = $vendor_earnings = $earnings = $order_count = 0;

                    $args = array(
                        'post_type' => 'shop_order',
                        'posts_per_page' => -1,
                        'post_status' => array('wc-pending', 'wc-processing', 'wc-on-hold', 'wc-completed', 'wc-cancelled', 'wc-refunded', 'wc-failed'),
                        'meta_query' => array(
                            array(
                                'key' => '_commissions_processed',
                                'value' => 'yes',
                                'compare' => '='
                            )
                        ),
                        'date_query' => array(
                            array(
                                'year' => $year,
                                'month' => $month,
                                'day' => $day,
                            ),
                        )
                    );

                    $qry = new WP_Query($args);

                    $orders = apply_filters('wcmp_filter_orders_report_overview', $qry->get_posts());

                    if (!empty($orders)) {
                        foreach ($orders as $order_obj) {

                            $order = new WC_Order($order_obj->ID);
                            $items = $order->get_items('line_item');
                            $shipping_items = $order->get_items('shipping');
                            $commission_array = array();
                            foreach ($items as $item_id => $item) {
                                $comm_pro_id = $product_id = wc_get_order_item_meta($item_id, '_product_id', true);
                                $line_total = wc_get_order_item_meta($item_id, '_line_total', true);
                                $line_tax = wc_get_order_item_meta($item_id,'_line_tax',true);
                                $variation_id = wc_get_order_item_meta($item_id, '_variation_id', true);
                                if ($variation_id)
                                    $comm_pro_id = $variation_id;
                                if ($product_id && $line_total) {
                                    $product_vendors = get_wcmp_product_vendors($product_id);
                                    if ($product_vendors) {
                                        if ($vendor_id != $product_vendors->id)
                                            continue;
                                        if($give_tax_to_vendor == 'Enable'){
                                            $sales += ($line_total+$line_tax);
                                            $total_sales += ($line_total + $line_tax);
                                        } else {
                                            $sales += $line_total;
                                            $total_sales += $line_total;
                                        }
                                        
                                        $args = array(
                                            'post_type' => 'dc_commission',
                                            'post_status' => array('publish', 'private'),
                                            'posts_per_page' => -1,
                                            'meta_query' => array(
                                                array(
                                                    'key' => '_commission_vendor',
                                                    'value' => absint($product_vendors->term_id),
                                                    'compare' => '='
                                                ),
                                                array(
                                                    'key' => '_commission_order_id',
                                                    'value' => absint($order_obj->ID),
                                                    'compare' => '='
                                                ),
                                                array(
                                                    'key' => '_commission_product',
                                                    'value' => absint($comm_pro_id),
                                                    'compare' => 'LIKE'
                                                ),
                                            ),
                                        );
                                        $commissions = get_posts($args);
                                        $comm_amount = 0;
                                        if (!empty($commissions)) {
                                            foreach ($commissions as $commission) {
                                                if (in_array($commission->ID, $commission_array))
                                                    continue;
                                                $comm_amount += (float) get_post_meta($commission->ID, '_commission_amount', true);
//                                                $item_shipping_amount = (float) get_post_meta($commission->ID, '_shipping', true);
//                                                $comm_amount += $item_shipping_amount;
//                                                $total_sales += $item_shipping_amount;
//                                                $sales += $item_shipping_amount;
                                                $commission_status = get_post_meta($commission->ID, '_paid_status', true);
                                                if ($commission_status == 'paid')
                                                    $paid_amt += $comm_amount;
                                                $commission_array[] = $commission->ID;
                                            }
                                        }
                                        if($give_tax_to_vendor == 'Enable'){
                                            $vendor_earnings += ($comm_amount + $line_tax);
                                            $total_vendor_earnings += ($comm_amount + $line_tax);
                                            $earnings += (( $line_total - $comm_amount ) + $line_tax);
                                            $total_earnings += (( $line_total - $comm_amount ) + $line_tax);
                                        } else{
                                            $vendor_earnings += $comm_amount;
                                            $total_vendor_earnings += $comm_amount;
                                            $earnings += ( $line_total - $comm_amount );
                                            $total_earnings += ( $line_total - $comm_amount );
                                        }
                                    }
                                }
                            }
                            $vendor_shipping_amount = 0;
                            if(!empty($shipping_items) && $give_shipping_to_vendor == 'Enable'){
                               foreach ($shipping_items as $shipping_id => $shipping){
                                    $vendor_shipping_amount = (float)wc_get_order_item_meta($shipping_id,'vendor_cost_'.$vendor_id,true);
                                    $vendor_shipping_tax_array = wc_get_order_item_meta($shipping_id,'vendor_tax_'.$vendor_id,true);
                                    $vendor_shipping_tax = 0;
                                    foreach ($vendor_shipping_tax_array as $shipping_tax){
                                        $vendor_shipping_tax += (float)$shipping_tax;
                                    }
                                    $total_sales += ($vendor_shipping_amount + $vendor_shipping_tax);
                                    $sales += ($vendor_shipping_amount + $vendor_shipping_tax);
                                    $vendor_earnings += ($vendor_shipping_amount + $vendor_shipping_tax);
                                    $total_vendor_earnings += ($vendor_shipping_amount + $vendor_shipping_tax);
                                    $earnings += ($vendor_shipping_amount + $vendor_shipping_tax);
                                    $total_earnings += ($vendor_shipping_amount + $vendor_shipping_tax);
                                } 
                            }
                            ++$order_count;
                            ++$total_order_count;
                        }
                    }
                    if ($order_count > 0)
                        $avg_sales = $sales / $order_count;
                    else
                        $avg_sales = 0;
                    $chart_data_order_count[] = wcmpArrayToObject(array('post_date' => date("Y-m-d H:i:s", $date), 'count' => $order_count));
                    $chart_data_sales[] = wcmpArrayToObject(array('post_date' => date("Y-m-d H:i:s", $date), 'sales' => $sales));
                    $chart_data_vendor_earnings[] = wcmpArrayToObject(array('post_date' => date("Y-m-d H:i:s", $date), 'vendor_earnings' => $vendor_earnings));
                    $chart_data_earnings[] = wcmpArrayToObject(array('post_date' => date("Y-m-d H:i:s", $date), 'earnings' => $earnings));
                    $chart_data_avg_sales[] = wcmpArrayToObject(array('post_date' => date("Y-m-d H:i:s", $date), 'avg_sales' => $avg_sales));
                }

                if ($total_order_count > 0)
                    $total_avg_sales = $total_sales / $total_order_count;
                else
                    $total_avg_sales = 0;
                ?>
                <div class="dc-reports-div" style="clear: both; display: block;">
                    <label><?php echo sprintf(__('Your Report [ %s ]', 'wcmp-vendor_frontend_report'), date('F j, Y', $start_date) . ' - ' . date('F j, Y', $end_date)); ?></label>
                    <div class="chart-sidebar">
                        <ul class="chart-legend">
                            <li style="border-color: #FF0000" class="highlight_series tips" data-series="1">
                                <i class="fa  fa-circle "></i><label><?php _e('Gross Sales in this Period %s', 'wcmp-vendor_frontend_report'); ?></label> <strong><span class="amount"><?php if ($total_sales > 0)
                    echo wc_price($total_sales);
                else
                    _e('n/a', 'wcmp-vendor_frontend_report');
                ?></span></strong> 						
                            </li>
                            <li style="border-color: #FF9B00" class="highlight_series " data-series="2" data-tip="">
                                <i class="fa  fa-circle "></i><label><?php _e('Average Daily Sales', 'wcmp-vendor_frontend_report'); ?></label> <strong><span class="amount"><?php if ($total_avg_sales > 0)
                    echo wc_price($total_avg_sales);
                else
                    _e('n/a', 'wcmp-vendor_frontend_report');
                ?></span></strong> 							
                            </li>
                            <li style="border-color: #E4D8EF" class="highlight_series " data-series="0" data-tip="">
                                <i class="fa  fa-circle "></i><label><?php _e('Number of Orders Placed', 'wcmp-vendor_frontend_report'); ?></label> <strong><span class="amount"><?php if ($total_order_count > 0)
                                        echo $total_order_count;
                                    else
                                        _e('n/a', 'wcmp-vendor_frontend_report');
                                    ?><span class="amount"></strong>							
                                            </li>
                                            <li style="border-color: #5cc488" class="highlight_series " data-series="3" data-tip="">
                                                <i class="fa  fa-circle "></i><label><?php _e('My Earnings', 'wcmp-vendor_frontend_report'); ?></label>	<strong><?php if ($total_vendor_earnings > 0)
                                echo wc_price($total_vendor_earnings);
                            else
                                _e('n/a', 'wcmp-vendor_frontend_report');
                ?></strong> 							
                                            </li>
                                            </ul>
                                            </div>
                                            </div> 
                                            <?php
                                            $chart_interval = ceil(max(0, ( $end_date - $start_date ) / ( 60 * 60 * 24 )));

                                            $chart_groupby = 'day';

                                            $total_orders = dc_vendor_frontend_prepare_chart_data($chart_data_order_count, 'post_date', 'count', $chart_interval, $start_date, $chart_groupby);
                                            $total_sales = dc_vendor_frontend_prepare_chart_data($chart_data_sales, 'post_date', 'sales', $chart_interval, $start_date, $chart_groupby);
                                            $average_sales = dc_vendor_frontend_prepare_chart_data($chart_data_avg_sales, 'post_date', 'avg_sales', $chart_interval, $start_date, $chart_groupby);
                                            $total_earned = dc_vendor_frontend_prepare_chart_data($chart_data_earnings, 'post_date', 'earnings', $chart_interval, $start_date, $chart_groupby);
                                            $vendor_total_earned = dc_vendor_frontend_prepare_chart_data($chart_data_vendor_earnings, 'post_date', 'vendor_earnings', $chart_interval, $start_date, $chart_groupby);

                                            $chart_colours = array(
                                                'total_sales' => '#FF0000',
                                                'average_sales' => '#FF9B00',
                                                'total_orders' => '#E4D8EF',
                                                'vendor_total_earned' => '#5cc488',
                                            );

                                            // Encode in json format
                                            $chart_data = json_encode(array(
                                                'total_orders' => array_values($total_orders),
                                                'total_sales' => array_map('dc_vendor_frontend_round_chart_totals', array_values($total_sales)),
                                                'average_sales' => array_map('dc_vendor_frontend_round_chart_totals', array_values($average_sales)),
                                                'vendor_total_earned' => array_map('dc_vendor_frontend_round_chart_totals', array_values($vendor_total_earned)),
                                            ));

                                            $barwidth = 60 * 60 * 24 * 1000;
                                            ?>
                                            <div class="chart-container">
                                                <div class="chart-placeholder main" style="width: 100%; height: 600px;"></div>
                                            </div>
                                            <script type="text/javascript">

                                                var main_chart;
                                                jQuery(function(){
                                                var order_data = jQuery.parseJSON('<?php echo $chart_data; ?>');
                                                var drawGraph = function(highlight) {
                                                var series = [
                                                {
                                                label: "<?php echo esc_js(__('Number of Orders', 'wcmp-vendor_frontend_report')) ?>",
                                                        data: order_data.total_orders,
                                                        color: '<?php echo $chart_colours['total_orders']; ?>',
                                                        bars: { fillColor: '<?php echo $chart_colours['total_orders']; ?>', fill: true, show: true, lineWidth: 0, barWidth: <?php echo $barwidth; ?> * 0.5, align: 'center' },
                                                        shadowSize: 0,
                                                },
                                                {
                                                label: "<?php echo esc_js(__('Total Sales', 'wcmp-vendor_frontend_report')) ?>",
                                                        data: order_data.total_sales,
                                                        yaxis: 2,
                                                        color: '<?php echo $chart_colours['total_sales']; ?>',
                                                        points: { show: true, radius: 5, lineWidth: 2, fillColor: '#fff', fill: true },
                                                        lines: { show: true, lineWidth: 2, fill: false },
                                                        shadowSize: 0,
                <?php echo dcvendor_get_currency_tooltip(); ?>
                                                },
                                                {
                                                label: "<?php echo esc_js(__('Average Order Value', 'wcmp-vendor_frontend_report')) ?>",
                                                        data: order_data.average_sales,
                                                        yaxis: 2,
                                                        color: '<?php echo $chart_colours['average_sales']; ?>',
                                                        points: { show: true, radius: 5, lineWidth: 2, fillColor: '#fff', fill: true },
                                                        lines: { show: true, lineWidth: 2, fill: false },
                                                        shadowSize: 0,
                <?php echo dcvendor_get_currency_tooltip(); ?>
                                                },
                                                {
                                                label: "<?php echo esc_js(__('Total Earnings by Vendor', 'wcmp-vendor_frontend_report')) ?>",
                                                        data: order_data.vendor_total_earned,
                                                        yaxis: 2,
                                                        color: '<?php echo $chart_colours['vendor_total_earned']; ?>',
                                                        points: { show: true, radius: 5, lineWidth: 2, fillColor: '#fff', fill: true },
                                                        lines: { show: true, lineWidth: 2, fill: false },
                                                        shadowSize: 0,
                <?php echo dcvendor_get_currency_tooltip(); ?>
                                                },
                                                ];
                                                //console.log(series);

                                                if (highlight !== 'undefined' && series[ highlight ]) {
                                                highlight_series = series[ highlight ];
                                                highlight_series.color = '#9c5d90';
                                                if (highlight_series.bars) {
                                                highlight_series.bars.fillColor = '#9c5d90';
                                                }

                                                if (highlight_series.lines) {
                                                highlight_series.lines.lineWidth = 5;
                                                }
                                                }

                                                main_chart = jQuery.plot(
                                                        jQuery('.chart-placeholder.main'),
                                                        series,
                                                {
                                                legend: {
                                                show: false
                                                },
                                                        grid: {
                                                        color: '#aaa',
                                                                borderColor: 'transparent',
                                                                borderWidth: 0,
                                                                hoverable: true
                                                        },
                                                        xaxes: [ {
                                                        color: '#aaa',
                                                                position: "bottom",
                                                                tickColor: 'transparent',
                                                                mode: "time",
                                                                timeformat: "<?php if ($chart_groupby == 'day')
                    echo '%d %b';
                else
                    echo '%b';
                ?>",
                                                                monthNames: <?php echo json_encode(array_values($wp_locale->month_abbrev)) ?>,
                                                                tickLength: 1,
                                                                minTickSize: [1, "<?php echo $chart_groupby; ?>"],
                                                                font: {
                                                                color: "#aaa"
                                                                }
                                                        } ],
                                                        yaxes: [
                                                        {
                                                        min: 0,
                                                                minTickSize: 1,
                                                                tickDecimals: 0,
                                                                color: '#d4d9dc',
                                                                font: { color: "#aaa" }
                                                        },
                                                        {
                                                        position: "right",
                                                                min: 0,
                                                                tickDecimals: 2,
                                                                alignTicksWithAxis: 1,
                                                                color: 'transparent',
                                                                font: { color: "#aaa" }
                                                        }
                                                        ],
                                                }
                                                );
                                                jQuery('.chart-placeholder').resize();
                                                }

                                                drawGraph();
                                                jQuery('.highlight_series').hover(
                                                        function() {
                                                        drawGraph(jQuery(this).data('series'));
                                                        },
                                                        function() {
                                                        drawGraph();
                                                        }
                                                );
                                                });
                                            </script>
                                        <?php } else {
                                            ?>
                                            <div>
                                                <label for="vendor_profile">
                <?php
                _e('Your account is not vendor capable.', 'wcmp-vendor_frontend_report');
                ?>
                                                </label>
                                            </div>
                <?php
            }
        }
    }

}
?>