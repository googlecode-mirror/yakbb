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
|| File: /core/classes/DB_mysql.class.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

defined("YAKBB") or die("Security breach.");

class DB_mysql implements DB {
	private $lastQuery;
	private $queries = array();
	private $dbc = false;

	public function __construct($config){
		$this->connect($config);
	}

	public function connect($c){
		$this->dbc = mysql_connect($c["dbhost"], $c["dbuser"], $c["dbpass"]) or die("Unable to connect to MySQL.");
		mysql_select_db($c["dbdb"]) or die("Unable to select MySQL database.");
	}

	public function disconnect(){
		if($this->dbc !== false){
			@mysql_close($this->dbc);
			$this->dbc = false;
		}
	}

	public function query($query){
		$query = preg_replace("/yakbb_/", YAKBB_DBPRE, $query);
		$this->queries[] = $query;
		$this->lastQuery = mysql_query($query);
		if(mysql_error()){
			die(mysql_error()."<br /><br /><a href='?action=upgrade'>Are you sure your installation is up to date?</a>");
		}
		return $this->last;

	}

	public function cacheQuery($query, $name=false){
	}

	public function clearCacheQuery($query, $name=false){
	}

	public function insert($table, array $ins){
		// We use the query function because we could change it up for some reason and forget to change here, plus it saves coding then too if there are changes.
		$ins = array_map(array($this, "secure"), $ins);
		return $this->query("INSERT INTO `yakbb_".$table."` (`".implode("`,`", array_keys($ins))."`) VALUES(\"".implode("\",\"", $ins)."\")");
	}

	public function lastInsertId($ref=false){
		if($this->dbc){
			return mysql_insert_id($this->dbc);
		}
	}

	public function numRows($ref=false){
		if($ref === false){
			$ref = $this->lastQuery;
		}
		return mysql_num_rows($ref);
	}

	public function affectedRows($ref=false){
		if($ref === false){
			$ref = $this->lastQuery;
		}
		return mysql_affected_rows($ref);
	}

	public function fetch($ref=false){
		if($ref === false){
			$ref = $this->lastQuery;
		}
		return mysql_fetch_assoc($ref);
	}

	public function secure($str){
		return mysql_real_escape_string($str);
	}

	public function free($ref=false){
		if($ref === false){
			$ref = $this->lastQuery;
		}
		return mysql_free_result($ref);

	}
}

?>