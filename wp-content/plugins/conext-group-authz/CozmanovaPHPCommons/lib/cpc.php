<?php

/*
 * Add classes directory to the autoloader
 */

$__CPC_classes = array('Web', 'String', 'Logger');  

/* chicken/egg pre-emptive load of Logger_Log */
require_once("Logger/Log.php");

function __CPC_autoload($sClassname) {
	global $__CPC_classes;
	/* tries to load [module]_[classname] from [module]/[classname].php */
	$a = explode('_', $sClassname);
	if (count($a) > 1) {
		if (in_array($a[0], $__CPC_classes)) {
			// echo "boo: $sClassname<br/>\n";
			$c = join('_', array_slice($a, 1, count($a)-1));
			Logger_Log::trace("__CPC_autoload: including $c", "__CPC_autoload");
			include($a[0] . '/' . $a[1] . '.php');
		}
	}
}

spl_autoload_register('__CPC_autoload');

?>