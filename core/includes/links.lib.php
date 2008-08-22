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
|| $Id: links.lib.php 118 2008-07-20 19:12:46Z cddude229 $
\*==================================================*/

defined("YAKBB") or die("Security breach.");

function link_thread(int $threadid, $threadname){
	return '<a href="'.url_thread($threadid, $threadname).'">'.$threadname.'</a>';
}

function link_board(int $boardid, $boardname){
	return '<a href="'.url_board($boardid, $boardname).'">'.$boardname.'</a>';
}

function link_user($userid, $username, $display, int $groupid=0){
	return '<a href="'.url_user($userid, $username, $display).'" class="group'.$groupid.'">'.$display.'</a>';
}

?>