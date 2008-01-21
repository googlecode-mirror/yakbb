<?php

/*	TODO
	- Make the nav tree parts into links
	- Thread listing
		- Redirect threads
		- Error message if no threads
	- CHECK PASSWORDS
	- Clear non-existing board cache data
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
$boarddat = $db->cacheQuery("SELECT * FROM ".DBPRE."boards WHERE id='".$bid."'", "board_data/".$bid);
if(count($boarddat) == 0){
	$tp->error("viewboard_doesnt_exist");
}
$boarddat = $boarddat[0];

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
	$cat = $db->cacheQuery("SELECT * FROM ".DBPRE."categories WHERE id='".$curboard["parentid"]."'", "category_data/".$curboard["parentid"]);
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
$tp->setTitle($boarddat["name"]);


?>