<?php
/**
 * GrouperGroup overrides factory method to create new instance based on
 * Grouper data
 * 
 * 
 * @author dopey (mdobrinic@cozmanova.com)
 * for SURFnet bv (www.surfnet.nl)
 * 
 */
class GrouperGroup extends Group {
	
	public static function create($grouper) {
		$oGroup = new GrouperGroup($grouper['id']);
				
		$oGroup->_aAttributes["title"] = $grouper["name"];
		$oGroup->_aAttributes["description"] = $grouper["description"];
		
		return $oGroup;
	}
}
?>