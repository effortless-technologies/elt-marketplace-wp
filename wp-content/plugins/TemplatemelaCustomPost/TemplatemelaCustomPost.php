<?php 
/*
  Plugin Name: Templatemela Custom Post
  Plugin URI: http://www.templatemela.com
  Description: Templatemela Custom Taxonomy(Portfolio, Staff, FAQs, Testimonials) for templatemela wordpress themes.
  Version: 1.0
  Author: Templatemela
  Author URI: http://www.templatemela.com
 */
 
// Templatemela FAQS 
 
function faq_theme_custom_posts(){
	//faq
	$labels = array(
	  'name' => _x('FAQs', 'faq','templatemela'),
	  'singular_name' => _x('FAQ', 'faq','templatemela'),
	  'add_new' => _x('Add New', 'faq','templatemela'),
	  'add_new_item' => __('Add New FAQ','templatemela'),
	  'edit_item' => __('Edit FAQ','templatemela'),
	  'new_item' => __('New FAQ','templatemela'),
	  'view_item' => __('View FAQ','templatemela'),
	  'search_items' => __('Search FAQ','templatemela'),
	  'not_found' =>  __('No FAQ found','templatemela'),
	  'not_found_in_trash' => __('No FAQ found in Trash','templatemela'), 
	  'parent_item_colon' => ''
	);
	$args = array(
	  'labels' => $labels,
	  'public' => true,
	  'publicly_queryable' => true,
	  'show_ui' => true, 
	  'query_var' => true, 
	  'capability_type' => 'post', 
	  'menu_position' => null,
	  'menu_icon' => 'dashicons-editor-help',
	  'rewrite' => array('slug'=>'faq','with_front'=>''),
	  'supports' => array('title','editor','author','thumbnail','comments')
	); 
	register_post_type('faq',$args);	
	
	// FAQ Categories
	$labels = array(
	  'name' => __( 'FAQ Categories', 'taxonomy general name' ,'templatemela'),
	  'singular_name' => __( 'FAQ Category', 'taxonomy singular name','templatemela' ),
	  'search_items' =>  __( 'Search FAQ Category' ,'templatemela'),
	  'all_items' => __( 'All FAQ Categories' ,'templatemela'),
	  'parent_item' => __( 'Parent FAQ Category' ,'templatemela'),
	  'parent_item_colon' => __( 'Parent FAQ Category:' ,'templatemela'),
	  'edit_item' => __( 'Edit FAQ Category','templatemela' ), 
	  'update_item' => __( 'Update FAQ Category' ,'templatemela'),
	  'add_new_item' => __( 'Add New FAQ Category','templatemela' ),
	  'new_item_name' => __( 'New Genre FAQ Category','templatemela' ),
	); 	
	
	register_taxonomy('faq_categories',array('faq'), array(
	  'hierarchical' => true,
	  'labels' => $labels,
	  'show_ui' => true,
	  'query_var' => true,
	  '_builtin' => false,
	  'paged'=>true,
	  'rewrite' => false,
	));
	
}
add_filter('init', 'faq_theme_custom_posts' );

function faq_filter_post_type_link($link, $post)
{
	if ($cats = get_the_terms($post->ID, 'faq_categories'))
	$link = str_replace('%faq%', 'array_pop($cats)->slug', $link);
	return $link;
}
add_filter('post_type_link', 'faq_filter_post_type_link', 10, 2); 



// Templatemela Portfolio

function portfolio_theme_custom_posts(){

	//Portfolio
	$labels = array(
	  'name' =>

__('Portfolios', 'Portfolio','templatemela'),
	  'singular_name' => __('Portfolio', 'Portfolio','templatemela'),
	  'add_new' => __('Add New', 'Portfolio item','templatemela'),
	  'add_new_item' => __('Add New Portfolio item','templatemela'),
	  'edit_item' => __('Edit Portfolio Item','templatemela'),
	  'new_item' => __('New Portfolio Item','templatemela'),
	  'view_item' => __('View Portfolio Item','templatemela'),
	  'search_items' => __('Search Portfolio Item','templatemela'),
	  'not_found' =>  __('No Portfolio item found','templatemela'),
	  'not_found_in_trash' => __('No Portfolio item found in Trash','templatemela'), 
	  'parent_item_colon' => ''
	);
	$args = array(
	  'labels' => $labels,
	  'public' => true,
	  'publicly_queryable' => true,
	  'show_ui' => true, 
	  'query_var' => true, 
	  'capability_type' => 'post', 
	  'menu_position' => null,
	  'menu_icon' => 'dashicons-images-alt2',
	  'rewrite' => array('slug'=>'portfolio','with_front'=>''),
	  'supports' => array('title','editor','author','thumbnail','comments')
	); 
	register_post_type('portfolio',$args);
	
	// Portfolio Categories
	$labels = array(
	  'name' => __( 'Portfolio Categories', 'taxonomy general name' ,'templatemela'),
	  'singular_name' => __( 'Portfolio Category', 'taxonomy singular name','templatemela' ),
	  'search_items' =>  __( 'Search Portfolio Category' ,'templatemela'),
	  'all_items' => __( 'All Portfolio Categories' ,'templatemela'),
	  'parent_item' => __( 'Parent Portfolio Category' ,'templatemela'),
	  'parent_item_colon' => __( 'Parent Portfolio Category:' ,'templatemela'),
	  'edit_item' => __( 'Edit Portfolio Category','templatemela' ), 
	  'update_item' => __( 'Update Portfolio Category' ,'templatemela'),
	  'add_new_item' => __( 'Add New Portfolio Category','templatemela' ),
	  'new_item_name' => __( 'New Genre Portfolio Category','templatemela' ),
	); 	
	
	register_taxonomy('portfolio_categories',array('portfolio'), array(
	  'hierarchical' => true,
	  'labels' => $labels,
	  'show_ui' => true,
	  'query_var' => true,
	  '_builtin' => false,
	  'paged'=>true,
	  'rewrite' => false,
	));
}
add_filter('init', 'portfolio_theme_custom_posts' );

function filter_post_type_link($link, $post)
{
	if ($cats = get_the_terms($post->ID, 'portfolio_categories'))
	$link = str_replace('%portfolio%', 'array_pop($cats)->slug', $link);
	return $link;
}
add_filter('post_type_link', 'filter_post_type_link', 10, 2); 


// Templatemela Staff

function tm_custom_post_staff(){
		$labels = array(
			'name' => _x( 'Staff', 'post type general name', 'templatemela' ),
			'singular_name' => _x( 'Staff', 'post type singular name', 'templatemela' ),
			'add_new' => _x( 'Add Staff', '', 'templatemela' ),
			'add_new_item' => __( 'Add Staff ', 'templatemela' ),
			'edit_item' => __( 'Edit Staff', 'templatemela' ),
			'new_item' => __( 'New Staff', 'templatemela' ),
			'view_item' => __( 'View Staff', 'templatemela' ),
			'search_items' => __( 'Search Staff', 'templatemela' ),
			'not_found' =>  __( 'No Staff found', 'templatemela' ),
			'not_found_in_trash' => __( 'No Staff found in Trash', 'templatemela' ), 
			'parent_item_colon' => ''
		);
		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null, 
			'menu_icon' => 'dashicons-groups',
			'taxonomies' => array( '' ), 
			'supports' => array( 'title', 'editor', 'page-attributes', 'thumbnail', 'excerpt')
		);
		
		register_post_type( 'staff', $args );	
}
add_filter( 'init', 'tm_custom_post_staff' );
add_action( 'add_meta_boxes', 'staff_add_custom_fields' );
add_action( 'save_post', 'staff_save_custom_fields' );

function staff_add_custom_fields() {
    add_meta_box( 
        'staff_options',
        'Staff Details',
        'staff_inner_custom_field',
        'staff' 
    );
}

function staff_inner_custom_field( $post ) {

	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), '_templatemela' );	
	get_post_meta($post->ID, 'staff_position', TRUE) ? $staff_position = get_post_meta($post->ID, 'staff_position', TRUE) : $staff_position = '';
	get_post_meta($post->ID, 'staff_link', TRUE) ? $staff_link = get_post_meta($post->ID, 'staff_link', TRUE) : $staff_link = '';
	get_post_meta($post->ID, 'staff_phone', TRUE) ? $staff_phone = get_post_meta($post->ID, 'staff_phone', TRUE) : $staff_phone = '';
	get_post_meta($post->ID, 'staff_email', TRUE) ? $staff_email = get_post_meta($post->ID, 'staff_email', TRUE) : $staff_email = '';
	get_post_meta($post->ID, 'staff_twitter', TRUE) ? $staff_twitter = get_post_meta($post->ID, 'staff_twitter', TRUE) : $staff_twitter = '';
	get_post_meta($post->ID, 'staff_facebook', TRUE) ? $staff_facebook = get_post_meta($post->ID, 'staff_facebook', TRUE) : $staff_facebook = '';
	get_post_meta($post->ID, 'staff_google_plus', TRUE) ? $staff_google_plus = get_post_meta($post->ID, 'staff_google_plus', TRUE) : $staff_google_plus = '';
	get_post_meta($post->ID, 'staff_linkedin', TRUE) ? $staff_linkedin = get_post_meta($post->ID, 'staff_linkedin', TRUE) : $staff_linkedin = '';
	get_post_meta($post->ID, 'staff_youtube', TRUE) ? $staff_youtube = get_post_meta($post->ID, 'staff_youtube', TRUE) : $staff_youtube = '';
	get_post_meta($post->ID, 'staff_rss', TRUE) ? $staff_rss = get_post_meta($post->ID, 'staff_rss', TRUE) : $staff_rss = '';
	get_post_meta($post->ID, 'staff_pinterest', TRUE) ? $staff_pinterest = get_post_meta($post->ID, 'staff_pinterest', TRUE) : $staff_pinterest = '';
	get_post_meta($post->ID, 'staff_skype', TRUE) ? $staff_skype = get_post_meta($post->ID, 'staff_skype', TRUE) : $staff_skype = '';
?>
<table class="form-table">
	<tbody>
	<tr valign="top">
		<th><label for="staff_position">Position:</label></th>
		<td><input type="text" id="staff_position" name="staff_position" value=" <?php echo esc_attr($staff_position); ?>" class="regular-text"/><br/>
		<span class="description">Enter the person's position or title (e.g. CEO, Manager etc.)</span> </td>
	</tr>
	
	<tr valign="top">
		<th><label for="staff_link">Personal blog / website:</label></th>
		<td><input type="text" id="staff_link" name="staff_link" value=" <?php echo esc_attr($staff_link); ?>" class="regular-text" /></td>
	</tr>
	<tr valign="top">
		<th><label for="staff_phone">Phone:</label></th>
		<td><input type="text" id="staff_phone" name="staff_phone" value=" <?php echo esc_attr($staff_phone); ?>" class="regular-text" /></td>
	</tr>
	<tr valign="top">
		<th><label for="staff_email">Email:</label></th>
		<td><input type="text" id="staff_email" name="staff_email" value=" <?php echo esc_attr($staff_email); ?>" class="regular-text" /></td>
	</tr>
	<tr valign="top">
		<th><label for="staff_twitter">Twitter:</label></th>
		<td><input type="text" id="staff_twitter" name="staff_twitter" value=" <?php echo esc_attr($staff_twitter); ?>" class="regular-text" /></td>
	</tr>
	<tr valign="top">
		<th><label for="staff_facebook">Facebook:</label></th>
		<td><input type="text" id="staff_facebook" name="staff_facebook" value=" <?php echo esc_attr($staff_facebook); ?>" class="regular-text" /></td>
	</tr>
	<tr valign="top">
		<th><label for="staff_google_plus">Google Plus:</label></th>
		<td><input type="text" id="staff_google_plus" name="staff_google_plus" value=" <?php echo esc_attr($staff_google_plus); ?>" class="regular-text" /></td>
	</tr>
	<tr valign="top">
		<th><label for="staff_linkedin">Linkedin:</label></th>
		<td><input type="text" id="staff_linkedin" name="staff_linkedin" value=" <?php echo esc_attr($staff_linkedin); ?>" class="regular-text" /></td>
	</tr>
	<tr valign="top">
		<th><label for="staff_youtube">Youtube:</label></th>
		<td><input type="text" id="staff_youtube" name="staff_youtube" value=" <?php echo esc_attr($staff_youtube); ?>" class="regular-text" /></td>
	</tr>
	<tr valign="top">
		<th><label for="staff_rss">RSS:</label></th>
		<td><input type="text" id="staff_rss" name="staff_rss" value=" <?php echo esc_attr($staff_rss); ?>" class="regular-text" /></td>
	</tr>
	<tr valign="top">
		<th><label for="staff_pinterest">Pinterest:</label></th>
		<td><input type="text" id="staff_pinterest" name="staff_pinterest" value=" <?php echo esc_attr($staff_pinterest); ?>" class="regular-text" /></td>
	</tr>
	<tr valign="top">
		<th><label for="staff_skype">Skype:</label></th>
		<td><input type="text" id="staff_skype" name="staff_skype" value=" <?php echo esc_attr($staff_skype); ?>" class="regular-text" /></td>
	</tr>	
	</tbody>
</table>

<?php }

function staff_save_custom_fields( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  if ( !current_user_can( 'edit_post', $post_id ) )
        return;

  $mydata = array();

  foreach($_POST as $key => $data) {
    if($key == '_templatemela')
      continue;
	  
    if(preg_match('/^staff/i', $key)) {
      $mydata[$key] = $data;
	  update_post_meta($post_id, $key, $data);
    }
  }  
  return $mydata;
  
} 

 
// Templatemela Testimonials

function testimonial_theme_custom_posts(){
	//testimonial
	$labels = array(
	  'name' => _x('Testimonials', 'Testimonial','templatemela'),
	  'singular_name' => _x('Testimonial', 'testimonial','templatemela'),
	  'add_new' => _x('Add New', 'Testimonial','templatemela'),
	  'add_new_item' => __('Add New Testimonial','templatemela'),
	  'edit_item' => __('Edit Testimonial','templatemela'),
	  'new_item' => __('New Testimonial','templatemela'),
	  'view_item' => __('View Testimonial','templatemela'),
	  'search_items' => __('Search Testimonial','templatemela'),
	  'not_found' =>  __('No Testimonial found','templatemela'),
	  'not_found_in_trash' => __('No Testimonial found in Trash','templatemela'), 
	  'parent_item_colon' => ''
	);
	$args = array(
	  'labels' => $labels,
	  'public' => true,
	  'publicly_queryable' => true,
	  'show_ui' => true, 
	  'query_var' => true, 
	  'capability_type' => 'post', 
	  'menu_position' => null,
	  'menu_icon' => 'dashicons-format-chat',	 
	  'rewrite' => array('slug'=>'testimonial','with_front'=>''),
	  'supports' => array('title','editor','author','thumbnail','comments')
	); 
	register_post_type('testimonial',$args);	
}
add_filter('init', 'testimonial_theme_custom_posts' );

add_action( 'admin_init', 'remove_metabox_option' );
function remove_metabox_option() {
   	remove_meta_box( 'commentsdiv', 'testimonial', 'normal' );
	remove_meta_box( 'authordiv', 'testimonial', 'normal' );
	remove_meta_box( 'commentstatusdiv', 'testimonial', 'normal' );
}


add_action( 'add_meta_boxes', 'testimonial_add_custom_fields' );
add_action( 'save_post', 'testimonial_save_custom_fields' );

function testimonial_add_custom_fields() {
    add_meta_box( 
        'testimonial_options',
        'Testimonial Information',
        'testimonial_inner_custom_field',
        'testimonial' 
    );
}

function testimonial_inner_custom_field( $post ) {
	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), '_templatemela' );	
	get_post_meta($post->ID, 'testimonial_position', TRUE) ? $testimonial_position = get_post_meta($post->ID, 'testimonial_position', TRUE) : $testimonial_position = '';
	get_post_meta($post->ID, 'testimonial_link', TRUE) ? $testimonial_link = get_post_meta($post->ID, 'testimonial_link', TRUE) : $testimonial_link = '';
?>

<table class="form-table">
  <tbody>
    <tr valign="top">
      <th><label for="testimonial_position">
        <?php _e('Email:', 'templatemela'); ?>
        </label></th>
      <td><input type="text" id="testimonial_position" name="testimonial_position" value=" <?php echo esc_attr($testimonial_position); ?>" class="regular-text"/></td>
    </tr>
    <tr valign="top">
      <th><label for="testimonial_link">
        <?php _e('Link:', 'templatemela'); ?>
        </label></th>
      <td><input type="text" id="testimonial_link" name="testimonial_link" value=" <?php echo esc_attr($testimonial_link); ?>" class="regular-text" /></td>
    </tr>
  </tbody>
</table>
<?php }


function testimonial_save_custom_fields( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  if ( !current_user_can( 'edit_post', $post_id ) )
        return;

  $mydata = array();

  foreach($_POST as $key => $data) {
    if($key == '_templatemela')
      continue;
	  
    if(preg_match('/^testimonial/i', $key)) {
      $mydata[$key] = $data;
	  update_post_meta($post_id, $key, $data);
    }
  }
 
  return $mydata;
  
} 
 
?>
