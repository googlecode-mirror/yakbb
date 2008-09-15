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
	// Object holders
	public $smarty  = false;
	public $db      = false;

	// Data holders
	public  $user   = array();
	private $lang   = array();

	// Other variables
	private $dbconfig = false;
	private $config   = array();
	private $groups   = array();
	private $module   = false;


	// Core loading functions
	public function __construct(){
	}

	public function initiate(){
		// Load config first
		if(file_exists("./config.inc.php")){
			require "./config.inc.php";
			$this->dbconfig = $config; // Will be unset after we create the DB object.
			unset($config);
		} else if(!defined("YAKBB_INSTALL")){ // No config and not in the install
			header("Location: ./install.php");
			exit;
		}

		// Gotta load our application's library next
		$this->loadLibrary();
		$this->loadUser();
		$this->setConfig();
		$this->selectModule();
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
		require YAKBB_CORE."includes/functions.lib.php";
		require YAKBB_CORE."classes/FlatFile.class.php";
		require YAKBB_CORE."classes/DB.class.php";
		if($this->dbconfig){ // If dbconfig is set, load the DB type and then config.
			require YAKBB_CORE."classes/DB_".$this->dbconfig["dbtype"].".class.php";
			$str = "DB_".$this->dbconfig["dbtype"];
			$this->db = new $str($this->dbconfig);
			unset($this->dbconfig);
			$this->loadConfig();
		}

		// Load url and link stuff
		if($this->config["sef_urls"] == true){
			require YAKBB_CORE."includes/urls_seo.lib.php";
		} else {
			require YAKBB_CORE."includes/urls.lib.php";
		}
		require YAKBB_CORE."includes/links.lib.php";
	}

	private function loadConfig(){
		$dat = $this->db->cacheQuery("
			SELECT
				name, value
			FROM
				yakbb_config
		", "config");
		foreach($dat as $v){
			$val = $v["value"];

			if(is_numeric($val)){
				$val = intval($val);
			} else if($val == "true"){
				$val = true;
			} else if($val == "false"){
				$val = false;
			}

			$this->config[$v["name"]] = $val;
		}
	}

	private function loadUser(){
		$this->user = array( // Guest dummy data
			"id" => 0,
			"name" => "Guest",
			"template" => $this->config["default_template"],
			"language" => $this->config["default_language"]
		);
		$this->smarty->assign("guest", true);
		$this->smarty->assign("admin_access", false);
	}

	private function setConfig(){
		$bnames = unserialize($this->config["board_title"]);
		$this->smarty->assign("board_title", $bnames[$this->user["language"]]);
	}

	private function selectModule(){
		// Decide the correct module
		$this->module = "";
		if(defined("YAKBB_INSTALL")){
			$this->module = "install";
			$this->smarty->template_dir .= "__install/";
			$this->smarty->compile_id = "__install";
		} else {
			// Set correct template dir
			if(validTemplate($this->user["template"])){
				$this->smarty->template_dir .= $this->user["template"]."/";
				$this->smarty->compile_id = $this->user["template"];
			} else {
				$this->smarty->template_dir .= $this->config["default_template"]."/";
				$this->smarty->compile_id = $this->config["default_template"]."/";
			}
			// Load other modules here
		}
		if(!file_exists(YAKBB_MODULES.$this->module.".php")){
			$this->module = "home";
		}
		$this->runSelectedModule();
	}

	private function runSelectedModule(){
		require YAKBB_MODULES.$this->module.".php";
		$mod = new $this->module();
		$mod->init();
	}

	public function loadLanguageFile($file){
		$f = YAKBB_LANGUAGES.$this->user["language"]."/".$file.".lang.php";
		if(file_exists($f)){
			require $f;
			$this->lang = array_merge($this->lang, $items);
		}
	}


	// Handling errors
	public function error($type, $error_string, $additional=array()){
		// Errors types: 0 = application error, 1 = general error, 2 = throw a template error
		$this->loadLanguageFile("errors");
		$this->smarty->assign("error", $this->getLang($error_string));
		$this->smarty->assign("additional", $additional);
		$this->smarty->display("error.tpl");
	}

	public function getLang($str){
		if(isset($this->lang[$str])){
			return $this->lang[$str];
		}
		return "";
	}
}

?>