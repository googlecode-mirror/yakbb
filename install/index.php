<?php

/*	TODO
		Step 0
			- Check config.inc.php writability.
			- Check uploadability to uploads directories
			- Check writability to cache directory
		Step 1
			- Beautify
			- Add support for port and prefix
		Step 2
			- Needs to generate valid salt
			- Add support for port and prefix
*/

define("SNAPONE", 1);

if(file_exists("./install.lock") || file_exists("install.lock")){
	die("Please delete the \"install.lock\" file if you wish to use the installer.");
}

if(!defined("SNAPONE")) exit;

?>
<html>
<head>
	<title>Install</title>
	<style type="text/CSS">
	.border {
		width: 700px;
		background-color: #000000;
	}
	.title, .cell1 {
		background-color: #FFFFFF;
	}
	</style>
</head>
<body><?php

$step = intval($_GET["step"]);

if($step == 0){
	// Load type selection
	$checks = array(
		/* array(
			"Name",
			"error message",
			check_code,
			display,
			required
		) */
		array(
			"PHP Version",
			"You must be using PHP 5. PHP 5.1.2 is recommended for the minimum.",
			(version_compare("5.0.0", phpversion()) < 0),
			phpversion(),
			"5.0.0"
		)
	);
	?>
	<table class="border" cellpadding="4" cellspacing="1" align="center">
	<tr><td class="title">
		Compatibility Check
	</td></tr>
	<tr><td class="cell1">
		<table width='100%'>
		<tr><td>Name</td><td>Description</td><td>Yours</td><td>Required</td></tr>
		<?php
			$broke = false;
			foreach($checks as $k => $v){
				if($v[2] == false){
					$broke = true;
					$col = " bgcolor='#CC6666'";
				} else {
					$col = " bgcolor='#DDDDDD'";
				}
				echo "<tr".$col."><td>".$v[0]."</td><td>".$v[1]."</td><td align='center'>".$v[3]."</td><td align='center'>".$v[4]."</td></tr>";
			}
		?>
		</table>
		<?php
			if($broke === false){
				echo "<br><br>You have passed the checks. Click <a href='?step=1'>here</a> to install YakBB";
			}
		?>
	</td></tr>
	</table>
	<?php
} else if($step == 1){
	?>
	<table class="border" cellpadding="4" cellspacing="1" align="center">
	<tr><td class="cell1">
	<form method="post" action="?step=2" name="installForm">
		<input type="hidden" name="dbtype" value="mysql" />
		Database Host: <input type="text" name="dbhost" value="localhost" /><br />
		Database Name: <input type="text" name="dbname" value="yakbb" /><br />
		Database User: <input type="text" name="dbuser" value="root" /><br />
		Database Password: <input type="password" name="dbpass" />

		<br /><br />
		<input type="submit" name="submit2" value="Install" />
	</form>
	</td></tr>
	</table>	
	<?php
} else if($step == 2){
	$valid_dbtypes = array("mysql");
	if(!in_array($_REQUEST["dbtype"], $valid_dbtypes)){
		die("Security hole attempt detected in DB Type");
	}
	$dbtype = $_REQUEST["dbtype"];
	$dbpre  = "yakbb_";
	define("DBPRE", $dbpre); // Just for compatibility purposes
	$dbhost = $_REQUEST["dbhost"];
	$dbname = $_REQUEST["dbname"];
	$dbuser = $_REQUEST["dbuser"];
	$dbpass = $_REQUEST["dbpass"];
	require "../constants.inc.php";
	require ".".LIBDIR."classes/flat_file.class.php";
	require ".".LIBDIR."classes/db/db.abstract.class.php";
	require ".".LIBDIR."classes/db/".$dbtype.".class.php";
	$db = new $dbtype();
	$db->connect(array(
		"host" => $dbhost,
		"username" => $dbuser,
		"password" => $dbpass,
		"db" => $dbname
	));

	// Would have died had no connection been established. Let's save the data to the file.
	$ff = new flat_file();
	$dat = $ff->loadFile("./config_blank.dat");
	$searches = array(
		"/{YAKVER}/",
		"/{DBVER}/",
		"/{DBSALT}/",
		"/{DBTYPE}/",
		"/{DBPRE}/",
		"/{DBUSER}/",
		"/{DBPASS}/",
		"/{DBHOST}/",
		"/{DBPORT}/",
		"/{DBNAME}/"
	);
	$replaces = array(
		"1.0.0a1",
		1,
		'#@C$#$%&^#$B%#$G%#%saFDSADFSA@',
		$dbtype,
		"yakbb_",
		$dbuser,
		$dbpass,
		$dbhost,
		21,
		$dbname
	);
	$dat = preg_replace($searches, $replaces, $dat);
	$ff->updateFile("../config.inc.php", $dat);
	require "./db/".$dbtype."/install_sql.php";
	require "./db/".$dbtype."/upgrade_sql.php";
	foreach($d as $k => $v){
		$db->query($v);
	}
	foreach($c as $k => $v){
		$db->query($v);
	}
	foreach($i as $k => $v){
		$db->query($v);
	}
	$upgrade = new upgrade_sql();
	$upgrade->upgrade(1);
	$ff->updateFile("./install.lock", "");
	?>
	Forum data successfully installed. Redirecting to admin registration page...
	<script type='text/Javascript'>
	setTimeout(function(){ location.href = '../?action=register'; }, 2000);
	</script>
	<?php
}
?>

</body>
</html>