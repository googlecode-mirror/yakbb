<!-- if count({ERRORS}) is not 0 -->
<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	{LANG}error_title{/LANG}
</td></tr>
<tr><td class="cell1">
	{LANG}registration_error{/LANG}
	<ul>
	<!-- repeat:ERRORS:CURRENT_ERROR -->
		<li>{repeat:CURRENT_ERROR}
	<!-- endrepeat -->
	</ul>
</td></tr>
</table>
<br /><br />
<!-- endif -->


<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	{LANG}register_title{/LANG}
</td></tr><tr><td class="cell1">
	<form name="registerForm" method="post" action="?action=register">
		<fieldset>
			<legend>{LANG}userdisplay_title{/LANG}</legend>
			<span class="set1">
				{LANG}username_brief{/LANG} <input type="text" name="username" maxlength="{username_max_length}" value="{USER}" /><br />
				<span class="smalltext">
					{LANG}username_description{/LANG}
				</span>
			</span>
			<span class="set2">
				{LANG}displayname_brief{/LANG} <input type="text" name="displayname" maxlength="{displayname_max_length}" value="{DISPLAY}" /><br />
				<span class="smalltext">
					{LANG}displayname_description{/LANG}
				</span>
			</span>
			<br class="clear" />
		</fieldset>
		<fieldset>
			<legend>{LANG}password_title{/LANG}</legend>
			<span class="set1">
				{LANG}password1_brief{/LANG} <input type="password" name="password1" /><br />
				<span class="smalltext">
					{LANG}password1_description{/LANG}
				</span>
			</span>
			<span class="set2">
				{LANG}password2_brief{/LANG} <input type="password" name="password2" /><br />
				<span class="smalltext">
					{LANG}password2_description{/LANG}
				</span>
			</span>
			<br class="clear" />
		</fieldset>
		<fieldset>
			<legend>{LANG}email_title{/LANG}</legend>
			<span class="set1">
				{LANG}email1_brief{/LANG} <input type="text" name="email1" value="{EMAIL}" /><br />
				<span class="smalltext">
					{LANG}email1_description{/LANG}
				</span>
			</span>
			<span class="set2">
				{LANG}email2_brief{/LANG} <input type="text" name="email2" value="{CEMAIL}" /><br />
				<span class="smalltext">
					{LANG}email2_description{/LANG}
				</span>
			</span>
			<br class="clear" />
			<br class="clear" />
			<span class="set1">
				{LANG}showemail_brief{/LANG} 
					<input type="radio" name="showemail" value="1"<!-- if {SHOWEMAIL}  --> checked="checked"<!-- endif --> />{LANG}yes_option{/LANG} 
					<input type="radio" name="showemail" value="0"<!-- if !{SHOWEMAIL} --> checked="checked"<!-- endif --> />{LANG}no_option{/LANG}
				<br />
				<span class="smalltext">
					{LANG}showemail_description{/LANG}
				</span>
			</span>
			<span class="set2">
				{LANG}emailoptin_brief{/LANG} 
					<input type="radio" name="emailoptin" value="1"<!-- if {EMAILOPTIN} --> checked="checked"<!-- endif --> />{LANG}yes_option{/LANG} 
					<input type="radio" name="emailoptin" value="0"<!-- if !{EMAILOPTIN} --> checked="checked"<!-- endif --> />{LANG}no_option{/LANG}
				<br />
				<span class="smalltext">
					{LANG}emailoptin_description{/LANG}
				</span>
			</span>
			<br class="clear" />
		</fieldset><br />
		<input type="submit" name="submitit" value="{LANG}register_button{/LANG}" /> 
		<input type="reset" name="resetit" value="{LANG}reset_button{/LANG}" onclick="return confirm('{LANG}reset_confirmation{/LANG}');" />
	</form>
</td></tr>
</table>