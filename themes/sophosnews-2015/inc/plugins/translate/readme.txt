=== Plugin Name ===
Contributors: Mark Stockley
Donate link:
Tags: translation
Requires at least: Unknown
Tested up to: 3.3
Stable tag: 

This Plugin allows you to use machine translation to automatically translate Wordpress posts into upto 50 languages.

== Description ==

This Plugin uses Google Translate machine translation to automatically translate Wordpress posts into upto 50 languages.

== Installation ==

1. Upload `ctranslate.php` to the plugins directory
2. add wpcom_vip_load_plugin( 'ctranslate' ); to functions.php

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 0.1 =
Initial version designed to create translated pages on Wordpress VIP.

== Configuration ==

To setup machine translation you will need a Google Translate v2 API Key. Enter your API key into the Translation Settings 
screen and select which languages you would like your posts translated into by default.

== Use ==

The default language selections are also displayed on the post editing screen. You can deselect the default language choices
and add per-post language selections on the post editing screen.

When you publish a post the plugin creates a new draft translation, in English, for each target language selected. Translations
don't happen instantly but are scheduled to occur at 10 second intervals after the original post is saved. This is to prevent any
disruption that might be caused to the Wordpress installation by processing 50 simultaneous translations.

Once a scheduled translation has occured the new post is automatically published under the same URL as the original post but 
prepended with the appropriate two-letter ISO language code. 

Translations and draft translations are visible in the Translations list below the Posts list. Translations can be edited in the 
same way as Posts can.
