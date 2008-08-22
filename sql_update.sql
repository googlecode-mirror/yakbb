-- Until I get the installer up and running, this will be how we deal with SQL updates.
-- The updates to the database structure and content are in this file.
-- Please make sure you've inserted sql_base.sql and then sql_data.sql information first.
-- Also, make sure to only run the most recent updates.


-- August 22nd, 2008 --

-- Create a table for thread subscriptions 
 CREATE TABLE `yakbb_alpha`.`yakbb_threads_subscriptions` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`userid` INT NOT NULL ,
`threadid` INT NOT NULL ,
`notification` ENUM( '0', '1', '2' ) NOT NULL ,
`timestamp` INT NOT NULL
) ENGINE = MYISAM 

-- Create a table for board subscriptions
 CREATE TABLE `yakbb_alpha`.`yakbb_boards_subscriptions` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`userid` INT NOT NULL ,
`boardid` INT NOT NULL ,
`notification` ENUM( '0', '1', '2' ) NOT NULL ,
`timestamp` INT NOT NULL
) ENGINE = MYISAM 