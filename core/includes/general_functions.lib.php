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
|| File: /core/includes/general_functions.lib.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

defined("YAKBB") or die("Security breach.");

// General functions
function makeDate($time=0, $format="F j, Y, g:i a"){
	if($time == 0){
		$time = time();
	}
	return date($format, $time);
}

function secure($data){
	// Secures HTML entities for the specified input

	return htmlentities($data, ENT_QUOTES);
}

function sha256($dat){
	// sha256 hash on the data.

	// Add the random salt for security and then return the sha256 hash
	return hash("sha256", $dat.YAKBB_DBSALT);
}

function loadLibrary($n){
	// Loads a library section that is not loaded by default (deletion, validation, etc.)

	$f = $n.".php";
	if(file_exists(YAKBB_CORE.$f)){
		require_once YAKBB_CORE.$f;
	} else if(file_exists(YAKBB_INCLUDES.$f)){
		require_once YAKBB_INCLUDES.$f;
	} else if(file_exists(YAKBB_CLASSES.$f)){
		require_once YAKBB_CLASSES.$f;
	} else {
		die("Error locating library file: ".$n);
	}
}

function redirect($url){
        // Redirects to the specified page. It then exits the script.
        // @param       Type            Description
        // $url         String          The part of the URL after the slash.

        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), "/\\");
        header("Location: http://".$host.$uri.$url);
        exit;
}

function record_yakbb_error($dat){
	$ff = new FlatFile();
	$ff->updateFile("yakbb_error_record.txt", $dat." - (".time().")\n");
	unset($ff);
}





// Template functions
function validTemplate($tempid){
	// Makes sure the template folder exists

	return is_dir(YAKBB_TEMPLATES.$tempid);
}




// Cookie functions
function setYakCookie($name, $value, $time=0){
	setcookie(YAKBB_DBPRE.$name, $value, $time);
}

function getYakCookie($name){
	if(isset($_COOKIE[YAKBB_DBPRE.$name])){
		return $_COOKIE[YAKBB_DBPRE.$name];
	}
	return "";
}


?>