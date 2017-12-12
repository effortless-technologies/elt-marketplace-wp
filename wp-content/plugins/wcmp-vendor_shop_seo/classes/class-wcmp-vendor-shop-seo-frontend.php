<?php
class WCMp_Vendor_Shop_Seo_Frontend {

    public function __construct() {
    	global $WCMp_Vendor_Shop_SEO;
			if(isset($WCMp_Vendor_Shop_SEO->option_settings['vendor_seo_enabled']) && $WCMp_Vendor_Shop_SEO->option_settings['vendor_seo_enabled'] == 'Enable') {
				add_action('other_exta_field_dcmv',array($this,'google_analytics_field'),10,1);
				add_action( 'wp_head',array($this,'wcmp_head_functionality'),9,1);
				add_filter( 'wp_title', array($this,'meta_title_by_wcmp'), 10, 3 );
				add_action( 'wp_head', array($this,'add_meta_tags_wcmp') , 2 );
				add_action( 'after_wcmp_fpm_template', array($this, 'add_product_seo_field'),10);
				add_action( 'after_wcmp_fpm_meta_save', array($this, 'save_seo_product_meta_frontend'), 10, 2);
			}
    }
    
    public function save_seo_product_meta_frontend($new_product_id, $product_manager_form_data){
    	if(isset($product_manager_form_data['_wcmp_seo_product_meta_title']) && $new_product_id != '') {
				update_post_meta($new_product_id, '_wcmp_seo_product_meta_title', $product_manager_form_data['_wcmp_seo_product_meta_title']);				  
			}
			if(isset($product_manager_form_data['_wcmp_seo_product_meta_keyword']) && $new_product_id != '') {				
				update_post_meta($new_product_id, '_wcmp_seo_product_meta_keyword', $product_manager_form_data['_wcmp_seo_product_meta_keyword']);				 
			}
			if(isset($product_manager_form_data['_wcmp_seo_product_meta_description']) && $new_product_id != '') {				
				update_post_meta($new_product_id, '_wcmp_seo_product_meta_description', $product_manager_form_data['_wcmp_seo_product_meta_description']);  
			}
    }
    
    public function add_product_seo_field() {
    	global $WCMp_Vendor_Shop_SEO, $WCMp;
    	$product_id = '';
			if(isset($_REQUEST['pro_id'])) {
				$product = wc_get_product( $_REQUEST['pro_id'] );
				if($product && !empty($product)) {
					$product_id = $_REQUEST['pro_id'];
				}
			}
			?>
			<h3 class="pro_ele_head simple variable external"> <?php _e('Product Seo', $WCMp_Frontend_Product_Manager->text_domain); ?></h3>
				<div class="pro_ele_block simple variable external">
					<p>
						<?php
							$WCMp->wcmp_wp_fields->dc_generate_form_field( $this->get_seo_product_field_frontend( $product_id ), array('in_table' => 0) );
						?>
					</p>
				</div>
				<?php
			    	
    }

    public function get_user_by_meta_data( $meta_key, $meta_value ) {
			$user_query = new WP_User_Query(
				array(
						'meta_key'	  =>	$meta_key,
						'meta_value'	=>	$meta_value
				)
			);
			$users = $user_query->get_results();
			return $users[0];
    }

    public function add_meta_tags_wcmp() {
    global $WCMp_Vendor_Shop_SEO,$post,$wp_query;
    $post_type = get_post_type( get_the_ID() );
    if(is_archive()) {
			$term =	$wp_query->queried_object;
			if($term->taxonomy == 'dc_vendor_shop') {
				$user = $this->get_user_by_meta_data( '_vendor_term_id', $term->term_id );
				$user_id = $user->ID;
				$meta_keyword = esc_attr( get_the_author_meta( '_dcmv_meta_keyword_shop', $user_id ) );
				$meta_descripion = esc_attr( get_the_author_meta( '_dcmv_meta_description_shop', $user_id ) );
				if($meta_descripion != '') {
						 echo '<meta name="description" content="' . $meta_descripion . '" />' . "\n";
				}
				if($meta_keyword != '') {
					echo '<meta name="keywords" content="' . $meta_keyword . '" />' . "\n";
				}
			}
    }
    else {
			if($post_type == 'product') {
				if(is_single(get_the_ID())) {
					$meta_descripion = get_post_meta($post->ID,'_wcmp_seo_product_meta_description',true);
					if($meta_descripion != '') {
							echo '<meta name="description" content="' . $meta_descripion . '" />' . "\n";
					}
					$meta_keyword = get_post_meta($post->ID,'_wcmp_seo_product_meta_keyword',true);
					if($meta_keyword != '') {
							echo '<meta name="keywords" content="' . $meta_keyword . '" />' . "\n";
					}
				}
			}
    }
  }

    public function meta_title_by_wcmp($title) {
			global $WCMp_Vendor_Shop_SEO,$post,$wp_query;
			if(is_archive()) {
				$term =	$wp_query->queried_object;
				if($term->taxonomy == 'dc_vendor_shop') {
					$user = $this->get_user_by_meta_data( '_vendor_term_id', $term->term_id );
					$user_id = $user->ID;
					$meta_title = esc_attr( get_the_author_meta( '_wcmp_meta_title_shop', $user_id ) );

					if($meta_title != '') {
							 return $term->name." | ".$meta_title;
					}else{
					return $title;
					}
				}
				else {
						return $title;
				}
			}
			else {
				$post_title = $post->post_title;
				$dcmv_title = get_post_meta($post->ID,'_wcmp_seo_product_meta_title',true);
				$new_title = $post_title." | ".$dcmv_title;
				if($dcmv_title !='') {
					 $post_type = get_post_type( get_the_ID() );
					 if($post_type == 'product') {
						 if(is_single(get_the_ID())) {
										return $new_title;
						 }
						 else {
								 return $title;
						 }
					 }
				}
				else {
					return $title;
				}
			}
    }

    public function wcmp_head_functionality() {
        global $WCMp_Vendor_Shop_SEO,$post,$wp_query;
        $post_type = get_post_type( get_the_ID() );
        if(is_archive()) {
            $term =	$wp_query->queried_object;
            $user = $this->get_user_by_meta_data( '_vendor_term_id', $term->term_id );
            $user_id = $user->ID;
            $google_analytics = esc_attr( get_the_author_meta( '_wcmp_google_analytics', $user_id ) );
            if( $google_analytics != '' ) {	?>
                <script type="text/javascript">
                    var _gaq = _gaq || [];
                    _gaq.push(['_setAccount', '<?php echo $google_analytics; ?>']);
                    _gaq.push(['_trackPageview']);
                    (function() {
                    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                    })();
                </script>
            <?php }
        }
        else {
            if($post_type == 'product') {
                if(is_single(get_the_ID())) {
                    $author_id=$post->post_author;
                    $google_analytics = esc_attr( get_the_author_meta( '_wcmp_google_analytics', $author_id ) );
                    if( $google_analytics != '' ) {	?>
                        <script type="text/javascript">
                            var _gaq = _gaq || [];
                            _gaq.push(['_setAccount', '<?php echo $google_analytics; ?>']);
                            _gaq.push(['_trackPageview']);
                            (function() {
                            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                            })();
                        </script>
                        <?php
                    }
                }
            }
        }
    }

    public function google_analytics_field() {
			global $WCMp_Vendor_Shop_SEO, $WCMp;
			$current_user = wp_get_current_user();				
			if(isset($_POST['_wcmp_google_analytics']) ) {
					update_usermeta( $current_user->ID, '_wcmp_google_analytics', $_POST['_wcmp_google_analytics'] );					
			}
			if(isset($_POST['_wcmp_meta_title_shop']) ) {
					update_usermeta( $current_user->ID, '_wcmp_meta_title_shop', $_POST['_wcmp_meta_title_shop'] );					
			}
			if(isset($_POST['_wcmp_meta_keyword_shop']) ) {
					update_usermeta( $current_user->ID, '_wcmp_meta_keyword_shop', $_POST['_wcmp_meta_keyword_shop'] );				
			}
			if(isset($_POST['_wcmp_meta_description_shop']) ) {
					update_usermeta( $current_user->ID, '_wcmp_meta_description_shop', $_POST['_wcmp_meta_description_shop'] );					
			}		
			
			$WCMp->wcmp_wp_fields->dc_generate_form_field( $this->get_seo_user_field( $current_user->ID ), array('in_table' => 0) ); 
			
			
			
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
		  $vendor = $this->get_seo_value_user($user_id);  //print_r($vendor);		  
		  $fields = apply_filters('wcmp_user_profile_fields', array(
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
    public function get_seo_product_field_frontend($product_id = '') {
    	global $WCMp, $WCMp_Vendor_Shop_SEO;
    	$_wcmp_seo_product_meta_title = get_post_meta ( $product_id, '_wcmp_seo_product_meta_title',true );
    	$_wcmp_seo_product_meta_keyword = get_post_meta ( $product_id, '_wcmp_seo_product_meta_keyword',true );
    	$_wcmp_seo_product_meta_description = get_post_meta ( $product_id, '_wcmp_seo_product_meta_description',true );		     
		  $fields = apply_filters('wcmp_shop_seo_fields', array(
															"_wcmp_seo_product_meta_title" => array(
															'label' => __('SEO Meta Title', $WCMp_Vendor_Shop_SEO->text_domain),
															'type' => 'text',
															'placeholder' => __('SEO Meta Title', $WCMp_Vendor_Shop_SEO->text_domain),
															'hints' => __('Please enter meta keyword for your shop page.', $WCMp_Vendor_Shop_SEO->text_domain),
															'value' => $_wcmp_seo_product_meta_title,
															'class'	=> "regular-text"
															), // Text
															"_wcmp_seo_product_meta_keyword" => array(
																'label' => __('SEO Meta Keyword', $WCMp_Vendor_Shop_SEO->text_domain),
																'type' => 'text',
																'placeholder' => __('SEO Keyword', $WCMp_Vendor_Shop_SEO->text_domain),
																'hints' => __('Please enter meta keyword for your shop page.', $WCMp_Vendor_Shop_SEO->text_domain),
																'value' => $_wcmp_seo_product_meta_keyword,
																'class'	=> "regular-text"
															), // Text
															"_wcmp_seo_product_meta_description" => array(
																'label' => __('SEO Meta Description', $WCMp_Vendor_Shop_SEO->text_domain),
																'type' => 'text',
																'placeholder' => __('SEO Description', $WCMp_Vendor_Shop_SEO->text_domain),
																'hints' => __('Please enter meta description for your shop page.', $WCMp_Vendor_Shop_SEO->text_domain),
																'value' => $_wcmp_seo_product_meta_description,
																'class'	=> "regular-text"
															) // Text			
			),$product_id);	
			return $fields;
		     	
    }

}