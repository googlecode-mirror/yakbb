<!-- if {REG} -->
<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	{LANG}regsuccess_title{/LANG}
</td></tr><tr><td class="cell1">
	{LANG}regsuccess_message{/LANG}
</td></tr>
</table><br /><br />
<!-- endif -->

<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	{LANG}login_title{/LANG}
</td></tr><tr><td class="cell1">
	<form name="loginForm" method="post" action="?action=login">
		<fieldset>
			<legend>{LANG}accountdetails_title{/LANG}</legend>
			<span class="set1">
				{LANG}username_brief{/LANG} <input type="text" name="username" maxlength="{username_max_length}" value="{USER}" /><br />
				<span class="smalltext">
					{LANG}username_description{/LANG}
				</span>
			</span>
			<span class="set2">
				{LANG}password_brief{/LANG} <input type="password" name="password" /><br />
				<span class="smalltext">
					{LANG}password_description{/LANG}
				</span>
			</span>
			<br class="clear" />
		</fieldset>
		<br />
		<input type="submit" name="submitit" value="{LANG}login_button{/LANG}" /> 
		<input type="reset" name="resetit" value="{LANG}reset_button{/LANG}" onclick="return confirm('{LANG}reset_confirmation{/LANG}');" />
	</form>
</td></tr>
</table>