<?php

/*	TODO
	- Last post data
	- Moderators list (if enabled)
	- Sub-boards list (if enabled)
	- On/off data
	- Sub-board data affects parents (if enabled)
	- Hide/show for categories
	- Add support for viewing category 0 on the main page
*/

if(!defined("SNAPONE")) exit;

class home {
	// Category stuff
	private $cats = array(); // Category data holder
	private $catids = array(); // List of category IDs
	private $singleCat = false; // Whether or not only a single category is bieng loaded
	

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

		// Add to the template
		$tp->addVar("home", array(
			"cats" => $this->cats,
			"boards" => $this->boards,
			"single" => $this->singleCat
		));

		// Load the IC stats
		if($this->singleCat != true){
			$yak->loadIC();
		}

		$plugins->callhook("home_end");
	}



	private function loadCats(){
		// Load the categories
		global $tp, $lang, $db, $plugins, $perms;

		// Extra info for single category stuff
		$extra = $extra2 = "";
		if(isset($_REQUEST["cat"])){
			$c = intval($_REQUEST["cat"]);
			if($c != 0){
				$extra = " AND id='".$c."'";
				$extra2 = "_".$c;
				$this->singleCat = true;
			} else {
				$tp->error("category_zero");
			}
		}

		// Query DB (or cache)
		$this->cats = $db->cacheQuery("SELECT * FROM ".DBPRE."categories WHERE showmain='1'".$extra." ORDER BY `order` ASC", "categories_main".$extra2);

		// Set the category IDs array and take care of the single cat stuff
		if($this->singleCat === true){
			$this->catids = array();
		} else {
			$this->catids = array(0);
		}

		// Check permissions
		foreach($this->cats as $k => $v){
			if(!$perms->checkPerm("viewcat", array("cid" => $v["id"]))){
				unset($this->cats[$k]);
			} else {
				$this->catids[] = $v["id"];
				$this->cats[$k]["link"] = $tp->catLink($v["id"], $v["name"]);
			}
		}

		// More tweaks if single cat verse normal
		if($this->singleCat === false){
			array_unshift($this->cats, array("id" => 0));
			$tp->addNav($lang->item("home_nav"));
		} else {
			$tp->addNav($tp->catLink($this->cats[0]["id"], $this->cats[0]["name"]));
		}

		$this->cats = $plugins->callhook("home_categories_checked", $this->cats);
	}



	private function loadBoards(){
		// Load boards, check permissions, and add to array.
		global $db, $perms, $parser, $tp, $plugins;

		$db->query("SELECT * FROM ".DBPRE."boards WHERE parenttype='c' AND parentid IN (".implode(",", $this->catids).") ORDER BY `order` ASC");
		while($b = $db->fetch()){
			if($perms->checkPerm("viewboard", array(
				"bid" => $b["id"],
				"cid" => $b["parentid"]
			))){
				$b["description"] = $parser->parse($b["description"]);
				$b["new_posts"] = false;
				$b["link"] = $tp->boardLink($b["id"], $b["name"]);
				$this->boards[] = $b;
			}
		}
		$this->boards = $plugins->callhook("home_boards_checked", $this->boards);
		$db->free();
	}
}
?>