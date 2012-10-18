<?php
/**
 * LimeSurvey Token Support for OpenSocial group relations
 * Implemented by M. Dobrinic for SURFnet
 * mdobrinic@cozmanova.com
 **/

global $grouprel_config;
require_once("GroupRel/_include.php");		// Link to external Library

class GroupTokens {
	private $_auth;			// Authentication Context, must contain userIdAttribute with userId
	private $_surveyId;		// Survey to work for
	private $_grouprel;		// array containing configuration
	
	private static $fetcher;	// IGroupRelations instance
	private static $cache_ttl;	// cache ttl period in seconds
	
	private $_userIdAttribute;	// name of the attribute that contains the UserID to use in fetcher-calls
	
	private $output;		// cached output variable
    
    public $_allmembers;    // placeholder for storing all the members
	
	
	/**
	 * Construct new GroupToken instance
	 * @param $auth initialized FoodleAuth-instance
	 * @param $foodle Initialized Foodle-instance, to work with
	 * @param $grouprel array containing the GroupRel-configuration
	 * @return unknown_type
	 */
	function __construct($auth, $surveyId, $grouprel) {
		$this->_auth = $auth;
		$this->_surveyId = $surveyId;
		$this->_grouprel = $grouprel;
		
		if (! isset(self::$cache_ttl)) {
			self::$cache_ttl = ($grouprel['cache_ttl'] ? $grouprel['cache_ttl'] : 300);	// defaults to 300s (5 minutes)
		}
		
		$this->_userIdAttribute = $grouprel['userIdAttribute'];
		
		// Instantiate our fetcher:
		if (! isset(self::$fetcher)) {
			$options = $grouprel['impl'];
			self::$fetcher = IGroupRelations::create($options);
		}
		
	}
	
	// Establish the UserID to use to link groups to
	public function getSubjectUser() {
		$a = $this->_auth;

		if (! array_key_exists( $this->_userIdAttribute, $a)) {
			throw new Exception("Attribute '{$this->_userIdAttribute}' was not found in set of user-attributes");
		}
		
		$userId = $a[ $this->_userIdAttribute ];
		if (is_array($userId)) {
			return $userId[0];
		}
		
		return $userId;
	}
	
	
	/**
	 * Cache wrapper; returns group relations stored in session key, or fetches
	 * new or updated version when non-existant or expired.
	 * @param string $var name of the data in the session
	 * @param attay @re_fetcher_arguments list of arguments to pass to the 
	 * 		fetcher when cache lookup failed
	 **/
	public static function getFreshFromSession($var, $re_fetcher_arguments = array() ) {

		if (!isset($_SESSION[$var])) $_SESSION[$var] = serialize(array());	// assert existing session variable
		
		$t = $_SESSION[$var];
		$o = unserialize( $t );

		if (!isset($o["instance"]) || self::is_expired($o, true, self::$cache_ttl)) {
			// get fresh copy, for this userId
			$fresh = self::$fetcher->fetch($re_fetcher_arguments);
			$o["instance"] = $fresh;
			$o["created"] = time();

			// Set new version in session:
			$_SESSION[$var] = serialize($o);
			
		}
		
		return $o["instance"];
	}
	
	
	/**
	 * Check whether a cache-item is expired or not, according to configured TTL setting
	 * @param array $cache_item item to inpect (array containing item "created" with unix timestamp)
	 * @param boolean $touch_item default to false; when true, the create-time of the cache_item 
 	 *   will be reset to current time
 	 * @param int @ttl number of seconds since "created" timestamp that a cached instance is valid
 	 **/
	public static function is_expired(&$cache_item, $touch_item = false, $ttl = 0) {
		$created = $cache_item["created"];
		if (! isset($created)) {
			return true;
		}
		
		if (intval($created) < time() - $ttl) {
			return true;
		}

		// touch created time to extend lifetime of instance
		if ($touch_item) {
			$cache_item["created"] = time();
		}
		
		return false;
	}		
	
	/**
	 * Private helper; prints data to console or returns data, depending on $buffered value
	 **/
    private static function returnBuffered($data, $buffered) {
    	if ($buffered) {
    		return $data;
    	} else {
    		print $data;
    		return;
    	}
    }

	
	/**
	 * Renders client-side code that leads to the group-selection form
	 * This is implemented in an AJAX function, that performs a separate request to establish
	 * the Group Relations form
	 * @param string @url default:null; contains the URL that returns the GroupSelection-form
	 *   if this is null, the URL will be calculated as 
	 *    "/limesurvey/admin/admin.php?action=osgroup&sid=$surveyid&subaction=form"
	 * @param boolean $buffered default false; when true, the response is returned as string, otherwise
	 *   it is directly written to the output
	 * @return when buffered==true, the output, otherwise nothing is returned.
	 **/
	function show($url = null, $buffered = false) {
		global $rooturl, $clang;
		
		// Perform 3-legged-OAuth using OpenSocial; do we have an access-token?
        self::$fetcher->prepareClient($this->getSubjectUser());
		// This will have redirected the user if there was no access-token yet... so we can move on now ;)
		
		// If we have already done our work, return it
		if (isset($this->output)) {
			return self::returnBuffered($this->output, $buffered);
		}

		$o = '';
		// Default action is to show the groups-form from (cached) definitions
		$o .= '<h2>' . 
		      $clang->gT("Invite your groups") . 
		      '</h2>';

		if ($url == null) {
	        $sGroupTokensURL = "/limesurvey/admin/admin.php?action=osgroup&sid=$surveyid&subaction=form";
	    } else {
	    	$sGroupTokensURL = $url;
	    }
		
		$o .= <<<SHOW
<div id="dGroupRelDistribute" style="margin: 0 auto;"></div>
<script type="text/javascript">

function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}


function loadGroupRel(url)
{
  if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  
  document.getElementById("dGroupRelDistribute").innerHTML = "<img src='$rooturl/images/GroupRel/resources/ajax-loader.gif' />";
  
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4) {
      if (xmlhttp.status==200) {
        document.getElementById("dGroupRelDistribute").innerHTML=xmlhttp.responseText;
      } else {
        alert("There was a problem when loading the group selector form.");
      }
    }
  };

  xmlhttp.open("GET",url,true);
  xmlhttp.send();
}

addLoadEvent( function() {
	loadGroupRel('{$sGroupTokensURL}') 
	} );
</script>
SHOW
;

        return self::returnBuffered($o, $buffered);
	}	// function show();
	

	/**	
	 * Establish all the users that must be invited for the survey
	 * When function is complete, the members are contained in ->_allmembers variable
	 * as a list of Person-instances.
	 * @return array of Person-instances that should be invited for the survey
	 **/
	public function invite() {
		// Establish groups gor current logged in user
		$userId = $this->getSubjectUser();
		
		// Establish relations:
		$relations = self::getFreshFromSession("relations", array("userId" => $userId));
		// Establish *selected* Groups and Persons 
		$aAllSelected = array();
		
		$aSelectedGroups = Selectable::selectedFromForm($_REQUEST, $relations);
		foreach ($aSelectedGroups as $aGroup) $aAllSelected[ $aGroup->getIdentifier() ] = $aGroup;

		if (sizeof($aAllSelected) == 0) {
			$output = "<p>You did not select any group.</p>";		
		}
		if (sizeof($aAllSelected) > 0) {
            // static callback: array(__CLASS__, "member-function-name")
            // instance callbacK: array($object_instance, "member-function-name")

            $this->_allmembers = array();
            
			self::$fetcher->process(array('userId' => $userId, 'message' => new Message()), 
				array($this, "appendMember"),			
				$aSelectedGroups, null);
            
            
            // $this->_allmembers now contains all the members
            // Delete doubles (based on identifier)
            $this->_allmembers = array_unique( $this->_allmembers );
            
            $output = '<h2>All members that were retrieved</h2><ul>';
            foreach ($this->_allmembers as $member) {
                $a = $member->getAttributes();
                $output .= '<li>' . $a['name'] . ' (' . $a['email'] . ')</li>';
            }
            $output .= '</ul>';
		}
		
		$this->output = "<div>" . $output . "</div>";
		
		return $output;
	}
	
	/*
     * $person-attributes contains:
     * "name" : full name of the person
     * "email" : email address of the person
     * $message contains the message instance that was passed when process() was called
     */
	public function appendMember(Message $message, &$person, $groupname) {
        $this->_allmembers[] = $person;
        // no more to do.
	}
	
		

	/** for testing purposes **/
	public function getFetcher() {
		return self::$fetcher;
	}
	
}



?>
