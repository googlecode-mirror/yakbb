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
|| File: /library/validation.lib.php
|| File Version: v0.1.0a
|| $Id: global.php 64 2008-04-14 15:32:04Z cddude229 $
\*==================================================*/

/*	TODO
	validEmail()
		- Needs to be finished to actually check e-mail
	validUsername()
		- Make sure isn't censored word
	validDisplayname()
		- Make sure isn't censored word
*/

if(!defined("SNAPONE")) exit;

function validEmail($email1, $email2){
	// Confirms if it is a valid e-mail or not

	global $yak, $db;
	$errors = array();

	if(empty($email1)){
		$errors[] = "email_empty";
	// } else if(!validEmail($email1)){
	// 	$errors[] = "email_invalid";
	} else if($email1 !== $email2){
		$errors[] = "email_no_match";
	} else if($yak->settings["unique_email"] == 1){
		// Gotta check for a unique e-mail if the setting is enabled. 
		$db->query("SELECT id FROM ".DBPRE."users WHERE email='".$db->secure($email1)."' LIMIT 1");
		if($db->numRows() == 1){
			$errors[] = "email_taken";
		}
		$db->free();
	}

	return (count($errors) == 0?true:$errors);
}



function validUsername($username, $reg=false){
	// Confirms if the username is valid or not.
	// $reg is whether or not it's running on the register page

	global $yak, $db;
	$errors = array();

	if(empty($username)){
		$errors[] = "username_empty";
	} else if(strlen($username) > $yak->settings["username_max_length"]){
		$errors[] = "username_max_length";
	} else if(strlen($username) < $yak->settings["username_min_length"]){
		$errors[] = "username_min_length";
	}

	if(!preg_match("/^[a-z0-9_]+$/i", $username)){
		$errors[] = "username_invalid_char";
	}

	if(count($errors) == 0 && $reg == true){
		// Check first to save a query, because they have to change their username if the above are false anyway
		$db->query("
			SELECT
				u.id
			FROM
				".DBPRE."users u
			WHERE
				u.name='".$db->secure($username)."'
			LIMIT 1
		");
		if($db->numRows() == 1){
			$errors[] = "username_taken";
		}
		$db->free();
	}

	return (count($errors) == 0?true:$errors);
}



function validPassword($pass1, $pass2, $username=false){
	// Confirms if the password is valid. (Usually is)

	global $yak, $user;
	$errors = array();

	if($username === false){
		$username = $user["name"];
	}

	if($pass1 !== $pass2){
		$errors[] = "password_no_match";
	}

	if(empty($pass1)){
		$errors[] = "password_empty";
	} else if(strlen($pass1) < 6){
		$errors[] = "password_too_short";
	}

	if($pass1 == $username){
		$errors[] = "password_username";
	} else if($pass1 == strrev($username)){
		$errors[] = "password_username_reversed";
	}

	return (count($errors) == 0?true:$errors);
}



function validDisplayname($display){
	// Confirms if the display is valid

	global $yak;
	$errors = array();

	if(empty($display)){
		$errors[] = "displayname_empty";
	} else if(strlen($display) > $yak->settings["displayname_max_length"]){
		$errors[] = "displayname_max_length";
	} else if(strlen($display) < $yak->settings["displayname_min_length"]){
		$errors[] = "displayname_min_length";
	}

	return (count($errors) == 0?true:$errors);
}



function validTitle($title){
	// Confirms if a thread/pm title is valid

	global $yak;
	$errors = array();

	if(strlen($title) < 1 || empty($title)){
		$errors[] = "title_empty";
	} else if(strlen($title) > $yak->settings["thread_subject_max"]){
		$errors[] = "title_too_long";
	}

	return (count($errors) == 0?true:$errors);
}



function validMessage($message){
	// Confirms that a message is valid
	
	global $yak;
	$errors = array();

	if(strlen($message) < 1 || empty($message)){
		$errors[] = "message_empty";
	} else if(strlen($message) > $yak->settings["thread_message_max"]){
		$errors[] = "message_too_long";
	}

	return (count($errors) == 0?true:$errors);
}



function validDescription($desc){
	// Confirms that a description is valid

	global $yak;
	$errors = array();

	if(strlen($desc) > $yak->settings["thread_desc_max"]){
		$errors[] = "desc_too_long";
	}

	return (count($errors) == 0?true:$errors);
}
?>