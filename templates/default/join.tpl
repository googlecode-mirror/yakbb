<?php if(countErrors() > 0){ ?>
<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	<?= lang("error_title"); ?>
</td></tr>
<tr><td class="cell1">
	<?= lang("registration_error"); ?>
	<ul>
	<?php while($err = loadError()){ ?>
		<li><?= lang($err) ?></li>
	<?php } ?>
	</ul>
</td></tr>
</table>
<br /><br />
<?php } ?>


<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	<?= lang("register_title"); ?>
</td></tr><tr><td class="cell1">
	<form name="registerForm" method="post" action="?action=register">
		<fieldset>
			<legend><?= lang("userdisplay_title"); ?></legend>
			<span class="set1">
				<?= lang("username_brief"); ?> <input type="text" name="username" maxlength="<?= getSetting("username_max_length"); ?>" value="<?= loadSentUsername() ?>" /><br />
				<span class="smalltext">
					<?= lang("username_description"); ?>
				</span>
			</span>
			<span class="set2">
				<?= lang("displayname_brief"); ?> <input type="text" name="displayname" maxlength="<?= getSetting("displayname_max_length"); ?>" value="<?= loadSentDisplayname() ?>" /><br />
				<span class="smalltext">
					<?= lang("displayname_description"); ?>
				</span>
			</span>
			<br class="clear" />
		</fieldset>
		<fieldset>
			<legend><?= lang("password_title"); ?></legend>
			<span class="set1">
				<?= lang("password1_brief"); ?> <input type="password" name="password1" /><br />
				<span class="smalltext">
					<?= lang("password1_description"); ?>
				</span>
			</span>
			<span class="set2">
				<?= lang("password2_brief"); ?> <input type="password" name="password2" /><br />
				<span class="smalltext">
					<?= lang("password2_description"); ?>
				</span>
			</span>
			<br class="clear" />
		</fieldset>
		<fieldset>
			<legend><?= lang("email_title"); ?></legend>
			<span class="set1">
				<?= lang("email1_brief"); ?> <input type="text" name="email1" value="<?= loadSentEmail1() ?>" /><br />
				<span class="smalltext">
					<?= lang("email1_description"); ?>
				</span>
			</span>
			<span class="set2">
				<?= lang("email2_brief"); ?> <input type="text" name="email2" value="<?= loadSentEmail2() ?>" /><br />
				<span class="smalltext">
					<?= lang("email2_description"); ?>
				</span>
			</span>
			<br class="clear" />
			<br class="clear" />
			<span class="set1">
				<?= lang("showemail_brief"); ?> 
					<input type="radio" name="showemail" value="1"<?php if(showEmailChecked()){ ?> checked="checked"<?php } ?> /><?= lang("yes_option"); ?> 
					<input type="radio" name="showemail" value="0"<?php if(!showEmailChecked()){ ?> checked="checked"<?php } ?> /><?= lang("no_option"); ?>
				<br />
				<span class="smalltext">
					<?= lang("showemail_description"); ?>
				</span>
			</span>
			<span class="set2">
				<?= lang("emailoptin_brief"); ?> 
					<input type="radio" name="emailoptin" value="1"<?php if(emailOptinChecked()){ ?> checked="checked"<?php } ?> /><?= lang("yes_option"); ?> 
					<input type="radio" name="emailoptin" value="0"<?php if(!emailOptinChecked()){ ?> checked="checked"<?php } ?> /><?= lang("no_option"); ?>
				<br />
				<span class="smalltext">
					<?= lang("emailoptin_description"); ?>
				</span>
			</span>
			<br class="clear" />
		</fieldset><br />
		<input type="submit" name="submitit" value="<?= lang("register_button"); ?>" /> 
		<input type="reset" name="resetit" value="<?= lang("reset_button"); ?>" onclick="return confirm('<?= lang("reset_confirmation"); ?>');" />
	</form>
</td></tr>
</table>