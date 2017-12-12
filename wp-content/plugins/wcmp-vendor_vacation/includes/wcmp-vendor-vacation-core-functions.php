<?php
/**
 * Error notices for wc-marketplace plugin not found
 */
if(!function_exists('wcmp_vendor_vacation_wcmp_inactive_notice')) {
	function wcmp_vendor_vacation_wcmp_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCMp Vendor Vacation is inactive.%s The %sWC Marketplace%s must be active for the WCMPS Advance Shipping to work. Please %sinstall & activate WC Marketplace%s', WCMPS_ADVANCE_SHIPPING_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/dc-woocommerce-multi-vendor/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=wc+marketplace' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('get_vendor_vacation_settings')) {
  function get_vendor_vacation_settings($name = '', $tab = '') {
    if(empty($tab) && empty($name)) return '';
    if(empty($tab)) return get_option($name);
    if(empty($name)) return get_option("dc_{$tab}_settings_name");
    $settings = get_option("dc_{$tab}_settings_name");
    if(!isset($settings[$name])) return '';
    return $settings[$name];
  }
}
if(!function_exists('wcmp_vacation_showMonth')) {
	function wcmp_vacation_showMonth($month = null, $year = null, $db_selected_dates = false) {
		$wday = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat','Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat','Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat','Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat','Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat','Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat','Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
		$calendar = '';
		if($month == null || $year == null) {
			$month = date('n');
			$year = date('Y');
		}
		$date = mktime(12, 0, 0, $month, 1, $year);
		$daysInMonth = date("t", $date);
		$offset = date("w", $date);
		$rows = 1;
		$prev_month = $month - 1;
		$prev_year = $year;
		if ($month == 1) {
			$prev_month = 12;
			$prev_year = $year-1;
		}
	
		$next_month = $month + 1;
		$next_year = $year;
		if ($month == 12) {
			$next_month = 1;
			$next_year = $year + 1;
		}
		$calendar .= "<div class='panel-calendar'><div class='panel-heading text-center'>";
		$calendar .= "";
		$calendar .= "<div class='row'><div class='col-md-12 col-xs-4'><strong>" . date("F", $date) . " <span class='month_year_label'>".date('Y', $date)."</span></strong></div>";
		$calendar .= "</div></div>"; 
		$calendar .= "<table class='table table-bordered'>";
		$calendar .= "<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>";
		$calendar .= "<tr>";
		for($i = 1; $i <= $offset; $i++) {
			$calendar .= "<td></td>";
		}
		for($day = 1; $day <= $daysInMonth; $day++) {
			if( ($day + $offset - 1) % 7 == 0 && $day != 1) {
				$calendar .= "</tr><tr>";
				$rows++;
			}
			$date_in_td = $day.'/'.$month.'/'.$year;
			if( $db_selected_dates && in_array($date_in_td, $db_selected_dates) ) {
				$calendar .= "<td class='include_date day_$day month_$month year_$year  mon_yr_{$month}_{$year} wday_".$wday[($day + $offset - 1)]."' data-date='$day/$month/$year' data-day='$day' data-month='$month' data-year='$year'>" . $day . "</td>";
			} else {
				$calendar .= "<td class='day_$day month_$month year_$year  mon_yr_{$month}_{$year} wday_".$wday[($day + $offset - 1)]."' data-date='$day/$month/$year' data-day='$day' data-month='$month' data-year='$year'>" . $day . "</td>";
			}
		}
		while( ($day + $offset) <= $rows * 7)
		{
			$calendar .= "<td></td>";
			$day++;
		}
		$calendar .= "</tr>";
		$calendar .= "</table>";
		
		$calendar .= "</div>";
		return $calendar;
	}
}
?>
