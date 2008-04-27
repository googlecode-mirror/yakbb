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
|| File: /library/template_calls/upgrade.calls.php
|| File Version: v0.1.0a
|| $Id: global.calls.php 74 2008-04-19 22:15:28Z cddude229 $
\*==================================================*/

if(!defined("SNAPONE")) exit;





/*====================================*\
|| MISC FUNCTIONS
||		dbUpgrade()
||		coreUpgrade()
\*====================================*/
function dbUpgrade(){
	global $upgrade;
	return $upgrade->dbUpgrade();
}

function coreUpgrade(){
	global $upgrade;
	return $upgrade->coreUpgrade();
}



?>