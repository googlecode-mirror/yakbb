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
|| File: /constants.inc.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

// For security reasons in all files from here on
define("YAKBB", true);

// Define YakBB status variables
define("YAKBB_INSTALL_VERSION",		"0.2.0a");
define("YAKBB_INSTALL_DB_VERSION",	1);

// Directory access list
define("YAKBB_CACHE",     "./cache/");
define("YAKBB_CONTENT",   "./content/");
define("YAKBB_CORE",      "./core/");
define("YAKBB_LANGUAGES", YAKBB_CONTENT."languages/");
define("YAKBB_MODULES",   YAKBB_CORE."modules/");
define("YAKBB_PLUGINS",   YAKBB_CONTENT."plugins/");
define("YAKBB_SMILIES",   YAKBB_CONTENT."smilies/");
define("YAKBB_TEMPLATES", YAKBB_CONTENT."templates/");
define("YAKBB_UPLOADS",   "./uploads/");
define("SMARTY_DIR",      YAKBB_CORE."smarty/");

?>