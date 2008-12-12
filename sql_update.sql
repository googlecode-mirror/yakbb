-- Until I get the installer up and running, this will be how we deal with SQL updates.
-- The updates to the database structure and content are in this file.
-- Please make sure you've inserted sql_base.sql and then sql_data.sql information first.
-- Also, make sure to only run the most recent updates.


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


-- Dropped ths birthday requirement, for now anyway
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