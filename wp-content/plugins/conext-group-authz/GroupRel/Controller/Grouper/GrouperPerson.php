<?php
/**
 * GrouperPerson overrides factory method to create new instance based on
 * Grouper data
 * 
 * 
 * @author dopey (mdobrinic@cozmanova.com)
 * for SURFnet bv (www.surfnet.nl)
 * 
 */
class GrouperPerson extends Person {
	
	public static function create($grouper) {
		$oPerson = new GrouperPerson($grouper['id']);
				
		//$oPerson->_aAttributes["name"] = $osapi->name["formatted"];
		//$oPerson->_aAttributes["email"] = $osapi->emails[0]["value"];
		
		return $oPerson;
	}
}
?>