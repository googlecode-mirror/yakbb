<table class="border" cellpadding="4" cellspacing="1">
	<tr><td class="cell1">
		{LANG}error_title{/LANG}
	</td></tr><tr><td class="cell1">
		<!-- if count({NUM_ERRORS}) is 1 -->
			{LANG}error_occurred{/LANG}
		<!-- else -->
			{LANG}errors_occurred{/LANG}
		<!-- endif --><br />
		<ul>
			{ERRORS}
		</ul>
	</td></tr>
</table>
