=== cbnet Different Posts Per Page ===
Contributors: chipbennett
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QP3N9HUSYJPK6
Tags: cbnet, post, posts, different, custom, formatting, page, plugin, navigation, pages, category, archive, pagination, maxblogpress
Requires at least: 2.9
Tested up to: 3.5
Stable tag: 2.0.1

Customize the number of posts, orderby, and order parameters for all index pages (blog/home, search, category, tag, taxonomy, author, date, and archive index).

== Description ==

This plugin will allow you to customize the number of posts, orderby, and order parameters for all index page contexts, including the blog posts index, search index, and all archive indexes (category, tag, taxonomy, author, date, archive). Orderby settings support all values relevant to archive index pages, including none, ID, author, title, name, date, modified, rand, and comment_count. Order settings support ASC and DESC.

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
2. From your Admin UI (Dashboard), use the menu to select Options -> cbnet Different Posts Per Page 
3. Configure settings, and save

== Frequently Asked Questions ==

= Coming Soon =

Let me know what questions you have!

== Screenshots ==

Screenshots coming soon.


== Changelog ==

= 2.0.1 =
* Bugfix
** Replace deprecated is_taxonomy() with is_tax()
= 2.0 =
* Major Revision
* Plugin completely rewritten:
** Settings API support
** Implement settings via pre_get_posts
** Made Plugin parameters filterable
** Removed all cruft code
* WARNING: Old settings will not be retained
= 1.8.1 =
* Readme.txt update
* Updated Donate Link in readme.txt
= 1.8 =
* Initial Release
* Forked from MaxBlogPress Different Posts Per Page plugin version 1.7.6


== Upgrade Notice ==

= 2.0.1 =
* Bugfix. Replace deprecated is_taxonomy() with is_tax()
= 2.0 =
Major update. Plugin completely re-written. WARNING: Old settings will not be retained.
= 1.8.1 =
Readme.txt update. Updated Donate Link in readme.txt
= 1.8 =
Initial Release. Forked from MaxBlogPress Different Posts Per Page plugin version 1.7.6.
