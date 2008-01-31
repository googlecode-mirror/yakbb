<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	<!-- POSTING LINKS HERE LATER -->
	[ <a href="?action=reply&amp;id={tid}">Reply</a> | Print ]
</td></tr>
</table><br /><br />

<!-- repeat:posts:post -->
<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="cell1" width="20%">
	{repeat:post->userlink}
</td><td class="cell1" width="80%">
	{repeat:post->ptitle} - Posted on {repeat:post->date} - [ <a href="?action=reply&amp;id={tid}">Reply</a> | <a href="?action=reply&amp;id={tid}&amp;quote={repeat:post->pid}">Quote</a> | Modify | Delete ]
	<hr size="1" />
	{repeat:post->message}
</td></tr>
</table><br /><br />
<!-- endrepeat -->


<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	POSTING LINKS HERE LATER
</td></tr>
</table>