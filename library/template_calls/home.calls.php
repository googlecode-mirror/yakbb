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
|| File: /library/template_calls/home.calls.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

if(!defined("SNAPONE")) exit;





/*====================================*\
|| BOARD AND CATEGORY FUNCTIONS
||		hasBoards()
||		hasCats()
||		loadBoard()
||		loadBoardReset()
||		loadCat()
||		loadCatReset()
\*====================================*/
function hasBoards(){
	// Returns a boolean of whether or not there are boards present
	global $home;

	return !!(count($home->boards()) > 0);
}

function hasCats(){
	// Returns a boolean of whether or not there are categories present
	global $home;

	return !!(count($home->cats()) > 0);
}

function loadBoard($reset=false){
	// Loads the next board.
	// @param	Type	Description
	// $reset	Boolean	If this is true, $count is reset to zero.

	global $home;
	static $count = 0;

	if($reset == true){
		$count = 0;
	}

	$board = $home->boards();
	if(isset($board[$count])){
		$board = $board[$count];
		$count++;
		return $board;
	}
	return false;
}

function loadBoardReset(){
	loadBoard(true);
}

function loadCat($reset=false){
	// Loads the next board.
	// @param	Type	Description
	// $reset	Boolean	If this is true, $count is reset to zero.

	global $home;
	static $count = 0;

	if($reset == true){
		$count = 0;
	}

	$cat = $home->cats();
	if(isset($cat[$count])){
		$cat = $cat[$count];
		$count++;
		return $cat;
	}
	return false;
}

function loadCatReset(){
	loadCat(true);
}





/*====================================*\
|| MISC FUNCTIONS
||		singleCatView()
\*====================================*/
function singleCatView(){
	// Returns a boolean of whether or not we're viewing a single category

	global $home;
	return !!$home->getSingle();
}


?>