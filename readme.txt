=== Plugin Name ===
Contributors: taylorde
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=385223
Tags: recent,posts,post,excerpt,preview
Requires at least: 3.2
Tested up to: 3.2.1
Stable tag: 2.0

Simple widget that displays the recent posts with a short content preview. Control the length of the content preview and number of posts

== Description ==

This is a very simple plugin that mimics the effect of the "recent posts" widget included with Wordpress, but with the addition of a content preview. The plugin will, by default, display the name of the post (with a link) and a short bit of text from the post.

The number of characters to truncate is controllable in the widget panel.

== Changelog ==

= 2.0 =
* It's a long time coming, but I finally got around to updating this plugin for a modern WordPress! It now utilizes the widget architecture so you can have multiple instances running. Actually, it's a complete re-write of the plugin.
* Eliminated the "hard truncate" option, because it didn't really matter
* Updated the truncate algorithm slightly, so it will remove punctuation at the end of the truncate view
* Cleaned up (er-rewrote) the widget options so play nicer with others.
* This version doesn't use the options table to store options (the plugin architecture takes care of that for us), so unfortunately upgrading will cause any previous options to disappear. I clean up (delete) any existing options in the table.

= 1.2 =
* Added a "truncate excerpts" option allows you to shorten the content that is hand-written in the excerpt field of a new post.
* Added a "hard truncate" option which will shorten the preview content to exactly the number of characters you specify.

= 1.1 = 
* Fixed xhtml validation errors that this plugin was causing.


== Limitations ==

* The plugin strips out any HTML tags from the post before displaying it, so if you are looking to display images -- this isn't your plugin.

== Installation ==

Same ol'

Do your normal plugin installation routine. There are no settings, just the options in the widget itself. The widget title is TDD Recent Posts. It does not replace the existing Recent Posts widget.

== Screenshots ==
1. Plugin being displayed in the sidebar of Twenty Eleven (WordPress' default theme)
2. Widget admin options.

== Frequently Asked Questions ==


= Can I set it to display pages as well as posts? =

Yes -- actually, any post type or any query you want -- although it is not in the admin menu.
1. Open tddrecentposts.php
1. Around line 124, you'll find the query arguments.
1. Add (or remove) query arguments from the array as discussed here: http://codex.wordpress.org/Function_Reference/query_posts
1. Or, request that I add the feature in the next version.

= ________ screwed up OR _________ isn't working... =

Sorry? Plugin dev isn't my full time job. Shoot me an email or post on my website and I'll try to fix it.