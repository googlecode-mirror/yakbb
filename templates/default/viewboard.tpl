<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title" colspan="4">
	[ <a href="?action=newthread&amp;board={bid}">New Thread</a> ]
</td></tr>
<!-- if count({threads}) is not 0 -->
	<tr><td class="title">
		{LANG}list_subject{/LANG}
	</td><td class="title" align="center" width="1">
		{LANG}list_replies{/LANG}
	</td><td class="title" align="center" width="1">
		{LANG}list_views{/LANG}
	</td><td class="title" align="center">
		{LANG}list_lastpost{/LANG}
	</td></tr>
	<!-- repeat:threads:thread -->
	<tr><td class="cell1" onmouseover="this.style.backgroundColor='#DDDDDD'" onmouseout="this.style.backgroundColor='#FFFFFF'" onclick="if(linkBubble) location.href = this.getElementsByTagName('a')[0].href">
		{repeat:thread->link} - {repeat:thread->description}<br />
		{repeat:thread->pages}
	</td><td class="cell1" align="center">
		{repeat:thread->replies}
	</td><td class="cell1" align="center">
		{repeat:thread->views}
	</td><td class="cell1">
		LP INFO
	</td></tr>
	<!-- endrepeat -->
<!-- else -->
	<tr><td class="cell1">
		{LANG}no_threads{/LANG}
	</td></tr>
<!-- endif -->
<tr><td class="title" colspan="4">
	(BUTTONS)
</td></tr>
</table>