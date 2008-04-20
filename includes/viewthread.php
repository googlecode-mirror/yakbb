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
|| File: /includes/viewthread.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

/*	TODO
	- Check viewboard passwords
	- Pagination
	- Quick reply
	- Buttons
		- Set if user can reply
		- Send topic to friend
		- Print thread
	- Last edit
	- Load attachments if the post has any.
		- This should be cached since it shouldn't be editted, unless removing, but
		that would cause a lot of cache files... >.< We'll decide later.
	- Add option to make views only go up if NOT the thread creator
	- Everything else. =P
*/

if(!defined("SNAPONE")) exit;

class viewthread {
	private $tid = 0;
	private $tdata = array();
	private $posts = array();

	public function __construct(){
		global $tp, $db, $parser;

		// Thread ID
		$this->tid = intval($_GET["thread"]);
		if($this->tid <= 0){
			$tp->error("viewthread_invalid_id");
		}

		// Check thread existance
		$thread = $db->query("
			SELECT
				t.*
			FROM
				".DBPRE."threads t
			WHERE
				t.id='".$this->tid."'
			LIMIT 1
		");
		if($db->numRows() == 0){
			$tp->error("viewthread_doesnt_exist");
		}
		$this->tdata = $db->fetch();
		$db->free();

		// Update views count
		$db->query("UPDATE ".DBPRE."threads SET views=views+1 WHERE id='".$this->tid."'");

		// Make nav tree (boards and cats only) and check permissions for parent boards
		$this->compileNavTree();

		// Template stuff
		$tp->addNav(threadLink($this->tdata));
		$tp->setTitle($this->tdata["title"], false);
		$tp->loadFile("thread", "viewthread.tpl");

		// Load posts
		$pq = $db->query("
			SELECT
				p.*, p.id AS postid,
				u.*
			FROM
				".DBPRE."posts p
			LEFT JOIN 
				".DBPRE."users u ON (u.id = p.userid)
			WHERE
				p.threadid='".$this->tid."'
			ORDER BY postid ASC
		");
		$this->posts = array();
		while($p = $db->fetch()){
			$this->posts[] = array(
				"pid" => $p["postid"],
				"ptitle" => $p["title"],
				"time" => $p["timestamp"],
				"date" => makeDate($p["timestamp"]),
				"userlink" => userLink($p),
				"message" => $parser->parse($p["message"], $p["disablesmilies"] == 0, $p["disableubbc"] == 0)
			);
		}
		$db->free();
	}

	private function compileNavTree(){
		// Compiles the nav tree (boards and cats only) and checks permissions

		global $db, $tp, $user;

		$curboard = array(
			"parentid" => $this->tdata["boardid"],
			"parenttype" => "b"
		);
		$boards = array();
		while(true){
			if($curboard["parenttype"] == "c"){
				break;
			}
			$curboard = $db->cacheQuery("
				SELECT
					b.*
				FROM
					".DBPRE."boards b
				WHERE
					b.id='".$curboard["parentid"]."'
				LIMIT 1
			", "board_data/".$curboard["parentid"]);
			$curboard = $curboard[0]; // We only want the first result... despite there being only one.
			$boards[] = $curboard;
		}

		if($curboard["parentid"] != 0){
			// Load the category if the ID isn't 0. (If it is zero, we don't really have a category. =P)
			$cat = $db->cacheQuery("
				SELECT
					c.*
				FROM
					".DBPRE."categories c
				WHERE
					c.id='".$curboard["parentid"]."'
				LIMIT 1
			", "category_data/".$curboard["parentid"]);
			$cat = $cat[0]; // Only want the first result... despite there being only one.

			// Check view permissions
			$cperms = unserialize($cat["permissions"]);
			if(!isset($cperms[$user["group"]]) || $cperms[$user["group"]]["view"] == false){
				$tp->error("viewboard_no_permissions");
			}
			unset($cperms);

			// Add nav, etc.
			$tp->addNav(catLink($cat));
			unset($cat);
		}
		unset($curboard);

		// boards
		$boards = array_reverse($boards);
		foreach($boards as $k => $v){
			// Check view permissions and then add nav.
			$bperms = unserialize($v["permissions"]);
			if(!isset($bperms[$user["group"]]) || $bperms[$user["group"]]["view"] == false){
				$tp->error("viewboard_no_permission");
			}
			$tp->addNav(boardLink($v));
		}
		unset($boards);
	}
}

?>