<?php
/**
 * Plugin name: SURFconext Group Authorization
 * Description: Apply authorization based on membership of
 *   groups, as provided by an external, OpenSocial, group-
 *   provider
 * Author: Mark Dobrinic, for SURFnet
 * Author URI: http://www.surfnet.nl
 * Plugin URI: http://www.surfnet.nl
 * Version: 1.0
**/

require_once("GroupContext.setup.php");

/**
 * Initialize plugin-specific settings when admin initializes
 */
add_action( 'admin_init', array('Conext_Authorization', 'init_settings') );

/**
 * Add options page for our pugin
 */
add_action('admin_menu', array('Conext_Authorization', 'add_options_page') );

/**
 * Hook that allows to add link to our settings in the plugin list
 */
add_filter('plugin_action_links',array('Conext_Authorization','plugin_action_links') ,10,2);

/**
 * Authorize a newly logged in user by hooking into wp_login action
 * Priority default: 10,
 * Request both 2 arguments to be passed, as the second argument is the 
 *   initialized WP_User instance
 */
add_action('wp_login', array('Conext_Authorization', 'apply_authorization'), 10, 2 );

// add_options_page(page_title, menu_title, capability, handle, [function]);
class Conext_Authorization {

	const OPTION_GROUP = 'conext_authorization';
	
  static function init_settings() {
  	register_setting(self::OPTION_GROUP, 'admin_role');
  	register_setting(self::OPTION_GROUP, 'editor_role');
    register_setting(self::OPTION_GROUP, 'author_role');
    register_setting(self::OPTION_GROUP, 'contributor_role');
   	register_setting(self::OPTION_GROUP, 'subscriber_role');
  }
  
  static function add_options_page() {
  	if (function_exists('add_options_page')) {
  	  // Authorized for users with 'edit_plugins'-capability; 
  	  //   default for 'Administrator'-role and up
  	  add_options_page('SURFconext Group Authorization', 
  	  	'<img src="'.plugins_url(basename(dirname(__FILE__)) . '/img/SURFconext_klein.gif'). '" style="width:22px;height:11px;border:0;" alt="SURFconext" />Conext AuthZ', 
	  	  'edit_plugins', basename(__FILE__), 
  	      array(__CLASS__, 'options_page'));
  	}
  }

  static function plugin_action_links($links,$file){
		static $this_plugin;
		if(!$this_plugin){
			$this_plugin = plugin_basename(__FILE__);
		}
		if($file == $this_plugin){
			$settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=conext-group-authz.php">Settings</a>';
			array_unshift($links,$settings_link);
		}
		return $links;
}
  
  
  static function apply_authorization($username, WP_User $oWPUser) {
  	// print_r($username);
  	// print_r($oWPUser);
  	// Prepare our context
  	$user_id = username_exists($username);
  	
  	if (! $user_id) {
  		print('Could not establish user_id for username '.$username);
  		return null;
  	}
  	
    // 1: Retrieve groups from Conext for user with $username
    global $grouprel_config;
		$oGroupRel = IGroupRelations::create($grouprel_config['impl']);
		
		try {
			$r = $oGroupRel->fetch(array('userId' => $username));
		} catch(Exception $e) {
  		print('Could not fetch groups from group provider for username '.$username. "<br/>\n");
  		print($e->getMessage() . "<br/>\n");
  		print($e->getErrorMessage() . "<br/>\n");
  		// print_r($e);
			return null;
		}
	//print_r($r); exit();	
  	// 2: Try to map group to role - waterfall this:
  	$new_role = 'subscriber';	// default fallback role
  	if (self::is_group_contained(get_option('admin_role'), $r)) {
  		$new_role = 'administrator';
  	} else if (self::is_group_contained(get_option('editor_role'), $r)) {
  		$new_role = 'editor';
  	} else if (self::is_group_contained(get_option('author_role'), $r)) {
  		$new_role = 'author';
  	} else if (self::is_group_contained(get_option('contributor_role'), $r)) {
  		$new_role = 'contributor';
  	}
  	
  	//  Make change persistent? Yep, at least, when it was changed
  	// Making persistent means: replace existing role with new role

  	if ( ! in_array($new_role, $oWPUser->roles) ) {
  		$oWPUser->set_role($new_role);
  		wp_update_user(array('ID' => $user_id, 'role' => $new_role));
  	}
  	
  	// Be done:
  	// print('All the way through and done.');
  	return;
  }
  
  
  
  /**
   * Check whether a given groupname is present in all_grous
   * @param string $the_group
   * @param array of Group-instances $all_groups all the groups to check
   */
  static private function is_group_contained($the_group, $all_groups) {
  	if (is_array($all_groups)) {
	  	foreach ($all_groups as $o) {
	  		if ($o instanceof Group) {
	  			if ($o->getIdentifier() == $the_group) {
		  			return true;
	  			} 
	  		}
	  	}
  		return false;
  	}
  }
  
  /**
   * Store the given role with the given user 
   * 
   * @param int $user_id existing user_id 
   * @param string $role role that needs to be set for the user
   */
  static private function set_role($user_id, $role) {
  	wp_update_user(array("ID" => $user_id, "role" => $role));
  }
  
  
  /**
   * Render form to change this plugin's options
   */
  static function options_page() {
?>  	
			<div class="wrap">
				<h2>SURFconext Group Authorization options</h2>
				<p>This page lets you configure how the SURFconext Group Authorization will perform.</p>
				<div style="width:100%;">
					<div style="float:left;margin:0 330px 0 0;">
						<form action="options.php" method="post">
							<?php settings_fields(self::OPTION_GROUP); ?>
							<table class="form-table">
								<tbody>
								  <tr valign="top">
								    <th colspan="2"><h3>Group Identifier settings</h3></th>
								  </tr>
								  <tr>
								    <td colspan="2"><p>
								    The Group Identifier settings specify the groups that are used to assign a role to a user. Everytime a user logs in, its group memberships are
								    compared against the group identifiers that you can configure below.</p>
								    <p>Note that there is only one role applied; when the user is member of the group specified in Admin Group Identifier, it is assigned 'admin' role, 
								    then the Editor Group is tried, etc.
								    </p></td>
								  </tr>
									<tr valign="top">
										<th scope="row"><label for="admin_role">Admin Group Identifier</label></th>
										<td><input name="admin_role" type="text" id="admin_role" size="72" value="<?php echo get_option('admin_role'); ?>" class="code">
										<br /><span class="description">The Admin Group specifies the Group URN that grants the user the 'admin'-role</span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="editor_role">Editor Group Identifier</label></th>
										<td><input name="editor_role" type="text" id="editor_role" size="72" value="<?php echo get_option('editor_role'); ?>" class="code">
										<br /><span class="description">The Editor Group specifies the Group URN that grants the user the 'editor'-role</span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="author_role">Author Group Identifier</label></th>
										<td><input name="author_role" type="text" id="author_role" size="72" value="<?php echo get_option('author_role'); ?>" class="code">
										<br /><span class="description">The Author Group specifies the Group URN that grants the user the 'author'-role</span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="contributor_role">Contributor Group Identifier</label></th>
										<td><input name="contributor_role" type="text" id="contributor_role" size="72" value="<?php echo get_option('contributor_role'); ?>" class="code">
										<br /><span class="description">The Contributor Group specifies the Group URN that grants the user the 'contributor'-role</span>
										</td>
									</tr>
								</tbody>
							</table>
							<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes'); ?>"></p>
							<p>&nbsp;</p>
						</form>
					</div>
					<div style="float:right;width:300px;position:absolute;right:20px;">
						<table class="widefat">
							<thead>
								<tr>
									<th>SURFconext and OpenSocial</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><p>SURFconext is built using OpenSocial technology. Support OpenSocial! :)</p>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>  	
<?php   	
  }	// function options_page()
        
}

?>
