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
|| File: /library/template_calls/login.calls.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

if(!defined("SNAPONE")) exit;





/*====================================*\
|| FORM FUNCTIONS
||		loadSentUsername()
\*====================================*/
function loadSentUsername(){
	// Returns the username that was sent via the form or present in the URL bar.
	if(isset($_REQUEST["username"])){
		return secure($_REQUEST["username"]);
	}
	return "";
}





/*====================================*\
|| ERROR FUNCTIONS
||		countErrors()
||		loadError()
||		loadErrorReset()
\*====================================*/
function countErrors(){
	// Tells whether or not the login generated any errors

	global $login;
	return count($login->getErrors());
}

function loadError($reset=false){
	// Loads the next board.
	// @param	Type	Description
	// $reset	Boolean	If this is true, $count is reset to zero.

	global $login;
	static $count = 0;

	if($reset == true){
		$count = 0; // Valid?
		return false;
	}

	$err = $login->getErrors();
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





/*====================================*\
|| MISC FUNCTIONS
||		registrationRedirect()
\*====================================*/
function registrationRedirect(){
	// Tells whether or not the page is a redirect after registration.

	return isset($_GET["reg"]);
}


?>