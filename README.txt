=== Plugin Name ===
Contributors: C. E.
Donate link: 
Tags: CDF, Computable Document Format, Mathematica, Wolfram Language, Stack Exchange, syntax highlighter
Requires at least: 3.0.1
Tested up to: 4.9.8
Stable tag: 1.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Improves your website with highlighted Mathematica code, embedded CDFs, and Wolfram Cloud content.

== Description ==

Mathematica Toolbox adds a set of shortcodes that can do Mathematica code highlighting, CDF embedding, and more. For detailed information about all the shortcodes please visit the documentation:
[http://cekdahl.github.io/MathematicaToolbox](http://cekdahl.github.io/MathematicaToolbox)

If you would like to control WordPress programmatically from a Mathematica notebook, check out the Wolfram Language WordPress XML-RPC client:
[http://github.com/cekdahl/wl-wordpress-xmlrpc-client](http://github.com/cekdahl/wl-wordpress-xmlrpc-client)

**Syntax highlighting and formatting**

* Uses the same highlighting script that is used on [Mathematica.StackExchange.com](http://mathematica.stackexchange.com) and on [Wolfram Community](http://community.wolfram.com).
* Preserves code indentation and prevents WordPress from inserting `<br>` and `<p>` into code.
* Replaces Wolfram Language character codes such as `\[Alpha]`, `\[Gamma]` etc. with their corresponding characters.

**Embed Wolfram technologies**

* Easily embed CDFs in any post or page.
* Retrieve and display an image from a Wolfram Cloud API.
* Retrieve and display raw data from a Wolfram Cloud API.
* Display a link to the documentation of a Wolfram Language function.

**Retrieve Mathematica.StackExchange data**

* Show a box with profile information
* Create links to questions and answers based on their IDs
* Make arbitrary requests to the Mathematica.StackExchange API

== Installation ==

1. Upload the folder `Mathematica-Toolbox` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

You should now be able to use any of the shortcodes. If you forget what they are you can use the buttons that should have appeared in boxes underneath the text editor to insert shortcode templates into the text. Either box can be hidden using the screen options pane. For more information, read the documentation:
[http://cekdahl.github.io/MathematicaToolbox](http://cekdahl.github.io/MathematicaToolbox)

== Frequently Asked Questions ==

= Where can I find more information about how to use this plugin? =

Detailed information about all features is in the documentation:
[http://cekdahl.github.io/MathematicaToolbox](http://cekdahl.github.io/MathematicaToolbox)

= Something is not working, what should I do? =

If the problem cannot be solved with the information in the documentation I will be happy to try to help you if you post a message under the support tab here in the plugin directory.

== Screenshots ==

1. CDFs can be included in WordPress posts, as well as data and images from Wolfram Cloud APIs.
2. There are two different ways to include highlighted code blocks, but they produce the same result. Character codes such as `\[Alpha]` get replaced automatically with their corresponding characters.
3. Handy shortcodes can render usernames and links to the online documentation.
4. Shortcodes that interface with the Stackexchange API can retrieve information about a user. `[mma_se_profile_box user_id="731"]` creates the profile box seen in the image.
5. Answers specified by IDs, retrieved from the Stackexchange API.
6. The top metabox is the "toolbox" which the plugin is named after. A click on one of those buttons inserts the corresponding shortcode into the editor. Below the toolbox there is an example of code in custom post fields, which can be included in the post using `[wlcode field="example"]` and `[wlcode field="glyphs"]` respectively.

== Changelog ==

**Version 1.0.4**

Release date: 2nd July, 2016

* Fixed a CDF embedding problem.
* Split the meta box into two.
* Syntax highlighting update for Mathematica version 10.3
* New documentation.

**Version 1.0.3**

Release date: 21st Mars, 2016

* Fixed a code highlighting problem.

**Version 1.0.2**

Release date: 13th September, 2015

* Fixed a problem with the `WolframCloudAPI` shortcode with custom parameters.

**Version 1.0.1**

Release date: 17th July, 2015

* Inline code shortcode. Used to get special markup for inline code.
* Fixed incorrect title attributes of some of the shortcode buttons.

== Upgrade Notice ==
