<?php
class WCMp_Product_Import_Export_Bundle_Ajax {
	public function __construct() {
		add_action( 'wp_ajax_wcmp_import_request', array( $this, 'wcmp_import_request' ) );
		add_action( 'wp_ajax_wcmp_generate_thumbnail_img', array( $this, 'generate_thumbnail_img' ) );
	}

	public function generate_thumbnail_img() {
		global $WCMp_Product_Import_Export_Bundle;
		@error_reporting( 0 );
		header( 'Content-type: application/json' );
		$id    = (int) $_REQUEST['id'];
		$image = get_post( $id );
		if ( ! $image || 'attachment' != $image->post_type || 'image/' != substr( $image->post_mime_type, 0, 6 ) ) {
			die(json_encode( array( 'success' => sprintf( __( '&quot;%1$s&quot; is successfully resized.', 'wcmp-product-import-export-bundle' ), esc_html( $_REQUEST['id'] ) ) ) ));
			//die( json_encode( array( 'error' => sprintf( __( 'Failed resize: %s is an invalid image ID.', 'wcmp-product-import-export-bundle' ), esc_html( $_REQUEST['id'] ) ) ) ) );
		}
		else {
			$fullsizepath = get_attached_file( $image->ID );
			if ( false === $fullsizepath || ! file_exists( $fullsizepath ) )
			$this->wcmp_json_error_msg( $image->ID, sprintf( __( 'The originally uploaded image file cannot be found at %s', 'wcmp-product-import-export-bundle' ), '<code>' . esc_html( $fullsizepath ) . '</code>' ) );
			@set_time_limit( 9000 );
			$metadata = wp_generate_attachment_metadata( $image->ID, $fullsizepath );
			if ( is_wp_error( $metadata ) )
			$this->wcmp_json_error_msg( $image->ID, $metadata->get_error_message() );
			if ( empty( $metadata ) )
			$this->wcmp_json_error_msg( $image->ID, __( 'Unknown failure reason.', 'wcmp-product-import-export-bundle' ) );
			wp_update_attachment_metadata( $image->ID, $metadata );
			die( json_encode( array( 'success' => sprintf( __( '&quot;%1$s&quot; is successfully resized.', 'wcmp-product-import-export-bundle' ), esc_html( get_the_title( $image->ID ) ), $image->ID, timer_stop() ) ) ) );
		}
	}


	public function wcmp_import_request() {
		global $WCMp_Product_Import_Export_Bundle;
		if ( $_REQUEST['import_page'] == 'wcmp_import_export_variation_csv' )
			 $WCMp_Product_Import_Export_Bundle->variation_wcmp_importer();
		else
			 $WCMp_Product_Import_Export_Bundle->importer();
	}
	public function wcmp_json_error_msg( $id, $message ) {
		global $WCMp_Product_Import_Export_Bundle;
				die( json_encode( array( 'error' => sprintf( __( '&quot;%1$s&quot; failed to resize. The error is: %3$s', 'wcmp-product-import-export-bundle' ), esc_html( get_the_title( $id ) ), $id, $message ) ) ) );
		}

}
