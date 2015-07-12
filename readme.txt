=== Decorative Caps with Series ===
I did this.
7/11/15

This is based on the plugin "Decorative Caps" by Pianosteve. 

This plugin requires Organize Series by Darren Ethier (2.4.6 has been tested but other versions should work). It serves no purpose without it. If you need decorative caps functionality without series, use the Decorative Caps plugin by Pianosteve. 


=== Decorative Caps with Series ===
Contributors: RhapsodyInProse
Donate link: Scintilliarium.com
Tags: formatting, decorative caps, series
Requires at least: 3.0
Tested up to: 4.2.2
Stable tag: 2.0.1

Replaces the first letter of the post (if it is a letter) on a per-series basis with the font/color/text decoration of your choice, in your theme's CSS file. Requires Organize Series by Darren Ethier (2.4.6 has been tested, but other versions should work). 

Solo Dei Gloria

== Description ==

The first letter of a post (provided that the post does not start with an image or a series of HTML tags -- some basic parsing to check this takes place) is modified as specified in the corresponding CSS class. This works even in posts that are split up into pages.  

An options page for the plugin includes specifying the CSS class name for each series.

NOTE: I have assumed that each site will have 10 or less series.  

To format the initial letter of each post in a series, you must:
    * specify a CSS class name for each series on the options page
    * create the CSS class in your theme's stylesheet.   

== Installation ==

1. Upload 'decorative-caps-with-series' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Specify the class name for each series on the options page. 

NOTE: '' is the default value. Nothing will happen if you leave the field for a series untouched. 


== Frequently Asked Questions ==

= Do you provide any support for this plugin? =

I will fix bugs. However, please don't be a turd. 

== Changelog ==

= 2.0.1 =
* Fixed include statement so plugin works with non-local hosts (duh).
