<?php
/**
 * Logger::Log logging functions
 * 
 * @copyright 2011
 * Cozmanova bv
 * http://www.cozmanova.com
 *
 */

if ( ! defined('__CPC_LOGGER_LOG_PHP') )
{
	define( '__CPC_LOGGER_LOG_PHP', 1 );

	
	class Logger_Log {
		public static $LOG_LEVEL_TRACE = "trace"; 
		public static $LOG_LEVEL_DEBUG = "debug"; 
		public static $LOG_LEVEL_WARNING = "warning";
		public static $LOG_LEVEL_ERROR = "error";
		public static $LOG_LEVEL_FATAL = "fatal";

		static function log($level, $msg, $ctx = null) {
			switch ($level) {
				case LOG_LEVEL_TRACE:
					self::trace($msg, $ctx);
					break;
				case LOG_LEVEL_DEBUG:
					self::debug($msg, $ctx);
					break;
				case LOG_LEVEL_WARNING:
					self::warn($msg, $ctx);
					break;
				case LOG_LEVEL_ERROR:
					self::error($msg, $ctx);
					break;
				case LOG_LEVEL_FATAL:
					self::fatal($msg, $ctx);
					break;
			}
		}

		static function trace($msg, $ctx = null) {
			$includeCtx = array();
			$excludeCtx = array("__AuthZServer_autoload", "__CPC_autoload", "Core_User");
			
			if ($ctx != null && in_array($ctx, $excludeCtx)) { 
				return; 
			}
			
			echo "trace:" . ($ctx!=''?$ctx.':':'') . $msg . "<br/>\n";
		}

		static function debug($msg, $ctx = null) {
			$includeCtx = array();
			$excludeCtx = array("__AuthZServer_autoload", "__CPC_autoload", "Core_User");
			
			if ($ctx != null && in_array($ctx, $excludeCtx)) { 
				return; 
			}
			
			echo "debug:" . ($ctx!=''?$ctx.':':'') . $msg . "<br/>\n";
				
		}

		static function warn($msg, $ctx = null) {
			echo "warn:" . ($ctx!=''?$ctx.':':'') . $msg . $msg . "<br/>\n";
				
		}

		static function error($msg, $ctx = null) {
			echo "ERROR:" . ($ctx!=''?$ctx.':':'') . $msg . $msg . "<br/>\n";
				
		}

		static function fatal($msg, $ctx = null) {
			echo "FATAL:" . ($ctx!=''?$ctx.':':'') . $msg . $msg . "<br/>\n";
				
		}
		
		
	}

}
?>