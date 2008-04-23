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
		// Add support for showing/hiding the current version (4/19/08)
		$this->db->query("INSERT INTO `".DBPRE."config` (`id` ,`name` ,`value`) VALUES ('9', 'show_version', 'true')");

		// Nav divider is done via template files now (4/19/08)
		$this->db->query("DELETE FROM ".DBPRE."config WHERE id='17' LIMIT 1");

		// Delete the strip tab spacing setting since it won't be used anymore (4/20/08)
		$this->db->query("DELETE FROM `".DBPRE."config` WHERE `id` = 3 LIMIT 1");

		// Create moderators relations table (4/20/08)
		$this->db->query("
			CREATE TABLE `".DBPRE."moderators_relations` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
				`boardid` INT NOT NULL ,
				`userid` INT NOT NULL ,
				PRIMARY KEY ( `id` )
			) ENGINE = MYISAM
		");

		// Changes to the messages table and relations to get the new system beginning. (4/20/08)
		$this->db->query("ALTER TABLE `".DBPRE."messages_relations` DROP `status`");
		$this->db->query("ALTER TABLE `".DBPRE."messages_relations` ADD `lastread` INT NOT NULL");
		$this->db->query("ALTER TABLE `".DBPRE."messages_relations` ADD `deleted` ENUM( '0', '1' ) NOT NULL");
		$this->db->query("
			CREATE TABLE `".DBPRE."messages_threads` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
				`title` TEXT NOT NULL ,
				`lastupdated` INT NOT NULL ,
				`initialsend` INT NOT NULL ,
				PRIMARY KEY ( `id` )
			) ENGINE = MYISAM
		");
		$this->db->query("ALTER TABLE `".DBPRE."messages` DROP `title`");
		$this->db->query("ALTER TABLE `".DBPRE."messages` ADD `threadid` INT NOT NULL AFTER `senderid` ");

		// Changes some tinyint's to enum's for thread data (4/21/08)
		$this->db->query("
			ALTER TABLE `".DBPRE."threads`
				CHANGE `announcement` `announcement` ENUM( '0', '1' ) NOT NULL DEFAULT '0',
				CHANGE `sticky` `sticky` ENUM( '0', '1' ) NOT NULL DEFAULT '0',
				CHANGE `locked` `locked` ENUM( '0', '1' ) NOT NULL DEFAULT '0'
		");

		// First permission in the groups table!! (4/21/08)
		$this->db->query("ALTER TABLE `".DBPRE."groups` ADD `replytolocked` ENUM( '0', '1' ) NOT NULL DEFAULT '0'");
		$this->db->query("UPDATE `".DBPRE."groups` SET `replytolocked` = '1' WHERE `id` =1 LIMIT 1 ;");

		// Fix board order being 0. It should be 1+ (4/22/08)
		$this->db->query("UPDATE `".DBPRE."boards` SET `order` = '1' WHERE `id` =3 LIMIT 1");
		$this->db->query("UPDATE `".DBPRE."boards` SET `order` = '1' WHERE `id` =4 LIMIT 1");

		// Add a second board to a main category for testing (4/22/08)
		$this->db->query('
			INSERT INTO `'.DBPRE.'boards`
				   (`id`, `parentid`, `parenttype`, `name`, `description`, `threads`, `posts`, `order`, `sublist`, `permissions`)
			VALUES (\'0\', \'1\', \'c\', \'Board 5... What?\', \'[b]Te[/b]st.\', \'0\', \'0\', \'2\', \'1\', \'a:3:{i:-1;a:5:{s:4:"view";b:1;s:5:"reply";b:0;s:4:"poll";b:0;s:6:"thread";b:0;s:6:"attach";b:0;}i:0;a:5:{s:4:"view";b:1;s:5:"reply";b:1;s:4:"poll";b:1;s:6:"thread";b:1;s:6:"attach";b:0;}i:1;a:5:{s:4:"view";b:1;s:5:"reply";b:1;s:4:"poll";b:1;s:6:"thread";b:1;s:6:"attach";b:1;}}\')
		');
	}
}

?>