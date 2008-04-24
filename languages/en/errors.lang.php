<?php

if(!defined("SNAPONE")) exit;

$items = array(
	// Error strings
	"nav_error" => "An Error Has Occured",

	// Core errors
	"invalid_include" => "The specified include is invalid.",
	"banned" => "You are banned from this forum currently.",

	// includes/join.php errors
	"join_logged_in" => "You must be logged out in order to register an account.",
	"join_registration_disabled" => "Registration for this forum is disabled. Please check back at a later time.",

	// includes/login.php errors
	"login_logged_in" => "You are already logged in.",

	// includes/post.php errors
	"invalid_post_type" => "You are trying to use a post type that is not supported.",
	"newthread_board_doesnt_exist" => "The board you are trying to create a new topic in does not exist.",
	"newthread_guest" => "Guests are not allowed to create threads in this board.",
	"newthread_invalid_id" => "The specified thread ID is invalid.",
	"newthread_cant_view" => "You are not allowed to view this board.",
	"postreply_guest" => "Currently guests are not allowed to post.",
	"postreply_invalid_id" => "The specified thread ID is invalid.",
	"postreply_thread_doesnt_exist" => "The thread you are trying to reply to does not exist.",
	"postreply_thread_locked" => "The thread you are trying to reply to is locked.",
	"postreply_no_permission" => "You do not have permission to post replies in this board.",

	// includes/viewboard.php errors
	"viewboard_invalid_id" => "The specified board ID is invalid.",
	"viewboard_doesnt_exist" => "The board you are trying to access does not exist.",
	"viewboard_no_permission" => "You do not have permission to view this board.",

	// includes/viewthread.php errors
	"viewthread_invalid_id" => "The specified thread ID is invalid.",
	"viewthread_doesnt_exist" => "The thread you are trying to access does not exist",
	"viewthread_no_permission" => "You do not have permission to view this thread.",
);

?>