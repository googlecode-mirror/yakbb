<?php

if(!defined("SNAPONE")) exit;

/*
	TODO
	- Make README use language variable? (Language loads after README, doesn't it? o.o;; )
	- Finish query-cache functions.
		-> Add expiration time settings
		-> Convert the makeDate() to actually use a custom date format
		-> If it creates a parent folder, make sure to create an index.html
	- Add the SNAPONE security check to all cached files tops. Including templates. =P
*/

class cache extends flat_file {
	private $dir = false; // Cache folder
	private $sql = false; // SQL cache folder = sql
	private $tpl = false; // Template cache folder = tpl
	private $cache = array(); // Holds loaded cache data.

	// Construct
	public function __construct($dir){
		// Creates the cache class and it's base.
		// @param	Type	Description
		// $dir		String	The directory for the cache to be located in.

		if(!file_exists($dir."sql/index.html")){
			$this->updateFile($dir."sql/index.html", "");
		}
		if(!file_exists($dir."tpl/index.html")){
			$this->updateFile($dir."tpl/index.html", "");
		}
		if(!file_exists($dir."index.html")){
			$this->updateFile($dir."index.html", "");
		}
		if(!file_exists($dir."README")){
			$this->updateFile($dir."README", "Everything located inside this folder is safe to delete at any time.");
		}

		$this->dir = $dir;
		$this->sql = $dir."sql/";
		$this->tpl = $dir."tpl/";
	}

	public function clearCache($file=false){
		// Clears the cache of EVERYTHING unless specified
		// @param	Type	Description
		// $file	Mixed	Either the name of the file or false to clear all.

		if($file === false){
			$this->clearDir($this->dir, array(), true);
		} else {
			$this->deleteFile($this->dir.$file.".php");
		}
	}







	// CACHING FUNCTIONS
	public function saveData($file, $data, $comm=""){
		// Saves specified data inside the specified file with the optional comment. Used to quickly add global PHP tags, etc.
		// @param	Type	Description
		// $file	String	The file inside the $this->dir folder.
		// $data	String	The data to be added. Make sure that it's been set correctly.
		// $comm	Mixed	The comment to be added. If false, add a created date.

		$d2 = "<"."?php\n\n/*\n".$comm."\nCreated: ".date("F d Y h:i:s A", time())."\n*/\n\n".$data."\n?".">";
		$this->updateFile($this->dir.$file.".php", $d2);
	}

	public function loadData($file, $type=false){
		// Loads the data from the file as a string or an array.
		// @param	Type	Description
		// $file	String	The file to be loaded.
		// $type	Boolean	Whether or not to return as a string or an array. (false = string, true = array)
		// Return	Return	Returns the contents of the file.

		if(file_exists($this->dir.$file.".php")){
			require_once $this->dir.$file.".php";
			if(isset($return_val)){
				return $return_val;
			}
		}
		return false;
	}





	// CORE FUNCTIONS
	public function loadSettings(){
		// Loads the config from the cache or via query.

		global $yak;

		if(file_exists($this->dir."settings.php")){
			require_once $this->dir."settings.php";
			return true; // Stop from executing scripting below.
		}
		global $db;

		$yak->settings = array(); // Is this really necessary? I guess so. =P
		$x = $db->query("SELECT name, value FROM ".DBPRE."config");
		while($con = $db->fetch($x)){
			$v = $con["value"];

			// Convert the types for storage easier. This is because settings should actually be this type anyway.
			if(is_numeric($v)){
				$v = intval($v);
			} else if($v == "true"){
				$v = true;
			} else if($v == "false"){
				$v = false;
			}

			$yak->settings[$con["name"]] = $v;
		}
		$this->saveData("settings", '$yak->settings = '.var_export($yak->settings, true).';', "This file was created in order to save a query and loading time. You may delete it if needed, but it will be recreated.");
		$db->free($x);
		return true;
	}

	public function loadGroups(){
		// Loads the groups from the cache or via query.

		global $yak;

		if(file_exists($this->dir."groups.php")){
			require_once $this->dir."groups.php";
			return true; // Stop from executing scripting below.
		}

		// Doesn't exist yet.
		global $db;

		$yak->groups = array(); // Is this really necessary? I guess so. =P
		$x = $db->query("SELECT * FROM ".DBPRE."groups");
		while($con = $db->fetch($x)){
			$yak->groups[$con["id"]] = $con;
		}

		$this->saveData("groups", '$yak->groups = '.var_export($yak->groups, true).';', "This file was created in order to save a query and loading time. You may delete it if needed, but it will be recreated.");
		$db->free($x);
		return true;
	}










	// Template functions
	public function isCachedTemplate($file, $tp, $time=0){
		// Checks whether a cached template exists or not.
		// @param	Type	Description
		// $file	String	The file to check for.
		// $tp		String	The template dirKey.
		// $time	Integer	The last time the real template file was edited.
		// Return	Return	Returns true if file exists, false otherwise

		$cached = true; // Set default value
		$fpath = $this->tpl.$tp."/".$file.".php"; // Save line space

		if(!file_exists($fpath)){ // Make sure file exists. Otherwise, kill it
			$cached = false;
		} else if($time > filemtime($fpath)){ // Check last edited times
			$cached = false;
			$this->deleteFile($fpath);
		}

		return $cached;
	}

	public function createCachedTemplate($file, $tp, $data){
		// Saves the specified data inside the specified file.
		// @param	Type	Description
		// $file	String	The file to be saved as.
		// $tp		String	The template dirKey.
		// $data	String	The actual template information.

		if(!file_exists($this->tpl.$tp."/index.html")){
			$this->updateFile($this->tpl.$tp."/index.html", "");
		}
		$this->updateFile($this->tpl.$tp."/".$file.".php", $data);
	}

	public function displayCachedTemplate($file, $tp, $vars=array()){
		// Displays the contents of a cached template.
		// @param	Type	Description
		// $file	String	The file to load from cache.
		// $tp		String	The template dirKey
		// $vars	Array	The vars array that is used to load information

		global $lang; // Load for language items to use
		require $this->tpl.$tp."/".$file.".php";
	}
















	// DB query functions
	public function queryCache($file){
		// Loads data from the cache of a query. Returns false on failure.
		// @param	Type	Description
		// $file	String	The file name (md5 of the query)
		// Return	Return	Returns false on failure and the data on success.

		if(file_exists($this->sql.$file.".php")){
			require $this->sql.$file.".php";
			return $return_value;
		}
		return false;
	}

	public function queryCacheStore($file, $dat, $query){
		// Stores the data from a query in the cache.
		// @param	Type	Description
		// $file	String	The file name (md5 of the query or custom)
		// $dat		Array	The result of the query to be stored.
		// $query	String	The actual query. Stored to keep track of data.
		
		$s = "<"."?php
/*
Query: ".$query."
Created: ".makeDate(time())."
*/

if(!defined(\"SNAPONE\")) exit;";
		$s .= "\n\n\$return_value = ".var_export($dat, true);
		$s .= "?".">";

		$this->updateFile($this->sql.$file.".php", $s);
		
	}

	public function queryCacheDelete($file){
		// Clears a cached query file
		// @param	Type	Description
		// $file	String	The file/query file to be cleared

		return $this->deleteFile($this->sql.$file.".php");
	}
}

$cache = new cache(CACHEDIR);
?>