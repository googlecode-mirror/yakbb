<?php

/*	TODO
	- Last post data
	- Moderators list (if enabled)
	- Sub-boards list (if enabled)
	- On/off data
	- Sub-board data affects parents
	- Hide/show for categories
	- Add support for viewing category 0 on the main page
*/

if(!defined("SNAPONE")) exit;

$tp->setTitle("home");
$tp->loadFile("home", "home.tpl");
$lang->learn("home");
$plugins->callhook("homestart");

// Categories
$extra = $extra2 = "";
$singleCat = false; // Viewing single category
if(isset($_REQUEST["cat"])){
	$c = intval($_REQUEST["cat"]);
	if($c != 0){
		$extra = " AND id='".$c."'";
		$extra2 = "_".$c;
		$singleCat = true;
	} else {
		$tp->error("category_zero");
	}
}
$cats = $db->cacheQuery("SELECT * FROM ".DBPRE."categories WHERE showmain='1'".$extra." ORDER BY `order` ASC", "categories_main".$extra2);
$cats = $plugins->callhook("home_categories_loaded", $cats);

if($singleCat === true){
	$ids = array();
} else {
	$ids = array(0);
}
foreach($cats as $k => $v){
	if(!$perms->checkPerm("viewcat", array("cid" => $v["id"]))){
		unset($cats[$k]);
	} else {
		$ids[] = $v["id"];
		$cats[$k]["link"] = $tp->catLink($cats[$k]["id"], $cats[$k]["name"]);
	}
}
if($singleCat === false){
	array_unshift($cats, array("id" => 0));
	$tp->addNav($lang->item("home_nav"));
} else {
	$tp->addNav($tp->catLink($cats[0]["id"], $cats[0]["name"]));
}
$cats = $plugins->callhook("home_categories_checked", $cats);

 
// Boards
$boards = array();
$db->query("SELECT * FROM ".DBPRE."boards WHERE parenttype='c' AND parentid IN (".implode(",", $ids).") ORDER BY `order` ASC");
while($b = $db->fetch()){
	if($perms->checkPerm("viewboard", array(
		"bid" => $b["id"],
		"cid" => $b["parentid"]
	))){
		$b["description"] = $parser->parse($b["description"]);
		$b["new_posts"] = false;
		$b["link"] = $tp->boardLink($b["id"], $b["name"]);
		$boards[] = $b;
	}
}
$boards = $plugins->callhook("home_boards_checked", $boards);
$db->free();


// Add to the template
$tp->addVar("home", array(
	"CATS" => $cats,
	"BOARDS" => $boards
));


// Load the IC stats
$yak->loadIC();


$plugins->callhook("home_end");
?>