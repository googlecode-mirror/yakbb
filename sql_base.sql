-- Until I get the installer up and running, this will be how we deal with SQL updates.
-- Initial SQL structure is located in this file.

-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 04, 2008 at 02:11 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `yakbb_alpha`
--

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_attachments`
--

CREATE TABLE `yakbb_attachments` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `postid` int(11) NOT NULL,
  `originalname` text NOT NULL,
  `newname` text NOT NULL,
  `preview` text NOT NULL,
  `filetype` text NOT NULL,
  `filesize` int(11) NOT NULL,
  `downloads` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_bans`
--

CREATE TABLE `yakbb_bans` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` enum('0','1','2') NOT NULL,
  `value` text NOT NULL,
  `started` int(11) NOT NULL,
  `expires` int(11) NOT NULL,
  `reason` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_boards`
--

CREATE TABLE `yakbb_boards` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `parenttype` enum('b','c') NOT NULL default 'c',
  `parentid` int(11) NOT NULL,
  `parentorder` int(11) NOT NULL,
  `permissions` text NOT NULL,
  `password` text NOT NULL,
  `redirecturl` text NOT NULL,
  `redirects` int(11) NOT NULL,
  `threads` int(11) NOT NULL,
  `posts` int(11) NOT NULL,
  `sublist` enum('0','1') NOT NULL default '1',
  `hidden` enum('0','1') NOT NULL default '0',
  `lastposttime` int(11) NOT NULL,
  `lastpostuserid` int(11) NOT NULL,
  `lastpostthreadid` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_categories`
--

CREATE TABLE `yakbb_categories` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `hideshow` enum('0','1') NOT NULL default '1',
  `showmain` enum('0','1') NOT NULL default '1',
  `order` int(11) NOT NULL,
  `permissions` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_config`
--

CREATE TABLE `yakbb_config` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `value` text NOT NULL,
  `groupid` int(11) NOT NULL,
  `grouporder` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_groups`
--

CREATE TABLE `yakbb_groups` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `color` text NOT NULL,
  `stars` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_messages`
--

CREATE TABLE `yakbb_messages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `message` text NOT NULL,
  `senderid` int(11) NOT NULL,
  `threadid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `disableubbc` enum('0','1') NOT NULL,
  `disablesmilies` enum('0','1') NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_messages_relations`
--

CREATE TABLE `yakbb_messages_relations` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `threadid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `lastread` int(11) NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_messages_threads`
--

CREATE TABLE `yakbb_messages_threads` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` text NOT NULL,
  `lastupdated` int(11) NOT NULL,
  `initialsend` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_moderators_relations`
--

CREATE TABLE `yakbb_moderators_relations` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `boardid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_polls`
--

CREATE TABLE `yakbb_polls` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `threadid` int(11) NOT NULL,
  `boardid` int(11) NOT NULL,
  `question` text NOT NULL,
  `closed` enum('0','1') NOT NULL,
  `expires` int(11) NOT NULL default '0',
  `canchoose` int(11) NOT NULL default '1',
  `viewresults` enum('0','1','2','3','4') NOT NULL,
  `canretract` enum('0','1') NOT NULL,
  `answer1` text NOT NULL,
  `answer2` text NOT NULL,
  `answer3` text NOT NULL,
  `answer4` text NOT NULL,
  `answer5` text NOT NULL,
  `answer6` text NOT NULL,
  `answer7` text NOT NULL,
  `answer8` text NOT NULL,
  `answer9` text NOT NULL,
  `answer10` text NOT NULL,
  `answer11` text NOT NULL,
  `answer12` text NOT NULL,
  `answer13` text NOT NULL,
  `answer14` text NOT NULL,
  `answer15` text NOT NULL,
  `answer16` text NOT NULL,
  `answer17` text NOT NULL,
  `answer18` text NOT NULL,
  `answer19` text NOT NULL,
  `answer20` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_polls_votes`
--

CREATE TABLE `yakbb_polls_votes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pollid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `votefor` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_posts`
--

CREATE TABLE `yakbb_posts` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `threadid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `message` text NOT NULL,
  `title` text NOT NULL,
  `disableubbc` enum('0','1') NOT NULL,
  `disablesmilies` enum('0','1') NOT NULL,
  `lastedittime` int(11) NOT NULL,
  `lastedituser` int(11) NOT NULL,
  `attachments` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_threads`
--

CREATE TABLE `yakbb_threads` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `creatorid` int(11) NOT NULL,
  `parentid` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `replies` int(11) NOT NULL,
  `lastposttime` int(11) NOT NULL,
  `lastpostuser` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `icon` int(11) NOT NULL,
  `haspoll` enum('0','1') NOT NULL,
  `announcement` enum('0','1') NOT NULL,
  `sticky` enum('0','1') NOT NULL,
  `locked` enum('0','1') NOT NULL,
  `redirecturl` text NOT NULL,
  `redirects` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_users`
--

CREATE TABLE `yakbb_users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` text NOT NULL,
  `displayname` text NOT NULL,
  `password` text NOT NULL,
  `group` int(11) NOT NULL default '0',
  `email` text NOT NULL,
  `emailshow` enum('0','1') NOT NULL default '0',
  `emailoptin` enum('0','1') NOT NULL default '1',
  `activated` enum('0','1') NOT NULL default '0',
  `activationcode` text NOT NULL,
  `pending` enum('0','1') NOT NULL,
  `registeredtime` int(11) NOT NULL,
  `gender` enum('0','1','2') NOT NULL default '0',
  `lastip` text NOT NULL,
  `lastlogin` int(11) NOT NULL,
  `lastlogininvis` int(11) NOT NULL,
  `invisible` enum('0','1') NOT NULL default '0',
  `template` text NOT NULL,
  `language` text NOT NULL,
  `posts` int(11) NOT NULL,
  `reputation` int(11) NOT NULL,
  `aim` text NOT NULL,
  `msn` text NOT NULL,
  `icq` int(11) NOT NULL,
  `yim` text NOT NULL,
  `skype` text NOT NULL,
  `xfire` text NOT NULL,
  `website` text NOT NULL,
  `websitename` text NOT NULL,
  `avatar` text NOT NULL,
  `avatarheight` int(11) NOT NULL,
  `avatarwidth` int(11) NOT NULL,
  `personaltext` text NOT NULL,
  `location` text NOT NULL,
  `birthday` int(11) NOT NULL,
  `birthdayhide` enum('0','1') NOT NULL default '1',
  `signature` text NOT NULL,
  `newpmnotify` enum('0','1','2','3') NOT NULL,
  `pmenabled` enum('0','1','2') NOT NULL,
  `timezone` int(11) NOT NULL,
  `dst` enum('0','1') NOT NULL,
  `dateformat` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
