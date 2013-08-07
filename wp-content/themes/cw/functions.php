<?php
/**
 * CW  functions
 *
 * @package WordPress
 * @subpackage CW
 * @since CW 1.0
 */

/**
 * Sets up theme defaults
 *
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since CW 1.0
 */
function cw_setup() {
	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', 'fonts/genericons.css' ) );

	/*
	 * Adds RSS feed links to <head> for posts and comments.
	 */
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Switches default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	/*
	 * This theme supports all available post formats by default.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'
	) );

	/*
	 * Create the main menu location.
	 */
	register_nav_menu( 'primary', __( 'Main Menu', 'cw' ) );

	/*
	 * This theme uses a custom image size for featured images, displayed on "standard" posts.
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop
}
add_action( 'after_setup_theme', 'cw_setup' );



/**
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since CW 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function cw_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'cw_wp_title', 10, 2 );



/**
 * Extends the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Active widgets in the sidebar to change the layout and spacing.
 * 3. When avatars are disabled in discussion settings.
 *
 * @since CW 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function cw_body_class( $classes ) {
	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	if ( is_active_sidebar( 'sidebar-2' ) && ! is_attachment() && ! is_404() )
		$classes[] = 'sidebar';

	if ( ! get_option( 'show_avatars' ) )
		$classes[] = 'no-avatars';

	return $classes;
}
add_filter( 'body_class', 'cw_body_class' );



/**
 * Register Widget Areas
 *
 * Uncomment and edit to create widget areas where needed.
 * These are default examples so make changes before production.
 *
 * @since CW 1
 *
 */
// function cw_widgets_init() {
// 	register_sidebar( array(
// 		'name'          => __( 'Main Widget Area' ),
// 		'id'            => 'sidebar-1',
// 		'description'   => __( 'Appears in the footer section of the site.' ),
// 		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
// 		'after_widget'  => '</aside>',
// 		'before_title'  => '<h3 class="widget-title">',
// 		'after_title'   => '</h3>',
// 	) );

// 	register_sidebar( array(
// 		'name'          => __( 'Secondary Widget Area' ),
// 		'id'            => 'sidebar-2',
// 		'description'   => __( 'Appears on posts and pages in the sidebar.' ),
// 		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
// 		'after_widget'  => '</aside>',
// 		'before_title'  => '<h3 class="widget-title">',
// 		'after_title'   => '</h3>',
// 	) );
// }
// add_action( 'widgets_init', 'cw_widgets_init' );



/**
 * Creates custom setting fields.
 *
 * @since CW 1.0
 */
function cw_custom_settings() {
	/* Add Contact Section & Fields to General */
	add_settings_section('general_contact_section', 'Contact Information', 'cw_settings_section_contact_callback', 'general');
	add_settings_field('general_contact_address1', 'Address', 'cw_settings_field_address1_callback', 'general', 'general_contact_section');
	add_settings_field('general_contact_address2', 'Address2', 'cw_settings_field_address2_callback', 'general', 'general_contact_section');
	add_settings_field('general_contact_city', 'City', 'cw_settings_field_city_callback', 'general', 'general_contact_section');
	add_settings_field('general_contact_state', 'State', 'cw_settings_field_state_callback', 'general', 'general_contact_section');
	add_settings_field('general_contact_zipcode', 'Zip Code', 'cw_settings_field_zipcode_callback', 'general', 'general_contact_section');
	add_settings_field('general_contact_phone', 'Phone Number', 'cw_settings_field_phone_callback', 'general', 'general_contact_section');
	add_settings_field('general_contact_email', 'Company Email', 'cw_settings_field_email_callback', 'general', 'general_contact_section');
}
add_filter('admin_init', 'cw_custom_settings');

function cw_settings_section_contact_callback() {
	echo '<p>The address will be used in different areas of the website including the vCard and being indexed by Google.</p>';
}
function cw_settings_field_address1_callback() {
	echo '<input type="text" name="general_contact_address1" id="general_contact_address1" class="regular-text">';
}
function cw_settings_field_address2_callback() {
	echo '<input type="text" name="general_contact_address2" id="general_contact_address2" class="regular-text">';
}
function cw_settings_field_city_callback() {
	echo '<input type="text" name="general_contact_city" id="general_contact_city" class="regular-text">';
}
function cw_settings_field_state_callback() {
	echo '<input type="text" name="general_contact_state" id="general_contact_state" class="regular-text">';
}
function cw_settings_field_zipcode_callback() {
	echo '<input type="text" name="general_contact_zipcode" id="general_contact_zipcode" class="regular-text">';
}
function cw_settings_field_phone_callback() {
	echo '<input type="text" name="general_contact_phone" id="general_contact_phone" class="regular-text">';
}
function cw_settings_field_email_callback() {
	echo '<input type="text" name="general_contact_email" id="general_contact_email" class="regular-text">';
}



/**
 * Remove Admin Menu Items
 *
 * @since CW 1.0
 */
function cw_remove_admin_menu_items() {
    $remove_menu_items = array();
    //$remove_menu_items = array(__('Posts'),__('Comments'));
	global $menu;
	end ($menu);
	while (prev($menu)){
		$item = explode(' ',$menu[key($menu)][0]);
		if(in_array($item[0] != NULL?$item[0]:"" , $remove_menu_items)){
		unset($menu[key($menu)]);}
	}
}
add_action('admin_menu', 'cw_remove_admin_menu_items');



/**
 * Import Custom Post Types
 *
 * Custom Post Types are found in ./functions/
 * Be sure and comment out the post types that are not needed.
 *
 * @since CW 1.0
 */

include_once 'functions/cpp-slideshow.php';

/*
 TODO(HIGH): Add Custom Post Types for alerts, directory, staff, faq, links, news, promos, services, and testimonials.
 */

// include_once 'functions/cpp-alerts.php';
// include_once 'functions/cpp-directory.php';
// include_once 'functions/cpp-staff.php';
// include_once 'functions/cpp-faq.php';
// include_once 'functions/cpp-links.php';
// include_once 'functions/cpp-news.php';
// include_once 'functions/cpp-promos.php';
// include_once 'functions/cpp-services.php';
// include_once 'functions/cpp-testimonials.php';