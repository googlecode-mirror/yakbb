<?php

if(!defined("SNAPONE")) exit;

class upgrade {
	public function __construct(){
		global $lang, $tp;

		// Set up the core upgrade
		$lang->learn("upgrade");
		$tp->setTitle("upgrade");
		$tp->addNav($lang->item("nav_upgrade"));
		$tp->loadFile("upgrade", "upgrade.tpl", array(
			"page1" => false,
			"page2" => false,
			"page3" => true
		));

		if(CURRENTDBVERSION > DBVERSION){
			$this->upgrade_db();
		}

		if(version_compare(CURRENTYAKVERSION, YAKVERSION) == 1){
			$this->upgrade_yak();
		}
	}

	private function upgrade_db(){
		global $tp;

		require "./install/db/".DBTYPE."/upgrade_sql.php";
		$up = new upgrade_sql();
		$up->upgrade();
		$tp->addVar("upgrade", array(
			"page1" => true,
			"page3" => false
		));
	}

	private function upgrade_yak(){
		global $tp;

		$tp->addVar("upgrade", array(
			"page2" => true,
			"page3" => false
		));
	}
}

?>