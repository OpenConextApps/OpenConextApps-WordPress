<?php
/**
 * GroupRelations implementation for OpenSocial API
 * 
 * Implementation of interface
 * 
 * @author Mark Dobrinic (mdobrinic@cozmanova.com)
 * Implementation for SURFnet (http://www.surfnet.nl)
 */


require_once( dirname(dirname(dirname(__FILE__))) . "/ExtLib/osapi/osapi.php");
require_once( 'osapiGroupRelProvider.php' );
require_once( 'OpenSocialGroup.php' );
require_once( 'OpenSocialPerson.php' );


class GroupRelationsImpl extends IGroupRelations {
	
	private $_consumerkey;
	private $_consumersecret;
	private $_osapiProvider;	// instance of configured osapi-provider
	
	private $_msgSource;		// configurable message source (i.e. from-email-address)
	
	private $_strictMode;
	
	private $_filestoragepath;	// FileStorage path for osapiFileStorage
	
	/**
	 * (non-PHPdoc)
	 * Configuration has to provide:<br/>
	 * <ul><li>osapi-provider: class of the osapi-provider
	 *     <li>key: Oauth consumer key</li>
	 *     <li>secret: Oauth consumer secret</li>
	 *     <li>provider: Name of the OSAPI Provider class that does the OpenSocial-work</li>
	 * </li>

	 * @see lib/GroupRel/Controller/IGroupRelations#configure()
	 */
	public function configure($config) {
		$this->_consumerkey = $config['consumerkey'];
		$this->_consumersecret = $config['consumersecret'];

		$this->_strictMode = ($config["strictMode"] == TRUE);
		
		$this->_msgSource = (isset($config["msgSource"])?$config["msgSource"]:null);
		
		$provider_config = $config["provider"];
		$cln = $provider_config["class"];
		$this->_osapiProvider = new $cln(NULL, $provider_config);
		
		$this->_filestoragepath = '/tmp/osapi';
	}
	
	
	/**
	 * Perform function to initialize the context to get user groups
	 * @param string $userId external userID
	 * throws exception when something goes really wrong
	 **/
	public function prepareClient($userId) {
		// make sure that a valid access-token is established for the user
        $storage = new osapiFileStorage($this->_filestoragepath);
        $auth = osapiOAuth3Legged_10a::performOAuthLogin(
                            $this->_consumerkey, $this->_consumersecret, 
                            $storage, $this->_osapiProvider, $userId);
	}
	
	/**
	 * Helper function to make one (2-legged-OAuth) call to configured OpenSocial container 
	 * @param $userId UserId to work with
	 * @param $osapi_service.call Service to call
	 * @param $keytoset name of the array key that will contain the results 
	 * @return array containing 'keytoset' => results, or osapiError-instance when error occurred
	 */
	protected function callOpenSocial2($user_params, $osapi_service, $keytoset) {
  		$osapi = new osapi(
  					$this->_osapiProvider, 
  					new osapiOAuth2Legged(
  						$this->_consumerkey, 
  						$this->_consumersecret, 
  						$user_params['userId']
  						)
  					);
  					
		if ($this->_strictMode) {
			$osapi->setStrictMode($strictMode);
		}
  
		// Start a batch so that many requests may be made at once.
		$batch = $osapi->newBatch();

		$call = explode('.', $osapi_service);
		if (sizeof($call) != 2) {
			throw new Exception("Invalid OpenSocial service call: {$osapi_service}");
		}
		
		// Instantiate service
		$oService = $osapi->$call[0];
		 
		$batch->add($oService->$call[1]($user_params), $keytoset);

		// Send the batch request.
		$result = $batch->execute();

		return $result;
	}


    /**
	 * Helper function to do 3-legged-OAuth OpenSocial request
	 * @param $userId UserId to work with
	 * @param $osapi_service.call Service to call
	 * @param $keytoset name of the array key that will contain the results 
	 * @return array containing 'keytoset' => results, or osapiError-instance when error occurred
	 */
    protected function callOpenSocial($user_params, $osapi_service, $keytoset) {
        $storage = new osapiFileStorage($this->_filestoragepath);
        $auth = osapiOAuth3Legged_10a::performOAuthLogin(
                            $this->_consumerkey, $this->_consumersecret, 
                            $storage, $this->_osapiProvider, $user_params['userId']);

  		$osapi = new osapi($this->_osapiProvider, $auth);

		if ($this->_strictMode) {
			$osapi->setStrictMode($strictMode);
		}
        
		// Start a batch so that many requests may be made at once.
		$batch = $osapi->newBatch();
		$call = explode('.', $osapi_service);
		if (sizeof($call) != 2) {
			throw new Exception("Invalid OpenSocial service call: {$osapi_service}");
		}
		// Instantiate service
		$oService = $osapi->$call[0];
		$user_params['userId'] = '@me';	// real userId does not work,use '@me' instead
		$batch->add($oService->$call[1]($user_params), $keytoset);

		// Send the batch request.
		try {
			$result = $batch->execute();
		} catch (Exception $e) {
			// Rethrow
			throw new Exception("Invalid OSAPI-call: " . $e->getMessage());
		}

//print_r($result);
//print_r($oService); exit();		
		
		
		if ($result[$keytoset] instanceof osapiError) {
			$err = $result[$keytoset];
			if ($err->getErrorCode() == 401) {
				// Token did not authorize the request; dispose of it, and
				// get a new one:
				if (($token = $storage->get($auth->storageKey)) !== false) {
      				$storage->delete($auth->storageKey);
      				
      				/* protect against infinite local loop */
      				$this->_token_retry_count = (isset($this->_token_retry_count)? $this->_token_retry_count+1 : 1);
      				if ($this->_token_retry_count < 3) {
	      				$this->prepareClient($user_params['userId']);
	      				return $this->callOpenSocial($user_params, $osapi_service, $keytoset);
      				} else {
      					throw new Exception("Could not establish accesstoken");
      				}
	      				
				} else {
					throw new Exception("Problem occured when performing OpenSocial call: {$osapi_service}");
				}
				
			}
		}
		
		return $result;
	}
    
	
	/**
	 * Fetch group relations for provided user<br/>
	 * <br/>
	 * Performs 3-legged Oauth call through OpenSocial REST API<br/>
	 * $args is an array with at least "userId" => OpenSocial UserID to perform call for
	 * @return array of Group and Person instances
	 */
	public function fetch($args) {
		$userId = $args["userId"];
		$user_params = array(
			'userId' => $userId
		);

		$result = $this->callOpenSocial($user_params, "groups.get", "getGroups");

		if (($result instanceof osapiError) || ($result['getGroups'] instanceof osapiError)) {
			// what to do? ignore request? or throw exception
			print_r($result); exit();
			throw new Exception("Error when retrieving group information OpenSocial (provider: " . $this->_osapiProvider->providerName . ")");
			// return array();
		}

		$fetchresult = array();

		if (is_object($result['getGroups'])) {
			foreach ($result['getGroups']->list as $osapiGroup) {
				$fetchresult[] = OpenSocialGroup::create($osapiGroup);
			}
		} elseif (is_array($result['getGroups'])) {
			foreach ($result['getGroups']['result']['list'] as $osapiGroup) {
				$fetchresult[] = OpenSocialGroup::createOS($osapiGroup);
			}
		}
		
		return $fetchresult;
	}
	
	
	private function getGroupMembers($userId, $group) {
		$user_params = array(
			'userId' => $userId,
			'groupId' => $group->getIdentifier(),
		);
		
		$result = $this->callOpenSocial($user_params, "people.get", "getPeople");
		
		if ($result instanceof osapiError) {
			// what to do? ignore request? or throw exception
			throw new Exception("Error when retrieving group member information from OpenSocial (provider: " . $this->_osapiProvider->providerName . ")");
			// return array();
		}

		$fetchresult = array();

		if (is_object($result['getPeople'])) {
			foreach ($result['getPeople']->list as $osapiPeople) {
				$fetchresult[] = OpenSocialPerson::create($osapiPeople);
			}
		} elseif (is_array($result['getPeople'])) {
			foreach ($result['getPeople']['result']['list'] as $osapiPeople) {
				$fetchresult[] = OpenSocialPerson::createOS($osapiPeople);
			}
		}
		
		return $fetchresult;
	}
	
	
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
		if (is_array($persons)) {
			$aGroupMembers["person"] = $persons;
		}
		
		$msg = '';	// debug placeholder
			
		foreach ($aGroupMembers as $groupname => $groupmembers) {
			// Send out message as Group-activity?
			
			// $this->sendSocialGroupMessage($groupname, $message);
			
			// Send out message to Group Members
			foreach ($groupmembers as $person) {
				
				if ($callback != NULL) {
					$cb_args = array(&$message, &$person, $groupname);
					
					call_user_func_array($callback, $cb_args);
				}
				
				$msg .= "===== New Message\n" . $message->__toString() . "\n\n"; 
			}
		}
		
		// Debug: mail total results to developer:
//		mail("someemail@developer.email.address", "Process Results", $msg, "From: {$message->_sender}");
		
	}
	
	
}
?>
