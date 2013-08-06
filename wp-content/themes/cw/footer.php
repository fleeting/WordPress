<?php
/**
 * @package WordPress
 * @subpackage CW
 * @since CW 1.0
 */
?>

	<footer role="contentinfo" class="row">
		<div class="large-12 columns">
			<hr />
			<p>&copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>, All Rights Reserved.</p>
		</div>
	</footer>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-2.0.3.min.js"><\/script>')</script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/foundation.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/main.js"></script>

	<!-- WP_FOOTER() -->
	<?php wp_footer(); ?>
</body>
</html>