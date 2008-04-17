<?php

if(!defined("SNAPONE")) exit;

class upgrade_sql {
	var $db;
	public function upgrade($n=DBVERSION){
		// $n = current version. Will fully update to final version
		global $cache, $db;
		$this->db = &$db;

		while(++$n <= CURRENTDBVERSION && is_callable(array($this, "upgradeIt".$n))){
			call_user_func(array($this, "upgradeIt".$n));
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
		// Change to enum from tinyint
		$this->db->query("ALTER TABLE `".DBPRE."boards` CHANGE `sublist` `sublist` ENUM( '0', '1' ) NOT NULL DEFAULT '1'");

		// Add the permissions group to boards and categories
		$this->db->query("ALTER TABLE `".DBPRE."boards` ADD `permissions` TEXT NOT NULL");
		$this->db->query("ALTER TABLE `".DBPRE."categories` ADD `permissions` TEXT NOT NULL");

		// Fix sub list and permissions
		$this->db->query('UPDATE `'.DBPRE.'boards` SET `sublist`=\'1\', `permissions`=\'a:3:{i:-1;a:5:{s:4:"view";b:1;s:5:"reply";b:0;s:4:"poll";b:0;s:6:"thread";b:0;s:6:"attach";b:0;}i:0;a:5:{s:4:"view";b:1;s:5:"reply";b:1;s:4:"poll";b:1;s:6:"thread";b:1;s:6:"attach";b:0;}i:1;a:5:{s:4:"view";b:1;s:5:"reply";b:1;s:4:"poll";b:1;s:6:"thread";b:1;s:6:"attach";b:1;}}\'');
		$this->db->query('UPDATE `'.DBPRE.'categories` SET `permissions`=\'a:3:{i:-1;a:1:{s:4:"view";b:1;}i:0;a:1:{s:4:"view";b:1;}i:1;a:1:{s:4:"view";b:1;}}\'');
	}
	private function upgradeIt3(){
		// Start groups stuff
		$this->db->query("CREATE TABLE `".DBPRE."groups` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `name` TEXT NOT NULL , `color` TEXT NOT NULL , PRIMARY KEY ( `id` ) ) ENGINE = MYISAM ");
		$this->db->query("INSERT INTO `".DBPRE."groups` (`id` ,`name` ,`color`) VALUES ('1', 'Admin', 'a:0:{}')");
	}
	private function upgradeIt4(){
		// Pagination for threads and posts
		$this->db->query("INSERT INTO `".DBPRE."config` (`id` ,`name` ,`value`)VALUES ('0', 'posts_per_page', '15'), ('0', 'threads_per_page', '30')");
		$this->db->query("
			CREATE TABLE `".DBPRE."messages_relations` (
				`id` int(10) unsigned NOT NULL auto_increment,
				`pmid` int(11) NOT NULL,
				`userid` int(11) NOT NULL,
				`status` enum('0','1','2','3') NOT NULL,
				PRIMARY KEY  (`id`)
			) ENGINE=MYISAM
		");
		$this->db->query("
			CREATE TABLE `".DBPRE."messages` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
				`title` TEXT NOT NULL ,
				`message` TEXT NOT NULL ,
				`senderid` INT NOT NULL ,
				`time` INT NOT NULL ,
				`disableubbc` ENUM( '0', '1' ) NOT NULL ,
				`disablesmilies` ENUM( '0', '1' ) NOT NULL ,
				PRIMARY KEY ( `id` )
			) ENGINE = MYISAM
		");
	}
	private function upgradeIt5(){
		// Preliminary stuff for group colors (4/16/08)
		$this->db->query("UPDATE `".DBPRE."groups` SET `color` = 'a:2:{s:7:\"default\";s:7:\"#FF0000\";s:9:\"template2\";s:7:\"#0000FF\";}' WHERE `".DBPRE."groups`.`id` =1 LIMIT 1 ;");
	}
}

?>