<?php
if ( ! class_exists( 'WC_Product_CSV_Importer', false ) ) {
	//include_once( dirname( __FILE__ ) . '/abstract-wc-product-importer.php' );
	include_once( WC_ABSPATH . 'includes/import/class-wc-product-csv-importer.php' );
}

/**
 * WC_Product_CSV_Importer Class.
 */
class WCMP_Product_CSV_Importer extends WC_Product_CSV_Importer {

	protected function set_parsed_data() {
		$parse_functions = $this->get_formating_callback();
		$mapped_keys     = $this->get_mapped_keys();
		$use_mb          = function_exists( 'mb_convert_encoding' );

		// Parse the data.
		foreach ( $this->raw_data as $row ) {
			// Skip empty rows.
			if ( ! count( array_filter( $row ) ) ) {
				continue;
			}
			$data = array();

			do_action( 'woocommerce_product_importer_before_set_parsed_data', $row, $mapped_keys );
			//print_r($row);die();

			$args = array(
	            'taxonomy'     => 'product_cat',
	            'orderby'      => 'name',
	            'show_count'   => 0,
	            'pad_counts'   => 0,
	            'hierarchical' => 1,
	            'title_li'     => '',
	            'hide_empty'   => 0
	        );
	        $all_categories = get_categories( $args );
	        $all_categories_array = array();
	        // print_r($data);die();
	        $new_row = array();
	        
	        foreach ($all_categories as $key => $value) {
	            $all_categories_array[$key] = $value->name;
	        }
	        $row_no = '';
	        foreach ($mapped_keys as $key => $value) {
	            
	            if($value == 'category_ids') {
	                $row_no = $key;
	                $csv_categories_array = array();
	                $csv_categories_together = $row[$key];
	                //print_r($csv_categories_together);die();
	                $csv_categories_array = explode(", ",$csv_categories_together);
	                foreach ($csv_categories_array as $index => $cat) {
	                    //print_r($cat);
	                    if (in_array($cat, $all_categories_array)) {
	                       array_push($new_row, $cat);
	                    }
	                }
	            }
	        }
	        $row_categories = implode(", ", $new_row);
	            //print_r($row[$value]);die();
	           $row[$row_no] = $row_categories;
	           //print_r($row);die();
			foreach ( $row as $id => $value ) {
				// Skip ignored columns.
				if ( empty( $mapped_keys[ $id ] ) ) {
					continue;
				}

				// Convert UTF8.
				if ( $use_mb ) {
					$encoding = mb_detect_encoding( $value, mb_detect_order(), true );
					if ( $encoding ) {
						$value = mb_convert_encoding( $value, 'UTF-8', $encoding );
					} else {
						$value = mb_convert_encoding( $value, 'UTF-8', 'UTF-8' );
					}
				} else {
					$value = wp_check_invalid_utf8( $value, true );
				}
				$data[ $mapped_keys[ $id ] ] = call_user_func( $parse_functions[ $id ], $value );
			}
			$this->parsed_data[] = apply_filters( 'woocommerce_product_importer_parsed_data', $this->expand_data( $data ), $this );
		}
	}
}