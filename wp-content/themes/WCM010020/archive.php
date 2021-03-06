<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, TemplateMela
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage TemplateMela
 * @since TemplateMela 1.0
 */

get_header(); ?>
<div id="main-content" class="main-content blog-page blog-list <?php echo esc_attr(tm_sidebar_position()); ?>">
<header class="page-header">
      <h1 class="page-title">
        <?php
						if ( is_day() ) :
							printf( __( 'Daily Archives: %s', 'templatemela' ), get_the_date() );

						elseif ( is_month() ) :
							printf( __( 'Monthly Archives: %s', 'templatemela' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'templatemela' ) ) );

						elseif ( is_year() ) :
							printf( __( 'Yearly Archives: %s', 'templatemela' ), get_the_date( _x( 'Y', 'yearly archives date format', 'templatemela' ) ) );

						else :
							_e( 'Archives', 'templatemela' );

						endif;
					?>
      </h1>
    </header>
<section id="primary" class="content-area">
  <div id="content" class="site-content" role="main">
    <?php if ( have_posts() ) : ?>
    
    <!-- .page-header -->
    <?php
					// Start the Loop.
					while ( have_posts() ) : the_post();

						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );

					endwhile;
					// Previous/next page navigation.
					templatemela_paging_nav();

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;
			?>
  </div>
  <!-- #content -->
</section>
<!-- #primary -->
<?php

get_sidebar( 'content' );
get_sidebar(); ?>
</div>
<!-- #main-content -->
<?php get_footer(); ?>
