<?php
/*
 * Plugin Name:   cbnet Different Posts Per Page
 * Plugin URI:    http://www.chipbennett.net/wordpress/plugins/cbnet-different-posts-per-page/
 * Description:   Show different number of posts in home, category, search or archive page. (Note: this plugin is a fork of the MaxBlogPress Different Posts Per Page plugin, with registration/activiation functionality removed.) Adjust settings <a href="options-general.php?page=cbnet-different-posts-per-page/cbnet-different-posts-per-page.php">here</a>.
 * Version:       1.8.1
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
 * This program was modified from MaxBlogPress Different Posts Per Page plugin, version 1.7.6, 
 * Copyright (C) 2007 www.maxblogpress.com, released under the GNU General Public License.
 */
 
define('cbnetdppp_NAME', 'cbnet Different Posts Per Page');
define('cbnetdppp_VERSION', '1.8.1');

/**
 * cbnetdppp - cbnet Different Posts Per Page Class
 * Holds all the necessary functions and variables
 */
class cbnetdppp 
{
    /**
     * Different posts per page plugin path
     * @var string
     */
	var $cbnetdppp_path    = "";
	
    /**
     * Different posts per page values set by admin
     * @var array
     */
	var $cbnetdppp_values  = array();
	
    /**
     * Options available in different posts per page values
     * @var array
     */
	var $cbnetdppp_option  = array();
	
    /**
     * Parsed query string variables are stored in this array
     * @var array
     */
	var $cbnetdppp_qvars   = array();
	
    /**
     * Holds the post/page type. If its home, single, archive, etc.
     * @var string
     */
	var $cbnetdppp_pg_type = "";
	
    /**
     * Holds post category
     * @var string
     */
	var $cbnetdppp_cat     = "";
	
    /**
     * Holds query string
     * @var string
     */
	var $cbnetdppp_qstr    = "";
	
    /**
     * Holds Post/Get data
     * @var array
     */
	var $cbnetdppp_request    = array();
	
    /**
     * Holds wordpress version
     * @var string
     */
	var $cbnetdppp_wp_version = "";
	
    /**
     * Holds the default 'posts per page' values
	 * These values will be set while activating the plugin
     * @var array
     */
	var	$default_options = array(
								'is_home' => array(
									'posts_per_page' => 5,
									'what_to_show'   => 'posts',
									'orderby'        => 'date',
									'order'          => 'DESC'
								),
								'is_category' => array(
									'posts_per_page' => 5,
									'what_to_show'   => 'posts',
									'orderby'        => 'date',
									'order'          => 'DESC'
								),
								'is_archive' => array(
									'posts_per_page' => 5,
									'what_to_show'   => 'posts',
									'orderby'        => 'date',
									'order'          => 'DESC'
								),
								'is_search' => array(
									'posts_per_page' => 5,
									'what_to_show'   => 'posts',
									'orderby'        => 'date',
									'order'          => 'DESC'
								),
								'is_tag' => array(
									'posts_per_page' => 5,
									'what_to_show'   => 'posts',
									'orderby'        => 'date',
									'order'          => 'DESC'
								)
							);

    /**
     * Holds the various pages available in wordpress
     * @var array
     */
	var $cbnetdppp_pages      = array('is_home'=>'Home', 'is_category'=>'Category', 'is_archive'=>'Archive', 'is_search'=>'Search', 
								'is_author'=>'Author', 'is_paged'=>'Paged', 'is_feed'=>'Feed', 'is_date'=>'Date', 
								'is_year'=>'Year', 'is_month'=>'Month', 'is_day'=>'Day', 'is_time'=>'Time', 'is_tag'=>'Tag');			
	var $cbnetdppp_shows      = array('posts');
	var $cbnetdppp_orderby    = array('date');
	var $cbnetdppp_orders     = array('DESC', 'ASC');

	
	/**
	 * Constructor. Add cbnetdppp plugin actions/filters and gets the user defined options.
	 * @access public
	 */
	function cbnetdppp() {
		global $wp_version;
		$this->cbnetdppp_wp_version = $wp_version;
		$default_posts_per_page = get_option("posts_per_page");
		$this->default_options[is_home][posts_per_page] 	= $default_posts_per_page;
		$this->default_options[is_category][posts_per_page] = $default_posts_per_page;
		$this->default_options[is_archive][posts_per_page] 	= $default_posts_per_page;
		$this->default_options[is_search][posts_per_page] 	= $default_posts_per_page;
		$this->default_options[is_tag][posts_per_page]      = $default_posts_per_page;

		$this->cbnetdppp_path     = preg_replace('/^.*wp-content[\\\\\/]plugins[\\\\\/]/', '', __FILE__);
		$this->cbnetdppp_path     = str_replace('\\','/',$this->cbnetdppp_path);
		$this->cbnetdppp_siteurl  = get_bloginfo('wpurl');
		$this->cbnetdppp_siteurl  = (strpos($this->cbnetdppp_siteurl,'http://') === false) ? get_bloginfo('siteurl') : $this->cbnetdppp_siteurl;
		$this->cbnetdppp_fullpath = $this->cbnetdppp_siteurl.'/wp-content/plugins/'.substr($this->cbnetdppp_path,0,strrpos($this->cbnetdppp_path,'/')).'/';
		$this->cbnetdppp_abspath  = str_replace("\\","/",ABSPATH); 
		$this->img_how       = '<img src="'.$this->cbnetdppp_fullpath.'images/how.gif" border="0" align="absmiddle">';
		$this->img_comment   = '<img src="'.$this->cbnetdppp_fullpath.'images/comment.gif" border="0" align="absmiddle">';
		
	    add_action('activate_'.$this->cbnetdppp_path, array(&$this, 'cbnetdpppActivate'));
		add_action('admin_menu', array(&$this, 'cbnetdpppAddMenu'));
		
		$this->cbnetdppp_activate = get_option('cbnetdppp_activate');
		if( !$this->cbnetdppp_values = get_option('diff_posts_per_page') ) {
			$this->cbnetdppp_values = array();
		}
		
		add_filter('query_string', array(&$this, 'cbnetdpppCustomQuery'));
		if ( 
		$this->cbnetdppp_values['is_home']['posts_per_page'] > 0 && $this->cbnetdppp_values['is_paged']['posts_per_page'] > 0 && 
		($this->cbnetdppp_values['is_home']['posts_per_page'] != $this->cbnetdppp_values['is_paged']['posts_per_page']) 
		) {
			$this->cbnetdppp_the_diff = intval($this->cbnetdppp_values['is_paged']['posts_per_page'] - $this->cbnetdppp_values['is_home']['posts_per_page']);
			add_filter('post_limits', array(&$this, 'cbnetdpppAlterLimits'));
		}
	}
	
	/**
	 * Called when plugin is activated. Adds option_value to the options table.
	 * @access public
	 */
	function cbnetdpppActivate() {
		add_option('diff_posts_per_page', $this->default_options, 'cbnet Different Posts Per Page plugin options', 'no');
		return true;
	}
	
	/**
	 * Make changes in the Lower Limit in the LIMIT portion of the query string.
	 * Required if admin has set 'posts_per_page' for is_paged.
	 * Solves the problem of some posts not being displayed.
	 * @param string $limits
	 * @return string $limits
	 * @access public
	 */
	function cbnetdpppAlterLimits($limits) {
		if ( trim($limits) ) {
			$cbnetdppp_limits_arr = explode(",",trim(strstr(trim($limits), " ")));
			$cbnetdppp_limit_from = trim($cbnetdppp_limits_arr[0]);
			$cbnetdppp_limit_to   = trim($cbnetdppp_limits_arr[1]);
			if ( $cbnetdppp_limit_from > 0 ) {
				$cbnetdppp_limit_from = $cbnetdppp_limit_from - $this->cbnetdppp_the_diff;
				$limits = " LIMIT ".$cbnetdppp_limit_from.", ".$cbnetdppp_limit_to;
			}
		}
		return $limits;
	}
	
	/**
	 * Adds user defined query options to the main query string
	 * Rebuilds the query string
	 * @param string $query_string The main query string
	 * @return string The custom query string
	 * @access public
	 */
	function cbnetdpppCustomQuery($query_string) {
		$query_str = $query_string;
		parse_str($query_str, $qvars);
		$this->cbnetdppp_qstr  = $query_str;
		$this->cbnetdppp_qvars = $qvars;
		if ( $this->cbnetdppp_values ) {
			$this->cbnetdpppGetPageType();
			$this->cbnetdpppGetCategory();
		}
		if ( $this->cbnetdppp_cat ) {
			$this->cbnetdppp_option = $this->cbnetdppp_values[$this->cbnetdppp_cat];
		} else if ( $this->cbnetdppp_pg_type ) {
			$this->cbnetdppp_option = $this->cbnetdppp_values[$this->cbnetdppp_pg_type];
		}
		if ( $this->cbnetdppp_option ) {
			$cbnetdppp_query_str = array(
				'posts_per_page' => $this->cbnetdppp_option['posts_per_page'],
				'what_to_show'   => $this->cbnetdppp_option['what_to_show'],
				'orderby'        => $this->cbnetdppp_option['orderby'],
				'order'          => $this->cbnetdppp_option['order']
				);
			$this->cbnetdppp_qvars = array_merge($cbnetdppp_query_str,$this->cbnetdppp_qvars);
			foreach ( $this->cbnetdppp_qvars as $key=>$val ) {
				$queryvars[] = $key.'='.$val;
			}
			$this->cbnetdppp_qstr = implode('&', $queryvars);
		}
		return $this->cbnetdppp_qstr;
	}
	
	/**
	 * Determines the page type.
	 * Archive page => Author, Category, Date
	 * Date => Time, Day, Month, Year
	 * @access public 
	 */
	function cbnetdpppGetPageType() {
		global $wp_query;
		$wp_query->parse_query($this->cbnetdppp_qstr);

		if ( $wp_query->is_feed AND $this->cbnetdppp_values['is_feed'] ) {
			$this->cbnetdppp_pg_type = 'is_feed';
		} else if ( $wp_query->is_paged AND $this->cbnetdppp_values['is_paged'] ) {
			$this->cbnetdppp_pg_type = 'is_paged';
		} else if ( $wp_query->is_tag AND $this->cbnetdppp_values['is_tag'] ) {
			$this->cbnetdppp_pg_type = 'is_tag';
		} else if ( $wp_query->is_archive ) {
			if ( $wp_query->is_author AND $this->cbnetdppp_values['is_author'] ) {
				$this->cbnetdppp_pg_type = 'is_author';
			} else if ( $wp_query->is_category AND $this->cbnetdppp_values['is_category'] ) {
				$this->cbnetdppp_pg_type = 'is_category';
			} else if ( $wp_query->is_date ) {
				if ( $wp_query->is_time AND $this->cbnetdppp_values['is_time'] ) {
					$this->cbnetdppp_pg_type = 'is_time';
				} else if ( $wp_query->is_day AND $this->cbnetdppp_values['is_day'] ) {
					$this->cbnetdppp_pg_type = 'is_day';
				} else if ( $wp_query->is_month AND $this->cbnetdppp_values['is_month'] ) {
					$this->cbnetdppp_pg_type = 'is_month';
				} else if ( $wp_query->is_year AND $this->cbnetdppp_values['is_year'] ) {
					$this->cbnetdppp_pg_type = 'is_year';
				} else if ( $this->cbnetdppp_values['is_date'] ) {
					$this->cbnetdppp_pg_type = 'is_date';
				} else if ( $this->cbnetdppp_values['is_archive'] ) {
					$this->cbnetdppp_pg_type = 'is_archive';
				}
			}
		} else if ( $wp_query->is_search AND $this->cbnetdppp_values['is_search'] ) {
			$this->cbnetdppp_pg_type = 'is_search';
		} else if ( function_exists(is_tag) AND is_tag() AND $this->cbnetdppp_values['is_tag'] ) {
			$this->cbnetdppp_pg_type = 'is_tag';		
		} else if ( $wp_query->is_home AND $this->cbnetdppp_values['is_home'] ) {
			$this->cbnetdppp_pg_type = 'is_home';
		}
	}
	
	/**
	 * Get the category id from category nice_name.
	 * @access public 
	 */
	function cbnetdpppGetCategory() {
		global $wp_query;
		global $wpdb;
		
		if ( $wp_query->is_category ) {
			if ( !($category_id = $wp_query->get('cat')) ) {
				$category    = $wp_query->get('category_name');
				if ( $this->cbnetdppp_wp_version < 2.3 ) {
					$sqlstr  = "SELECT cat_ID FROM $wpdb->categories WHERE category_nicename = '". $wpdb->escape($category) ."'";
				} else {
					$sqlstr  = "SELECT term_id FROM $wpdb->terms WHERE slug = '". $wpdb->escape($category) ."'";
				}
				$category_id = (int) $wpdb->get_var($sqlstr);
			}
			if ( $this->cbnetdppp_values['cat_'.$category_id] ) {
				$this->cbnetdppp_cat = 'cat_'.$category_id;
			}
		}
	}
	
	/**
	 * Get the category_nicename from category id
	 * @param int $catid Category Id.
	 * @return string Category nicename.
	 * @access public 
	 */
	function cbnetdpppGetCategoryNicename($catid) {
		global $wpdb;
		if ( $this->cbnetdppp_wp_version < 2.3 ) {
			$sqlstr  = "SELECT category_nicename FROM $wpdb->categories WHERE cat_ID = '". $wpdb->escape((int) $catid) ."'";
		} else {
			$sqlstr  = "SELECT name FROM $wpdb->terms WHERE term_id = '". $wpdb->escape((int) $catid) ."'";
		}
		return $wpdb->get_var($sqlstr);
	}
	
	/**
	 * Adds "DiffPostsPerPage" link to admin Options menu
	 * @access public 
	 */
	function cbnetdpppAddMenu() {
		add_options_page('Different Posts Per Page', 'DiffPostsPerPage', 'manage_options', $this->cbnetdppp_path, array(&$this, 'cbnetdpppOptionsPg'));
	}
	
	/**
	 * Displays the page content for "DiffPostsPerPage" Options submenu
	 * Carries out all the operations in Options page
	 * @access public 
	 */
	function cbnetdpppOptionsPg() {
		load_plugin_textdomain('DiffPostsPerPage');
		$this->cbnetdppp_request = $_REQUEST['cbnetdppp'];
		
		if ( $this->cbnetdppp_request['add_pg'] || $this->cbnetdppp_request['add_cat'] ) {
			$this->cbnetdpppAddOptions();
			$this->cbnetdpppShowOptionsPage($msg=1);
		} else if ( $this->cbnetdppp_request['delete_checked'] ) {
			$this->cbnetdpppDeleteOptions();
			$this->cbnetdpppShowOptionsPage($msg=2);
		} else if ( $this->cbnetdppp_request['save_all'] ) {
			$this->cbnetdpppSaveOptions();
			$this->cbnetdpppShowOptionsPage($msg=3);
		} else {
			$this->cbnetdpppShowOptionsPage($msg);
		}
	}
	
	/**
	 * Adds and updates 'posts per page' options
	 * @access public 
	 */
	function cbnetdpppAddOptions() {
		if ($this->cbnetdppp_request['add_cat']) {
			$cbnetdppp_options_new = array(
				'cat_'. $_REQUEST['cat'] => array(
					'posts_per_page' => intval($this->cbnetdppp_request['category']['posts_per_page']),
					'what_to_show'   => $this->cbnetdppp_request['category']['what_to_show'],
					'orderby'        => $this->cbnetdppp_request['category']['orderby'],
					'order'          => $this->cbnetdppp_request['category']['order']
				));
		}
		else {
			$cbnetdppp_options_new = array(
				$this->cbnetdppp_request['page'] => array(
					'posts_per_page' => intval($this->cbnetdppp_request['posts_per_page']),
					'what_to_show'   => $this->cbnetdppp_request['what_to_show'],
					'orderby'        => $this->cbnetdppp_request['orderby'],
					'order'          => $this->cbnetdppp_request['order']
				));
		}
		$this->cbnetdppp_values = array_merge($this->cbnetdppp_values, $cbnetdppp_options_new);
		update_option('diff_posts_per_page', $this->cbnetdppp_values);
	}
	
	/**
	 * Saves the current 'posts per page' options
	 * @access public 
	 */
	function cbnetdpppSaveOptions() {
		if ( count($this->cbnetdppp_request['option_save']) ) {
			foreach ( $this->cbnetdppp_request['option_save'] as $key=>$pg ) {
				$cbnetdppp_options_new[$pg] = array(
							'posts_per_page' => intval($this->cbnetdppp_request['option_posts_per_page'][$key]),
							'what_to_show'   => 'posts',
							'orderby'        => 'date',
							'order'          => $this->cbnetdppp_request['option_order'][$key]
							);
			}
		}
		$this->cbnetdppp_values = $cbnetdppp_options_new;
		update_option('diff_posts_per_page', $this->cbnetdppp_values);
	}
	
	/**
	 * Deletes 'posts per page' options
	 * @access public 
	 */
	function cbnetdpppDeleteOptions() {
		if ( count($this->cbnetdppp_request['delete']) ) {
			foreach ( $this->cbnetdppp_request['delete'] as $pg ) {
				unset($this->cbnetdppp_values[$pg]);
			}
		}
		update_option('diff_posts_per_page', $this->cbnetdppp_values);
	}
	
	/**
	 * Page Header
	 */
	function cbnetdpppHeader() {
		echo '<h2>'.cbnetdppp_NAME.' '.cbnetdppp_VERSION.'</h2>';
	}
	
	/**
	 * Page Footer
	 */
	function cbnetdpppFooter() {
		echo '<p style="text-align:center;margin-top:3em;"><strong>'.cbnetdppp_NAME.' '.cbnetdppp_VERSION.' by <a href="http://www.chipbennett.net/" target="_blank" >Chip Bennett</a></strong></p>';
	}
	
	/**
	 * Display the options page
	 * @param string $msg Update/Delete message to be shown
	 * @access public 
	 */
	function cbnetdpppShowOptionsPage($msg=0) {
		if ( $msg==1 || $msg==3 ) {
			$msg = "'posts per page' options saved.";
		} else if ( $msg==2 ) {
			$msg = "'posts per page' options deleted.";
		}
		if ( $msg ) {
			echo '<div id="message" class="updated fade"><p><strong>'. __($msg, 'cbnetdppp') .'</strong></p></div>';
		}
		?>
		<script>
		//<!--
		function isNumeric(num){
			var the_val = num.value;
			var ret = (/^-?[0-9]*$/.test(the_val));
			if ( ret == false ) {
				alert('Should be a numeric value');
				num.value = the_val.substr(the_val,the_val.length-1);
				return false;
			}
			return true;
		}
		function toggleAll(parent) {
			var now = parent.checked;
			var frm = document.cbnetdpppform;
			var len = frm.elements.length;
			for ( i=0; i<len; i++ ) {
				if ( frm.elements[i].name=='cbnetdppp[delete][]' ) {
					frm.elements[i].checked=now;
				}
			}
		}//--></script>
		<style type="text/css">
		table, tbody, tfoot, thead, tr, th, td {
			padding: 3px;
		}</style>
		<form name="cbnetdpppform" method="post">
		<div class="wrap">
		 <?php $this->cbnetdpppHeader(); ?>
		 <h3><?php _e('Current "posts per page" options', 'cbnetdppp'); ?></h3>
		 <table cellspacing="1" cellpadding="3" width="60%" style="border:1px solid #dddddd; padding:0;">
		  <tr>
		   <td style="background-color:#dfdfdf" width="10%"><div align="center"><input type="checkbox" name="checkall" onclick="toggleAll(this)"/></div></td>
		   <td style="background-color:#dfdfdf"><strong><?php _e('Page', 'cbnetdppp'); ?></strong></td>
		   <td style="background-color:#dfdfdf" width="20%"><div align="center"><strong><?php _e('Show', 'cbnetdppp'); ?></strong></div></td>
		   <td style="background-color:#dfdfdf" width="15%"><div align="center"><strong><?php _e('Order', 'cbnetdppp'); ?></strong></div></td>
		  </tr>
	
		<?php
		  $i = 0;
		  foreach ( (array) $this->cbnetdppp_values as $thepage => $cbnetdppp_option ) {
		     if (strpos($thepage, 'cat_') !== false) {
			   $cat_id    = str_replace('cat_', '', $thepage);
			   $cat_name  = $this->cbnetdpppGetCategoryNicename(intval($cat_id));
			   $page_name = substr($cat_name,0,45).' (category)';
		     }
		     else {
			   $page_name = $this->cbnetdppp_pages[$thepage];
		     }
			 $bgcol = ($i % 2 != 0) ? '#f1f1f1' : '#ffffff';
			 ?>
		     <tr>
			  <td style="background-color:<?php echo $bgcol;?>"><div align="center"><input type="checkbox" name="cbnetdppp[delete][]" value="<?php echo $thepage; ?>" /></div></td>
			  <td style="background-color:<?php echo $bgcol;?>"><?php echo $page_name; ?><input type="hidden" name="cbnetdppp[option_save][]" value="<?php echo $thepage; ?>" /></td>
			  <td style="background-color:<?php echo $bgcol;?>"><div align="center"><input type="text" name="cbnetdppp[option_posts_per_page][]" value="<?php echo $cbnetdppp_option['posts_per_page'];?>" size="2" maxlength="5" onkeyup="isNumeric(this);" /> posts</div></td>
		      <td style="background-color:<?php echo $bgcol;?>"><div align="center"><select name="cbnetdppp[option_order][]">
              <?php foreach ($this->cbnetdppp_orders as $order) {
			  		$selected='';
			  		if ($cbnetdppp_option['order']==$order) $selected='selected';
					echo "<option $selected>$order</option>";
			  } ?>
              </select></div></td>
			 </tr>
		<?php
		    $i++;
		  } // Eof foreach
		?>
		    <tr>
			 <td style="background-color:#ffffff" colspan="4">
				<input type="submit" class="button" name="cbnetdppp[save_all]" value="<?php _e('Save All', 'cbnetdppp'); ?>" />	 
				<input type="submit" class="button" name="cbnetdppp[delete_checked]" value="<?php _e('Delete Checked', 'cbnetdppp'); ?>" /></td>
			</tr>
		</table>
	
	    <h3 style="margin-top:4em;"><?php _e('Add more "posts per page" option', 'cbnetdppp'); ?></h3>
		<p><?php _e('# Use \'-1\' to show all posts.', 'cbnetdppp'); ?></p>
		<table cellspacing="1" cellpadding="3" width="80%" style="border:1px solid #dddddd; padding:0;">
			<tr class="alternate">
				<td style="background-color:#dfdfdf" width="14%">&nbsp;</td>
				<td style="background-color:#dfdfdf"><strong><?php _e('Select', 'cbnetdppp'); ?></strong></td>
				<td style="background-color:#dfdfdf" width="15%"><div align="center"><strong><?php _e('Show', 'cbnetdppp'); ?></strong></div></td>
				<td style="background-color:#dfdfdf" width="10%"><div align="center"><strong><?php _e('Order', 'cbnetdppp'); ?></strong></div></td>
				<td style="background-color:#dfdfdf" width="10%">&nbsp;</td>
			</tr>
			<tr>
				<td style="background-color:#ffffff" scope="row"><?php _e('Page', 'cbnetdppp'); ?></td>
				<td style="background-color:#ffffff">
				<select name="cbnetdppp[page]">
				<?php
				foreach ($this->cbnetdppp_pages as $key=>$val) {
					echo "<option value='".$key."'>$val</option>";
				}
				?>
				</select></td>
				<td style="background-color:#ffffff"><div align="center">
				<input type="text" name="cbnetdppp[posts_per_page]" size="3" maxlength="5" onkeyup="isNumeric(this);" /> posts
				<input type="hidden" name="cbnetdppp[what_to_show]" value="<?php echo $this->cbnetdppp_shows[0];?>" /></div></td>
				<td style="background-color:#ffffff"><div align="center">
				<input type="hidden" name="cbnetdppp[orderby]" value="<?php echo $this->cbnetdppp_orderby[0];?>" />
				<select name="cbnetdppp[order]">
                <?php 
				foreach ($this->cbnetdppp_orders as $order) {
					echo "<option>$order</option>";
				}
				?>
                </select></div></td>
				<td style="background-color:#ffffff"><div align="center"><input type="submit" class="button" name="cbnetdppp[add_pg]" value="<?php _e('Add', 'cbnetdppp'); ?>" /></div></td>
			</tr>
			<tr>
				<td style="background-color:#f1f1f1"><?php _e('Category', 'cbnetdppp'); ?></td>
				<td style="background-color:#f1f1f1">
				<?php dropdown_cats(0, 'All', 'ID', 'asc', 0, 0, 0, FALSE, 0, 0) ?></td>
				<td style="background-color:#f1f1f1"><div align="center">
				<input type="text" name="cbnetdppp[category][posts_per_page]" size="3" maxlength="5" onkeyup="isNumeric(this);" /> posts
				<input type="hidden" name="cbnetdppp[category][what_to_show]" value="<?php echo $this->cbnetdppp_shows[0];?>" /></div></td>
				<td style="background-color:#f1f1f1"><div align="center">
				<input type="hidden" name="cbnetdppp[category][orderby]" value="<?php echo $this->cbnetdppp_orderby[0];?>" />
				<select name="cbnetdppp[category][order]">
				<?php 
				foreach ($this->cbnetdppp_orders as $order) {
					echo "<option>$order</option>";
				}
				?>
				</select></div></td>
				<td style="background-color:#f1f1f1"><div align="center"><input type="submit" class="button" name="cbnetdppp[add_cat]" value="<?php _e('Add', 'cbnetdppp'); ?>" /></div></td>
			</tr>
		</table>
		<?php $this->cbnetdpppFooter(); ?>
	    </div>
	    </form>
		<?php
	}
	
} // End of Class

$cbnetdppp = new cbnetdppp();
?>