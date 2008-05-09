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
|| File: /includes/home.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

/*	TODO
	- Last post data
	- Moderators list (if enabled)
	- Sub-boards list (if enabled)
	- On/off data
	- Sub-board data affects parents' data (if enabled)
	- Hide/show for categories
	- Add support for viewing category 0 on the main page
	- Checking the viewing permissions for categories
*/

if(!defined("SNAPONE")) exit;

class home {
	// Category stuff
	private $cats = array(); // Category data holder
	private $catids = array(); // List of category IDs
	private $singleCat = false; // Whether or not only a single category is being loaded

	// Boards stuff
	private $boards = array(); // Board data holder



	// Begin functions
	public function __construct(){
		global $tp, $lang, $plugins, $yak;

		$tp->setTitle("home");
		$tp->loadFile("home", "home.tpl");
		$lang->learn("home");
		$plugins->callhook("homestart");

		// Categories
		$this->loadCats();
		$plugins->callhook("home_categories_loaded");

		// Boards
		$this->loadBoards();

		// Load the IC stats
		if($this->singleCat != true){
			$yak->loadIC();
		}

		$plugins->callhook("home_end");
	}



	private function loadCats(){
		// Load the categories
		global $tp, $lang, $db, $plugins, $user;

		// Extra info for single category stuff
		$extra = $extra2 = "";
		if(isset($_GET["cat"])){
			$c = intval($_GET["cat"]);
			if($c > 0){
				$extra = " AND id='".$c."'";
				$extra2 = "_".$c;
				$this->singleCat = true;
			} else {
				$tp->error("category_zero");
			}
		}

		// Query DB (or cache)
		$this->cats = $db->cacheQuery("
			SELECT
				c.*
			FROM
				yakbb_categories c
			WHERE
				c.showmain='1'".$extra."
			ORDER BY
				`order` ASC
		", -1, "categories_main".$extra2);

		// Make sure the category exists.
		if(count($this->cats) == 0 && $this->singleCat === true){
			$db->clearCacheQuery("categories_main".$extra2);
			$tp->error("cat_no_exist");
		}

		// Set the category IDs array and take care of the single cat stuff
		if($this->singleCat === true){
			$this->catids = array();
		} else {
			$this->catids = array(0);
		}

		// Check permissions
		foreach($this->cats as $k => $v){
			// Check category perms. Unset it if not valid.
			$catperms = unserialize($v["permissions"]);
			if(!isset($catperms[$user["group"]]) || $catperms[$user["group"]]["view"] == false){
				unset($this->cats[$k]);
			} else {
				$this->catids[] = $v["id"];
			}
		}

		// More tweaks if single cat verse normal
		if($this->singleCat === false){
			array_unshift($this->cats, array("id" => 0));
			$tp->addNav($lang->item("home_nav"));
		} else {
			$tp->addNav(catLink($this->cats[0]));
		}

		$this->cats = $plugins->callhook("home_categories_checked", $this->cats);
	}



	private function loadBoards(){
		// Load boards, check permissions, and add to array.
		global $db, $parser, $tp, $plugins, $user;

		$bdat = $db->query("
			SELECT
				b.*
			FROM
				yakbb_boards b
			WHERE
				b.parenttype = 'c'
				AND b.parentid IN (".implode(",", $this->catids).")
				AND b.hidden = '0'
			ORDER BY
				`order` ASC
		");
		while($b = $db->fetch($bdat)){

			// Check perms and skip the board if it doesn't pass
			$boardperms = unserialize($b["permissions"]);
			if(!isset($boardperms[$user["group"]]) || $boardperms[$user["group"]]["view"] == false){
				continue;
			}

			// Add the data to the listing
			$b["description"] = $parser->parse($b["description"]);
			$b["new_posts"] = false;
			$this->boards[] = $b;
		}

		// Run through hook and clean up
		$this->boards = $plugins->callhook("home_boards_checked", $this->boards);
		$db->free();
	}



	private function getSubListing($bid, $del){
		// Returns a sub-board listing for the specified $bid. The boards are separated
		// by $del.
		// Pre-condition: $bid is a valid board ID; not necessarily a secure one

		global $db, $user;

		$bid = intval($bid);
		if($bid <= 0){
			// Not secure
			return "";
		}

		$subs = array();

		$subq = $db->queryCache("
			SELECT
				b.*
			FROM
				yakbb_boards b
			WHERE
				b.parentid = '".$bid."'
				AND b.parenttype = 'b'
				AND b.hidden = '0'
			ORDER BY
				`order` ASC
		", -1, "subboardlisting_".$bid);
		foreach($subq as $k => $subdat){
			$subperm = unserialize($subdat["permissions"]);
			if(isset($subperm[$user["group"]]) && $subperm[$user["group"]]["view"] == true){
				$subs[] = boardLink($subdat);
			}
		}

		return implode($subs, $del);
		
	}






	// TEMPLATE FUNCTIONS
	public function boards(){
		// Returns the current boards data

		return $this->boards;
	}
	public function cats(){
		// Returns the current categories data

		return $this->cats;
	}
	public function getSingle(){
		// Returns the current single category status

		return $this->singleCat;
	}
	public function subListing($bid, $del){
		// Returns a sub-board listing for the specified board id.

		return $this->getSubListing($bid, $del);
	}
}
?>