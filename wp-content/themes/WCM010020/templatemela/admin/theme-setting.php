<?php
/*===================================================================================================================
=============================================== General Settings ====================================================
=================================================================================================================== */
$options1 = array(array());

$options1[] = array("id" => "tmoption_logo_image",
					"label" => "Logo Image",
					"type" => "upload",	
					"std" => get_template_directory_uri()."/images/logo.png",				
					"description" => "Upload Your Logo.");				
$options1[] = array("id" => "tmoption_logo_image_alt",
					"label" => "Logo Image Alt",
					"type" => "textbox",
					"std" => "Branchy",
					"description" => "Define logo image alt here.");
$options1[] = array("id" => "tmoption_showsite_description",
					"label" => "Show Site Description?",
					"type" => "select",
					"description" => "Display Site Description or Not.",
					"options" => array('no' => 'No','yes' => 'Yes'));
$options1[] = array("id" => "tmoption_favicon_icon",
					"label" => "Favicon Icon",
					"type" => "upload-1",
					"description" => "Define favicon icon path here.");

$options1[] = array("id" => "tmoption_contact_email",
					"label" => "Contact Email",
					"type" => "textbox",
					"std" => "demo.templatemela@gmail.com",
					"description" => "Email ID where to receive contact emails.");
					
$options1[] = array("id" => "tmoption_control_panel",
					"label" => "Show Control Panel?",
					"type" => "select",
					"description" => "Enable or Disable Control Panel.",
					"options" => array('no' => 'No','yes' => 'Yes'));
					
$options1[] = array("id" => "tmoption_responsive",
				  "label" => "Do you want Responsive Design?",
				  "type" => "select",
				  "description" => "Enable or Disable Responsive Design.",
				  "options" => array('yes' => 'Yes','no' => 'No'));
		
$options1[] = array("id" => "tmoption_navigation_option",
					"label" => "Pagination",
					"type" => "select",
					"description" => "Choose the Pagination Option For Theme.",
					"options" => array('1' => 'Default','2' => 'Numbering'));						
									
$options1[] = array("label" => "Background Settings",
					"title" =>"Background Settings",
					"type" => "Title-1");
					
					
$options1[] = array("id" => "tmoption_background_upload",
					"label" => "Upload Background Image",
					"type" => "upload-3",
					"description" => "Upload Your Background.");	
					
$options1[] = array("id" => "tmoption_back_repeat",
					"label" => "Background Repeat",
					"type" => "select",
					"description" => "Choose Background repeate.",
					"options" => array('no-repeat' => 'no-repeat','repeat' => 'repeat','repeat-x' => 'repeat-x','repeat-y' => 'repeat-y'));	
					
$options1[] = array("id" => "tmoption_back_position",
					"label" => " Background Position",
					"type" => "select",
					"description" => "Choose Backgroung position.",
					"options" => array('top+left' => 'top left','top+center' => 'top center','top+right' => 'top right','center+right' => 'center right','center+left' => 'center left','center+center' => 'center center','bottom+right' => 'bottom right','bottom+center' => 'bottom center','bottom+left' => 'bottom left'));	
				
$options1[] = array("id" => "tmoption_back_attachment",
					"label" => "Background Attachment",
					"type" => "select",
					"description" => "Choose Background attachment.",
					"options" => array('scroll' => 'Scroll','Fixed' => 'fixed'));	

$options1[] = array("id" => "tmoption_bkg_color",
					"label" => "Body Background Color",
					"type" => "textbox-1",
					"std" => "FFFFFF",
					"description" => "Change your body background color.");

$options1[] = array("id" => "tmoption_bodyfont_color",
					"label" => "Body Font Color",
					"std" => "696969",
					"type" => "textbox-1",
					"description" => "Change your body font color.");			

$options1[] = array("id" => "tmoption_bodyfont",
					"label" => "Body Font",
					"type" => "select",
					"description" => "Change your body font.",
					"options" => array('Istok+Web'=>'Istok Web','please-select'=>'please-select','Open+Sans'=>'Open Sans','Nunito'=>'Nunito','Antic'=>'Antic','Bitter'=>'Bitter','Droid+Serif'=>'Droid Serif','Philosopher'=>'Philosopher','Oxygen'=>'Oxygen','Rokkitt'=>'Rokkitt','Galdeano'=>'Galdeano','Droid+Sans'=>'Droid Sans','Oswald'=>'Oswald','Play'=>'Play','Cabin'=>'Cabin','Cuprum'=>'Cuprum','Varela'=>'Varela','Andika'=>'Andika','Ubuntu' =>'Ubuntu','Other+Fonts'=>'Other Fonts'));
					
$options1[] = array("id" => "tmoption_bodyfont_other",
					"label" => "Specified Other Body Font",
					"type" => "textbox",
					"description" => "Change your specified body font Like Arail,verdana etc.");
					
$options1[] = array("id" => "tmoption_button_color",
					"label" => "Buttons Color",
					"type" => "textbox-1",
					"std" => "2FB991",
					"description" => "Change your buttons color.");
					
$options1[] = array("id" => "tmoption_button_hover_color",
					"label" => "Buttons Hover Color",
					"type" => "textbox-1",
					"std" => "343434",
					"description" => "Change your button hover color.");				
							

/*===================================================================================================================
=============================================== Header Settings ====================================================
=================================================================================================================== */

$options2 = array(array());

$options2[] = array("id" => "tmoption_header_layout",
					"label" => "Select Header Layout",
					"type" => "radio",
					"description" => "Select Header Layout.",	
					"std" =>"2",
					"options" => array('1' => '','2' => ''));
					
$options2[] = array("id" => "tmoption_header_background_upload",
					"label" => "Upload Header Background Image",
					"type" => "upload-1",
					"description" => "Upload Your Header Background Image.");	
					
$options2[] = array("id" => "tmoption_header_back_repeat",
					"label" => "Header Image Background Repeat",
					"type" => "select",
					"description" => "Choose Header Image Background repeate.",
					"options" => array('no-repeat' => 'no-repeat','repeat' => 'repeat','repeat-x' => 'repeat-x','repeat-y' => 'repeat-y'));	
					
$options2[] = array("id" => "tmoption_header_back_position",
					"label" => "Header Image Background Position",
					"type" => "select",
					"description" => "Choose Header Image Backgroung position.",
					"options" => array('top+left' => 'top left','top+center' => 'top center','top+right' => 'top right','center+right' => 'center right','center+left' => 'center left','center+center' => 'center center','bottom+right' => 'bottom right','bottom+center' => 'bottom center','bottom+left' => 'bottom left'));	
				
$options2[] = array("id" => "tmoption_header_back_attachment",
					"label" => "Header Image Background Attachment",
					"type" => "select",
					"description" => "Choose Header Image Background attachment.",
					"options" => array('scroll' => 'Scroll','Fixed' => 'fixed'));	
					
$options2[] = array("id" => "tmoption_headerbkg_color",
					"label" => "Header background Color",
					"type" => "textbox-1",
					"std" => "EFEFEF",
					"description" => "Change your header background color.");

$options2[] = array("id" => "tmoption_menubg_color",
					"label" => "Menu background Color",
					"type" => "textbox-1",
					"std" => "999999",
					"description" => "Change your Menu background color.");
	
/*===================================================================================================================
=============================================== Content Settings ====================================================
=================================================================================================================== */
	
$options3[] = array("id" => "tmoption_h1font",
					"label" => "H1 Font",
					"type" => "select",
					"description" => "Change your H1 font.",
					"options" => array('Istok+Web'=>'Istok Web','please-select'=>'please-select','Roboto Slab'=>'Roboto Slab','Open+Sans'=>'Open Sans','Nunito'=>'Nunito','Droid+Sans'=>'Droid Sans','Antic'=>'Antic','Bitter'=>'Bitter','Droid+Serif'=>'Droid Serif','Philosopher'=>'Philosopher','Oxygen'=>'Oxygen','Rokkitt'=>'Rokkitt','Galdeano'=>'Galdeano','Glegoo'=>'Glegoo','Oswald'=>'Oswald','Play'=>'Play','Cuprum'=>'Cuprum','Varela'=>'Varela','Andika'=>'Andika','Other+Fonts'=>'Other Fonts'));
					
$options3[] = array("id" => "tmoption_h1font_other",
					"label" => "Specified Other H1 Font",
					"type" => "textbox-3",
					"std" => "Arial",
					"description" => "Change your specified H1 font.");	

$options3[] = array("id" => "tmoption_h1color",
					"label" => "H1 Color",
					"type" => "textbox-1",
					"std" => "191919",
					"description" => "Change your H1 font color.");	
					
$options3[] = array("id" => "tmoption_h2font",
					"label" => "H2 Font",
					"type" => "select",
					"description" => "Change your H2 Font.",
					"options" => array('Istok+Web'=>'Istok Web','please-select'=>'please-select','Roboto Slab'=>'Roboto Slab','Open+Sans'=>'Open Sans','Nunito'=>'Nunito','Droid+Sans'=>'Droid Sans','Antic'=>'Antic','Bitter'=>'Bitter','Droid+Serif'=>'Droid Serif','Philosopher'=>'Philosopher','Oxygen'=>'Oxygen','Rokkitt'=>'Rokkitt','Galdeano'=>'Galdeano','Oswald'=>'Oswald','Play'=>'Play','Cuprum'=>'Cuprum','Varela'=>'Varela','Andika'=>'Andika','Other+Fonts'=>'Other Fonts'));
					
$options3[] = array("id" => "tmoption_h2font_other",
					"label" => "Specified Other H2 Font",
					"type" => "textbox-3",
					"std" => "Arial",
					"description" => "Change your specified H2 font.");

$options3[] = array("id" => "tmoption_h2color",
					"label" => "H2 Color",
					"type" => "textbox-1",
					"std" => "191919",
					"description" => "Change your H2 font color.");		
					
$options3[] = array("id" => "tmoption_h3font",
					"label" => "H3 Font",
					"type" => "select",
					"description" => "Change your H3 font.",
					"options" => array('Istok+Web'=>'Istok Web','please-select'=>'please-select','Roboto Slab'=>'Roboto Slab','Open+Sans'=>'Open Sans','Nunito'=>'Nunito','Droid+Sans'=>'Droid Sans','Antic'=>'Antic','Bitter'=>'Bitter','Droid+Serif'=>'Droid Serif','Philosopher'=>'Philosopher','Oxygen'=>'Oxygen','Rokkitt'=>'Rokkitt','Galdeano'=>'Galdeano','Oswald'=>'Oswald','Play'=>'Play','Cuprum'=>'Cuprum','Varela'=>'Varela','Andika'=>'Andika','Other+Fonts'=>'Other Fonts'));
					
$options3[] = array("id" => "tmoption_h3font_other",
					"label" => "Specified Other H3 Font",
					"type" => "textbox-3",
					"std" => "Arial",
					"description" => "Change your specified H3 font.");	

$options3[] = array("id" => "tmoption_h3color",
					"label" => "H3 Color",
					"type" => "textbox-1",
					"std" => "191919",
					"description" => "Change your H3 font color.");										
					
$options3[] = array("id" => "tmoption_h4font",
					"label" => "H4 Font",
					"type" => "select",
					"description" => "Change your H4 font.",
					"options" => array('Istok+Web'=>'Istok Web','please-select'=>'please-select','Open+Sans'=>'Open Sans','Nunito'=>'Nunito','Droid+Sans'=>'Droid Sans','Antic'=>'Antic','Bitter'=>'Bitter','Droid+Serif'=>'Droid Serif','Philosopher'=>'Philosopher','Oxygen'=>'Oxygen','Rokkitt'=>'Rokkitt','Galdeano'=>'Galdeano','Oswald'=>'Oswald','Play'=>'Play','Cuprum'=>'Cuprum','Varela'=>'Varela','Andika'=>'Andika','Other+Fonts'=>'Other Fonts'));
					
$options3[] = array("id" => "tmoption_h4font_other",
					"label" => "Specified Other H4 Font",
					"type" => "textbox-3",
					"std" => "Arial",
					"description" => "Change your specified H4 font.");	

$options3[] = array("id" => "tmoption_h4color",
					"label" => "H4 Color",
					"type" => "textbox-1",
					"std" => "606060",
					"description" => "Change your H4 font color.");	
					
$options3[] = array("id" => "tmoption_h5font",
					"label" => "H5 Font",
					"type" => "select",
					"description" => "Change your H5 font.",
					"options" => array('Istok+Web'=>'Istok Web','please-select'=>'please-select','Open+Sans'=>'Open Sans','Nunito'=>'Nunito','Droid+Sans'=>'Droid Sans','Antic'=>'Antic','Bitter'=>'Bitter','Droid+Serif'=>'Droid Serif','Philosopher'=>'Philosopher','Oxygen'=>'Oxygen','Rokkitt'=>'Rokkitt','Galdeano'=>'Galdeano','Oswald'=>'Oswald','Play'=>'Play','Cuprum'=>'Cuprum','Varela'=>'Varela','Andika'=>'Andika','Other+Fonts'=>'Other Fonts'));
					
$options3[] = array("id" => "tmoption_h5font_other",
					"label" => "Specified Other H5 Font",
					"type" => "textbox-3",
					"std" => "Arial",
					"description" => "Change your specified H5 font.");	

$options3[] = array("id" => "tmoption_h5color",
					"label" => "H5 Color",
					"type" => "textbox-1",
					"std" => "606060",
					"description" => "change your H5 font color.");	
					
$options3[] = array("id" => "tmoption_h6font",
					"label" => "H6 Font",
					"type" => "select",
					"description" => "Change your H6 font.",
					"options" => array('Istok+Web'=>'Istok Web','please-select'=>'please-select','Open+Sans'=>'Open Sans','Nunito'=>'Nunito','Droid+Sans'=>'Droid Sans','Antic'=>'Antic','Bitter'=>'Bitter','Droid+Serif'=>'Droid Serif','Philosopher'=>'Philosopher','Oxygen'=>'Oxygen','Rokkitt'=>'Rokkitt','Galdeano'=>'Galdeano','Oswald'=>'Oswald','Play'=>'Play','Cuprum'=>'Cuprum','Varela'=>'Varela','Andika'=>'Andika','Other+Fonts'=>'Other Fonts'));
					
$options3[] = array("id" => "tmoption_h6font_other",
					"label" => "Specified Other H6 Font",
					"type" => "textbox-3",
					"std" => "Arial",
					"description" => "Change your specified H6 font.");		

$options3[] = array("id" => "tmoption_h6color",
					"label" => "H6 Color",
					"type" => "textbox-1",
					"std" => "606060",
					"description" => "Change your H6 font color.");		
					
$options3[] = array("id" => "tmoption_link_color",
					"label" => "Link Color",
					"type" => "textbox-1",
					"std" => "555555",
					"description" => "Change your link color.");	
					
$options3[] = array("id" => "tmoption_hoverlink_color",
					"label" => "Link Hover Color",
					"type" => "textbox-1",
					"std" => "2FB991",
					"description" => "Change your link Hover color.");	
		
/*===================================================================================================================
=============================================== Footer Settings ====================================================
=================================================================================================================== */

$options4 = array(array());

$options4[] = array("id" => "tmoption_footerbkg_color",
					"label" => "Footer Background Color",
					"type" => "textbox-1",
					"std" => "EFEFEF",
					"description" => "Change your footer background color.");

$options4[] = array("id" => "tmoption_footerlink_color",
					"label" => "Footer Link Color",
					"type" => "textbox-1",
					"std" => "696969",
					"description" => "Change your footer link color.");	
					
$options4[] = array("id" => "tmoption_footerhoverlink_color",
					"label" => "Footer Link Hover Color",
					"type" => "textbox-1",
					"std" => "2FB991",
					"description" => "Change your footer link hover color.");
$options4[] = array("id" => "tmoption_footer_slog",
					"label" => "Footer copyright",
					"type" => "textbox",
					"description" => "Enter your copyright statement here.",
					"std" => "Templatemela.");	

$options4[] = array("id" => "tmoption_footer_link",
					"label" => "Footer copyright Link",
					"type" => "textbox",
					"description" => "Enter your copyright link here.",
					"std" => "https://www.google.co.in/");

					
			
/*===================================================================================================================
=============================================== Top Bar Settings ====================================================
=================================================================================================================== */

$options6 = array(array());
					
$options6[] = array("id" => "tmoption_show_topbar",
					"label" => "Show Top Bar",
					"type" => "select",
					"description" => "Displays Top bar.",
					"options" => array('yes' => 'Yes','no' => 'No'));	
					
$options6[] = array("id" => "tmoption_topbar_bkg_color",
					"label" => "Top Bar Background Color",
					"type" => "textbox-1",
					"std" => "FFFFFF",
					"description" => "Change your topbar background color.");
					
$options6[] = array("id" => "tmoption_topbar_text_color",
					"label" => "Top Bar Text Color",
					"type" => "textbox-1",
					"std" => "555555",
					"description" => "Change your topbar text color.");
					
$options6[] = array("id" => "tmoption_topbar_link_color",
					"label" => "Top Bar Link Color",
					"type" => "textbox-1",
					"std" => "555555",
					"description" => "Change your topbar links color.");
					
$options6[] = array("id" => "tmoption_topbar_link_hover_color",
					"label" => "Top Bar Link Hover Color",
					"type" => "textbox-1",
					"std" => "2FB991",
					"description" => "Change your topbar links hover color.");

$options6[] = array("label" => "Contact Panel Settings",
					"title" =>"Contact Panel Settings",
					"type" => "Title-1");

$options6[] = array("id" => "tmoption_contact_panel",
					"label" => "Show Contact Number?",
					"type" => "select",
					"description" => "Enable or Disable Contact No.",
					"options" => array('yes' => 'Yes','no' => 'No'));

$options6[] = array("id" => "tmoption_Contact_Text",
					"label" => "Your Text",
					"type" => "textbox",
					"std" => "Call Us Now",
					"description" => "Change your Contact Text.");


$options6[] = array("id" => "tmoption_topbar_phone",
					"label" => "Phone No",
					"type" => "textbox",
					"std" => "Toll Free :0123-456-789",
					"description" => "Change your Phone Number.");
					
					
$options6[] = array("label" => "Top Custom Banner",
					"title" =>"Top Custom Banner Settings",
					"type" => "Title-1");
					
$options6[] = array("id" => "tmoption_custom_banner",
					"label" => "Show Custom Banner",
					"type" => "select",
					"description" => "Displays Custom Banner.",
					"options" => array('yes' => 'Yes','no' => 'No'));
										
$options6[] = array("id" => "tmoption_custom_text1",
					"label" => "Custom Text 1",
					"std" => "UP TO 5% REWARD",
					"type" => "textbox",
					"description" => "Change your top custom text.");

$options6[] = array("id" => "tmoption_custom_text1_desc",
					"label" => "Custom Text 1 Desc",
					"std" => "At vero eos et accusamus et iusto odio dignis",
					"type" => "textbox",
					"description" => "Change your top custom text Desc.");					
					
$options6[] = array("id" => "tmoption_custom_text2",
					"label" => "Custom Text 2",
					"std" => "DELIVERY SCHEDULE",
					"type" => "textbox",
					"description" => "Change your top custom text.");		

$options6[] = array("id" => "tmoption_custom_text2_desc",
					"label" => "Custom Text 2 Desc",
					"std" => "At vero eos et accusamus et iusto odio dignis",
					"type" => "textbox",
					"description" => "Change your top custom text Desc.");										
					
$options6[] = array("id" => "tmoption_custom_text3",
					"label" => "Custom Text 3",
					"std" => "SAME DAY SHIPPING",
					"type" => "textbox",
					"description" => "Change your top custom text.");					
					
$options6[] = array("id" => "tmoption_custom_text3_desc",
					"label" => "Custom Text 3 Desc",
					"std" => "At vero eos et accusamus et iusto odio dignis",
					"type" => "textbox",
					"description" => "Change your top custom text Desc.");								

//=============================================== Shop Settings ==================================================================
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) :

$options7 = array(array());
									
$options7[] = array("id" => "tmoption_shop_items",
					"label" => "Products per Shop Page",	
					"type" => "textbox", 
					"std" => "12",				
					"description" => "Dispaly total number of products on shop page for pagination");
					
$options7[] = array("id" => "tmoption_shop_per_items",
					"label" => "Products per row on Shop Page",	
					"type" => "textbox", 
					"std" => "4",				
					"description" => "Dispaly number of products per row on shop page");									
					
$options7[] = array("id" => "tmoption_related_items",
					"label" => "Related Products",	
					"type" => "textbox", 
					"std" => "8",				
					"description" => "Dispaly total number of Related products on shop page");
									
$options7[] = array("id" => "tmoption_upsells_items",
					"label" => "Upsell Products",	
					"type" => "textbox", 
					"std" => "8",				
					"description" => "Dispaly total number of Upsell sell products on shop page");
					
$options7[] = array("id" => "tmoption_crosssell_items",
					"label" => "Cross Sell Products",	
					"type" => "textbox", 
					"std" => "8",				
					"description" => "Dispaly total number of Cross sell products on checkout page");
					
endif;
															
?>
<!-- =========================================== Call Font Script =========================================== -->

<div class="main-block">
<div class="icon-templatemela"><img src="<?php echo get_option( 'siteurl' ).'/wp-content/themes/'.get_option( 'template' ).'/templatemela/logo.png'; ?>" /></div>
<h2 class="title-themeset">TemplateMela - Theme Settings</h2>
<?php  global $result;   
	if ($result=='success') 
	 echo '<div class="updated settings-error" id="setting-error-settings_updated"><p><strong>Settings saved.</strong></p></div>';
?>
<div class="tab_main">
<!-- =========================================== Start Tab =============================================== -->
<div id="tab-container" class='tab-container'>
  <ul class='etabs'>
    <li class="tab first"> <a href="#General" title="General Settings"><img src="<?php echo get_template_directory_uri(); ?>/images/megnor/admin/general_setting.png" alt="general"/> <span class="title">
      <?php echo __('General','templatemela');?>
      </span></a> </li>
    <li class="tab topbar"> <a href="#Topbar"><img src="<?php echo get_template_directory_uri(); ?>/images/megnor/admin/topbar_setting.png" alt="color"/> <span class="title">
      <?php echo __('Topbar','templatemela');?>
      </span></a> </li>
    <li class="tab header"> <a href="#Header"><img src="<?php echo get_template_directory_uri(); ?>/images/megnor/admin/header_setting.png" alt="color"/> <span class="title">
      <?php echo __('Header','templatemela');?>
      </span></a> </li>
    <li class="tab"> <a href="#Content"><img src="<?php echo get_template_directory_uri(); ?>/images/megnor/admin/content_settings.png" alt="color"/> <span class="title">
      <?php echo __('Content','templatemela');?>
      </span></a> </li>
    <li class="tab"> <a href="#Footer"><img src="<?php echo get_template_directory_uri(); ?>/images/megnor/admin/footer_settings.png" alt="color"/> <span class="title">
      <?php echo __('Footer','templatemela');?>
      </span></a> </li>
	<?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) : ?>
    <li class="tab"> <a href="#shop"><img src="<?php echo get_template_directory_uri(); ?>/images/megnor/admin/shop_setting.png" alt="blog"/> <span class="title">
      <?php echo __('Shop','templatemela');?>
      </span></a> </li>
		<?php endif; ?>
 </ul>
  <!-- Start Panel-container -->
  <div class='panel-container'>
    <!-- =========================================== Start General Setting =========================================== -->
    <div id="General">
      <form enctype="multipart/form-data" method="post" id="settingForm1" name="settingForm1">
        <input type="hidden" name="action" value="save_options1"  />
        <?php 
	 if(!isset( $_REQUEST['action'])) {$_REQUEST['action']=''; }
	 if(!isset( $_REQUEST['reset1'])) {$_REQUEST['reset1']=''; }
       if ( 'save_options1' == $_REQUEST['action'] )
       {
 			foreach ($options1 as $value) {
				if(!isset( $value['id'] )) {$value['id']=''; }
				if(isset( $value['id'] ) && isset($_REQUEST[ $value['id'] ] )){
					update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
				}
						
			} 
      	} 
	    else if( 'reset1' == $_REQUEST['reset1'] )
        {
 	    	foreach ($options1 as $value) 
	    	{
				if(!isset( $value['id'] )) {$value['id']=''; }
				delete_option( $value['id'] ); 
          	}
        }
    	?>
        <div class="form-table">
        <div class="background-title">
          <label>
          <?php echo __('General Settings', 'templatemela'); ?>
          </label>
        </div>
        <?php
       $i= 0;
	   foreach ($options1 as $value) { 
	   if(!isset( $value['type'] )) {$value['type'] =''; }
	   switch ( $value['type'] ) {
	 

	 case 'textbox':?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <input class="regular-text" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo esc_attr(stripslashes(get_option( $value['id'] ))); } else {  	 if(!isset( $value['std'] )) {$value['std'] =''; }  echo esc_attr(stripslashes($value['std'])); } ?>" />
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
        </div>
        <!--odd-even-->
        <?php
		break;
		
	case 'upload': ?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
            <br />
            <br />
            <?php if(get_option('tmoption_logo_image') != '') { ?>
            <img src="<?php echo esc_url(get_option('tmoption_logo_image'));?>" id="slider_logodisplay"/>&nbsp;<a id="slider_remove_link1" href="javascript:removeImage1();"><img src="<?php echo get_template_directory_uri(); ?>/images/megnor/admin/remove.png" /></a>
            <?php } ?>
            <script>
			function removeImage1() {
				document.getElementById("tmoption_logo_image").value="";
				document.getElementById("slider_logodisplay").src="";
				document.getElementById("slider_remove_link1").innerHTML="";					
			}
		</script>
          </div>
          <div class="content">
            <input style=" <?php if($value['id'] != 'tmoption_logo_image') { echo 'display:none' ; } ?> " class="regular-text" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo esc_attr(stripslashes(get_option( $value['id'] ))); } else { echo esc_attr(stripslashes($value['std'])); } ?>"/>
            <input id="upload_image_button" class="button-primary" type="button" value="Upload Logo" />
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
        </div>
        <!--even odd setting-->
        <?php	break;
		
	case 'select':	?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <select class="select_input" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>">
              <?php foreach ($value['options'] as $op_id => $suboption) { ?>
              <option value="<?php echo esc_attr($op_id); ?>" <?php if (get_option($value['id']) == $op_id) { echo ' selected="selected"'; } ?>><?php echo esc_attr($suboption); ?></option>
              <?php } ?>
            </select>
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
        </div>
        <!--even-Odd-->
        <?php	break; //end Switch
		
	case 'textbox-1':?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <?php if ( get_option( $value['id'] ) != "") { 
			$stylecolor =  stripslashes(get_option( $value['id'] )); 
			} else { 
			$stylecolor = stripslashes($value['std']); } 
			$stylecolor = 'style="background-color:#'.$stylecolor.'"';?>
            <input class="regular-text1" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo esc_attr(stripslashes(get_option( $value['id'] ))); } else { echo esc_attr(stripslashes($value['std'])); } ?>" <?php echo $stylecolor; ?> />
            <span class="description"><?php echo esc_attr($value['description']); echo esc_attr(get_option( $value['id']));?></span> </div>
        </div>
        <!--odd-even-->
        <?php	break;
		
	case 'upload-1': ?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
            <br />
            <br />
            <?php if(get_option('tmoption_favicon_icon') != '') { ?>
            <img src="<?php echo esc_url(get_option('tmoption_favicon_icon'));?>" id="slider_favicondisplay"/>&nbsp;<a id="slider_remove_link2" href="javascript:removeImage2();"><img src="<?php echo get_template_directory_uri(); ?>/images/megnor/admin/remove.png" /></a>
            <?php }	?>
            <script>
			function removeImage2() {
				document.getElementById("tmoption_favicon_icon").value="";
				document.getElementById("slider_favicondisplay").src="";
				document.getElementById("slider_remove_link2").innerHTML="";					
			}
		</script>
          </div>
          <div class="content">
            <input class"favicon-upload" style="<?php if($value['id'] != 'tmoption_favicon_icon') { echo 'display:none' ; } ?> " class="regular-text" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php echo get_option($value['id']); ?>" />
            <input id="upload_favicon_icon" class="button-primary" type="button" value="Upload Favicon Icon" />
            <span class="description"><?php echo esc_attr($value['description']); ?></span><br />
          </div>
        </div>
        <!--even odd setting-->
        <?php	break;
		
	case 'upload-3': ?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
            <br />
            <br />
            <?php if(get_option('tmoption_background_upload') != '') { ?>
            <img src="<?php echo esc_url(get_option('tmoption_background_upload'));?>" id="slider_backgrounddisplay"/>&nbsp;<a id="slider_remove_link3" href="javascript:removeImage3();"><img src="<?php echo get_template_directory_uri(); ?>/images/megnor/admin/remove.png" /></a>
            <?php } ?>
            <script>
			function removeImage3() {
				document.getElementById("tmoption_background_upload").value="";
				document.getElementById("slider_backgrounddisplay").src="";
				document.getElementById("slider_remove_link3").innerHTML="";					
			}
		</script>
          </div>
          <div class="content">
            <input style=" <?php if($value['id'] != 'tmoption_background_upload') { echo 'display:none' ; } ?> " class="regular-text" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php echo esc_attr(get_option($value['id'])); ?>" />
            <input id="upload_backgroundimage_button" class="button-primary" type="button" value="Upload Image" />
            <span class="description"><?php echo esc_attr($value['description']); ?></span><br />
          </div>
        </div>
        <!--even odd setting-->
        <?php	break;
		
	case 'texture': 	
		?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
          <?php } else { ?>
          <div class="even setting_main">
            <?php }
	    $img_dir = get_template_directory_uri() .'/images/megnor/colorpicker/pattern/';
		?>
            <div class="title">
              <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
            </div>
            <div class="content">
              <div class="tm_content">
                <div class="thumb-sel"> <img class="thumb" src="<?php if ( get_option( $value['id'] ) != ""){echo $img_dir.get_option($value['id']);}else{echo $img_dir.$value['std'];} ?>" /> <span id="switch" class="close"></span> </div>
                <div class="thumb-list">
                  <ul>
                    <?php foreach ($value['options'] as $opt_key => $opt_val) { 
		    if ( get_option( $value['id'] ) != "")   {
            if($opt_key == get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";}
            } else {
            if($opt_key == ($value['std'])) { $checked = "checked=\"checked\""; } else { $checked = ""; }
            } ?>
                    <li>
                      <input type="radio" name="<?php echo esc_attr($value['id']) ?>" value="<?php echo esc_attr($opt_key) ?>" <?php echo esc_attr($checked); ?>/>
                      <img class="thumb" src="<?php echo esc_url($img_dir.$opt_key) ?>" title="<?php echo esc_attr($opt_val) ?>" /> </li>
                    <?php } ?>
                  </ul>
                </div>
              </div>
              <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
          </div>
          <?php break;
		
	case 'Title-1':		
		 if(!isset( $value['id'] )) {$value['id'] =''; }
		?>
          <div class="background-title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <?php break;
		
		}
	   $i++;
      }?>
        </div>
        <!--from-table-->
        <div class="submit">
          <input type="submit" value="Save Changes" class="button-primary" name="Submit" >
        </div>
      </form>
      <!--mainform-->
      <!-- reset Button -->
      <div class="reset-option">
        <form enctype="multipart/form-data" method="post" id="settingForm1" name="settingFormx"  >
          <p class="submit">
            <input type="hidden" name="reset1" value="reset1" />
            <input type="submit" value="Set Default" class="button-primary" name="reset"/>
          </p>
        </form>
      </div>
      <!-- End Reset Button -->
    </div>
    <!--general-setting-->
    <div style="clear:both"></div>
    <!-- =========================================== End General Settings =========================================== -->
    <!-- =========================================== Start Top bar Settings =========================================== -->
    <div id="Topbar">
      <form enctype="multipart/form-data" method="post" id="settingForm6" name="settingForm6"  >
        <input type="hidden" name="action" value="save_options6" />
        <?php

	if(!isset( $_REQUEST['action'] )) {$_REQUEST['action']=''; }
	if(!isset( $_REQUEST['reset6'] )) {$_REQUEST['reset6']=''; }
    if ( 'save_options6' == $_REQUEST['action'] )
     {
	 
 		foreach ($options6 as $value) {
				if(!isset( $value['id'] )) {$value['id']=''; }
				
				if(isset( $value['id'] ) && isset($_REQUEST[ $value['id'] ] )){
					update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
				}
		}
	    } 
		
     else if( 'reset6' == $_REQUEST['reset6'] )
      {
 	    foreach ($options6 as $value) 
	     {
				 if(!isset( $value['id'] )) {$value['id']=''; }
				 delete_option( $value['id'] ); 
        }
      }
    ?>
        <div class="form-table">
        <div class="background-title">
          <label>
          <?php echo __('Topbar Settings', 'templatemela'); ?>
          </label>
        </div>
        <?php
     foreach ($options6 as $value) { 
		if(!isset( $value['type'] )) {$value['type'] =''; }
	switch ( $value['type'] ) {
	
		case 'select':	?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <select class="select_input" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>">
              <?php foreach ($value['options'] as $op_id => $suboption) { ?>
              <option value="<?php echo esc_attr($op_id); ?>" <?php if (get_option($value['id']) == $op_id) { echo ' selected="selected"'; } ?>><?php echo esc_attr($suboption); ?></option>
              <?php } ?>
            </select>
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
        </div>
        <!--even-Odd-->
        <?php	break; //end Switch
		case 'textbox':?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo $value['id']; ?>"><?php echo $value['label']; ?></label>
          </div>
          <div class="content">
            <input class="regular-text" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo esc_attr(stripslashes(get_option( $value['id'] ))); } else { echo esc_attr(stripslashes($value['std'])); } ?>" />
            <span class="description"><?php echo $value['description']; echo get_option( $value['id']);?></span> </div>
        </div>
        <!--odd-even-->
        <?php
			
		break;
		case 'textbox-3':?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <input class="regular-text" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo esc_attr(stripslashes(get_option( $value['id'] ))); } else { echo esc_attr(stripslashes($value['std'])); } ?>" />
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
        </div>
        <!--odd-even-->
        <?php
		break;
		
		case 'textbox-1':?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
          <?php } else { ?>
          <div class="even setting_main">
            <?php }?>
            <div class="title">
              <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
            </div>
            <div class="content">
              <?php if ( get_option( $value['id'] ) != "") { 
			$stylecolor =  stripslashes(get_option( $value['id'] )); 
			} else { 
			$stylecolor = stripslashes($value['std']); } 
			$stylecolor = 'style="background-color:#'.$stylecolor.'"'; ?>
              <input class="regular-text1" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'] )); } else { echo stripslashes($value['std']); } ?>" <?php echo $stylecolor; ?> />
              <span class="description"><?php echo esc_attr($value['description']); echo get_option( $value['id']);?></span> </div>
          </div>
          <!--odd-even-->
          <?php
	break;
	
	case 'Title-1':		
	if(!isset( $value['id'] )) {$value['id'] =''; }
	?>
          <div class="background-title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <?php break;
		
		   }
		$i++;
      }
?>
        </div>
        <!--form table-->
        <div class="submit">
          <input type="submit" value="Save Changes" class="button-primary" name="Submit" >
        </div>
      </form>
      <!-- reset Button -->
      <div class="reset-option">
        <form enctype="multipart/form-data" method="post" id="settingForm6" name="settingForm6"   >
          <p class="submit">
            <input type="hidden" name="reset6" value="reset6" />
            <input type="submit" value="Set Default" class="button-primary" name="reset"/>
          </p>
        </form>
      </div>
      <!-- End Reset Button -->
    </div>
    <!---#color-->
    <script type="text/javascript">
function Ajax(){

var xmlHttp;
	try{	
		xmlHttp=new XMLHttpRequest();// Firefox, Opera 8.0+, Safari
	}
	catch (e){
		try{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer
		}
		catch (e){
		    try{
				xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e){
				alert("No AJAX!?");
				return false;
			}
		}
	}

xmlHttp.onreadystatechange=function(){
	if(xmlHttp.readyState==4){
		document.getElementById('tab_main').innerHTML=xmlHttp.responseText;
		return false;
	}
}
xmlHttp.send(null);
}
window.onload=function(){
}
</script>
    <div style="clear:both"></div>
    <!-- =========================================== End Top bar Settings =========================================== -->
	
	
	
    <!-- =========================================== Start Shop Settings =========================================== -->
	<?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) : ?>
    <div id="shop">
      <form enctype="multipart/form-data" method="post" id="settingForm7" name="settingForm7" >
        <input type="hidden" name="action" value="save_options7" />
        <?php
	if(!isset( $_REQUEST['action'])) {$_REQUEST['action']=''; }	
	if(!isset( $_REQUEST['reset7'])) {$_REQUEST['reset7']=''; }	
	
       if ( 'save_options7' == $_REQUEST['action'] )
        {
 	    	foreach ($options7 as $value) {
	    	
				if(!isset( $value['id'] )) {$value['id']=''; }
				if(isset( $value['id'] ) && isset($_REQUEST[ $value['id'] ] )){
					update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
				}

			} 
	     } 
       else if( 'reset7' == $_REQUEST['reset7'] )
         {
 	     foreach ($options7 as $value) 
	     {
		 if(!isset( $value['id'] )) {$value['id']=''; }
		 delete_option( $value['id'] ); 
         }
      }
     ?>
        <div class="form-table">
        <div class="background-title">
         <label>
            <?php echo __('Shop Settings', 'templatemela'); ?>
          </label>
        </div>
        <?php
        foreach ($options7 as $value) { 
if(!isset( $value['type'])) {$value['type']=''; }	
	    switch ( $value['type'] ) {
	
	case 'textbox':?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <input class="regular-text" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'] )); } else { echo stripslashes($value['std']); } ?>" />
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
			
		
        </div>
        <!--odd-even-->
        <?php break;
			
	case 'select':	?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
          <?php } else { ?>
          <div class="even setting_main">
            <?php }?>
            <div class="title">
              <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
            </div>
            <div class="content">
              <select class="select_input" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>">
                <?php foreach ($value['options'] as $op_id => $suboption) { ?>
                <option value="<?php echo esc_attr($op_id); ?>" <?php if (get_option($value['id']) == $op_id) { echo ' selected="selected"'; } ?>><?php echo esc_attr($suboption); ?></option>
                <?php } ?>
              </select>
              <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
          </div>
          <!--even-Odd-->
          <?php	break; //end Switch
       }
     $i++;
    }
   ?>
        </div>
        <!--form-table-->
        <div class="submit">
          <input type="submit" value="Save Changes" class="button-primary" name="Submit" >
        </div>
      </form>
      <!-- reset Button -->
      <div class="reset-option">
        <form enctype="multipart/form-data" method="post" id="settingForm7" name="settingFormx" >
          <p class="submit">
            <input type="hidden" name="reset7" value="reset7" />
            <input type="submit" value="Set Default" class="button-primary" name="reset"/>
          </p>
        </form>
      </div>
      <!-- End Reset Button -->
    </div>
	<?php endif; ?>
    <!-- =========================================== End Shop Settings =========================================== -->

	<?php /*?><!-- =========================================== Start Slider Settings =========================================== -->
    <div id="slider">
      <form enctype="multipart/form-data" method="post" id="settingForm8" name="settingForm8" >
        <input type="hidden" name="action" value="save_options8" />
        <?php
	if(!isset( $_REQUEST['action'])) {$_REQUEST['action']=''; }	
	if(!isset( $_REQUEST['reset8'])) {$_REQUEST['reset8']=''; }	
	
       if ( 'save_options8' == $_REQUEST['action'] )
        {
 	    	foreach ($options8 as $value) {
	    	
				if(!isset( $value['id'] )) {$value['id']=''; }
				if(isset( $value['id'] ) && isset($_REQUEST[ $value['id'] ] )){
					update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
				}

			} 
	     } 
       else if( 'reset8' == $_REQUEST['reset8'] )
         {
 	     foreach ($options8 as $value) 
	     {
		 if(!isset( $value['id'] )) {$value['id']=''; }
		 delete_option( $value['id'] ); 
         }
      }
     ?>
        <div class="form-table">
        <div class="background-title">
          <label> <?php echo __('Slider Settings', 'templatemela'); ?> </label>
        </div>
        <?php
        foreach ($options8 as $value) { 
if(!isset( $value['type'])) {$value['type']=''; }	
	    switch ( $value['type'] ) {
	
	case 'textbox':?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <input class="regular-text" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php echo esc_attr(get_option($value['id'])); ?>" />
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
        </div>
        <!--odd-even-->
        <?php break;
			
	case 'select':	?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
          <?php } else { ?>
          <div class="even setting_main">
            <?php }?>
            <div class="title">
              <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
            </div>
            <div class="content">
              <select class="select_input" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>">
                <?php foreach ($value['options'] as $op_id => $suboption) { ?>
                <option value="<?php echo esc_attr($op_id); ?>" <?php if (get_option($value['id']) == $op_id) { echo ' selected="selected"'; } ?>><?php echo esc_attr($suboption); ?></option>
                <?php } ?>
              </select>
              <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
          </div>
          <!--even-Odd-->
          <?php	break; //end Switch
       }
     $i++;
    }
   ?>
        </div>
        <!--form-table-->
        <div class="submit">
          <input type="submit" value="Save Changes" class="button-primary" name="Submit" >
        </div>
      </form>
      <!-- reset Button -->
      <div class="reset-option">
        <form enctype="multipart/form-data" method="post" id="settingForm8" name="settingFormx" >
          <p class="submit">
            <input type="hidden" name="reset8" value="reset8" />
            <input type="submit" value="Set Default" class="button-primary" name="reset"/>
          </p>
        </form>
      </div>
      <!-- End Reset Button -->
    </div><?php */?>
    <!-- =========================================== End Slider Settings =========================================== -->	
	
	
    <!-- =========================================== Start Header Settings =========================================== -->
    <div id="Header">
      <form enctype="multipart/form-data" method="post" id="settingForm2" name="settingForm2"  >
        <input type="hidden" name="action" value="save_options2" />
        <?php

	if(!isset( $_REQUEST['action'] )) {$_REQUEST['action']=''; }
	if(!isset( $_REQUEST['reset2'] )) {$_REQUEST['reset2']=''; }
    if ( 'save_options2' == $_REQUEST['action'] )
     {
 		foreach ($options2 as $value) {
				if(!isset( $value['id'] )) {$value['id']=''; }
				if(isset( $value['id'] ) && isset($_REQUEST[ $value['id'] ] )){
					update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
				}
		}
	    } 
     else if( 'reset2' == $_REQUEST['reset2'] )
      {
 	    foreach ($options2 as $value) 
	     {
				 if(!isset( $value['id'] )) {$value['id']=''; }
				 delete_option( $value['id'] ); 
        }
      }
    ?>
        <div class="form-table">
        <div class="background-title">
			<label>
          <?php echo __('Header Settings', 'templatemela'); ?>
		  	</label>
        </div>
        <?php
     foreach ($options2 as $value) { 
		if(!isset( $value['type'] )) {$value['type'] =''; }
	switch ( $value['type'] ) {
	
		case 'select':	?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <select class="select_input" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>">
              <?php foreach ($value['options'] as $op_id => $suboption) { ?>
              <option value="<?php echo esc_attr($op_id); ?>" <?php if (get_option($value['id']) == $op_id) { echo ' selected="selected"'; } ?>><?php echo esc_attr($suboption); ?></option>
              <?php } ?>
            </select>
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
        </div>
        <!--even-Odd-->
        <?php	break; //end Switch
		
		case 'textbox-3':?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <input class="regular-text" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo esc_attr(stripslashes(get_option( $value['id'] ))); } else { echo esc_attr(stripslashes($value['std'])); } ?>" />
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
        </div>
        <!--odd-even-->
        <?php
		break;
		
		case 'textbox-1':?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <?php if ( get_option( $value['id'] ) != "") { 
			$stylecolor =  stripslashes(get_option( $value['id'] )); 
			} else { 
			$stylecolor = stripslashes($value['std']); } 
			$stylecolor = 'style="background-color:#'.$stylecolor.'"'; ?>
            <input class="regular-text1" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo esc_attr(stripslashes(get_option( $value['id'] ))); } else { echo esc_attr(stripslashes($value['std'])); } ?>" <?php echo $stylecolor; ?> />
            <span class="description"><?php echo esc_attr($value['description']); echo esc_attr(get_option( $value['id']));?></span> </div>
        </div>
        <!--odd-even-->
        <?php
			
		break;
			
		
case 'texture': 
		
		?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }
	            
				
								$img_dir = get_template_directory_uri().'/templatemela/images/';
								?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <div class="tm_content">
              <div class="thumb-sel"> <img class="thumb" src="<?php if ( get_option( $value['id'] ) != ""){echo $img_dir.get_option($value['id']);}else{echo $img_dir.$value['std'];} ?>" /> <span id="switch" class="close"></span> </div>
              <div class="thumb-list">
                <ul>
                  <?php foreach ($value['options'] as $opt_key => $opt_val) { 
					    if ( get_option( $value['id'] ) != "")   {
                        if($opt_key == get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";}
                        } else {
                        if($opt_key == ($value['std'])) { $checked = "checked=\"checked\""; } else { $checked = ""; }
                         } ?>
                  <li>
                    <input type="radio" name="<?php echo esc_attr($value['id']) ?>" value="<?php echo esc_attr($opt_key) ?>" <?php echo esc_attr($checked); ?>/>
                    <img class="thumb" src="<?php echo esc_url($img_dir.$opt_key) ?>" title="<?php echo esc_attr($opt_val) ?>" /> </li>
                  <?php } ?>
                </ul>
              </div>
            </div>
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
        </div>
        <?php
				break;
		case 'upload': ?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <input style=" <?php if($value['id'] != 'tmoption_background_upload') { echo 'display:none' ; } ?> " class="regular-text" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php echo esc_attr(get_option($value['id'])); ?>" />
            <input id="upload_image_button1" class="button-primary" type="button" value="Upload Image" />
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
        </div>
        <!--even odd setting-->
        <?php	break;
		
		case 'upload-1': ?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
          <?php } else { ?>
          <div class="even setting_main">
            <?php }?>
            <div class="title">
              <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
              <br />
              <br />
              <?php if(get_option('tmoption_header_background_upload') != '') { ?>
              <img src="<?php echo esc_url(get_option('tmoption_header_background_upload'));?>" id="slider_backgrounddisplay1" class="thumb"/>&nbsp;<a id="slider_remove_link31" href="javascript:removeImage31();"><img src="<?php echo get_template_directory_uri(); ?>/images/megnor/admin/remove.png" /></a>
              <?php } ?>
              <script>
			function removeImage31() {
				document.getElementById("tmoption_header_background_upload").value="";
				document.getElementById("slider_backgrounddisplay1").src="";
				document.getElementById("slider_remove_link31").innerHTML="";					
			}
		</script>
            </div>
            <div class="content">
              <input style=" <?php if($value['id'] != 'tmoption_header_background_upload') { echo 'display:none' ; } ?> " class="regular-text" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php echo esc_attr(get_option($value['id'])); ?>" />
              <input id="upload_backgroundimage_button" class="button-primary" type="button" value="Upload Image" />
              <span class="description"><?php echo esc_attr($value['description']); ?></span><br />
            </div>
          </div>
          <!--even odd setting-->
          <?php	break;
		case 'Title-1':		
		 if(!isset( $value['id'] )) {$value['id'] =''; }
		?>
          <div class="background-title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <?php break;
		  
		  case 'radio':?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content cont-layout">
            <?php
			 foreach ($value['options'] as $key=>$option) {
			 if ( get_option( $value['id'] ) != "")   {
			 if($key == get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";}
			} else {
			if($key == ($value['std'])) { $checked = "checked=\"checked\""; } else { $checked = ""; }
			} ?>
            <div class="cont-layout-option">
              <input type="radio" name="<?php echo esc_attr($value['id']); ?>" value="<?php echo esc_attr($key); ?>" <?php echo esc_attr($checked); ?> />
              <br/>
              <?php if($key == '1'){?>
              <img src="<?php echo get_template_directory_uri() . '/images/megnor/admin/header1.png' ?>" alt="" />
              <?php } ?>
              <?php if($key == '2'){?>
              <img src="<?php echo get_template_directory_uri() . '/images/megnor/admin/header2.png' ?>" alt="" />
              <?php } ?>
              <?php if($key == '3'){?>
              <img src="<?php echo get_template_directory_uri() . '/images/megnor/admin/header3.png' ?>" alt="" />
              <?php } ?>
              <?php if($key == '4'){?>
              <img src="<?php echo get_template_directory_uri() . '/images/megnor/admin/header4.png' ?>" alt="" />
              <?php } ?>
              <br/>
              <?php // echo $option; ?>
            </div>
            <?php } ?>
          </div>
          <?php	break;		
		
		   }
		$i++;
      }
?>
        </div>
        <!--form table-->
        <div class="submit">
          <input type="submit" value="Save Changes" class="button-primary" name="Submit" >
        </div>
      </form>
      <!-- reset Button -->
      <div class="reset-option">
        <form enctype="multipart/form-data" method="post" id="settingForm2" name="settingFormx"   >
          <p class="submit">
            <input type="hidden" name="reset2" value="reset2" />
            <input type="submit" value="Set Default" class="button-primary" name="reset"/>
          </p>
        </form>
      </div>
      <!-- End Reset Button -->
    </div>
    <!---#color-->
    <script type="text/javascript">
function Ajax(){

var xmlHttp;
	try{	
		xmlHttp=new XMLHttpRequest();// Firefox, Opera 8.0+, Safari
	}
	catch (e){
		try{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer
		}
		catch (e){
		    try{
				xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e){
				alert("No AJAX!?");
				return false;
			}
		}
	}

xmlHttp.onreadystatechange=function(){
	if(xmlHttp.readyState==4){
		document.getElementById('tab_main').innerHTML=xmlHttp.responseText;
		return false;
	}
}
xmlHttp.send(null);
}
window.onload=function(){
}
</script>
    <!-- =========================================== Start Content Settings =========================================== -->
    <div id="Content">
      <form enctype="multipart/form-data" method="post" id="settingForm3" name="settingForm3" >
        <input type="hidden" name="action" value="save_options3" />
        <?php
	 if(!isset( $_REQUEST['action'])) {$_REQUEST['action']=''; }
	 if(!isset( $_REQUEST['reset3'])) {$_REQUEST['reset3']=''; }

       if ( 'save_options3' == $_REQUEST['action'] )
           {
 	    	foreach ($options3 as $value) {
	    	
				if(!isset( $value['id'] )) {$value['id']=''; }
				if(isset( $value['id'] ) && isset($_REQUEST[ $value['id'] ] )){
					update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
				}

			 }
 
	     } 
       else if( 'reset3' == $_REQUEST['reset3'] )
         {
 	     foreach ($options3 as $value) 
	     {
			 if(!isset( $value['id'] )) {$value['id']=''; }
			 delete_option( $value['id'] ); 
         }
      }
     ?>
        <div class="form-table">
        <div class="background-title">
          <label>
          <?php echo __('Content Settings', 'templatemela'); ?>
          </label>
        </div>
        <?php
        foreach ($options3 as $value) { 
	    switch ( $value['type'] ) {

		
		case 'select':
		
		?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <select class="select_input" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>">
              <?php foreach ($value['options'] as $op_id => $suboption) { ?>
              <option value="<?php echo esc_attr($op_id); ?>" <?php if (get_option($value['id']) == $op_id) { echo ' selected="selected"'; } ?>><?php echo esc_attr($suboption); ?></option>
              <?php } ?>
            </select>
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
        </div>
        <?php
		break;
		
		case 'textbox-3':?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <input class="regular-text" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo esc_attr(stripslashes(get_option( $value['id'] ))); } else { echo esc_attr(stripslashes($value['std'])); } ?>" />
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
        </div>
        <!--odd-even-->
        <?php
		break;
		
		case 'textbox-1':?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <?php if ( get_option( $value['id'] ) != "") { 
					$stylecolor =  stripslashes(get_option( $value['id'] )); 
					} else { 
					$stylecolor = stripslashes($value['std']); } 
					$stylecolor = 'style="background-color:#'.$stylecolor.'"'; ?>
            <input class="regular-text1" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'] )); } else { echo stripslashes($value['std']); } ?>" <?php echo $stylecolor; ?> />
            <span class="description"><?php echo esc_attr($value['description']); echo esc_attr(get_option( $value['id']));?></span> </div>
        </div>
        <!--odd-even-->
        <?php
			
		break;
		case 'textbox':?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
          <?php } else { ?>
          <div class="even setting_main">
            <?php }?>
            <div class="title">
              <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
            </div>
            <div class="content">
              <input class="regular-text" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo esc_attr(stripslashes(get_option( $value['id'] ))); } else { echo esc_attr(stripslashes($value['std'])); } ?>" />
              <span class="description"><?php echo esc_attr($value['description']); echo esc_attr(get_option( $value['id']));?></span> </div>
          </div>
          <!--odd-even-->
          <?php
			
		break;
       }
     $i++;
    }
   ?>
        </div>
        <!--form-table-->
        <div class="submit">
          <input type="submit" value="Save Changes" class="button-primary" name="Submit" >
        </div>
      </form>
      <!-- reset Button -->
      <div class="reset-option">
        <form enctype="multipart/form-data" method="post" id="settingForm3" name="settingFormx" >
          <p class="submit">
            <input type="hidden" name="reset3" value="reset3" />
            <input type="submit" value="Set Default" class="button-primary" name="reset"/>
          </p>
        </form>
      </div>
      <!-- End Reset Button -->
    </div>
    <!--Font-->
    <!-- =========================================== Start Footer Settings =========================================== -->
    <div id="Footer">
      <form enctype="multipart/form-data" method="post" id="settingForm4" name="settingForm4"  >
        <input type="hidden" name="action" value="save_options4" />
        <?php
	 if(!isset( $_REQUEST['action'])) {$_REQUEST['action']=''; }
	 if(!isset( $_REQUEST['reset4'])) {$_REQUEST['reset4']=''; }

    if ( 'save_options4' == $_REQUEST['action'] )
     {
 		foreach ($options4 as $value) {
				if(!isset( $value['id'] )) {$value['id']=''; }
				if(isset( $value['id'] ) && isset($_REQUEST[ $value['id'] ] )){
					update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
				}

		}
	    } 
     else if( 'reset4' == $_REQUEST['reset4'] )
      {
 	    foreach ($options4 as $value) 
	     {
			 if(!isset( $value['id'] )) {$value['id']=''; }
			 delete_option( $value['id'] ); 
        }
      }
    ?>
        <div class="form-table">
        <div class="background-title">
          <label>
          <?php echo __('Footer Settings', 'templatemela'); ?>
          </label>
        </div>
        <?php
     foreach ($options4 as $value) { 
	if(!isset( $value['type'])) {$value['type']=''; }
	switch ( $value['type'] ) {
	
		case 'select':	?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <select class="select_input" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>">
              <?php foreach ($value['options'] as $op_id => $suboption) { ?>
              <option value="<?php echo esc_attr($op_id); ?>" <?php if (get_option($value['id']) == $op_id) { echo ' selected="selected"'; } ?>><?php echo $suboption; ?></option>
              <?php } ?>
            </select>
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
        </div>
        <!--even-Odd-->
        <?php	break; //end Switch
		
		case 'textbox-3':?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <input class="regular-text" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo esc_attr(stripslashes(get_option( $value['id'] ))); } else { echo esc_attr(stripslashes($value['std'])); } ?>" />
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
        </div>
        <!--odd-even-->
        <?php
		break;
		
		case 'textbox-1':?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <?php if ( get_option( $value['id'] ) != "") { 
					$stylecolor =  stripslashes(get_option( $value['id'] )); 
					} else { 
					$stylecolor = stripslashes($value['std']); } 
					$stylecolor = 'style="background-color:#'.$stylecolor.'"'; ?>
            <input class="regular-text1" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'] )); } else { echo stripslashes($value['std']); } ?>" <?php echo $stylecolor; ?> />
            <span class="description"><?php echo esc_attr($value['description']); echo esc_attr(get_option( $value['id']));?></span> </div>
        </div>
        <!--odd-even-->
        <?php
			
		break;
			
		
case 'texture': 
		
		?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }
	        $img_dir = get_template_directory_uri().'/images/megnor/admin/';
			?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <div class="tm_content">
              <div class="thumb-sel"> <img class="thumb" src="<?php if ( get_option( $value['id'] ) != ""){echo $img_dir.get_option($value['id']);}else{echo $img_dir.$value['std'];} ?>" /> <span id="switch" class="close"></span> </div>
              <div class="thumb-list">
                <ul>
                  <?php foreach ($value['options'] as $opt_key => $opt_val) { 
				if ( get_option( $value['id'] ) != "")   {
				if($opt_key == get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";}
				} else {
				if($opt_key == ($value['std'])) { $checked = "checked=\"checked\""; } else { $checked = ""; }
				 } ?>
                  <li>
                    <input type="radio" name="<?php echo esc_attr($value['id']) ?>" value="<?php echo esc_attr($opt_key) ?>" <?php echo esc_attr($checked); ?>/>
                    <img class="thumb" src="<?php echo esc_url($img_dir.$opt_key) ?>" title="<?php echo esc_attr($opt_val) ?>" /> </li>
                  <?php } ?>
                </ul>
              </div>
            </div>
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
        </div>
        <?php break;
	case 'upload': ?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
        <?php } else { ?>
        <div class="even setting_main">
          <?php }?>
          <div class="title">
            <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
          </div>
          <div class="content">
            <input style=" <?php if($value['id'] != 'tmoption_background_upload') { echo 'display:none' ; } ?> " class="regular-text" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php echo esc_attr(get_option($value['id'])); ?>" />
            <input id="upload_image_button1" class="button-primary" type="button" value="Upload Image" />
            <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
        </div>
        <!--even odd setting-->
        <?php	break;
		case 'textbox':?>
        <?php if( $i % 2 != 0) { ?>
        <div class="odd setting_main">
          <?php } else { ?>
          <div class="even setting_main">
            <?php }?>
            <div class="title">
              <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_attr($value['label']); ?></label>
            </div>
            <div class="content">
              <input class="regular-text" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'] )); } else { echo stripslashes($value['std']); } ?>" />
              <span class="description"><?php echo esc_attr($value['description']); ?></span> </div>
          </div>
          <!--odd-even-->
          <?php
			
		break;
		
		   }
		$i++;
      }
?>
        </div>
        <!--form table-->
        <div class="submit">
          <input type="submit" value="Save Changes" class="button-primary" name="Submit" >
        </div>
      </form>
      <!-- reset Button -->
      <div class="reset-option">
        <form enctype="multipart/form-data" method="post" id="settingForm4" name="settingFormx"   >
          <p class="submit">
            <input type="hidden" name="reset4" value="reset4" />
            <input type="submit" value="Set Default" class="button-primary" name="reset"/>
          </p>
        </form>
      </div>
      <!-- End Reset Button -->
    </div>
    <!---#color-->
    <!-- =========================================== End Footer Settings =========================================== -->
    <!-- =========================================== Start Portfolio Settings =========================================== -->
    
<div id="ajax-response"></div>
