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
|| File: /core/modules/register.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

/*	TODO
	- Needs to fill in incorrect registration information
	- Check to see if username is taken
	- Check to see if e-mail is taken (based on setting)
	- Implement account activation
	- Implement e-mail activation
	- Implement account pending
	- Send registration e-mail
	- Display registration errors
*/

class register {
	private $errors = array();

	public function init(){
		global $yakbb;

		$yakbb->loadLanguageFile("register");

		if(isset($_POST["submit2"])){
			$this->validateRegistration();
		}

		$yakbb->smarty->assign("page_title", $yakbb->getLang("page_title"));

		$yakbb->loadTemplate("register.tpl");
	}

	private function validateRegistration(){
		loadLibrary("validation.lib");
		$user = secure($_POST["username"]);
		$display = secure($_POST["display"]);
		$pass1 = secure($_POST["pass1"]);
		$pass2 = secure($_POST["pass2"]);
		$email1 = secure($_POST["email1"]);
		$email2 = secure($_POST["email2"]);

		$res = valid_username($user);
		if($res !== true){
			$this->errors[] = $res;
		}

		$res = valid_displayname($display);
		if($res !== true){
			$this->errors[] = $res;
		}

		if($pass1 !== $pass2){
			$this->errors[] = "passwords_dont_match";
		} else {
			$res = valid_password($pass1);
			if($res !== true){
				$this->errors[] = $res;
			}
		}

		if($email1 !== $email2){
			$this->errors[] = "emails_dont_match";
		} else {
			$res = valid_email($email1);
			if($res !== true){
				$this->errors[] = $res;
			}
		}

		// Validate these next two for the most protective method.
		if($_POST["hideemail"] == "no"){
			$hideemail = false;
		} else {
			$hideemail = true;
		}

		if($_POST["receiveemail"] == "yes"){
			$receiveemail = true;
		} else {
			$receiveemail = false;
		}

		// Check ToS box
		if(!$_POST["tos"]){
			$this->errors[] = "tos_not_checked";
		}

		if(count($this->errors) == 0){
			// Add the user
			global $yakbb;
			$yakbb->db->insert("users", array(
				"id" => 0,
				"username" => $user,
				"displayname" => $display,
				"password" => sha256($pass1),
				"email" => $email1,
				"emailshow" => ($hideemail?0:1),
				"emailoptin" => ($receiveemail?1:0),
				"activated" => 1,
				"activationcode" => "",
				"pending" => 0,
				"registeredtime" => time(),
				"lastip" => $yakbb->ip,
				"template" => $yakbb->config["default_template"],
				"language" => $yakbb->config["default_language"],
				"timezone" => $yakbb->config["default_timezone"]
			));
			redirect("?action=login&reg=true");
		}
	}
}

?>