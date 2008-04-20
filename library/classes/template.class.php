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
|| File: /library/classes/template.class.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

if(!defined("SNAPONE")) exit;

/*	NOTES
	- The templating system for YakBB was inspired by viewing the template system 
	of many templating systems and I got most of the inspiration for our system from
	WordPress (WordPress.org). The system is coded uniquely and from scratch however,
	thus any similarities in the actual coding are completely accidental.
*/

class template {
	private $dir = false; // Template directory path
	private $dirKey = ""; // Template directory key. Used with caching. This is public incase current template ID is needed.

	private $files = array(); // List of files to load (in that order). [$distinct_name] = $file_name;

	private $title = "No title"; // Title of the page
	private $navs = array(); // Nav tree holder
	private $seo = false; // Whether or not to use SEO. Set via the coding below.

	// CREATION, CORE, AND MISC FUNCTIONS
	public function __construct(){
		// Loads the global calls here... some are actually used in the coding.
		require LIBDIR."template_calls/global.calls.php";
	}
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

	public function getDirKey(){
		// Returns the current directory key for the current template

		return $this->dirKey;
	}




	




	// Display functions
	public function display(){
		// Displays the pages.

		global $user, $yak, $db, $lang;

		array_unshift($this->navs, '<a href="./">'.$yak->settings["board_title"].'</a>');
		$lang->templateLearn($this->dir."lang_override");

		$this->loadFile("footer", "footer.tpl");

		// Finish up some loading stuff before loading files... for example, the template calls.
		if(file_exists(LIBDIR."template_calls/".$yak->curPage.".calls.php")){
			require LIBDIR."template_calls/".$yak->curPage.".calls.php";
		}

		// Load the files in the list
		foreach($this->files as $k => $v){
			require $this->dir.$v;
		}

		// And.... we're done!
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

		$this->errors = $err;

		// Load the error functions
		require LIBDIR."template_calls/error.calls.php";

		// Fix the glitch of errors not overriding previous templates
		$this->files = array("header" => $this->files["header"]);

		$this->loadFile("error", "error.tpl");
		$this->addNav($lang->item("nav_error"));
		$this->setTitle("error");
		$this->display();
	}

	public function getErrors(){
		// Returns a list of all the errors

		return $this->errors;
	}













	// Public template file management functions
	public function loadFile($name, $file){
		// Loads a template file.
		// @param	Type	Description
		// $name	String	ID name of the file. Must be unique.
		// $file	String	Name of the actual file.

		if(!file_exists($this->dir.$file)){
			die("Template file \"".secure($file)."\" does not exist.");
		}
		$this->files[$name] = $file;
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

	public function getTitle(){
		// Returns the current page title

		return $this->title;
	}

	public function addNav($nav){
		// Adds a nav tree item.
		// @param	Type	Description
		// $nav		String	The nav (including link if used) to be added to the nav tree.

		$this->navs[] = $nav;
	}

	public function getNav(){
		// Returns the nav tree array

		return $this->navs;
	}

	public function setSEO($check=true){
		// Enables SEO links
		// @param	Type	Description
		// $check	Boolean	Whether or not to have SEO enabled.

		define("YAK_SEO", $check);
	}

	public function getTemplatePath(){
		// Returns a path to the template

		return $this->dir;
	}
}

$templates = array(); // This will hold the template's information.
$tp = new template();
?>