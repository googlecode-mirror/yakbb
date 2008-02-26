<?php

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
	- Everything else. =P
*/

if(!defined("SNAPONE")) exit;

$tid = intval($_REQUEST["thread"]);
if($tid <= 0){
	$tp->error("viewthread_invalid_id");
}

$thread = $db->query("SELECT * FROM ".DBPRE."threads WHERE id='".$tid."' LIMIT 1");
if($db->numRows() == 0){
	$tp->error("viewthread_doesnt_exist");
}
$tdat = $db->fetch();
$db->free();
$db->query("UPDATE ".DBPRE."threads SET views=views+1 WHERE id='".$tid."'");

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

	// Check view permissions
	$cperms = unserialize($cat["permissions"]);
	if(!isset($cperms[$user["group"]]) || $cperms[$user["group"]]["view"] == false){
		$tp->error("viewboard_no_permissions");
	}

	// Add nav, etc.
	$tp->addNav($tp->catLink($cat["id"], $cat["name"]));
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
	$tp->addNav($tp->boardLink($v["id"], $v["name"]));
}
unset($boards);

// Template stuff
$tp->addNav($tp->threadLink($tid, $tdat["title"]));
$tp->setTitle($tdat["title"], false);
$tp->loadFile("thread", "viewthread.tpl", array(
	"tid" => $tid,
	"can_reply" => false
));

// Check if user can post replies to thread
if($bperms[$user["group"]]["reply"] == true){
	$tp->addVar("thread", "can_reply", true);
}

// Load posts
$posts = $db->query("SELECT
					p.*, p.id AS postid, u.*
					FROM ".DBPRE."posts p
					LEFT JOIN ".DBPRE."users u ON (u.id = p.userid)
					WHERE p.threadid='".$tid."'
					ORDER BY postid ASC
");
$pholder = array();
while($p = $db->fetch()){
	$pholder[] = array(
		"pid" => $p["postid"],
		"ptitle" => $p["title"],
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