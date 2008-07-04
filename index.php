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
|| File: /index.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/


ob_start(); // Catch white output and smarty output

// Begin loading necessary data
require "./constants.inc.php";
require YAKBB_CORE."classes/YakBB.class.php";

$yakbb = new YakBB();
$yakbb->initiate();

// Output the final content
ob_end_flush();

?>