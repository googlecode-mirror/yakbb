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


if(isset($_REQUEST["submitit"])){
	$username = secure($_REQUEST["username"]);
	$password = sha256($_REQUEST["password"]);

	if(isset($_REQUEST["username"])){
		$tp->addVar("login", array(
			"USER" => substr($username, 0, $yak->settings["username_max_length"])
		));
	}

	$error = array();
	if(strlen($username) <= $yak->settings["username_max_length"]){
		$x = $db->query("SELECT password FROM ".DBPRE."users WHERE name='".$username."'");
		if($db->numRows($x) == 1){
			$x = $db->fetch($x);
			if($x["password"] == $password){
				setcookie(DBPRE."user", $username, time()+9999999);
				setcookie(DBPRE."pass", $password, time()+9999999);
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
}


$tp->addVar("login", "REG", isset($_REQUEST["reg"]));

?>