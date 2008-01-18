<?php

if(!defined("SNAPONE")) exit;

$d = array(); // Drops
$c = array(); // Creates
$i = array(); // Inserts
$d[] = "DROP TABLE IF EXISTS `".DBPRE."attachments`";
$c[] = "CREATE TABLE `".DBPRE."attachments` (
`id` int(10) unsigned NOT NULL auto_increment,
`postid` int(11) NOT NULL,
`originalname` text collate latin1_general_ci NOT NULL,
`newname` text collate latin1_general_ci NOT NULL,
`preview` text collate latin1_general_ci NOT NULL,
`filetype` text collate latin1_general_ci NOT NULL,
`downloads` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";





$d[] = "DROP TABLE IF EXISTS `".DBPRE."boards`";
$c[] = "CREATE TABLE `".DBPRE."boards` (
`id` int(10) unsigned NOT NULL auto_increment,
`parentid` int(11) NOT NULL,
`parenttype` enum('b','c') collate latin1_general_ci NOT NULL,
`name` text collate latin1_general_ci NOT NULL,
`description` text collate latin1_general_ci NOT NULL,
`threads` int(11) NOT NULL,
`posts` int(11) NOT NULL,
`order` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";


$i[] = "INSERT INTO `".DBPRE."boards` VALUES (1, 1, 'c', 'Board 1', 'o.o', 0, 0, 1)";
$i[] = "INSERT INTO `".DBPRE."boards` VALUES (2, 2, 'c', 'Board 2', 'Should be under category 2', 0, 0, 1)";
$i[] = "INSERT INTO `".DBPRE."boards` VALUES (3, 0, 'c', 'Board 3', 'This should appear under no category.', 0, 0, 0)";



$d[] = "DROP TABLE IF EXISTS `".DBPRE."categories`";
$c[] = "CREATE TABLE `".DBPRE."categories` (
`id` int(11) unsigned NOT NULL auto_increment,
`name` text collate latin1_general_ci NOT NULL,
`hideshow` int(1) NOT NULL,
`showmain` int(1) NOT NULL,
`order` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";


$i[] = "INSERT INTO `".DBPRE."categories` VALUES (1, 'Category 1', 1, 1, 1)";
$i[] = "INSERT INTO `".DBPRE."categories` VALUES (2, 'Category 2', 1, 1, 2)";
$i[] = "INSERT INTO `".DBPRE."categories` VALUES (3, 'Category 3', 1, 1, 3)";



$d[] = "DROP TABLE IF EXISTS `".DBPRE."config`";
$c[] = "CREATE TABLE `".DBPRE."config` (
`id` int(10) unsigned NOT NULL auto_increment,
`name` text collate latin1_general_ci NOT NULL,
`value` text collate latin1_general_ci NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";


$i[] = "INSERT INTO `".DBPRE."config` VALUES (1, 'default_template', 'default')";
$i[] = "INSERT INTO `".DBPRE."config` VALUES (2, 'default_language', 'en')";
$i[] = "INSERT INTO `".DBPRE."config` VALUES (3, 'strip_tab_spacing', 'false')";
$i[] = "INSERT INTO `".DBPRE."config` VALUES (4, 'defaulttimezone', '-6')";
$i[] = "INSERT INTO `".DBPRE."config` VALUES (5, 'dst', 'true')";
$i[] = "INSERT INTO `".DBPRE."config` VALUES (6, 'board_title', 'Message Board')";
$i[] = "INSERT INTO `".DBPRE."config` VALUES (7, 'registration_enabled', 'true')";
$i[] = "INSERT INTO `".DBPRE."config` VALUES (8, 'session_login', 'true')";
$i[] = "INSERT INTO `".DBPRE."config` VALUES (10, 'username_max_length', '30')";
$i[] = "INSERT INTO `".DBPRE."config` VALUES (11, 'displayname_max_length', '30')";
$i[] = "INSERT INTO `".DBPRE."config` VALUES (12, 'activation_required', 'false')";
$i[] = "INSERT INTO `".DBPRE."config` VALUES (13, 'username_min_length', '2')";
$i[] = "INSERT INTO `".DBPRE."config` VALUES (14, 'displayname_min_length', '1')";
$i[] = "INSERT INTO `".DBPRE."config` VALUES (15, 'unique_email', 'true')";
$i[] = "INSERT INTO `".DBPRE."config` VALUES (16, 'switch_users', 'true')";



$d[] = "DROP TABLE IF EXISTS `".DBPRE."polls`";
$c[] = "CREATE TABLE `".DBPRE."polls` (
`id` int(10) unsigned NOT NULL auto_increment,
`threadid` int(11) NOT NULL,
`boardid` int(11) NOT NULL,
`question` text collate latin1_general_ci NOT NULL,
`closed` tinyint(1) NOT NULL,
`answer1` text collate latin1_general_ci NOT NULL,
`answer2` text collate latin1_general_ci NOT NULL,
`answer3` text collate latin1_general_ci NOT NULL,
`answer4` text collate latin1_general_ci NOT NULL,
`answer5` text collate latin1_general_ci NOT NULL,
`answer6` text collate latin1_general_ci NOT NULL,
`answer7` text collate latin1_general_ci NOT NULL,
`answer8` text collate latin1_general_ci NOT NULL,
`answer9` text collate latin1_general_ci NOT NULL,
`answer10` text collate latin1_general_ci NOT NULL,
`answer11` text collate latin1_general_ci NOT NULL,
`answer12` text collate latin1_general_ci NOT NULL,
`answer13` text collate latin1_general_ci NOT NULL,
`answer14` text collate latin1_general_ci NOT NULL,
`answer15` text collate latin1_general_ci NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";





$d[] = "DROP TABLE IF EXISTS `".DBPRE."poll_votes`";
$c[] = "CREATE TABLE `".DBPRE."poll_votes` (
`id` int(10) unsigned NOT NULL auto_increment,
`pollid` int(11) NOT NULL,
`userid` int(11) NOT NULL,
`timestamp` int(11) NOT NULL,
`votefor` int(2) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";





$d[] = "DROP TABLE IF EXISTS `".DBPRE."posts`";
$c[] = "CREATE TABLE `".DBPRE."posts` (
`id` int(10) unsigned NOT NULL auto_increment,
`threadid` int(11) NOT NULL,
`userid` int(11) NOT NULL,
`timestamp` int(11) NOT NULL,
`message` text collate latin1_general_ci NOT NULL,
`disableubbc` tinyint(1) NOT NULL,
`disablesmilies` tinyint(1) NOT NULL,
`letime` int(11) NOT NULL,
`leuser` int(11) NOT NULL,
`attachments` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";





$d[] = "DROP TABLE IF EXISTS `".DBPRE."threads`";
$c[] = "CREATE TABLE `".DBPRE."threads` (
`id` int(10) unsigned NOT NULL auto_increment,
`timestamp` int(11) NOT NULL,
`title` text collate latin1_general_ci NOT NULL,
`description` text collate latin1_general_ci NOT NULL,
`creatorid` int(11) NOT NULL,
`boardid` int(11) NOT NULL,
`replies` int(11) NOT NULL,
`views` int(11) NOT NULL,
`icon` int(11) NOT NULL,
`announcement` tinyint(1) NOT NULL,
`sticky` tinyint(1) NOT NULL,
`locked` tinyint(1) NOT NULL,
`redirecturl` text collate latin1_general_ci NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";





$d[] = "DROP TABLE IF EXISTS `".DBPRE."users`";
$c[] = "CREATE TABLE `".DBPRE."users` (
`id` int(10) unsigned NOT NULL auto_increment,
`name` text collate latin1_general_ci NOT NULL,
`display` text collate latin1_general_ci NOT NULL,
`group` int(11) NOT NULL,
`password` text collate latin1_general_ci NOT NULL,
`activated` tinyint(1) NOT NULL,
`activationcode` text collate latin1_general_ci NOT NULL,
`email` text collate latin1_general_ci NOT NULL,
`emailshow` tinyint(1) NOT NULL,
`emailoptin` tinyint(1) NOT NULL,
`language` text collate latin1_general_ci NOT NULL,
`template` text collate latin1_general_ci NOT NULL,
`posts` int(11) NOT NULL,
`reputation` int(11) NOT NULL,
`aim` text collate latin1_general_ci NOT NULL,
`msn` text collate latin1_general_ci NOT NULL,
`icq` int(11) NOT NULL,
`yim` text collate latin1_general_ci NOT NULL,
`skype` text collate latin1_general_ci NOT NULL,
`xfire` text collate latin1_general_ci NOT NULL,
`website` text collate latin1_general_ci NOT NULL,
`websitename` text collate latin1_general_ci NOT NULL,
`avatar` text collate latin1_general_ci NOT NULL,
`avatarheight` int(11) NOT NULL,
`avatarwidth` int(11) NOT NULL,
`personaltext` text collate latin1_general_ci NOT NULL,
`registered` int(11) NOT NULL,
`lastlogin` int(11) NOT NULL,
`lastlogininvis` int(11) NOT NULL,
`lastip` text collate latin1_general_ci NOT NULL,
`location` text collate latin1_general_ci NOT NULL,
`gender` tinyint(1) NOT NULL,
`birthday` int(11) NOT NULL,
`signature` text collate latin1_general_ci NOT NULL,
`newpmnotify` tinyint(1) NOT NULL,
`invisible` tinyint(1) NOT NULL,
`pmenabled` tinyint(1) NOT NULL,
`timezone` int(11) NOT NULL,
`dst` tinyint(1) NOT NULL,
`dateformat` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";

?>