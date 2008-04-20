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
|| File: /includes/viewboard.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

/*	TODO
	- Thread listing
		- Redirect threads go through tracker??
		- Pagination
		- Order by lastpost instead of timestamp
			-> lporder actually; this will work better with the bump feature
		- Sticky and announcements list
		- New/no new replies info
		- Add commas to views and replies
	- CHECK PASSWORDS
	- Sub-boards listing
	- Mark board as read
	- Test viewing permissions
*/

if(!defined("SNAPONE")) exit;

class viewboard {
	private $bid=0;
	private $threads=array();

	public function __construct(){
		global $tp, $lang, $db;

		$tp->loadFile("board", "viewboard.tpl");
		$lang->learn("viewboard");
		$tp->setTitle("viewboard");

		if(!isset($_GET["board"]) || !is_numeric($_GET["board"]) || $_GET["board"] < 1){
			$tp->error("viewboard_invalid_id");
		}

		$this->bid = intval($_GET["board"]);

		// Make sure board exists
		$boarddat = $db->cacheQuery("
			SELECT
				b.*
			FROM
				".DBPRE."boards b
			WHERE
				b.id='".$this->bid."'
			LIMIT 1",
		"board_data/".$this->bid);
		if(count($boarddat) == 0){
			$db->clearCacheQuery("board_data/".$this->bid);
			$tp->error("viewboard_doesnt_exist");
		}
		$boarddat = $boarddat[0]; // First result only. This is a cache issue... I maybe should rework that. =P

		// Compile the nav tree and also check the viewing permissions for parent boards
		$this->compileNavTree($boarddat);

		$tp->addNav(boardLink($boarddat));
		$tp->setTitle($boarddat["name"], false);

		$this->loadThreads();
	}

	private function compileNavTree($curboard){
		// Compiles the nav tree when viewing a board
		// This also checks if they can view the parent boards

		global $db, $tp, $user;
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

			// Check view perms
			$catperms = unserialize($cat["permissions"]);
			if(!isset($catperms[$user["group"]]) || $catperms[$user["group"]]["view"] == false){
				$tp->error("viewboard_no_permissions");
			}

			// Add nav and cleanup
			$tp->addNav(catLink($cat));
		}

		// Boards
		$boards = array_reverse($boards);
		foreach($boards as $k => $v){
			// Check view permissions and then add nav
			$boardperms = unserialize($v["permissions"]);
			if(!isset($boardperms[$user["group"]]) || $boardperms[$user["group"]]["view"] == false){
				$tp->error("viewboard_no_permission");
			}
			$tp->addNav(boardLink($v));
		}
	}

	private function loadThreads(){
		// Loads the thread listing

		global $db;

		$this->threads = array();
		$db->query("
			SELECT
				t.*
			FROM
				".DBPRE."threads t
			WHERE
				t.boardid='".$this->bid."'
			ORDER BY
				timestamp DESC
		");

		while($t = $db->fetch()){
			$this->threads[] = array(
				"id" => $t["id"],
				"creationtime" => $t["timestamp"],
				"creationdate" => makeDate($t["timestamp"]),
				"title" => $t["title"],
				"name" => $t["title"],
				"description" => $t["description"],
				"creator" => $t["creatorid"],
				"board" => $t["boardid"],
				"replies" => $t["replies"],
				"views" => $t["views"],
				"icon" => "",
				"announcement" => false,
				"sticky" => $t["sticky"] == 1,
				"locked" => $t["locked"] == 1,
				"redirect" => (strlen($t["redirecturl"])>0?$t["redirecturl"]:false),
				"pages" => ""
			);
		}
	}










	// TEMPLATE FUNCTIONS
	public function getBoardId(){
		// Returns the current board's ID

		return $this->bid;
	}

	public function getThreads(){
		// Returns the thread list

		return $this->threads;
	}
}

?>