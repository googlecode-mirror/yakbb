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
|| File: /library/template_calls/global.calls.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

/*	TODO
	userLink()
		- Prefixes (Suffixes?)
		- Group classname
		- Color
	getPermission()
		- Completely coded. kthxbai
*/

if(!defined("SNAPONE")) exit;





/*====================================*\
|| LINK FUNCTIONS
||		boardLink()
||		catLink()
||		userLink()
||		seoSwitch()
||		threadLink()
\*====================================*/
function boardLink($bdata, $urlonly=false){
	// Generates a link to viewing a board

	if(YAK_SEO == true){
		$url = "board".$bdata["id"]."/".urlSafe($bdata["name"], 20)."/";
	} else {
		$url = "?board=".$bdata["id"];
	}
	if($urlonly){
		return $url;
	}
	return "<a href=\"".$url."\">".$bdata["name"]."</a>";
}

function catLink($cdata, $urlonly=false){
	// Generates a link to viewing a category

	if(YAK_SEO == true){
		$url = "cat".$cdata["id"]."/".urlSafe($cdata["name"], 10)."/";
	} else {
		$url = "?cat=".$cdata["id"];
	}
	if($urlonly){
		return $url;
	}
	return "<a href=\"".$url."\">".$cdata["name"]."</a>";
}

function userLink($udata, $urlonly=false){
	// Generates a link to a user's profile

	if(YAK_SEO == true){
		$url = "viewprofile/".$udata["name"];
	} else {
		$url = "?user=".$udata["name"];
	}
	if($urlonly){
		return $url;
	}
	return "<a href=\"".$url."\">".$udata["display"]."</a>";
}

function seoSwitch($urlseo, $urlnormal){
	// Returns a SEO link or a normal link depending on the settings

	if(YAK_SEO){
		return $urlseo;
	}
	return $urlnormal;
}

function threadLink($tdata, $urlonly=false){
	// Generates a link to viewing a thread

	if(YAK_SEO == true){
		$url = "thread".$tdata["id"]."/".urlSafe($tdata["title"], 20)."/";
	} else {
		$url = "?thread=".$tdata["id"];
	}
	if($urlonly){
		return $url;
	}
	return "<a href=\"".$url."\">".$tdata["title"]."</a>";
}





/*====================================*\
|| INFORMATION FUNCTIONS
||		getPageTitle()
||		getPermission()
||		getSetting()
||		isGuest()
||		lang()
||		viewingPage()
\*====================================*/
function getPageTitle(){
	// Returns the current title for the page

	global $tp;
	return $tp->getTitle();
}

function getPermission($perm){
	// Looks up a specified permission. Only returns true or false

	return false; // Permission wasn't found. Default to false.
}

function getSetting($settings){
	// Returns the value of a setting

	global $yak;
	if(isset($yak->settings[$settings])){
		return $yak->settings[$settings];
	}
	return false;
}

function isGuest(){
	// Returns a boolean of whether or not the user is a guest

	global $user;
	return !!($user["id"] == 0);
}

function lang($item){
	// Returns a language item for the specified input

	global $lang;
	return $lang->item($item);
}

function viewingPage(){
	// Returns the ID of the file we're currently accessing

	global $yak;
	return $yak->curPage;
}





/*====================================*\
|| STATISTIC FUNCTIONS
||		getGenTime()
||		getQueries()
\*====================================*/
function getGenTime(){
	// Returns the time it took to run the script

	global $starttime;
	return substr(((array_sum(explode(" ", microtime()))) - $starttime), 0, 6);
}

function getQueries(){
	// Returns the number of queries that were ran

	global $db;
	return $db->queries;
}





/*====================================*\
|| MISC FUNCTIONS
||		baseUrl()
||		compileNavTree()
||		templatePath()
||		upgradeAvailable()
\*====================================*/
function baseUrl(){
	// Returns the base access URL

	return "http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), "/\\")."/";
}

function compileNavTree($div){
	// $div = The divider

	global $tp;
	return join($tp->getNav(), $div);
}

function templatePath(){
	// Returns the directory path for the current template

	global $tp;
	return $tp->getTemplatePath();
}

function upgradeAvailable(){
	// Returns true if an upgrade is available

	return !!(CURRENTDBVERSION > DBVERSION || version_compare(CURRENTYAKVERSION, YAKVERSION));
}


?>