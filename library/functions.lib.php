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
|| File: /library/functions.lib.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

/*	TODO
	getUserGroup()
		- Needs more testing
	makeDate()
		- Make it load the user's default format if $format is null.
	redirect()
		- Make it accept external URLs
*/

if(!defined("SNAPONE")) exit;

function ampToNormal($str){
	// Converts &amp; to &. Typically used in URL stuff

	return preg_replace("/&amp;/", "&", $str);
}

function getUserGroup(){
	// Returns the user's group data

	global $yak, $user;
	if($user["group"] > 0 && isset($yak->groups[$user["group"]])){
		$group = $yak->groups[$user["group"]];
	} else if($user["id"] > 0){
		$group = array(
			"id" => 0,
			"name" => "Member",
			"color" => "a:0:{}",
			"replytolocked" => 0
		);
	} else {
		$group = array(
			"id" => -1,
			"name" => "Guest",
			"color" => "a:0:{}",
			"replytolocked" => 0
		);
	}

	return $group;
}

function libraryLoad($n){
	// Loads a library section that is not loaded by default (deletion, validation, etc.)

	require_once LIBDIR.$n.".php";
}

function redirect($url){
	// Redirects to the specified page. It then exits the script.
	// @param	Type		Description
	// $url		String		The part of the URL after the slash.

	$host = $_SERVER['HTTP_HOST'];
	$uri = rtrim(dirname($_SERVER['PHP_SELF']), "/\\");
	header("Location: http://".$host.$uri.$url);
	exit;
}

function secure($data){
	// Secures HTML entities for the specified input

	return htmlentities($data, ENT_QUOTES);
}

function makeDate($time=false, $format="D M d, Y g:i a"){
	// Creates a date format based off the given time.
	// @param	Type	Description
	// $time	Mixed	Integer of time or boolean false telling you to create a new time()
	// $format	String	The format the string should take. If not given, defaults to the user's specified format.
	// Return	Return	Returns the date string.

	if($time === false){
		$time = time();
	}

	return date($format, $time);
}

function sha256($data){
	// sha256 hash on the data.

	// Add the random salt for security and then return the sha256 hash
	return hash("sha256", $data.DBSALT);
}

function urlSafe($str, $len=-1){
	// Makes a string safe to be used in clean URLs
	// @param	Type		Description
	// $str		String		The string to be cleaned up
	// $len		Number		The length the string should be trimmed to. -1 means no trim
	// return	Return		Returns the URL safe string

	$str = html_entity_decode($str, ENT_QUOTES); // They all get stripped anyway
	$str = preg_replace("/[^A-Z0-9_-]/i", "_", $str);
	if($len != -1 && strlen($str) > $len){
		$str = substr($str, 0, $len);
	}
	return $str;
}
?>