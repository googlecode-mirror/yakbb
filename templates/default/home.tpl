<?php if(hasBoards() || hasCats()){ ?>
	<?php while($cat = loadCat()){ ?>
		<table class="border" cellpadding="4" cellspacing="1">
		<?php if($cat["id"] != 0){ ?>
		<tr><td class="title" colspan="5">
			<?= catLink($cat); ?>
		</td></tr>
		<?php } ?>
		<tr><td class="title" width="15">
		</td><td class="title" width="583">
			<?= lang("forum_data"); ?>
		</td><td class="title" width="1" align="center">
			<?= lang("threads"); ?>
		</td><td class="title" width="1" align="center">
			<?= lang("posts"); ?>
		</td><td class="title" width="150">
			<?= lang("last_post"); ?>
		</td></tr>
		<?php while($board = loadBoard()){ ?>
		<?php if($board["parentid"] == $cat["id"]){ ?>
		<tr><td class="cell1" align="center">
			<?php if($board["new_posts"]){ ?>
				<img src="<?= templatePath() ?>images/on.gif" alt="*" title="<?= lang("new_posts"); ?>" />
			<?php } else { ?>
				<img src="<?= templatePath() ?>images/off.gif" alt="-" title="<?= lang("no_new_posts"); ?>" />
			<?php } ?>
		</td><td class="cell1" onmouseover="this.style.backgroundColor='#DDDDDD'" onmouseout="this.style.backgroundColor=''" onclick="if(linkBubble) location.href = this.getElementsByTagName('a')[0].href">
			<?= boardLink($board); ?><br />
			<?= $board["description"]; ?><br />
			<?= subBoardListing($board, ", "); ?>
		</td><td class="cell1" align="center">
			<?= $board["threads"]; ?>
		</td><td class="cell1" align="center">
			<?= $board["posts"]; ?>
		</td><td class="cell1">
			LAST POST INFO
		</td></tr>
		<?php } ?>
		<?php } ?>
		<?php loadBoardReset(); ?>
		</table>
		<?php if(!singleCatView()){ ?>
		<br /><br />
		<?php } ?>
	<?php } ?>
<?php } else { ?>
	<table class="border" cellpadding="4" cellspacing="1">
	<tr><td class="title">
		<?php lang("nocats_title"); ?>
	</td></tr>
	<tr><td class="cell1">
		<?= lang("nocats_message"); ?>
	</td></tr>
	</table>
<?php } ?>



<?php if(!singleCatView()){ ?>
<!-- include: infocenter.tpl -->
<?php } ?>