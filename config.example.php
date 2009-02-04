<?php
/**
 * Last updated $Date: 2007-03-09 20:52:41 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 335 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/config.example.php $
 *
 * @author Andrew Brockhurst, <andy.brockhurst@b3cft.com>
 */

$config = Config::getInstance();

/* e.g. '' if running in web root, '/scoring' if running at http://youdomain.com/scoring/  */
$config->set('web', 		'root',				'');

/* Name of branch for text displays, Logo etc. */
$config->set('web',			'branchName',		'North Hertfordshire TEST');

/* Set to true if a production/live site, false if a development/testing site */
$config->set('web',			'live',				false);

/* Custom footer text can be changed should you desire it */
$config->set('web',			'customFooter', 	'<a href="http://sourceforge.net/projects/camranbss/" title="Visit our SourceForge Project Page"><img src="http://sflogo.sourceforge.net/sflogo.php?group_id=189731&amp;type=1" width="88" height="31" border="0" alt="SourceForge.net Logo" /></a>');

/* email address to send feedback to, if blank, it disables feedback */
$config->set('web',			'feedbackEmail', 	'');

/* email address to send new signup details to */
$config->set('web',			'signupEmail', 		'');

/* If set to true, will automatically enable signups and allow them reviewer access */
$config->set('web',			'signupAutoEnable', false);

/* Allow signup of non CAMRA members */
$config->set('web',			'signupNonCamra', 	false);

/**
 * DOCROOT is calculated by the application
 * and will always be a web accessible path.
 *
 * Sessions are encrypted, but you may wish to move to a server specific location
 * the session files and log files, so that are not accessible by browsing.
 *
 * Logs could potentially include your DB username and password.
 **/

/* where session files will be stored, default should be okay*/
$config->set('path', 		'session',			DOCROOT.'/tmp/');

/* where error log will be written to */
$config->set('error',		'logfile', 			DOCROOT.'/tmp/error.log');

/* email address to mail errors to, blank or undefined disables */
$config->set('error',		'mailto', 			'');

/* toggle showing errors in the page */
$config->set('error',		'showInPage', 		true);

/* name of session cookie for persistence across browser sessions */
$config->set('session',		'cookieName',		'CAMRA_SESS');

/* length of persistent sessions */
$config->set('session',		'duration',			12*60*60); /* 12 hours, don't y' know */

/* salt added to session munging */
$config->set('session',		'salt',				'weLOVEb33rWEloveB33R');

/**
 * Setup Database connection
 * please see
 * http://php.net/manual/en/ref.pdo.php#pdo.drivers
 * on how to use alternative Database types
 **/
$config->set('database', 	'connectString', 	'mysql:host=localhost;dbname=camraNBSSdatabase');
$config->set('database', 	'username',			'nbssDBuser');
$config->set('database', 	'password',			'nbssDBpassword');
$config->set('database', 	'tablePrefix',		'nbss_');
?>