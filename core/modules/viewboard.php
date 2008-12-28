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
	- Need to load new/viewed info for threads
	- Need to forward permissions for buttons and attachments
	- Add subscription management info
	- Need to mark board as read if not already
*/

class viewboard {
	private $boardid = 0;
	private $bdata   = array();
	private $threads = array();

	public function init(){
		global $yakbb;

		$yakbb->loadLanguageFile("viewboard");

		// Get and validate board ID
		$this->boardid = intval($_GET["board"]); // Force integer value
		if($this->boardid == 0){
			$yakbb->error(2, "invalid_board_id");
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
			$yakbb->error(2, "board_doesnt_exist");
		}
		$this->bdata = $yakbb->db->fetch();

		// Check some permissions
		$perms = boardPermissions($this->boardid);
		if($perms["view"] == false){
			$yakbb->error(2, "perms_cant_view_board");
		}

		// Calculate pagination and then load threads
		$showpagination = false;
		$totalpages = 1;
		if($this->bdata["threads"] > 0){
			// Don't load threads if no posts/threads. We'll still load announcements

			// Load pagination
			$currentpage = (isset($_GET["page"]) && intval($_GET["page"]) > 0?intval($_GET["page"]):1);
			if($this->bdata["threads"] > $yakbb->config["threads_per_page"]){
				$showpagination = true;
				$totalpages = ceil($this->bdata["threads"]/$yakbb->config["threads_per_page"]);
				if($currentpage > $totalpages){
					$yakbb->error(2, "viewboard_page_doesnt_exist");
				}
			} else {
				$totalpages = 1;
			}
			$yakbb->db->query("
				SELECT
					t.*,
					u.username, u.displayname, u.group,
					lpu.username AS lpusername, lpu.displayname AS lpdisplay, lpu.group AS lpgroup
				FROM
					yakbb_threads t
				LEFT JOIN
					yakbb_users u
					ON (u.id = t.creatorid)
				LEFT JOIN
					yakbb_users lpu
					ON (u.id = lastpostuser)
				WHERE
					t.parentid = '".$this->boardid."'
				ORDER BY
					t.lastposttime DESC,
					t.id DESC
				LIMIT
					".(($currentpage-1)*$yakbb->config["threads_per_page"]).", ".$yakbb->config["threads_per_page"]."
			");
			$this->threads = array();
			while($t = $yakbb->db->fetch()){
				$t["link"] = link_thread($t["id"], $t["name"]);
				$t["starterlink"] = link_user($t["creatorid"], $t["username"], $t["displayname"], $t["group"]);
				$t["lpuserlink"] = link_user($t["lastpostuser"], $t["lpusername"], $t["lpdisplay"], $t["lpgroup"]);
				$t["lpdate"] = makeDate($t["lastposttime"]);
				$this->threads[] = $t;
			}
		}

		// Template stuff
		$yakbb->smarty->assign("showpagination", $showpagination);
		$yakbb->smarty->assign("totalpages", $totalpages);
		$yakbb->smarty->assign("boardid", $this->boardid);
		$yakbb->smarty->assign("page_title", $this->bdata["name"]);
		$yakbb->smarty->assign("threads", $this->threads);
		$yakbb->loadTemplate("viewboard.tpl");
	}
}

?>