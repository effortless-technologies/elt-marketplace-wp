<?php
/**
 * The template for displaying demo plugin content.
 *
 * Override this template by copying it to yourtheme/wcmp-product-import-export-bundle/wcmp-product-import-export-full-width-template.php
 *
 * @author 		dualcube
 * @package 	wcmp-product-import-export-bundle/Templates
 * @version     0.0.1
 */
get_header(); ?>
<div id="primary" class="content-area"  style="width:100%;">
	<main id="main" class="site-main" role="main" style="width:100%;">
		<?php while ( have_posts() ) : the_post(); ?>
		<div class="wcmp_import_export_title_div"><h1><?php the_title(); ?></h1></div>
		<?php the_content(); ?>
		<?php endwhile;  ?>
	</main>
</div>
<?php get_footer(); ?>