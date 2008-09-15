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
|| File: /core/classes/DB.class.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

defined("YAKBB") or die("Security breach.");

abstract class DB {
	private $lastQuery;
	private $queries = array();
	private $dbc = false;
	abstract function __construct($config);
	public function __deconstruct(){
		$this->disconnect();
	}
	public function connected(){
		return !!$this->dbc;
	}
	abstract function connect($c);
	abstract function disconnect();
	abstract function query($query);
	abstract function cacheQuery($query, $name=false, $expiration=-1);
	public function queryCache($query, $name=false, $expiration=-1){
		return $this->cacheQuery($query, $name, $expiration);
	}
	abstract function clearCacheQuery($query, $name=false);
	public function clearQueryCache($query, $name=false){
		return $this->clearCacheQuery($query, $name);
	}
	abstract function insert($table, array $ins);
	abstract function lastInsertId($ref=false);
	abstract function numRows($ref=false);
	abstract function affectedRows($ref=false);
	abstract function fetch($ref=false);
	public function fetchAssoc($ref=false){
		return $this->fetch($ref);
	}
	abstract function secure($str);
	abstract function free($ref=false);
	public function freeResult($ref=false){
		return $this->free($ref);
	}
	public function getQueries(){
		return $this->queries;
	}
}

?>