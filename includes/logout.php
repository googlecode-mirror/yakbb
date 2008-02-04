<?php

if(!defined("SNAPONE")) exit;

class logout {
	public function __construct(){
		global $plugins;

		$plugins->callhook("logout_start");

		setcookie(DBPRE."user", "user", 0);
		setcookie(DBPRE."pass", "pass", 0);
		session_regenerate_id();

		$plugins->callhook("logout_end");

		redirect("?");
	}
}


?>