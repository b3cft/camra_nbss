<?php
/**
 * Last updated $Date: 2007-02-28 16:45:46 +0000 (Wed, 28 Feb 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 297 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/logout/index.php $
 **/
$access = array('reviewer','superreviewer','admin','sysadmin');
include('../includes/base.php');
Session::destroy();
header('Location: '.$config->get('web','root').'/login/');
exit;
?>