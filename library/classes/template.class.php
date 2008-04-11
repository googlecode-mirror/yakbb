<?php

if(!defined("SNAPONE")) exit;

/*	NOTES
	- The templating system for YakBB was inspired by viewing the template system 
	of many forum systems. One large influence was phpBB2/3 and its templating 
	system. It has had it's own tweaks added in however and is coded completely from
	scratch.


	TODO
	userLink()
		- Needs to add the group color
		- Prefixes??
	- Nav tree dividers should be template specific
*/

class template {
	private $dir = false; // Template directory path
	public $dirKey = ""; // Template directory key. Used with caching. This is public incase current template ID is needed.
	private $imgdir = false; // Image directory path

	private $files = array(); // List of files to load (in that order). [$distinct_name] = $file_name;
	private $vars = array(); // Holds all of the variables. The key is the name in relation to the $files array. Sub-items of that array are the replacement. Key = term, value = replacement
	private $gvars = array(); // Global variables. Same key-value relationship as $vars

	private $title = "No title"; // Title of the page
	private $navs = array(); // Nav tree holder
	public $seo = false; // Whether or not to use SEO

	// Stat Variables
	private $cachedAlready = 0; // Number of TPL files loaded from cache.
	private $loopKey = 0; // Number of loops. This is used to prevent craching via overload in parsing from includes.

	// CREATION, CORE, AND MISC FUNCTIONS

	public function loadTemplate($dir, $kill=false){
		// Changes the directory
		// @param	Type	Description
		// $dir		String	The name of the directory, minus trailing slash.
		// $kill	Boolean	Whether or not to kill it if the directory isn't found.
		// Return	Return	Returns whether it was successfully changed. False can also mean it used default.

		if(!empty($dir) && is_dir(TEMPLATESDIR.$dir."/")){
			global $templates;
			require TEMPLATESDIR.$dir."/config.inc.php"; // Load configuration for template.

			if($templates[$dir]["config"]["development"] == false){
				// See if development mode is on or off.
				$this->dirKey = $dir;
				$this->dir = TEMPLATESDIR.$dir."/";
				$this->imgdir = $this->dir."images/";
				$this->loadFile("header", "header.tpl");
				return true;
			}
		}

		if($kill === true){ // Kill check
			die("Unable to load a template.");
		} else if($this->dir === false){ // Load default override
			$this->loadDefault(true);
			return false;
		}
	}

	public function loadDefault($x=true){
		// Loads the default template. Calls defaultTemplateId() from the cache class to load the ID.
		// @param	Type		Description
		// $x		Boolean		A variable passed onto loadTemplate if a kill needs to be executed upon failure. Defaults to true.

		global $yak;
		$this->loadTemplate($yak->settings["default_template"], $x);
	}




	




	// Display functions
	public function display(){
		// Displays the pages.

		global $user, $yak, $db, $starttime, $lang;

		array_unshift($this->navs, '<a href="./">'.$yak->settings["board_title"].'</a>');
		$this->addGlobal($yak->settings); // Register settings in global variables.
		$this->addGlobal(array(
			"TPATH" => $this->dir, // MUST NOT BE OVERRIDDEN
			"title" => $this->title,
			"guest" => !!($user["name"] == "guest"),
			"navtree" => implode($yak->settings["nav_divider"], $this->navs)
		));
		$lang->templateLearn($this->dir."lang_override");

		$this->loadFile("footer", "footer.tpl");
		$this->preParseFiles();
		$gentime = substr(((array_sum(explode(" ", microtime()))) - $starttime), 0, 6);
		$this->addVar("footer", array(
			"GENTIME" => $gentime, // We add gen time here because prePareseFiles can take a bit of time. =P
			// "QUERYTIME" => substr($db->time, 0, 6),
			"QUERIES" => $db->queries,
			"TPLFILES" => count($this->files),
			"TPLCACHED" => $this->cachedAlready,
			"MEMUSE" => number_format(memory_get_usage()),
			"MEMPEAK" => (function_exists("memory_get_peak_usage")?number_format(memory_get_peak_usage()):"")
		));
		$this->displayFiles();
		exit; // Incase extra scripting is trying to execute for some reason
	}

	public function error($err){
		// Used in generating an error message.
		// @param	Type	Description
		// $err		Mixed	An array of the errors or a string of an error.

		global $lang;
		$lang->learn("errors");
		if(!is_array($err)){
			$err = array($err);
		}

		// Fix the glitch of errors not overriding previous templates
		$this->files = array("header" => $this->files["header"]);

		$this->loadFile("error", "error.tpl", array(
			"errors" => array_map(array($lang, "item"), $err)
		));
		$this->addNav($lang->item("nav_error"));
		$this->setTitle("error");
		$this->display();
	}

	private function displayFiles(){
		// Displays all files in the $this->files array.

		global $cache;
		foreach($this->files as $name=>$file){
			$cache->displayCachedTemplate($file, $this->dirKey, array_merge($this->gvars, $this->vars[$name]));
		}
	}

	private function preParseFiles(){
		// Preparses all files and saves the cached parsing.
		// Should this be public for the pre-parse all files?

		global $cache;
		foreach($this->files as $key => $value){
			if(!$cache->isCachedTemplate($value, $this->dirKey, filemtime($this->dir.$value))){
				$cache->createCachedTemplate($value, $this->dirKey, $this->preParse($value));
			} else {
				$this->cachedAlready++;
			}
		}
	}























	// Public template file management functions
	public function loadFile($name, $file, $parse=array()){
		// Loads a template file.
		// @param	Type	Description
		// $name	String	ID name of the file. Must be unique.
		// $file	String	Name of the actual file.
		// $parse	Array	Some variables to parse into the file.

		if(!file_exists($this->dir.$file)){
			die("Template file \"".secure($file)."\" does not exist.");
		}
		$this->vars[$name] = $parse;
		$this->files[$name] = $file;
	}

	public function addVar($file, $type, $value=false){
		// Adds a variable to register. Actually takes an array too.
		// @param	Type	Description
		// $file	String	The name of the file section to add these variables to
		// $type	Mixed	An array or a single string of the items to add. IF an array, key is the normal $type and value of that is $value
		// $value	Mixed	A single string value or a boolean false
		// Return	Return	Returns false if file can't be found. Returns true otherwise

		if(!isset($this->vars[$file])){
			return false;
		}

		// Do array portion
		// May rewrite this portion later to use array_merge instead. Will test performance items to see
		if($value === false && is_array($type)){
			foreach($type as $key => $val){
				$this->addVar($file, $key, $val);
			}
			return true; // Prevent from trying to add an array.
		}

		// Add normal items
		$this->vars[$file][$type] = $value;
		return true;
	}

	public function addGlobal($key, $value=false){
		// Adds a global variable.
		// @param	Type	Description
		// $key		Mixed	Either the key to add or an array of items to add.
		// $value	Mixed	Either the value to add or boolean false.
		// Return	Return	Always returns true.

		if(is_array($key)){
			reset($key);
			foreach($key as $k => $v){
				$this->addGlobal($k, $v);
			}
			return true;
		}

		$this->gvars[$key] = $value;
	}

	public function setTitle($tit, $ulang=true){
		// Sets the title of the page
		// @param	Type	Description
		// $tit		String	The new title
		// $ulang	Boolean	Whether or not the new title is from a lang variable.

		if($ulang){
			global $lang;
			$this->title = $lang->item($tit."_title");
		} else {
			$this->title = $tit;
		}
	}

	public function addNav($nav){
		// Adds a nav tree item.
		// @param	Type	Description
		// $nav		String	The nav (including link if used) to be added to the nav tree.

		$this->navs[] = $nav;
	}

	public function setSEO(){
		// Enables SEO links

		$this->seo = true;
	}























// Template parser
	private function preParse($file, $fdat=false){
		// Preparses the specified file.
		// @param	Type	Description
		// $file	String	The file to preparse.
		// $fdat	String	The file data. If false, will load data.
		// Return	Return	Returns the preparsed content.

		global $yak;

		if(!file_exists($this->dir.$file) && $fdat === false){
			die("Unable to load template file \"".$file."\"");
		}

		if($fdat === false){
			$fdat = file_get_contents($this->dir.$file);
		}

		$fdat = preg_replace_callback("/<!-- include: (.+?) -->/i", array($this, "includeParser"), $fdat);

		// Repeats
		$fdat = preg_replace_callback("/<!--\s?repeat:(.+?):(.+?)\s?-->/i", array($this, "repeatParser"), $fdat);
		$fdat = preg_replace("/<!--\s?endrepeat\s?-->/i", "<"."?php }} ?".">", $fdat);

		// IFs
		$fdat = preg_replace_callback("/<!--\s?((else)?if.+?)\s?-->/i", array($this, "ifParser"), $fdat);
		$fdat = preg_replace("/<!--\s?else\s?-->/i", "<"."?php } else { ?".">", $fdat);
		$fdat = preg_replace("/<!--\s?endif\s?-->/i", "<"."?php } ?".">", $fdat);

		// Language variables
		$fdat = preg_replace("/{LANG}(.+?){\/LANG}/i", "<"."?php echo \$lang->item(\"$1\"); ?".">", $fdat);

		// Variables
		$fdat = preg_replace_callback("/{([^\s]+?)}/", array($this, "variableParser"), $fdat);

		// SEO stuff
		if($this->seo === true){
			$fdat = preg_replace("/\?action=(.+?)&(amp;)?/i", "./$1.html?", $fdat);
			$fdat = preg_replace("/\?action=(.+?)\b/i", "./$1.html", $fdat);
		}

		if($yak->settings["strip_tab_spacing"] == 1 || $yak->settings["strip_tab_spacing"] == true){
			$fdat = preg_replace("/\t+/", "", $fdat);
		}
		return $fdat;
	}

	private function variableParser($m, $ech=null){
		// Returns an usable variable string
		// @param	Type	Description
		// $m		Array	The matches of the variable
		// $ech		Boolean	Whether or not to return the content in echoable form.
		// Return	Return	The parsed variable in normal or echo form.

		if(is_array($m)){
			$m = $m[1];
			if($ech === null){
				$ech = true;
			}
		} else if($ech == null){
			$ech = false;
		}

		if(preg_match("/^repeat:/i", $m) || preg_match("/->/", $m)){
			$m = preg_replace("/^repeat:/i", "", $m);
			$d = explode("->", $m);
			$varname = "\$".$d[0];
			foreach($d as $k => $v){
				if($k != 0){
					$varname .= "[\"".$v."\"]";
				}
			}
		} else {
			$varname = "\$vars[\"".$m."\"]";
		}

		if($ech == true){
			return "<"."?php if(isset(".$varname.")) echo ".$varname."; ?".">";
		} else {
			return $varname;
		}
	}

	private function includeParser($m){
		// Called by preParse() to parse an include section. This is done because of 
		// the need to call a function and the laziness of me and eval. Plus, we can 
		// prevent an overload more easily this way too.
		// @param	Type	Description
		// $m		Array	This contains all matches.
		// Return	Return	Returns the new value.

		if(++$this->loopKey > 100){ // Only loop 100 times incase the user is stupid and put a continuous include.
			return "";
		}
		return $this->preParse($m[1]);
	}

	private function repeatParser($m){
		// Called by preParse() to parse a repeat correctly because of depth issues.
		// @param	Type	Description
		// $m		Array	This contains all matches.
		// Return	Return	Returns the new value

		// Get the depth right
		$varname = $this->variableParser($m, false);

		// Send back the replacement string.
		return "<"."?php
if(isset(".$varname.") && is_array(".$varname.")){
	reset(".$varname.");
	foreach(".$varname." as \$".$m[2]."){
?".">";
	}

	private function ifParser($m){
		// Called by preParse() to parse the variables inside an if.
		// @param	Type	Description
		// $m		Array	This contains all matches. We only need $m[1] though.
		// Return	Return	Returns the new value.

		$m = $m[1];
		$m = preg_replace("/else/i", "} else ", $m); // Add end bracket for the else.
		$m = preg_replace("/if/i", "if(", $m); // Add the first parenthesis around the if since it's in $m. End parenthesis is in the return
		while(preg_match("/{(.+?)}/", $m, $v)){
			$m = preg_replace("/".$v[0]."/", $this->variableParser($v[1], false), $m);
		}
		$m = preg_replace("/\sand\s/", " && ", $m); // And
		$m = preg_replace("/\sor\s/", " || ", $m); // Or
		$m = preg_replace("/\sis not\s/", " != ", $m); // Alternative to using !=
		$m = preg_replace("/\sis\s/", " == ", $m); // Alternative to using ==
		$m = preg_replace("/\snot\s/", " ! ", $m); // Alternative to using !

		return "<"."?php ".$m."){ ?".">"; // Return our new value
	}























// Link management functions (board, thread, profile, etc.)
	public function boardLink($bid, $bname){
		if($this->seo){
			return '<a href="./board-'.$bid.'.html" onclick="linkBubble=false">'.$bname.'</a>';
		}
		return '<a href="?board='.$bid.'" onclick="linkBubble=false">'.$bname.'</a>';
	}
	public function threadLink($tid, $tname){
		if($this->seo){
			return '<a href="./thread-'.$tid.'.html" onclick="linkBubble=false">'.$tname.'</a>';
		}
		return '<a href="?thread='.$tid.'" onclick="linkBubble=false">'.$tname.'</a>';
	}
	public function userLink($uid, $uname, $ugroup){
		if($this->seo){
			return '<a href="./user-'.$uid.'.html">'.$uname.'</a>';
		}
		return '<a href="?user='.$uid.'">'.$uname.'</a>';
	}
	public function catLink($cid, $cname){
		if($this->seo){
			return '<a href="./cat-'.$cid.'.html">'.$cname.'</a>';
		}
		return '<a href="?cat='.$cid.'">'.$cname.'</a>';
	}
}

$templates = array(); // This will hold the template's information.
$tp = new template();
?>