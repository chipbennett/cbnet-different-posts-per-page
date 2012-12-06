<?php
/**
 * cbnet Different Posts Per Page Plugin Options
 *
 * This file defines the Options for the cbnet Different Posts Per Page Plugin.
 * 
 * Plugin Options Functions
 * 
 *  - Define Default Plugin Options
 *  - Register/Initialize Plugin Options
 *  - Define Admin Settings Page
 *  - Register Contextual Help
 * 
 * @package 	cbnetdppp
 * @copyright	Copyright (c) 2012, Chip Bennett
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		cbnetdppp 2.0
 */

/**
 * Globalize the variable that holds the Plugin Options
 * 
 * @global	array	$cbnetdppp_options	holds Plugin options
 */
global $cbnetdppp_options;

/**
 * cbnetdppp Plugin Settings API Implementation
 *
 * Implement the WordPress Settings API for the 
 * cbnetdppp Plugin Settings.
 * 
 * @link	http://codex.wordpress.org/Settings_API	Codex Reference: Settings API
 * @link	http://ottopress.com/2009/wordpress-settings-api-tutorial/	Otto
 * @link	http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/	Ozh
 */
function cbnetdppp_register_options(){
	require( plugin_dir_path( __FILE__ ) . 'options-register.php' );
}
// Settings API options initilization and validation
add_action( 'admin_init', 'cbnetdppp_register_options' );


/**
 * Get cbnetdppp Plugin Options
 * 
 * Array that holds all of the defined values
 * for cbnetdppp Plugin options. If the user 
 * has not specified a value for a given Plugin 
 * option, then the option's default value is
 * used instead.
 *
 * @uses	cbnetdppp_get_option_defaults()	defined in \functions\options.php
 * 
 * @uses	get_option()
 * @uses	wp_parse_args()
 * 
 * @return	array	$cbnetdppp_options	current values for all Plugin options
 */
function cbnetdppp_get_options() {
	// Get option default values
	$default_options = cbnetdppp_get_option_defaults();
	// Get user-configured option values
	global $cbnetdppp_options;
	// Use user-configured option value;
	// if not defined, use the default value
	$cbnetdppp_options = wp_parse_args( get_option( 'plugin_cbnetdppp_options', array() ), $default_options );
	// Return option values
	return $cbnetdppp_options;
}

/**
 * cbnetdppp Plugin Option Defaults
 * 
 * Returns an associative array that holds 
 * all of the default values for all Plugin 
 * options.
 * 
 * @uses	cbnetdppp_get_option_parameters()	defined in \functions\options.php
 * 
 * @return	array	$defaults	associative array of option defaults
 */
function cbnetdppp_get_option_defaults() {
	$parameters = cbnetdppp_get_option_parameters();
	$defaults = array();
	foreach ( $parameters as $parameter ) {
		$name = $parameter['name'];
		$defaults[$name] = $parameter['default'];
	}
	return apply_filters( 'cbnetdppp_option_defaults', $defaults );
}

/**
 * cbnetdppp Plugin Option Parameters
 * 
 * Array that holds parameters for all options for
 * cbnetdppp. The 'type' key is used to generate
 * the proper form field markup and to sanitize
 * the user-input data properly. The 'tab' key
 * determines the Settings Page on which the
 * option appears, and the 'section' tab determines
 * the section of the Settings Page tab in which
 * the option appears.
 * 
 * @return	array	$options	array of arrays of option parameters
 */
function cbnetdppp_get_option_parameters() {
	$parameters = array(
		// Search Index Page Options
		'search_posts_per_page' => array(
			'name' => 'search_posts_per_page',
			'title' => __( 'Posts per page', 'cbnetdpp' ),
			'type' => 'text',
			'sanitize' => 'integer',
			'section' => 'search_index',
			'default' => cbnetdppp_get_default_posts_per_page(),
		),
		'search_order' => array(
			'name' => 'search_order',
			'title' => __( 'Order', 'cbnetdpp' ),
			'type' => 'select',
			'valid_options' => cbnetdppp_get_order_options(),
			'section' => 'search_index',
			'default' => cbnetdppp_get_default_order(),
		),
		'search_orderby' => array(
			'name' => 'search_orderby',
			'title' => __( 'Order By', 'cbnetdpp' ),
			'type' => 'select',
			'valid_options' => cbnetdppp_get_orderby_options(),
			'section' => 'search_index',
			'default' => cbnetdppp_get_default_orderby(),
		),
		// Blog Posts Index Page Options
		'blog_posts_per_page' => array(
			'name' => 'blog_posts_per_page',
			'title' => __( 'Posts per page', 'cbnetdpp' ),
			'type' => 'text',
			'sanitize' => 'integer',
			'section' => 'blog_index',
			'default' => cbnetdppp_get_default_posts_per_page(),
		),
		'blog_order' => array(
			'name' => 'blog_order',
			'title' => __( 'Order', 'cbnetdpp' ),
			'type' => 'select',
			'valid_options' => cbnetdppp_get_order_options(),
			'section' => 'blog_index',
			'default' => cbnetdppp_get_default_order(),
		),
		'blog_orderby' => array(
			'name' => 'blog_orderby',
			'title' => __( 'Order By', 'cbnetdpp' ),
			'type' => 'select',
			'valid_options' => cbnetdppp_get_orderby_options(),
			'section' => 'blog_index',
			'default' => cbnetdppp_get_default_orderby(),
		),
		// Category Archive Index Page Options
		'category_posts_per_page' => array(
			'name' => 'category_posts_per_page',
			'title' => __( 'Posts per page', 'cbnetdpp' ),
			'type' => 'text',
			'sanitize' => 'integer',
			'section' => 'category_index',
			'default' => cbnetdppp_get_default_posts_per_page(),
		),
		'category_order' => array(
			'name' => 'category_order',
			'title' => __( 'Order', 'cbnetdpp' ),
			'type' => 'select',
			'valid_options' => cbnetdppp_get_order_options(),
			'section' => 'category_index',
			'default' => cbnetdppp_get_default_order(),
		),
		'category_orderby' => array(
			'name' => 'category_orderby',
			'title' => __( 'Order By', 'cbnetdpp' ),
			'type' => 'select',
			'valid_options' => cbnetdppp_get_orderby_options(),
			'section' => 'category_index',
			'default' => cbnetdppp_get_default_orderby(),
		),
		// Tag Archive Index Page Options
		'tag_posts_per_page' => array(
			'name' => 'tag_posts_per_page',
			'title' => __( 'Posts per page', 'cbnetdpp' ),
			'type' => 'text',
			'sanitize' => 'integer',
			'section' => 'tag_index',
			'default' => cbnetdppp_get_default_posts_per_page(),
		),
		'tag_order' => array(
			'name' => 'tag_order',
			'title' => __( 'Order', 'cbnetdpp' ),
			'type' => 'select',
			'valid_options' => cbnetdppp_get_order_options(),
			'section' => 'tag_index',
			'default' => cbnetdppp_get_default_order(),
		),
		'tag_orderby' => array(
			'name' => 'tag_orderby',
			'title' => __( 'Order By', 'cbnetdpp' ),
			'type' => 'select',
			'valid_options' => cbnetdppp_get_orderby_options(),
			'section' => 'tag_index',
			'default' => cbnetdppp_get_default_orderby(),
		),
		// Taxonomy Archive Index Page Options
		'taxonomy_posts_per_page' => array(
			'name' => 'taxonomy_posts_per_page',
			'title' => __( 'Posts per page', 'cbnetdpp' ),
			'type' => 'text',
			'sanitize' => 'integer',
			'section' => 'taxonomy_index',
			'default' => cbnetdppp_get_default_posts_per_page(),
		),
		'taxonomy_order' => array(
			'name' => 'taxonomy_order',
			'title' => __( 'Order', 'cbnetdpp' ),
			'type' => 'select',
			'valid_options' => cbnetdppp_get_order_options(),
			'section' => 'taxonomy_index',
			'default' => cbnetdppp_get_default_order(),
		),
		'taxonomy_orderby' => array(
			'name' => 'taxonomy_orderby',
			'title' => __( 'Order By', 'cbnetdpp' ),
			'type' => 'select',
			'valid_options' => cbnetdppp_get_orderby_options(),
			'section' => 'taxonomy_index',
			'default' => cbnetdppp_get_default_orderby(),
		),
		// Author Archive Index Page Options
		'author_posts_per_page' => array(
			'name' => 'author_posts_per_page',
			'title' => __( 'Posts per page', 'cbnetdpp' ),
			'type' => 'text',
			'sanitize' => 'integer',
			'section' => 'author_index',
			'default' => cbnetdppp_get_default_posts_per_page(),
		),
		'author_order' => array(
			'name' => 'author_order',
			'title' => __( 'Order', 'cbnetdpp' ),
			'type' => 'select',
			'valid_options' => cbnetdppp_get_order_options(),
			'section' => 'author_index',
			'default' => cbnetdppp_get_default_order(),
		),
		'author_orderby' => array(
			'name' => 'author_orderby',
			'title' => __( 'Order By', 'cbnetdpp' ),
			'type' => 'select',
			'valid_options' => cbnetdppp_get_orderby_options(),
			'section' => 'author_index',
			'default' => cbnetdppp_get_default_orderby(),
		),
		// Date Archive Index Page Options
		'date_posts_per_page' => array(
			'name' => 'date_posts_per_page',
			'title' => __( 'Posts per page', 'cbnetdpp' ),
			'type' => 'text',
			'sanitize' => 'integer',
			'section' => 'date_index',
			'default' => cbnetdppp_get_default_posts_per_page(),
		),
		'date_order' => array(
			'name' => 'date_order',
			'title' => __( 'Order', 'cbnetdpp' ),
			'type' => 'select',
			'valid_options' => cbnetdppp_get_order_options(),
			'section' => 'date_index',
			'default' => cbnetdppp_get_default_order(),
		),
		'date_orderby' => array(
			'name' => 'date_orderby',
			'title' => __( 'Order By', 'cbnetdpp' ),
			'type' => 'select',
			'valid_options' => cbnetdppp_get_orderby_options(),
			'section' => 'date_index',
			'default' => cbnetdppp_get_default_orderby(),
		),
		// Archive Index Page Options
		'archive_posts_per_page' => array(
			'name' => 'archive_posts_per_page',
			'title' => __( 'Posts per page', 'cbnetdpp' ),
			'type' => 'text',
			'sanitize' => 'integer',
			'section' => 'archive_index',
			'default' => cbnetdppp_get_default_posts_per_page(),
		),
		'archive_order' => array(
			'name' => 'archive_order',
			'title' => __( 'Order', 'cbnetdpp' ),
			'type' => 'select',
			'valid_options' => cbnetdppp_get_order_options(),
			'section' => 'archive_index',
			'default' => cbnetdppp_get_default_order(),
		),
		'archive_orderby' => array(
			'name' => 'archive_orderby',
			'title' => __( 'Order By', 'cbnetdpp' ),
			'type' => 'select',
			'valid_options' => cbnetdppp_get_orderby_options(),
			'section' => 'archive_index',
			'default' => cbnetdppp_get_default_orderby(),
		),
	);
	return apply_filters( 'cbnetdppp_option_parameters', $parameters );
}

/**
 * cbnetdppp Plugin Admin Settings Page Sections
 * 
 * Array that holds all of the tabs for the
 * cbnetdppp Plugin Settings Page. Each tab
 * key holds an array that defines the 
 * sections for each tab, including the
 * description text.
 * 
 * @return	array	$tabs	array of arrays of section parameters
 */
function cbnetdppp_get_options_page_sections() {
	$sections = array(
		'search_index' => array(
			'name' => 'search_index',
			'title' => __( 'Search Index Page', 'cbnetdpp' ),
			'description' => __( 'Set query options for the Search index page.', 'cbnetdppp' ),
		),
		'blog_index' => array(
			'name' => 'blog_index',
			'title' => __( 'Blog Posts Index Page', 'cbnetdpp' ),
			'description' => __( 'Set query options for the Blog Posts Index page.', 'cbnetdppp' ),
		),
		'category_index' => array(
			'name' => 'category_index',
			'title' => __( 'Category Archive Index Page', 'cbnetdpp' ),
			'description' => __( 'Set query options for the Category archive index page.', 'cbnetdppp' ),
		),
		'tag_index' => array(
			'name' => 'tag_index',
			'title' => __( 'Tag Archive Index Page', 'cbnetdpp' ),
			'description' => __( 'Set query options for the Tag archive index page.', 'cbnetdppp' ),
		),
		'taxonomy_index' => array(
			'name' => 'taxonomy_index',
			'title' => __( 'Taxonomy Archive Index Page', 'cbnetdpp' ),
			'description' => __( 'Set query options for the Taxonomy archive index page.', 'cbnetdppp' ),
		),
		'author_index' => array(
			'name' => 'author_index',
			'title' => __( 'Author Archive Index Page', 'cbnetdpp' ),
			'description' => __( 'Set query options for the Author archive index page.', 'cbnetdppp' ),
		),
		'date_index' => array(
			'name' => 'date_index',
			'title' => __( 'Date Archive Index Page', 'cbnetdpp' ),
			'description' => __( 'Set query options for the Date archive index page.', 'cbnetdppp' ),
		),
		'archive_index' => array(
			'name' => 'archive_index',
			'title' => __( 'Archive Index Page', 'cbnetdpp' ),
			'description' => __( 'Set query options for the default Archive index page.', 'cbnetdppp' ),
		),
	);
	return apply_filters( 'cbnetdppp_sections', $sections );
}
 
 function cbnetdppp_get_orderby_options() {
	$orderby_options = array(
		'none' => __( 'None', 'cbnetdppp' ),
		'ID' => __( 'Post ID', 'cbnetdppp' ),
		'author' => __( 'Post Author', 'cbnetdppp' ),
		'title' => __( 'Post Title', 'cbnetdppp' ),
		'name' => __( 'Post Slug', 'cbnetdppp' ),
		'date' => __( 'Post Published Date', 'cbnetdppp' ),
		'modified' => __( 'Post Modified Date', 'cbnetdppp' ),
		'rand' => __( 'Random', 'cbnetdppp' ),
		'comment_count' => __( 'Post Comment Count', 'cbnetdppp' ),
	);
	return apply_filters( 'cbnetdppp_orderby_options', $orderby_options );
 }
 
 function cbnetdppp_get_order_options() {
	$order_options = array(
		'ASC' => __( 'Ascending', 'cbnetdppp' ),
		'DESC' => __( 'Descending', 'cbnetdppp' ),
	);
	return apply_filters( 'cbnetdppp_order_options', $order_options );
 }
 
 function cbnetdppp_get_default_posts_per_page() {
	return apply_filters( 'cbnetdppp_default_posts_per_page', get_option( 'posts_per_page' ) );
 }
 
 function cbnetdppp_get_default_order() {
	return apply_filters( 'cbnetdppp_default_order', 'DESC' );
 }
 
 function cbnetdppp_get_default_orderby() {
	return apply_filters( 'cbnetdppp_default_orderby', 'date' );
}

?>