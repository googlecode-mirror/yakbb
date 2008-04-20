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
|| File: /library/template_calls/error.calls.php
|| File Version: v0.1.0a
|| $Id: global.calls.php 74 2008-04-19 22:15:28Z cddude229 $
\*==================================================*/

if(!defined("SNAPONE")) exit;





/*====================================*\
|| GENERAL FUNCTIONS
||		errorCount()
||		loadError()
||		loadErrorReset()
\*====================================*/
function errorCount(){
	// Returns a count of the number of errors

	global $tp;
	return count($tp->getErrors());
}

function loadError($reset=false){
	// Loads the next board.
	// @param	Type	Description
	// $reset	Boolean	If this is true, $count is reset to zero.

	global $tp;
	static $count = 0;

	if($reset == true){
		$count = 0; // Valid?
	}

	$err = $tp->getErrors();
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