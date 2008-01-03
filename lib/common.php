<?php

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
require_once LIBDIR."classes/permissions.class.php";
require_once LIBDIR."classes/post_parser.class.php";

// Load the database abstraction and actual database classes. Then, make the $db class from that and run a few cores.
require_once LIBDIR."classes/db/db.abstract.class.php";
if(defined("DBTYPE")){ // Check for install this way since it WILL use it
	require_once LIBDIR."classes/db/".DBTYPE.".class.php";
	$db = new $connection["type"]();
	$db->connect($connection);
	unset($connection); // Security reasons
}
?>