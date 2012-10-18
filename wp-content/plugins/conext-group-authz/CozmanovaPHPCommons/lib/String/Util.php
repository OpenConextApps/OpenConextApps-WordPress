<?php
/**
 * String::Util helper functions for dealing with strings
 * 
 * @copyright 2011
 * Cozmanova bv
 * http://www.cozmanova.com
 *
 */

if ( ! defined('__CPC_STRING_UTIL_PHP') )
{
	define( '__CPC_STRING_UTIL_PHP', 1 );

	
	class String_Util {
		
		/**
		 * returns true when the string starts with a provided prefix; 
		 * Note: uses substr, so it is a case sensitive comparison!
		 * @param string $str String to look in to
		 * @param string $prefix Prefix to check for
		 */
		static function stringStartsWith($str, $prefix) {
			return (substr($str, 0, strlen($prefix)) == $prefix); 
		}
		
		
		/**
		 * Produce a random string of iLength characters;
		 * Can take an optional seed value to control random generation.
		 * Uses mt_rand() as PRNG
		 * @param $iLength number of characters to produce
		 * @param $iSeed optional seed for mt_srand; if unset, uses microtime()-based seed
		 */
		static function randomString($iLength, $iSeed = null)
		{
			$i = 0;	
			$str = '';
		
			mt_srand( (isset($iSeed)?$iSeed:(double) microtime() * 1000003) );
			while ( $i < $iLength )
			{
				$str .= substr(md5(mt_rand()), 1, 1);
				$i++;
			}
		
			return $str;
		}
		
		
	}	// class String_Util
	
}	// __CPC_STRING_UTIL_PHP