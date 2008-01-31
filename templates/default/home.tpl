<!-- if count({BOARDS}) > 0 || count({CATS}) > 0 -->
	<!-- repeat:CATS:CAT -->
		<table class="border" cellpadding="4" cellspacing="1">
		<!-- if {repeat:CAT->id} is not 0 -->
		<tr><td class="title" colspan="5">
			{repeat:CAT->link}
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
		<!-- repeat:BOARDS:BOARD -->
		<!-- if {repeat:BOARD->parentid} == {repeat:CAT->id} -->
		<tr><td class="cell1" align="center">
			<!-- if {repeat:BOARD->new_posts} -->
			<img src="{TPATH}images/on.gif" alt="*" title="{LANG}new_posts{/LANG}" />
			<!-- else -->
			<img src="{TPATH}images/off.gif" alt="-" title="{LANG}no_new_posts{/LANG}" />
			<!-- endif -->
		</td><td class="cell1">
			{repeat:BOARD->link}<br />
			{repeat:BOARD->description}
		</td><td class="cell1" align="center">
			{repeat:BOARD->threads}
		</td><td class="cell1" align="center">
			{repeat:BOARD->posts}
		</td><td class="cell1">
			LAST POST INFO
		</td></tr>
		<!-- endif -->
		<!-- endrepeat -->
		</table><br /><br />
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



<!-- include: infocenter.tpl -->