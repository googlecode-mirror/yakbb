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
|| File: /core/classes/Thread.class.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/


defined("YAKBB") or die("Security breach.");

class Thread {
	private $threadid = 0;

	// Core loading functions
	public function __construct($dat){
		// $dat = data of the thread
	}

	public function moveToBoard($boardid){
		// $boardid = ID of new board. Need to validate board's existence
	}

	public function addReply($postdat){
		// $postdat = array of data
	}

	public function update($threaddat){
	}

	public function delete(){
		if(!function_exists("delete_threads")){
			loadLibrary("deletion.lib");
		}
		delete_threads($this->threadid);
	}
}

?>