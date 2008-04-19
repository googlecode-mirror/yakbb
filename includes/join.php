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
|| File: /includes/join.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

/*	TODO
	- Need to add CAPTCHA
	- Need to send an e-mail to the given one
	- Need to work with the activation stuff
	- Need to add the JavaScript checks in for the system using AJAX and normal stuff
	- Make sure e-mail isn't valid
	- Registration script needs to be tested along with all error messages (thoroughly)
	- Needs to clear the cached member count
	- Make sure e-mail and username aren't banned or in reserved
*/

if(!defined("SNAPONE")) exit;

class join {
	public function __construct(){
		global $tp, $lang, $guest, $yak;

		if($guest === false){
			$tp->error("join_logged_in");
		} else if($yak->settings["registration_enabled"] == false){
			$tp->error("join_registration_disabled");
		}

		$tp->setTitle("join");
		$tp->loadFile("join", "join.tpl", array(
			"USER" => "",
			"DISPLAY" => "",
			"EMAIL" => "",
			"CEMAIL" => "",
			"SHOWEMAIL" => false,
			"EMAILOPTIN" => false,
			"ERRORS" => array()
		));
		$lang->learn("join");
		$tp->addNav($lang->item("join_nav"));

		if(isset($_REQUEST["submitit"])){
			$this->formSent();
		}
	}



	public function formSent(){
		global $db, $tp, $lang, $yak;

		libraryLoad("validation.lib");
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


		// Username
		$uCheck = validUsername($username, true);
		if($uCheck !== true){
			$errors = array_merge($errors, $uCheck);
		}

		// Displayname
		$dCheck = validDisplayname($display);
		if($dCheck !== true){
			$errors = array_merge($errors, $dCheck);
		}

		// Password
		$pCheck = validPassword($pass1, $pass2, $username);
		if($pCheck !== true){
			$errors = array_merge($errors, $pCheck);
		}

		// E-mail
		$eCheck = validEmail($email1, $email2);
		if($eCheck !== true){
			$errors = array_merge($errors, $eCheck);
		}

		if(count($errors) == 0){
			// No errors.
			// Register the user and redirect to the login page with the login message
	
			// Generate an activation string randomly. 10 characters, may change later.
			$possibles = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-";
			$i = 0;
			$le = strlen($possibles);
			$acode = "";
			while(++$i <= 10){
				$acode .= $possibles[rand(0, $le)];
			}

			// Check $adminLoadIt stuff
			$adminLoadIt = false;
			$m = $db->fetch($db->query("
				SELECT
					count(id) AS totmem
				FROM
					".DBPRE."users
			"));
			if($m["totmem"] == 0){
				$adminLoadIt = true;
			}
			$db->free();

			// Insert data
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
			$db->clearCacheQuery("stats/member_count");
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
	}
}
?>