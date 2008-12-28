{include file="header.tpl"}

<table width="100%" class="border" cellspacing="1">
<tr><td class="title" colspan="6">
	<span style="float: right">
		[ <a href="?action=newthread&amp;board={$boardid}">New Thread</a> - Mark as Read - Subscribe ]
	</span>
	{if $showpagination}
	Pages: 
	{section name=foo start=1 loop=$totalpages+1}
		&nbsp;<a href="?board={$boardid}&amp;page={$smarty.section.foo.index}">{$smarty.section.foo.index}</a>{if $smarty.section.foo.index != $smarty.section.foo.total}, {/if}
	{/section}
	{/if}
</td></tr>
{if count($threads) != 0}
	<tr class="title"><td width="1">
	</td><td>
		Subject
	</td><td width="1" align="center">
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
			{$thread.link}<br />
			[ Pages: 1, 2, 3, 4, 5 ]
		</td><td nowrap="nowrap" valign="middle" align="center">
			{$thread.starterlink}
		</td><td align="center" valign="middle">
			{$thread.replies}
		</td><td align="center" valign="middle">
			{$thread.views}
		</td><td valign="top" width="30%">
			On {$thread.lpdate}<br />
			By {$thread.lpuserlink}
		</td></tr>
	{/foreach}
{else}
	<tr><td colspan="6" align="center">
		There are currently no threads in this board.
	</td></tr>
{/if}
</table>

{include file="footer.tpl"}