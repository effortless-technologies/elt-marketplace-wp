<?php
class WCMP_Vendor_Frontend_Report_Ajax {

	public function __construct() {
		add_filter('wp_ajax_frontend_product_report_sort', array( $this, 'product_report_sort' ));
	    add_filter('wp_ajax_frontend_product_search', array( $this, 'search_product_data' ));
	    add_filter('wp_ajax_get_more_low_in_stock_product', array( $this, 'get_more_low_in_stock_product' ));
	    add_filter('wp_ajax_get_more_out_of_stock_product', array( $this, 'get_more_out_of_stock_product' ));
	    add_filter('wp_ajax_get_more_most_stocked_product', array( $this, 'get_more_most_stocked_product' ));
	}

	function product_report_sort() {
  	global $WCMp, $WCMp_Vendor_Frontend_Report;
  	$sort_choosen = isset($_POST['sort_choosen']) ? $_POST['sort_choosen'] : '';
  	$report_array = isset($_POST['report_array']) ? $_POST['report_array'] : array();
  	$report_bk = isset($_POST['report_bk']) ? $_POST['report_bk'] : array();
  	$max_total_sales = isset($_POST['max_total_sales']) ? $_POST['max_total_sales'] : 0;
  	$total_sales_sort = isset($_POST['total_sales_sort']) ? $_POST['total_sales_sort'] : array();
  	$admin_earning_sort = isset($_POST['admin_earning_sort']) ? $_POST['admin_earning_sort'] : array();
  	$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
  	$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
  	
  	$i = 0;
  	$max_value = 10;
  	$report_sort_arr = array();
  	
  	if( $sort_choosen == 'total_sales_desc' ) {
			arsort($total_sales_sort);
			foreach( $total_sales_sort as $product_id => $value ) {
				if( $i++ < $max_value ) {
					$report_sort_arr[$product_id]['total_sales'] = $report_bk[$product_id]['total_sales'];
					$report_sort_arr[$product_id]['admin_earning'] = $report_bk[$product_id]['admin_earning'];
				}
			}
		} else if( $sort_choosen == 'total_sales_asc' ) {
			asort($total_sales_sort);
			foreach( $total_sales_sort as $product_id => $value ) {
				if( $i++ < $max_value ) {
					$report_sort_arr[$product_id]['total_sales'] = $report_bk[$product_id]['total_sales'];
					$report_sort_arr[$product_id]['admin_earning'] = $report_bk[$product_id]['admin_earning'];
				}
			}
		} else if( $sort_choosen == 'admin_earning_desc' ) {
			arsort($admin_earning_sort);
			foreach( $admin_earning_sort as $product_id => $value ) {
				if( $i++ < $max_value ) {
					$report_sort_arr[$product_id]['total_sales'] = $report_bk[$product_id]['total_sales'];
					$report_sort_arr[$product_id]['admin_earning'] = $report_bk[$product_id]['admin_earning'];
				}
			}
		} else if( $sort_choosen == 'admin_earning_asc' ) {
			asort($admin_earning_sort);
			foreach( $admin_earning_sort as $product_id => $value ) {
				if( $i++ < $max_value ) {
					$report_sort_arr[$product_id]['total_sales'] = $report_bk[$product_id]['total_sales'];
					$report_sort_arr[$product_id]['admin_earning'] = $report_bk[$product_id]['admin_earning'];
				}
			}
		}
		
		
		$report_chart = $report_html = '';
		
		if ( sizeof( $report_sort_arr ) > 0 ) {
			foreach ( $report_sort_arr as $product_id => $sales_report ) {
				$width = ( $sales_report['total_sales'] > 0 ) ? ( round( $sales_report['total_sales'] ) / round( $max_total_sales ) ) * 100 : 0;
					$width2 = ( $sales_report['admin_earning'] > 0 ) ? ( round( $sales_report['admin_earning'] ) / round( $max_total_sales ) ) * 100 : 0;

				$product = new WC_Product($product_id);
				$product_url = admin_url('post.php?post='. $product_id .'&action=edit');
				
				$report_chart .= '<tr>
					<td width="1%"><span>' . wc_price( $sales_report['total_sales'] ) . '</span><span class="alt">' . wc_price( $sales_report['admin_earning'] ) . '</span></td>
					<td class="bars">
						<span style="width:' . esc_attr( $width ) . '%">&nbsp;</span>
						<span class="alt" style="width:' . esc_attr( $width2 ) . '%">&nbsp;</span>
						<label><a href="' . $product_url . '">' . $product->get_title() . '</a></label>
					</td></tr>';
			}
			
			$vendor_title = sprintf(__( 'Sales and Earnings [ %s ]', 'wcmp-vendor_frontend_report' ), date('F j, Y', $start_date) .' - '. date('F j, Y', $end_date));
			$month_title = __( 'Month', $WCMp->text_domain );
			$vendor_earning_title = __( 'Sales Report', $WCMp->text_domain );
			$gross_sales = __( 'Gross Sales', $WCMp->text_domain );
			$my_earnings = __( 'My Earnings', $WCMp->text_domain );
			
			$report_html = '
				<h4>'.$vendor_title.'</h4>
				<div class="bar_indecator">
					<div class="bar1">&nbsp;</div>
					<span class="">'.$gross_sales.'</span>
					<div class="bar2">&nbsp;</div>
					<span class="">'.$my_earnings.'</span>
				</div>
				<table class="bar_chart">
					<thead>
						<tr>
							<th>'.$month_title.'</th>
							<th colspan="2">'.$vendor_earning_title.'</th>
						</tr>
					</thead>
					<tbody>
						'.$report_chart.'
					</tbody>
				</table>
			';
		} else {
			$report_html = '<tr><td colspan="3">' . __( 'No product was sold in the given period.', $WCMp->text_domain ) . '</td></tr>';
		}
  	
  	echo $report_html;
  	
  	die;
  }
  
  function search_product_data() {
  	global $WCMp, $WCMp_Vendor_Frontend_Report;
  	
  	$product_id = $_POST['product_id'];
  	$start_date = $_POST['start_date'];
  	$end_date = $_POST['end_date'];
  	
  	if(isset($start_date) && empty($end_date)) {
  		$end_date = date('d-m-Y');
  	}
  	
  	if(empty($start_date)) $start_date = strtotime(date('01-m-Y',strtotime('this month')));
  	else $start_date = strtotime($start_date);
  	if(empty($end_date)) $end_date = strtotime(date('t-m-Y', strtotime('this month')));
  	else $end_date = strtotime($end_date);
  	
  	$report_chart = $report_html = '';
  	$current_user_id = get_current_user_id();
  	if($product_id) {
			$is_variation = false;
			$_product = array();
			$vendor = false;
			
			$_product = wc_get_product($product_id);
		
			if( $_product->is_type( 'variation' ) ) {
				$title = $_product->get_formatted_name();
				$is_variation = true;
			} else {
				$title = $_product->get_title();
			}
		
			if( isset( $product_id ) && !$is_variation) {
				$vendor = get_wcmp_product_vendors($product_id); 
			} else if(isset( $product_id ) && $is_variation) {
				$variatin_parent = wp_get_post_parent_id($product_id);
				$vendor = get_wcmp_product_vendors($variatin_parent);
			}
			if($vendor) {
				$orders = array();
				if( $_product->is_type( 'variable' ) ) {
					$get_children = $_product->get_children();
					if(!empty($get_children)) {
						foreach($get_children as $child) {
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
			if(!empty($orders)) {
				foreach($orders as $order_id) {
					$order = new WC_Order ( $order_id );
					$order_line_items = $order->get_items('line_item');
					
					if(!empty($order_line_items)) {
						foreach($order_line_items as $line_item) {
							if ( $line_item[ 'product_id' ] == $product_id || $line_item[ 'variation_id' ] == $product_id ) {
								if( $_product->is_type( 'variation' ) ) {
									$order_items_product_id = $line_item['product_id'];
									$order_items_variation_id = $line_item['variation_id'];
								} else {
									$order_items_product_id = $line_item['product_id'];
									$order_items_variation_id = $line_item['variation_id'];
								}
								$order_date_str = strtotime($order->get_date_created());
								if( $order_date_str > $start_date && $order_date_str < $end_date ) {
									$order_items[$i] = array(
										'order_id' => $order_id,
										'product_id' => $order_items_product_id,
										'variation_id' => $order_items_variation_id,
										'line_total' => $line_item['line_total'],
										'item_quantity' => $line_item['qty'],
										'post_date' => $order->get_date_created(),
										'multiple_product' => 0
									);
									if( count($order_line_items) > 1 ) {
										$order_items[$i]['multiple_product'] = 1;
									}
									$i++;
								}
							}
						}
					}
				}
			}
			
			$total_sales = $admin_earnings = array();
			$max_total_sales = 0;
			if( isset($order_items) && !empty($order_items) ) {
				foreach( $order_items as $order_item ) {
					if ( $order_item['line_total'] == 0 && $order_item['item_quantity'] == 0 )
						continue;
	
					// Get date
					$date 	= date( 'Ym', strtotime( $order_item['post_date'] ) );
					
					if( $order_item['variation_id'] != 0 ) {
						$variation_id = $order_item['variation_id'];
						$product_id_1 = $order_item['variation_id'];
					} else {
						$variation_id = 0;
						$product_id_1 = $order_item['product_id'];
					}
					
					if(!$vendor) {
						break;
					}
					
					$vendor_earnings = 0;
					if( $order_item['multiple_product'] == 0 ) {
						$commissions = false;
						
						$args = array(
							'post_type' =>  'dc_commission',
							'post_status' => array( 'publish', 'private' ),
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
						
						$commissions = get_posts( $args );
						
						if( !empty($commissions) ) {
							foreach($commissions as $commission) {
								$vendor_earnings = $vendor_earnings + get_post_meta($commission->ID, '_commission_amount', true);
							}
						}
						
					} else if( $order_item['multiple_product'] == 1 ) {
						
						$vendor_obj = new WCMp_Vendor(); 
						$vendor_items = $vendor_obj->get_vendor_items_from_order($order_item['order_id'], $vendor->term_id);
						
						foreach( $vendor_items as $vendor_item ) {
							if( $variation_id == 0 ) {
								if( $vendor_item['product_id'] == $product_id ) {
									$item = $vendor_item;
									break;
								}
							} else {
								if( $vendor_item['variation_id'] == $variation_id ) {
									$item = $vendor_item;
									break;
								}
							}
						}
						if( !$is_variation ) {
							$commission_obj = new WCMp_Calculate_Commission();
							$vendor_earnings = $vendor_earnings + $commission_obj->get_item_commission( $product_id, $variation_id, $item, $order_item['order_id'] );
						} else {
							$commission_obj = new WCMp_Calculate_Commission();
							$vendor_earnings = $vendor_earnings + $commission_obj->get_item_commission( $variatin_parent, $variation_id, $item, $order_item['order_id'] );
						}
					}
					
					$total_sales[$product_id] = isset($total_sales[$product_id]) ? ( $total_sales[$product_id] + $order_item['line_total'] ) : $order_item['line_total'];
					$admin_earnings[$product_id] = isset($admin_earnings[$product_id]) ? ( $admin_earnings[$product_id] + $order_item['line_total'] - $vendor_earnings ) : $order_item['line_total'] - $vendor_earnings;
					
					
					if ( $total_sales[ $product_id ] > $max_total_sales )
						$max_total_sales = $total_sales[ $product_id ];
				}
			}
			
			
			if ( sizeof( $total_sales ) > 0 ) {
				foreach ( $total_sales as $date => $sales ) {
					$width = ( $sales > 0 ) ? ( round( $sales ) / round( $max_total_sales ) ) * 100 : 0;
					$admin_earnings[$date] = $sales - $admin_earnings[$date] ;
					$width2 = ( isset( $admin_earnings[$date] ) && $admin_earnings[$date] > 0 ) ? ( round( $admin_earnings[$date] ) / round( $max_total_sales ) ) * 100 : 0;
					
					if(!isset( $admin_earnings[$date] )) $admin_earnings[$date] = 0;
					
					$report_chart .= '<tr><td>' . date_i18n( 'F', strtotime( $date . '01' ) ) . '</td>
						<td ><span>' . wc_price( $sales ) . '</span><span class="alt">' . wc_price( $admin_earnings[ $date ] ) . '</span></td>
						<td width="60%" class="bars">
							<span style="width:' . esc_attr( $width ) . '%">&nbsp;</span>
							<span class="alt" style="width:' . esc_attr( $width2 ) . '%">&nbsp;</span>
						</td></tr>';
				}
				
				$vendor_title = __( 'Sales and Earnings', $WCMp->text_domain );
				$month_title = __( 'Month', $WCMp->text_domain );
				$vendor_earning_title = __( 'Sales Report', $WCMp->text_domain );
				$gross_sales = __( 'Gross Sales', $WCMp->text_domain );
				$my_earnings = __( 'My Earnings', $WCMp->text_domain );
				
				$report_html = '
					<h4>'.$vendor_title.'</h4>
					<div class="bar_indecator">
						<div class="bar1">&nbsp;</div>
						<span class="">'.$gross_sales.'</span>
						<div class="bar2">&nbsp;</div>
						<span class="">'.$my_earnings.'</span>
					</div>
					<table class="bar_chart">
						<thead>
							<tr>
								<th>'.$month_title.'</th>
								<th colspan="2">'.$vendor_earning_title.'</th>
							</tr>
						</thead>
						<tbody>
							'.$report_chart.'
						</tbody>
					</table>
				';
			} else {
				$report_html = '<tr><td colspan="3">' . __( 'This product was not sold in the given period.', $WCMp->text_domain ) . '</td></tr>';
			}
		} else {
			$report_html =  '<tr><td colspan="3">' . __( 'Please select a product.', $WCMp->text_domain ) . '</td></tr>';
		}
  	echo  $report_html;
  	die;
  }	
	
	function get_more_low_in_stock_product() {
		global $WCMp, $WCMp_Vendor_Frontend_Report, $wpdb;
  	$user = wp_get_current_user();
  	$vendor = get_wcmp_vendor($user->ID);
  	$current_page = $_POST['current_page'];
  	$max_items = $_POST['max_items'];
  	$stock   = absint( max( get_option( 'woocommerce_notify_low_stock_amount' ), 1 ) );
		$nostock = absint( max( get_option( 'woocommerce_notify_no_stock_amount' ), 0 ) );
			
		$query_from = apply_filters( 'wcmp_report_low_in_stock_query_from', "FROM {$wpdb->posts} as posts
			INNER JOIN {$wpdb->term_relationships}  AS term_relationships  ON (posts.ID = term_relationships.object_id)
			INNER JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id
			INNER JOIN {$wpdb->postmeta} AS postmeta2 ON posts.ID = postmeta2.post_id
			WHERE 1=1
			AND term_relationships.term_taxonomy_id IN ({$vendor->term_id}) 
			AND posts.post_type IN ( 'product', 'product_variation' )
			AND posts.post_status = 'publish'
			AND postmeta2.meta_key = '_manage_stock' AND postmeta2.meta_value = 'yes'
			AND postmeta.meta_key = '_stock' AND CAST(postmeta.meta_value AS SIGNED) <= '{$stock}'
			AND postmeta.meta_key = '_stock' AND CAST(postmeta.meta_value AS SIGNED) > '{$nostock}'
		" );
		$current_page = $current_page;
		$per_page = 5;
		$items     = $wpdb->get_results( $wpdb->prepare( "SELECT posts.ID as id, posts.post_parent as parent {$query_from} GROUP BY posts.ID ORDER BY posts.post_title DESC LIMIT %d, %d;", ( $current_page - 1 ) * $per_page, $per_page ), ARRAY_A );
  	$more_button = true;
		if(count($items) == $max_items) $more_button = false;
			
		$WCMp_Vendor_Frontend_Report->template->get_template( 'reports/stock_items.php', array('items' => $items) );
		die;
	}
	
	function get_more_out_of_stock_product() {
		global $WCMp, $WCMp_Vendor_Frontend_Report, $wpdb;
  	$user = wp_get_current_user();
  	$vendor = get_wcmp_vendor($user->ID);
  	$current_page = $_POST['current_page'];
  	$max_items = $_POST['max_items'];
  	$stock   = absint( max( get_option( 'woocommerce_notify_low_stock_amount' ), 1 ) );
		$nostock = absint( max( get_option( 'woocommerce_notify_no_stock_amount' ), 0 ) );
			
		$query_from = apply_filters( 'wcmp_report_out_of_stock_query_from', "FROM {$wpdb->posts} as posts
			INNER JOIN {$wpdb->term_relationships}  AS term_relationships  ON (posts.ID = term_relationships.object_id)
			INNER JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id
			INNER JOIN {$wpdb->postmeta} AS postmeta2 ON posts.ID = postmeta2.post_id
			WHERE 1=1
			AND term_relationships.term_taxonomy_id IN ({$vendor->term_id}) 
			AND posts.post_type IN ( 'product', 'product_variation' )
			AND posts.post_status = 'publish'
			AND postmeta2.meta_key = '_manage_stock' AND postmeta2.meta_value = 'yes'
			AND postmeta.meta_key = '_stock' AND CAST(postmeta.meta_value AS SIGNED) <= '{$stock}'
		" );
		$current_page = $current_page;
		$per_page = 5;
		$items     = $wpdb->get_results( $wpdb->prepare( "SELECT posts.ID as id, posts.post_parent as parent {$query_from} GROUP BY posts.ID ORDER BY posts.post_title DESC LIMIT %d, %d;", ( $current_page - 1 ) * $per_page, $per_page ), ARRAY_A );
  	$more_button = true;
		if(count($items) == $max_items) $more_button = false;
			
		$WCMp_Vendor_Frontend_Report->template->get_template( 'reports/stock_items.php', array('items' => $items) );
		die;
	}
	
	function get_more_most_stocked_product() {
		global $WCMp, $WCMp_Vendor_Frontend_Report, $wpdb;
  	$user = wp_get_current_user();
  	$vendor = get_wcmp_vendor($user->ID);
  	$current_page = $_POST['current_page'];
  	$max_items = $_POST['max_items'];
  	$stock   = absint( max( get_option( 'woocommerce_notify_low_stock_amount' ), 1 ) );
		$nostock = absint( max( get_option( 'woocommerce_notify_no_stock_amount' ), 0 ) );
			
		$query_from = apply_filters( 'wcmp_report_out_of_stock_query_from', "FROM {$wpdb->posts} as posts
			INNER JOIN {$wpdb->term_relationships}  AS term_relationships  ON (posts.ID = term_relationships.object_id)
			INNER JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id
			INNER JOIN {$wpdb->postmeta} AS postmeta2 ON posts.ID = postmeta2.post_id
			WHERE 1=1
			AND term_relationships.term_taxonomy_id IN ({$vendor->term_id}) 
			AND posts.post_type IN ( 'product', 'product_variation' )
			AND posts.post_status = 'publish'
			AND postmeta2.meta_key = '_manage_stock' AND postmeta2.meta_value = 'yes'
			AND postmeta.meta_key = '_stock' AND CAST(postmeta.meta_value AS SIGNED) > '{$stock}'
		" );
		$current_page = $current_page;
		$per_page = 5;
		$items     = $wpdb->get_results( $wpdb->prepare( "SELECT posts.ID as id, posts.post_parent as parent {$query_from} GROUP BY posts.ID ORDER BY posts.post_title DESC LIMIT %d, %d;", ( $current_page - 1 ) * $per_page, $per_page ), ARRAY_A );
  	$more_button = true;
		if(count($items) == $max_items) $more_button = false;
			
		$WCMp_Vendor_Frontend_Report->template->get_template( 'reports/stock_items.php', array('items' => $items) );
		die;
	}
	

}