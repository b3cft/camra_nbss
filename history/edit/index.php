<?php
/**
 * Last updated $Date: 2007-03-09 13:02:59 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 326 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/admin/beers/index.php $
 **/
$title='Edit Review';
$access = array('reviewer', 'superreviewer', 'admin', 'sysadmin');
include('../../includes/base.php');
include(DOCROOT.'/skin/header.php');
?>
<h2>Edit Review</h2>
<?php
include(DOCROOT.'/skin/footer.php');
?>