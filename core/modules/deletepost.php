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
|| File: /core/modules/deletepost.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

/*	TODO
	- Need to make sure user can delete the post
		- Board view permissions
		- Category view permissions
		- Thread is locked?
		- Has permission to delete all posts or is post owner?
	- Need to change to use url_* functions instead of making URL on the spot
	- Convert to use deletion library to simplify things
*/

class deletepost {
	private $boardid  = 0;
	private $threadid = 0;
	private $postid   = 0;

	private $pdat     = array();

	public function init(){
		global $yakbb;

		// Get and validate board ID
		$this->postid = intval($_GET["post"]); // Force integer value
		if($this->postid == 0){
			$yakbb->error(2, "invalid_post_id");
		}

		// Need to check if post exists. Load thread data also.
		$yakbb->db->query("
			SELECT
				p.*,
				t.*
			FROM
				yakbb_posts p
			LEFT JOIN
				yakbb_threads t
				ON (t.id = p.threadid)
			WHERE
				p.id = '".$this->postid."'
			LIMIT
				1
		");
		if($yakbb->db->numRows() == 0){
			$yakbb->error(2, "delete_post_doesnt_exist");
		}
		$this->pdat = $yakbb->db->fetch();
		$this->threadid = $this->pdat["threadid"];
		$this->boardid = $this->pdat["parentid"];

		// Delete the post
		$threadgone = false;
		$yakbb->db->query("
			DELETE FROM
				yakbb_posts
			WHERE
				id = '".$this->postid."'
			LIMIT
				1
		");
		if($this->pdat["replies"] == 0){
			// No other posts in the thread. Delete the thread
			$threadgone = true;
			$yakbb->db->query("
				DELETE FROM
					yakbb_threads
				WHERE
					id = '".$this->threadid."'
				LIMIT
					1
			");
		} else {
			// Update thread stats
			$yakbb->db->query("
				UPDATE
					yakbb_threads
				SET
					replies = replies-1
				WHERE
					id = '".$this->threadid."'
				LIMIT
					1
			");
		}

		// Update board stats
		$yakbb->db->query("
			UPDATE
				yakbb_boards
			SET
				".($threadgone?"threads = threads-1,":"")."
				posts = posts-1
			WHERE
				id = '".$this->boardid."'
			LIMIT
				1
		");

		if($threadgone === true){
			// Redirect to board
			redirect("?board=".$this->boardid);
		} else {
			// Redirect to thread
			redirect("?thread=".$this->threadid);
		}
	}
}

?>