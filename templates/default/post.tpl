<?php if(countErrors() > 0){ ?>
<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="cell1">
	<?= lang("error_title"); ?>
</td></tr><tr><td class="cell1">
	<?php if(countErrors() == 1){ ?>
		<?= lang("error_occurred"); ?>
	<?php } else {?>
		<?= lang("errors_occurred"); ?>
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


<form action="<?= getFormAction() ?>" method="post" name="postForm">
<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	<?= lang("post_title_".getMode()); ?>
</td></tr><tr><td class="cell1">
	<?= lang("text_title"); ?> <input type="text" value="<?= getSentTitle() ?>" name="posttitle" maxlength="<?= getSetting("thread_subject_max"); ?>" size="30" /><br />
	<?php if(showDescription()){ ?>
		<?= lang("text_desc"); ?> <input type="text" value="<?= getSentDescription() ?>" name="postdesc" maxlength="<?= getSetting("thread_desc_max"); ?>" size="30" /><br />
	<?php } ?>
	<?= lang("text_message"); ?><br />
	<textarea name="postmessage" rows="10" cols="60"><?= getSentMessage() ?></textarea>
</td></tr>
<?php if(showPollForm()){ ?>
	<tr><td class="cell1">
	<b>Create Poll</b><br />
	Question: <input type="text" name="pollquestion" value="<?= getSentQuestion() ?>" /><br />
	Choice 1: <input type="text" name="choice1" value="<?= getSentChoice(1) ?>" /> - 
	Choice 9: <input type="text" name="choice9" value="<?= getSentChoice(9) ?>" /><br />
	Choice 2: <input type="text" name="choice2" value="<?= getSentChoice(2) ?>" /> - 
	Choice 10: <input type="text" name="choice10" value="<?= getSentChoice(10) ?>" /><br />
	Choice 3: <input type="text" name="choice3" value="<?= getSentChoice(3) ?>" /> - 
	Choice 11: <input type="text" name="choice11" value="<?= getSentChoice(11) ?>" /><br />
	Choice 4: <input type="text" name="choice4" value="<?= getSentChoice(4) ?>" /> - 
	Choice 12: <input type="text" name="choice12" value="<?= getSentChoice(12) ?>" /><br />
	Choice 5: <input type="text" name="choice5" value="<?= getSentChoice(5) ?>" /> - 
	Choice 13: <input type="text" name="choice13" value="<?= getSentChoice(13) ?>" /><br />
	Choice 6: <input type="text" name="choice6" value="<?= getSentChoice(6) ?>" /> - 
	Choice 14: <input type="text" name="choice14" value="<?= getSentChoice(14) ?>" /><br />
	Choice 7: <input type="text" name="choice7" value="<?= getSentChoice(7) ?>" /> - 
	Choice 15: <input type="text" name="choice15" value="<?= getSentChoice(15) ?>" /><br />
	Choice 8: <input type="text" name="choice8" value="<?= getSentChoice(8) ?>" /> - 
	Choice 16: <input type="text" name="choice16" value="<?= getSentChoice(16) ?>" /><br /><br />
	<b>Poll Options</b><br />
	Retract Vote:
		<input type="radio" name="retract" value="yes" <?php if(getSentRetract()){ ?>checked="checked"<?php } ?> /> Yes
		<input type="radio" name="retract" value="no" <?php if(!getSentRetract()){ ?>checked="checked"<?php } ?> /> No<br />
	Can Choose: <input type="text" name="canchoose" value="<?= getSentCanChoose() ?>" maxlength="2" /> (How many options can be chosen, 16 maximum.)<br />
	Expires: <input type="text" name="expires" value="<?= getSentExpires() ?>" /> (Time in days. You may enter decimal numbers. 0 for infinite time)
	</td></tr>
<?php } ?>
<tr><td class="cell1">
	<input type="submit" value="<?= lang("submit_button_".getMode()); ?>" name="submitit" accesskey="s" />
</td></tr>
</table>
</form>