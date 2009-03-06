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
|| File: /core/includes/permissions.lib.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

/*	TODO
		getBoardPermissions()
			- Needs to be fully tested
		getCategoryPermissions()
			- Needs to be fully tested
*/

defined("YAKBB") or die("Security breach.");

// Loading permission functions
function getBoardPermissions($boardid){
	// Get specific permissions for a specific board. All is done from the cache.

	global $yakbb;

	$boardid = intval($boardid);
	$perms = loadBoardData($boardid);
	$perms = unserialize($perms["permissions"]);

	// Get permissions
	if(empty($perms) || !isset($perms[$yakbb->user["group"]])){
		return array(
			"view" => false,
			"create_thread" => false,
			"create_poll" => false,
			"post_reply" => false,
			"add_attachment" => false,
			"download_attachment" => false
		);
	} else {
		return $perms[$yakbb->user["group"]];
	}
}

function getCategoryPermissions($catid){
	// Get specific permissions for a specific category. All is done from the cache.

	global $yakbb;

	$catid = intval($catid);
	$perms = loadCategoryData($catid);
	$perms = unserialize($perms["permissions"]);

	// Get permissions
	if(empty($perms) || !isset($perms[$yakbb->user["group"]])){
		return array(
			"view" => false
		);
	} else {
		return $perms[$yakbb->user["group"]];
	}

	return array();
}

function getPermissions($groupid=false){
	// Gets the permission array for a specific group

	global $yakbb;
	if($groupid === false){
		$groupid = $yakbb->user["group"];
	}

	return $yakbb->groups[$groupid];
}

function getPermission($perm, $groupid=false){
	// Gets a specific permission for a group

	$perms = getPermissions($groupid);
	if(isset($perms[$perm])){
		return $perms[$perm] == 1;
	}
	return false;
}




// Checking permission functions
function canModifyPost($postid, $userid=0){
	// if $userid is 0, load current user id.
	return true;
}

function canDeletePost($postid, $userid=0){
	// if $userid is 0, load current user id.
	return true;
}



function canViewThread($threadid, $userid=0){
	// if $userid is 0, load current user id.
	return true;
}

function canReplyToThread($threadid, $userid=0){
	// if $userid is 0, load current user id.
	return true;
}

function canLockThread($threadid, $userid=0){
	// if $userid is 0, load current user id.
	return true;
}

function canMoveThread($threadid, $userid=0){
	// if $userid is 0, load current user id.
	return true;
}

function canDeleteThread($threadid, $userid=0){
	// if $userid is 0, load current user id.
	return true;
}

function canBumpThread($threadid, $userid=0){
	// if $userid is 0, load current user id.
	return true;
}

function canStickyThread($threadid, $userid=0){
	// if $userid is 0, load current user id.
	return true;
}

function canAnnounceThread($threadid, $userid=0){
	// if $userid is 0, load current user id.
	return true;
}

function canMergeThreads($threadid, $userid=0){
	// if $userid is 0, load current user id.
	// This only checks the thread you want to merge.
	// All merges need to be made in the same board
	return true;
}

function canSplitThread($threadid, $userid=0){
	// if $userid is 0, load current user id.
	return true;
}



function canViewBoard($boardid, $userid=0){
	// if $userid is 0, load current user id.
	return true;
}

function canCreateNewThread($boardid, $userid=0){
	// if $userid is 0, load current user id.
	return true;
}

function canCreateNewPoll($boardid, $userid=0){
	// if $userid is 0, load current user id.
	return true;
}



function canViewCategory($catid, $userid=0){
	// if $userid is 0, load current user id.
	return true;
}

function canHideCategory($catid, $userid=0){
	// if $userid is 0, load current user id.
	return true;
}

?>