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
function boardLink($bdata){
	// Generates a link to viewing a board

	if(YAK_SEO == true){
		$url = "board".$bdata["id"]."/".urlSafe($bdata["name"], 20)."/";
	} else {
		$url = "?board=".$bdata["id"];
	}
	return "<a href=\"".$url."\">".$bdata["name"]."</a>";
}

function catLink($cdata){
	// Generates a link to viewing a category

	if(YAK_SEO == true){
		$url = "cat".$cdata["id"]."/".urlSafe($cdata["name"], 10)."/";
	} else {
		$url = "?cat=".$cdata["id"];
	}
	return "<a href=\"".$url."\">".$cdata["name"]."</a>";
}

function userLink($udata){
	// Generates a link to a user's profile

	if(YAK_SEO == true){
		$url = "viewprofile/".$udata["name"];
	} else {
		$url = "?user=".$udata["name"];
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

function threadLink($tdata){
	// Generates a link to viewing a thread

	if(YAK_SEO == true){
		$url = "thread".$tdata["id"]."/".urlSafe($tdata["name"], 20)."/";
	} else {
		$url = "?thread=".$tdata["id"];
	}
	return "<a href=\"".$url."\">".$tdata["name"]."</a>";
}





/*====================================*\
|| INFORMATION FUNCTIONS
||		getPermission()
||		getSetting()
||		isGuest()
||		lang()
||		viewingPage()
\*====================================*/
function getPermission($perm){
	// Looks up a specified permission
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