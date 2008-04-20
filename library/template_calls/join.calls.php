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
|| File: /library/template_calls/join.calls.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

if(!defined("SNAPONE")) exit;





/*====================================*\
|| FORM FUNCTIONS
||		emailOptinChecked()
||		loadSentDisplayname()
||		loadSentEmail1()
||		loadSentEmail2()
||		loadSentUsername()
||		showEmailChecked()
\*====================================*/
function emailOptinChecked(){
	// Returns a boolean of if email optin was checked

	return !!(isset($_POST["emailoptin"]) && intval($_POST["emailoptin"]) == 1);
}

function loadSentDisplayname(){
	// Returns the displayname that was sent via the form or present in the URL bar.
	if(isset($_REQUEST["displayname"])){
		return secure($_REQUEST["displayname"]);
	}
	return "";
}

function loadSentEmail1(){
	// Returns the E-mail that was sent via the form
	if(isset($_POST["email1"])){
		return secure($_POST["email1"]);
	}
	return "";
}

function loadSentEmail2(){
	// Returns the second E-mail that was sent via the form
	if(isset($_POST["email1"])){
		return secure($_POST["email2"]);
	}
	return "";
}

function loadSentUsername(){
	// Returns the username that was sent via the form or present in the URL bar.
	if(isset($_REQUEST["username"])){
		return secure($_REQUEST["username"]);
	}
	return "";
}

function showEmailChecked(){
	// Returns a boolean of if show email was checked

	return !!(isset($_POST["showemail"]) && intval($_POST["showemail"]) == 1);
}





/*====================================*\
|| ERROR FUNCTIONS
||		countErrors()
||		loadError()
||		loadErrorReset()
\*====================================*/
function countErrors(){
	// Tells whether or not the login generated any errors

	global $join;
	return count($join->getErrors());
}

function loadError($reset=false){
	// Loads the next board.
	// @param	Type	Description
	// $reset	Boolean	If this is true, $count is reset to zero.

	global $join;
	static $count = 0;

	if($reset == true){
		$count = 0; // Valid?
		return false;
	}

	$err = $join->getErrors();
	if(isset($err[$count])){
		$err = $err[$count];
		$count++;
		return $err;
	}
	return false;
}

function loadErrorReset(){
	// Resets the loadError() loop

	loadError(true);
}


?>