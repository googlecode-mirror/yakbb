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
|| File: /core/classes/Board.class.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/


defined("YAKBB") or die("Security breach.");

class Board {
	// Core loading functions
	public function __construct($dat){
		// $dat = data of the board
	}

	public function moveToCat($catid, $position){
		// $catid = ID of new cat. Need to validate cat's existence.
		// $position = New position in board list under parent cats
	}

	public function createThread($threaddat){
		// $threaddat = array of data
	}

	public function update($boarddat){
	}

	public function delete(){
	}
}

?>