<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title" colspan="4">
	<!-- if {perm_newthread} -->
	[ <a href="?action=newthread&amp;bid=<?= boardId() ?>"><?= lang("new_thread") ?></a> ]
	<!-- endif -->
</td></tr>
<?php if(threadCount() > 0){ ?>
	<tr><td class="title">
		<?= lang("list_subject"); ?>
	</td><td class="title" align="center" width="1">
		<?= lang("list_replies"); ?>
	</td><td class="title" align="center" width="1">
		<?= lang("list_views"); ?>
	</td><td class="title" align="center">
		<?= lang("list_lastpost"); ?>
	</td></tr>
	<?php while($thread = loadThread()){ ?>
	<tr><td class="cell1" onmouseover="this.style.backgroundColor='#DDDDDD'" onmouseout="this.style.backgroundColor='#FFFFFF'" onclick="if(linkBubble) location.href = this.getElementsByTagName('a')[0].href">
		<?= threadLink($thread) ?> - <?= $thread["description"] ?><br />
		<?= $thread["pages"] ?>
	</td><td class="cell1" align="center">
		<?= $thread["replies"] ?>
	</td><td class="cell1" align="center">
		<?= $thread["views"] ?>
	</td><td class="cell1">
		LP INFO
	</td></tr>
	<?php } ?>
<?php } else { ?>
	<tr><td class="cell1">
		<?= lang("no_threads"); ?>
	</td></tr>
<?php } ?>
<tr><td class="title" colspan="4">
	<!-- if {perm_newthread} -->
	[ <a href="?action=newthread&amp;bid=<?= boardId() ?>"><?= lang("new_thread") ?></a> ]
	<!-- endif -->
</td></tr>
</table>