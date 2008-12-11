{include file="header.tpl"}

<table width="100%" class="border" cellspacing="1">
<tr><td class="title" colspan="6">
	<span style="float: right">
		[ <a href="?action=newthread&amp;board={$boardid}">New Thread</a> - Mark as Read - Subscribe ]
	</span>
	View board
</td></tr><tr class="title"><td width="1">
</td><td>
	Subject
</td><td width="75" align="center">
	Starter
</td><td width="1" align="center">
	Replies
</td><td width="1" align="center">
	Views
</td><td width="100">
	Last Post
</td></tr>
{foreach from=$threads item=thread}
<tr><td valign="middle">
	Icon
</td><td valign="top">
	<a href="{$thread.link}">{$thread.name}</a><br />
	[ Pages: 1, 2, 3, 4, 5 ]
</td><td nowrap="nowrap" valign="middle">
	Starter here. wrapping test
</td><td align="center" valign="middle">
	{$thread.replies}
</td><td align="center" valign="middle">
	{$thread.views}
</td><td valign="top" nowrap="nowrap">
	On Dec 32nd, 2108<br />
	By Some User
</td></tr>
{/foreach}
</table>

{include file="footer.tpl"}