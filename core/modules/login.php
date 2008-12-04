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
|| File: /core/modules/login.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

/*	TODO
	- Implement "Login as Invisible"
	- Implement "Login for..."
	- Implement error reporting
*/

class login {
	private $errors = array();

	public function init(){
		global $yakbb;

		$yakbb->loadLanguageFile("login");

		if(isset($_POST["submit2"])){
			$this->validate();
		}

		$yakbb->smarty->assign("page_title", $yakbb->getLang("page_title"));
		$yakbb->smarty->display("login.tpl");
	}

	private function validate(){
		loadLibrary("validation.lib");
		$user = secure($_POST["username"]);
		$pass = $_POST["password"];

		$reg = valid_username($user);
		if($reg !== true){
			$this->errors[] = $reg;
		}

		$reg = valid_password($pass);
		if($reg !== true){
			$this->errors[] = $reg;
		}

		if(count($this->errors) == 0){
			// Check actual login data now
			global $yakbb;
			$yakbb->db->query("
				SELECT
					password
				FROM
					yakbb_users
				WHERE
					username = '".$yakbb->db->secure($user)."'
				LIMIT
					1
			");
			$x = $yakbb->db->fetch();
			if($yakbb->db->numRows() == 0){
				$this->errors[] = "user_doesnt_exist";
			} else if(sha256($pass) !== $x["password"]){
				$this->errors[] = "password_incorrect";
			} else {
				// Login
				setYakCookie("username", $user, time()+60*60*24*180);
				setYakCookie("password", sha256($pass), time()+60*60*24*180);
				redirect("?");
			}
		}
	}
}

?>