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
 * Slideshow Custom Post Type
 *
 * @since CW 1.0
 */
function cw_cpp_slideshow_init() {
	$field_args = array(
		'labels' => array(
			'name' => __( 'Slideshow' ),
			'singular_name' => __( 'Slideshow' ),
			'add_new' => __( 'Add New Slide' ),
			'add_new_item' => __( 'Add New Slide' ),
			'edit_item' => __( 'Edit Slide' ),
			'new_item' => __( 'Add New Slide' ),
			'view_item' => __( 'View Slide' ),
			'search_items' => __( 'Search Slideshow' ),
			'not_found' => __( 'No slides found' ),
			'not_found_in_trash' => __( 'No slides found in trash' )
		),
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => true,
		'rewrite' => true,
		'menu_position' => 20,
		'supports' => array('title', 'thumbnail', 'page-attributes')
	);
	register_post_type('slideshow',$field_args);
}
add_action( 'init', 'cw_cpp_slideshow_init' );



/**
 * Tweaks to Featured Image on Slideshow
 *
 * Moves Featured Image field from sidebar to main on slideshow and renames it.
 *
 * @since CW 1.0
 */
function cw_cpp_slideshow_move_image_box() {
	remove_meta_box( 'postimagediv', 'slideshow', 'side' );
	add_meta_box('postimagediv', __('Slide Image'), 'post_thumbnail_meta_box', 'slideshow', 'normal', 'high');
}
add_action('do_meta_boxes', 'cw_cpp_slideshow_move_image_box');



/**
 * Add Categories to Custom Post Type
 *
 * This is just an example of adding categories to a custom post type.
 * To see in action just uncomment and it will add categories to slideshow.
 *
 * @since CW 1.0
 */
// function cw_cpp_slideshow_categories() {
// 	$field_args = array(
// 		'labels' => array(
// 			'name'              => _x( 'Categories', 'taxonomy general name' ),
// 			'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
// 			'search_items'      => __( 'Search Categories' ),
// 			'all_items'         => __( 'All Categories' ),
// 			'parent_item'       => __( 'Parent Category' ),
// 			'parent_item_colon' => __( 'Parent Category:' ),
// 			'edit_item'         => __( 'Edit Category' ),
// 			'update_item'       => __( 'Update Category' ),
// 			'add_new_item'      => __( 'Add New Category' ),
// 			'new_item_name'     => __( 'New Category' ),
// 			'menu_name'         => __( 'Categories' ),
// 		),
// 		'hierarchical' => true
// 	);
// 	register_taxonomy( 'slideshow_categories', 'slideshow', $field_args );
// }
// add_action( 'init', 'cw_cpp_slideshow_categories', 0 );



/**
 * Slideshow Custom Fields
 *
 * @since CW 1.0
 */
function cw_cpp_slideshow_metaboxes() {
	add_meta_box('slideshow_meta', 'Slide Caption', 'cw_cpp_slideshow_meta', 'slideshow', 'normal', 'default');
}
add_action( 'add_meta_boxes', 'cw_cpp_slideshow_metaboxes' );

function cw_cpp_slideshow_meta() {
	global $post;
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="slideshow_meta_noncename" id="slideshow_meta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

	// Get the data if there is any.
	$caption = get_post_meta($post->ID, '_slideshow_caption', true);

	// Echo out the field
	echo '<textarea name="_slideshow_caption" class="widefat">' . $example_meta  . '</textarea>';
}
function cw_cpp_slideshow_save_meta($post_id, $post) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['slideshow_meta_noncename'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	$meta['_slideshow_caption'] = $_POST['_slideshow_caption'];

	// Add values of $meta as custom fields
	foreach ($meta as $key => $value) { // Cycle through the $meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}
}
add_action('save_post', 'cw_cpp_slideshow_save_meta', 1, 2); // save the custom fields



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