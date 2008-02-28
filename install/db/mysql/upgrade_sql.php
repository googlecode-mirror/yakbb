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

		// Change to enum from tinyint
		$db->query("ALTER TABLE `".DBPRE."boards` CHANGE `sublist` `sublist` ENUM( '0', '1' ) NOT NULL DEFAULT '1'");

		// Add the permissions group to boards and categories
		$db->query("ALTER TABLE `".DBPRE."categories` ADD `permissions` TEXT NOT NULL");

		// Fix sub list and permissions
		$db->query('UPDATE `'.DBPRE.'boards` SET `sublist`=\'1\', `permissions`=\'a:3:{i:-1;a:5:{s:4:"view";b:1;s:5:"reply";b:0;s:4:"poll";b:0;s:6:"thread";b:0;s:6:"attach";b:0;}i:0;a:5:{s:4:"view";b:1;s:5:"reply";b:1;s:4:"poll";b:1;s:6:"thread";b:1;s:6:"attach";b:0;}i:1;a:5:{s:4:"view";b:1;s:5:"reply";b:1;s:4:"poll";b:1;s:6:"thread";b:1;s:6:"attach";b:1;}}\'');
		$db->query('UPDATE `'.DBPRE.'categories` SET `permissions`=\'a:3:{i:-1;a:1:{s:4:"view";b:1;}i:0;a:1:{s:4:"view";b:1;}i:1;a:1:{s:4:"view";b:1;}}\'');
	}
	private function upgradeIt3(){
		global $db;

		// Start groups stuff
		$db->query("CREATE TABLE `".DBPRE."groups` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `name` TEXT NOT NULL , `color` TEXT NOT NULL , PRIMARY KEY ( `id` ) ) ENGINE = MYISAM ");
		$db->query("INSERT INTO `".DBPRE."groups` (`id` ,`name` ,`color`) VALUES ('1', 'Admin', 'a:0:{}')");
	}
}

?>