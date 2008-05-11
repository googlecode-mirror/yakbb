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
|| File: /library/template_calls/memberslist.calls.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

if(!defined("SNAPONE")) exit;





/*====================================*\
|| MEMBER FUNCTIONS
||		loadMember()
||		loadPostReset()
||		postCount()
\*====================================*/
function loadMember($reset=false){
	// Loads the next member
	// @param	Type	Description
	// $reset	Boolean	If this is true, $count is reset to zero.

	global $memberslist;
	static $count = 0;

	if($reset == true){
		$count = 0;
		return false;
	}

	$mem = $memberslist->getMembers();
	if(isset($mem[$count])){
		$mem = $mem[$count];
		$count++;
		return $mem;
	}
	return false;
}

function loadMemberReset(){
	loadMember(true);
}

function memberCount(){
	// This returns the number of threads

	global $memberslist;
	return count($memberslist->getMembers());
}




?>