<?php

if(!defined("SNAPONE")) exit;

$lang->learn("upgrade");
$tp->addNav($lang->item("nav_upgrade"));
$tp->loadFile("upgrade", "upgrade.tpl", array(
	"page1" => false,
	"page2" => false,
	"page3" => true
));

if(CURRENTDBVERSION > DBVERSION){
	require "./install/db/mysql/upgrade_sql.php";
	$up = new upgrade_sql();
	$up->upgrade();
	$tp->addVar("upgrade", array(
		"page1" => true,
		"page3" => false
	));
}

if(version_compare(CURRENTYAKVERSION, YAKVERSION) == 1){
	$tp->addVar("upgrade", array(
		"page2" => true,
		"page3" => false
	));
}

?>