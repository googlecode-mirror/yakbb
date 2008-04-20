<br /><br />
<div style="border: 1px #000000 solid; padding: 4px">
	YakBB copyright Chris Dessonville - YakBB<?php if(getSetting("show_version") == true){ ?> v<?php echo YAKVERSION; ?><?php } ?><br /><br />
	Generation Time: <?= getGenTime() ?> seconds - 
	Queries: <?= getQueries() ?><br />
	Memory Usage: <?= number_format(memory_get_usage()) ?> bytes
	<?php if(function_exists("memory_get_peak_usage")){ ?>
		- Peak: <?= number_format(memory_get_peak_usage()) ?> bytes
	<?php } ?>
	<br /><br />
	Queries List:<br />
	<?php global $db;
	foreach($db->queriesList as $k => $v){
		echo $v."<br />";
	}
	?>
</div>

</div></div>
</body>
</html>