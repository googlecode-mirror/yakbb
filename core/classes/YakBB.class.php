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

/*	TODO
	- Need to assign IP to $yakbb->ip
	error()
		- Need to add support for type 0 and type 1 errors
		- Need to add support in error.tpl for additional information.
*/

defined("YAKBB") or die("Security breach.");

class YakBB {
	// Object holders
	public  $smarty  = false; // Smarty object reference
	public  $db      = false; // Database object reference
	public  $parser  = false; // Post parser object reference

	// Data holders
	public  $user = array(); // User data
	public  $ip   = ""; // USer's IP
	private $lang = array(); // Language variables

	// Other variables
	private $dbconfig  = false; // Databsae configuration (cleared early on)
	public  $config    = array(); // Configuration
	private $groups    = array(); // Groups from the database
	private $module    = false; // The currently selected module
	private $templates = array(); // List of template files to load


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
		$this->finish();
	}

	public function finish(){
		$stats = array(
			"queries" => count($this->db->queries)
		);
		$this->smarty->assign("stats", $stats);
		foreach($this->templates as $k => $v){
			$this->smarty->display($v);
		}
		exit;
	}

	public function loadTemplate($file){
		$this->templates[] = $file;
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
		require YAKBB_CORE."includes/loading.lib.php";
		require YAKBB_CORE."includes/general_functions.lib.php";
		require YAKBB_CORE."includes/permissions.lib.php";
		require YAKBB_CORE."classes/FlatFile.class.php";
		require YAKBB_CORE."classes/DB.class.php";
		require YAKBB_CORE."classes/Post_Parser.class.php";
		if($this->dbconfig){ // If dbconfig is set, load the DB type and then config.
			require YAKBB_CORE."classes/DB_".$this->dbconfig["dbtype"].".class.php";
			$str = "DB_".$this->dbconfig["dbtype"];
			$this->db = new $str($this->dbconfig);
			unset($this->dbconfig);
			$this->loadConfig();
			$this->loadGroups();
		}

		$this->parser = new Post_Parser();

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

	private function loadGroups(){
		$this->groups = array();
		$dat = $this->db->cacheQuery("
			SELECT
				*
			FROM
				yakbb_groups
		", "groups");

		foreach($dat as $v){
			$this->groups[$v["id"]] = $v;
		}
	}

	private function loadUser(){
		$this->user = array( // Guest dummy data
			"id" => 0,
			"username" => "Guest",
			"group" => -1,
			"template" => $this->config["default_template"],
			"language" => $this->config["default_language"]
		);
		$this->smarty->assign("guest", true);
		$this->smarty->assign("admin_access", false);

		if(getYakCookie("username") != "" && getYakCookie("password") != ""){
			// Check login
			$user = secure(getYakCookie("username"));
			$pass = getYakCookie("password");
			loadLibrary("validation.lib");
			if(valid_username($user) === true && valid_password($pass) === true){
				$this->db->query("
					SELECT
						*
					FROM
						yakbb_users
					WHERE
						username = '".$this->db->secure($user)."'
					LIMIT
						1
				");
				if($this->db->numRows() == 1){
					$x = $this->db->fetch();
					if($x["password"] === $pass){
						$this->user = $x;
						$this->smarty->assign("guest", false);
					}
				}
			}
		}
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
			if(isset($_REQUEST["action"])){
				$this->module = $_GET["action"];
			} else if(isset($_GET["thread"])){ // Will only be get
				$this->module = "viewthread";
			} else if(isset($_GET["board"])){ // Will only be get
				$this->module = "viewboard";
			}

			$modules = array(
				// General
				"register" => "register",
				"join" => "register",
				"login" => "login",
				"signon" => "login",
				"signin" => "login",
				"logout" => "logout",
				"signoff" => "logout",
				"home" => "home",

				// Thread related
				"viewthread" => "viewthread",
				"reply" => "post",
				"modify" => "post",
				"delete" => "deletepost",

				// Board related
				"viewboard" => "viewboard",
				"newthread" => "post",

				// Admin related

				// Misc
			);

			// See if module is correct, and then load it. Otherwise default to home.
			if(!isset($modules[$this->module])){
				$this->module = "home";
			} else {
				$this->module = $modules[$this->module];
			}
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
		// Only throws a type 2 error for now
		$this->loadLanguageFile("errors");
		$this->smarty->assign("error", $this->getLang($error_string));
		$this->smarty->assign("additional", $additional);
		$this->smarty->assign("page_title", $this->getLang("error_title"));
		$this->loadTemplate("error.tpl");
		$this->finish();
	}

	public function getLang($str){
		if(isset($this->lang[$str])){
			return $this->lang[$str];
		}
		return "";
	}
}

?>