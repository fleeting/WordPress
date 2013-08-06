<?php
/**
 * @package WordPress
 * @subpackage CW
 * @since CW 1.0
 */
get_header(); ?>


<div class="content" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'content', 'page' ); ?>
		<?php comments_template( '', true ); ?>
	<?php endwhile; // end of the loop. ?>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>