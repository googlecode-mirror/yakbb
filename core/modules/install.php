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
|| File: /core/modules/install.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

class install {
	public function init(){
		global $yakbb;

		$yakbb->loadLanguageFile("installer");
		if(file_exists("./install.lock")){
			die($yakbb->getLang("installer_locked"));
		}

		$part = intval($_GET["part"]);
		if($part == 0) $part = 1;
		$yakbb->smarty->assign("part", $part);

		switch($part){
			case 1:  $this->part1(); break;
		}
		$yakbb->loadTemplate("index.tpl");
	}

	private function part1(){
		global $yakbb;

		
	}
}

?>