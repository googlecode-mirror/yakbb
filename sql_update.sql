-- Until I get the installer up and running, this will be how we deal with SQL updates.
-- The updates to the database structure and content are in this file.
-- Please make sure you've inserted sql_base.sql and then sql_data.sql information first.
-- Also, make sure to not run an update twice... might 'cause issues.





-- August 22nd, 2008 --

-- Create a table for thread subscriptions 
 CREATE TABLE `yakbb_threads_subscriptions` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`userid` INT NOT NULL ,
`threadid` INT NOT NULL ,
`notification` ENUM( '0', '1', '2' ) NOT NULL ,
`timestamp` INT NOT NULL
) ENGINE = MYISAM 


-- Create a table for board subscriptions
 CREATE TABLE `yakbb_boards_subscriptions` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`userid` INT NOT NULL ,
`boardid` INT NOT NULL ,
`notification` ENUM( '0', '1', '2' ) NOT NULL ,
`timestamp` INT NOT NULL
) ENGINE = MYISAM 





-- November 21st, 2008 --

-- Some more settings
INSERT INTO `yakbb_config` (
`id` ,
`name` ,
`value` ,
`groupid` ,
`grouporder`
)
VALUES (
'0', 'username_min_length', '1', '0', '0'
), (
'0', 'username_max_length', '30', '0', '0'
), (
'0', 'displayname_min_length', '1', '0', '0'
), (
'0', 'displayname_max_length', '30', '0', '0'
);


-- Dropped the birthday requirement, for now anyway
 ALTER TABLE `yakbb_users` DROP `birthdayhide`  
 

-- Needed a default timezone
 INSERT INTO `yakbb_config` (
`id` ,
`name` ,
`value` ,
`groupid` ,
`grouporder`
)
VALUES (
'0', 'default_timezone', '-6', '0', '0'
);





-- December 10th, 2008 --

-- Adding more config for posting
INSERT INTO `yakbb_config` (
`id` ,
`name` ,
`value` ,
`groupid` ,
`grouporder`
)
VALUES (
'0', 'subject_min_length', '1', '0', '0'
), (
'0', 'subject_max_length', '100', '0', '0'
), (
'0', 'message_min_length', '1', '0', '0'
), (
'0', 'message_max_length', '50000', '0', '0'
);


-- Even more config options, this time for pagination
INSERT INTO `yakbb_config` (
`id` ,
`name` ,
`value` ,
`groupid` ,
`grouporder`
)
VALUES (
'0', 'threads_per_page', '20', '0', '0'
), (
'0', 'posts_per_page', '10', '0', '0'
);





-- December 12th, 2008 -- 

-- Add "firstpost" variable to posts
ALTER TABLE `yakbb_posts` ADD `firstpost` ENUM( '0', '1' ) NOT NULL ;





-- December 28th, 2008 --

-- Create the table to keep track of last view of a board
CREATE TABLE `yakbb_boards_views` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`boardid` INT NOT NULL ,
`userid` INT NOT NULL
) ENGINE = MYISAM 


-- Create the table to keep track of last view of a thread
CREATE TABLE `yakbb_threads_views` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`threadid` INT NOT NULL ,
`userid` INT NOT NULL
) ENGINE = MYISAM 


-- Add a field to track the ID of the last post read in the thread. This is for the future implementation of "go to last unread post"
ALTER TABLE `yakbb_threads_views` ADD `lastpostreadid` INT NOT NULL 


-- Remove the "boardid" field from polls. It's not necessary in the least...
ALTER TABLE `yakbb_polls` DROP `boardid` 


-- Create the basics of a super admin group
INSERT INTO `yakbb_groups` (
`id` ,
`name` ,
`color` ,
`stars` ,
`admin_access` ,
`sticky_threads` ,
`lock_threads` ,
`announce_threads` ,
`lock_polls` ,
`delete_attachments` ,
`delete_threads` ,
`delete_posts` ,
`delete_polls` ,
`modify_posts` ,
`modify_polls` ,
`create_boards` ,
`delete_boards` ,
`modify_boards` ,
`reorder_boards` ,
`create_categories` ,
`delete_categories` ,
`modify_categories` ,
`reorder_categories` ,
`move_boards` ,
`create_events` ,
`modify_events` ,
`delete_events` ,
`modify_settings` ,
`modify_profiles` ,
`delete_users` ,
`modify_groups` ,
`move_threads` ,
`split_threads` ,
`merge_threads` ,
`view_log` ,
`approve_accounts`
)
VALUES (
'0', 'Super Admin', '', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1'
);