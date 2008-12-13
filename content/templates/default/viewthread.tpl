{include file="header.tpl"}

<table width="100%" class="border" cellspacing="1">
<tr><td class="title" colspan="6">
	<span style="float: right">
		[ <a href="?action=reply&amp;thread={$threadid}">Reply</a> - Print - Subscribe ]
	</span>
	View thread
</td></tr><tr class="title"><td width="20%">
	Author
</td><td width="80%">
	Post ({$viewcount} View{if $viewcount==1}{else}s{/if})
</td></tr>
{foreach from=$posts item=post}
<tr><td valign="top">
	{$post.userlink}
</td><td>
	<span style="float: right">
		[ <a href="?action=reply&amp;thread={$threadid}&amp;quote={$post.postid}">Quote</a> - <a href="?action=modify&amp;post={$post.id}">Modify</a> - <a href="javascript:void(0);" onclick="if(confirm('Are you sure you wish to delete this post?')) location.href='?action=delete&amp;post={$post.postid}'">Delete</a> ]
	</span>
	{$post.message}
</td></tr>
{/foreach}
</table>

{include file="footer.tpl"}