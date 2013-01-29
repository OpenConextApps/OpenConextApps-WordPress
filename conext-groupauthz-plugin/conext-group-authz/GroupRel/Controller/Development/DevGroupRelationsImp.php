<?php
/**
 * development implementation, works with static results
 */

    
class DevGroupRelationsImp extends IGroupRelations {
    
    public $_numGroups;     // define the number of groups to return
    public $_numMembers;    // define the number of members per group to return
    public $_memberEmail;   // define the email address to return for members

    /**
     * placeholder to pass on configuration
     **/
	public function configure($config) {
        $this->_numGroups = 3;
        $this->_numMembers = 4;
        $this->_memberEmail = 'test@email-address.com';
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
	 * Fetch group relations for provided user<br/>
	 * <br/>
	 * $args is an array with at least "userId" => -ExternalUserID-
	 * @return array of Group and Person instances
	 */
	public function fetch($args) {
		$userId = $args["userId"];
		
		$fetchresult = array();

        $i=0; while ($i < $this->_numGroups) {
            $o = new Group("group:identifier:num$i");
            
            $o->_aAttributes["title"] = "Group $i Title";
            $o->_aAttributes["description"] = "Description of group $i";
            
            $fetchresult[] = $o;
            
            $i++;
        }
		
		return $fetchresult;
	}

    
    protected function getGroupMembers( $userId, $aGroup ) {
        $groupId = $aGroup->getIdentifier();
        
        $members = array();
        
        $i=0; while ($i < $this->_numMembers) {
            $o = new Person("Member $i $groupId");
            $o->_aAttributes["name"] = "First $i Name";
            $o->_aAttributes["email"] = $this->_memberEmail;
            $members[] = $o;
        }   // while()
        
        return $members;
        
    }   // getGroupMembers()
    
    
    public function process($args, $callback, $groups, $persons = array()) {
		$userId = $args["userId"];	// require this for authorizing OpenSocial-calls
		assert( '$userId != null');
		
		$message = &$args["message"];
		
		// Resolve members ad member-info (emailaddresses) of selected groups:
		$aGroupMembers = array();
		
		foreach ($groups as $aGroup) {
			$aGroupMembers[ $aGroup->getIdentifier() ] = $this->getGroupMembers( $userId, $aGroup );
			
		}
        
		// Add to persons-array
        // actively ignore person selections; this are not supported
		
		$msg = '';	// debug placeholder
        
		foreach ($aGroupMembers as $groupname => $groupmembers) {
			// Send out message as Group-activity?
			
			// Send out message to Group Members
			foreach ($groupmembers as $person) {
				
				if ($callback != NULL) {
                    /* allow callback to do something with the message/person-instance/groupname */
					$cb_args = array(&$message, $person, $groupname);
					
					call_user_func_array($callback, $cb_args);
				}
			}
		}
	}
    
}   // class GroupRelationsImpl



?>
