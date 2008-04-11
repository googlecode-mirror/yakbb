<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>{board_title} - {title}</title>
	<style type="text/CSS">
	div#holder div {
		text-align: left;
	}
	table.border {
		background-color: #000000;
		color: #FFFFFF;
		width: 750px;
	}
	table.php, table.quote {
		width: 550px;
	}
	td.cell1, tr.cell1 td, td.title, tr.title td {
		background-color: #FFFFFF;
		color: #000000;
	}
	span.smalltext {
		font-size: 8pt;
	}
	a:visited {
		color: #0000FF;
	}

	/* Form Details */
	span.set1 {
		float: left;
	}
	span.set2 {
		float: right;
	}
	span.set1, span.set2{
		width: 350px;
	}
	br.clear {
		clear: both;
	}
	</style>
	<script type="text/Javascript">
	var linkBubble = true;
	</script>
</head>
<body>
<div align="center" id="holder">
<div style="width: 750px">
<table cellpadding="4" cellspacing="1" class="border">
<tr><td class="cell1" style="text-align: center">
	{board_title}
</td></tr><tr><td class="cell1">
	<font size="2">
		<a href="?action=home">{LANG}menu_home{/LANG}</a> - 
		<a href="?action=help">{LANG}menu_help{/LANG}</a> - 
		<a href="?action=search">{LANG}menu_search{/LANG}</a> - 
		<!-- if {guest} -->
			<a href="?action=login">{LANG}menu_login{/LANG}</a>
			<!-- if {registration_enabled} -->
				 - <a href="?action=register">{LANG}menu_register{/LANG}</a>
			<!-- endif -->
		<!-- else -->
			<a href="?action=usercp">{LANG}menu_usercp{/LANG}</a> - 
			<a href="?action=members">{LANG}menu_members{/LANG}</a> - 
			<a href="?action=logout">{LANG}menu_logout{/LANG}</a>
		<!-- endif -->
		
	</font>
</td></tr>
</table>
<br /><br />

<!-- if (CURRENTDBVERSION > DBVERSION or version_compare(CURRENTYAKVERSION, YAKVERSION) == 1) and !isset({upgrade_check}) -->
	<table class="border" cellpadding="4" cellspacing="1">
	<tr><td class="cell1">
		There are currently updates available. Click <a href="?action=upgrade">here</a> to upgrade your forum.
	</td></tr>
	</table>
	<br /><br />
<!-- endif -->
<div id="nav" style="text-align: left">{navtree}</div>