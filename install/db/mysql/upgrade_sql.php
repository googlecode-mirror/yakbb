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
}

?>