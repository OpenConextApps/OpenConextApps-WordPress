<?php 
/**
 * Factory class that instantiates a worker class to establish grouprelations of a user
 * Configure the handler like this
 *  					'handler' => array(
 *							'class' => 'OpenSocial_GroupRelationsImpl',		// implementation that uses OpenSocial API
 *							'implementation-specific' => 'more-config-options',	//
 *						)
 *
 * @author dopey (mdobrinic@cozmanova.com)
 * for SURFnet bv (www.surfnet.nl)
 *
 */

abstract class IGroupRelations {
	
	public static function create($config) {
		
		// Check requirements
		if (!array_key_exists("class", $config)) {
			throw new Exception('Invalid GroupRelations-configuration, missing "class"-config key in : ' . var_export($config, TRUE));
		}
		
		// Create class
		$className = $config["class"];
		require_once( str_replace('_', '/', $className) . '.php' );
		$class = substr($className, strrpos($className, "_")+1); 
		$o = new $class;
		
		// Configure created class
		$o->configure($config);
		
		return $o;
	}
	
	
	/**
	 * Configure local context from configuration
	 * @return nothing.
	 */
	public abstract function configure($config);
	
	
	/**
	 * Perform function to initialize the context to get user groups
	 * @param string $userId external userID
	 * throws exception when something goes really wrong
	 **/
	public abstract function prepareClient($userId);
    
    
	/**
	 * Fetch group relations for provided user
	 * @return array of cGroups and cPersons
	 */
	public abstract function fetch($args);
	
	
	/**
	 * Perform action on a provided set of groups or persons
	 * @param $arguments array of key=>value parameters
	 * @param $callback function reference ($function) or object method reference (array($object,$method)) that 
	 *    is called with (&($arguments['$message']), Person:$person, string:$groupname) arguments
	 * @param $groups array of Group-instances to distribute message to (or to its individual members)
	 * @param $persons array of Person-onstances to distribute message to
	 * @return nothing
	 */
	public abstract function process($arguments, $callback, $groups, $persons = array());
	
	
}

?>