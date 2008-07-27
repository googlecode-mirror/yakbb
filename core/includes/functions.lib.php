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