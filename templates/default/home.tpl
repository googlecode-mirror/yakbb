<!-- if count({boards}) > 0 || count({cats}) > 0 -->
	<!-- repeat:cats:cat -->
		<table class="border" cellpadding="4" cellspacing="1">
		<!-- if {repeat:cat->id} is not 0 -->
		<tr><td class="title" colspan="5">
			{repeat:cat->link}
		</td></tr>
		<!-- endif -->
		<tr><td class="title" width="15">
		</td><td class="title" width="583">
			{LANG}forum_data{/LANG}
		</td><td class="title" width="1" align="center">
			{LANG}threads{/LANG}
		</td><td class="title" width="1" align="center">
			{LANG}posts{/LANG}
		</td><td class="title" width="150">
			{LANG}last_post{/LANG}
		</td></tr>
		<!-- repeat:boards:board -->
		<!-- if {repeat:board->parentid} == {repeat:cat->id} -->
		<tr><td class="cell1" align="center">
			<!-- if {repeat:board->new_posts} -->
			<img src="{TPATH}images/on.gif" alt="*" title="{LANG}new_posts{/LANG}" />
			<!-- else -->
			<img src="{TPATH}images/off.gif" alt="-" title="{LANG}no_new_posts{/LANG}" />
			<!-- endif -->
		</td><td class="cell1" onmouseover="this.style.backgroundColor='#DDDDDD'" onmouseout="this.style.backgroundColor='#FFFFFF'" onclick="if(linkBubble) location.href = this.getElementsByTagName('a')[0].href">
			{repeat:board->link}<br />
			{repeat:board->description}
		</td><td class="cell1" align="center">
			{repeat:board->threads}
		</td><td class="cell1" align="center">
			{repeat:board->posts}
		</td><td class="cell1">
			LAST POST INFO
		</td></tr>
		<!-- endif -->
		<!-- endrepeat -->
		</table>
		<!-- if !{single} -->
		<br /><br />
		<!-- endif -->
	<!-- endrepeat -->
<!-- else -->
	<table class="border" cellpadding="4" cellspacing="1">
	<tr><td class="title">
		{LANG}nocats_title{/LANG}
	</td></tr>
	<tr><td class="cell1">
		{LANG}nocats_message{/LANG}
	</td></tr>
	</table>
<!-- endif -->



<!-- if !{single} -->
<!-- include: infocenter.tpl -->
<!-- endif -->