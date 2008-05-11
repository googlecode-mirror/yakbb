<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	[ 
	<?php if(canReply()){ ?>
		<a href="<?= replyLink(threadId(), true) ?>">Reply</a> | 
	<?php } ?>
	Print
	]
</td></tr>
</table><br /><br />

<?php while($post = loadPost()){ ?>
<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="cell1" width="20%" valign="top">
	<?= userLink($post) ?>
</td><td class="cell1" width="80%">
	<?= $post["title"] ?> - Posted on <?= $post["date"] ?> - 
		<?php if(canReply()){ ?>
			<a href="<?= replyLink(threadId(), true) ?>">Reply</a> - <a href="<?= replyLink(threadId(), true, $post["postid"]) ?>">Quote</a>
		<?php } ?>
		<?php if(canModify($post["postid"])){ ?> - Modify<?php } ?>
		<?php if(canDelete($post["postid"])){ ?> - Delete<?php } ?>
	<hr size="1" />
	<?= $post["message"] ?>
</td></tr>
</table><br /><br />
<?php } ?>


<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	[ 
	<?php if(canReply()){ ?>
		<a href="<?= replyLink(threadId(), true) ?>">Reply</a> | 
	<?php } ?>
	Print
	]
</td></tr>
</table>