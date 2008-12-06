{include file="header.tpl"}

<table width="100%" class="border" cellspacing="1">
<tr><td class="title" colspan="6">
	<span style="float: right">
		[ Reply - Print - Subscribe ]
	</span>
	View thread
</td></tr><tr class="title"><td width="20%">
	Author
</td><td width="80%">
	Post
</td></tr>
{foreach from=$posts item=post}
<tr><td>
	<a href="{$post.userlink}">{$post.displayname}</a>
</td><td>
	{$post.message}
</td></tr>
{/foreach}
</table>

{include file="footer.tpl"}