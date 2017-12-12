<?php

/**
 * WCMP Pdf Invoices plugin Install
 *
 * Plugin install script which adds default pages, taxonomies, and database tables to WordPress. Runs on activation and upgrade.
 *
 * @author 		Dualcube
 * @package 	wcmp_pdf_invoices_install/class/
 * @version   0.0.1
 */
class WCMP_Pdf_Invoices_Install {
  
  public function __construct() {
  	
    global $WCMp_PDF_Invoices;
	
    if(!get_option( "WCMP_Pdf_Invoices_plugin_page_install")){
        $this->WCMP_Pdf_Invoices_plugin_create_pages();
    }
  }
  
  /**
   * Create pages that the plugin relies on, storing page id's in variables.
   *
   * @access public
   * @return void
   */
  function WCMP_Pdf_Invoices_plugin_create_pages() {
    global $WCMp_PDF_Invoices,$WCMp;
    require_once ( $WCMp->plugin_path . 'includes/class-wcmp-install.php' );
    $WCMp_Install = new WCMp_Install();
    // PDF invoice Pages
    $WCMp_Install->wcmp_product_vendor_plugin_create_page( esc_sql( _x('wcmp_vendor_edit_invoice', 'page_slug', 'wcmp-pdf_invoices'
) ), 'WCMP_Pdf_Invoices_Vendor_Edit_Page_Id', __('Invoice Settings', 'wcmp-pdf_invoices'
), '[vendor_invoice_settings]' );
    if(function_exists('update_wcmp_vendor_settings')){
        update_wcmp_vendor_settings('wcmp_vendor_edit_invoice', get_option('WCMP_Pdf_Invoices_Vendor_Edit_Page_Id'), 'vendor', 'general');
    }
    update_option( "WCMP_Pdf_Invoices_plugin_page_install", 1 );
  }  
 
}
?>