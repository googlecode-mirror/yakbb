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
	isAdmin()
		- Needs to actually check
	getPermission()
		- Completely coded. kthxbai
	botSecureEmail()
		- Needs to actually secure it instead of printing out a link
		- Needs to test to see if the user can even view e-mails
	showGender()
		- Needs to add icon support
*/

if(!defined("SNAPONE")) exit;





/*====================================*\
|| LINK FUNCTIONS
||		boardLink()
||		catLink()
||		newThreadLink()
||		replyLink()
||		seoSwitch()
||		threadLink()
||		userLink()
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

function newThreadLink($bdata, $urlonly=false){
	// Generates a link to posting a thread

	if(is_array($bdata)){
		$bdata = $bdata["id"];
	}

	if(YAK_SEO == true){
		$url = "newthread".$bdata."/";
	} else {
		$url = "?action=newthread&amp;bid=".$tdata["id"];
	}
	if($urlonly){
		return $url;
	}
	return "<a href=\"".$url."\">".$tdata["title"]."</a>";
}

function seoSwitch($urlseo, $urlnormal){
	// Returns a SEO link or a normal link depending on the settings

	if(YAK_SEO){
		return $urlseo;
	}
	return $urlnormal;
}

function replyLink($tdata, $urlonly=false, $quote=0){
	// Generates a link to reply to a thread

	// We only want an ID. Some may just send the thread data out of habit
	if(is_array($tdata)){
		$tdata = $tdata["id"];
	}

	if(YAK_SEO == true){
		$url = "reply".$tdata."/";
		if($quote != 0){
			$url .= "quote".$quote."/";
		}
	} else {
		$url = "?action=reply&amp;tid=".$tdata;
		if($quote != 0){
			$url .= "&amp;quote=".$quote;
		}
	}
	if($urlonly){
		return $url;
	}
	global $lang;
	return "<a href=\"".$url."\">".$lang->item("reply_to_thread")."</a>";
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





/*====================================*\
|| INFORMATION FUNCTIONS
||		getPageTitle()
||		getPermission()
||		getSetting()
||		isAdmin()
||		isGuest()
||		lang()
||		showGender()
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

function isAdmin(){
	// Does the user have access to the admin area?
	// THIS IS ONLY FOR USE IN TEMPLATES. BACKSIDE SHOULD NOT USE IT

	return true;
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

function showGender($get, $type=1){
	// Outputs a gender name or icon.
	// $type -> 1 = full string, 0 = icon

	if($type == 0) // Not coded yet
		return "";

	switch($get){
		case GENDER_MALE:
			if($type == 1){
				return lang("gender_male_string");
			}
		case GENDER_FEMALE:
			if($type == 1){
				return lang("gender_female_string");
			}
		case GENDER_NONE:
			if($type == 1){
				return lang("gender_none_string");
			}
	}
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
||		botSecureEmail()
||		compileNavTree()
||		templatePath()
||		upgradeAvailable()
\*====================================*/
function baseUrl(){
	// Returns the base access URL

	return "http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), "/\\")."/";
}

function botSecureEmail($email, $inner="E-mail"){
	// Secures an e-mail for when a bot is viewing it. This should ALWAYS be applied

	return '<a href="mailto:'.$email.'">'.$inner.'</a>';
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