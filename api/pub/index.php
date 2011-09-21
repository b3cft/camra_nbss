<?php
/**
 * Last updated $Date: 2007-03-13 08:51:52 +0000 (Tue, 13 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 341 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/api/pub/index.php $
 **/
define('NOSESSION', true);
include('../../includes/base.php');
$expiresOffset = 3600 * 24 * 10;
header('Content-type: text/plain');
header('Cache-Control: public');
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $expiresOffset) . " GMT");

$query = $_GET['query'];
if ( stripos($query, ',') )
{
	$query = explode(',', $query);
	$results = getQueryResults('SELECT town.name as town, pub.name as pub, pub.id FROM '.$config->get('database', 'tablePrefix').'pub pub, '.$config->get('database', 'tablePrefix').'town town WHERE pub.town_id=town.id AND pub.name LIKE \''.trim($query[1]).'%\' AND town.name LIKE \''.trim($query[0]).'%\' ORDER BY town.name, pub.name');
}
else
{
	$results = getQueryResults('SELECT town.name as town, pub.name as pub, pub.id FROM '.$config->get('database', 'tablePrefix').'pub pub, '.$config->get('database', 'tablePrefix').'town town WHERE pub.town_id=town.id AND (pub.name LIKE \''.$query.'%\' OR town.name LIKE \''.$query.'%\') ORDER BY town.name, pub.name');
}
sendResults($query,$results);

function sendResults($query,$results) {
	foreach($results as $result)
	{
		print "{$result['town']}, {$result['pub']}\t{$result['id']}\n";
	}
}
?>