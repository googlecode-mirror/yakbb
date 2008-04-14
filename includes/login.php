<?php

/*	TODO
	- Session/Public login
	- Switch-users
	- For now it logs you in forever. Change to a selectable amount of time
*/

if(!defined("SNAPONE")) exit;

class login {
	function __construct(){
		global $guest, $yak, $tp, $lang, $plugins;

		// Make sure user can login
		if($guest == false && $yak->settings["switch_users"] == false){
			$tp->error("login_logged_in");
		}

		// Template stuff
		$tp->setTitle("login");
		$tp->loadFile("login", "login.tpl", array(
			"errors" => array(),
			"REG" => isset($_REQUEST["reg"]) // Show registration message if it's set to be shown
		));
		$lang->learn("login");
		$tp->addNav($lang->item("nav_login"));

		$plugins->callhook("login_start");

		if(isset($_REQUEST["submitit"]) && isset($_REQUEST["username"]) && isset($_REQUEST["password"])){
			$this->formSent();
		}

		$plugins->callhook("login_end");
	}

	function formSent(){
		// Form was sent
		global $plugins, $tp, $db, $yak, $lang;

		$plugins->callhook("login_form_sent");
		$username = $db->secure(secure($_REQUEST["username"]));
		$password = sha256($_REQUEST["password"]);

		$tp->addVar("login", array(
			"USER" => substr($username, 0, $yak->settings["username_max_length"])
		));

		$error = array();
		libraryLoad("validation.lib");
		if(empty($username)){
			$error[] = "username_empty";
		} else if(strlen($username) < $yak->settings["username_max_length"]){
			$x = $db->query("
				SELECT
					password
				FROM
					".DBPRE."users
				WHERE
					name='".$username."'
				LIMIT 1
			");
			if($db->numRows($x) == 1){
				$x = $db->fetch($x);
				if($x["password"] == $password){
					// Login successful
					$plugins->callhook("login_success_start");
		
					setcookie(DBPRE."user", $username, time()+9999999);
					setcookie(DBPRE."pass", $password, time()+9999999);

					$plugins->callhook("login_success_end");

					redirect("?");
				} else {
					$error[] = "wrong_password";
				}
			} else {
				$error[] = "username_doesnt_exist";
			}
		} else {
			$error = "username_too_long";
		}

		// Errored
		$error = $plugins->callhook("login_error", $error);
		$lang->learn("errors");
		$tp->addVar("login", array(
			"errors" => array_map(array($lang, "item"), $error)
		));
	}
}

?>