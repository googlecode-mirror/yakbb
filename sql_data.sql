-- Until I get the installer up and running, this will be how we deal with SQL updates.
-- Initial SQL data is located in this file.
-- Please make sure you've inserted sql_base.sql data first.


-- phpMyAdmin SQL Dump
-- version 2.11.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 27, 2008 at 02:57 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: 'yakbb_alpha'
--

--
-- Dumping data for table 'yakbb_boards'
--

INSERT INTO yakbb_boards (id, name, description, parenttype, parentid, parentorder, permissions, password, redirecturl, redirects, threads, posts, sublist, modslist, hidden, lastposttime, lastpostuserid, lastpostthreadid) VALUES
(1, 'Board 1', 'This is a test board.', 'c', 1, 1, 'a:3:{i:-1;a:6:{s:4:"view";b:1;s:13:"create_thread";b:0;s:11:"create_poll";b:0;s:10:"post_reply";b:0;s:14:"add_attachment";b:0;s:19:"download_attachment";b:0;}i:0;a:6:{s:4:"view";b:1;s:13:"create_thread";b:1;s:11:"create_poll";b:1;s:10:"post_reply";b:1;s:14:"add_attachment";b:0;s:19:"download_attachment";b:0;}i:1;a:6:{s:4:"view";b:1;s:13:"create_thread";b:1;s:11:"create_poll";b:1;s:10:"post_reply";b:1;s:14:"add_attachment";b:1;s:19:"download_attachment";b:1;}}', '', '', 0, 0, 0, '1', '1', '0', 0, 0, 0);

--
-- Dumping data for table 'yakbb_categories'
--

INSERT INTO yakbb_categories (id, name, description, hideshow, showmain, order, permissions) VALUES
(1, 'Category 1', 'This is a test category', '1', '1', 1, 'a:3:{i:-1;a:1:{s:4:"view";b:1;}i:0;a:1:{s:4:"view";b:1;}i:1;a:1:{s:4:"view";b:1;}}');

--
-- Dumping data for table 'yakbb_config'
--

INSERT INTO yakbb_config (id, name, value, groupid, grouporder) VALUES
(1, 'sef_urls', 'false', 0, 0),
(2, 'subs_count_towards_parent', 'true', 0, 0),
(3, 'default_template', 'default', 0, 0),
(4, 'default_language', 'en', 0, 0),
(5, 'dst_default', 'true', 0, 0),
(6, 'board_title', 'a:1:{s:2:"en";s:16:"My Message Board";}', 0, 0),
(7, 'registration_enabled', 'true', 0, 0),
(8, 'session_login', 'true', 0, 0),
(9, 'activation_required', 'false', 0, 0),
(10, 'unique_email', 'true', 0, 0),
(11, 'switch_users', 'true', 0, 0),
(12, 'show_version', 'true', 0, 0),
(13, 'timezone_default', '-6', 0, 0),
(14, 'admin_approval', 'false', 0, 0),
(15, 'pm_enabled', 'true', 0, 0);