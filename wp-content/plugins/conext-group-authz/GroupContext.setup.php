<?php
/**
 * Configuration for GroupContext in WordPress
 * 
 * Proof-of-Concept - so this could be optimized A LOT by taking the configuration
 * from the WordPress config-options with nice UI, but this is NOT DONE now.
 */

/**
 * Depend on CozmanovaPHPCommons shared features
 */
require_once('CozmanovaPHPCommons/lib/cpc.php');


/* load application configuration */
$cfgKeys = array( 'OAUTH_CONFIG_requestTokenUrl', 'OAUTH_CONFIG_authorizeUrl', 'OAUTH_CONFIG_accessTokenUrl', 
				  'OAUTH_CONFIG_restEndpoint', 'OAUTH_CONFIG_rpcEndpoint',
				  'OAUTH_CONFIG_consumerKey', 'OAUTH_CONFIG_consumerSecret',
				);

// Load keys from .ini file:
$iniKeys = parse_ini_file( 'config.ini' );
foreach ($iniKeys as $key => $val ) {
	define( $key, $val );
}

// Check definitions integrity
foreach ( $cfgKeys as $key ) {
	if ( ! defined( $key ) ) {
		echo 'CONFIG::' . $key . ' is missing in config.ini';
		exit();
	} 
}


/* Load our own libraries */
// Mind that if SIMPLESAML_PATH is defined, the OAuth.php library is included from SimpleSAML instead of
// from osapi's OAuth include 
require_once('GroupRel/_include.php');

//require_once('GroupRel/Controller/OpenSocial/osapiGroupRelProvider.php');
//require_once('GroupRel/Controller/OpenSocial/GroupRelationsImpl.php');



// Build configuration structure:
global $grouprel_config;
$grouprel_config = array(
		/* cache_ttl: defines how many seconds a fetched instance is cached */
		'cache_ttl' => 2,

		/* userIdAttribute: the userId-attribute to use as (external) userId in openSocial calls */
		'userIdAttribute' => 'NameID',	// set by NameIDAttribute-module in SSP	-- OpenSocial UserID, Grouper UserID

		/* impl: defines a configuration for the actual fetching code */
    	'impl' => array(
			/* class: Worker class instance of IGroupRelations, used to retrieve Group relations */
			'class' => 'OpenSocial_GroupRelationsImpl',
			
			/* configuration for the worker class; see documentation below */
			'consumerkey' => OAUTH_CONFIG_consumerKey,
			'consumersecret' => OAUTH_CONFIG_consumerSecret,
			'provider' => array(
				'providerName' => 'conext',
				'class' => 'osapiGroupRelProvider',
				'requestTokenUrl' => OAUTH_CONFIG_requestTokenUrl,
				'authorizeUrl' => OAUTH_CONFIG_authorizeUrl,
				'accessTokenUrl' => OAUTH_CONFIG_accessTokenUrl, 
				'restEndpoint' => OAUTH_CONFIG_restEndpoint,
				'rpcEndpoint' => OAUTH_CONFIG_rpcEndpoint,
				),
			'strictMode' => FALSE,
    	),
    );



