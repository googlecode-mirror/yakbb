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
	public function cacheQuery($query){
		global $cache;
		$q2 = md5($query);
		if($dat = $cache->queryCache($q2)){
			return $dat;
		}

		// Current cache does not exist
		$this->query($query);
		$dat = array();
		while($x = $this->fetch()){
			$dat[] = $x; 
		}
		$this->free();
		$cache->queryCacheStore($q2, $dat);
		return $dat;
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