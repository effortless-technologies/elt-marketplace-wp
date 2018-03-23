<?php

class WCMP_Vendor_Report_Shortcode_Product_Overview {

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
        $current_user_id = get_current_user_id();
        $give_tax_to_vendor = get_wcmp_vendor_settings('give_tax','payment');
        $give_shipping_to_vendor = get_wcmp_vendor_settings('give_shipping','payment');
        if (function_exists('is_user_wcmp_vendor')) {
            if (is_user_wcmp_vendor($current_user_id)) {
                if (isset($_GET['product_overview_from_date'])) {
                    $start_date = strtotime($_GET['product_overview_from_date']);
                } else {
                    $start_date = strtotime(date('Ymd', strtotime(date('Ym', current_time('timestamp')) . '01')));
                }
                if (isset($_GET['product_overview_to_date'])) {
                    $end_date = strtotime($_GET['product_overview_to_date']);
                } else {
                    $end_date = strtotime('+0 day', current_time('timestamp'));
                }
                $products = $product_ids = array();
                $vendor = false;
                $vendor = get_wcmp_vendor($current_user_id);
                if ($vendor) {
                    $products = $vendor->get_products();
                    foreach ($products as $product) {
                        $product_ids[] = $product->ID;
                    }
                } else {
                    $args = array(
                        'posts_per_page' => -1,
                        'offset' => 0,
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'post_type' => 'product',
                        'post_status' => 'publish'
                    );
                    $products = get_posts($args);
                    foreach ($products as $product) {
                        $product_ids[] = $product->ID;
                    }
                }
                $total_sales = $admin_earnings = array();
                $max_total_sales = $index = 0;
                $product_report = $report_bk = array();
                if (isset($product_ids) && !empty($product_ids)) {
                    foreach ($product_ids as $product_id) {
                        $is_variation = false;
                        $_product = array();
                        $vendor = false;
                        $_product = wc_get_product($product_id);
                        if ($_product->is_type('variation')) {
                            $title = $_product->get_formatted_name();
                            $is_variation = true;
                        } else {
                            $title = $_product->get_title();
                        }
                        if (isset($product_id) && !$is_variation) {
                            $vendor = get_wcmp_product_vendors($product_id);
                        } else if (isset($product_id) && $is_variation) {
                            $variation_parent = wp_get_post_parent_id($product_id);
                            $vendor = get_wcmp_product_vendors($variation_parent);
                        }
                        if ($vendor) {
                            $orders = array();
                            if ($_product->is_type('variable')) {
                                $get_children = $_product->get_children();
                                if (!empty($get_children)) {
                                    foreach ($get_children as $child) {
                                        $orders = array_merge($orders, $vendor->get_vendor_orders_by_product($vendor->term_id, $child));
                                    }
                                    $orders = array_unique($orders);
                                }
                            } else {
                                $orders = array_unique($vendor->get_vendor_orders_by_product($vendor->term_id, $product_id));
                            }
                        }
                        $order_items = array();
                        $i = 0;
                        if (!empty($orders)) {
                            foreach ($orders as $order_id) {
                                $order = new WC_Order($order_id);
                                $order_line_items = $order->get_items('line_item');
                                $shipping_items = $order->get_items('shipping');
                                if (!empty($order_line_items)) {
                                    foreach ($order_line_items as $line_item) {
                                        if ($line_item['product_id'] == $product_id) {
                                            if ($_product->is_type('variation')) {
                                                $order_items_product_id = $line_item['product_id'];
                                                $order_items_variation_id = $line_item['variation_id'];
                                            } else {
                                                $order_items_product_id = $line_item['product_id'];
                                                $order_items_variation_id = $line_item['variation_id'];
                                            }
                                            $order_date_str = strtotime($order->get_date_created());
                                            if (!empty($start_date) && !empty($end_date)) {
                                                if ($order_date_str > $start_date && $order_date_str < $end_date) {
                                                    $order_items[$i] = array(
                                                        'order_id' => $order_id,
                                                        'product_id' => $order_items_product_id,
                                                        'variation_id' => $order_items_variation_id,
                                                        'line_total' => $line_item['line_total'],
                                                        'item_quantity' => $line_item['qty'],
                                                        'post_date' => $order->get_date_created(),
                                                        'multiple_product' => 0
                                                    );
                                                }
                                            } else {
                                                $order_items[$i] = array(
                                                    'order_id' => $order_id,
                                                    'product_id' => $order_items_product_id,
                                                    'variation_id' => $order_items_variation_id,
                                                    'line_total' => $line_item['line_total'],
                                                    'item_quantity' => $line_item['qty'],
                                                    'post_date' => $order->get_date_created(),
                                                    'multiple_product' => 0
                                                );
                                            }
                                            if (count($order_line_items) > 1) {
                                                $order_items[$i]['multiple_product'] = 1;
                                            }
                                            $i++;
                                        }
                                    }
                                }
                            }
                        }
                        if (isset($order_items) && !empty($order_items)) {
                            foreach ($order_items as $order_item) {
                                if ($order_item['line_total'] == 0 && $order_item['item_quantity'] == 0)
                                    continue;

                                if ($order_item['variation_id'] != 0) {
                                    $variation_id = $order_item['variation_id'];
                                    $product_id_1 = $order_item['variation_id'];
                                } else {
                                    $variation_id = 0;
                                    $product_id_1 = $order_item['product_id'];
                                }

                                $vendor = get_wcmp_product_vendors($product_id);
                                if (!$vendor) {
                                    break;
                                }

                                $commissions = false;
                                $vendor_earnings = 0;
                                if ($order_item['multiple_product'] == 0) {

                                    $args = array(
                                        'post_type' => 'dc_commission',
                                        'post_status' => array('publish', 'private'),
                                        'posts_per_page' => -1,
                                        'meta_query' => array(
                                            array(
                                                'key' => '_commission_vendor',
                                                'value' => absint($vendor->term_id),
                                                'compare' => '='
                                            ),
                                            array(
                                                'key' => '_commission_order_id',
                                                'value' => absint($order_item['order_id']),
                                                'compare' => '='
                                            ),
                                            array(
                                                'key' => '_commission_product',
                                                'value' => absint($product_id_1),
                                                'compare' => 'LIKE'
                                            )
                                        )
                                    );

                                    $commissions = get_posts($args);

                                    if (!empty($commissions)) {
                                        foreach ($commissions as $commission) {
                                            $vendor_earnings = $vendor_earnings + get_post_meta($commission->ID, '_commission_amount', true);
                                        }
                                    }
                                } else if ($order_item['multiple_product'] == 1) {

                                    $vendor_obj = new WCMp_Vendor();
                                    $vendor_items = $vendor_obj->get_vendor_items_from_order($order_item['order_id'], $vendor->term_id);
                                    foreach ($vendor_items as $vendor_item) {
                                        if ($variation_id == 0) {
                                            if ($vendor_item['product_id'] == $product_id) {
                                                $item = $vendor_item;
                                                break;
                                            }
                                        } else {
                                            if ($vendor_item['product_id'] == $product_id && $vendor_item['variation_id'] == $variation_id) {
                                                $item = $vendor_item;
                                                break;
                                            }
                                        }
                                    }
                                    $commission_obj = new WCMp_Calculate_Commission();
                                    $vendor_earnings = $commission_obj->get_item_commission($product_id, $variation_id, $item, $order_item['order_id']);
                                }

                                if ($vendor_earnings <= 0) {
                                    continue;
                                }

                                $total_sales[$product_id] = isset($total_sales[$product_id]) ? ( $total_sales[$product_id] + $order_item['line_total'] ) : $order_item['line_total'];
                                if (is_user_wcmp_vendor($current_user_id)) {
                                    $admin_earnings[$product_id] = $vendor_earnings;
                                } else {
                                    $admin_earnings[$product_id] = isset($admin_earnings[$product_id]) ? ( $admin_earnings[$product_id] + $order_item['line_total'] - $vendor_earnings ) : $order_item['line_total'] - $vendor_earnings;
                                }
                                if ($total_sales[$product_id] > $max_total_sales)
                                    $max_total_sales = $total_sales[$product_id];
                            }
                        }

                        if (!empty($total_sales[$product_id]) && !empty($admin_earnings[$product_id])) {
                            $product_report[$index]['product_id'] = $product_id;
                            $product_report[$index]['total_sales'] = $total_sales[$product_id];
                            $product_report[$index++]['admin_earning'] = $admin_earnings[$product_id];

                            $report_bk[$product_id]['total_sales'] = $total_sales[$product_id];
                            $report_bk[$product_id]['admin_earning'] = $admin_earnings[$product_id];
                        }
                    }

                    $i = 0;
                    $max_value = 10;
                    $report_sort_arr = array();
                    $total_sales_sort = $admin_earning_sort = array();
                    if (!empty($product_report) && !empty($report_bk)) {
                        $total_sales_sort = wp_list_pluck($product_report, 'total_sales', 'product_id');
                        $admin_earning_sort = wp_list_pluck($product_report, 'admin_earning', 'product_id');

                        foreach ($total_sales_sort as $key => $value) {
                            $total_sales_sort_arr[$key]['total_sales'] = $report_bk[$key]['total_sales'];
                            $total_sales_sort_arr[$key]['admin_earning'] = $report_bk[$key]['admin_earning'];
                        }

                        arsort($total_sales_sort);
                        foreach ($total_sales_sort as $product_id => $value) {
                            if ($i++ < $max_value) {
                                $report_sort_arr[$product_id]['total_sales'] = $report_bk[$product_id]['total_sales'];
                                $report_sort_arr[$product_id]['admin_earning'] = $report_bk[$product_id]['admin_earning'];
                            }
                        }
                    }
                    wp_localize_script('dc_product_overview_js', 'wcmp_report_product_overview', array('product_report' => $product_report,
                        'report_bk' => $report_bk,
                        'total_sales_sort' => $total_sales_sort,
                        'admin_earning_sort' => $admin_earning_sort,
                        'max_total_sales' => $max_total_sales,
                        'start_date' => $start_date,
                        'end_date' => $end_date
                    ));
                    $report_chart = $report_html = '';
                    if (sizeof($report_sort_arr) > 0) {
                        foreach ($report_sort_arr as $product_id => $sales_report) {
                            $width = ( $sales_report['total_sales'] > 0 ) ? ( round($sales_report['total_sales']) / round($max_total_sales) ) * 100 : 0;
                            $width2 = ( $sales_report['admin_earning'] > 0 ) ? ( round($sales_report['admin_earning']) / round($max_total_sales) ) * 100 : 0;

                            $product = new WC_Product($product_id);
                            $product_url = get_permalink($product_id); //admin_url('post.php?post='. $product_id .'&action=edit');

                            $report_chart .= '<tr>
								<td width="1%"><span>' . wc_price($sales_report['total_sales']) . '</span><span class="alt">' . wc_price($sales_report['admin_earning']) . '</span></td>
								<td class="bars">
									<span style="width:' . esc_attr($width) . '%">&nbsp;</span>
									<span class="alt" style="width:' . esc_attr($width2) . '%">&nbsp;</span>
									<label><a href="' . $product_url . '">' . $product->get_title() . '</a></label>
								</td></tr>';
                        }

                        $vendor_title = sprintf(__('Sales and Earnings [ %s ]', 'wcmp-vendor_frontend_report'), date('F j, Y', $start_date) . ' - ' . date('F j, Y', $end_date));
                        $month_title = __('Month', 'wcmp-vendor_frontend_report');
                        $vendor_earning_title = __('Sales Report', 'wcmp-vendor_frontend_report');
                        $gross_sales = __('Gross Sales', 'wcmp-vendor_frontend_report');
                        $my_earnings = __('My Earnings', 'wcmp-vendor_frontend_report');

                        $report_html = '
							<h4>' . $vendor_title . '</h4>
							<div class="bar_indecator">
								<div class="bar1">&nbsp;</div>
								<span class="">' . $gross_sales . '</span>
								<div class="bar2">&nbsp;</div>
								<span class="">' . $my_earnings . '</span>
							</div>
							<table class="bar_chart">
								<thead>
									<tr>
										<th>' . $month_title . '</th>
										<th colspan="2">' . $vendor_earning_title . '</th>
									</tr>
								</thead>
								<tbody>
									' . $report_chart . '
								</tbody>
							</table>
						';
                    } else {
                        $report_html = '<tr><td colspan="3">' . __('No product was sold in the given period.', 'wcmp-vendor_frontend_report') . '</td></tr>';
                    }
                } else {
                    $report_html = '<tr><td colspan="3">' . __('Your store has no products.', 'wcmp-vendor_frontend_report') . '</td></tr>';
                }
                $WCMp_Vendor_Frontend_Report->template->get_template('reports/sales_by_product_overview.php', array('report_html' => $report_html, 'start_date' => isset($_GET['product_overview_from_date']) ? $_GET['product_overview_from_date'] : '', 'end_date' => isset($_GET['product_overview_to_date']) ? $_GET['product_overview_to_date'] : ''));
            } else {
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