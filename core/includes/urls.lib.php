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
|| File: /core/includes/urls.lib.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

defined("YAKBB") or die("Security breach.");

function url_thread($threadid, $threadnamem, $page=false){
	$str = "?thread=".$threadid;

	if($page !== false && intval($page) != 0){
		$str .= "&amp;page=".intval($page);
	}

	return $str;
}

function url_board($boardid, $boardname, $page=false){
	$str = "?board=".$boardid;

	if($page !== false && intval($page) != 0){
		$str .= "&amp;page=".intval($page);
	}

	return $str;
}

function url_user($userid, $username, $display){
	return "?user=".$username;
}

?>