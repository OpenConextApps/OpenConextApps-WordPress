<?php
/**
 * Web::CGI utility functions
 * 
 * @copyright 2010 
 * Cozmanova bv 
 * http://www.cozmanova.com
 *
 */

if ( ! (__CPC_WEB_CGIUTIL_PHP == 1) )
{
	define( '__CPC_WEB_CGIUTIL_PHP', 1 );


	class Web_CGIUtil {
		
		static function get_prf_argument($a1, $a2, $a3, $name, $default_value = null) {
			$r = $a1[$name];
			if (! isset($r) && is_array($a2)) {
				$r = $a2[$name];
			}
			if (! isset($r) && is_array($a3)) {
				$r = $a3[$name];
			}
			
			if (isset($r)) return $r;
			
			return $default_value;
		}
		
		/**
		 * Retrieve argument value, try from GET first, then from POST and if still not found, try from COOKIE
		 * @param $name name of the argument
		 * @param $default_value default return value
		 * @return unknown_type value if found, default value if not found
		 */
		static function get_argument($name, $default_value=null) {
			global $_GET, $_POST, $_COOKIE;

			return Web_CGIUtil::get_prf_argument($_GET, $_POST, $_COOKIE, $name, $default_value);
		}
		
		static function get_checked_string($name, $min_len=0, $max_len=-1, $default_value=null) {
			return GenUtil::get_argument($name, $default_value);
		}

		
		static function get_checked_int($name, $min_val=0, $max_val=-1, $default_value=null) {
			return GenUtil::get_argument($name, $default_value);
		}
		
		
		static function get_self_url($path = NULL) {
			$port = $_SERVER['SERVER_PORT'];
			if ($_SERVER['HTTPS'] == 'on') {
				$proto = 'https';
				if ($port == 443) $port = NULL;
			} else {
				$proto = 'http';
				if ($port == 80) $port = NULL;
			}
			$url = $proto . '://' . $_SERVER['SERVER_NAME'] . (isset($port) ? ':' . $port : '');
			if (isset($path)) $url .= $path;
			return $url;
		}
		
		
		/**
		 * Append CGI-argument to URI
		 * @param unknown_type $base Base URL  
		 * @param unknown_type $key argument name
		 * @param unknown_type $val argument value (not yet URL-encoded)
		 */
		static function appendArg($base, $key, $val = null) {
			$s = $base;
			if (strpos($base, '?') > 0) {
				$s .= "&$key";
			} else {
				$s .= "?$key";
			}
			if ($val != null) { $s .= "=" . self::urlencode_rfc3986($val); }
			return $s;
		}
		
		
		static function cgistring_remove($cgistring, $keys_to_remove) {
			$params = array();

			$kv = explode("&", $cgistring);
			foreach ($kv as $k) {
				$p = explode("=", $k);
				$params[$p[0]] = $p[1];
			}
			
			foreach ($keys_to_remove as $r) {
				unset($params[$r]);
			}
			
			$kv = array();
			foreach ($params as $k=>$v) {
				$kv[] = $k.'='.$v;
			}
			
			return join('&', $kv);
		}
		
		
		static function urlencode_rfc3986($input) {
			if (is_array($input)) {
				return array_map(array('Web_CGIUtil', 'urlencode_rfc3986'), $input);
			} else if (is_scalar($input)) {
				return str_replace('+', ' ',
							str_replace('%7E', '~', rawurlencode($input))
					);
			} else {
				return '';
			}
		}
		
		
		
	}


}	// __CPC_WEB_CGIUTIL_PHP


?>