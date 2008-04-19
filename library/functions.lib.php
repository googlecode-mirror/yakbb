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
|| File: /library/functions.lib.php
|| File Version: v0.1.0a
|| $Id: global.php 64 2008-04-14 15:32:04Z cddude229 $
\*==================================================*/

if(!defined("SNAPONE")) exit;

function libraryLoad($n){
	// Loads a library section that is not loaded by default (deletion, validation, etc.)

	require_once LIBDIR.$n.".php";
}

function redirect($url){
	// Redirects to the specified page. It then exits the script.
	// @param	Type		Description
	// $url		String		The part of the URL after the slash.

	$host = $_SERVER['HTTP_HOST'];
	$uri = rtrim(dirname($_SERVER['PHP_SELF']), "/\\");
	header("Location: http://".$host.$uri.$url);
	exit;
}

function secure($data){
	// Secures HTML entities for the specified input

	return htmlentities($data, ENT_QUOTES);
}

function makeDate($time){
	return date("D M d, Y g:i a", $time);
}

function sha256($data){
	// sha256 hash on the data.

	// Add the random salt for security and then return the sha256 hash
	return hash("sha256", $data.DBSALT);
}
?>