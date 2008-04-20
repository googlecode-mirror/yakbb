<?php if(registrationRedirect()){ ?>
<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	<?= lang("regsuccess_title"); ?>
</td></tr><tr><td class="cell1">
	<?= lang("regsuccess_message"); ?>
</td></tr>
</table><br /><br />
<?php } ?>

<?php if(countErrors() > 0){ ?>
<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	<?= lang("error_title"); ?>
</td></tr><tr><td class="cell1">
	<?php if(countErrors() == 1){ ?>
		<?= lang("error_occured"); ?>
	<?php } else { ?>
		<?= lang("errors_occured"); ?>
	<?php } ?>
	<ul>
		<?php while($err = loadError()){ ?>
		<li><?= lang($err) ?></li>
		<?php } ?>
	</ul>
</td></tr>
</table><br /><br />
<?php } ?>

<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	<?= lang("login_title"); ?>
</td></tr><tr><td class="cell1">
	<form name="loginForm" method="post" action="?action=login">
		<fieldset>
			<legend><?= lang("accountdetails_title"); ?></legend>
			<span class="set1">
				<?= lang("username_brief"); ?> <input type="text" name="username" maxlength="<?= getSetting("username_max_length"); ?>" value="<?= loadSentUsername() ?>" /><br />
				<span class="smalltext">
					<?= lang("username_description"); ?>
				</span>
			</span>
			<span class="set2">
				<?= lang("password_brief"); ?> <input type="password" name="password" /><br />
				<span class="smalltext">
					<?= lang("password_description"); ?>
				</span>
			</span>
			<br class="clear" />
		</fieldset>
		<br />
		<input type="submit" name="submitit" value="<?= lang("login_button"); ?>" /> 
		<input type="reset" name="resetit" value="<?= lang("reset_button"); ?>" onclick="return confirm('<?= lang("reset_confirmation"); ?>');" />
	</form>
</td></tr>
</table>