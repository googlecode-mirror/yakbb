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
|| File: /core/includes/links.lib.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

defined("YAKBB") or die("Security breach.");

function link_thread($threadid, $threadname, $page=false){
	return '<a href="'.url_thread($threadid, $threadname, $page).'">'.$threadname.'</a>';
}

function link_board($boardid, $boardname, $page=false){
	return '<a href="'.url_board($boardid, $boardname, $page).'">'.$boardname.'</a>';
}

function link_user($userid, $username, $display, $groupid=0){
	return '<a href="'.url_user($userid, $username, $display).'" class="group'.$groupid.'">'.$display.'</a>';
}

?>