{include file="header.tpl"}

{if count($posterrors) != 0}
	<table width="100%" class="border" cellspacing="1">
	<tr><td class="title">
		Errors
	</td></tr><tr><td>
		The following error(s) occured: <ul>
			{foreach from=$posterrors item=error}
			<li>{$error}
			{/foreach}
			</ul>
	</td></tr>
	</table><br /><br />
{/if}

<form name="postForm" action="?action={$actiontype}" method="post">
	{if $actiontype == "reply"}
		<input type="hidden" name="thread" value="{$threadid}" />
	{elseif $actiontype == "newthread"}
		<input type="hidden" name="board" value="{$boardid}" />
	{elseif $actiontype == "modify"}
		<input type="hidden" name="post" value="{$postid}" />
	{/if}
	<table width="100%" class="border" cellspacing="1">
	<tr><td colspan="2" class="title">
	{if $actiontype == "reply"}
		Reply
	{elseif $actiontype == "newthread"}
		New Thread
	{elseif $actiontype == "modify"}
		Modify Post
	{/if}
	</td></tr><tr><td>
		Subject:
	</td><td>
		<input type="text" name="subject" value="{$sent_subject}" />
	</td></tr><tr><td>
		Icon:
	</td><td>
		Icons here
	</td></tr><tr><td valign="top">
		Message:
	</td><td>
		<textarea name="message" cols="50" rows="10">{$sent_message}</textarea>
	</td></tr><tr><td colspan="2" align="center">
		<input type="submit" value="Post" name="submitpost" /> 
		<input type="submit" value="Preview" name="previewpost" onclick="alert('Not implemented'); return false" disabled="disabled" /> 
		<input type="reset" value="Reset" name="resetpost" onclick="return confirm('Do you really weant to reset the form?');" />
	</td></tr>
	</table>
</form>

{include file="footer.tpl"}