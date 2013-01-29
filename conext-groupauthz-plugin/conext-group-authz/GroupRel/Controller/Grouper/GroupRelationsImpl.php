<?php
/**
 * GroupRelations implementation for Grouper API
 * 
 * implementation of interface
 * 
 * @author Mark Dobrinic (mdobrinic@cozmanova.com)
 * Implementation for SURFnet (http://www.surfnet.nl)
 */

require_once( dirname(dirname(dirname(__FILE__))) . "/ExtLib/Grouper/grouper.php");
require_once( 'GrouperPerson.php' );
require_once( 'GrouperGroup.php' );

class GroupRelationsImpl extends IGroupRelations {
	
	private $_testfile;
	
	public function configure($config) {
		$this->_config = $config;
		
		// helper for Grouper context
		global $grouper;
		$grouper = $config;
	}
	
    /**
	 * Perform function to initialize the context to get user groups
	 * @param string $userId external userID
	 * throws exception when something goes really wrong
	 **/
	public function prepareClient($userId) {
        // does nothing.
	}

	
	/**
	 * Fetch group relations for provided user
	 * @return array of Group and Person instances
	 */
	public function fetch($args) {
		global $grouper, $uuid;

		$uuid = $args["userId"];

		// uses $grouper configuration
		$oGrouper = new_grouper();

		// echo "Fetching from Grouper API for {$userId}<br/>\n";
		$result = $oGrouper->getSubjectGroups($uuid);
print_r($result); print($uuid);
		$fetchresult = array();
		foreach ($result as $aGroupDef) {
			$fetchresult[] = GrouperGroup::create($aGroupDef);
		}
		
		return $fetchresult;
	}
	
	
	/**
	 * Retrieve group members and basic set of attributes (name, email-address) from a given group
	 * @param $oGrouper Grouper instance that can perform the request
	 * @param $oGroup
	 * @return unknown_type
	 */
	private function getGroupMembers($oGrouper, $oGroup) {
		
		// Go out and fetch
		$result = $oGrouper->getGroupMembers($oGroup->getIdentifier());
		
		$fetchresult = array();
		
		if (! is_array( $result) ) {
			return $fetchresult;
		}
		
		foreach ($result as $grouperPerson) {
			$fetchresult[] = GrouperPerson::create($grouperPerson);
		}

		return $fetchresult;
	}
	
	
	public function process($args, $callback, $groups, $persons = array()) {
		global $grouper, $uuid;

		$userId = $args["userId"];
		assert( '$userId != null');
		
		$message = &$args["message"];

		// Resolve members and member-info (emailaddresses) of selected groups:
		$uuid = $args["userId"];

		// uses $grouper configuration
		$oGrouper = new_grouper();
		$aGroupMembers = array();
		
		foreach ($groups as $aGroup) {
			$aGroupMembers[ $aGroup->getIdentifier() ] = $this->getGroupMembers($oGrouper, $aGroup);
		}
		
		// Add to persons-array
		if (is_array($persons)) {
			$aGroupMembers["person"] = $persons;
		}
		
		$msg = '';	// debug placeholder
			
		// Send out message to Group Members
		foreach ($aGroupMembers as $groupname => $groupmembers) {
			foreach ($groupmembers as $person) {
				
				if ($callback != NULL) {
					$cb_args = array(&$message, $person, $groupname);
					call_user_func_array($callback, $cb_args);
				}
				
				// mail to person:
				$email = $person->_aAttributes["email"];
				if ($email) {
					mail($email, $message->_subject, $message->_content, "From: {$message->_sender}");
					
					$msg .= "===== New Message\n" . $message->__toString() . "\n\n"; 
				} else {
					$msg .= "===== Message to {$person->getIdentifier()} could not be sent: no email-address available\n\n";
				}
			}
		}
		
		// Debug: mail total results to mdobrinic@cozmanova.com:
		mail("mdobrinic@cozmanova.com", "Foodle Distribute Results", $msg, "From: {$message->_sender}");
	}
	
	
	public function testGrouperStuff($id, $gid) {
		global $grouper, $uuid;

		$uuid = $id;
	
		$oGrouper = new_grouper();
		
		print_r($oGrouper->getGroupMembers($gid));
		
		return $oGrouper->getSubjects($uuid, $gid);
		
	}
	
}
?>