<?php

if(!defined("SNAPONE")) exit;

/*	TODO
	validEmail()
		- Needs to be finished. Just returns true currently
*/

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

function validEmail($email){
	// Confirms if it is a valid e-mail or not

	return true;
}

function sha256($data){
	// sha256 hash on the data.

	// Add the random salt for security
	$data .= DBSALT;
	if(function_exists("hash")){ // Faster than the other options.
		return hash("sha256", $data);
	} else {
		return sha256::hash($data);
	}
}
if(!function_exists("hash") || !function_exists("hash_algos") || !in_array("sha256", hash_algos())){
	// Only load the sha256 class if the function doesn't exist to save time and processing power. It's a huge file (26.8kb for both files)
	require_once LIBDIR."hash_sha256.php";
}
?>