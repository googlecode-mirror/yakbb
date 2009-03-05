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
|| File: /core/classes/Category.class.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/


defined("YAKBB") or die("Security breach.");

class Category {
	// Core loading functions
	public function __construct($dat){
		// $dat = data of the category
	}

	public function delete(){
		// Delete category
	}

	public function createBoard($boarddat){
		// $boarddat = array of data
	}

	public function update($update){
		// $update = array of data to update in the category
	}
}

?>