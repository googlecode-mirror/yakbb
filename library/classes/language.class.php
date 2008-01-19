<?php

if(!defined("SNAPONE")) exit;

$languages = array();

class language {
	public $curlang = "en"; // Current language. Only public incase current ID is needed
	private $lang = array(); // Array to hold all the items.
	private $langDir = LANGUAGEDIR; // Directory of all items
	private $dir = ""; // Directory reference.

	public function __construct($lang=false){
		// Creates the language class.
		// @param	Type	Description
		// $lang	String	The language to load.

		if($lang !== false && !empty($lang)){
			$this->setLanguage($lang);
		} else {
			$this->loadDefault();
		}
	}

	public function loadDefault(){
		// Loads the default language.

		global $yak;
		$this->setLanguage($yak->settings["default_language"], true);
	}

	public function setLanguage($lang="en", $kill=false){
		// Sets the default language.
		// @param	Type	Description
		// $lang	String	The language to load.
		// $kill	Boolean	Whether or not to kill the script if the directory isn't found.

		if(!is_dir($this->langDir.$lang."/") && !empty($lang)){
			if($kill == true){
				die("No language directory.");
			} else {
				$this->loadDefault();
			}
		}

		global $languages;

		$this->dir = $this->langDir.$lang."/";
		require $this->dir."config.inc.php";
		$this->curlang = $lang;
		$this->lang = array(); // Clear lang.
		$this->learn("global");
	}

	public function learn($item){
		// Loads a specified language file
		// @param	Type	Description
		// $item	String	The language file name to learn from.

		global $yak;

		if(file_exists($this->dir.$item.".lang.php")){
			require $this->dir.$item.".lang.php";
			$this->lang	= array_merge($this->lang, $items); // $items is the item name in the section.
		} else {
			die("Lang file \"".$item."\" for lang \"".$this->curlang."\" does not exist.");
		}
	}

	public function templateLearn($item){
		// Loads a language file for a template.
		// @param	Type	Description
		// $item	String	The direct path to the language file to learn from.

		if(file_exists($item.".".$this->curlang.".lang.php")){
			require $item.".".$this->curlang.".lang.php";
			$this->lang = array_merge($this->lang, $items);
		}
	}

	public function item($item){
		// Loads a specific item.
		// @param	Type	Description
		// $item	String	The name of the item to load.
		// Return	Return	Returns the loaded item.

		// We check with isset() to eliminate minor errors that are annoying.
		return (isset($this->lang[$item])?$this->lang[$item]:"");
	}
}

?>