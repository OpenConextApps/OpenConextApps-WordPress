<?php 

class Group extends Selectable {
	
	private $_sGroupIdentifier = NULL;
	
	private $_aContacts = array();
	
	/**
	 * Array of arrays of values
	 * @var array
	 */
	public $_aAttributes = array();
	
	
	public function __construct($sGroupIdentifier) {
		$this->_sGroupIdentifier = $sGroupIdentifier;
	}
	
	
	public function getIdentifier() {
		return $this->_sGroupIdentifier;
	}
	
	public function getContacts() {
		return $this->_aContacts;
	}
	
	
	public static function fromJSON($oJSON) {
		$oGroup = new Group($oJSON["name"]);
		
		foreach ($oJSON["contacts"] as $oContact) {
			$oGroup->_aContacts[] = Person::fromJSON($oContact);
		}
		
		return $oGroup;
	}
	
	
	
	/**
	 * In: ?
	 * 
	 * Out: 
	 * array(
	 * 	"id" => idenfitier,
	 *  "name" => name,
	 *  "attributes" => array( key=>val, ... , key=>val )
	 *  "children" =>
	 *  	array(
	 *  	 "id => identifier
	 *  	 "name" => name,
	 *  	 "attributes" => array( key=>val, ... , key=>val )
	 *  	)
	 *  )
	 *  
	 
	 * @param $aJSON
	 * @return unknown_type
	 */
	public static function fromJSON_flat($aJSON) {
		$group = array();
		
		$group["id"] = $aJSON["name"];
		$group["name"] = $aJSON["name"];
		
		if (! isset($aJSON["contacts"])) {
			return $group;
		}
		
		$group["children"] = array();
		
		foreach ($aJSON["children"] as $contact) {
			$group["children"] = Group::fromJSON_flat($contact); 
		}
		
		
	}
	
	
	public function toJSON_array() {
		$aDataContacts = array();
		foreach ($this->_aContacts as $oContact ) {
			$aDataContacts[] = $oContact->toJSON_array();
		}
		return array( "name" => $this->_sGroupIdentifier,
						"contacts" => $aDataContacts);
		
	}

	
	public function toJSON() {
		return json_encode($this->toJSON_array());
	}
	
	
	
	
	public static function fromXml($oXML) {
		
		
	}
	
	
	public static function fromOsapi($osapi) {
		if (is_array($osapi)) {
			$oGroup = new Group($osapi['id']['groupId']);
			$oGroup->_aAttributes["title"] = $osapi['title'];
			$oGroup->_aAttributes["description"] = $osapi['description'];
		} else {
			$oGroup = new Group($osapi->id);
				
			$oGroup->_aAttributes["title"] = $osapi->title;
			if (isset($osapi->description)) {
				$oGroup->_aAttributes["description"] = $osapi->description;
			}
		}	
		return $oGroup;
	}
	
	
	public function __toString() {
		return $this->getIdentifier(); 
	}
	

	
}
?>
