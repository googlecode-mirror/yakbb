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
|| File: /core/includes/functions.lib.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

/*	TODO
		boardPermissions()
			- Needs to be coded completely
		catPermissions()
			- Needs to be coded completely
		getPermissions()
			- Needs to change to be get permissions updated while in a board too
*/

defined("YAKBB") or die("Security breach.");

// General functions
function makeDate($time=0, $format="F j, Y, g:i a"){
	if($time == 0){
		$time = time();
	}
	return date($format, $time);
}

function secure($data){
	// Secures HTML entities for the specified input

	return htmlentities($data, ENT_QUOTES);
}

function sha256($dat){
	// sha256 hash on the data.

	// Add the random salt for security and then return the sha256 hash
	return hash("sha256", $data.YAKBB_DBSALT);
}

function loadLibrary($n){
	// Loads a library section that is not loaded by default (deletion, validation, etc.)

	if(file_exists(YAKBB_CORE.$n.".php")){
		require_once YAKBB_CORE.$n.".php";
	} else if(file_exists(YAKBB_INCLUDES.$n.".php")){
		require_once YAKBB_INCLUDES.$n.".php";
	} else if(file_exists(YAKBB_CLASSES.$n.".php")){
		require_once YAKBB_CLASSES.$n.".php";
	} else {
		die("Error locating library file: ".$n);
	}
}

function redirect($url){
        // Redirects to the specified page. It then exits the script.
        // @param       Type            Description
        // $url         String          The part of the URL after the slash.

        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), "/\\");
        header("Location: http://".$host.$uri.$url);
        exit;
}


// Template functions
function validTemplate($tempid){
	// Makes sure the template folder exists

	return is_dir(YAKBB_TEMPLATES.$tempid);
}


// Permission functions
function boardPermissions($boardid){
	// Get specific permissions for a specific board. All is done from the cache.
}

function catPermissions($catid){
	// Get specific permissions for a specific category. All is done from the cache.
}

function getPermissions($groupid=false){
	// Gets the permission array for a specific group

	global $yakbb;
	if($groupid === false){
		$groupid = $yakbb->user["group"];
	}

	return $yakbb->groups[$groupid];
}

function getPermission($perm, $groupid=false){
	// Gets a specific permission for a group

	$perms = getPermissions($groupid);
	if(isset($perms[$perm])){
		return $perms[$perm] == 1;
	}
	return false;
}





// Cookie functions
function setYakCookie($name, $value, $time=0){
	setcookie(DBPRE.$name, $value, $time);
}

function getYakCookie($name){
	if(isset($_COOKIE[DBPRE.$name])){
		return $_COOKIE[DBPRE.$name];
	}
	return "";
}


?>