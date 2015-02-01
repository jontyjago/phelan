<?php

//Functions.php for Phelangood Theme
//Author: Jonathan Evans
//Version 1.0

//Google fonts into header
function load_fonts() {
            wp_register_style('googleFonts', 'http://fonts.googleapis.com/css?family=Sorts+Mill+Goudy:400,400italic|Nobile:400,400italic');
            wp_enqueue_style( 'googleFonts');
}
 
add_action('wp_print_styles', 'load_fonts');

remove_theme_support( 'menus' );
// Register Header menu
register_nav_menu( 'header_menu', 'Header Menu' );

//define Article Custom Post Type
add_action( 'init', 'create_sp_articles' );

function create_sp_articles() {

	$labels = array(
		//the double underscore is needed for translations
		//just leaving it in here
		'name' 					=> __( 'Articles' ),
		'singular_name' 		=> __( 'Article' ),
		'add_new'           	=> _x( 'Add New', 'articles' ),
		'add_new_item'      	=> __( 'Add New Article' ),
		'edit_item'         	=> __( 'Edit Article' ),
		'new_item'          	=> __( 'New Article' ),
		'all_items'         	=> __( 'All Articles' ),
		'view_item'         	=> __( 'View Article' ),
		'search_items'      	=> __( 'Search Articles' ),
		'not_found'         	=> __( 'No Article Found' ),
		'not_found_in_trash' 	=> __( 'No Articles Found in the Trash' ), 
		'parent_item_colon'  	=> '',
		'menu_name'          	=> __(' Articles' ),
	);
	$args = array (
		'labels' => $labels,
		'public' => true,
		'has_archive' => true,
		'rewrite' => array('slug' => 'articles'),
		'menu_position' => 5,
		'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' )
	);

	register_post_type('articles', $args);
	flush_rewrite_rules( false );
}

//Customise messages
function sp_articles_messages( $messages ) {
	global $post, $post_ID;
	$messages['articles'] = array(
		0 => '', 
		1 => sprintf( __('Article updated. <a href="%s">View Article</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Article updated.'),
		5 => isset($_GET['revision']) ? sprintf( __('Article restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Article published. <a href="%s">View Article</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('Article saved.'),
		8 => sprintf( __('Article submitted. <a target="_blank" href="%s">Preview product</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Article scheduled for publication on: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Article</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Article draft updated. <a target="_blank" href="%s">Preview Article</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $messages;
}

add_filter( 'post_updated_messages', 'sp_articles_messages' );

/* Define the custom box */

add_action( 'add_meta_boxes', 'add_sp_articledata' );

// backwards compatible (before WP 3.0)
// add_action( 'admin_init', 'myplugin_add_custom_box', 1 );

/* Do something with the data entered */
add_action( 'save_post', 'save_sp_articledata' );

/* Adds a box to the main column on the Post and Page edit screens */
function add_sp_articledata() {
    $screens = array( 'articles' );
    foreach ($screens as $screen) {
        add_meta_box(
            'sp_sectionid',
            __( 'Article Details' ),
            'sp_inner_custom_box',
            $screen,
            'normal',
            'high'
        );
    }
}

/* Prints the box content */
function sp_inner_custom_box( $post ) {

  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'sp_noncename' );

  // The actual fields for data entry
  // Use get_post_meta to retrieve an existing value from the database and use the value for the form
	  $sp_date_published = get_post_meta( $post->ID, 'sp_date_published', true );
	  $sp_publication = get_post_meta( $post->ID, 'sp_publication', true );
	  $sp_sort_date = get_post_meta( $post->ID, 'sp_sort_date', true );

	  if (strlen($sp_sort_date==0)) {
	  	$sp_sort_date = date('Y-m-d');
	  }

	  echo '<label for="sp_date_published">';
	       _e("Date First Published", 'sp_textdomain' );
	  echo '</label> ';
	  echo '<input type="text" id="sp_date_published" name="sp_date_published" value="'.esc_attr($sp_date_published).'" size="25" />';
	  echo '<br /><label for="sp_publication">';
	       _e("Publication Name", 'sp_textdomain' );
	  echo '</label> ';
	  echo '<input type="text" id="sp_publication" name="sp_publication" value="'.esc_attr($sp_publication).'" size="25" />';
	  echo '<br /><label for="sp_sort_date">';
	       _e("Sort Date - used to order articles on the homepage, use YYYY-MM-DD format", 'sp_textdomain' );
	  echo '</label> ';
	  echo '<input type="text" id="sp_sort_date" name="sp_sort_date" value="'.esc_attr($sp_sort_date).'" size="25" />';

}

/* When the post is saved, saves our custom data */
function save_sp_articledata( $post_id ) {


  // Secondly we need to check if the user intended to change this value.
  if ( ! isset( $_POST['sp_noncename'] ) || ! wp_verify_nonce( $_POST['sp_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  // Thirdly we can save the value to the database

  //if saving in a custom table, get post_ID
  $post_ID = $_POST['post_ID'];
  //sanitize user input
  $date_published = sanitize_text_field( $_POST['sp_date_published'] );
  $publication = sanitize_text_field( $_POST['sp_publication'] );
  $sort_date = sanitize_text_field( $_POST['sp_sort_date'] );
  $sort_date_num = strtotime($sort_date);

  // Do something with $mydata 
  // either using 
  add_post_meta($post_ID, 'sp_date_published', $date_published, true) or
    update_post_meta($post_ID, 'sp_date_published', $date_published);
  add_post_meta($post_ID, 'sp_publication', $publication, true) or
    update_post_meta($post_ID, 'sp_publication', $publication);
  add_post_meta($post_ID, 'sp_sort_date', $sort_date, true) or
    update_post_meta($post_ID, 'sp_sort_date', $sort_date);
  add_post_meta($post_ID, 'sp_sort_date_num', $sort_date_num, true) or
    update_post_meta($post_ID, 'sp_sort_date_num', $sort_date_num);
  // or a custom table (see Further Reading section below)
}

//Article Categories taxonomy

function sp_article_categories() {
	$labels = array(
		'name'              => _x( 'Article Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Article Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Article Categories' ),
		'all_items'         => __( 'All Article Categories' ),
		'parent_item'       => __( 'Parent Article Category' ),
		'parent_item_colon' => __( 'Parent Article Category:' ),
		'edit_item'         => __( 'Edit Article Category' ), 
		'update_item'       => __( 'Update Article Category' ),
		'add_new_item'      => __( 'Add New Article Category' ),
		'new_item_name'     => __( 'New Article Category' ),
		'menu_name'         => __( 'Article Categories' ),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
	);
	register_taxonomy( 'article_category', 'articles', $args );
}
add_action( 'init', 'sp_article_categories', 0 );

if ( ! function_exists( 'phelan_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since Twenty Twelve 1.0
 */
function phelan_content_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentytwelve' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?></div>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
	<?php endif;
}
endif;