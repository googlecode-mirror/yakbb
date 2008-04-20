<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?= getSetting("board_title"); ?> - {title}</title>
	<style type="text/CSS">
	div#holder div {
		text-align: left;
	}
	table.border {
		background-color: #000000;
		color: #FFFFFF;
		width: 750px;
	}
	table.php, table.quote {
		width: 550px;
	}
	td.cell1, tr.cell1 td, td.title, tr.title td {
		background-color: #FFFFFF;
		color: #000000;
	}
	span.smalltext {
		font-size: 8pt;
	}
	a:visited {
		color: #0000FF;
	}

	/* Form Details */
	span.set1 {
		float: left;
	}
	span.set2 {
		float: right;
	}
	span.set1, span.set2{
		width: 350px;
	}
	br.clear {
		clear: both;
	}
	</style>
	<script type="text/Javascript">
	var linkBubble = true;
	</script>
	<base href="<?= baseUrl() ?>" />
</head>
<body>
<div align="center" id="holder">
<div style="width: 750px">
<table cellpadding="4" cellspacing="1" class="border">
<tr><td class="cell1" style="text-align: center">
	<?= getSetting("board_title"); ?>
</td></tr><tr><td class="cell1">
	<font size="2">
		<a href="<?= seoSwitch("home/", "?action=home") ?>"><?= lang("menu_home"); ?></a> - 
		<a href="<?= seoSwitch("help/", "?action=help") ?>"><?= lang("menu_help"); ?></a> - 
		<a href="<?= seoSwitch("search/", "?action=search") ?>"><?= lang("menu_search"); ?></a> - 
		<?php if(isGuest()){ ?>
			<a href="<?= seoSwitch("login/", "?action=login") ?>"><?= lang("menu_login"); ?></a>
			<?php if(getSetting("registration_enabled")){ ?>
				 - <a href="<?= seoSwitch("register/", "?action=register") ?>"><?= lang("menu_register"); ?></a>
			<?php } ?>
		<?php } else { ?>
			<a href="<?= seoSwitch("usercp/", "?action=usercp") ?>"><?= lang("menu_usercp"); ?></a> - 
			<a href="<?= seoSwitch("members/", "?action=members") ?>"><?= lang("menu_members"); ?></a> - 
			<a href="<?= seoSwitch("logout/", "?action=logout") ?>"><?= lang("menu_logout"); ?></a>
		<?php } ?>
		
	</font>
</td></tr>
</table>
<br /><br />

<?php if(upgradeAvailable() && !viewingPage() == "upgrade"){ ?>
	<table class="border" cellpadding="4" cellspacing="1">
	<tr><td class="cell1">
		There are currently updates available. Click <a href="<?= seoSwitch("upgrade/", "?action=upgrade") ?>">here</a> to upgrade your forum.
	</td></tr>
	</table>
	<br /><br />
<?php } ?>
<div id="nav" style="text-align: left"><?= compileNavTree(" :: "); ?></div>