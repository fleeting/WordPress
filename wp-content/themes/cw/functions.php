<?php
/**
 * CW  functions
 *
 *
 * @package WordPress
 * @subpackage CW
 * @since CW 1.0
 */

if ( ! isset( $content_width ) )
	$content_width = 625;

/**
 * Sets up theme defaults
 *
 * @uses load_theme_textdomain() For translation/localization support.
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
	 * Makes Twenty Twelve available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Twelve, use a find and replace
	 * to change 'cw' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'cw', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'cw' ) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop
}
add_action( 'after_setup_theme', 'cw_setup' );

/**
 * Enqueues scripts and styles for front-end.
 *
 * @since CW 1.0
 */
function cw_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/*
	 * Adds JavaScript for handling the navigation menu hide-and-show behavior.
	 */
	wp_enqueue_script( 'cw-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0', true );

	/*
	 * Loads our main stylesheet.
	 */
	wp_enqueue_style( 'cw-style', get_stylesheet_uri() );

	/*
	 * Loads the Internet Explorer specific stylesheet.
	 */
	wp_enqueue_style( 'cw-ie', get_template_directory_uri() . '/css/ie.css', array( 'cw-style' ), '20121010' );
	$wp_styles->add_data( 'cw-ie', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'cw_scripts_styles' );

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since CW 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
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
		$title = "$title $sep " . sprintf( __( 'Page %s', 'cw' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'cw_wp_title', 10, 2 );

/**
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since CW 1.0
 */
function cw_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'cw_page_menu_args' );

if ( ! function_exists( 'cw_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since CW 1.0
 */
function cw_content_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'cw' ); ?></h3>
			<div class="nav-previous alignleft"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'cw' ) ); ?></div>
			<div class="nav-next alignright"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'cw' ) ); ?></div>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
	<?php endif;
}
endif;

if ( ! function_exists( 'cw_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own cw_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since CW 1.0
 */
function cw_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'cw' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'cw' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite class="fn">%1$s %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span> ' . __( 'Post author', 'cw' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'cw' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'cw' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'cw' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'cw' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

// Conact info in Settings
add_filter('admin_init', 'extra_settings');

function extra_settings() {

	// Menu -----------------------------------------------------------
	register_setting('general', 'example_field', 'esc_attr');
	add_settings_field('example_field', '<label for="example_field">'.__('Example Extra Setting' , 'example_field' ).'</label>' , 'example_field', 'general');

}

function example_field() {
	$example_field = get_option( 'example_field', '' );
	echo '<input type="text" id="example_field" name="example_field" value="' . $example_field . '" /><br><p class="description">Example of extra setting.</p>';
}



if ( ! function_exists( 'cw_entry_meta' ) ) :
/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own cw_entry_meta() to override in a child theme.
 *
 * @since CW 1.0
 */
function cw_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'cw' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'cw' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'cw' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'cw' );
	} elseif ( $categories_list ) {
		$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'cw' );
	} else {
		$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'cw' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

/**
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since CW 1.0
 *
 * @param array Existing class values.
 * @return array Filtered class values.
 */
function cw_body_class( $classes ) {
	$background_color = get_background_color();

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'page-templates/full-width.php' ) )
		$classes[] = 'full-width';

	if ( is_page_template( 'page-templates/front-page.php' ) ) {
		$classes[] = 'template-front-page';
		if ( has_post_thumbnail() )
			$classes[] = 'has-post-thumbnail';
		if ( is_active_sidebar( 'sidebar-2' ) && is_active_sidebar( 'sidebar-3' ) )
			$classes[] = 'two-sidebars';
	}

	if ( empty( $background_color ) )
		$classes[] = 'custom-background-empty';
	elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) )
		$classes[] = 'custom-background-white';

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'cw-fonts', 'queue' ) )
		$classes[] = 'custom-font-enabled';

	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'cw_body_class' );

/**
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since CW 1.0
 */
function cw_content_width() {
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $content_width;
		$content_width = 960;
	}
}
add_action( 'template_redirect', 'cw_content_width' );

//
//
//
//
// custom post type
function example_init() {
	$labels = array(
		'name'               => _x( 'Examples', 'post type general name' ),
		'singular_name'      => _x( 'Example', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'book' ),
		'add_new_item'       => __( 'Add New Example' ),
		'edit_item'          => __( 'Edit Example' ),
		'new_item'           => __( 'New Example' ),
		'all_items'          => __( 'All Examples' ),
		'view_item'          => __( 'View Example' ),
		'search_items'       => __( 'Search Examples' ),
		'not_found'          => __( 'No examples found' ),
		'not_found_in_trash' => __( 'No examples found in the Trash' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'Example Custom Post Type'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Example',
		'public'        => true,
		'menu_position' => 5,
		'register_meta_box_cb' => 'add_example_metaboxes',
		'supports'      => array( 'title', 'thumbnail' )
	);
	register_post_type( 'example', $args );
}
add_action( 'init', 'example_init' );

function example_categories() {
	$labels = array(
		'name'              => _x( 'Example Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Example Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Example Categories' ),
		'all_items'         => __( 'All Example Categories' ),
		'parent_item'       => __( 'Parent Example Category' ),
		'parent_item_colon' => __( 'Parent Example Category:' ),
		'edit_item'         => __( 'Edit Example Category' ),
		'update_item'       => __( 'Update Example Category' ),
		'add_new_item'      => __( 'Add New Example Category' ),
		'new_item_name'     => __( 'New Example Category' ),
		'menu_name'         => __( 'Example Categories' ),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
	);
	register_taxonomy( 'example_categories', 'example', $args );
}
add_action( 'init', 'example_categories', 0 );

function add_example_metaboxes() {
	add_meta_box('example_meta', 'Example Meta', 'example_meta', 'example', 'normal', 'default');
}

function example_meta() {
	global $post;
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="example_meta_noncename" id="example_meta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

	// Get the work data if its already been entered
	$example_meta = get_post_meta($post->ID, '_example_field', true);

	// Echo out the field
	echo '<input type="text" name="_example_field" value="' . $example_meta  . '">';
}

function save_example_meta($post_id, $post) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['example_meta_noncename'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	$meta['_example_field'] = $_POST['_example_field'];

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
add_action('save_post', 'save_example_meta', 1, 2); // save the custom fields

/*
* Remove Unwanted Admin Menu Items
* exmaple $remove_menu_items = array(__('Posts'),__('Comments'),__('Tools'));
*-----------------------------------------------------------------------------------*/

function remove_admin_menu_items() {
    $remove_menu_items = array(__('Posts'),__('Comments'));
	global $menu;
	end ($menu);
	while (prev($menu)){
		$item = explode(' ',$menu[key($menu)][0]);
		if(in_array($item[0] != NULL?$item[0]:"" , $remove_menu_items)){
		unset($menu[key($menu)]);}
	}
}
add_action('admin_menu', 'remove_admin_menu_items');