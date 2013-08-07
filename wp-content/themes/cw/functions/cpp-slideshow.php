<?php
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