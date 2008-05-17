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
|| $Id$
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
		- Finish add poll stuff
			- Check permissions
			- View results needs to be added
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
	private $bdat = array(); // Holds the current board data in newthread mode
	private $tdat = array(); // Holds the current thread data in reply mode
	private $pdat = array(); // Holds the current post data in modify mode
	private $quote = ""; // The quote data if a quote is set
	private $errors = array(); // Holds the errors
	private $poll = false;

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
				yakbb_threads t
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
				yakbb_boards b
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

		// Quote stuff if the form wasn't sent because we won't add the quote if it wasn't loaded
		if(!isset($_REQUEST["submitit"]) && isset($_GET["quote"])){
			$quote = intval($_GET["quote"]);
			if($quote > 0){
				$check = $db->query("
					SELECT
						p.*
					FROM
						yakbb_posts p
					WHERE
						p.id='".$quote."'
					LIMIT 1
				");
				if($db->numRows() == 1){
					$x = $db->fetch();
					$this->quote = "[quote]".$x["message"]."[/quote]";
				}
				$db->free();
			}
		}
	}

	private function newThreadSetup(){
		// This sets up the rest of the code to handle newthread mode correctly
		// Also handles creating a poll, if permissions are set

		global $tp, $db, $user, $lang;

		// Establish the board ID
		$this->id = intval($_REQUEST["bid"]);
		if($this->id < 0){
			$tp->error("newthread_invalid_id");
		} else if($user["id"] == 0){
			$tp->error("newthread_guest");
		}

		// Make sure the thread exists
		$board = $db->query("
			SELECT
				b.*
			FROM
				yakbb_boards b
			WHERE
				b.id='".$this->id."'
			LIMIT 1
		");
		if($db->numRows() == 0){
			$tp->error("newthread_board_doesnt_exist");
		}
		$this->bdat = $db->fetch();
		$db->free(); 

		// Check if they can view the board.
		$bperms = unserialize($this->bdat["permissions"]);
		if(!isset($bperms[$user["group"]]) || $bperms[$user["group"]]["thread"] == false){
			$tp->error("newthread_cant_view");
		}

		// Set up the nav tree
		$this->setupNavTree($this->bdat);

		// Template stuff
		$tp->addNav(boardLink($this->bdat));
		$tp->addNav($lang->item("nav_newthread"));
		$tp->setTitle("newthread");
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
					yakbb_boards b
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
					yakbb_categories c
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

		// Poll stuff. Let's do our checks
		if(isset($_REQUEST["pollquestion"]) && strlen($_REQUEST["pollquestion"]) > 0 && $this->mode == "newthread"){
			$question = secure($_REQUEST["pollquestion"]);
			$choices = array();
			for($n=1;isset($_REQUEST["choice".$n]) && $n-1 < POLL_NUM_MAX;$n++){
				if(strlen($_REQUEST["choice".$n]) > 0){
					$choices[] = secure($_REQUEST["choice".$n]);
				}
			}
			$retract = !!($_REQUEST["retract"] == "yes");
			$choose = intval($_REQUEST["canchoose"]);
			$expires = $_REQUEST["expires"];

			// Fix up the expires date.
			if(!preg_match("/^\d+(.\d+)?$/", $expires) || $expires > 10000 || $expires < 0){
				// Incorrect, infinite basically, or zero.
				$expires = 0;
			} else {
				$expires = time() + ($expires*24*60*60); // Convert to days in the future
			}

			// Check choose stuff
			if($choose < 1){
				$choose = 1;
			} else if($choose > POLL_NUM_MAX || $choose > count($choices)){
				$choose = count($choices);
			}

			// Check choices validity
			if(count($choices) < 2){
				$errors[] = "poll_not_enough_choices";
			} else if(count($choices) > POLL_NUM_MAX){
				$errors[] = "poll_too_many_choices";
			}

			if(count($errors) == 0){
				$this->poll = true;
			}
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
						"description" => $description,
						"creatorid" => $user["id"],
						"boardid" => $this->id,
						"icon" => 1,
						"announcement" => 0,
						"sticky" => 0,
						"locked" => 0,
						"redirecturl" => "",
						"haspoll" => ($this->poll == true?1:0)
					));
					$tid = $db->insertId();
					$this->tdat = array(
						"id" => $tid,
						"title" => $title
					);

					if($this->poll == true){
						$ar = array(
							"id" => 0,
							"threadid" => $tid,
							"boardid" => $this->id,
							"question" => $question,
							"expires" => $expires,
							"canchoose" => $choose,
							"canretract" => ($retract?1:0),
							"viewresults" => 0,
							"closed" => 0
						);
						foreach($choices as $k => $v){
							$ar["answer".($k+1)] = $v;
						}
						$db->insert("polls", $ar);
					}
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
				$sql = "UPDATE yakbb_boards SET posts=posts+1";
				if($this->mode == "newthread"){
					$sql .= ", threads=threads+1 WHERE id='".$this->id."' LIMIT 1";
				} else { // Reply
					$sql .= " WHERE id='".$this->tdat["boardid"]."' LIMIT 1";
					$db->query("UPDATE yakbb_threads SET replies=replies+1 WHERE id='".$tid."' LIMIT 1");
				}
				$db->query($sql);
				if($user["id"] != 0){
					$db->query("UPDATE yakbb_users SET posts=posts+1 WHERE id='".$user["id"]."' LIMIT 1");
				}

				// Clear the cached data
				$db->clearCacheQuery("stats/posts_count");
				$db->clearCacheQuery("stats/threads_count");
			}

			// Redirect to the thread for all modes
			redirect("/".ampToNormal(threadLink($this->tdat, true)));
		} else {
			// We'll add the errors now
			$this->errors = array_merge($this->errors, $errors);
		}
	}













	// TEMPLATE FUNCTIONS
	public function getMode(){
		return $this->mode;
	}

	public function getItem($item="", $n=0){
		// Loads the title, message, or description

		global $lang, $yak;
		switch($item){
			case "canchoose": // Can choose multiple and amount
				if(isset($_REQUEST["canchoose"])){
					$can = intval($_REQUEST["canchoose"]);
					if($can > 0){
						return $can;
					}
				}
				return 1;
				break;
			case "choice": // Used to load choice 1-16
				if(isset($_REQUEST["choice".$n])){
					return secure(substr($_REQUEST["choice".$n], 0, $yak->settings["poll_choice_max_length"]));
					
				}
				return "";
				break;
			case "desc": // Thread description alternative
			case "description": // Thread description
				if(isset($_REQUEST["postdesc"])){
					return secure(substr($_REQUEST["postdesc"], 0, $yak->settings["thread_desc_max"]));
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
			case "expires": // Expiration date
				if(isset($_REQUEST["expires"])){
					$exp = intval($_REQUEST["expires"]);
					if($exp > 0){
						return $exp;
					}
				}
				return 0;
				break;
			case "message": // Actual message body
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
			case "question": // Poll question
				if(isset($_REQUEST["pollquestion"])){
					return secure(substr($_REQUEST["pollquestion"], 0, $yak->settings["poll_question_max_length"]));
				}
				return "";
				break;
			case "title": // Post/thread title
				if(isset($_REQUEST["posttitle"])){
					return secure(substr($_REQUEST["posttitle"], 0, $yak->settings["thread_subject_max"]));
				}
				if($this->mode == "reply"){
					return $lang->item("reply_string").$this->tdat["title"];
				} else if($this->mode == "modify"){
					// Will load modify data later
				}
				return "";
				break;
			case "retract": // Retraction of vote
				if(isset($_REQUEST["retract"]) && $_REQUEST["retract"] == "yes"){
					return true;
				}
				return false;
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

?>