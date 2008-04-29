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
|| File: /library/deletion.lib.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

if(!defined("SNAPONE")) exit;

/*	ABOUT
	- This file contains functions used in the management involved with the deletion 
	of anything in the database.

	TODO
	- deletePosts()
		- Code it completely.
		- Test function.
	- deleteThreads()
		- Code it completely.
		- Test function.
	- deleteUsers()
		- Code it completely.
		- Test function.
	- deleteBoards()
		- Finish coding it.
		- Test function.
	- deleteCategories()
		- Test function.
*/

function deletePosts($postids){
	// Deletes an aray of posts
}

function deleteThreads($threadids){
	// Deletes an array of threads
}

function deleteBoards($boardids){
	// Deletes an array of boards

	global $db;
	$boardids = array_unique($boardids); // Unique-ified
	$boards = implode(", ", $boardids);

	// Make sure they subbmited some boards.
	if(count($boardids) == 0 || empty($boardids) || empty($boards)){
		return true;
	}

	// Delete the boards
	$db->query("DELETE FROM yakbb_boards WHERE id IN (".$boards.")");
}

function deleteCategories($catids){
	// Deletes an array of categories
	
	global $db;
	$catids = array_unique($catids); // Unique-ify them
	$cats = implode(", ", $catids);

	// Make sure they submitted some categories.
	if(count($catids) == 0 || empty($catids) || empty($cats)){
		return true;
	}

	// Delete the categories
	$db->query("DELETE FROM yakbb_categories WHERE id IN (".$cats.")");

	// Let's clean up the boards also.
	$x = $db->query("
		SELECT
			b.id
		FROM
			yakbb_boards b
		WHERE
			b.catid IN (".$cats.")
	");
	if($db->numRows($x) > 0){
		// Boards exist in the category. Let's compile a list and then delete them

		$boards = array();
		while($bdat = $db->fetchAssoc($x)){
			$boards[] = $bdat["id"];
		}

		// Let's get rid of them. We'll return the results of that attempt for completeness
		return deleteBoards($boards);
	}

	// Function was successful. Return true
	return true;
}

function deleteUsers($userids){
	// Deletes an array of users
}

?>