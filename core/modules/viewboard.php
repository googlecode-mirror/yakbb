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
|| File: /core/modules/viewboard.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

/*	TODO
	- Need to check parent category permissions
	- Need to add sub-boards support
		- Need to check parent boards permissions if in subboard
	- Need to add a nav tree
	- Need to add thread sorting options
	- Need to add pagination to threads
	- Need to load user info for threads
	- Need to load new/viewed info for threads
	- Need to forward permissions for buttons and attachments
	- Add subscription management info
	- Need to mark board as read if not already
*/

class viewboard {
	private $boardid = 0;
	private $bdata = array();
	private $threads = array();

	public function init(){
		global $yakbb;

		$yakbb->loadLanguageFile("viewboard");

		// Get and validate board ID
		$this->boardid = intval($_GET["board"]); // Force integer value
		if($this->boardid == 0){
			$yakbb->error(1, "invalid_board_id");
		}

		// Need to check if board is in the database and load data if so.
		$yakbb->db->query("
			SELECT
				*
			FROM
				yakbb_boards
			WHERE
				id='".$this->boardid."'
			LIMIT
				1
		");
		if($yakbb->db->numRows() == 0){
			$yakbb->error(1, "board_doesnt_exist");
		}
		$this->bdata = $yakbb->db->fetch();

		// Check some permissions
		$perms = boardPermissions($this->boardid);
		if($perms["view"] == false){
			$yakbb->error(1, "perms_cant_view_board");
		}

		// Load threads
		$yakbb->db->query("
			SELECT
				*
			FROM
				yakbb_threads
			WHERE
				parentid='".$this->boardid."'
			ORDER BY
				lastposttime DESC
			LIMIT
				30
		");
		$this->threads = array();
		while($t = $yakbb->db->fetch()){
			$t["link"] = url_thread($t["id"], $t["name"]);
			$this->threads[] = $t;
		}

		// Template stuff
		$yakbb->smarty->assign("boardid", $this->boardid);
		$yakbb->smarty->assign("page_title", $this->bdata["name"]);
		$yakbb->smarty->assign("threads", $this->threads);
		$yakbb->loadTemplate("viewboard.tpl");
	}
}

?>