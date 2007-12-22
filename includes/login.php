<?php

/*	TODO
	- Session/Public login
	- Switch-users
	- For now it logs you in forever. Change to a selectable amount of time
	- Need to add the errors to the login.tpl file
*/

if(!defined("SNAPONE")) exit;

if($guest == false && $yak->settings["switch_users"] == false){
	$tp->error("login_logged_in");
}

$tp->setTitle("login");
$tp->loadFile("login", "login.tpl");
$lang->learn("login");
$plugins->callhook("login_start");


if(isset($_REQUEST["submitit"]) && isset($_REQUEST["username"]) && isset($_REQUEST["password"])){
	$plugins->callhook("login_form_sent");
	$username = secure($_REQUEST["username"]);
	$password = sha256($_REQUEST["password"]);

	$tp->addVar("login", array(
		"USER" => substr($username, 0, $yak->settings["username_max_length"])
	));

	$error = array();
	if(strlen($username) <= $yak->settings["username_max_length"]){
		$x = $db->query("SELECT password FROM ".DBPRE."users WHERE name='".$username."'");
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
		$error[] = "username_too_long";
	}

	// Errored
	$error = $plugins->callhook("login_error", $error);
}


$tp->addVar("login", "REG", isset($_REQUEST["reg"]));

$plugins->callhook("login_end");

?>