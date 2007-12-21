<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>{board_title} - {title}</title>
	<style type="text/CSS">
	table.border {
		background-color: #000000;
		color: #FFFFFF;
		width: 750px;
	}
	td.cell1, tr.cell1 td, td.title, tr.title td {
		background-color: #FFFFFF;
		color: #000000;
	}
	span.smalltext {
		font-size: 8pt;
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
</head>
<body>
<div align="center">
<div style="width: 750px">
<table cellpadding="4" cellspacing="1" class="border">
<tr><td class="cell1" align="center">
	{board_title}
</td></tr><tr><td class="cell1" align="center">
	<font size="2">
		<a href="?">Home</a> - 
		<a href="?action=help">Help</a> - 
		<a href="?action=search">Search</a> - 
		<a href="bugs/">Bugs</a> - 
		<!-- if {guest} -->
			<a href="?action=login">Login</a>
			<!-- if {registration_enabled} -->
				 - <a href="?action=register">Register</a>
			<!-- endif -->
		<!-- else -->
			<a href="?action=usercp">User CP</a> - 
			<a href="?action=members">Members</a> - 
			<a href="?action=logout">Logout</a>
		<!-- endif -->
		
	</font>
</td></tr>
</table>
<br /><br />