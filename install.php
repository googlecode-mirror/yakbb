<?php

/*	TODO
	
*/

define("SNAPONE", 1);
define("IN_INSTALL", 1);

// Check for lock file
if(file_exists("install-lock.txt")){
	die("The installer is currently locked. Please delete \"install-lock.txt\" to fix this.");
} else if(is_dir("/install/")){
	die("The installer folder can not be found.");
}

// Load some files we need
require_once "./global.php";

?>