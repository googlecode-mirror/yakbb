<table class="border" cellpadding="4" cellspacing="1">
	<tr><td class="cell1">
		<?= lang("error_title"); ?>
	</td></tr><tr><td class="cell1">
		<?php if(errorCount() == 1){ ?>
			<?= lang("error_occurred"); ?>
		<?php } else { ?>
			<?= lang("errors_occurred"); ?>
		<?php } ?><br />
		<ul>
			<?php while($error = loadError()){ ?>
			<li><?= lang($error) ?></li>
			<?php } ?>
		</ul>
	</td></tr>
</table>
