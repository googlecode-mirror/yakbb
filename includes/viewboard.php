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
|| $Id: global.php 64 2008-04-14 15:32:04Z cddude229 $
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
	- Sub-boards list
	- Mark board as read
	- Test viewing permissions
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
$boarddat = $db->cacheQuery("
	SELECT
		b.*
	FROM
		".DBPRE."boards b
	WHERE
		b.id='".$bid."'
	LIMIT 1",
"board_data/".$bid);
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
	$tp->addNav($tp->catLink($cat["id"], $cat["name"]));
	unset($cat);
}

// Boards
$boards = array_reverse($boards);
foreach($boards as $k => $v){
	// Check view permissions and then add nav
	$boardperms = unserialize($v["permissions"]);
	if(!isset($boardperms[$user["group"]]) || $boardperms[$user["group"]]["view"] == false){
		$tp->error("viewboard_no_permission");
	}
	$tp->addNav($tp->boardLink($v["id"], $v["name"]));
}
unset($boards);

$tp->addNav($tp->boardLink($boarddat["id"], $boarddat["name"]));
$tp->setTitle($boarddat["name"], false);

// Send some permissions to the forum
$perms = unserialize($boarddat["permissions"]);
if(isset($perms[$user["group"]])){
	$tp->addVar("board", array(
		"perm_newthread" => !!($perms[$user["group"]]["thread"] == true),
		"perm_reply" => !!($perms[$user["group"]]["reply"] == true)
	));
} else {
	$tp->addVar("board", array(
		"perm_newthread" => false,
		"perm_reply" => false
	));
}

// Load threads
$threads = array();
$db->query("
	SELECT
		t.*
	FROM
		".DBPRE."threads t
	WHERE
		t.boardid='".$bid."'
	ORDER BY
		timestamp DESC
");

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
	"threads" => $threads,
	"bid" => $bid
));

?>