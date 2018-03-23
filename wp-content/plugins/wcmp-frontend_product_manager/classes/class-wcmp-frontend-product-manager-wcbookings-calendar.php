<?php

/**
 * WCMp Product Types plugin core
 *
 * Booking WC Booking Calendar Support
 *
 * @author 		WC Marketplace
 * @package 	wcmp-pts/classes
 * @version   1.0.0
 */

class WCMp_Frontend_Product_Manager_WCBookings_Calendar {
	protected $bookings;
	protected $vendor_bookings;

	public function __construct() {
	    global $WCMp, $WCMp_Frontend_Product_Manager;

	    if( !empty( get_wcmp_vendor(get_current_user_id()) ) && wcmp_forntend_manager_is_booking() && current_user_can( 'manage_bookings' ) ) {
    		if (!class_exists('WC_Bookings_Admin')) {
				include_once( WC_BOOKINGS_ABSPATH . 'includes/admin/class-wc-bookings-admin.php' );
			}
			//get all the booking ID's of current vendor
			$this->vendor_bookings = $this->get_vendor_booking_id_list();

			add_filter('get_booking_products_args', array( $this,'get_vendor_specific_booking_products_args'));
    	} else {
    		//if vendor is not authorized set booking list to an empty array
    		$this->vendor_bookings = array();
    	}
    }

	/**
	* Get Vendor booking list
	*
	* @access public
	* @return array
	*/
    public function get_vendor_booking_id_list() {
    	global $WCMp, $wpdb;
		$vendor_id = get_current_user_id();
		
		//vendor product lit
		$products = array();

		if ($vendor_id) {
			$vendor = get_wcmp_vendor($vendor_id);
			if ($vendor)
				$vendor_products = $vendor->get_products();
			if (!empty($vendor_products)) {
				foreach ($vendor_products as $vendor_product) {
					$products[] = $vendor_product->ID;
					if( $vendor_product->post_type == 'product_variation' ) $products[] = $vendor_product->post_parent;
				}
			}
		}
		
		//get a list of booking id's where product id matches in vendor product list
  		$query =   "SELECT ID FROM {$wpdb->posts} as posts
					INNER JOIN {$wpdb->postmeta} AS postmeta ON posts.ID = postmeta.post_id
					WHERE 1=1
					AND posts.post_type IN ( 'wc_booking' )
					AND posts.post_status <> 'was-in-cart'
					AND postmeta.meta_key = '_booking_product_id' AND postmeta.meta_value in (" . implode(',', $products) . ")";
		
		$query_results = $wpdb->get_results($query);
		$vendor_bookings_arr = array();
		foreach( $query_results as $vendor_booking ) {
			$vendor_bookings_arr[] = $vendor_booking->ID;
		}
		return $vendor_bookings_arr;
    }

	/**
	 * add author parameter to filter out other vendors bookable products
	 *
	 * @return array
	 */   
   	public function get_vendor_specific_booking_products_args( $args ) {
		return array_merge($args, array('author' => get_current_user_id()));
    }

	/**
	 * Return all bookings for a product in a given range
	 * @param integer $start_date
	 * @param integer $end_date
	 * @param int  $product_or_resource_id
	 * @param bool $check_in_cart
	 *
	 * @return array
	 */
    private function get_bookings_in_date_range( $start_date, $end_date, $product_or_resource_id = 0, $check_in_cart = true ) {
    	$vendor_id = get_current_user_id();

    	//Append vendor id to separate out each vendor transient
    	$transient_name = 'book_dr_' . md5( http_build_query( array( $vendor_id, $start_date, $end_date, $product_or_resource_id, $check_in_cart, WC_Cache_Helper::get_transient_version( 'bookings' ) ) ) );
		
		$booking_ids    = get_transient( $transient_name );

		if ( false === $booking_ids ) {
			$booking_ids = $this->get_bookings_in_date_range_query( $start_date, $end_date, $product_or_resource_id, $check_in_cart );
			set_transient( $transient_name, $booking_ids, DAY_IN_SECONDS * 30 );
		}

		return array_map( 'get_wc_booking', wp_parse_id_list( $booking_ids ) );
    }

	/**
	 * Return all bookings for a product in a given range - the query part (no cache)
	 * @param  integer $start_date
	 * @param  integer$end_date
	 * @param  int $product_or_resource_id
	 * @param  bool $check_in_cart
	 * @return array of booking ids
	 */
	private function get_bookings_in_date_range_query( $start_date, $end_date, $product_or_resource_id = 0, $check_in_cart = true ) {
		$args = array(
			'status'       => get_wc_booking_statuses(),
			'object_id'    => $product_or_resource_id,
			'object_type'  => 'product_or_resource',
			'date_between' => array(
				'start' => $start_date,
				'end'   => $end_date,
			),
		);

		if ( ! $check_in_cart ) {
			$args['status'] = array_diff( $args['status'], array( 'in-cart' ) );
		}

		if ( $product_or_resource_id ) {
			if ( get_post_type( $product_or_resource_id ) === 'bookable_resource' ) {
				$args['resource_id'] = absint( $product_or_resource_id );
			} else {
				$args['product_id']  = absint( $product_or_resource_id );
			}
		}

		return array_intersect( WC_Booking_Data_Store::get_booking_ids_by( $args ), $this->vendor_bookings );
	}

	/**
	 * Output the calendar view.
	 */
	public function output() {
		
		wp_enqueue_style( 'booking-calendar-style' );

		$product_filter = isset( $_REQUEST['filter_bookings'] ) ? absint( $_REQUEST['filter_bookings'] ) : '';
		$view           = isset( $_REQUEST['view'] ) && 'day' === $_REQUEST['view'] ? 'day' : 'month';

		/*if(!empty($product_filter) && !in_array($product_filter, $this->vendor_bookings)) $product_filter = '';*/

		if ( 'day' === $view ) {
			wp_enqueue_script('wcmp_woocommerce_jquery_tiptip');
			
			$day = isset( $_REQUEST['calendar_day'] ) ? wc_clean( $_REQUEST['calendar_day'] ) : date( 'Y-m-d' );
			$this->bookings = $this->get_bookings_in_date_range(
				strtotime( 'midnight', strtotime( $day ) ),
				strtotime( 'midnight +1 day', strtotime( $day ) ) - 1,
				$product_filter,
				false
			);
		} else {
			$month = isset( $_REQUEST['calendar_month'] ) ? absint( $_REQUEST['calendar_month'] ) : date( 'n' );
			$year  = isset( $_REQUEST['calendar_year'] ) ? absint( $_REQUEST['calendar_year'] ) : date( 'Y' );

			if ( $year < ( date( 'Y' ) - 10 ) || $year > 2100 ) {
				$year = date( 'Y' );
			}

			if ( $month > 12 ) {
				$month = 1;
				$year ++;
			}

			if ( $month < 1 ) {
				$month = 12;
				$year --;
			}

			$start_of_week = absint( get_option( 'start_of_week', 1 ) );
			$last_day      = date( 't', strtotime( "$year-$month-01" ) );
			$start_date_w  = absint( date( 'w', strtotime( "$year-$month-01" ) ) );
			$end_date_w    = absint( date( 'w', strtotime( "$year-$month-$last_day" ) ) );

			// Calc day offset
			$day_offset = $start_date_w - $start_of_week;
			$day_offset = $day_offset >= 0 ? $day_offset : 7 - abs( $day_offset );

			// Cald end day offset
			$end_day_offset = 7 - ( $last_day % 7 ) - $day_offset;
			$end_day_offset = $end_day_offset >= 0 && $end_day_offset < 7 ? $end_day_offset : 7 - abs( $end_day_offset );

			// We want to get the last minute of the day, so we will go forward one day to midnight and subtract a min
			$end_day_offset = $end_day_offset + 1;

			$start_time = strtotime( "-{$day_offset} day", strtotime( "$year-$month-01" ) );
			$end_time   = strtotime( "+{$end_day_offset} day midnight", strtotime( "$year-$month-$last_day" ) );
			$this->bookings  = $this->get_bookings_in_date_range(
				$start_time,
				$end_time,
				$product_filter,
				false
			);
		}
		include( 'booking/html-calendar-' . $view . '.php' );
		//wp_enqueue_script( 'selectWoo');
		//wp_enqueue_script( 'wc-enhanced-select' );
	}
	
	/**
	 * List bookings for a day.
	 */
	public function list_bookings( $day, $month, $year ) {
		$date_start = strtotime( "$year-$month-$day midnight" ); // Midnight today.
		$date_end   = strtotime( "$year-$month-$day tomorrow" ); // Midnight next day.
		$booking_details_url = wcmp_get_vendor_dashboard_endpoint_url( 'booking-details' );
		foreach ( $this->bookings as $booking ) {
			if ( $booking->get_start() < $date_end && $booking->get_end() > $date_start ) {
				echo '<li><a href="' . $booking_details_url . $booking->get_id() . '/">';
				echo '<strong>#' . $booking->get_id() . ' - ';
				$product = $booking->get_product();
				if ( $product ) {
					echo $product->get_title();
				}
				echo '</strong>';
				echo '<ul>';
				$customer = $booking->get_customer();
				if ( $customer && ! empty( $customer->name ) ) {
					echo '<li>' . __( 'Booked by', 'wcmp-frontend_product_manager' ) . ' ' . $customer->name . '</li>';
				}
				echo '<li>';
				if ( $booking->is_all_day() ) {
					echo __( 'All Day', 'wcmp-frontend_product_manager' );
				} else {
					echo $booking->get_start_date() . '&mdash;' . $booking->get_end_date();
				}
				echo '</li>';
				$resource = $booking->get_resource();
				if ( $resource ) {
					echo '<li>' . __( 'Resource #', 'wcmp-frontend_product_manager' ) . $resource->ID . ' - ' . $resource->post_title . '</li>';
				}
				$persons  = $booking->get_persons();
				foreach ( $persons as $person_id => $person_count ) {
					echo '<li>';
					/* translators: 1: person id 2: person name 3: person count */
					printf( __( 'Person #%1$s - %2$s (%3$s)', 'wcmp-frontend_product_manager' ), $person_id, get_the_title( $person_id ), $person_count );
					echo '</li>';
				}
				echo '</ul></a>';
				echo '</li>';
			}
		}
	}
		/**
	 * List bookings on a day.
	 *
	 * @version  1.10.7 [<description>]
	 */
	public function list_bookings_for_day() {
		$bookings_by_time = array();
		$all_day_bookings = array();
		$unqiue_ids       = array();

		$booking_details_url = wcmp_get_vendor_dashboard_endpoint_url( 'booking-details' );

		foreach ( $this->bookings as $booking ) {
			if ( $booking->is_all_day() ) {
				$all_day_bookings[] = $booking;
			} else {
				$start_time = $booking->get_start_date( '', 'Gi' );

				if ( ! isset( $bookings_by_time[ $start_time ] ) ) {
					$bookings_by_time[ $start_time ] = array();
				}

				$bookings_by_time[ $start_time ][] = $booking;
			}
			$unqiue_ids[] = $booking->get_product_id() . $booking->get_resource_id();
		}

		ksort( $bookings_by_time );

		$unqiue_ids = array_flip( $unqiue_ids );
		$index      = 0;
		$colours    = array( '#3498db', '#34495e', '#1abc9c', '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c', '#2980b9', '#8e44ad', '#2c3e50', '#16a085', '#27ae60', '#f39c12', '#d35400', '#c0392b' );

		foreach ( $unqiue_ids as $key => $value ) {
			if ( isset( $colours[ $index ] ) ) {
				$unqiue_ids[ $key ] = $colours[ $index ];
			} else {
				$unqiue_ids[ $key ] = $this->random_color();
			}
			$index++;
		}

		$column = 0;

		foreach ( $all_day_bookings as $booking ) {
			echo '<li data-tip="' . $this->get_tip( $booking ) . '" style="background: ' . $unqiue_ids[ $booking->get_product_id() . $booking->get_resource_id() ] . '; left:' . 100 * $column . 'px; top: 0; bottom: 0;"><a href="' . $booking_details_url . $booking->get_id() . '/">#' . $booking->get_id() . '</a></li>';
			$column++;
		}

		$start_column = $column;
		$last_end     = 0;

		$day = isset( $_REQUEST['calendar_day'] ) ? wc_clean( $_REQUEST['calendar_day'] ) : date( 'Y-m-d' );
		$day_timestamp = strtotime( $day );
		$next_day_timestamp = strtotime( $day . '+1 days' );

		foreach ( $bookings_by_time as $bookings ) {
			foreach ( $bookings as $booking ) {

				// Adjust start_time if event starts before the calendar day
				if ( $booking->get_start() >= $day_timestamp ) {
					$start_time = $booking->get_start_date( '', 'Hi' );
				} else {
					$start_time = '0000';
				}

				// Adjust end_time if event ends after the calendar day
				if ( $booking->get_end() > $next_day_timestamp ) {
					$end_time = '2400';
				} else {
					$end_time  = $booking->get_end_date( '', 'Hi' );
				}

				$height = ( strtotime( $end_time ) - strtotime( $start_time ) ) / 60;

				if ( $height < 30 ) {
					$height = 30;
				}

				if ( $last_end > $start_time ) {
					$column++;
				} else {
					$column = $start_column;
				}

				$start_time_stamp   = strtotime( $start_time );
				$start_hour_in_mins = date( 'H', $start_time_stamp ) * 60;
				$start_minutes      = date( 'i', strtotime( $start_time ) );
				$from_top           = $start_hour_in_mins + $start_minutes;

				echo '<li data-tip="' . $this->get_tip( $booking ) . '" style="background: ' . esc_attr( $unqiue_ids[ $booking->get_product_id() . $booking->get_resource_id() ] ) . '; left:' . esc_attr( 100 * $column ) . 'px; top: ' . esc_attr( $from_top ) . 'px; height: ' . esc_attr( $height ) . 'px;"><a href="' . $booking_details_url . $booking->get_id() . '/">#' . esc_html( $booking->get_id() ) . '</a></li>';

				if ( $end_time > $last_end ) {
					$last_end = $end_time;
				}
			}
		}
	}
	/**
	 * Get a random colour.
	 */
	public function random_color() {
		return sprintf( '#%06X', mt_rand( 0, 0xFFFFFF ) );
	}

	/**
	 * Get a tooltip in day view.
	 *
	 * @param  object $booking
	 * @return string
	 */
	public function get_tip( $booking ) {
		$return = '';

		$return .= '#' . $booking->get_id() . ' - ';
		$product = $booking->get_product();

		if ( $product ) {
			$return .= $product->get_title();
		}

		$customer = $booking->get_customer();

		if ( $customer && ! empty( $customer->name ) ) {
			$return .= '<br/>' . __( 'Booked by', 'wcmp-frontend_product_manager' ) . ' ' . $customer->name;
		}

		$resource = $booking->get_resource();

		if ( $resource ) {
			$return .= '<br/>' . __( 'Resource #', 'wcmp-frontend_product_manager' ) . $resource->ID . ' - ' . $resource->post_title;
		}

		$persons  = $booking->get_persons();

		foreach ( $persons as $person_id => $person_count ) {
			$return .= '<br/>';

			/* translators: 1: person id 2: person name 3: person count */
			$return .= sprintf( __( 'Person #%1$s - %2$s (%3$s)', 'wcmp-frontend_product_manager' ), $person_id, get_the_title( $person_id ), $person_count );
		}

		return esc_attr( $return );
	}

	/**
	 * Filters products for narrowing search.
	 */
	public function product_filters() {
		$filters  = array();
		$products = WC_Bookings_Admin::get_booking_products();
		foreach ( $products as $product ) {
			$filters[ $product->get_id() ] = $product->get_name();

			$resources = $product->get_resources();

			foreach ( $resources as $resource ) {
				$filters[ $resource->get_id() ] = '&nbsp;&nbsp;&nbsp;' . $resource->get_name();
			}
		}

		return $filters;
	}

	/**
	 * Filters resources for narrowing search.
	 */
	public function resources_filters() {
		$filters   = array();
		$resources = WC_Bookings_Admin::get_booking_resources();

		foreach ( $resources as $resource ) {
			$filters[ $resource->get_id() ] = $resource->get_name();
		}

		return $filters;
	}
}	
