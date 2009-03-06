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
|| File: /core/includes/insertion.lib.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

defined("YAKBB") or die("Security breach.");

/*	TODO
	insert_post()
	insert_thread()
	insert_board()
	insert_category()
	insert_pm()
	insert_user()
		- Implement activation code portion
		- Implement admin approval portion
*/

function insert_post($postdat){
	// Creates a new post on the forum
}



function insert_thread($threaddat){
	// Creates a new thread on the forum
}



function insert_board($boarddat){
	// Creates a new board on the forum
}



function insert_category($catdat){
	// Creates a new category on the forum
}



function insert_pm($pmdat){
	// Creates a new PM on the forum... may be broken into multiple functions
}



function insert_user($userdat){
	// Creates a new user on the forum

	global $yakbb;

	// List fields that this function can provide.
	$valid_fields = array(
		"username", "displayname", "password", "email", "emailshow", "emailoptin"
	);
	$required_fields = array(
		"username", "password", "email"
	);

	// Validate that ONLY these fields are provided. Then, validate required fields
	$fields_provided = array_keys($userdat);
	foreach($fields_provided as $k => $item){
		if(!in_array($item, $valid_fields)){
			unset($userdat[$item]); // Remove the invalid item
		}
	}

	foreach($required_fields as $k => $item){
		if(!in_array($item, $fields_provided)){
			record_yakbb_error("Missed field \"".$item."\" in call to insert_user().");
			return false;
		}
	}

	// Set the data that will ALWAYS be this way
	$userdat["group"]          = 0;
	$userdat["activated"]      = 1;
	$userdat["activationcode"] = ""; // Sent via e-mail 
	$userdat["pending"]        = 0; // Admin approval required?
	$userdat["registeredtime"] = time();
	$userdat["lastip"]         = $yakbb->ip;
	$userdat["template"]       = $yakbb->config["default_template"];
	$userdat["language"]       = $yakbb->config["default_language"];
	$userdat["timezone"]       = $yakbb->config["default_timezone"];

	// Set the data that is optional. intval() is used to force integer value upon certain ones
	$userdat["emailshow"]   = (isset($userdat["emailshow"])?intval($userdat["emailshow"]):0);
	$userdat["emailoptin"]  = (isset($userdat["emailoptin"])?intval($userdat["emailoptin"]):0);
	$userdat["displayname"] = (isset($userdat["displayname"])?$userdat["displayname"]:$userdat["username"]);

	// Validate inputted data
	if(!function_exists("valid_username")){
		loadLibrary("validation.lib");
	}

	$errors = array();

	$res = valid_username($userdat["username"]);
	if($res !== true){
		$errors[] = $res;
	}

	$res = valid_displayname($userdat["displayname"]);
	if($res !== true){
		$errors[] = $res;
	}

	$res = valid_password($userdat["password"]);
	if($res !== true){
		$errors[] = $res;
	}

	$res = valid_email($userdat["email"]);
	if($res !== true){
		$errors[] = $res;
	}

	if(count($errors) == 0){
		$yakbb->db->insert("users", $userdat);
		return true;
	} else {
		return $errors;
	}
}

?>