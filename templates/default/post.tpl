<!-- if count({errors}) is not 0 -->
<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="cell1">
	<?= lang("error_title"); ?>
</td></tr><tr><td class="cell1">
	<!-- if count({errors}) is 1 -->
		<?= lang("error_occured"); ?>
	<!-- else -->
		<?= lang("errors_occured"); ?>
	<!-- endif -->
	<ul>
		<!-- repeat:errors:err -->
		<li>{repeat:err}</li>
		<!-- endrepeat -->
	</ul>
</td></tr>
</table>
<br /><br />
<!-- endif -->
<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	<?= lang("post_title2"); ?>
</td></tr><tr><td class="cell1">
	<form action="{form_action}" method="post" name="postForm">
		<?= lang("text_title"); ?> <input type="text" value="{posttitle}" name="posttitle" maxlength="{thread_subject_max}" size="30" /><br />
		<!-- if {mode} is "newthread" --><?= lang("text_desc"); ?> <input type="text" value="{postdesc}" name="postdesc" maxlength="{thread_desc_max}" size="30" /><br /> <!-- endif -->
		<?= lang("text_message"); ?><br />
		<textarea name="postmessage" rows="10" cols="60">{postmessage}</textarea>
		<br /><br /><input type="submit" value="<?= lang("submit_button"); ?>" name="submitit" accesskey="s" />
	</form>
</td></tr>
</table>