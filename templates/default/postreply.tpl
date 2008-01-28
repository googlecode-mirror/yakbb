<!-- if count({errors}) is not 0 -->
<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="cell1">
	{LANG}error_title{/LANG}
</td></tr><tr><td class="cell1">
	<!-- if count({errors}) is 1 -->
		{LANG}error_occured{/LANG}
	<!-- else -->
		{LANG}errors_occured{/LANG}
	<!-- endif -->
	<ul>
		<!-- repeat:errors:err -->
		<li>{repeat:err}</li>
		<!-- endrepeat -->
	</ul>
</td></tr>
</table>
<br /><br />
<!-- endif -->
<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	{LANG}reply_title{/LANG}
</td></tr><tr><td class="cell1">
	<form action="?action=reply&amp;id={tid}" method="post" name="replyForm">
		Title: <input type="text" value="{posttitle}" name="posttitle" maxlength="{thread_subject_max}" size="30" /><br />
		Message:<br />
		<textarea name="postmessage">{postmessage}</textarea>
		<br /><br /><input type="submit" value="{LANG}submit_button{/LANG}" name="submitit" />
	</form>
</td></tr>
</table>