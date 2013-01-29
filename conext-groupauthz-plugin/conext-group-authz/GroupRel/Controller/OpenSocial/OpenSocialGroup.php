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
		if (is_array($osapi->id)) {
			$oGroup = new OpenSocialGroup($osapi->id["groupId"]);
		} else {
			$oGroup = new OpenSocialGroup($osapi->id);
		}
				
		$oGroup->_aAttributes["title"] = $osapi->title;
		if (isset( $osapi->description )) {
			$oGroup->_aAttributes["description"] = $osapi->description;
		}
		
		return $oGroup;
	}
    
    public static function createOS($osapi) {
		$oGroup = new OpenSocialGroup($osapi['id']['groupId']);
        
        if (array_key_exists('title', $osapi)) {
            $oGroup->_aAttributes["title"] = $osapi['title'];
        }
        if (array_key_exists('description', $osapi)) {
            $oGroup->_aAttributes["description"] = $osapi['description'];
        }
		
		return $oGroup;
    }
}
?>
