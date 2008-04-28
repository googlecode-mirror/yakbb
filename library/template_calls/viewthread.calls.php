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
|| File: /library/template_calls/viewthread.calls.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

if(!defined("SNAPONE")) exit;





/*====================================*\
|| BOARD FUNCTIONS
||		loadPost()
||		loadPostReset()
||		postCount()
||		threadId()
\*====================================*/
function loadPost($reset=false){
	// Loads the next thread
	// @param	Type	Description
	// $reset	Boolean	If this is true, $count is reset to zero.

	global $viewthread;
	static $count = 0;

	if($reset == true){
		$count = 0;
		return false;
	}

	$post = $viewthread->getPosts();
	if(isset($post[$count])){
		$post = $post[$count];
		$count++;
		return $post;
	}
	return false;
}

function loadPostReset(){
	loadPost(true);
}

function postCount(){
	// Should guys really be counting threads in linens?
	// This returns the number of threads

	global $viewthread;
	return count($viewthread->getPosts());
}

function threadId(){
	// Returns the ID of the current board

	global $viewthread;
	return $viewthread->getThreadId();
}




?>