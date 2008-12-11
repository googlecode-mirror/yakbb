{include file="header.tpl"}

{foreach from=$cats item=cat}
	<table class="border" width="100%" cellspacing="1">
	<tr><td class="title" colspan="5">
		{if $cat.hideshow == 1}
		<span style="float: right">[ <a href="javascript:void(0);">+</a> ]</span>
		{/if}
		{$cat.name}
	</td></tr><tr class="title"><td>
	</td><td>
		Board Data
	</td><td>
		Posts
	</td><td>
		Threads
	</td><td>
		Last Post
	</td></tr>
	{foreach from=$cat.boards item=board}
		<tr><td align="center" width="1">
			On/off
		</td><td valign="top" onclick="location.href='{$board.url}';">
			{$board.link}<br />
			{$board.description}
			{if $board.sublist == 1}
				<br />{$board.sublisting}
			{/if}
			{if $board.modslist == 1}
				<br />{$board.modlisting}
			{/if}
		</td><td align="center" width="1">
			{$board.posts}
		</td><td align="center" width="1">
			{$board.threads}
		</td><td valign="top">
			Last post
		</td></tr>
	{/foreach}
	</table>
{/foreach}

{include file="footer.tpl"}