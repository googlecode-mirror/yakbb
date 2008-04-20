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
|| File: /includes/login.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

/*	TODO
	- Session/Public login
	- Switch-users
	- For now it logs you in forever. Change to a selectable amount of time
*/

if(!defined("SNAPONE")) exit;

class login {
	private $errors = array(); // Holds the errors that were generated

	public function __construct(){
		global $guest, $yak, $tp, $lang, $plugins;

		// Make sure user can login
		if($guest == false && $yak->settings["switch_users"] == false){
			$tp->error("login_logged_in");
		}

		// Template stuff
		$tp->setTitle("login");
		$tp->loadFile("login", "login.tpl");
		$lang->learn("login");
		$tp->addNav($lang->item("nav_login"));

		$plugins->callhook("login_start");

		if(isset($_POST["submitit"]) && isset($_POST["username"]) && isset($_POST["password"])){
			$this->formSent();
		}

		$plugins->callhook("login_end");
	}

	public function formSent(){
		// Form was sent
		global $plugins, $tp, $db, $yak, $lang;

		$plugins->callhook("login_form_sent");
		$username = $db->secure(secure($_REQUEST["username"]));
		$password = sha256($_REQUEST["password"]);

		// $tp->addVar("login", array(
		// 	"USER" => substr($username, 0, $yak->settings["username_max_length"])
		// ));

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
			$error[] = "username_too_long";
		}

		// Errored
		$this->errors = $plugins->callhook("login_error", $error);
		$lang->learn("errors");
	}

	public function getErrors(){
		return $this->errors;
	}
}

?>