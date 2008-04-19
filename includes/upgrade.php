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
|| File: /includes/upgrade.php
|| File Version: v0.1.0a
|| $Id: global.php 64 2008-04-14 15:32:04Z cddude229 $
\*==================================================*/

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
		$tp->addGlobal("upgrade_check", true);

		// DB Upgrades
		if(CURRENTDBVERSION > DBVERSION){
			$this->upgrade_db();
		}

		// Update the actual Yak version
		if(version_compare(CURRENTYAKVERSION, YAKVERSION) == 1){
			$this->upgrade_yak();
		}
	}



	private function upgrade_db(){
		// Upgrade the DB.
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
		// Used when there's an upgrade to the actual YakBB source that needs patching
		global $tp;

		$tp->addVar("upgrade", array(
			"page2" => true,
			"page3" => false
		));
	}
}

?>