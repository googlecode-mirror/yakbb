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
|| File: /includes/post.php
|| File Version: v0.1.0a
|| $Id: postreply.php 78 2008-04-20 21:29:31Z cddude229 $
\*==================================================*/

/*	TODO
	all modes
		- Preview
		- Guests posting stuff
		- Post limits (time)
		- Disable ubbc/smilies options
		- Attachments
		- Notifications
			- Send
			- Allow to bookmark from post page, except modify
	newthread
		- Need to check thread creation permissions
	reply
		- Thread summary below reply
		- Quotes
		- Make sure the thread isn't a redirect URL
		- Need to check reply permissions
		- Need to override thread being locked if user can reply to locked threads
			- Add isLocked() function to add a notice to staff that the thread is locked
		- Nav tree needs to include link to thread you're replying to
	getItem()
		- Modify needs to load description if first post
*/

if(!defined("SNAPONE")) exit;

class post {
	private $mode = ""; // The current mode we're in
	private $id = 0; // ID of the current board (newthread), thread (reply), or post (modify)
	private $tdat = array(); // Holds the current thread data in reply mode
	private $pdat = array(); // Holds the current post data in modify mode
	private $quote = ""; // The quote data if a quote is set
	private $errors = array(); // Holds the errors

	public function __construct(){
		// Basics

		global $lang, $tp;
		$lang->learn("post");

		// We need to figure out if we're replying, modifying, or creating.
		switch($_GET["action"]){
			case "reply":
				$this->mode = "reply";
				$this->replySetup();
				break;
			case "newthread":
				$this->mode = "newthread";
				$this->newThreadSetup();
				break;
			case "modify":
				$this->mode = "modify";
				die("This mode currently isn't supported.");
				break;
			default:
				$this->mode = "error";
				$tp->error("invalid_post_type");
				break;
		}

		$tp->loadFile("post", "post.tpl");

		// Check for errors
		if(isset($_POST["submitit"])){
			$this->formSent();
		}
	}

	private function replySetup(){
		// Sets up the basic stuff for replies.

		global $tp, $db, $user, $lang;

		if(!isset($_REQUEST["tid"])){
			$tp->error("postreply_invalid_id");
		}

		$this->id = intval($_REQUEST["tid"]);

		if($this->id < 0){
			$tp->error("postreply_invalid_id");
		} else if($user["id"] == 0){
			$tp->error("postreply_guest");
		}

		// Make sure the thread exists
		$thread = $db->query("
			SELECT
				t.*
			FROM
				".DBPRE."threads t
			WHERE
				t.id='".$this->id."'
			LIMIT 1
		");
		if($db->numRows() == 0){
			$tp->error("postreply_thread_doesnt_exist");
		}
		$this->tdat = $db->fetch();
		$db->free();

		if($this->tdat["locked"] == 1){
			$g = getUserGroup();
			$tp->error("postreply_thread_locked");
		}
		$this->setupNavTree(array(
			"parentid" => $this->tdat["boardid"],
			"parenttype" => "b"
		));

		// Check reply permissions. It's ok to run another query because it's to the cache.
		$bdat = $db->cacheQuery("
			SELECT
				b.*
			FROM
				".DBPRE."boards b
			WHERE
				b.id='".$this->tdat["boardid"]."'
			LIMIT 1
		", -1, "board_data/".$this->tdat["boardid"]);
		$bdat = $bdat[0];
		$bperms = unserialize($bdat["permissions"]);
		if(!isset($bperms[$user["group"]]) || $bperms[$user["group"]] == false){
			$tp->error("postreply_no_permission");
		}

		// Add the thread nav tree stuff and set title
		$tp->addNav(threadLink($this->tdat));
		$tp->addNav($lang->item("nav_reply"));
		$tp->setTitle("reply");
	}

	private function newThreadSetup(){
	}

	private function setupNavTree($curboard){
		// This sets up the navtree and also checks parent data
		// Load parent boards and category and see if user can reply

		global $db, $user, $tp;

		$boards = array();
		while(true){
			if($curboard["parenttype"] == "c"){
				break;
			}
			$curboard = $db->cacheQuery("
				SELECT
					b.*
				FROM
					".DBPRE."boards b
				WHERE
					b.id='".$curboard["parentid"]."'
				LIMIT 1
			", -1, "board_data/".$curboard["parentid"]);
			$curboard = $curboard[0]; // We only want the first result... despite there being only one.
			$boards[] = $curboard;
		}

		if($curboard["parentid"] != 0){
			// Load the category if the ID isn't 0. (If it is zero, we don't really have a category. =P)
			$cat = $db->cacheQuery("
				SELECT
					c.*
				FROM
					".DBPRE."categories c
				WHERE
					c.id='".$curboard["parentid"]."'
				LIMIT 1
			", -1, "category_data/".$curboard["parentid"]);
			$cat = $cat[0]; // Only want the first result... despite there being only one.

			// Check view perms
			$catperms = unserialize($cat["permissions"]);
			if(!isset($catperms[$user["group"]]) || $catperms[$user["group"]]["view"] == false){
				$tp->error("viewboard_no_permissions");
			}

			// Add nav and cleanup
			$tp->addNav(catLink($cat));
			unset($cat);
		}
		unset($curboard);

		$boards = array_reverse($boards);
		foreach($boards as $k => $v){
			// Check view permissions and then add nav
			$bperms = unserialize($v["permissions"]);
			if(!isset($bperms[$user["group"]]) || $bperms[$user["group"]]["view"] == false){
				$tp->error("viewboard_no_permission");
			}
			$tp->addNav(boardLink($v));
		}
		unset($boards);
	}

	private function formSent(){
		// Validates data and actually does the submission.
		// Form was sent. Let's test out the post.

		libraryLoad("validation.lib");

		$title = secure(trim($_REQUEST["posttitle"]));
		if(isset($_REQUEST["postdesc"])){ // May not be set
			$description = secure(trim($_REQUEST["postdesc"]));
		} else {
			$description = "";
		}
		$message = secure(trim($_REQUEST["postmessage"]));

		$errors = array(); // Keep it local just for now

		// Title
		$tCheck = validTitle($title);
		if($tCheck !== true){
			$errors = array_merge($errors, $tCheck);
		}

		// Description
		$dCheck = validDescription($description);
		if($dCheck !== true){
			$errors = array_merge($errors, $dCheck);
		}

		// Message
		$mCheck = validMessage($message);
		if($mCheck !== true){
			$errors = array_merge($errors, $mCheck);
		}

		if(count($errors) == 0){
			// Passes checks. Good to go.
			global $db, $user;

			// Check if mode is modify. If it is, we do something completel different.
			if($this->mode == "modify"){
				// Code later
			} else {
				// If mode is new thread, let's create the thread first. =P
				if($this->mode == "newthread"){
					$db->insert("threads", array(
						"id" => 0,
						"timestamp" => time(),
						"title" => $title,
						"description" => $descripton,
						"creatorid" => $user["id"],
						"boardid" => $this->id,
						"replies" => 0,
						"views" => 0,
						"icon" => 0,
						"announcement" => 0,
						"sticky" => 0,
						"locked" => 0,
						"redirecturl" => ""
					));
					$tid = $db->insertId();
					$this->tdat = array(
						"id" => $tid,
						"title" => $title
					);
				} else {
					$tid = $this->id;
				}
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
				$sql = "UPDATE ".DBPRE."boards SET posts=posts+1";
				if($this->mode == "newthread"){
					$sql .= ", threads=threads+1 WHERE id='".$this->id."' LIMIT 1";
				} else { // Reply
					$sql .= " WHERE id='".$this->tdat["boardid"]."' LIMIT 1";
					$db->query("UPDATE ".DBPRE."threads SET replies=replies+1 WHERE id='".$tid."' LIMIT 1");
				}
				$db->query($sql);
				if($user["id"] != 0){
					$db->query("UPDATE ".DBPRE."users SET posts=posts+1 WHERE id='".$user["id"]."' LIMIT 1");
				}

				// Clear the cached data
				$db->clearCacheQuery("stats/posts_count");
				$db->clearCacheQuery("stats/threads_count");
			}

			// Redirect to the thread for all modes
			redirect("/".threadLink($this->tdat, true));
		} else {
			// We'll add the errors now
			$this->errors = $errors;
		}
	}













	// TEMPLATE FUNCTIONS
	public function getMode(){
		return $this->mode;
	}

	public function getItem($item=""){
		// Loads the title, message, or description

		global $lang, $yak;
		switch($item){
			case "desc":
			case "description":
				if(isset($_REQUEST["postdesc"])){
					return secure(substr($_REQUEST["postdesc"], 0, $yak->setting["thread_desc_max"]));
				}
				switch($this->mode){
					case "reply":
						return "";
					case "newthread":
						return "";
					case "modify":
						// Will do check later
						return "";
				}
				return "";
				break;
			case "title":
				if(isset($_REQUEST["posttitle"])){
					return secure(substr($_REQUEST["posttitle"], 0, $yak->settings["thread_subject_max"]));
				}
				if($this->mode == "reply"){
					return $lang->item("reply_string").$this->tdat["title"];
				} else if($this-mode == "modify"){
					// Will load modify data later
				}
				return "";
				break;
			case "message":
				if(isset($_REQUEST["postmessage"])){
					return secure($_REQUEST["postmessage"]);
				}
				switch($this->mode){
					case "reply":
						if(!empty($this->quote)){
							return $this->quote;
						}
						return "";
					case "modify":
						// Will do later
						break;
					case "newthread":
						return ""; // Nothing to add if we're creating a new thread.
				}
				break;
			default:
				return "";
		}
	}

	public function getErrors(){
		return $this->errors;
	}

	public function getId(){
		return $this->id;
	}
}

// Check for specific scenarios.
/* if(!isset($bperms[$user["group"]]) || $bperms[$user["group"]]["view"] == false){
	$tp->error("postreply_no_permission");
} else if($tdat["locked"] == 1 && !$perms->checkPerm("replylocked", array("bid" => $tdat["boardid"]))){
	// Thread is locked and the user can't reply to locked threads.
	$tp->error("postreply_thread_locked");
}

$tp->addNav($lang->item("nav_postreply"));
$tp->setTitle("reply");
$tp->loadFile("reply", "post.tpl"); /*, array(
	"mode" => "reply",
	"tid" => $tid,
	"form_action" => ($tp->seo?"./reply.html?":"?action=reply&amp;")."id=".$tid,
	"errors" => array(),
	"posttitle" => "Re: ".$tdat["title"],
	"postmessage" => ""
));

// Load quote stuff
if(isset($_REQUEST["quote"])){
	$quote = intval($_REQUEST["quote"]);
	if($quote > 0){
		$check = $db->query("
			SELECT
				p.*
			FROM
				".DBPRE."posts p
			WHERE
				p.id='".$quote."'
			LIMIT 1
		");
		if($db->numRows() == 1){
			$x = $db->fetch();
			$data = "[quote]".$x["message"]."[/quote]";
			// $tp->addVar("reply", "postmessage", $data);
		}
		$db->free();
	}
}

if(isset($_REQUEST["submitit"])){
	// Form was sent. Let's test out the post.
	libraryLoad("validation.lib");

	$title = secure(trim($_REQUEST["posttitle"]));
	$message = secure(trim($_REQUEST["postmessage"]));

	$errors = array();

	// Title
	$tCheck = validTitle($title);
	if($tCheck !== true){
		$errors = array_merge($errors, $tCheck);
	}

	// Message
	$mCheck = validMessage($message);
	if($mCheck !== true){
		$errors = array_merge($errors, $mCheck);
	}

	if(count($errors) == 0){
		// Passes checks. Good to go.
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
		$db->query("UPDATE ".DBPRE."boards SET posts=posts+1 WHERE id='".$tdat["boardid"]."' LIMIT 1");
		$db->query("UPDATE ".DBPRE."threads SET replies=replies+1 WHERE id='".$tid."' LIMIT 1");
		if($guest === false){
			$db->query("UPDATE ".DBPRE."users SET posts=posts+1 WHERE id='".$user["id"]."' LIMIT 1");
		}

		// Clear the cached data
		$db->clearCacheQuery("stats/posts_count");

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
} */

?>