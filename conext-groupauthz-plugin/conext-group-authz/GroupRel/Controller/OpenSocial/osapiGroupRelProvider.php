<?php
/**
 * OSAPI Provider for GroupRelations environment
 * @author dopey
 */

class osapiGroupRelProvider extends osapiProvider {
  public function __construct(osapiHttpProvider $httpProvider = NULL, array $config = array()) {
    parent::__construct($config["requestTokenUrl"], 
    		$config["authorizeUrl"], 
    		$config["accessTokenUrl"], 
    		$config["restEndpoint"], 
    		$config["rpcEndpoint"], 
    		$config["providerName"], true, $httpProvider);
  }

  /**
   * Set's the signer's useBodyHash to true
   * @param mixed $request The osapiRequest object being processed, or an array
   *     of osapiRequest objects.
   * @param string $method The HTTP method used for this request.
   * @param string $url The url being fetched for this request.
   * @param array $headers The headers being sent in this request.
   * @param osapiAuth $signer The signing mechanism used for this request.
   */
  public function preRequestProcess(&$request, &$method, &$url, &$headers, osapiAuth &$signer) {
    if (method_exists($signer, 'setUseBodyHash')) {
      $signer->setUseBodyHash(true);
    }
    // Should we add scope, and how:
    if ($method == 'GET') {
    	$request->params['scope']='read';
    }
    
    // Initialize headers, to enforce OAuth Authorization through
    // http-headers instead of through querystring parameters
    if (! is_array($headers)) {
    	$headers = array();
    }
  }
}
?>