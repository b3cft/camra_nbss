<?php
/**
 * Last updated $Date: 2007-03-09 13:02:59 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 326 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/feedback/thankyou/index.php $
 **/
$title = 'Feedback : Thank you';
$access = array('reviewer','superreviewer','admin','sysadmin');
include('../../includes/base.php');
include(DOCROOT.'/skin/header.php');

?>
	<h2>Thank you</h2>
	<p>Your feedback has been sent.</p>
<?php
include(DOCROOT.'/skin/footer.php');
?>