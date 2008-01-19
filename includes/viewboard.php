<?php

if(!defined("SNAPONE")) exit;

if(!isset($_GET["board"]) || !is_numeric($_GET["board"]) || $_GET["board"] < 1){
	$tp->error("viewboard_invalid_id");
}

$bid = intval($_GET["board"]);

// Make sure board exists
$boarddat = $db->query("SELECT * FROM ".DBPRE."boards WHERE id='".$bid."'");
if($db->numRows() == 0){
	$tp->error("viewboard_doesnt_exist");
}

$curboard = $boarddat;
$boards = array();
while(true){
	if($curboard["parenttype"] == "c"){
		break;
	}
	$curboard = $db->cacheQuery("SELECT * FROM ".DBPRE."boards WHERE id='".$curboard["parentid"]."'");
	$boards[] = $curboard;
}

$cat = $db->cacheQuery("SELECT * FROM ".DBPRE."categories WHERE id='".$curboard["parentid"]."'");



?>