<?php
if ( ! class_exists( 'WCMp_Product_Import_Export_Bundle_Import' ) )
	return;
class WCMp_Product_Import_Export_Bundle_Import_Variation extends WCMp_Product_Import_Export_Bundle_Import {	
	public function __construct() {
		parent::__construct();
		$this->import_page = 'wcmp_import_export_variation_csv';
	}

	public function make_url_var(){		
		global $WCMp_Product_Import_Export_Bundle;       
		$this->action     = get_the_permalink().'?import=wcmp_import_export_variation_csv&amp;step=1' ;
		$this->bytes      = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
		$this->size       = ini_get("upload_max_filesize");;
		$this->upload_dir = wp_upload_dir();		
		$WCMp_Product_Import_Export_Bundle->template->get_template('variation-product-file-upload-form.php' );
	}

	function wcmp_header_section() {
		global $WCMp_Product_Import_Export_Bundle;
		echo '<h2>' . ( empty( $_GET['merge'] ) ? __( 'Import Products', 'wcmp-product-import-export-bundle' ) : __( 'Merge Products', 'wcmp-product-import-export-bundle' ) ) . '</h2>';
	}



}