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
|| File: /core/classes/YakBB.class.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

defined("YAKBB") or die("Security breach.");

class YakBB {
	private $smarty = null;

	public function __construct(){
	}

	public function initiate(){
		$this->loadSmarty();
		$this->loadClasses();
	}

	private function loadSmarty(){
		// put full path to Smarty.class.php

		if($this->smarty !== null){
			return true;
		}

		global $smarty;
		require SMARTY_DIR."Smarty.class.php";
		$smarty = $this->smarty = new Smarty();

		$this->smarty->template_dir = YAKBB_TEMPLATES;
		$this->smarty->compile_dir = YAKBB_CACHE."compiled_templates";
		$this->smarty->cache_dir = YAKBB_CACHE."cached_templates";
		$this->smarty->config_dir = YAKBB_TEMPLATES."config";
		// $smarty->debugging = true; // < -- Doesn't want to work

		$this->smarty->assign('name', 'Chris');
		$this->smarty->display('index.tpl');
	}

	private function loadClasses(){
	}
}

?>