=== SEO Auto Links ===
Contributors: maartenbrakkee
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=AF5JSJ5X6Q3VJ&item_name=SEO%20Auto%20Links&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHoste
Tags:  post, posts, pages, tags, categories, comments, links, seo, google, automatic, link
Requires at least: 2.3
Tested up to: 3.9
Stable tag: 0.5
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

With SEO Auto Links you can easily add links (automatically) for keywords and phrases in posts, pages and comments.

== Description == 

With SEO Auto Links you can easily add links (automatically) for keywords and phrases in posts, pages and comments and link them to corresponding posts, pages, categories, tags or any URL.

On the organized settings page in the admin area you can change settings for internal links, custom keywords, excluding and targeting.

== Changelog == 

= Wishlist =
* Enable/disable creating links in widgets
* UTF-8 support (cyrillic)
* Custom fields
* Custom title with custom keywords
* Fix blank return the_content() sometimes
* Two word keywords, exclusion list

= 0.5 =
* Fixed can't save some options bug
* Polish translation added (thanks to micslunet)

= 0.4 =
* Changed function names to fix cannot redeclare error
* Updated exclude input boxes
* Code clean-up

= 0.3 =
* Added Dutch translation

= 0.2 =
* Some code clean-up

= 0.1 =
* First release

== Credits ==

The SEO Auto links plugin is based on the SEO Smart Links 2.7.6 by Vladimir Prelovac:

* [Vladimir Prelovac ](http://www.prelovac.com/vladimir/  "Vladimir Prelovac ") for his [SEO Smart Links](http://wordpress.org/plugins/seo-automatic-links/  "SEO Smart Links") plugin.

== Installation ==

1. Upload the complete seo-auto-links folder to your /wp-content/plugins/ folder.
2. Go to the Plugins page and activate the plugin.
3. Use the SEO Auto Links settings page to change the settings for SEO Auto Links.
4. Enjoy the automatically inserted links.

== Screenshots ==

1. Plugin settings page

== License ==

This file is part of SEO Auto links.

SEO Auto links is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

SEO Auto links is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with SEO Auto links. If not, see <http://www.gnu.org/licenses/>.

== Frequently Asked Questions ==

**What's the difference between SEO Auto Links and SEO Smart Links?**

SEO Auto Links is based on SEO Smart Links 2.7.6 by Vladimir Prelovac. SEO Auto Links has a redesigned settings page with the settings grouped under internal links, custom keywords, excluding and targeting. Furthermore is SEO Auto Links completely translatable, so you can easily translate it to you native language (please contact me if you have a translation finished).

**How do I enable SEO Auto links cache?**

The only thing you need to do is enabling WordPress cache inside wp-config.php:

define(ENABLE_CACHE, true);

Make a backup of this file before you make any changes!

**How can I review the keyword generated and add exclusions?**

Unfortunately it is only possible to view the keywords on the posts or pages self. There is no list of generated keywords. You can add exclusions on the settings page (Settings > SEO Auto Links).

**What is "allow self linking"?**

When allow self linking is enabled, it is possible that SEO Auto Links creates links on a page to the page itself. When allow self linking is turned off SEO Auto Links will only genrate links to other posts or pages.

**Why would I want to specify exclusions in the excluding section? In the previous sections I've specified keywords and 'Enabled' posts only.**

It could be possible you want specific keywords to auto link, but not on specific posts or pages. Futhermore you don't need to specifiy keywords for SEO Auto Links to work, but you can always exclude keywords if needed.

**What does the targeting section do?**

In the Internal Links section you can enable SEO Auto Links for different post types (on which post types should there be linked keywords?). In the Targeting section you can enable which post types SEO Auto Links should link to.

**When will future x included or bug y fixed?**

At the moment I have little spare time to work on this plugin. Thus future bug fixes and requests could take some time.