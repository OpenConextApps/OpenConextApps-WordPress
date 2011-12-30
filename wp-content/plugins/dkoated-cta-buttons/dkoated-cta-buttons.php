<?php
/**
 * Plugin name: DKOATED CTA Buttons
 * Description: Add beautiful and SEO ready call to action buttons through shortcodes to your WordPress. Simple usage, no external resources, no javascript and no images necessary! Just pure CSS!
 * Author: DKOATED, David Klein
 * Author URI: http://DKOATED.com
 * Plugin URI: http://DKOATED.com/dkoated-cta-buttons-wordpress-plugin/
 * Version: 1.3.3
 */

add_action('admin_init','dkb_settings_init' );
function dkb_settings_init(){
	register_setting('dkb_settings_options','fallback_url');
	register_setting('dkb_settings_options','fallback_text');
	register_setting('dkb_settings_options','fallback_desc');
	register_setting('dkb_settings_options','fallback_title');
	register_setting('dkb_settings_options','fallback_type');
	register_setting('dkb_settings_options','fallback_color');
	register_setting('dkb_settings_options','fallback_width');
	register_setting('dkb_settings_options','fallback_opennewwindow');
	register_setting('dkb_settings_options','fallback_nofollow');
	register_setting('dkb_settings_options','fallback_customvi');
	register_setting('dkb_settings_options','fallback_customho');
}
add_filter('plugin_action_links','dkoated_cta_buttons_plugin_action_links',10,2);
function dkoated_cta_buttons_plugin_action_links($links,$file){
	static $this_plugin;
	if(!$this_plugin){
		$this_plugin = plugin_basename(__FILE__);
	}
	if($file == $this_plugin){
		$settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=/dkoated-cta-buttons/dkoated-cta-buttons.php">Settings</a>';
		array_unshift($links,$settings_link);
	}
	return $links;
}

if(!class_exists("dkoated_cta_buttons_plugin_adminmenu")){
	class dkoated_cta_buttons_plugin_adminmenu{
		function dkoated_cta_buttons_plugin_adminmenu(){
			add_action('admin_menu',array(&$this,'add_dkoated_cta_buttons_menu'));
		}
		function add_dkoated_cta_buttons_menu(){
			if(function_exists('add_menu_page')){
				add_options_page('CTA Buttons','<img src="'.plugins_url(basename(dirname(__FILE__)) . '/img/icon.png'). '" style="width:11px;height:9px;border:0;" alt="DKOATED CTA Buttons" />CTA Buttons','manage_options',__FILE__,array($this,'dkoated_cta_buttons_menu_page'));
			}
		}
		function dkoated_cta_buttons_menu_page(){
			?>
			<div class="wrap">
				<div style="background:url('<?php echo plugins_url(basename(dirname(__FILE__)) . '/img/icon32.png'); ?>') no-repeat;float:left;height:34px;margin:7px 0 0 0;width:36px;"><a href="http://dkoated.com/" target="_blank" title="DKOATED" style="height:34px;width:36px;display:block;"></a></div>
				<h2>DKOATED CTA Buttons</h2>
				<p>Welcome to the DKOATED CTA Buttons plugin. This plugin enables you to add beautiful and SEO ready call to action buttons through shortcodes to your WordPress. Simple usage, no external resources, no javascript and no images necessary! Just pure CSS!</p>
				<div style="width:100%;">
					<div style="float:left;margin:0 330px 0 0;">
						<form action="options.php" method="post">
							<?php settings_fields('dkb_settings_options'); ?>
							<h3>Default Fallback Settings</h3>
							<p>The default fallback settings listed below determine the default fallback to use when the corresponding attribute is unspecified with the shortcode.</p>
							<p>To get started just add one of the following codes to any post or page and fill in the attributed with your information.</p>
							<p>Standard Button (without Sub-Headline):<br />
							<code>[DKB url="" text="" title="" type="" color="" width="" opennewwindow="" nofollow=""]</code></p>
							<p>Standard Button (with Sub-Headline):<br />
							<code>[DKB url="" text="" desc="" title="" type="" color="" width="" opennewwindow="" nofollow=""]</code></p>
							<p>Standard Button (with custom colors):<br />
							<code>[DKB url="" text="" title="" type="" width="" opennewwindow="" nofollow="" custom="yes"]</code></p>
							<table class="form-table">
								<tbody>
									<tr valign="top">
										<th scope="row"><label for="fallback_url">URL</label></th>
										<td><input name="fallback_url" type="text" id="fallback_url" value="<?php echo get_option('fallback_url'); ?>" class="regular-text code">
										<br /><span class="description">The URL attribute is the link of the button. If unspecified, the attribute defaults to your homepage URL.<br />Default fallback: <code><?php echo get_bloginfo('wpurl') ?></code><br />Manual usage: <code>[DKB <strong>url="<?php echo get_bloginfo('wpurl') ?>"</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_text">Text</label></th>
										<td><input name="fallback_text" type="text" id="fallback_text" value="<?php echo get_option('fallback_text'); ?>" class="regular-text code">
										<br /><span class="description">The Text attribute is the text of the button. If unspecified, the attribute defaults to whatever you chose in the URL attribute.<br />Default fallback: <code>empty</code><br />Manual usage: <code>[DKB ... <strong>text="Your button text"</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_desc">Desc</label></th>
										<td><input name="fallback_desc" type="text" id="fallback_desc" value="<?php echo get_option('fallback_desc'); ?>" class="regular-text code">
										<br /><span class="description">The Desc attribute is the text of the button's sub-headline. If unspecified, the attribute defaults nothing, thus a button with no sub-headline will be generated.<br />Default fallback: <code>empty</code><br />Manual usage: <code>[DKB ... <strong>desc="Your sub-headline"</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_title">Title</label></th>
										<td><input name="fallback_title" type="text" id="fallback_title" value="<?php echo get_option('fallback_title'); ?>" class="regular-text code">
										<br /><span class="description">The Title attribute is the link-title of the button's link. If unspecified, the attribute defaults to whatever you chose in the URL attribute.<br />Default fallback: <code>empty</code><br />Manual usage: <code>[DKB ... <strong>title="Your SEO link title"</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_type">Type</label></th>
										<td><input name="fallback_type" type="text" id="fallback_type" value="<?php echo get_option('fallback_type'); ?>" class="regular-text code">
										<br /><span class="description">The Type attribute is the size of the button. If unspecified, the attribute defaults to its standard normal size.<br />Default fallback: <code>empty</code><br />Manual usage: <code>[DKB ... <strong>type="large|normal|small|extrasmall"</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_color">Color</label></th>
										<td><input name="fallback_color" type="text" id="fallback_color" value="<?php echo get_option('fallback_color'); ?>" class="regular-text code">
										<br /><span class="description">The Color attribute is the color of the button. If unspecified, the attribute defaults to the black color.<br />Default fallback: <code>empty</code><br />Manual usage: <code>[DKB ... <strong>color="black|white|grey|red|green|blue|orange|yellow|pink|brown|#000000|#ff0066|..."</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_width">Width</label></th>
										<td><input name="fallback_width" type="text" id="fallback_width" value="<?php echo get_option('fallback_width'); ?>" class="regular-text code">
										<br /><span class="description">The Width attribute is the width of the button. If unspecified, the attribute defaults to automatic and adapts to either the button text or the sub-headline's text (whichever is longer).<br />Default fallback: <code>empty</code><br />Manual usage: <code>[DKB ... <strong>width="your size in pixel without <em>px</em>"</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_opennewwindow">Opennewwindow</label></th>
										<td><input name="fallback_opennewwindow" type="text" id="fallback_opennewwindow" value="<?php echo get_option('fallback_opennewwindow'); ?>" class="regular-text code">
										<br /><span class="description">The Opennewwindow attribute forces the link to either open in a new window or open the link in the same window. If unspecified, the attribute defaults to yes.<br />Default fallback: <code>empty</code><br />Manual usage: <code>[DKB ... <strong>opennewwindow="yes|no"</strong>]</code></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_nofollow">Nofollow</label></th>
										<td><input name="fallback_nofollow" type="text" id="fallback_nofollow" value="<?php echo get_option('fallback_nofollow'); ?>" class="regular-text code">
										<br /><span class="description">The Nofollow attribute forces search engines to either follow or not follow the link for indexation. If unspecified, the attribute defaults to yes (search engine bots will not follow the link).<br />Default fallback: <code>empty</code><br />Manual usage: <code>[DKB ... <strong>nofollow="yes|no"</strong>]</code></span>
										</td>
									</tr>
								</tbody>
							</table>
							<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes'); ?>"></p>
							<p>&nbsp;</p>
							<h3>Custom Color Settings</h3>
							<p>The custom color settings listed below determine the normal, visited and hover colors to use for the buttons if the attribute custom is set to yes with the shortcode. The code to activate the custom colors is <code>[DKB ... <strong>custom="yes"</strong>]</code></p>
							<table class="form-table">
								<tbody>
									<tr valign="top">
										<th scope="row"><label for="fallback_customvi">Custom Color: Button Color</label></th>
										<td><input name="fallback_customvi" type="text" id="fallback_customvi" value="<?php echo get_option('fallback_customvi'); ?>" class="regular-text code">
										<br /><span class="description">The Custom Color is the default color of the button (unhovered) and is required to be set if the custom attribute is set to "yes". It's a standard hex color and requires the '#' sign in front of the 6 digit hex color.<br />Default fallback: <code>empty</code><br />Manual usage: Color needs to be specified here. For example: <code><strong>#ff0066</strong></code></span></td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="fallback_customho">Custom Color: Button Hover Color</label></th>
										<td><input name="fallback_customho" type="text" id="fallback_customho" value="<?php echo get_option('fallback_customho'); ?>" class="regular-text code">
										<br /><span class="description">The Custom Hover Color is the default color of the button when hovered and is required to be set if the custom attribute is set to "yes". It's a standard hex color and requires the '#' sign in front of the 6 digit hex color.<br />Default fallback: <code>empty</code><br />Manual usage: Color needs to be specified here.  For example: <code><strong>#ff0066</strong></code></span></td>
									</tr>
								</tbody>
							</table>
							<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes'); ?>"></p>
						</form>
					</div>
					<div style="float:right;width:300px;position:absolute;right:20px;">
						<table class="widefat">
							<thead>
								<tr>
									<th>Help Spread the Word!</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><p><strong>Want to help make this plugin even more awesome?</strong> All donations are used to improve this plugin, so donate what you can and are willing to spend. Every penny counts and is highly appreciated!</p>
									<p><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
									<input type="hidden" name="cmd" value="_s-xclick">
									<input type="hidden" name="hosted_button_id" value="UR3YE88FGAU88">
									<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="Thank you for your donation!!!">
									<img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1"><br /></form></p>
									<p><a href="http://wordpress.org/extend/plugins/dkoated-cta-buttons/" title="Rate Plugin 5 Stars on WordPress.org" rel="nofollow" target="_blank">Please rate the plugin 5 Stars on WordPress.org</a></p></td>
								</tr>
							</tbody>
						</table>
						&nbsp;
						<table class="widefat">
							<thead>
								<tr>
									<th>Need support?</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><p>If you are having problems with this plugin, please let me know about it on the <a href="http://dkoated.com/dkoated-cta-buttons-wordpress-plugin/" target="_blank" title="DKOATED.com Plugin Page">plugin page on DKOATED.com</a>.</p></td>
								</tr>
							</tbody>
						</table>
						&nbsp;
						<table class="widefat">
							<thead>
								<tr>
									<th>Latest news from DKOATED.com</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><p><a href="http://dkoated.com/" target="_blank" title="DKOATED.com">DKOATED.com</a><br /><a href="http://www.facebook.com/DKOATED" target="_blank" title="DKOATED on Facebook">DKOATED on Facebook</a><br /><a href="http://twitter.com/DKOATED" target="_blank" title="DKOATED.com">DKOATED on Twitter</a><br /><a href="https://plus.google.com/u/0/b/116249477495808918165/" target="_blank" title="DKOATED.com">DKOATED on Google+</a><br /><a href="http://dkoated.com/feed/" target="_blank" title="DKOATED.com">DKOATED RSS Feed</a></p></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<p><small><a href="http://wordpress.org/extend/plugins/dkoated-cta-buttons/" target="_blank">DKOATED CTA Buttons</a> plugin brought to you by <a href="https://plus.google.com/u/0/103198314695328331300" target="_blank">David Klein</a> from <a href="http://dkoated.com" target="_blank">DKOATED.com</a> | <a href="http://dkoated.com/donate/" target="_blank">Donate me coffee &hearts;</a>.</small></p>
			</div>
			<?php 
		}
	}
}
$wpdpd = new dkoated_cta_buttons_plugin_adminmenu();

if(!is_admin()){
	define('DKOATED_CTA_BUTTONS_VERSION','1.3.3');
	$css_url = plugins_url(basename(dirname(__FILE__)) . '/css/dkoated-cta-buttons.css');
	wp_register_style('dkoated-cta-buttons',$css_url,array(),DKOATED_CTA_BUTTONS_VERSION,'screen');
	wp_enqueue_style('dkoated-cta-buttons');

	/* @param $atts */
	/* These are the attributes */
	function sc_DKOATEDCTABUTTONS($atts){
		extract(shortcode_atts(array(
			"url" => '',
			"text" => '',
			"desc" => '',
			"title" => '',
			"type" => '',
			"color" => '',
			"width" => '',
			"opennewwindow" => '',
			"nofollow" => '',
			"custom" => ''
		), $atts));

		if($url == '' && get_option('fallback_url') == ''){$url = get_bloginfo('url');}
		if($url == '' && get_option('fallback_url') != ''){$url = get_option('fallback_url');}
		if($url != '' && get_option('fallback_url') == ''){$url = $url;}
		if($url != '' && get_option('fallback_url') != ''){$url = $url;}

		if($text == '' && get_option('fallback_text') == ''){$text = get_bloginfo('url');}
		if($text == '' && get_option('fallback_text') != ''){$text = get_option('fallback_text');}
		if($text != '' && get_option('fallback_text') == ''){$text = $text;}
		if($text != '' && get_option('fallback_text') != ''){$text = $text;}

		if($desc == '' && get_option('fallback_desc') == ''){$desc = '';}
		if($desc == '' && get_option('fallback_desc') != ''){$desc = '<span><br /><em>' . get_option('fallback_desc') . '</em></span>';}
		if($desc != '' && get_option('fallback_desc') == ''){$desc = '<span><br /><em>' . $desc . '</em></span>';}
		if($desc != '' && get_option('fallback_desc') != ''){$desc = '<span><br /><em>' . $desc . '</em></span>';}

		if($title == '' && get_option('fallback_title') == ''){$title = $text;}
		if($title == '' && get_option('fallback_title') != ''){$title = get_option('fallback_title');}
		if($title != '' && get_option('fallback_title') == ''){$title = $title;}
		if($title != '' && get_option('fallback_title') != ''){$title = $title;}

		if($type == '' && get_option('fallback_type') == ''){$type = 'normal';}
		if($type == '' && get_option('fallback_type') != ''){$type = get_option('fallback_type');}
		if($type != '' && get_option('fallback_type') == ''){$type = $type;}
		if($type != '' && get_option('fallback_type') != ''){$type = $type;}

		if($color == '' && get_option('fallback_color') == '' && $custom == ''){$color = 'black';}
		if($color == '' && get_option('fallback_color') == '' && $custom == 'no'){$color = 'black';}
		if($color == '' && get_option('fallback_color') == '' && $custom == 'yes'){$color = 'custom';}
		if($color == '' && get_option('fallback_color') != '' && $custom == ''){$color = get_option('fallback_color');}
		if($color == '' && get_option('fallback_color') != '' && $custom == 'no'){$color = get_option('fallback_color');}
		if($color == '' && get_option('fallback_color') != '' && $custom == 'yes'){$color = 'custom';}
		if($color == 'black' && get_option('fallback_color') != '' && $custom == ''){$color = 'black';}
		if($color == 'black' && get_option('fallback_color') == '' && $custom == ''){$color = 'black';}
		if($color == 'grey' && get_option('fallback_color') != '' && $custom == ''){$color = 'grey';}
		if($color == 'grey' && get_option('fallback_color') == '' && $custom == ''){$color = 'grey';}
		if($color == 'white' && get_option('fallback_color') != '' && $custom == ''){$color = 'white';}
		if($color == 'white' && get_option('fallback_color') == '' && $custom == ''){$color = 'white';}
		if($color == 'red' && get_option('fallback_color') != '' && $custom == ''){$color = 'red';}
		if($color == 'red' && get_option('fallback_color') == '' && $custom == ''){$color = 'red';}
		if($color == 'green' && get_option('fallback_color') != '' && $custom == ''){$color = 'green';}
		if($color == 'green' && get_option('fallback_color') == '' && $custom == ''){$color = 'green';}
		if($color == 'blue' && get_option('fallback_color') != '' && $custom == ''){$color = 'blue';}
		if($color == 'blue' && get_option('fallback_color') == '' && $custom == ''){$color = 'blue';}
		if($color == 'pink' && get_option('fallback_color') != '' && $custom == ''){$color = 'pink';}
		if($color == 'pink' && get_option('fallback_color') == '' && $custom == ''){$color = 'pink';}
		if($color == 'orange' && get_option('fallback_color') != '' && $custom == ''){$color = 'orange';}
		if($color == 'orange' && get_option('fallback_color') == '' && $custom == ''){$color = 'orange';}
		if($color == 'yellow' && get_option('fallback_color') != '' && $custom == ''){$color = 'yellow';}
		if($color == 'yellow' && get_option('fallback_color') == '' && $custom == ''){$color = 'yellow';}
		if($color == 'brown' && get_option('fallback_color') != '' && $custom == ''){$color = 'brown';}
		if($color == 'brown' && get_option('fallback_color') == '' && $custom == ''){$color = 'brown';}

		if($width == '' && get_option('fallback_width') == ''){$width = '';}
		if($width == '' && get_option('fallback_width') != '' && is_numeric(get_option('fallback_width'))){$width = 'style="width:' . get_option('fallback_width') . 'px !important;max-width:' . get_option('fallback_width') . 'px !important;"';}
		if($width != '' && is_numeric($width)){$width = 'style="width:' . $width . 'px !important;max-width:' . $width . 'px !important;"';}

		if($opennewwindow == '' && get_option('fallback_opennewwindow') == ''){$opennewwindow = ' target="_blank"';}
		if($opennewwindow == '' && get_option('fallback_opennewwindow') == 'yes'){$opennewwindow = ' target="_blank"';}
		if($opennewwindow == '' && get_option('fallback_opennewwindow') == 'no'){$opennewwindow = '';}
		if($opennewwindow == 'yes' && get_option('fallback_opennewwindow') == ''){$opennewwindow = ' target="_blank"';}
		if($opennewwindow == 'yes' && get_option('fallback_opennewwindow') == 'yes'){$opennewwindow = ' target="_blank"';}
		if($opennewwindow == 'yes' && get_option('fallback_opennewwindow') == 'no'){$opennewwindow = ' target="_blank"';}
		if($opennewwindow == 'no' && get_option('fallback_opennewwindow') == ''){$opennewwindow = '';}
		if($opennewwindow == 'no' && get_option('fallback_opennewwindow') == 'yes'){$opennewwindow = '';}
		if($opennewwindow == 'no' && get_option('fallback_opennewwindow') == 'no'){$opennewwindow = '';}

		if($nofollow == '' && get_option('fallback_nofollow') == ''){$nofollow = ' rel="nofollow"';}
		if($nofollow == '' && get_option('fallback_nofollow') == 'yes'){$nofollow = ' rel="nofollow"';}
		if($nofollow == '' && get_option('fallback_nofollow') == 'no'){$nofollow = '';}
		if($nofollow == 'yes' && get_option('fallback_nofollow') == ''){$nofollow = ' rel="nofollow"';}
		if($nofollow == 'yes' && get_option('fallback_nofollow') == 'yes'){$nofollow = ' rel="nofollow"';}
		if($nofollow == 'yes' && get_option('fallback_nofollow') == 'no'){$nofollow = ' rel="nofollow"';}
		if($nofollow == 'no' && get_option('fallback_nofollow') == ''){$nofollow = '';}
		if($nofollow == 'no' && get_option('fallback_nofollow') == 'yes'){$nofollow = '';}
		if($nofollow == 'no' && get_option('fallback_nofollow') == 'no'){$nofollow = '';}

		if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') != '' && $custom == ''){$custom = '<style>.hex' . substr($color,1) . '.dkoatedbutton,.hex' . substr($color,1) . '.dkoatedbutton:visited{background-color:' . $color . ' !important;}.hex' . substr($color,1) . '.dkoatedbutton:hover{background-color:' . $color . ' !important;}</style>';$color = 'hex' . substr($color,1);}
		if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') == '' && $custom == ''){$custom = '<style>.hex' . substr($color,1) . '.dkoatedbutton,.hex' . substr($color,1) . '.dkoatedbutton:visited{background-color:' . $color . ' !important;}.hex' . substr($color,1) . '.dkoatedbutton:hover{background-color:' . $color . ' !important;}</style>';$color = 'hex' . substr($color,1);}
		if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') != '' && $custom == 'no'){$custom = '<style>.hex' . substr($color,1) . '.dkoatedbutton,.hex' . substr($color,1) . '.dkoatedbutton:visited{background-color:' . $color . ' !important;}.hex' . substr($color,1) . '.dkoatedbutton:hover{background-color:' . $color . ' !important;}</style>';$color = 'hex' . substr($color,1);}
		if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') == '' && $custom == 'no'){$custom = '<style>.hex' . substr($color,1) . '.dkoatedbutton,.hex' . substr($color,1) . '.dkoatedbutton:visited{background-color:' . $color . ' !important;}.hex' . substr($color,1) . '.dkoatedbutton:hover{background-color:' . $color . ' !important;}</style>';$color = 'hex' . substr($color,1);}
		if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') != '' && $custom == 'yes'){$custom = '<style>.custom.dkoatedbutton,.custom.dkoatedbutton:visited{background-color:' . get_option('fallback_customvi') . ' !important;}.custom.dkoatedbutton:hover{background-color:' . get_option('fallback_customho') . ' !important;}</style>';}
		if(preg_match('/^#[a-f0-9]{6}$/i',$color) && get_option('fallback_color') == '' && $custom == 'yes'){$custom = '<style>.custom.dkoatedbutton,.custom.dkoatedbutton:visited{background-color:' . get_option('fallback_customvi') . ' !important;}.custom.dkoatedbutton:hover{background-color:' . get_option('fallback_customho') . ' !important;}</style>';}
		/* if($custom == ''){$custom = '';}
		if($custom != '' && $custom != 'yes'){$custom = '';}
		if($custom == 'no'){$custom = '';} */
		if($custom == 'yes'){$custom = '<style>.custom.dkoatedbutton,.custom.dkoatedbutton:visited{background-color:' . get_option('fallback_customvi') . ' !important;}.custom.dkoatedbutton:hover{background-color:' . get_option('fallback_customho') . ' !important;}</style>';}
		/* @var string */
		/* This is the output */
		$var_sHTML = '';
		$var_sHTML .= '' . $custom . '<a class="' . $type . ' ' . $color . ' dkoatedbutton" ' . $width . ' href="' . $url . '" title="' . $title . '" ' . $opennewwindow . ' ' . $nofollow .'>' . $text . $desc . '</a>';
		return $var_sHTML;
	}
	/* Add Shortcode to WordPress */
	add_shortcode('DKB','sc_DKOATEDCTABUTTONS');
	add_filter('widget_text','shortcode_unautop');
	add_filter('widget_text','do_shortcode');
}
?>