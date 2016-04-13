=== cbnet Different Posts Per Page ===
Contributors: chipbennett
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QP3N9HUSYJPK6
Tags: cbnet, posts_per_page, order, orderby, pre_get_posts
Requires at least: 3.3
Tested up to: 4.5
Stable tag: 2.2

Customize the number of posts, orderby, and order parameters for all index pages (blog/home, search, category, tag, taxonomy, author, date, and archive index).

== Description ==

This plugin will allow you to customize the number of posts, orderby, and order parameters for all index page contexts, including the blog posts index, search index, and all archive indexes (category, tag, taxonomy, author, date, archive). Orderby settings support all values relevant to archive index pages, including none, ID, author, title, name, date, modified, rand, and comment_count. Order settings support ASC and DESC.

Note: Plugin settings can be configured via Dashboard -> Settings -> Reading.

== Installation ==

Manual installation:

1. Upload the `cbnet-different-posts-per-page` folder to the `/wp-content/plugins/` directory

Installation using "Add New Plugin"

1. From your Admin UI (Dashboard), use the menu to select Plugins -> Add New
2. Search for 'cbnet Different Posts Per Page'
3. Click the 'Install' button to open the plugin's repository listing
4. Click the 'Install' button

Activiation and Use

1. Activate the plugin through the 'Plugins' menu in WordPress
2. From your Admin UI (Dashboard), use the menu to select Options -> Reading 
3. Configure settings, and save

== Frequently Asked Questions ==

= Coming Soon =

Let me know what questions you have!

== Screenshots ==

Screenshots coming soon.


== Changelog ==

= 2.2 =
* Maintenance Release
* Made Plugin translation-ready
= 2.1.1 =
* Bugfix
	* Stop Plugin from stomping on Widget Posts Widget query
= 2.1 =
* Bugfix
	* Fixed issue with incorrect user capability for settings page
* Enhancement
	* Moved Plugin settings to Settings -> Reading
= 2.0.1 =
* Bugfix
** Replace deprecated is_taxonomy() with is_tax()
= 2.0 =
* Major Revision
* Plugin completely rewritten:
	* Settings API support
	* Implement settings via pre_get_posts
	* Made Plugin parameters filterable
	* Removed all cruft code
* WARNING: Old settings will not be retained
= 1.8.1 =
* Readme.txt update
* Updated Donate Link in readme.txt
= 1.8 =
* Initial Release
* Forked from MaxBlogPress Different Posts Per Page plugin version 1.7.6


== Upgrade Notice ==

= 2.2 =
Maintenance Release. Made Plugin translation-ready
= 2.1.1 =
Bugfix: fixed issue with Plugin stomping on Recent Posts Widget query
= 2.1 =
Bugfix/Enhancement: fixed incorrect use capability for settings page, and moved settings to Settings -> Reading
= 2.0.1 =
Bugfix. Replace deprecated is_taxonomy() with is_tax()
= 2.0 =
Major update. Plugin completely re-written. WARNING: Old settings will not be retained.
= 1.8.1 =
Readme.txt update. Updated Donate Link in readme.txt
= 1.8 =
Initial Release. Forked from MaxBlogPress Different Posts Per Page plugin version 1.7.6.
