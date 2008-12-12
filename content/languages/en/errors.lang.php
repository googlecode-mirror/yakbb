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
|| File: /content/languages/en/errors.lang.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

defined("YAKBB") or die("Security breach.");

global $yakbb;

$items = array(
	// core/includes/YakBB.class.php
		"error_title" => "Error",

	// core/includes/validation.lib.php
		"username_invalid_characters" => "Your username contains invalid characters. It may only contain letters, numbers, underscores, and dashes.",
		"username_too_short" => "Your username is too short. It needs to be ".$yakbb->config["username_min_length"]." character".($yakbb->config["username_min_length"] == 1?"":"s").".",
		"username_too_long" => "Your username is too long. It may only be ".$yakbb->config["username_max_length"]." characters.",
		"displayname_too_short" => "Your display name is too short. It needs to be ".$yakbb->config["displayname_min_length"]." character".($yakbb->config["displayname_min_length"] == 1?"":"s").".",
		"displayname_too_long" => "Your display name is too long. It may only be ".$yakbb->config["displayname_max_length"]." characters.",

	// register.php
		"passwords_dont_match" => "The two passwords you entered do not match.",
		"emails_dont_match" => "The two e-mails you entered do not match.",
		"tos_not_checked" => "You must agree to the Terms of Service and Privacy Policy to register.",

	// login.php
		"user_doesnt_exist" => "The specified user does not exist.",
		"password_incorrect" => "The password you entered is incorrect.",

	// viewboard.php
		"invalid_board_id" => "The provided board ID is invalid.",
		"board_doesnt_exist" => "The board you are trying to view does not exist.",
		"perms_cant_view_board" => "You do not have the correct permissions to view this board.",
		"viewboard_page_doesnt_exist" => "You are trying to view a page which doesn't exist.",

	// viewthread.php
		"invalid_thread_id" => "The provided thread ID is invalid.",
		"thread_doesnt_exist" => "The thread you are trying to view does not exist. It may have been deleted or moved elsewhere.",

	// post.php
		"invalid_post_action" => "The matching action is invalid.",
		"reply_thread_doesnt_exist" => "The thread you are trying to reply to does not exist.",
);


?>