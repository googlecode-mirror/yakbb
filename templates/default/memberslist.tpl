<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title" colspan="6">
	Members' List <!-- This will change depending on the page we're on -->
</td></tr><tr><td class="title">
	<?= lang("user_title") ?>
</td><td class="title">
	<?= lang("email_title") ?>
</td><td class="title">
	<?= lang("rank_title") ?>
</td><td class="title">
	<?= lang("posts_title") ?>
</td><td class="title">
	<?= lang("location_title") ?>
</td><td class="title">
	<?= lang("gender_title") ?>
</td></tr>
<?php while($mem = loadMember()){ ?>
<tr><td class="cell1">
	<?= userLink($mem) ?>
</td><td class="cell1">
	<?= botSecureEmail($mem["email"], $mem["email"]) ?>
</td><td class="cell1">
	COMING SOON
</td><td class="cell1">
	<?= $mem["posts"] ?>
</td><td class="cell1">
	<?= $mem["location"] ?>
</td><td class="cell1">
	<?= showGender($mem["gender"], 1) ?>
</td></tr>
<?php } ?>
</table>