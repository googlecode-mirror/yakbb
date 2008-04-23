<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	[ 
	<!-- if {can_reply} is true -->
		<a href="?action=reply&amp;tid=<?= threadId() ?>">Reply</a> | 
	<!-- endif -->
	Print
	]
</td></tr>
</table><br /><br />

<?php while($post = loadPost()){ ?>
<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="cell1" width="20%">
	<?= userLink($post) ?>
</td><td class="cell1" width="80%">
	<?= $post["title"] ?> - Posted on <?= $post["date"] ?> - [ <a href="?action=reply&amp;tid=<?= threadId() ?>">Reply</a> | <a href="?action=reply&amp;tid=<?= threadId() ?>&amp;quote=<?= $post["id"] ?>">Quote</a> | Modify | Delete ]
	<hr size="1" />
	<?= $post["message"] ?>
</td></tr>
</table><br /><br />
<?php } ?>


<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	[ 
	<!-- if {can_reply} is true -->
		<a href="?action=reply&amp;tid={tid}">Reply</a> | 
	<!-- endif -->
	Print
	]
</td></tr>
</table>