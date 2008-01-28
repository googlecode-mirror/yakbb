<?php

/*	TODO
	- Need to add CAPTCHA
	- Need to send registration e-mail
	- Need to work with the activation stuff
	- Need to add the JavaScript checks in for the system using AJAX and normal stuff
	- Make sure e-mail isn't valid
	- Registration script needs to be tested along with all error messages (thoroughly)
*/

if(!defined("SNAPONE")) exit;

if($guest === false){
	$tp->error("join_logged_in");
} else if($yak->settings["registration_enabled"] == false){
	$tp->error("join_registration_disabled");
}

$tp->setTitle("join");
$tp->loadFile("join", "join.tpl");
$lang->learn("join");
$tp->addNav($lang->item("join_nav"));

if(isset($_POST["submitit"])){
	// Form submitted. Let's check the information.
	$errors = array();

	$username = secure(trim($_POST["username"]));
	$display = secure(trim($_POST["displayname"]));
	$pass1 = $_POST["password1"];
	$pass2 = $_POST["password2"];
	$email1 = secure(trim($_POST["email1"]));
	$email2 = secure(trim($_POST["email2"]));
	$showemail = intval($_POST["showemail"]);
	$emailoptin = intval($_POST["emailoptin"]);


	if(empty($username)){
		$errors[] = "username_empty";
	} else if(strlen($username) > $yak->settings["username_max_length"]){
		$errors[] = "username_max_length";
	} else if(strlen($username) < $yak->settings["username_min_length"]){
		$errors[] = "username_min_length";
	}

	if(count($errors) == 0){
		// Check first to save a query, because they have to change their username if the above are false anyway
		$db->query("SELECT id FROM ".DBPRE."users WHERE name='".$db->secure($username)."' LIMIT 1");
		if($db->numRows() == 1){
			$errors[] = "username_taken";
		}
		$db->free();
	}

	if(!preg_match("/^[a-z0-9_]+$/i", $username)){
		$errors[] = "username_invalid_char";
	}


	if(empty($display)){
		$errors[] = "displayname_empty";
	} else if(strlen($display) > $yak->settings["displayname_max_length"]){
		$errors[] = "displayname_max_length";
	} else if(strlen($display) < $yak->settings["displayname_min_length"]){
		$errors[] = "displayname_min_length";
	}


	if($pass1 !== $pass2){
		$errors[] = "password_no_match";
	}

	if(empty($pass1)){
		$errors[] = "password_empty";
	} else if(strlen($pass1) < 6){
		$errors[] = "password_too_short";
	} else if($pass1 == $username){
		$errors[] = "password_username";
	} else if($pass1 == strrev($username)){
		$errors[] = "password_username_reversed";
	}


	if(empty($email1)){
		$errors[] = "email_empty";
	} else if($email1 !== $email2){
		$errors[] = "email_no_match";
	} else if(!validEmail($email1)){
		$errors[] = "email_invalid";
	} else if($yak->settings["unique_email"] == 1){
		// Gotta check for a unique e-mail if they have that enabled. 
		$db->query("SELECT id FROM ".DBPRE."users WHERE email='".$db->secure($email1)."'");
		if($db->numRows() == 1){
			$errors[] = "email_taken";
		}
		$db->free();
	}

	if(count($errors) == 0){
		// No errors.
		// Register the user and redirect to the login page with the login message

		// Generate an activation string randomly. 10 characters, may change later.
		$possibles = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-";
		$i = 0;
		$acode = "";
		while(++$i <= 10){
			$acode .= $possibles[rand(0, strlen($possibles))];
		}


		// Check $adminLoadIt stuff
		$adminLoadIt = false;
		$m = $db->fetch($db->query("SELECT count(id) AS totmem FROM ".DBPRE."users"));
		if($m["totmem"] == 0){
			$adminLoadIt = true;
		}
		$db->free();


		$db->insert("users", array(
			"id" => 0,
			"name" => $username,
			"display" => $display,
			"group" => ($adminLoadIt?1:0), // $adminLoadIt is only if the total amount of users is zero.
			"password" => sha256($pass1), // Encrypt the password
			"activated" => 0, // Will be changed later
			"activationcode" => $acode,
			"email" => $email1,
			"emailshow" => $showemail,
			"emailoptin" => $emailoptin,
			"language" => $yak->settings["default_language"],
			"template" => $yak->settings["default_template"],
			"registered" => time(),
			"lastip" => $yak->ip,
			"pmenabled" => 1, // PMs enabled by default. =P Otherwise it'd be 0 which sucks
			"timezone" => $yak->settings["defaulttimezone"],
			"dst" => $yak->settings["dst"]
		));
		redirect("?action=login&reg=1&user=".$username);
	}

	// Check failed. Let's resend the information and we'll generate the errors
	$tp->addVar("join", array(
		"USER" => substr($username, 0, $yak->settings["username_max_length"]),
		"DISPLAY" => substr($display, 0, $yak->settings["displayname_max_length"]),
		"EMAIL" => $email1,
		"CEMAIL" => $email2,
		"SHOWEMAIL" => !!$showemail,
		"EMAILOPTIN" => !!$emailoptin,
		"ERRORS" => array_map(array($lang, "item"), $errors)
	));
} else {
	// Add these to prevent notice errors.
	$tp->addVar("join", array(
		"USER" => "",
		"DISPLAY" => "",
		"EMAIL" => "",
		"CEMAIL" => "",
		"SHOWEMAIL" => false,
		"EMAILOPTIN" => false,
		"ERRORS" => array()
	));
}
?>