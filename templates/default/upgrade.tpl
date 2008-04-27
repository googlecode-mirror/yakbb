<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	<?= lang("upgrade_title"); ?>
</td></tr><tr><td class="cell1">
	<?php if(dbUpgrade()){ ?>
		<?= lang("db_upgrade"); ?>
		<br /><br />
	<?php } ?>
	<?php if(coreUpgrade()){ ?>
		<?= lang("core_upgrade"); ?>
		<br /><br />
	<?php } ?>
	<?php if(!coreUpgrade() || !dbUpgrade()){ ?>
		<?= lang("no_upgrade"); ?>
		<br /><br />
	<?php } ?>
	<a href="javascript:history.go(-1)">Back to previous page</a>
</td></tr>
</table>