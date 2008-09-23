{include file="header.tpl"}

<form name="registerForm" action="?action=register" method="post">
<table width="100%" class="border" cellspacing="1">
<tr><td class="title">
	Register
</td></tr><tr><td>
	<b>Required Information</b><br />
	<table cellpadding="0" cellspacing="0">
	<tr><td>
		<b>Username:</b>
	</td><td>
		<input type="text" name="username" />
	</td><td>
		<b>Display Name:</b>
	</td><td>
		<input type="text" name="display" />
	</td></tr><tr><td colspan="2">
		<font size="1">This will be the name you use to login.</font>
	</td><td colspan="2">
		<font size="1">This name will be shown to other users. It can be changed once you login.</font>
	</td></tr><tr><td>
		<b>Password:</b>
	</td><td>
		<input type="password" name="pass1" />
	</td><td>
		<b>Confirm Password:</b>
	</td><td>
		<input type="password" name="pass2" />
	</td></tr><tr><td colspan="2">
		Please enter the password for your account.
	</td><td colspan="2">
		Please confirm your password by reentering it.
	</td></tr><tr><td>
		<b>E-mail:</b>
	</td><td>
		<input type="text" name="email1" />
	</td><td>
		<b>Confirm E-mail:</b>
	</td><td>
		<input type="text" name="email2" />
	</td></tr><tr><td colspan="2">
		Please enter the e-mail to be associated with your account. We will send all mail to this account.
	</td><td colspan="2">
		Please confirm your e-mail by reentering it.
	</td></tr>
	</table>
</td></tr><tr><td>
	<b>Settings</b><br />
	<table cellpadding="0" cellspacing="0">
	<tr><td>
		<b>Hide E-mail:</b>
	</td><td>
		Yes <input type="radio" name="hideemail" value="yes" checked="checked" /> - No <input type="radio" name="hideemail" value="no" />
	</td><td>
		<b>Receive E-mails:</b>
	</td><td>
		Yes <input type="radio" name="receiveemail" value="yes" checked="checked" /> - No <input type="radio" name="receiveemail" value="no" />
	</td></tr><tr><td colspan="2">
		Do you want your e-mail to be hidden from other members?
	</td><td colspan="2">
		Do you want to receive e-mails from the forum administrators?
	</td></tr>
	</table>
</td></tr><tr><td>
	By checking the following box, I agree to the Terms of Service and the Privacy Policy of this forum. <input type="checkbox" name="tos" /><br /><br />
	<input type="submit" value="Register" name="submit2" /> <input type="reset" value="Reset Form" name="res" onclick="return confirm('Are you sure you want to reset the form?');" />
</td></tr>
</table>
</form>

{include file="footer.tpl"}