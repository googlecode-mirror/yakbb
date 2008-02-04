<?php

if(!defined("SNAPONE")) exit;

class clear_cache {
	public function __construct(){
		global $cache;

		$cache->clearCache();
		redirect("?");
	}
}
?>