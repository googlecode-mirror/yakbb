--
-- Dumping data for table `yakbb_boards`
--

INSERT INTO `yakbb_boards` (`id`, `parentid`, `parenttype`, `name`, `description`, `threads`, `posts`, `order`, `sublist`, `permissions`) VALUES
(1, 1, 'c', 'Board 1', 'o.o', 8, 28, 1, '1', 'a:3:{i:-1;a:5:{s:4:"view";b:1;s:5:"reply";b:0;s:4:"poll";b:0;s:6:"thread";b:0;s:6:"attach";b:0;}i:0;a:5:{s:4:"view";b:1;s:5:"reply";b:1;s:4:"poll";b:1;s:6:"thread";b:1;s:6:"attach";b:0;}i:1;a:5:{s:4:"view";b:1;s:5:"reply";b:1;s:4:"poll";b:1;s:6:"thread";b:1;s:6:"attach";b:1;}}'),
(2, 2, 'c', 'Board 2', 'Should be [b]u[i]nde[/i]r[/b] category 2', 0, 0, 1, '1', 'a:3:{i:-1;a:5:{s:4:"view";b:1;s:5:"reply";b:0;s:4:"poll";b:0;s:6:"thread";b:0;s:6:"attach";b:0;}i:0;a:5:{s:4:"view";b:1;s:5:"reply";b:1;s:4:"poll";b:1;s:6:"thread";b:1;s:6:"attach";b:0;}i:1;a:5:{s:4:"view";b:1;s:5:"reply";b:1;s:4:"poll";b:1;s:6:"thread";b:1;s:6:"attach";b:1;}}'),
(3, 0, 'c', 'Board 3', 'This should appear under no category.', 0, 0, 1, '1', 'a:3:{i:-1;a:5:{s:4:"view";b:1;s:5:"reply";b:0;s:4:"poll";b:0;s:6:"thread";b:0;s:6:"attach";b:0;}i:0;a:5:{s:4:"view";b:1;s:5:"reply";b:1;s:4:"poll";b:1;s:6:"thread";b:1;s:6:"attach";b:0;}i:1;a:5:{s:4:"view";b:1;s:5:"reply";b:1;s:4:"poll";b:1;s:6:"thread";b:1;s:6:"attach";b:1;}}'),
(4, 3, 'b', 'Sub-Board 1', 'Testing... 1, 2, 3. :)', 0, 0, 1, '1', 'a:3:{i:-1;a:5:{s:4:"view";b:1;s:5:"reply";b:0;s:4:"poll";b:0;s:6:"thread";b:0;s:6:"attach";b:0;}i:0;a:5:{s:4:"view";b:1;s:5:"reply";b:1;s:4:"poll";b:1;s:6:"thread";b:1;s:6:"attach";b:0;}i:1;a:5:{s:4:"view";b:1;s:5:"reply";b:1;s:4:"poll";b:1;s:6:"thread";b:1;s:6:"attach";b:1;}}'),
(5, 1, 'c', 'Board 5... What?', '[b]Te[/b]st.', 0, 0, 2, '1', 'a:3:{i:-1;a:5:{s:4:"view";b:1;s:5:"reply";b:0;s:4:"poll";b:0;s:6:"thread";b:0;s:6:"attach";b:0;}i:0;a:5:{s:4:"view";b:1;s:5:"reply";b:1;s:4:"poll";b:1;s:6:"thread";b:1;s:6:"attach";b:0;}i:1;a:5:{s:4:"view";b:1;s:5:"reply";b:1;s:4:"poll";b:1;s:6:"thread";b:1;s:6:"attach";b:1;}}');

-- --------------------------------------------------------

--
-- Dumping data for table `yakbb_categories`
--

INSERT INTO `yakbb_categories` (`id`, `name`, `hideshow`, `showmain`, `order`, `permissions`) VALUES
(1, 'Category 1', 1, 1, 1, 'a:3:{i:-1;a:1:{s:4:"view";b:1;}i:0;a:1:{s:4:"view";b:1;}i:1;a:1:{s:4:"view";b:1;}}'),
(2, 'Category 2', 1, 1, 2, 'a:3:{i:-1;a:1:{s:4:"view";b:1;}i:0;a:1:{s:4:"view";b:1;}i:1;a:1:{s:4:"view";b:1;}}'),
(3, 'Category 3', 1, 1, 3, 'a:3:{i:-1;a:1:{s:4:"view";b:1;}i:0;a:1:{s:4:"view";b:1;}i:1;a:1:{s:4:"view";b:1;}}');

-- --------------------------------------------------------

--
-- Dumping data for table `yakbb_config`
--

INSERT INTO `yakbb_config` (`id`, `name`, `value`) VALUES
(1, 'default_template', 'default'),
(2, 'default_language', 'en'),
(4, 'defaulttimezone', '-6'),
(5, 'dst', 'true'),
(6, 'board_title', 'Message Board'),
(7, 'registration_enabled', 'true'),
(8, 'session_login', 'true'),
(10, 'username_max_length', '30'),
(11, 'displayname_max_length', '30'),
(12, 'activation_required', 'false'),
(13, 'username_min_length', '2'),
(14, 'displayname_min_length', '1'),
(15, 'unique_email', 'true'),
(16, 'switch_users', 'true'),
(18, 'mod_list_main', 'true'),
(19, 'seo_engine', 'true'),
(20, 'thread_desc_max', '100'),
(21, 'thread_subject_max', '50'),
(22, 'thread_message_max', '25000'),
(23, 'posts_per_page', '15'),
(24, 'threads_per_page', '30'),
(9, 'show_version', 'true');

-- --------------------------------------------------------

--
-- Dumping data for table `yakbb_groups`
--

INSERT INTO `yakbb_groups` (`id`, `name`, `color`, `replytolocked`) VALUES
(1, 'Admin', 'a:2:{s:7:"default";s:7:"#FF0000";s:9:"template2";s:7:"#0000FF";}', '1');