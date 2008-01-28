<?php

/*	TODO
	- Thread summary below reply
	- Make TPL file use language variables
	- lang->BLANK_too_long needs to be replaced with the amount of characters used.
	- Fill in the default title with "Re: {THREADTITLE}"
	- Show errors
	- Preview
	- Guests posting stuff
	- Update board and thread counts
*/

if(!defined("SNAPONE")) exit;

$lang->learn("postreply");
$tid = intval($_REQUEST["id"]);
if($tid < 0){
	$tp->error("postreply_invalid_id");
} else if($guest){
	$tp->error("postreply_guest");
}

// Make sure the thread exists
$thread = $db->query("SELECT * FROM ".DBPRE."threads WHERE id='".$tid."' LIMIT 1");
if($db->numRows() == 0){
	$tp->error("postreply_thread_doesnt_exist");
}
$tdat = $db->fetch();
$db->free();

// Load parent boards and category and see if user can reply
$curboard = array(
	"parentid" => $tdat["boardid"],
	"parenttype" => "b"
);
$boards = array();
while(true){
	if($curboard["parenttype"] == "c"){
		break;
	}
	$curboard = $db->cacheQuery("SELECT * FROM ".DBPRE."boards WHERE id='".$curboard["parentid"]."' LIMIT 1", "board_data/".$curboard["parentid"]);
	$curboard = $curboard[0]; // We only want the first result... despite there being only one.
	$boards[] = $curboard;
}

if($curboard["parentid"] != 0){
	// Load the category if the ID isn't 0. (If it is zero, we don't really have a category. =P)
	$cat = $db->cacheQuery("SELECT * FROM ".DBPRE."categories WHERE id='".$curboard["parentid"]."' LIMIT 1", "category_data/".$curboard["parentid"]);
	$cat = $cat[0]; // Only want the first result... despite there being only one.
	if(!$perms->checkPerm("viewcat", array("cid" => $cat["id"]))){
		$tp->error("viewboard_no_permissions");
	}
	$tp->addNav($cat["name"]);
	unset($cat);
}
unset($curboard);

$boards = array_reverse($boards);
foreach($boards as $k => $v){
	if(!$perms->checkPerm("viewboard", array("bid" => $v["id"]))){
		$tp->error("viewboard_no_permission");
	}
	$tp->addNav($v["name"]);
}
unset($boards);

// Check if thread is locked or user can override
if($tdat["locked"] == 1 && !$perms->checkPerm("replylocked", array("bid" => $tdat["boardid"]))){
	$tp->error("postreply_thread_locked");
}

$tp->addNav($lang->item("nav_postreply"));
$tp->setTitle("reply");
$tp->loadFile("reply", "postreply.tpl", array(
	"tid" => $tid,
	"errors" => array(),
	"posttitle" => "",
	"postmessage" => ""
));

if(isset($_REQUEST["submitit"])){
	// Form was sent. Let's test out the post.
	$title = secure($_REQUEST["posttitle"]);
	$message = secure($_REQUEST["postmessage"]);

	$errors = array();

	// Title errors.
	if(strlen($title) < 1 || empty($title)){
		$errors[] = "title_empty";
	} else if(strlen($title) > $yak->settings["thread_subject_max"]){
		$errors[] = "title_too_long";
	}

	// Message errors
	if(strlen($message) < 1 || empty($message)){
		$errors[] = "message_empty";
	} else if(strlen($message) > $yak->settings["thread_message_max"]){
		$errors[] = "message_too_long";
	}

	if(count($errors) == 0){
		// Passes checks. Good to go.
		$db->insert("posts", array(
			"id" => 0,
			"threadid" => $tid,
			"userid" => $user["id"],
			"timestamp" => time(),
			"message" => $message,
			"disableubbc" => 0,
			"disablesmilies" => 0,
			"attachments" => 0
		));
		if($tp->seo){
			redirect("/thread-".$tid.".html");
		} else {
			redirect("?thread=".$tid);
		}
	} else {
		// We'll add the errors now
		$lang->learn("errors");
		$tp->addVar("reply", array(
			"errors" => array_map(array($lang, "item"), $errors),
			"posttitle" => $title,
			"postmessage" => $message
		));
	}
}

?>