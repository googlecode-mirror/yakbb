<?php
/*==================================================*\
|| ___     ___  ___     _     __  ______    ______
|| \--\   /--/ /---\   |-|   /-/ |--___-\  |--___-\
||  \--\_/--/ /--_--\  |-|  /-/  |-|___\-| |-|___\-|
||   \_---_/ /--/_\--\ |--\/-/   |------<  |------<
||     |-|   |---_---| |-----\   |--____-\ |--____-\
||     |-|   |--/ \--| |--/\--\  |-|___/-| |-|___/-|
||     |_|   |_|   |_| |_|  |__| |______/  |_______/
||
||==================================================||
|| Program: YakBB v1.0.0
|| Author: Chris Dessonville
||==================================================||
|| File: /index.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/


ob_start(); // Catch white output and smarty output to
// avoid headers errors and allow redirect at any point

session_start();

// Register GLOBALS protection. YakBB should be secure already, but it doesn't hurt.
// Snippet from http://www.php.net/manual/en/faq.misc.php#faq.misc.registerglobals
if(ini_get('register_globals')){
	if(isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])){
		die('GLOBALS overwrite attempt detected');
	}

	// Variables that shouldn't be unset
	$noUnset = array(
		'GLOBALS',	'_GET',
		'_POST',	'_COOKIE',
		'_REQUEST',	'_SERVER',
		'_ENV',		'_FILES'
	);

	$input = array_merge(
		$_GET,		$_POST,
		$_COOKIE,	$_SERVER,
		$_ENV,		$_FILES,
		(isset($_SESSION) && is_array($_SESSION)?$_SESSION:array())
	);

	foreach($input as $k => $v){
		if(!in_array($k, $noUnset) && isset($GLOBALS[$k])){
			unset($GLOBALS[$k]);
		}
	}
	unset($noUnset);
	unset($input);
}

// Set error reporting for now
error_reporting(E_ALL);

// Security stuff before we load anything.
set_magic_quotes_runtime(false); // Disable magic quotes from DB.

// Strip slashes management
if(get_magic_quotes_gpc()){
	function ss($n){
		foreach($n as $k=>$v)
			if(is_array($v))
				$n[$k] = ss($v);
			else
				$n[$k] = stripslashes($v);
	}
	ss(&$_REQUEST);
	ss(&$_POST);
	ss(&$_GET);
}

// Begin loading necessary data
require "./constants.inc.php";
require YAKBB_CORE."classes/YakBB.class.php";

$yakbb = new YakBB();
$yakbb->initiate();

// Output the final content
ob_end_flush();

?>