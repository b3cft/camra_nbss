<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php echo $config->get('web', 'branchName')?> Camra NBSS : <?php echo $title;?></title>
	<meta name="description" content="<?php echo $config->get('web', 'branchName')?> Campaign for Real Ale" />
	<meta name="keywords" content="beer, beer scoring, NBSS, camra, <?php echo $config->get('web', 'branchName')?> camra, campaign for real ale" />
	<meta name="author" content="Andy Brockhurst" />
	<meta name="generator" content="handcoded PHP loveliness" />
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="MSSmartTagsPreventParsing" content="TRUE" />
	<link rel="Shortcut Icon" type="image/x-icon" href="<?php echo $config->get('web', 'root')?>/skin/images/favicon.ico" />
	<?php
	printCSSInclude('style.css');
	printCSSInclude('yui/calendar/assets/calendar.css');
	?>
</head>

<body>
<script type="text/javascript">document.getElementsByTagName("body")[0].className+=" javascript";</script>
<div id="body">
	<div id="header">
		<h1 title="<?php echo $config->get('web', 'branchName')?> Campaign for Real Ale, National Beer Scoring System"><?php echo $config->get('web', 'branchName')?> Campaign for Real Ale, National Beer Scoring System</h1>
<?php
	if(!is_null($user))
	{
		print('<div class="userinfo">Not '.$user['firstname'].' '.$user['lastname'].'? <a href="'.$config->get('web','root').'/logout/">Logout</a></div>');
	}
	if (!$config->get('web', 'live'))
	{
		print('<h2 class="red bold">This is a TEST SITE do not submit live scores here.</h2>');
	}

?>
	</div>
	<div id="content">