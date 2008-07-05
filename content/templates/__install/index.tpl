<html>
<head>
	<title>YakBB Installation (Part {$part|default:1})</title>
	<style type="text/CSS">
	{literal}
	body {
		background-color: #EEEEEE;
	}
	table.border {
		background-color: #000000;
		width: 700px;
	}
	table.border tr td {
		background-color: #DDDDDD;
		color: #000000;
	}
	{/literal}
	</style>
</head>
<body>
<center>
{if $part == 2}
	Part 2
{elseif $part == 3}
	Part 3
{elseif $part == 4}
	Part 4
{else}
	<table class="border" cellpading="4" cellspacing="1">
	<tr><td>
		Welcome to the YakBB installer. Let's get started, shall we?
	</td></tr>
	</table>
{/if}
</center>
</body>
</html>