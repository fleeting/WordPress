<?php
/**
 * @package WordPress
 * @subpackage CW
 * @since CW 1.0
 */
?><!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">

	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="author" href="<?php echo get_template_directory_uri(); ?>/humans.txt">
	<link rel="dns-prefetch" href="//ajax.googleapis.com">

	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/style.css">
	<script src="<?php echo get_template_directory_uri(); ?>/js/modernizr.custom.js"></script>

	<!-- WP_HEAD() -->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<header role="banner" class="row">
		<div class="large-12 columns">
			<h1><?php bloginfo( 'name' ); ?></h1>
			<nav role="navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu inline-list' ) ); ?>
			</nav>
			<hr />
		</div>
	</header>