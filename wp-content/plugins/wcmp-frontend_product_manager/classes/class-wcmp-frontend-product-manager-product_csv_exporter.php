<?php

if ( ! class_exists( 'WC_Product_CSV_Exporter', false ) ) {
	include_once( WC_ABSPATH . 'includes/export/class-wc-product-csv-exporter.php' );
}

class WCMP_Product_CSV_Exporter  extends WC_Product_CSV_Exporter {

	public function prepare_data_to_export() {
		$columns  = $this->get_column_names();

		$product_arr = array(
			'status'   => array( 'private', 'publish', 'pending', 'draft'),
			//'type'     => array_merge( $this->product_types_to_export , array( 'booking','bundle','subscription','variable-subscription' ) ),
			'type'     => $this->product_types_to_export,
			'limit'    => $this->get_limit(),
			'page'     => $this->get_page(),
			'orderby'  => array(
				'ID'   => 'ASC',
			),
			'return'   => 'objects',
			'paginate' => true,
		);
		/*if( wcmp_forntend_manager_is_bundle() ) {
			$this->product_types_to_export = array_merge( $this->product_types_to_export , array('bundle') );
		} 
		if( wcmp_forntend_manager_is_subscription() ) {
			$this->product_types_to_export = array_merge( $this->product_types_to_export , array('subscription','variable-subscription') );
		}
		if( wcmp_forntend_manager_is_booking() ) {
			$this->product_types_to_export = array_merge( $this->product_types_to_export , array('booking') );
		}
		$product_arr['type'] = $this->product_types_to_export;*/
		$products = wc_get_products($product_arr);

		
		$this->total_rows = $products->total;
		$this->row_data   = array();
		$user_id = get_current_user_id();
		foreach ( $products->products as $product ) {
			$product_id = $product->get_ID();
			$vendor = get_wcmp_product_vendors($product_id);
			if( !empty($vendor) && $vendor->id == $user_id ) {
				$row = array();
				foreach ( $columns as $column_id => $column_name ) {
					$column_id = strstr( $column_id, ':' ) ? current( explode( ':', $column_id ) ) : $column_id;
					$value     = '';

					// Skip some columns if dynamically handled later or if we're being selective.
					if ( in_array( $column_id, array( 'downloads', 'attributes', 'meta' ) ) || ! $this->is_column_exporting( $column_id ) ) {
						continue;
					}

					// Filter for 3rd parties.
					if ( has_filter( "woocommerce_product_export_{$this->export_type}_column_{$column_id}" ) ) {
						$value = apply_filters( "woocommerce_product_export_{$this->export_type}_column_{$column_id}", '', $product, $column_id );

					// Handle special columns which don't map 1:1 to product data.
					} elseif ( is_callable( array( $this, "get_column_value_{$column_id}" ) ) ) {
						$value = $this->{"get_column_value_{$column_id}"}( $product );

					// Default and custom handling.
					} elseif ( is_callable( array( $product, "get_{$column_id}" ) ) ) {
						$value = $product->{"get_{$column_id}"}( 'edit' );
					}

					$row[ $column_id ] = $value;
				}

				$this->prepare_downloads_for_export( $product, $row );
				$this->prepare_attributes_for_export( $product, $row );
				$this->prepare_meta_for_export( $product, $row );

				$this->row_data[] = apply_filters( 'wcmp_product_export_row_data', $row, $product );
			} else {
				continue;
			}
		}
	}

}