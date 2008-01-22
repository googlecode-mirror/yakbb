<?php

/*	TODO
	- Make the nav tree parts into links
	- Thread listing
		- Redirect threads go through tracker??
		- Pagination
		- Order by lastpost instead of timestamp
			-> lporder actually; this will work better with the bump feature
		- Sticky and announcements list
		- New/no new replies info
	- CHECK PASSWORDS
	- Sub-boards list
	- Send new thread, new poll, etc. permissions for buttons
*/

if(!defined("SNAPONE")) exit;

$tp->loadFile("board", "viewboard.tpl");
$lang->learn("viewboard");
$tp->setTitle("viewboard");

if(!isset($_GET["board"]) || !is_numeric($_GET["board"]) || $_GET["board"] < 1){
	$tp->error("viewboard_invalid_id");
}

$bid = intval($_GET["board"]);

// Make sure board exists
$boarddat = $db->cacheQuery("SELECT * FROM ".DBPRE."boards WHERE id='".$bid."' LIMIT 1", "board_data/".$bid);
if(count($boarddat) == 0){
	$db->clearCacheQuery("board_data/".$bid);
	$tp->error("viewboard_doesnt_exist");
}
$boarddat = $boarddat[0]; // First result only. This is a cache issue... I maybe should rework that. =P

$curboard = $boarddat;
$boards = array();
while(true){
	if($curboard["parenttype"] == "c"){
		break;
	}
	$curboard = $db->cacheQuery("SELECT * FROM ".DBPRE."boards WHERE id='".$curboard["parentid"]."' LIMIT 1", "board_data/".$curboard["parentid"]);
	$curboard = $curboard[0]; // We only want the first result... despite there being only one.
	$boards[] = $curboard;
}

if($curboard["parentid"] != 0){
	// Load the category if the ID isn't 0. (If it is zero, we don't really have a category. =P)
	$cat = $db->cacheQuery("SELECT * FROM ".DBPRE."categories WHERE id='".$curboard["parentid"]."' LIMIT 1", "category_data/".$curboard["parentid"]);
	$cat = $cat[0]; // Only want the first result... despite there being only one.
	if(!$perms->checkPerm("viewcat", array("cid" => $cat["id"]))){
		$tp->error("viewboard_no_permissions");
	}
	$tp->addNav($cat["name"]);
}
$boards = array_reverse($boards);
foreach($boards as $k => $v){
	if(!$perms->checkPerm("viewboard", array("bid" => $v["id"]))){
		$tp->error("viewboard_no_permission");
	}
	$tp->addNav($v["name"]);
}

$tp->addNav($boarddat["name"]);
$tp->setTitle($boarddat["name"], false);

// Load threads
$threads = array();
$db->query("SELECT * FROM ".DBPRE."threads WHERE boardid='".$bid."' ORDER BY timestamp DESC");

while($t = $db->fetch()){
	$threads[] = array(
		"id" => $t["id"],
		"creationtime" => $t["timestamp"],
		"creationdate" => makeDate($t["timestamp"]),
		"title" => $t["title"],
		"description" => $t["description"],
		"creator" => $t["creatorid"],
		"board" => $t["boardid"],
		"replies" => $t["replies"],
		"views" => $t["views"],
		"icon" => "",
		"announcement" => false,
		"sticky" => $t["sticky"] == 1,
		"locked" => $t["locked"] == 1,
		"link" => (strlen($t["redirecturl"])>0?$t["redirecturl"]:$tp->threadLink($t["id"], $t["title"]))
	);
}

$db->free();

$tp->addVar("board", array(
	"threads" => $threads
));

?>