{include file="header.tpl"}

<form name="loginForm" action="?action=login" method="post">
<table width="100%" class="border" cellspacing="1">
<tr><td class="title">
	Login
</td></tr><tr><td>
	<table cellpadding="0" cellspacing="0">
	<tr><td>
		<b>Username:</b>
	</td><td>
		<input type="text" name="username" />
	</td><td>
		<b>Password:</b>
	</td><td>
		<input type="password" name="password" />
	</td></tr><tr><td colspan="2">
		<b>Login as Invisible:</b> <input type="checkbox" name="invisible" />
	</td><td colspan="2">
		<b>Login For:</b>
		<select name="loginfor">
			<option value="0">Browser Session</option>
			<option value="15">15 Minutes</option>
			<option value="30">30 Minutes</option>
			<option value="60" selected="selected">1 Hour</option>
			<option value="120">2 Hours</option>
			<option value="360">6 Hours</option>
			<option value="1440">1 Day</option>
			<option value="-1">Forever</option>
		</select>
	</td></tr>
	</table>
	<input type="submit" value="Login" name="submit2" />
</td></tr>
</table>
</form>

{include file="footer.tpl"}