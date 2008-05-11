<table class="border" cellpadding="4" cellspacing="1">
<tr><td class="title" colspan="6">
Members list
</td></tr><tr><td class="title">
	User
</td><td class="title">
	E-mail
</td><td class="title">
	Rank
</td><td class="title">
	Posts
</td><td class="title">
	Location
</td><td class="title">
	Gender
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