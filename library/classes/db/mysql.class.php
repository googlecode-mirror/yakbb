<?php

if(!defined("SNAPONE")) exit;

/*	TODO
	insert()
		- The "secure" part needs more testing
	query()
		- Remove the "or die()" part upon completion.
	cacheQuery()
		- Needs to be finished
		- Needs to be tested
*/

class mysql extends db {
	public $queries=0;
	protected $dbc;
	public $last=false;

	public function connect($c){
		$this->dbc = mysql_connect($c["host"], $c["username"], $c["password"]) or die("Unable to connect to MySQL.");
		mysql_select_db($c["db"]) or die("Unable to select MySQL database.");
	}
	public function cacheQuery($query, $name=false){
		global $cache;
		if($name === false){
			$name = md5($query);
		}
		if($dat = $cache->queryCache($name)){
			return $dat;
		}

		if(is_array($dat) && count($dat) == 0){
			return $dat;
		}

		// Current cache does not exist
		$this->query($query);
		$dat = array();
		while($x = $this->fetch()){
			$dat[] = $x; 
		}
		$this->free();
		// If it's a count, only get the first since there won't be any more
		if(strstr($query, "count(")){
			$dat = $dat[0];
		}
		$cache->queryCacheStore($name, $dat, $query);
		return $dat;
	}
	public function clearCacheQuery($name, $query=false){
		// Clears a cached query
		global $cache;
		if($query){
			// Used it the file wasn't given a specific name
			$name = md5($name);
		}
		return $cache->queryCacheDelete($name);
	}
	public function query($query){
		$this->queries++;
		$this->last = mysql_query($query);
		return $this->last;
	}
	public function insert($table, array $ins){
		// We use the query function because we could change it up for some reason and forget to change here, plus it saves coding then too if there are changes.
		$ins = array_map(array($this, "secure"), $ins);
		return $this->query("INSERT INTO `".DBPRE.$table."` (`".implode("`,`", array_keys($ins))."`) VALUES(\"".implode("\",\"", $ins)."\")");
	}
	public function numRows($res=false){
		if($res === false){
			$res = $this->last;
		}
		return mysql_num_rows($res);
	}
	public function affectedRows($res=false){
		if($res === false){
			$res = $this->last;
		}
		return mysql_affected_rows($res);
	}
	public function fetchAssoc($res=false){
		if($res === false){
				$res = $this->last;
		}
		return mysql_fetch_assoc($res);
	}
	public function free($res=false){
		if($res === false){
				$res = $this->last;
		}
		return mysql_free_result($res);
	}
	public function secure($str){
		return mysql_real_escape_string($str);
	}
	public function close(){
		if($this->dbc !== false){
			@mysql_close($this->dbc);
			$this->dbc = false;
		}
	}
}
?>