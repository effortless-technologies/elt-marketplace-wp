<?php
class WCMp_Upload_Product_Shortcode {
	public static $tabs;
	
	public function __construct() {

	}
		
	public static function execute_shortcode() {
		global $WCMp,$DC_Product_Vendor, $WCMp_Product_Import_Export_Bundle;
		if($WCMp)
		$DC_Product_Vendor = $WCMp;
		$add_product_from_frontend = get_user_meta( get_current_user_id(), '_add_product_from_frontend', true );		
		if( get_current_user_id() == 0) {
			$myaccountlink = get_permalink( get_option('woocommerce_myaccount_page_id') );
			_e('You are not <b>logged In</b>. If you are vendor ? then <b>logged In</b> first. ', 'wcmp-product-import-export-bundle');
			echo '<a href="'.$myaccountlink.'">';
			_e('Click Here to login','wcmp-product-import-export-bundle');
			echo '</a>';				
		}
		else {
			if(! $add_product_from_frontend  ) {
				if(self::is_wcmp_vendor(get_current_user_id())) {
					$add_product_from_frontend = 'Enable';					
				}
				else {
					$add_product_from_frontend = 'non vendor';
				}
			}	
			$user = get_userdata( get_current_user_id() );
			if($add_product_from_frontend == 'Enable' )	{
				if(WC_Import_Export_Dependencies::wc_marketplace_plugin_active_check() ) {
					$capabilities = get_option( "wcmp_import_export_settings_name" );					
					if( $capabilities['can_add_bulk_products'] ) {
						self::create_wcmp_product_import_export_bundle_settings();
					}
					else {
						_e('Administrator not allow any vendor to add product from frontend.', 'wcmp-product-import-export-bundle');
					}
					return;
				}
				self::create_wcmp_product_import_export_bundle_settings();
			}
			elseif($add_product_from_frontend == 'non vendor') {
				_e('You are not a vendor please contact to administrator for any query.', 'wcmp-product-import-export-bundle');				
			}
			else {
				_e('Administrator has not allowed you to add product(s) from front-end.', 'wcmp-product-import-export-bundle');
			}
		}
	}

	public static function create_wcmp_product_import_export_bundle_settings() {
		global $WCMp_Product_Import_Export_Bundle;
		self::wcmp_settings_tabs();
		//define(WP_LOAD_IMPORTERS,'1');
		self::frontend_settings_body();
	}

	public static function wcmp_settings_tabs( $current = 'import' ) {
		if ( isset ( $_GET['tab'] ) ) :
			$current = $_GET['tab'];
		else:
			$current = 'import';
		endif;
		$links = array();

		$tabs = self::get_wcmp_settings_tabs();
		self::frontend_tabs($tabs,$current);
	}


	public static function get_wcmp_settings_tabs() {
		global $WCMp_Product_Import_Export_Bundle;
		$tabs = apply_filters('wcmp_product_import_export_bundle_tabs', array(
			'import' => __('Import Products', 'wcmp-product-import-export-bundle'),
			'export' => __('Export Products', 'wcmp-product-import-export-bundle'),
			'sample' => __('Sample CSV', 'wcmp-product-import-export-bundle')
		));
		return $tabs;
	}

	public static function frontend_tabs($tabs,$current) {
		global $WCMp_Product_Import_Export_Bundle;
		foreach( $tabs as $tab => $name ) :
	    /* if tab is sample download tab */
		if($tab=='sample')
			$links[] = "<li class=''><a class='nav-tab' href='".plugins_url( 'Sample_CSV.zip', WCMP_PRODUCT_IMPORT_EXPORT_BUNDLE_FILE )."'>$name</a></li>";
		else
			if ( $tab == $current ) :
				$links[] = "<li class='active'><a class='nav-tab nav-tab-active' href='?page=wcmp-product-import-export-bundle&tab=$tab'>$name</a></li>";
			else :
				$links[] = "<li class=''><a class='nav-tab' href='?page=wcmp-product-import-export-bundle&tab=$tab'>$name</a></li>";
			endif;
		endforeach;
		echo '<div class="product">';
		echo '<div class="woocommerce-tabs wc-tabs-wrapper">'; /* start wc tab wrapper*/
		echo '<ul class="tabs wc-tabs">';
		foreach ( $links as $link ) {
			echo $link;
		}
		echo '</ul>';
	}
	
	public static function is_wcmp_vendor($user_id) {
		
		$is_vendor = false;
		if( function_exists( 'is_user_wcmp_vendor' ) ) {
			$is_vendor = is_user_wcmp_vendor($user_id);
		}
		return $is_vendor;		
	}

	public static function frontend_settings_body() {
		global $WCMp_Product_Import_Export_Bundle;
		if(isset($_REQUEST['import'])){
			include_once ABSPATH . 'wp-admin/includes/media.php';
			include_once ABSPATH . 'wp-admin/includes/file.php';
			include_once ABSPATH . 'wp-admin/includes/image.php';
			echo '<div class="panel entry-content wc-tab">';
			switch ($_GET['import']) {
				case "wcmp_import_export_variation_csv" :
					$WCMp_Product_Import_Export_Bundle->variation_wcmp_importer();
				break;
				default :
					$WCMp_Product_Import_Export_Bundle->importer();
				break;
			}
			echo '</div>';
		}
		else {
			$tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : 'import' );
			if($tab=='import' || $tab=='export'):
			switch ($tab) {
				case "export" :
					self::export_page();
				break;
				default :
					self::import_page();
				break;
			}
		 endif;
		}
		echo '</div>'; /*end product div*/
		echo '</div>'; /* end wc wrapper */
	}


	public static function import_page() {
		global $WCMp_Product_Import_Export_Bundle;
		//$WCMp_Product_Import_Export_Bundle->template->get_template( 'simple-product-import-template.php' );
		$WCMp_Product_Import_Export_Bundle->template->get_template( 'variation-product-import-template.php' );
	}

	public static function export_page() {
		global $WCMp_Product_Import_Export_Bundle;
		$WCMp_Product_Import_Export_Bundle->template->get_template('simple-product-export-template.php' );
		$WCMp_Product_Import_Export_Bundle->template->get_template('variation-product-export-template.php' );

	}
}