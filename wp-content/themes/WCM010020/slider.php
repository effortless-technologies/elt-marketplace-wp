<script type="text/javascript">
            jQuery(function() {
				jQuery('#ei-slider').eislideshow({
					animation			: 'center',
					autoplay			: true,
					slideshow_interval	: 3000,
					titlesFactor		: 0
                });
            });
</script>

<?php wp_reset_query(); // Reset
$paged = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$slider_args = array(
		'posts_per_page' 	=> 10, 
		'paged' 			=> $paged,
		'post_type'			=> 'slider',
		'status'			=> 'publish',
		'order'				=> 'ASC'
	);
	$wp_query = new WP_Query();
	$wp_query->query( $slider_args );
	if ( $wp_query->have_posts() ): ?>
<div class="home-slider">
	<div id="ei-slider" class="ei-slider">
		 <ul class="ei-slider-large">
			<?php 
			$i = 1;
			while( $wp_query->have_posts() ): $wp_query->the_post();
			get_post_meta($post->ID, 'slider_background_image', TRUE) ? $slider_background_image = get_post_meta($post->ID, 'slider_background_image', TRUE) : $slider_background_image = '';	
			get_post_meta($post->ID, 'slider_link', TRUE) ? $slider_link = get_post_meta($post->ID, 'slider_link', TRUE) : $slider_link = '';
			?>	
				<li>
						<?php if ( $slider_background_image != '' ) : ?>
						<div class="main_background_image">
							<?php if ( !empty($slider_link)) : ?>
								<a href="<?php echo esc_url($slider_link); ?>" title="<?php echo esc_url($slider_link); ?> " target="_blank"><img src="<?php echo esc_url($slider_background_image); ?>" alt=""></a>
							<?php else: ?>
								<img src="<?php echo esc_url($slider_background_image); ?>" alt="" />
							<?php endif; ?>
						</div>
						<?php endif; ?>		
                </li>
			<?php $i++;  endwhile; ?>
		</ul>
		<ul class="ei-slider-thumbs">
		<li class="ei-slider-element">Current</li> 
		<?php 	
			$i = 1;				
			while( $wp_query->have_posts() ): $wp_query->the_post();
			get_post_meta($post->ID, 'slider_background_image', TRUE) ? $slider_background_image = get_post_meta($post->ID, 'slider_background_image', TRUE) : $slider_background_image = '';
			get_post_meta($post->ID, 'slider_text_1', TRUE) ? $slider_text_1 = get_post_meta($post->ID, 'slider_text_1', TRUE) : $slider_text_1 = '';
			get_post_meta($post->ID, 'slider_text_2', TRUE) ? $slider_text_2 = get_post_meta($post->ID, 'slider_text_2', TRUE) : $slider_text_2 = '';
			 ?>                  
				<li>
						<div class="cms-texts">
							<span class="text1"><?php echo esc_attr($slider_text_1); ?></span><br/>
							<span class="text2"><?php echo esc_attr($slider_text_2); ?></span>
						</div>
						<img src="<?php echo esc_url($slider_background_image); ?>" alt="" />
				</li>
				
                        
			<?php $i++;  endwhile; ?>
          </ul><!-- ei-slider-thumbs -->	
	</div>
</div>
<?php endif; ?>
<?php wp_reset_query(); ?>


