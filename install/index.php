<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>NBSS Installer</title>
	<meta name="description" content="Campaign for Real Ale National Beer Scoring System" />
	<meta name="keywords" content="beer, beer scoring, NBSS" />
	<meta name="author" content="Andy Brockhurst" />
	<meta name="generator" content="handcoded PHP loveliness" />
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="MSSmartTagsPreventParsing" content="TRUE" />
	<link rel="Shortcut Icon" type="../skin/images/favicon.ico" />
<style type="text/css">
html *{margin:0;padding:0;}
body{
    text-align: center;
    background: #fff;
    font-family: Arial, Verdana, sans-serif;
    font-size: 0.8em;
}
table, tr, td, input, select, textarea{
	font-family: inherit;
	font-size: inherit;
}
.red{color: #c00;}
.orange{color: #f55;}
.green{color: #5a1;}
.blue{color: #00f;}
.caps{text-transform: uppercase;}
.cleft{clear:left;}
.cright{clear:right;}
.clear{clear:both;}
.fleft{float:left;display:inline;}
.fright{float:right;display:inline;}
.bold{font-weight: bold;}
.unbold{font-weight: normal;}
.italic{font-style: italic;}
abbr,span.abbr,acronym {
	cursor: help;
	border-bottom: 1px dotted #639;
}
br.clear{line-height: 0;}
body *{text-align: left;}
h1,h2,h3,h4,h5,h6{
    font-family: "Century Gothic", "Trebuchet MS", "Arial Narrow", Arial, sans-serif;
    text-transform: uppercase;
    font-weight: normal;
}
h3,h4,h5{
 margin-top: 1em;
}
pre{
	font-size: 120%;
}
ol{
	margin-left: 1.3em;
}
div#body{
	width: 760px;
	margin: 0 auto;
}
div#header{
	margin: 0 0 10px 0;
	float: left;
	display: inline;
}
div#header h1{
	float: right;
	text-indent: -999em;
	width: 222px;
	height: 121px;
	background: no-repeat url(../skin/images/nbsslogo.gif);
}
</style>
</head>
<?php
include('../VERSION');
?>
<body>
	<div id="body">
		<div id="header">
			<h1 title="Campaign for Real Ale, National Beer Scoring System">Campaign for Real Ale, National Beer Scoring System</h1>
		</div>
		<h2>NBSS Install Checker</h2>
		<h3>Checking pre-requisites</h3>
		<ol>
<?php
		$extensions = get_loaded_extensions();
		if (PHP_VERSION>=5) {print ('<li class="green">Pass: PHP5+</li>');}
		else {print ('<li class="red">Fail: PHP5+ is required</li>');}

		if (in_array('PDO', $extensions)){print ('<li class="green">Pass: PDO support enabled</li>');}
		else{print ('<li class="red">Fail: PHP\'s PDO is required</li>');}

		if (in_array('pdo_mysql', $extensions)){print ('<li class="green">Pass: PDO mysql driver enabled</li>');}
		else{print ('<li class="red">Fail: PHP\'s PDO_mysql driver is required</li>');}

		if (in_array('pdo_sqlite', $extensions)){print ('<li class="green">Pass: PDO sqlite driver enabled</li>');}
		else{print ('<li class="orange">Warning: PHP\'s PDO_sqlite driver not enabled</li>');}

		/**
		 * @todo
		 * GD Check
		 * Mail Check
		 * Load config and check write accesses
		 */
?>
		</ol>
		<h3>Show readme:</h3>
<pre>
<?php include('../README');?>
</pre>
	</div><!--#body-->
</body>
</html>