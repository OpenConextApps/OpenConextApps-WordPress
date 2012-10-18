<?php 

class Person extends Selectable {
	
	private $_sContactIdentifier = NULL;
	
	/**
	 * Array of arrays of values
	 * @var array
	 */
	public $_aAttributes = array();
	
	public function __construct($sUID) {
		$this->_sContactIdentifier = $sUID;
	}
	
	
	public function getIdentifier() {
		return $this->_sContactIdentifier;
	}
	
	public function getAttributes() {
		return $this->_aAttributes;
	}

	
	public static function fromJSON($oJSON) {
		$oContact = new Person($oJSON["uid"]);
		
		foreach ($oJSON["attributes"] as $sKey => $oVal) {
			if (!is_array($oVal)) {
				$oContact->_aAttributes[$sKey] = array($oVal);
			} else {
				$oContact->_aAttributes[$sKey] = $oVal;
			}
		}
		
		return $oContact; 
	}
	
	
	public function toJSON_array() {
		$aData = array( "uid" => $this->_sContactIdentifier,
						"attributes" => $this->_aAttributes);

		return $aData;
	}

	
	public function toJSON() {
		return json_encode($this->toJSON_array());
	}
	
	
	public static function fromOsapi($osapi) {
		$oPerson = new Person($osapi->id);
				
		$oPerson->_aAttributes["name"] = $osapi->name["formatted"];
		$oPerson->_aAttributes["email"] = $osapi->emails[0]["value"];
		
		return $oPerson;
	}
	
	public function __toString() {
		if (isset($this->_aAttributes["name"])) {
			return $this->_aAttributes["name"];
		}
		
		return $this->getIdentifier(); 
	}
	
	
}

?>