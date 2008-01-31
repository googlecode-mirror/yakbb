<?php

if(!defined("SNAPONE")) exit;

/*
We define this as an abstract class so that we can make 100% sure that all classes have the same REQUIRED functions. They can add
their own additional functions if they wish, but they should only be designed to be called internally from within the class and not
externally from the script. Meaning, they should not be public functions.

NOTES
- All query() functions must raise the $queries value by one to tally queries used.
- insert() is designed to create an insert statement when provided with a table name and an array containing elements to be inserted. Keys are the column names and values are the new values. The insert is also responsible for securing against SQL injection.
- connect() also selects the database in question.
- cacheQuery() loads data from the cache and only queries the DB if the data isn't present.
- secure() is only designed to protect against SQL injection.
- Don't define queryCache(), fetch(), freeResult(), and __destruct() in the actual files.
*/

abstract class db {
	public $queries=0;
	protected $dbc;
	public $last;

	abstract public function connect($data);
	public function queryCache($query, $name=false){
		return $this->cacheQuery($query, $name);
	}
	abstract public function cacheQuery($query, $name=false);
	abstract public function clearCacheQuery($name, $query=false);
	abstract public function query($query);
	abstract public function insert($table, array $ins);
	abstract public function insertId();
	abstract public function numRows($res=false);
	abstract public function affectedRows($res=false);
	abstract public function fetchAssoc($res=false);
	public function fetch($res=false){
		return $this->fetchAssoc($res);
	}
	abstract public function free($res=false);
	public function freeResult($res=false){
		return $this->free($res);
	}
	abstract public function secure($str);
	abstract public function close();
	public function __destruct(){
		$this->close();
	}
}
?>