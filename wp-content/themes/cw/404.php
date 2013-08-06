<?php
/**
 * @package WordPress
 * @subpackage CW
 * @since CW 1.0
 */
get_header(); ?>

	<div class="main row" role="main">
		<div class="large-9 columns">
			<h2 class="entry-title"><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?' ); ?></h2>

			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.' ); ?></p>
		</div>

		<div class="large-3 columns">
			<?php get_sidebar(); ?>
		</div>
	</div>

<?php get_footer(); ?>