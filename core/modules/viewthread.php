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
|| File: /core/modules/viewthread.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

/*	TODO
	- Need to check parent category permissions
	- Need to check parent board permissions
		- Need to look if in sub-board
	- Need to add a nav tree
	- Need to add pagination to threads
	- Need to forward permissions for buttons and attachments
	- Add subscription management info
	- Need to add support for showing posts by guests
	- Need to make views count go up with each view
	- Need to mark thread as read if not already read
*/

class viewthread {
	private $threadid = 0;
	private $tdata = array();
	private $posts = array();

	public function init(){
		global $yakbb;

		$yakbb->loadLanguageFile("viewthread");

		// Get and validate thread ID
		$this->threadid = intval($_GET["thread"]); // Force integer value
		if($this->threadid == 0){
			$yakbb->error(2, "invalid_thread_id");
		}

		// Need to check if thread is in the database and load data if so.
		$yakbb->db->query("
			SELECT
				*
			FROM
				yakbb_threads
			WHERE
				id='".$this->threadid."'
			LIMIT
				1
		");
		if($yakbb->db->numRows() == 0){
			$yakbb->error(2, "thread_doesnt_exist");
		}
		$this->tdata = $yakbb->db->fetch();

		// Check some permissions
		$perms = boardPermissions($this->tdata["parentid"]);
		if($perms["view"] == false){
			$yakbb->error(2, "perms_cant_view_board");
		}

		// Load posts
		$yakbb->db->query("
			SELECT
				p.id AS postid, p.*,
				u.*
			FROM
				yakbb_posts p
			LEFT JOIN
				yakbb_users u
				ON (p.userid = u.id)
			WHERE
				p.threadid='".$this->threadid."'
			ORDER BY
				p.timestamp ASC
			LIMIT
				15
		");
		$this->posts = array();
		while($p = $yakbb->db->fetch()){
			$p["userlink"] = link_user($p["userid"], $p["username"], $p["displayname"], $p["group"]);
			$p["message"] = $yakbb->parser->parse($p["message"]);
			$this->posts[] = $p;
		}

		// Raise view count if not thread creator
		if($this->tdata["creatorid"] == $yakbb->user["id"]){
			$yakbb->db->query("
				UPDATE
					yakbb_threads
				SET
					views=views+1
				WHERE
					id = '".$this->threadid."'
				LIMIT
					1
			");
		}

		// Template stuff
		$yakbb->smarty->assign("viewcount", $this->tdata["views"]+1);
		$yakbb->smarty->assign("threadid", $this->threadid);
		$yakbb->smarty->assign("page_title", $this->tdata["name"]);
		$yakbb->smarty->assign("posts", $this->posts);
		$yakbb->loadTemplate("viewthread.tpl");
	}
}

?>