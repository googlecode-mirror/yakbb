<?php

if(!defined("SNAPONE")) exit;

$items = array(
	// Errors
	"title_empty" => "Please enter a thread title.",
	"title_too_long" => "Your thread title is too long. It may only be ".$yak->settings["thread_subject_max"]." characters.",
	"message_empty" => "Please enter a message.",
	"message_too_long" => "Your message is too long. It may only be ".$yak->settings["thread_message_max"]." characters.",

	// Page title
	"nav_postreply" => "Replying to Thread",

	// Misc
	"submit_button" => "Post Reply!",
);

?>