<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	<?= lang("upgrade_title"); ?>
</td></tr><tr><td class="cell1">
	<!-- if {page1} -->
		<?= lang("db_upgrade"); ?>
		<br /><br />
	<!-- endif -->
	<!-- if {page2} -->
		<?= lang("core_upgrade"); ?>
		<br /><br />
	<!-- endif -->
	<!-- if {page3} -->
		<?= lang("no_upgrade"); ?>
		<br /><br />
	<!-- endif -->
	<a href="javascript:history.go(-1)">Back to previous page</a>
</td></tr>
</table>