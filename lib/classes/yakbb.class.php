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

		global $lang, $tp, $plugins;
		$plugins->callhook("ic_start");
		$lang->learn("infocenter");

		$plugins->callhook("ic_end");
	}
}
	
	
$yak = new yakbb();
?>