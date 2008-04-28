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
|| File: /library/template_calls/post.calls.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

if(!defined("SNAPONE")) exit;


/*	TODO
	showDescription()
		- Needs to have support for modify mode and check if it's the first post. Returns false for now
	getFormAction()
		- Does not return an action when in modify mode
*/





/*====================================*\
|| INFORMATION FUNCTIONS
||		getMode()
||		showDescription()
\*====================================*/
function getMode(){
	// Returns the current mode

	global $post;
	return $post->getMode();
}

function showDescription(){
	// Whether or not to show the description.

	global $post;
	switch($post->getMode()){
		case "reply":
			return false;
		case "newthread":
			return true;
		case "modify":
			return false;
		default:
			return false;
	}
}





/*====================================*\
|| FORM FUNCTIONS
||		getFormAction()
||		getSentDescription()
||		getSentMessage()
||		getSentTitle()
\*====================================*/
function getFormAction(){
	// Returns the form action

	global $post;
	switch($post->getMode()){
		case "reply":
			return replyLink($post->getId(), true);
		case "newthread":
			return newThreadLink($post->getId(), true);
		case "modify":
			return "";
		default:
			return "";
	}
}

function getSentDescription(){
	// Returns the description

	global $post;
	return $post->getItem("description");
}

function getSentMessage(){
	// Returns the message

	global $post;
	return $post->getItem("message");
}

function getSentTitle(){
	// Returns the title

	global $post;
	return $post->getItem("title");
}





/*====================================*\
|| ERROR FUNCTIONS
||		countErrors()
||		loadError()
||		loadErrorReset()
\*====================================*/
function countErrors(){
	// Tells whether or not the post attempt generated any errors

	global $post;
	return count($post->getErrors());
}

function loadError($reset=false){
	// Loads the next error
	// @param	Type	Description
	// $reset	Boolean	If this is true, $count is reset to zero.

	global $post;
	static $count = 0;

	if($reset == true){
		$count = 0; // Valid?
		return false;
	}

	$err = $post->getErrors();
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