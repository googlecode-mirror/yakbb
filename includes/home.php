<?php

if(!defined("SNAPONE")) exit;

$tp->setTitle("home");
$tp->loadFile("home", "home.tpl");
$lang->learn("home");
$plugins->callhook("homestart");

// Categories
$cats = $db->cacheQuery("SELECT * FROM ".DBPRE."categories WHERE showmain='1' ORDER BY `order` ASC");
$cats = $plugins->callhook("home_categories_loaded", $cats);

$ids = array(0);
foreach($cats as $k => $v){
	if(!$perms->checkPerm("viewcat", array("cid" => $v["id"]))){
		unset($cats[$k]);
	} else {
		$ids[] = $v["id"];
	}
}
array_unshift($cats, array("id" => 0));
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


// Add to the template
$tp->addVar("home", array(
	"CATS" => $cats,
	"BOARDS" => $boards
));


// Load the IC stats
$yak->loadIC();


$plugins->callhook("home_end");
?>