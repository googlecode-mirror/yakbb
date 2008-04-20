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
|| File: /library/classes/yakbb.class.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

/*	TODO
		loadIC()
			- Code the entire function.
*/

if(!defined("SNAPONE")) exit;

class yakbb {
	// Define core variables
	public $settings = array(); // Settings
	public $groups = array(); // List of groups
	public $bans = array(); // Holds a list of bans
	public $ip; // The user's secured IP
	public $curPage; // The current page being viewed

	// Functions
	public function __construct(){
		$this->ip = secure($_SERVER["REMOTE_ADDR"]); // We'll look more into this later. >.<
	}

	public function loadIC(){
		// This function is here because of its possible to use on all pages.

		global $lang, $tp, $plugins, $db;
		$stats = array(); // Stats/info holder. Add it globally in a bit.
		$plugins->callhook("ic_start");
		$lang->learn("infocenter");

		// Basic stats
		$mems = $db->queryCache("
			SELECT
				count(id) AS mems
			FROM
				".DBPRE."users
		", -1, "stats/member_count");
		$stats["total_mems"] = $mems["mems"];

		$threads = $db->queryCache("
			SELECT
				count(id) AS threads
			FROM
				".DBPRE."threads
		", -1, "stats/threads_count");
		$stats["total_threads"] = $threads["threads"];

		$posts = $db->queryCache("
			SELECT
				count(id) AS posts
			FROM
				".DBPRE."posts
		", -1, "stats/posts_count");
		$stats["total_posts"] = $posts["posts"];

		// $tp->addGlobal($stats);

		$plugins->callhook("ic_end");
	}
}
	
	
$yak = new yakbb();
?>