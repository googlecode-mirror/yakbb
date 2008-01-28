<?php

/*	TODO
	- Pagination
	- Buttons
	- Load attachements if the post has any.
		- This should be cached since it shouldn't be editted, unless removing.
	- Everything else. =P
*/

if(!defined("SNAPONE")) exit;

$tid = intval($_REQUEST["thread"]);
if($tid < 0){
	$tp->error("viewthread_invalid_id");
}

$thread = $db->query("SELECT * FROM ".DBPRE."threads WHERE id='".$tid."' LIMIT 1");
if($db->numRows() == 0){
	$tp->error("viewthread_doesnt_exist");
}
$tdat = $db->fetch();
$db->free();

$curboard = array(
	"parentid" => $tdat["boardid"],
	"parenttype" => "b"
);
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
	unset($cat);
}
unset($curboard);
$boards = array_reverse($boards);
foreach($boards as $k => $v){
	if(!$perms->checkPerm("viewboard", array("bid" => $v["id"]))){
		$tp->error("viewboard_no_permission");
	}
	$tp->addNav($v["name"]);
}
unset($boards);

$tp->addNav($tdat["title"]);
$tp->setTitle($tdat["title"], false);
$tp->loadFile("thread", "viewthread.tpl", array(
	"tid" => $tid
));

$posts = $db->query("SELECT
					p.*, u.*
					FROM ".DBPRE."posts p
					LEFT JOIN ".DBPRE."users u ON (u.id = p.userid)
					WHERE p.threadid='".$tid."'
");
$pholder = array();
while($p = $db->fetch()){
	$pholder[] = array(
		"pid" => $p["id"],
		"time" => $p["timestamp"],
		"date" => makeDate($p["timestamp"]),
		"userlink" => $tp->userLink($p["userid"], $p["name"], $p["group"]),
		"message" => $parser->parse($p["message"], $p["disablesmilies"] == 0, $p["disableubbc"] == 0)
	);
}
$db->free();

$tp->addVar("thread", "posts", $pholder);
unset($pholder);

?>