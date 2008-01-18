<?php

/*	TODO
	- Make it update config file if update is ran
*/

if(!defined("SNAPONE")) exit;

class upgrade_sql {
	private $curver = 1;
	public function upgrade($n){
		// $n = current version. Will fully update to final version
		while($n <= $this->curver && is_callable(array($this, "upgradeIt".$n))){
			call_user_func(array($this, "upgradeIt".$n));
			echo $n;
			$n++;
		}
		return $this->curver;
	}

	private function upgradeIt0(){} // Prevent possible errors
	private function upgradeIt1(){} // Prevent possible errors
}

?>