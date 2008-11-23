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
|| File: /core/includes/validation.lib.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

/*	TODO
	- Complete valid_password
	- Complete valid_email
*/

defined("YAKBB") or die("Security breach.");

function valid_username($username){
	if(!preg_match("/^[A-Z0-9_-]+$/i", $username)){
		return "username_invalid_characters";
	}

	global $yakbb;
	$len = strlen($username);
	if($len < $yakbb->config["username_min_length"]){
		return "username_too_short";
	} else if ($len > $yakbb->config["username_max_length"]){
		return "username_too_long";
	}

	return true;
}

function valid_displayname($display){
	global $yakbb;
	$len = strlen($display);
	if($len < $yakbb->config["displayname_min_length"]){
		return "displayname_too_short";
	} else if ($len > $yakbb->config["displayname_max_length"]){
		return "displayname_too_long";
	}

	return true;
}

function valid_password($password){
	return true;
}

function valid_email($email){
	return true;
}

?>