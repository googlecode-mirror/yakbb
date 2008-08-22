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
|| File: /core/includes/urls_seo.lib.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

defined("YAKBB") or die("Security breach.");

function seo_safe($str){
	// Replaces all non-alphanumerical characters
	return preg_replace("/[^A-Z0-9]/i", "_", $str);
}

function url_thread($threadid, $threadname){
	return "/thread-".$threadid."/".seo_safe($threadname)."/";
}

function url_board($boardid, $boardname){
	return "/board-".$boardid."/".seo_safe($boardname)."/";
}

function url_user($userid, $username, $display){
	return "/profile-".$username."/"; // Usernames are already SEO safe
	// return "/profile-".$userid."/";
}

?>