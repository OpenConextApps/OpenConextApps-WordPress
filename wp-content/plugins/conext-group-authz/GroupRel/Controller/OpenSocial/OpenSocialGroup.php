<?php
/**
 * OpenSocialGroup overrides factory method to create new instance based on
 * OpenSocial data
 * 
 * 
 * @author dopey (mdobrinic@cozmanova.com)
 * for SURFnet bv (www.surfnet.nl)
 * 
 */
class OpenSocialGroup extends Group {
	
	public static function create($osapi) {
		$oGroup = new OpenSocialGroup($osapi->id["groupId"]);
				
		$oGroup->_aAttributes["title"] = $osapi->title;
		$oGroup->_aAttributes["description"] = $osapi->description;
		
		return $oGroup;
	}
    
    public static function createOS($osapi) {
		$oGroup = new OpenSocialGroup($osapi['id']['groupId']);
        
		$oGroup->_aAttributes["title"] = $osapi['title'];
		$oGroup->_aAttributes["description"] = $osapi['description'];
		
		return $oGroup;
    }
}
?>