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
|| File: /library/common.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

if(!defined("SNAPONE")) exit;

// Load the "common" functions library. There are other functions that are loaded when needed.
require_once LIBDIR."functions.lib.php";

// Load the forum class.
require_once LIBDIR."classes/yakbb.class.php";

// Load some of the needed classes
require_once LIBDIR."classes/plugin.class.php";
require_once LIBDIR."classes/flat_file.class.php";
require_once LIBDIR."classes/cache.class.php";
require_once LIBDIR."classes/language.class.php";
require_once LIBDIR."classes/template.class.php";
require_once LIBDIR."classes/post_parser.class.php";

// Load the database abstraction and actual database classes. Then, make the $db class from that and run a few cores.
require_once LIBDIR."classes/db/db.abstract.class.php";
require_once LIBDIR."classes/db/".DBTYPE.".class.php";
$db = new $connection["type"]();
$db->connect($connection);
unset($connection); // Security reasons

// Load settings.
$cache->loadSettings(); // Load our settings from the database or cache
$cache->loadGroups(); // Load our groups from the database or cache

// Do SEO stuff briefly.
if($yak->settings["seo_engine"] === true){
	$tp->setSEO();
}
?>