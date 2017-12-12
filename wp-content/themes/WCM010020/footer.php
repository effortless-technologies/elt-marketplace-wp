<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage TemplateMela
 * @since TemplateMela 1.0
 */
?>
<?php templatemela_content_after(); ?>
</div>
<!-- .main-content-inner -->
</div>
<!-- .main_inner -->
</div>
<!-- #main -->
<?php templatemela_footer_before(); ?>
</div>

<footer id="colophon" class="site-footer" role="contentinfo">
<div class="footer-top">
	<div class="footer-top-inner">
	<?php templatemela_get_widget('footer-block'); ?>
	</div>
</div>
  <div class="footer_inner">
    <?php templatemela_footer_inside(); ?>	
	
    <?php get_sidebar('footer'); ?>
	<!-- .footer-bottom -->
  </div>
    
  <!--. Footer inner -->
</footer>
<div class="footer-bottom">
	<div class="footer-bottom-container">
	<div class="footer-bottom-left">
      <div class="footer-menu-links">
        <?php
					$tm_footer_menu=array(
					'menu' => 'TM Footer Navigation',
					'depth'=> 1,
					'echo' => false,
					'menu_class'      => 'footer-menu', 
					'container'       => '', 
					'container_class' => '', 
					'theme_location' => 'footer-menu'
					);
					echo wp_nav_menu($tm_footer_menu);				    
					?>
      </div>
      <!-- #footer-menu-links -->
	  
      <div class="site-info">  <?php __( 'Copyright', 'templatemela' ); ?> &copy; <?php echo esc_attr(date('Y')); ?> <a href="<?php echo esc_attr(get_option('tmoption_footer_link')); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" target="_blank" rel="home"><?php echo esc_attr(get_option('tmoption_footer_slog'));?>
        </a>
        <?php do_action( 'templatemela_credits' ); ?>
      </div>
	 </div>
	 <div class="footer-bottom-right">
	  <div id="fifth" class="fifth-widget footer-widget">
    	<?php dynamic_sidebar( 'fifth-footer-widget-area' ); ?>
  	  </div>
	 </div>
	  	       
	  </div>
    </div>
<!-- #colophon -->
<?php templatemela_footer_after(); ?>
	
</div>
<!-- #page -->
<?php tm_go_top(); ?>
<?php 
if(trim(get_option('tmoption_google_analytics_id'))!=''):?>
<?php endif; ?>
<?php templatemela_get_widget('before-end-body-widget'); ?>
<?php wp_footer(); ?>
</body></html>

// <?php if ($user_ID) : ?>
// <script>
// enter index below
// var dimIndex = 2;
// 
// set custom dimension
// ga('set', 'dimension'+dimIndex, 'Registered User');
// send data to GA
// ga('send', 'event', 'User Type', 'Registered User', {'nonInteraction': 1});
// </script>
// <?php endif; ?>
