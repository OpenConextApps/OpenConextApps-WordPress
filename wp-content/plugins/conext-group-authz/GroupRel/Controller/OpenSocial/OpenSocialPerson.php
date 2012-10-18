<?php
/**
 * OpenSocialPerson overrides factory method to create new instance based on
 * OpenSocial data
 * 
 * 
 * @author dopey (mdobrinic@cozmanova.com)
 * for SURFnet bv (www.surfnet.nl)
 * 
 */
class OpenSocialPerson extends Person {
	
	public static function create($osapi) {
		$oPerson = new OpenSocialPerson($osapi->id);
				
		$oPerson->_aAttributes["name"] = $osapi->name["formatted"];
		$oPerson->_aAttributes["email"] = $osapi->emails[0]["value"];
		
		return $oPerson;
	}
    
    /** Construct OpenSocialPerson from osapi ARRAY result **/
	public static function createOS($osapi) {
		$oPerson = new OpenSocialPerson($osapi["id"]);
        
		$oPerson->_aAttributes['name'] = $osapi['name']['formatted'];
		$oPerson->_aAttributes['firstname'] = $osapi['name']['familyName'];
		$oPerson->_aAttributes['lastname'] = $osapi['name']['givenName'];
        
		$oPerson->_aAttributes['email'] = $osapi['emails'][0]['value'];
        
		return $oPerson;
	}
    
}
?>