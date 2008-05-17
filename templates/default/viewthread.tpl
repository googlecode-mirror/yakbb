<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	[ 
	<?php if(canReply()){ ?>
		<a href="<?= replyLink(threadId(), true) ?>"><?= lang("reply_to_thread") ?></a> | 
	<?php } ?>
	<?= lang("print_thread") ?>
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
			<a href="<?= replyLink(threadId(), true) ?>"><?= lang("reply_to_thread") ?></a> - 
			<a href="<?= replyLink(threadId(), true, $post["postid"]) ?>"><?= lang("quote_thread") ?></a>
		<?php } ?>
		<?php if(canModify($post["postid"])){ ?> - <?= lang("modify_post") ?><?php } ?>
		<?php if(canDelete($post["postid"])){ ?> - <?= lang("delete_post") ?><?php } ?>
	<hr size="1" />
	<?= $post["message"] ?>
</td></tr>
</table><br /><br />
<?php } ?>


<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title">
	[ 
	<?php if(canReply()){ ?>
		<a href="<?= replyLink(threadId(), true) ?>"><?= lang("reply_to_thread") ?></a> | 
	<?php } ?>
	<?= lang("print_thread") ?>
	]
</td></tr>
</table>