<?php

/*	TODO
	- Thread summary below reply
	- Thread description
	- Preview
	- Guests posting stuff
	- Check permissions to create a thread
	- Post limits (time)
	- Redirect URL options. (Mainly for moved threads)
	- Attachments support
	- Disable ubbc/smilies options
*/

if(!defined("SNAPONE")) exit;

$lang->learn("newthread");
$bid = intval($_REQUEST["board"]);
if($bid < 0){
	$tp->error("newthread_invalid_id");
} else if($guest){
	$tp->error("newthread_guest");
}

// Make sure the thread exists
$board = $db->query("SELECT * FROM ".DBPRE."boards WHERE id='".$bid."' LIMIT 1");
if($db->numRows() == 0){
	$tp->error("newthread_board_doesnt_exist");
} else if(!$perms->checkPerm("viewboard", array("bid" => $bid))){
	$tp->erorr("newthread_cant_view");
}
$bdat = $db->fetch();
$db->free();

// Load parent boards and category and see if user can reply
$curboard = $bdat;
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
	$tp->addNav($tp->catLink($cat["id"], $cat["name"]));
	unset($cat);
}
unset($curboard);

$boards = array_reverse($boards);
foreach($boards as $k => $v){
	if(!$perms->checkPerm("viewboard", array("bid" => $v["id"]))){
		$tp->error("viewboard_no_permission");
	}
	$tp->addNav($tp->boardLink($v["id"], $v["name"]));
}
unset($boards);

$tp->addNav($lang->item("nav_newthread"));
$tp->setTitle("newthread");
$tp->loadFile("newthread", "newthread.tpl", array(
	"bid" => $bid,
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
		$db->insert("threads", array(
			"id" => 0,
			"timestamp" => time(),
			"title" => $title,
			"description" => " ",
			"creatorid" => $user["id"],
			"boardid" => $bid,
			"icon" => 1,
			"announcement" => 0,
			"sticky" => 0,
			"locked" => 0,
			"redirecturl" => ""
		));

		$tid = $db->insertId();

		$db->insert("posts", array(
			"id" => 0,
			"threadid" => $tid,
			"userid" => $user["id"],
			"timestamp" => time(),
			"message" => $message,
			"title" => $title,
			"disableubbc" => 0,
			"disablesmilies" => 0,
			"attachments" => 0
		));

		// Update board, thread, and user counts
		$db->query("UPDATE ".DBPRE."boards SET posts=posts+1, threads=threads+1 WHERE id='".$bid."'");
		if($guest !== false){
			$db->query("UPDATE ".DBPRE."users SET posts=posts+1 WHERE id='".$user["id"]."'");
		}

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