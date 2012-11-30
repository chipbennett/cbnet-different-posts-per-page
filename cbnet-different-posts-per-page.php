<?php
/*
 * Plugin Name:   cbnet Different Posts Per Page
 * Plugin URI:    http://www.chipbennett.net/wordpress/plugins/cbnet-different-posts-per-page/
 * Description:   Customize the number of posts, orderby, and order parameters for all index pages (blog/home, search, category, tag, taxonomy, author, date, and archive index).
 * Version:       2.0.1
 * Author:        chipbennett
 * Author URI:    http://www.chipbennett.net/
 *
 * License:       GNU General Public License, v2 (or newer)
 * License URI:  http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * Version 2.0 and later of this Plugin: Copyright (C) 2012 Chip Bennett,
 * Released under the GNU General Public License, version 2.0 (or newer)
 * 
 * Previous versions of this Plugin were derived from MaxBlogPress Different Posts Per Page plugin, version 1.7.6, 
 * Copyright (C) 2007 www.maxblogpress.com, released under the GNU General Public License.
 */

/**
 * Bootstrap Plugin Options
 */
include( plugin_dir_path( __FILE__ ) . 'options.php' );

/**
 * Globalize the variable that holds the Plugin Options
 * 
 * @global	array	$cbnetdppp_options	holds Plugin options
 */
global $cbnetdppp_options;
$cbnetdppp_options = cbnetdppp_get_options();

/**
 * Filter pre_get_posts
 */
function cbnetdppp_filter_pre_get_posts( $query ) {
	// Globalize Plugin options
	global $cbnetdppp_options;
	// Only modify the main query
	if ( is_main_query() ) {
		$context = '';
		// Search results index
		if ( is_search() ) {
			$context = 'search';
		}
		// Blog posts index
		else if ( is_home() ) {
			$context = 'blog';
		}
		// Category archive index
		else if ( is_category() ) {
			$context = 'category';
		}
		// Tag archive index
		else if ( is_tag() ) {
			$context = 'tag';
		}
		// Taxonomy archive index
		else if ( is_tax() ) {
			$context = 'taxonomy';
		}
		// Author archive index
		else if ( is_author() ) {
			$context = 'author';
		}
		// Date archive index
		else if ( is_date() ) {
			$context = 'date';
		}
		// Archive index
		else if ( is_archive() ) {
			$context = 'archive';
		}
		// Modify the query variables according to context
		if ( '' != $context ) {
			$query->set( 'posts_per_page', $cbnetdppp_options[$context . '_posts_per_page'] );
			$query->set( 'orderby', $cbnetdppp_options[$context . '_orderby'] );
			$query->set( 'order', $cbnetdppp_options[$context . '_order'] );
		}
	}
	return $query;
}
add_action( 'pre_get_posts', 'cbnetdppp_filter_pre_get_posts' );

?>