<?php

if(!defined("SNAPONE")) exit;

class upgrade_sql {
	private $db;
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
		// Fix some int things being enums. (4/27/08)
		$this->db->query("
			ALTER TABLE `yakbb_categories`
				CHANGE `hideshow` `hideshow` ENUM( '0', '1' ) NOT NULL ,
				CHANGE `showmain` `showmain` ENUM( '0', '1' ) NOT NULL
		");
		$this->db->query("
			ALTER TABLE `yakbb_bans`
				CHANGE `type` `type` ENUM( '1', '2', '3' ) NOT NULL
		");
		$this->db->query("
			ALTER TABLE `yakbb_polls`
				CHANGE `closed` `closed` ENUM( '0', '1' ) NOT NULL
		");
		$this->db->query("
			ALTER TABLE `yakbb_posts`
				CHANGE `disableubbc` `disableubbc` ENUM( '0', '1' ) NOT NULL ,
				CHANGE `disablesmilies` `disablesmilies` ENUM( '0', '1' ) NOT NULL
		");
		$this->db->query("
			ALTER TABLE `yakbb_users`
				CHANGE `activated` `activated` ENUM( '0', '1' ) NOT NULL ,
				CHANGE `emailshow` `emailshow` ENUM( '0', '1' ) NOT NULL ,
				CHANGE `emailoptin` `emailoptin` ENUM( '0', '1', '2' ) NOT NULL ,
				CHANGE `gender` `gender` ENUM( '0', '1', '2' ) NOT NULL ,
				CHANGE `newpmnotify` `newpmnotify` ENUM( '0', '1', '2' ) NOT NULL ,
				CHANGE `invisible` `invisible` ENUM( '0', '1' ) NOT NULL ,
				CHANGE `dst` `dst` ENUM( '0', '1' ) NOT NULL
		");
		$this->db->query("
			ALTER TABLE `yakbb_users`
				CHANGE `pmenabled` `pmenabled` ENUM( '0', '1', '2' ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '2'
		");

		// Add a 16th poll option. It never hurt anything (4/27/08)
		$this->db->query("
			ALTER TABLE `yakbb_polls`
				ADD `answer16` TEXT NOT NULL
		");

		// Rename the poll_votes table to polls_votes to keep them grouped together (4/27/08)
		$this->db->query("RENAME TABLE `yakbb_poll_votes`  TO `yakbb_polls_votes`");

		// Add some data to the polls table (4/27/08)
		$this->db->query("
			ALTER TABLE `yakbb_polls`
				ADD `expires` INT NOT NULL DEFAULT '0' AFTER `question` ,
				ADD `canchoose` INT NOT NULL DEFAULT '1' AFTER `expires` ,
				ADD `viewresults` ENUM( '0', '1', '2', '3' ) NOT NULL DEFAULT '3' AFTER `canchoose` ,
				ADD `canretract` ENUM( '0', '1' ) NOT NULL AFTER `canchoose`
		");
	}
}
?>