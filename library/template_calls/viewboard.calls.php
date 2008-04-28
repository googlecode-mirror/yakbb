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
|| File: /library/template_calls/viewboard.calls.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

if(!defined("SNAPONE")) exit;





/*====================================*\
|| BOARD FUNCTIONS
||		boardId()
||		loadThread()
||		loadThreadReset()
||		threadCount()
\*====================================*/
function boardId(){
	// Returns the ID of the current board

	global $viewboard;
	return $viewboard->getBoardId();
}

function loadThread($type="normal", $reset=false){
	// Loads the next thread
	// @param	Type	Description
	// $type	String	The type of thread to load. Normal, sticky, and announcement
	// $reset	Boolean	If this is true, $count is reset to zero.

	global $viewboard;
	static $count = 0;

	if($reset == true){
		$count = 0;
		return false;
	}

	$thread = $viewboard->getThreads();
	if(isset($thread[$count])){
		$thread = $thread[$count];
		$count++;
		return $thread;
	}
	return false;
}

function loadThreadReset(){
	loadThread(null, true);
}

function threadCount(){
	// Should guys really be counting threads in linens?
	// This returns the number of threads

	global $viewboard;
	return count($viewboard->getThreads());
}



?>