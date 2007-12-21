<!-- if count({BOARDS}) > 0 || count({CATS}) > 0 -->
	<table class="border" cellpadding="4" cellspacing="1">
	<tr><td class="title" width="1">
	</td><td class="title" width="597">
		{LANG}forum_data{/LANG}
	</td><td class="title" width="1">
		{LANG}posts{/LANG}
	</td><td class="title" width="1">
		{LANG}threads{/LANG}
	</td><td class="title" width="150">
		{LANG}last_post{/LANG}
	</td></tr>
	<!-- repeat:CATS:CAT -->
		<!-- if {repeat:CAT->id} is not 0 -->
			<tr><td class="title" colspan="5">
				{repeat:CAT->name}
			</td></tr>
		<!-- endif -->
		<!-- repeat:BOARDS:BOARD -->
			<!-- if {repeat:BOARD->parentid} == {repeat:CAT->id} -->
				<tr><td class="cell1">
					<!-- if {repeat:BOARD->new_posts} -->
						<img src="{TPATH}/images/on.gif" alt="*" />
					<!-- else -->
						<img src="{TPATH}/images/off.gif" alt="" />
					<!-- endif -->
				</td><td class="cell1">
					{repeat:BOARD->link}<br />
					{repeat:BOARD->description}
				</td><td class="cell1">
					{repeat:BOARD->posts}
				</td><td class="cell1">
					{repeat:BOARD->threads}
				</td><td class="cell1">
					LAST POST INFO
				</td></tr>
			<!-- endif -->
		<!-- endrepeat -->
	<!-- endrepeat -->
	</table>
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