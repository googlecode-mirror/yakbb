<?php

class permissions {
	private $validTypes = array();

	public function __construct(){
		$this->validTypes = array(
			"viewboard" => array(&$this, "checkBoard"), // Deprecated and should be removed
			"viewcat" => array(&$this, "checkCategory"), // Deprecated and should be removed
			"replylocked" => array(&$this, "replyToLockedThreads")
		);
	}

	public function checkPerm($type, $vars=array()){
		// Called by the external script to check specific permissions
		// @param	Type		Description
		// $type	String		The type of permission to check.
		// $vars	Array		An array of parameters to pass on to the function
		// Return	Return		Returns the value of the func if the func was called. False otherwise.

		if(!empty($type) && isset($this->validTypes[$type]) && is_callable($this->validTypes[$type])){
			return call_user_func($this->validTypes[$type], $vars);
		}
		return false;
	}

	public function addType($type, $call){
		// Adds a function call/type of call. This can also be used to override the defaults
		$this->validTypes[$type] = $call;
	}


	// Built in calls
	private function checkCategory($var){
		// Checks to see if a category is viewable by the current user.
		// @param	Type		Description
		// $var		Array		An array containing data about the category.
		// Return	Return		Returns true if a user can view the category.

		die("Deprecated call to permissions::checkCategory");
	}
	private function checkBoard($var){
		// Checks to see if a board is viewable by the current user.
		// @param	Type		Description
		// $var		Array		An array containing data about the board.
		// Return	Return		Returns true if a user can view the board.

		die("Deprecated call to permissions::checkBoard");
	}
	private function replyToLockedThreads($var){
		// Checks to see if the user can reply to locked threads (global) or in specified board
		// @param	Type		Description
		// $var		Array		An array containing data about the board.
		// Return	Return		Returns true if a user can view the board.

		$board = $var["bid"];
		return true;
	}
}

$perms = new permissions();
?>