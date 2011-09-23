<?php
/**
 * Last updated $Date: 2007-03-09 13:02:59 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 326 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/index.php $
 **/
$title = 'Site Down';
$_SERVER['SCRIPT_URI'] = '/down/';
include('../includes/base.php');
include(DOCROOT.'/skin/header.php');

?>
<h2>Site down for maintainence.</h2>
<p class="homehead">Please come back later.</p>
<?php
include(DOCROOT.'/skin/loginfooter.php');
