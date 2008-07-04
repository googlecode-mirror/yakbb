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
|| File: /core/classes/YakBB.class.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

defined("YAKBB") or die("Security breach.");

class YakBB {
	private $smarty = false;
	private $db = false;
	private $config = false;

	public function __construct(){
	}

	public function initiate(){
		// Load config first
		if(file_exists("./config.inc.php")){
			require "./config.inc.php";
			$this->config = $config; // Will be unset after we create the DB object.
			unset($config);
		} else if(!defined("YAKBB_INSTALL")){
			define("YAKBB_INSTALL", 1); // Currently in install mode
		}

		// Gotta load our application's library next
		$this->loadLibrary();
	}

	private function loadSmarty(){
		if($this->smarty){
			return true;
		}

		global $smarty;
		require SMARTY_DIR."Smarty.class.php";
		$smarty = $this->smarty = new Smarty();

		$this->smarty->template_dir = YAKBB_TEMPLATES; // Load this one temporarily
		$this->smarty->compile_dir = YAKBB_CACHE."compiled_templates";
		$this->smarty->cache_dir = YAKBB_CACHE."cached_templates";
		$this->smarty->config_dir = YAKBB_TEMPLATES."config";
		// $this->smarty->debugging = true; // < -- Doesn't want to work
	}

	private function loadLibrary(){
		$this->loadSmarty();
		require YAKBB_CORE."classes/DB.class.php";
		if($this->config){ // If config is set, load the DB type.
			require YAKBB_CORE."classes/DB_".$this->config["dbtype"].".class.php";
			$str = "DB_".$this->config["dbtype"];
			$this->db = new $str($this->config);
			unset($this->config);
		}
	}
}

?>