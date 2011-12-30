=== DKOATED CTA Buttons ===
Contributors: DKOATED, David Klein
Donate link: http://DKOATED.com/donate
Tags: call to action, call to action button, download, download button, buy, buy button, register, register button, button, buttons, shortcode, shortcodes, css, css button, cta, cta button, hyperlink, link, link button, post, posts, page, pages
Requires at least: 2.9.2
Tested up to: 3.3
Stable tag: 1.3.3

Add beautiful and SEO-ready call to action buttons through shortcodes to your WordPress. No external resources, no javascript, no images!

== Description ==
Add beautiful and SEO ready call to action buttons through shortcodes to your WordPress. Simple usage, no external resources, no javascript and no images necessary! Just pure CSS!

* Ten pre-defined colors to choose from: Black, White, Grey, Red, Blue, Green, Yellow, Pink, Brown and Orange.
* Use unlimited colors with the "color" attribute.
* Unlimited custom colors through the admin panel. Specify your own button color with the attribute "custom" in the admin panel.
* Headline and optional sub-headline.
* No javascript, no external libraries needed (such as jQuery, Modernizr, etc.).
* No images needed, just pure CSS.
* Search engine optimized buttons: Fully crawlable, W3C valid code, Nofollow or Follow links and link-titles.
* Dynamically sets height and width according to your theme's link font and base size.
* Possibility to manually override the width of the button.
* Open your links in a new window (or tab) or have them open links in the same browser window.
* Simple usage, but with optional advanced settings.
* Admin panel to set your own custom fallbacks. No need to specify all your buttons with the same attributes anymore!
* Add the shortcode in posts, pages and widgets (through the text-widget)

= Simple Usage: =
<code>&#91;DKB url="http://dkoated.com/" text="Your headline here" type="large|normal|small|extrasmall" color="black|white|grey|red|green|blue|orange|yellow|pink|brown|#000000|#ff0066|..."&#93;</code>

= Advanced Usage: =
<code>&#91;DKB url="http://dkoated.com/" text="Main Button Text" desc="Sub-headline of button" title="Link SEO title" type="large|normal|small|extrasmall" color="black|white|grey|red|green|blue|orange|yellow|pink|brown|#000000|#ff0066|..." width="100" opennewwindow="yes|no" nofollow="yes|no" custom="yes|no"&#93;</code>

= Settings help: =
* <strong>url=""</strong>: Requires a full link, including <em>http://</em> or <em>https://</em>
* <strong>text=""</strong>: Displays as main text of the button
* <strong>desc=""</strong>: If set, displays as the sub-headline of the button
* <strong>title=""</strong>: If set, text displays as the hover popup and allows to include more keywords for search engine optimization
* <strong>type=""</strong>: Renders the button in different sizes. Choose from either "large", "normal", "small" or "extrasmall".
* <strong>color=""</strong>: Renders the button in different colors. Choose from either "black", "white", "grey", "red, "blue", "green", "yellow", "pink", "brown" or "orange" or use your own hex-colors, such as "#ff0066" or "#000000".
* <strong>width=""</strong>: If set, renders the button with a specific width
* <strong>opennewwindow=""</strong>: Forces the link to open in a new window or in the same window. Choose from either "yes" or "no"
* <strong>nofollow=""</strong>: Forces search engines to either follow the link or to ignore the link, thus not visiting the links URL
* <strong>custom=""</strong>: Forces the button to be rendered in the colors specified in the admin panel

== Installation ==
You can either use the WordPress built-in installer and upgrader or you can install the plugin manually.

= Automatic Installation: =
* Search for 'DKOATED CTA Buttons' through the built-in Plugin menu in WordPress
* Click on 'Install', then click on 'Activate' (or 'Activate plugin')

= Manual Installation: =
* Download the plugin and extract the Zip archive locally
* Upload the extracted folder 'dkoated-cta-buttons' to your '/wp-content/plugins/' directory
* Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==
Visit [DKOATED CTA Buttons WordPress Plugin Page](http://DKOATED.com/dkoated-cta-buttons-wordpress-plugin/) for screenshots and working demos.

== Changelog ==
= 1.3.3 =
* Place the shortcode everywhere you want, including in your text-widgets!

= 1.3.2 =
* Fixed another bug with the "color" attribute, where specified colors were not showing

= 1.3.1 =
* Fixed bug with the "color" attribute, where specified colors were not showing
* Fixed bug with the "color" and "custom" attribute, where specified overwrote each other

= 1.3.0 =
* Ability to use hex-colors (such as '#ff0066') through the attribute "color" within the shortcode
* Fixed bug with the "custom" attribute, where custom colors were not overriding the attribute "color"
* Additional failsafe checks for attributes width, colors, custom colors and hex-values within the color attribute

= 1.2.2 =
* Admin panel works again

= 1.2.1 =
* Fixed naming error

= 1.2.0 =
* New feature: Admin panel to set your own custom fallbacks (overriding default fallbacks for unspecified attributes)
* New feature: Specify custom button colors in the admin panel and use them with the new attribute "custom" within the shortcode
* Custom fallbacks always overrides default fallbacks

= 1.1.1 =
* Minified some more sourcecode for performance optimizations
* Minified CSS for max. compatibility and performance optimization
* New readme.txt with advanced attributes help
* If "width" is specified, the plugin now checks if the value is properly inserted (only numeric without 'px')

= 1.1.0 =
* New fallback for attribute "url": If left empty or unspecified "url" now defaults to your WordPress homepage URL
* New fallback for attribute "text": If left empty or unspecified "text" now defaults to the URL of the link
* New feature: Specify a fixed button width in pixels through the attribute "width"
* New feature: Add your own link-title (good for SEO). If left empty or unspecified, the "title"-attribute defaults to the "text"-attribute
* Fixed some line-height issues with buttons that make use of sub-headlines

= 1.0.0 =
* Initial Release
* WordPress 3.3 ready

== Frequently Asked Questions ==
= How do I use the plugin? =
Easy: Just add the following code to either a post or page: <code>&#91;DKB url="http://dkoated.com/" text="Main Button Text" desc="Sub-headline of button" title="Link SEO title" type="large|normal|small|extrasmall" color="black|white|grey|red|green|blue|orange|yellow|pink|brown" width="100" opennewwindow="yes|no" nofollow="yes|no"&#93;</code>. As you may see in the code, there are many options to choose from. Just remove the ones you don't want. All options you choose not to use, will default to their default settings.

= But do I need to add that WHOLE thing? =
No. Actually there are no required attribute fields for the shortcode. Theoretically, the minimum code you need to use is: <code>&#91;DKB&#93;</code>, but that wouldn't make a lot of sense would it? I highly recommend to at least use the "url" and "text"-attributes: <code>&#91;DKB url="http://dkoated.com/" text="Your headline here"&#93;</code>. Everything else, such as the attribute fields "title", "type", "color", "width", "opennewwindow" and "nofollow" are optional and if unspecified, these attribute fields will default to their default setting.

= What are the default settings? =
* "url" defaults to your WordPress homepage URL
* "text" defaults to the "url" attribute
* "desc" defaults to nothing, so a button without sub-headline will be rendered
* "type" defaults to "normal"
* "color" defaults to "black"
* "opennewwindow" defaults to "no" (opens in the same browser window)
* "nofollow" defaults to "no", meaning the link will be followed by search engines
* "custom" defaults to "no", meaning no custom colors will be used to render the button

= Can I use just some attribute fields? =
Yes. If you want to let's say only specify the "width" and "nofollow" attributes, you would use this code: <code>&#91;DKB url="http://dkoated.com/" text="Your headline here" width="200" nofollow="yes"&#93;</code>. All other unspecified attributes will default to their default settings.

= The buttons look all messed up! What's wrong? =
If you are using a webkit-based browser such as Safari or Chrome and you have the plugin WP-Minify installed, you have to explicitly exclude the file 'dkoated-cta-buttons.css' from minification within the WP-Minify options. The css file itself is already optimized and would not benefit from a minification anyway (perhaps by only a few bytes).

= What's up with the "size" attribute? =
Specify the exact width of the button in pixels. Just add a number (without 'px') and watch the button grow (or get smaller). Be cautious though, the specified size will, no matter what, be set and displayed. If you specify a longer text to be displayed within the button through either "text" or "desc", the text will be cut off if the width of the button is smaller.

= How can I specify my custom button color in the admin panel? =
Easy. Add your hex-color including the '#'-sign in the admin panel and use the following shortcode <code>&#91;DKB url="http://dkoated.com/" text="Your headline here" <strong>custom="yes"</strong>&#93;</code>. Please note, that the use of the attribute "custom" always takes preference before the attribute "color".

= I am having massive problems here. Please help! =
Head over to [DKOATED CTA Buttons WordPress Plugin Page](http://DKOATED.com/dkoated-cta-buttons-wordpress-plugin/) and leave some feedback. I'll try to help out as much as I can, alright? ;)

== Upgrade Notice ==
Just upgrade your plugin.