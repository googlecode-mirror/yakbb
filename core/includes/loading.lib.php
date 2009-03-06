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
|| File: /core/includes/loading.lib.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

defined("YAKBB") or die("Security breach.");


function loadBoardData($boardid){
	global $yakbb;

	$board = $yakbb->db->queryCache("
		SELECT
			id, name, description,				# Basic data
			parenttype, parentid, parentorder,	# Parent data
			permissions, password,				# Permissions data
			redirecturl, redirects,				# Redirect data
			sublist, modslist, hidden			# Settings data
		FROM
			yakbb_boards
		WHERE
			id = '".$boardid."'
		LIMIT
			1
	", "board_data/".$boardid);

	return $board[0];
}

function loadCategoryData($catid){
	global $yakbb;

	$cat = $yakbb->db->queryCache("
		SELECT
			id, name, description,		# Cat data
			hideshow, showmain, order,	# Settings data
			permissions					# Permissions data
		FROM
			yakbb_categories
		WHERE
			id = '".$catid."'
		LIMIT
			1
	", "cat_data/".$catid);

	return $cat[0];
}



?>