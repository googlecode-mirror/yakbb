<?php

if(!defined("SNAPONE")) exit;

class upgrade_sql {
	public function upgrade($n=DBVERSION){
		// $n = current version. Will fully update to final version
		global $cache;
		while($n <= CURRENTDBVERSION && is_callable(array($this, "upgradeIt".$n))){
			call_user_func(array($this, "upgradeIt".$n));
			$n++;
		}

		// Update config file
		$ff = new flat_file();
		$f = "../config.inc.php";
		if(!file_exists($f)){
			$f = "./config.inc.php";
		}
		$dat = $ff->loadFile($f);
		if(!$dat) die("Can't locate config file for upgrade query.");
		$dat = preg_replace("/define\(\"DBVERSION\",.*?\"\d+\"\);/i", "define(\"DBVERSION\",\t \"".CURRENTDBVERSION."\");", $dat);
		$ff->updateFile($f, $dat);
		if(isset($cache)){
			$cache->clearCache();
		}
	}

	private function upgradeIt0(){} // Prevent possible errors
	private function upgradeIt1(){} // Prevent possible errors
	private function upgradeIt2(){
		global $db;

		// Nav divider
		$db->query("INSERT INTO ".DBPRE."config VALUES ('0', 'nav_divider', ' :: ');");
	}
	private function upgradeIt3(){
		global $db;

		// Main page sub-boards listing and moderators listing
		$db->query("ALTER TABLE ".DBPRE."boards ADD `sublist` TINYINT NOT NULL AFTER `order`");
		$db->query("INSERT INTO ".DBPRE."config VALUES ('0', 'mod_list_main', 'true')");
	}
	private function upgradeIt4(){
		global $db;

		// Ban table. =P
		$db->query("CREATE TABLE ".DBPRE."bans (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `type` TINYINT( 1 ) NOT NULL, `value` TEXT NOT NULL, PRIMARY KEY ( `id` )) ENGINE = MYISAM ");
		$db->query("ALTER TABLE ".DBPRE."bans ADD `expires` INT NOT NULL, ADD `reason` TEXT NOT NULL, ADD `started` INT NOT NULL");
	}
	private function upgradeIt5(){
		global $db;

		// SEO work
		$db->query("INSERT INTO ".DBPRE."config VALUES ('0', 'seo_engine', 'true')");
	}
	private function upgradeIt6(){
		global $db;

		// Thread stuff
		$db->query("INSERT INTO ".DBPRE."config VALUES ('0', 'thread_desc_max', '100')");
		$db->query("INSERT INTO ".DBPRE."config VALUES ('0', 'thread_subject_max', '50')");
		$db->query("INSERT INTO ".DBPRE."config VALUES ('0', 'thread_message_max', '25000')");
	}
}

?>