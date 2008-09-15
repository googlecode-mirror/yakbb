<html>
<head>
	<title>{$board_title} - {$page_title}</title>
	<style type="text/CSS">
	{literal}
	body {
		background-color: #E4E4E4;
	}
	table.border {
		background-color: #000000;
	}
	table.border tr td {
		padding: 4px;
	}
	tr.title td, td.title {
		background-color: #DDDDDD;
	}
	td {
		background-color: #F6F6F6;
	}
	{/literal}
	</style>
</head>
<body>

<div align="center">
<div style="width: 750px; text-align: left">

<table class="border" width="100%" cellspacing="1">
<tr class="title"><td align="center">
	<span style="font-size: 18pt">
		Whee
	</span>
</td></tr><tr><td align="center">
	<span style="font-size: 11pt">
	[ <a href="?">Home</a> | 
	<a href="?action=help">Help</a> | 
	{if $guest == false}
		<a href="?action=search">Search</a> | 
		<a href="?action=calendar">Calendar</a> | 
		<a href="?action=members">Members</a> | 
		{if $admin_access == true}
			<a href="?action=admin">Admin</a> | 
		{/if}
		<a href="?action=logout">Logout</a>
	{else}
		<a href="?action=login">Login</a> | 
		<a href="?action=register">Register</a>
	{/if}]
	</span>
</td></tr>
</table>