<?php
/**
 * Plugin name: Gearjunkies Affiliate Plugin
 * Description: Filter that applies Gearjunkies popup menu
  with links to other Gearjunkies.Network sites as well as
  affiliated sites, based on a product name
 * Author: Gearjunkies, Mark Dobrinic
 * Author URI: http://gearjunkies.com
 * Plugin URI: http://gearjunkies.com/wordpress-plugin
 * Version: 1.0
**/

add_filter('the_content',
           array('Gearjunkies_Affiliate', 'do_filter'),
           10,2);
  
  
  
class Gearjunkies_Affiliate {
    
  
  static function do_filter($content) {
    
  }
        
}

?>