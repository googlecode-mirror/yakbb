<?php

/*	TODO
		loadIC()
			- Code the entire function.
*/

if(!defined("SNAPONE")) exit;

class yakbb {
	// Define core variables
	public $settings = array();
	public $ip; // The user's secured IP

	// Functions
	public function __construct(){
		$this->ip = secure($_SERVER["REMOTE_ADDR"]); // We'll look more into this later. >.<
	}

	public function loadIC(){
		// This function is here because of its possible use on all pages.

		global $lang, $tp, $plugins, $db;
		$stats = array(); // Stats/info holder. Add it globally in a bit.
		$plugins->callhook("ic_start");
		$lang->learn("infocenter");

		// Basic stats
		$mems = $db->queryCache("SELECT count(id) AS mems FROM ".DBPRE."users", "stats/member_count");
		$stats["total_mems"] = $mems["mems"];

		$threads = $db->queryCache("SELECT count(id) AS threads FROM ".DBPRE."threads", "stats/threads_count");
		$stats["total_threads"] = $threads["threads"];

		$posts = $db->queryCache("SELECT count(id) AS posts FROM ".DBPRE."posts", "stats/posts_count");
		$stats["total_posts"] = $posts["posts"];

		$tp->addGlobal($stats);

		$plugins->callhook("ic_end");
	}
}
	
	
$yak = new yakbb();
?>