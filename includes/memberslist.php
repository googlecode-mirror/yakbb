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
|| File: /includes/memberslist.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

if(!defined("SNAPONE")) exit;

/*	TODO
	- Staff view option
	- Top posters option (different from sort by column?)
	- Column sort
	- Pagination
	- Make rank be shown
*/

class memberslist {
	private $members = array();

	public function __construct(){
		global $tp, $lang, $user;

		// Make sure they can view
		if($user["id"] == 0 && getSetting("guests_view_memberslist") == false){
			$tp->error("guest_memberslist");
		}

		// If so, learn the language and then call the specific sections
		$lang->learn("memberslist");
		if($_GET["action"] == "members"){
			$this->membersList();
		}

		$tp->loadFile("ml", "memberslist.tpl");
	}

	private function membersList(){
		global $tp, $lang, $db;
		$tp->setTitle("memberslist");
		$tp->addNav($lang->item("members_nav"));

		$q = $db->query("
			SELECT
				u.*
			FROM
				yakbb_users u
			ORDER BY
				display ASC
		");

		$this->members = array();
		while($m = $db->fetch($q)){
			$m["posts"] = number_format($m["posts"]);
			$this->members[] = $m;
		}
	}









	// TEMPLATE FUNCTIONS
	public function getMembers(){
		return $this->members;
	}
}

?>