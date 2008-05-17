<?php

if(!defined("SNAPONE")) exit;

$items = array(
	// Errors
	"title_empty" => "Please enter a thread title.",
	"title_too_long" => "Your thread title is too long. It may only be ".$yak->settings["thread_subject_max"]." characters.",
	"message_empty" => "Please enter a message.",
	"message_too_long" => "Your message is too long. It may only be ".$yak->settings["thread_message_max"]." characters.",
	"desc_too_long" => "Your thread description is too long. It may only be ".$yak->settings["thread_desc_max"]." characters.",
	"poll_not_enough_choices" => "You must provide at least 2 choices for your poll.",
	"poll_too_many_choices" => "You may not provide more than ".POLL_NUM_MAX." choices for your poll.",

	// Page title and text sections
	"nav_reply" => "Replying to Thread",
	"nav_newthread" => "Creating a Thread",
	"text_title" => "Title: ",
	"text_desc" => "Description: ",
	"text_message" => "Message: ",
	"post_title_reply" => "Reply to Thread",
	"post_title_newthread" => "Create a Thread",

	// Misc
	"submit_button_reply" => "Post Reply!",
	"submit_button_newthread" => "Create Thread",
);

?>