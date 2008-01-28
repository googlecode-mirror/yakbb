<table class="border" cellpadding="4" cellspacing="1">
	<tr><td class="cell1">
		{LANG}error_title{/LANG}
	</td></tr><tr><td class="cell1">
		<!-- if count({errors}) is 1 -->
			{LANG}error_occured{/LANG}
		<!-- else -->
			{LANG}errors_occurred{/LANG}
		<!-- endif --><br />
		<ul>
			<!-- repeat:errors:err -->
			<li>{repeat:err}</li>
			<!-- endrepeat -->
		</ul>
	</td></tr>
</table>
