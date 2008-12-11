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
|| File: /content/languages/en/post.lang.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

defined("YAKBB") or die("Security breach");

global $yakbb;

$items = array(
	"page_title_reply" => "Post Reply",
	"page_title_newthread" => "Create New Thread",
	"page_title_modify" => "Modify Post",

	// Errors
	"subject_too_short" => "The post's subject is too short. It needs to be ".$yakbb->config["subject_min_length"]." character".($yakbb->config["subject_min_length"] == 1?"":"s").".",
	"subject_too_long" => "The post's subject is too long. It may only be ".$yakbb->config["subject_max_length"]." characters.",
	"message_too_short" => "The post is too short. It needs to be ".$yakbb->config["message_min_length"]." character".($yakbb->config["message_min_length"] == 1?"":"s").".",
	"message_too_long" => "The post is too long. It may only be ".$yakbb->config["message_max_length"]." characters.",
);


?>