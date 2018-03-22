<?php
class WCMP_Vendor_Report_Shortcode_Transaction_Overview {

	public function __construct() {

	}

	/**
	 * Output the demo shortcode.
	 *
	 * @access public
	 * @param array $atts
	 * @return void
	 */
	public static function output( $attr ) {
		global $WCMp_Vendor_Frontend_Report, $WCMp, $woocommerce, $wp_locale;
		
		$user_id = get_current_user_id();
		
		if(function_exists( 'is_user_wcmp_vendor' ) ) {
			if(is_user_wcmp_vendor($user_id)) {
				
							
				//wp_enqueue_style( 'woocommerce_admin_print_reports_styles', WC()->plugin_url() . '/assets/css/reports-print.css', array(), WC_VERSION, 'print' );
				//wp_enqueue_style('vendor_frontend_css',  $WCMp_Vendor_Frontend_Report->plugin_url.'assets/frontend/css/frontend.css', array(), $WCMp_Vendor_Frontend_Report->version);
				//
				//$frontend_script_path = $WCMp_Vendor_Frontend_Report->plugin_url . 'assets/frontend/js/';
				//$suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
				
				//wp_enqueue_script('jquery-ui-datepicker');
				//wp_register_script('jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip' . $suffix . '.js', array( 'jquery' ), WC_VERSION, true );
				//wp_enqueue_script( 'flot', WC()->plugin_url() . '/assets/js/admin/jquery.flot' . $suffix . '.js', array( 'jquery' ), WC_VERSION );
				//wp_enqueue_script( 'flot-resize', WC()->plugin_url() . '/assets/js/admin/jquery.flot.resize.js', array( 'jquery', 'flot' ), WC_VERSION );
				//wp_enqueue_script( 'flot-time', WC()->plugin_url() . '/assets/js/admin/jquery.flot.time' . $suffix . '.js', array( 'jquery', 'flot' ), WC_VERSION );
				//wp_enqueue_script( 'flot-pie', WC()->plugin_url() . '/assets/js/admin/jquery.flot.pie' . $suffix . '.js', array( 'jquery', 'flot' ), WC_VERSION );
				//wp_enqueue_script( 'flot-stack', WC()->plugin_url() . '/assets/js/admin/jquery.flot.stack' . $suffix . '.js', array( 'jquery', 'flot' ), WC_VERSION );
				//wp_enqueue_script( 'frontend_trans_report_js', $frontend_script_path.'transaction_report.js', array('jquery','jquery-ui-datepicker'), $WCMp_Vendor_Frontend_Report->version, true);
				
				$vendor_id = get_current_user_id();
				
				$vendor = get_wcmp_vendor($user_id);
				
				$chart_data = array();
				
				if(!isset($_GET['transaction_from_date'])) $_GET['transaction_from_date'] = '';
				if(!isset($_GET['transaction_to_date'])) $_GET['transaction_to_date'] = '';
				?>
				<div class="transaction_overview_settings">
					<form method="get" action="#wcmp_report_transaction" id="wcmp_report_transaction" class="" style="margin-bottom: 10px; float: left; display: block;">
						<table  style="float: left; width: 57%;">
							<tbody>
								<tr>
									<td><input id="wcmp_frontend_transaction_from_date" name="transaction_from_date" placeholder="<?php _e('From', 'wcmp-vendor_frontend_report' ); ?>" value ="<?php echo $_GET['transaction_from_date'];?>"/></td>
									<td><input id="wcmp_frontend_transaction_to_date" name="transaction_to_date" placeholder="<?php _e('To', 'wcmp-vendor_frontend_report' ); ?>" value ="<?php echo $_GET['transaction_to_date'];?>"/></td>
									<td><p class="submit"><input type="submit" name="transaction_submit" id="submit" class="all_new_btn button button-primary" value="<?php _e('Filter', 'wcmp-vendor_frontend_report' ); ?>"></p></td>
								</tr>
							</tbody>
						</table>
					</form>
					<form method="post" name="export_transaction_report_orders_from">
						<input type="submit"
							 class="all_new_btn button button-primary"
							 style=" float: right;  margin-top: 17px;"
							 name="export_transaction_report_orders"
							 value="<?php _e( 'Export CSV',  'wcmp-vendor_frontend_report'); ?>" 
						/>
					</form>
				</div>
				<?php
				
				if($_GET['transaction_from_date']) $start_date = strtotime($_GET['transaction_from_date']); 
				else $start_date =  strtotime( date('Ymd', strtotime( date('Ym', current_time('timestamp') ) . '01' ) ) );
				
				if($_GET['transaction_to_date']) $end_date = strtotime($_GET['transaction_to_date']);
				else $end_date = strtotime( date('Ymd', current_time( 'timestamp' ) ) );
				
				$total_transaction_amount = $toatl_transfer_charge = $total_order_countt = $total_transaction_count = 0;
				
				for( $date = $start_date; $date <=  $end_date; $date = strtotime( '+1 day', $date ) ) {
					
					$transaction_amount = $transfer_charge = $order_count = $transaction_count = 0;
					
					$transactions = apply_filters('wcmp_filter_transactions_report_overview' , $WCMp->transaction->get_transactions($vendor->term_id, date('j-n-Y', $date)));
				
					if ( !empty($transactions) ) {
						foreach($transactions as $transaction) {
							$transaction_id = 
							$transaction_amount += $transaction['amount'];
							$transfer_charge += $transaction['transfer_charge'];
							if(!empty($transaction['commission_details'])) {
								foreach($transaction['commission_details'] as $order_id) {
									$order_count++;
									$total_order_countt++;
								}
							}
							$transaction_count++;
							$total_transaction_count++;
							$total_transaction_amount += $transaction_amount;
							$toatl_transfer_charge += $transfer_charge;
						}
					}
					
					if ( $transaction_count > 0 ) $avg_transactions = $transaction_amount / $transaction_count;
					else $avg_transactions = 0;
					
					$chart_data_order_count[] = wcmpArrayToObject(array('post_date' => date("Y-m-d H:i:s",  $date ), 'order_count' => $order_count));
					$chart_data_transaction_amount[] = wcmpArrayToObject(array('post_date' => date("Y-m-d H:i:s",  $date ), 'transaction_amount' => $transaction_amount));
					$chart_data_avg_transaction_amount[] = wcmpArrayToObject(array('post_date' => date("Y-m-d H:i:s",  $date ), 'avg_transactions' => $avg_transactions));
					$chart_data_transfer_charge[] = wcmpArrayToObject(array('post_date' => date("Y-m-d H:i:s",  $date ), 'transfer_charge' => $transfer_charge));
					$chart_data_transaction_count[] = wcmpArrayToObject(array('post_date' => date("Y-m-d H:i:s",  $date ), 'transaction_count' => $transaction_count));
				}
				if ( $total_transaction_count > 0 ) $avg_transaction_amount = $total_transaction_amount / $total_transaction_count;
				else $avg_transaction_amount = 0;
				?>
				<div class="dc-reports-div" style="clear: both; display: block;">
				<label><?php echo sprintf(__( 'Your Report [ %s ]', 'wcmp-vendor_frontend_report' ), date('F j, Y', $start_date) .' - '. date('F j, Y', $end_date)); ?></label>
					<div class="chart-sidebar">
						<ul class="chart-legend">
							<li style="border-color: #FF0000" class="highlight_series tips" data-series="2">
								<i class="fa  fa-circle "></i><label><?php _e( 'Gross Credit in this Period', 'wcmp-vendor_frontend_report' ); ?> </label> <strong><span class="amount"><?php if ( $total_transaction_amount > 0 ) echo wc_price( $total_transaction_amount ); else _e( 'n/a', 'wcmp-vendor_frontend_report' ); ?></span></strong> 						
							</li>
							<li style="border-color: #FF9B00" class="highlight_series " data-series="3" data-tip="">
								<i class="fa  fa-circle "></i><label><?php _e( 'Average Daily Credit', 'wcmp-vendor_frontend_report' ); ?> </label> <strong><span class="amount"><?php if ( $avg_transaction_amount > 0 ) echo wc_price( $avg_transaction_amount ); else _e( 'n/a', 'wcmp-vendor_frontend_report' ); ?></span></strong> 							
							</li>
							<li style="border-color: #01CCFF" class="highlight_series " data-series="4" data-tip="">
								 <i class="fa  fa-circle "></i><label><?php _e( 'Debit for transfer charge', 'wcmp-vendor_frontend_report' ); ?> </label> <strong><span class="amount"><?php if ( $toatl_transfer_charge > 0 ) echo wc_price( $toatl_transfer_charge ); else _e( 'n/a', 'wcmp-vendor_frontend_report' ); ?>	</span></strong>
							</li>
							<li style="border-color: #E4D8EF" class="highlight_series " data-series="1" data-tip="">
								<i class="fa  fa-circle "></i><label><?php _e( 'Number of transactions', 'wcmp-vendor_frontend_report' ); ?> </label>	<strong><span class="amount"><?php if ( $total_transaction_count > 0 ) echo  $total_transaction_count; else _e( 'n/a', 'wcmp-vendor_frontend_report' ); ?></span></strong> 							
							</li>
							<li style="border-color: #5cc488" class="highlight_series " data-series="0" data-tip="">
								 <i class="fa  fa-circle "></i><label><?php _e( 'Number of units ordered', 'wcmp-vendor_frontend_report' ); ?> </label> <strong><span class="amount"><?php if ( $total_order_countt > 0 ) echo $total_order_countt; else _e( 'n/a', 'wcmp-vendor_frontend_report' ); ?></span></strong>							
							</li>
						</ul>
					</div>
				</div> 
					<?php 
					$chart_interval = ceil( max( 0, ( $end_date - $start_date ) / ( 60 * 60 * 24 ) ) );
					
					$chart_groupby = 'day';
					
					$total_order_count         = dc_vendor_frontend_prepare_chart_data( $chart_data_order_count, 'post_date', 'order_count', $chart_interval, $start_date, $chart_groupby );
					$total_transaction_amount  = dc_vendor_frontend_prepare_chart_data( $chart_data_transaction_amount, 'post_date', 'transaction_amount', $chart_interval, $start_date, $chart_groupby );
					$total_avg_transactions    = dc_vendor_frontend_prepare_chart_data( $chart_data_avg_transaction_amount, 'post_date', 'avg_transactions', $chart_interval, $start_date, $chart_groupby );
					$total_transfer_charge   	 = dc_vendor_frontend_prepare_chart_data( $chart_data_transfer_charge, 'post_date', 'transfer_charge', $chart_interval, $start_date, $chart_groupby );
					$total_transaction_count   = dc_vendor_frontend_prepare_chart_data( $chart_data_transaction_count, 'post_date', 'transaction_count', $chart_interval, $start_date, $chart_groupby );
			
					$chart_colours = array(
						'total_transaction_amount'  => '#FF0000',
						'total_avg_transactions'    => '#FF9B00',
						'total_transfer_charge'   	=> '#01CCFF',
						'total_transaction_count'   => '#E4D8EF',
						'total_order_count'        	=> '#5cc488',
					);
					
					// Encode in json format
					$chart_data = json_encode( array(
						'total_transaction_amount'      => array_values( $total_transaction_amount ),
						'total_order_count'     => array_map( 'dc_vendor_frontend_round_chart_totals' , array_values( $total_order_count ) ),
						'total_avg_transactions' => array_map( 'dc_vendor_frontend_round_chart_totals' , array_values( $total_avg_transactions ) ),
						'total_transfer_charge'  => array_map( 'dc_vendor_frontend_round_chart_totals' ,  array_values( $total_transfer_charge ) ),
						'total_transaction_count'    => array_map( 'dc_vendor_frontend_round_chart_totals' , array_values( $total_transaction_count ) ),
					) );
					
					$barwidth = 60 * 60 * 24 * 1000;
					?>
					<div class="chart-container-transaction">
						<div class="chart-placeholder-transaction main" style="width: 100%; height: 600px;"></div>
					</div>
					<script type="text/javascript">
			
						var main_chart;
			
						jQuery(function(){
							var order_data = jQuery.parseJSON( '<?php echo $chart_data; ?>' );
							var drawGraph = function( highlight ) {
								var series = [
									{
										label: "<?php echo esc_js( __( 'Number of Orders', 'wcmp-vendor_frontend_report' ) ) ?>",
										data: order_data.total_order_count,
										color: '<?php echo $chart_colours['total_order_count']; ?>',
										bars: { fillColor: '<?php echo $chart_colours['total_order_count']; ?>', fill: true, show: true, lineWidth: 0, barWidth: <?php echo $barwidth; ?> * 0.5, align: 'center' },
										shadowSize: 0,
										hoverable: false
									},
									{
										label: "<?php echo esc_js( __( 'Number of Transactions', 'wcmp-vendor_frontend_report' ) ) ?>",
										data: order_data.total_transaction_count,
										color: '<?php echo $chart_colours['total_transaction_count']; ?>',
										bars: { fillColor: '<?php echo $chart_colours['total_transaction_count']; ?>', fill: true, show: true, lineWidth: 0, barWidth: <?php echo $barwidth; ?> * 0.5, align: 'center' },
										shadowSize: 0,
									},
									
									
									{
										label: "<?php echo esc_js( __( 'Total transaction Amount', 'wcmp-vendor_frontend_report' ) ) ?>",
										data: order_data.total_transaction_amount,
										yaxis: 2,
										color: '<?php echo $chart_colours['total_transaction_amount']; ?>',
										points: { show: true, radius: 5, lineWidth: 2, fillColor: '#fff', fill: true },
										lines: { show: true, lineWidth: 2, fill: false },
										shadowSize: 0,
										<?php echo dcvendor_get_currency_tooltip(); ?>
									},	
									
									{
										label: "<?php echo esc_js( __( 'Average transaction Amount', 'wcmp-vendor_frontend_report' ) ) ?>",
										data: order_data.total_avg_transactions ,
										yaxis: 2,
										color: '<?php echo $chart_colours['total_avg_transactions']; ?>',
										points: { show: true, radius: 5, lineWidth: 2, fillColor: '#fff', fill: true },
										lines: { show: true, lineWidth: 2, fill: false },
										shadowSize: 0,
										<?php echo dcvendor_get_currency_tooltip(); ?>
									},
									
									{
										label: "<?php echo esc_js( __( 'Total Transfer Charge', 'wcmp-vendor_frontend_report' ) ) ?>",
										data: order_data.total_transfer_charge,
										yaxis: 2,
										color: '<?php echo $chart_colours['total_transfer_charge']; ?>',
										points: { show: true, radius: 5, lineWidth: 2, fillColor: '#fff', fill: true },
										lines: { show: true, lineWidth: 2, fill: false },
										shadowSize: 0,
										prepend_tooltip: "<?php echo get_woocommerce_currency_symbol(); ?>"
									},
									
									
								];
								
								//console.log(series);
			
								if ( highlight !== 'undefined' && series[ highlight ] ) {
									highlight_series = series[ highlight ];
			
									highlight_series.color = '#9c5d90';
			
									if ( highlight_series.bars ) {
										highlight_series.bars.fillColor = '#9c5d90';
									}
			
									if ( highlight_series.lines ) {
										highlight_series.lines.lineWidth = 5;
									}
								}
			
								main_chart = jQuery.plot(
									jQuery('.chart-placeholder-transaction.main'),
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
											timeformat: "<?php if ( $chart_groupby == 'day' ) echo '%d %b'; else echo '%b'; ?>",
											monthNames: <?php echo json_encode( array_values( $wp_locale->month_abbrev ) ) ?>,
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
			
								jQuery('.chart-placeholder-transaction').resize();
							}
			
							drawGraph();
							
							jQuery('.highlight_series').hover(
								function() {
									drawGraph( jQuery(this).data('series') );
								},
								function() {
									drawGraph();
								}
							);
							
						});
					</script>
				<?php
			}
		}
	}
}
?>
						