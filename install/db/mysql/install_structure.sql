--
-- Table structure for table `yakbb_attachments`
--

CREATE TABLE `yakbb_attachments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `postid` int(11) NOT NULL,
  `originalname` text collate latin1_general_ci NOT NULL,
  `newname` text collate latin1_general_ci NOT NULL,
  `preview` text collate latin1_general_ci NOT NULL,
  `filetype` text collate latin1_general_ci NOT NULL,
  `downloads` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_bans`
--

CREATE TABLE `yakbb_bans` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL,
  `value` text NOT NULL,
  `expires` int(11) NOT NULL,
  `reason` text NOT NULL,
  `started` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_boards`
--

CREATE TABLE `yakbb_boards` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `parentid` int(11) NOT NULL,
  `parenttype` enum('b','c') collate latin1_general_ci NOT NULL,
  `name` text collate latin1_general_ci NOT NULL,
  `description` text collate latin1_general_ci NOT NULL,
  `threads` int(11) NOT NULL,
  `posts` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `sublist` enum('0','1') collate latin1_general_ci NOT NULL default '1',
  `permissions` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_categories`
--

CREATE TABLE `yakbb_categories` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` text collate latin1_general_ci NOT NULL,
  `hideshow` int(1) NOT NULL,
  `showmain` int(1) NOT NULL,
  `order` int(11) NOT NULL,
  `permissions` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_config`
--

CREATE TABLE `yakbb_config` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` text collate latin1_general_ci NOT NULL,
  `value` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_groups`
--

CREATE TABLE `yakbb_groups` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `color` text NOT NULL,
  `replytolocked` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_messages`
--

CREATE TABLE `yakbb_messages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `message` text NOT NULL,
  `senderid` int(11) NOT NULL,
  `threadid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
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
  `pmid` int(11) NOT NULL,
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
  `id` int(10) unsigned NOT NULL auto_increment,
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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_poll_votes`
--

CREATE TABLE `yakbb_poll_votes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pollid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `votefor` int(2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_posts`
--

CREATE TABLE `yakbb_posts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `threadid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `message` text collate latin1_general_ci NOT NULL,
  `title` text collate latin1_general_ci NOT NULL,
  `disableubbc` tinyint(1) NOT NULL,
  `disablesmilies` tinyint(1) NOT NULL,
  `letime` int(11) NOT NULL,
  `leuser` int(11) NOT NULL,
  `attachments` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_threads`
--

CREATE TABLE `yakbb_threads` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `timestamp` int(11) NOT NULL,
  `title` text collate latin1_general_ci NOT NULL,
  `description` text collate latin1_general_ci NOT NULL,
  `creatorid` int(11) NOT NULL,
  `boardid` int(11) NOT NULL,
  `replies` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `icon` int(11) NOT NULL,
  `announcement` enum('0','1') collate latin1_general_ci NOT NULL default '0',
  `sticky` enum('0','1') collate latin1_general_ci NOT NULL default '0',
  `locked` enum('0','1') collate latin1_general_ci NOT NULL default '0',
  `redirecturl` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `yakbb_users`
--

CREATE TABLE `yakbb_users` (
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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;