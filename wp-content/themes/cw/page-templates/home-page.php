<?php
/**
 * Template Name: Home Page Template
 * Description: Home page
 * @package WordPress
 * @subpackage CW
 * @since CW 1.0
 */
get_header(); ?>

	<div class="content">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php if ( has_post_thumbnail() ) : ?>
					<figure>
						<?php the_post_thumbnail(); ?>
					</figure><!-- .entry-page-image -->
				<?php endif; ?>

				<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; // end of the loop. ?>
	</div>
<?php get_footer(); ?>