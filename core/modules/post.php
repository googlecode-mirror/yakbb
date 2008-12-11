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
|| File: /core/modules/post.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

/*	TODO
	- Need to check permissions
	- Need to make sure thread isn't locked
	- Need to make sure thread exists
	- Implement timeout
	- Need to up user's post count for reply and new thread
	- Need to make replies go to newest post
	- Quotes
	- Modify posts
*/

class post {
	private $actiontype  = ""; // Selected action
	private $errors      = array(); // Error holder

	private $board       = 0; // Board ID for new thread
	private $thread      = 0; // Thread ID for reply
	private $post        = 0; // Post ID for modify

	private $subject     = ""; // Subject submission
	private $message     = ""; // Message submission
	private $description = ""; // Description submission

	public function init(){
		global $yakbb;

		// Load language
		$yakbb->loadLanguageFile("post");

		// Get correct action type and check IDs
		$this->actiontype = $_REQUEST["action"];
		if($this->actiontype == "reply"){
			$this->thread = intval($_REQUEST["thread"]);
		} else if($this->actiontype == "newthread"){
			$this->board = intval($_REQUEST["board"]);
		} else if($this->actiontype == "modify"){
			$this->post = intval($_REQUEST["post"]);
		} else {
			$yakbb->error("2", "invalid_post_action");
		}

		if(isset($_REQUEST["submitpost"])){
			$this->validate();
		} else if(isset($_REQUEST["previewpost"])){
			// Disabled for now
			$yakbb->error("2", "");
		}

		// Template stuff
		$yakbb->smarty->assign("posterrors", array_map(array($yakbb, "getLang"), $this->errors));
		$yakbb->smarty->assign("sent_subject", $this->subject);
		$yakbb->smarty->assign("sent_message", $this->message);
		$yakbb->smarty->assign("threadid", $this->thread);
		$yakbb->smarty->assign("boardid", $this->board);
		$yakbb->smarty->assign("postid", $this->post);
		$yakbb->smarty->assign("actiontype", $this->actiontype);
		$yakbb->smarty->assign("page_title", $yakbb->getLang("page_title_".$this->actiontype));
		$yakbb->smarty->assign("posts", $this->posts);
		$yakbb->loadTemplate("post.tpl");
	}

	private function validate(){
		loadLibrary("validation.lib");
		global $yakbb;

		$this->subject = secure($_REQUEST["subject"]);
		$this->message = secure($_REQUEST["message"]);

		// Validate subject
		$res = valid_subject($this->subject);
		if($res !== true){
			$this->errors[] = $res;
		}

		// Validate message
		$res = valid_message($this->message);
		if($res !== true){
			$this->errors[] = $res;
		}

		// Check if thread exists when replying
		if($this->actiontype == "reply"){
			$yakbb->db->query("
				SELECT
					parentid
				FROM
					yakbb_threads
				WHERE
					id = '".$this->thread."'
				LIMIT
					1
			");
			if($yakbb->db->numRows() == 0){
				$yakbb->error(2, "reply_thread_doesnt_exist");
			}
			$dat = $yakbb->db->fetch();
			$this->board = $dat["parentid"];
		}

		// Passes validation, submit/modify/whatever it
		if(count($this->errors) == 0){
			$this->submitInfo();
		}
	}

	private function submitInfo(){
		global $yakbb;
		if($this->actiontype == "modify"){
			// Update
		} else {
			// Create thread if needed and then insert the reply
			$posttime = time();
			if($this->actiontype == "newthread"){
				// create thread
				$yakbb->db->insert("threads", array(
					"id" => 0,
					"name" => $this->subject,
					"description" => $this->desc,
					"creatorid" => $yakbb->user["id"],
					"parentid" => $this->board,
					"lastposttime" => $posttime,
					"lastpostuser" => $yakbb->user["id"],
					"created" => $posttime,
					"icon" => 0,
					"haspoll" => 0,
					"announcement" => 0,
					"sticky" => 0,
					"locked" => 0,
					"redirecturl" => ""
				));
				$this->thread = $yakbb->db->lastInsertId();
			}

			// insert reply to thread/first post
			$yakbb->db->insert("posts", array(
				"id" => 0,
				"threadid" => $this->thread,
				"userid" => $yakbb->user["id"],
				"timestamp" => $posttime,
				"message" => $this->message,
				"title" => $this->subject,
				"disablesmilies" => 0,
				"attachments" => 0
			));

			// Update thread info if not creating a thread
			if($this->actiontype == "reply"){
				$yakbb->db->query("
					UPDATE
						yakbb_threads
					SET
						replies = replies+1,
						lastposttime = '".$posttime."',
						lastpostuser = '".$yakbb->user["id"]."'
					WHERE
						id = '".$this->thread."'
					LIMIT
						1
				");
			}

			
			$yakbb->db->query("
				UPDATE
					yakbb_boards
				SET
					posts = posts+1,
					".($this->actiontype == "newthread"?"threads = threads+1,":"")."
					lastposttime = '".$posttime."',
					lastpostuserid = '".$yakbb->user["id"]."',
					lastpostthreadid = '".$this->thread."'
				WHERE
					id = '".$this->board."'
				LIMIT
					1
			");
			redirect("?thread=".$this->thread);
		}
	}
}

?>