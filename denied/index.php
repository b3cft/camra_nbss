<?php
/**
 * Last updated $Date: 2007-03-09 13:02:59 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 326 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/denied/index.php $
 **/
$title = 'Access Denied';
$access = array('reviewer','superreviewer','admin','sysadmin');
include('../includes/base.php');
include(DOCROOT.'/skin/header.php');
?>
	<h2>Whoopsie daisy!</h2>
	<p>You appear to have stumbled on something you shouldn't have.</p>
	<p>Tsk, Tsk. Please contact you administrator if you think you should be able to access that.</p>
	<p>Otherwise we'll say no more about it!</p>
<?php
include(DOCROOT.'/skin/footer.php')
?>