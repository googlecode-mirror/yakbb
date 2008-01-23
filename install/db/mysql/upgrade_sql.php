<?php

if(!defined("SNAPONE")) exit;

class upgrade_sql {
	public function upgrade($n){
		// $n = current version. Will fully update to final version
		global $cache;
		while($n <= CURRENTDBVERSION && is_callable(array($this, "upgradeIt".$n))){
			call_user_func(array($this, "upgradeIt".$n));
			$n++;
		}

		// Update config file
		$ff = new flat_file();
		$dat = $ff->loadFile("../config.inc.php");
		if(!$dat) die("Can't locate config file for update query.");
		// echo $dat; exit;
		$dat = preg_replace("/define\(\"DBVERSION\",.*?\"\d+\"\);/i", "define(\"DBVERSION\",\t \"".CURRENTDBVERSION."\");", $dat);
		$ff->updateFile("../config.inc.php", $dat);
		$cache->clearCache();
	}

	private function upgradeIt0(){} // Prevent possible errors
	private function upgradeIt1(){} // Prevent possible errors
	private function upgradeIt2(){
		global $db;
		$db->query("INSERT INTO ".DBPRE."config VALUES ('0', 'nav_divider', ' :: ');");
	}
	private function upgradeIt3(){
		global $db;

		// Main page sub-boards listing and moderators listing
		$db->query("ALTER TABLE ".DBPRE."boards ADD `sublist` TINYINT NOT NULL AFTER `order`");
		$db->query("INSERT INTO ".DBPRE."config VALUES ('0', 'mod_list_main', 'true')");
	}
}

?>