<?php

if(!defined("SNAPONE")) exit;

if(!isset($_GET["board"]) || $_GET["board"] < 1 || !is_numeric($_GET["board"])){
	$tp->error("viewboard_invalid_id");
}

$bid = intval($_GET["board"]);

// Make sure board exists
$boarddat = $db->query("SELECT * FROM ".DBPRE."boards WHERE id='".$bid."'");
if($db->numRows() == 0){
	$tp->error("viewboard_doesnt_exist");
}

?>