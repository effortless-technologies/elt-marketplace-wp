<?php
class WCMp_Vendor_Shop_Seo_Admin {

  public $settings;

    public function __construct() {
			global $WCMp_Vendor_Shop_SEO;
			
			add_filter("settings_general_tab_options", array($this, "seo_settings_option"));	
			add_filter("settings_general_tab_new_input", array($this, "seo_settings_sanitize"), 10, 2);
			
			if(isset($WCMp_Vendor_Shop_SEO->option_settings['vendor_seo_enabled']) && $WCMp_Vendor_Shop_SEO->option_settings['vendor_seo_enabled'] == 'Enable') {
				add_action( 'show_user_profile', array($this,'wcmp_seo_extra_profile_fields') );
				add_action( 'edit_user_profile', array($this,'wcmp_seo_extra_profile_fields') );
				add_action( 'personal_options_update', array($this,'wcmp_seo_save_extra_profile_fields') );
				add_action( 'edit_user_profile_update', array($this,'wcmp_seo_save_extra_profile_fields') );
				add_action( 'add_meta_boxes', array($this,'wcmp_seo_metabox' ));
				add_action( 'save_post', array( $this, 'wcmp_save_seo_meta' ) );
			}
    }
    
    function seo_settings_option($settings_tab_options) {
		global $WCMp, $WCMp_Vendor_Shop_SEO;
		
		$settings_tab_options["sections"]["seo_settings_section"] = array( "title" =>  __('Vendor Shop SEO', $WCMp_Vendor_Shop_SEO->text_domain),
																																						 "ref" => &$this,
																																						 "fields" => array( "vendor_seo_enabled" => array('title' => __('Enable ', $WCMp_Vendor_Shop_SEO->text_domain), 'type' => 'checkbox', 'name' => 'vendor_seo_enabled', 'value' => 'Enable' )
																																																)
																																						 );
		return $settings_tab_options;
	}
	
	function seo_settings_sanitize($new_input, $input) {
		global $WCMp, $WCMp_Vendor_Shop_SEO;		
		if( isset( $input['vendor_seo_enabled'] ) )
      $new_input['vendor_seo_enabled'] = sanitize_text_field( $input['vendor_seo_enabled'] );    
		return $new_input;
	}	
	
	function seo_settings_section_info() {
		
	}

	// save Seo Meta Field
	public function wcmp_save_seo_meta($post_id) {
			global $WCMp_Vendor_Shop_SEO;        
			if(isset($_POST['_wcmp_seo_product_meta_title']) ) {
					update_post_meta($post_id,'_wcmp_seo_product_meta_title',$_POST['_wcmp_seo_product_meta_title']);
			}
			if(isset($_POST['_wcmp_seo_product_meta_keyword']) ) {
					update_post_meta($post_id,'_wcmp_seo_product_meta_keyword',$_POST['_wcmp_seo_product_meta_keyword']);
			}
			if(isset($_POST['_wcmp_seo_product_meta_description']) ) {
					update_post_meta($post_id,'_wcmp_seo_product_meta_description',$_POST['_wcmp_seo_product_meta_description']);
			}
	}
	// Register the meta box for Seo Fields
	public function wcmp_seo_metabox() {
		global $WCMp_Vendor_Shop_SEO;
		$screens = array( 'product' );
		foreach ( $screens as $screen ) {
			add_meta_box('wcmp_seo_section',	__( 'WCMp SEO Information', $WCMp_Vendor_Shop_SEO->text_domain ),array($this,'wcmp_seo_section_display_callback'),	$screen, 'normal', 'high'	);
		}
	}

	public function wcmp_seo_section_display_callback() {
		global $WCMp_Vendor_Shop_SEO, $post, $WCMp;			
		?>
		<table class="form-table">
			<tbody>					
				<?php $WCMp->wcmp_wp_fields->dc_generate_form_field( $this->get_seo_product_field( $post->ID ), array('in_table' => 1) ); ?>
			</tbody>				
		</table>
		<?php
	}

	public function wcmp_seo_extra_profile_fields($user) {
		global $WCMp_Vendor_Shop_SEO, $WCMp;
		if(isset($user->caps['dc_vendor']) && $user->caps['dc_vendor'] == 1) {
			$this->get_seo_user_field($user->ID);
				?>
			<table class="form-table">
				<tbody>
					<tr>
						<th colspan="2">
							<label for="google_analytics" > <?php echo  __('Google Analytics Information and MetaData',$WCMp_Vendor_Shop_SEO->text_domain);?></label>
						</th>					
					</tr>
					<?php $WCMp->wcmp_wp_fields->dc_generate_form_field( $this->get_seo_user_field( $user->ID ), array('in_table' => 1) ); ?>
				</tbody>
			</table>					
				<?php
		}
	}
	
	
	public function get_seo_product_field($product_id) {
		global $WCMp, $WCMp_Vendor_Shop_SEO;		
		$product_seo = $this->get_seo_value_product($product_id);
		//print_r($vendor);
		
		$fields = apply_filters('dc_vendor_fields', array(			
		"_wcmp_seo_product_meta_title" => array(
			'label' => __('SEO Meta Title', $WCMp_Vendor_Shop_SEO->text_domain),
			'type' => 'text',
			'placeholder' => __('SEO Meta Title', $WCMp_Vendor_Shop_SEO->text_domain),
			'hints' => __('Please enter meta keyword for your shop page.', $WCMp_Vendor_Shop_SEO->text_domain),
			'value' => $product_seo['_wcmp_seo_product_meta_title'],
			'class'	=> "regular-text"
		), // Text
		"_wcmp_seo_product_meta_keyword" => array(
			'label' => __('SEO Meta Keyword', $WCMp_Vendor_Shop_SEO->text_domain),
			'type' => 'text',
			'placeholder' => __('SEO Keyword', $WCMp_Vendor_Shop_SEO->text_domain),
			'hints' => __('Please enter meta keyword for your shop page.', $WCMp_Vendor_Shop_SEO->text_domain),
			'value' => $product_seo['_wcmp_seo_product_meta_keyword'],
			'class'	=> "regular-text"
		), // Text
		"_wcmp_seo_product_meta_description" => array(
			'label' => __('SEO Meta Description', $WCMp_Vendor_Shop_SEO->text_domain),
			'type' => 'text',
			'placeholder' => __('SEO Description', $WCMp_Vendor_Shop_SEO->text_domain),
			'hints' => __('Please enter meta description for your shop page.', $WCMp_Vendor_Shop_SEO->text_domain),
			'value' => $product_seo['_wcmp_seo_product_meta_description'],
			'class'	=> "regular-text"
		) // Text			
		),$product_id);	
		return $fields;
				
	}
	
	
	public function get_seo_value_product($product_id) {    	
		$product_seo['_wcmp_seo_product_meta_title'] = get_post_meta($product_id,'_wcmp_seo_product_meta_title',true);
		$product_seo['_wcmp_seo_product_meta_keyword'] = get_post_meta($product_id,'_wcmp_seo_product_meta_keyword',true);
		$product_seo['_wcmp_seo_product_meta_description'] = get_post_meta($product_id,'_wcmp_seo_product_meta_description',true);
		return $product_seo;    	
	}
	
	
	public function get_seo_value_user($user_id) {
		global $WCMp, $WCMp_Vendor_Shop_SEO;    	
		$user['_wcmp_google_analytics'] = esc_attr( get_the_author_meta( '_wcmp_google_analytics', $user_id ));
		$user['_wcmp_meta_title_shop'] = esc_attr( get_the_author_meta( '_wcmp_meta_title_shop', $user_id ));
		$user['_wcmp_meta_keyword_shop'] = esc_attr( get_the_author_meta( '_wcmp_meta_keyword_shop', $user_id ));
		$user['_wcmp_meta_description_shop'] = esc_attr( get_the_author_meta( '_wcmp_meta_description_shop', $user_id ));
		return $user;    	
	}
	
	public function get_seo_user_field($user_id) {
		global $WCMp, $WCMp_Vendor_Shop_SEO;		
		$vendor = $this->get_seo_value_user($user_id);
		//print_r($vendor);
		
		$fields = apply_filters('dc_vendor_fields', array(
		"_wcmp_google_analytics" => array(
			'label' => __('Google Analytics', $WCMp_Vendor_Shop_SEO->text_domain),
			'type' => 'text',
			'placeholder' => 'UA-66234573-1',
			'hints' => __('Please enter your google analytics Tracking code.', $WCMp_Vendor_Shop_SEO->text_domain),
			'value' => $vendor['_wcmp_google_analytics'],
			'class'	=> "user-profile-fields"
		), // Text
		"_wcmp_meta_title_shop" => array(
			'label' => __('SEO Meta Title', $WCMp_Vendor_Shop_SEO->text_domain),
			'type' => 'text',
			'placeholder' => __('SEO Title', $WCMp_Vendor_Shop_SEO->text_domain),
			'hints' => __('Please enter meta keyword for your shop page.', $WCMp_Vendor_Shop_SEO->text_domain),
			'value' => $vendor['_wcmp_meta_title_shop'],
			'class'	=> "user-profile-fields"
		), // Text
		"_wcmp_meta_keyword_shop" => array(
			'label' => __('SEO Meta Keyword', $WCMp_Vendor_Shop_SEO->text_domain),
			'type' => 'text',
			'placeholder' => __('SEO Keyword', $WCMp_Vendor_Shop_SEO->text_domain),
			'hints' => __('Please enter meta keyword for your shop page.', $WCMp_Vendor_Shop_SEO->text_domain),
			'value' => $vendor['_wcmp_meta_keyword_shop'],
			'class'	=> "user-profile-fields"
		), // Text
		"_wcmp_meta_description_shop" => array(
			'label' => __('SEO Meta Description', $WCMp_Vendor_Shop_SEO->text_domain),
			'type' => 'text',
			'placeholder' => __('SEO Description', $WCMp_Vendor_Shop_SEO->text_domain),
			'hints' => __('Please enter meta description for your shop page.', $WCMp_Vendor_Shop_SEO->text_domain),
			'value' => $vendor['_wcmp_meta_description_shop'],
			'class'	=> "user-profile-fields"
		) // Text			
		),$user_id);	
		return $fields;
				
	}
	
	public function wcmp_seo_save_extra_profile_fields($user_id) {    	
		if ( !current_user_can( 'edit_user', $user_id ) ) {				
				return false;					
		}
		
		if($_POST['_wcmp_google_analytics']) {
				update_usermeta( $user_id, '_wcmp_google_analytics', $_POST['_wcmp_google_analytics'] );
		}
		if($_POST['_wcmp_meta_title_shop']) {
				update_usermeta( $user_id, '_wcmp_meta_title_shop', $_POST['_wcmp_meta_title_shop'] );
		}
		if($_POST['_wcmp_meta_keyword_shop']) {
				update_usermeta( $user_id, '_wcmp_meta_keyword_shop', $_POST['_wcmp_meta_keyword_shop'] );
		}
		if($_POST['_wcmp_meta_description_shop']) {
				update_usermeta( $user_id, '_wcmp_meta_description_shop', $_POST['_wcmp_meta_description_shop'] );
		}
	}

}