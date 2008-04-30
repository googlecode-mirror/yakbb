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
|| File: /global.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

/*	TODO
		CORE
			- Check to see if magic_quotes_runtime is actually disabled originally so we can 
			check to make sure it's working
			- Check to see if register_globals is on and the safety is working or if runs when
			register_globals is disabled
		BAN SYSTEM
			- Enable wild cards for the IPs
			- Needs a cache time or to not be cached??
			- Test e-mail and username
		OTHER
			- Update the "Upgrade" table to use language variables.
*/

if(!defined("SNAPONE")) exit;

// Check PHP version. It could have changed since installation for some reason
if(version_compare(phpversion(), "5.1.2") < 0){
	die("You must be running at least PHP 5.1.2.");
}

// Check install. Redirect if not installed.
if(!file_exists("./config.inc.php")){
	$host = $_SERVER['HTTP_HOST'];
	$uri = rtrim(dirname($_SERVER['PHP_SELF']), "/\\");
	header("Location: http://".$host.$uri."/install/");
	exit;
}

// Register GLOBALS protection. YakBB should be secure already, but it doesn't hurt.
// Snippet from http://www.php.net/manual/en/faq.misc.php#faq.misc.registerglobals
if(ini_get('register_globals')){
	if(isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])){
		die('GLOBALS overwrite attempt detected');
	}

	// Variables that shouldn't be unset
	$noUnset = array('GLOBALS',		'_GET',
					 '_POST',		'_COOKIE',
					 '_REQUEST',	'_SERVER',
					 '_ENV',		'_FILES');

	$input = array_merge($_GET,		$_POST,
						 $_COOKIE,	$_SERVER,
						 $_ENV,		$_FILES,
						 isset($_SESSION) && is_array($_SESSION)?$_SESSION:array());
   
	foreach($input as $k => $v){
		if(!in_array($k, $noUnset) && isset($GLOBALS[$k])){
			unset($GLOBALS[$k]);
		}
	}
	unset($noUnset);
	unset($input);
}

// Start load time tracker
$starttime = array_sum(explode(" ", microtime()));

// Set error reporting for now
error_reporting(E_ALL);

// Security stuff before we load anything.
set_magic_quotes_runtime(false); // Disable magic quotes from DB.

// Strip slashes management
if(get_magic_quotes_gpc()){
	function ss($n){
		foreach($n as $k=>$v)
			if(is_array($v)){
				$n[$k] = ss($v);
			} else {
				$n[$k] = stripslashes($v);
			}
	}
	ss(&$_REQUEST);
	ss(&$_POST);
	ss(&$_GET);
}

session_start();

// Load some constants and a bit of config.
require_once "./constants.inc.php";
require_once "./config.inc.php";

// Make sure they aren't using an out-of-date alpha. This will be removed at beta release
if(YAKVERSION == "0.0.1a"){
	die("Please reinstall YakBB. You're using an out-of-date alpha version. (".YAKVERSION.")");
}

// Let's load the library stuff. Start with common.php since it loads most of what we need. We'll load any additional classes we need once we're inside the INCLUDESDIR files.
require_once LIBDIR."common.php";

// Define some global variables. These must NOT be overriden anywhere else or it will break the forum script.
$user = array();
$guest = true; // We'll use this to help us figure out if the user has logged in.

// Check cookies
if(isset($_COOKIE[DBPRE."user"]) && isset($_COOKIE[DBPRE."pass"])){
	$plugins->callhook("global_user_validation_start");

	// Store the username, query the DB, and check the password
	$username = $db->secure(secure($_COOKIE[DBPRE."user"]));
	$db->query("
		SELECT
			u.*
		FROM
			yakbb_users u
		WHERE
			u.name='".$username."'
		LIMIT 1
	");
	if($db->numRows() == 1){
		$user = $db->fetch();
		if($_COOKIE[DBPRE."pass"] == $user["password"]){ // Compare login here.
			$plugins->callhook("global_user_validation_success");
			$guest = false;
		}
	}
	$db->free();

	// User didn't pass, so let's unset the other stuff
	if($guest === true){
		unset($user); // Security reasons. Just in case.
		setcookie(DBPRE."user", "", 0);
		setcookie(DBPRE."pass", "", 0);
		session_regenerate_id();
	}
}

if($guest === true){
	// After all this time, the user isn't a valid/logged in user.
	// Time to set them as a guest. We'll start be resetting the user array.

	$plugins->callhook("global_guest_true");

	$user = array(
		"id" => 0,
		"name" => "guest",
		"display" => "Guest",
		"group" => "-1",
		"dateformat" => "1",
		"timezone" => $yak->settings["defaulttimezone"],
		"dst" => $yak->settings["dst"]
	);

	$user = $plugins->callhook("global_guest_settings", $user);

	// Load the default items for language and template
	$lang = new language();
	$tp->loadDefault();
} else {
	// Do some user specific things. =P
	$user = $plugins->callhook("global_user_settings", $user);

	// Load the user's defaults for language and template
	$lang = new language($user["language"]);
	$tp->loadTemplate($user["template"]);

	// Update last login time, etc. if needed
	if(!isset($_SESSION["last_login"]) || $_SESSION["last_login"] < time()-15){
		// Set invisible section
		if($user["invisible"] == 1){
			$invis = "invis";
		} else {
			$invis = "";
		}

		// Update last login time and ip
		$db->query("UPDATE yakbb_users SET lastlogin".$invis."='".time()."', lastip='".$yak->ip."' WHERE id='".$user["id"]."'");
		$_SESSION["last_login"] = time();
	}
}

// Check if user is banned
// Load cache of bans... this may be switched to not using a cache later
$yak->bans = $db->cacheQuery("
	SELECT
		id, type, value, expires
	FROM
		yakbb_bans
", 10, "bans");
foreach($yak->bans as $k => $v){
	if($v["expires"] <= time()){ // Make sure the ban hasn't expired
		continue;
	}
	switch($v["type"]){
		case 1: // IP
			if($v["value"] == $yak->ip){
				$tp->error("banned");
			}
			break;
		case 2: // e-mail
			if($guest === false && $user["email"] == $v["value"]){
				$tp->error("banned");
			}
			break;
		case 3: // username
			if($guest === false && $user["name"] == $v["value"]){
				$tp->error("banned");
			}
			break;
	}
}


// Load specified actions
$def = "home"; // Action to default to. This is changeable by a plugin later on
$va = array( // Valid Actions
	// SYNTAX: "urlaccess" => "modulefile", // <-- No comma on last line
	// BLANK ONES HAVE NOT HAD FILES ADDED YET
	"home" => "home",
	"index" => "home",

	// Main menu. Join/register/signup and login/signin are alternatives.
	"join" => "join",
	"register" => "join",
	"signup" => "join",
	"login" => "login",
	"signin" => "login",
	"help" => "help",
	"search" => "search",
	"usercp" => "",
	"members" => "",
	"logout" => "logout",

	// Standard viewing pages
	"board" => "viewboard",
		"newthread" => "post",
	"thread" => "viewthread",
		"reply" => "post",
	"user" => "", // "viewprofile",

	// Development tools only.
	"upgrade" => "upgrade",
	"cc" => "clear_cache",
);


// We call these plugin hooks here because it should be able to change the default action and append additional actions
$def = $plugins->callhook("global_default_action", $def);
$va = $plugins->callhook("global_valid_actions", $va);

// Use request because it can be POST or GET.... COOKIE too though.
// Let's look at that again later for the cookie reason
if(isset($_REQUEST["action"])){
	$act = $_REQUEST["action"];
} else if(isset($_REQUEST["board"])){
	$act = "board";
} else if(isset($_REQUEST["thread"])){
	$act = "thread";
} else if(isset($_REQUEST["user"])){
	$act = "user";
}

if(!isset($act)){
	$act = $def;
}

if(in_array($act, array_keys($va)) && !empty($va[$act]) && file_exists(INCLUDESDIR.$va[$act].".php")){
	// May have passed the action check, but the file may not exist because of a mistake or something.
	$n = $va[$act];
	$yak->curPage = $n;
	unset($va);
	require_once INCLUDESDIR.$n.".php";
	if(class_exists($n)){
		${$n} = new $n();
	}
} else {
	unset($va);
	$tp->error("invalid_include");
}

$tp->display();
?>