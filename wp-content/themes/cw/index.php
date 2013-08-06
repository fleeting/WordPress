<?php
/**
 * @package WordPress
 * @subpackage CW
 * @since CW 1.0
 */

get_header(); ?>

	<div class="main row" role="main">
		<div class="large-9 columns">
			<?php if ( have_posts() ) : ?>
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content', get_post_format() ); ?>
				<?php endwhile; ?>
			<?php else : ?>
				<article id="post-0" class="post no-results not-found">

				<?php if ( current_user_can( 'edit_posts' ) ) :
					// Show a different message to a logged-in user who can add posts.
				?>
					<header class="entry-header">
						<h2 class="entry-title"><?php _e( 'No posts to display', 'cw' ); ?></h2>
					</header>

					<div class="entry-content">
						<p><?php printf( __( 'Ready to publish your first post? <a href="%s">Get started here</a>.', 'cw' ), admin_url( 'post-new.php' ) ); ?></p>
					</div><!-- .entry-content -->

				<?php else :
					// Show the default message to everyone else.
				?>
					<header class="entry-header">
						<h2 class="entry-title"><?php _e( 'Nothing Found', 'cw' ); ?></h2>
					</header>

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'cw' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				<?php endif; // end current_user_can() check ?>
				</article><!-- #post-0 -->
			<?php endif; // end have_posts() check ?>
		</div>

		<div class="large-3 columns">
			<?php get_sidebar(); ?>
		</div>
	</div><!-- .content -->
<?php get_footer(); ?>