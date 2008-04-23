<?php if(countErrors() > 0){ ?>
<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="cell1">
	<?= lang("error_title"); ?>
</td></tr><tr><td class="cell1">
	<?php if(countErrors() == 1){ ?>
		<?= lang("error_occured"); ?>
	<?php } else {?>
		<?= lang("errors_occured"); ?>
	<?php } ?>
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
	<?= lang("post_title_".getMode()); ?>
</td></tr><tr><td class="cell1">
	<form action="<?= getFormAction() ?>" method="post" name="postForm">
		<?= lang("text_title"); ?> <input type="text" value="<?= getSentTitle() ?>" name="posttitle" maxlength="<?= getSetting("thread_subject_max"); ?>" size="30" /><br />
		<?php if(showDescription()){ ?>
			<?= lang("text_desc"); ?> <input type="text" value="<?= getSentDescription() ?>" name="postdesc" maxlength="<?= getSetting("thread_desc_max"); ?>" size="30" /><br />
		<?php } ?>
		<?= lang("text_message"); ?><br />
		<textarea name="postmessage" rows="10" cols="60"><?= getSentMessage() ?></textarea>
		<br /><br /><input type="submit" value="<?= lang("submit_button_".getMode()); ?>" name="submitit" accesskey="s" />
	</form>
</td></tr>
</table>