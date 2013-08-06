<?php
/**
 * @package WordPress
 * @subpackage CW
 * @since CW 1.0
 */
?>

	<footer id="colophon" role="contentinfo">
		<div class="site-info">
			This is the footer
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- end .wrapper-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>
	document.write('<script src=' +
	('__proto__' in {} ? '/wp-content/themes/cw/js/vendor/zepto' : 'js/vendor/jquery') +
	'.js><\/script>')
</script>
<script src="/wp-content/themes/cw/js/foundation.min.js"></script>
<script src="/wp-content/themes/cw/js/main.js"></script>
<script>
	$(document).foundation();
</script>
<?php wp_footer(); ?>

</body>
</html>