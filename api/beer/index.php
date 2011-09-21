<?php
/**
 * Last updated $Date: 2007-03-13 08:51:52 +0000 (Tue, 13 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 341 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/api/beer/index.php $
 **/
include('../../includes/base.php');

header('Content-type: text/plain');
$query = $_GET['query'];
if (stripos($query, ','))
{
	$query = explode(',', $query);
	$results = getQueryResults('SELECT brewery.name as brewery, beer.name as beer, beer.id FROM '.$config->get('database', 'tablePrefix').'beer beer, '.$config->get('database', 'tablePrefix').'brewery brewery WHERE beer.brewery_id=brewery.id AND beer.name LIKE \''.trim($query[1]).'%\' AND brewery.name LIKE \''.trim($query[0]).'%\' ORDER BY brewery.name, beer.name');
}
else
{
	$results = getQueryResults('SELECT brewery.name as brewery, beer.name as beer, beer.id FROM '.$config->get('database', 'tablePrefix').'beer beer, '.$config->get('database', 'tablePrefix').'brewery brewery WHERE beer.brewery_id=brewery.id AND (beer.name LIKE \''.trim($query).'%\' OR brewery.name LIKE \''.trim($query).'%\') ORDER BY brewery.name, beer.name');
}
sendResults($query,$results);

function sendResults($query,$results) {
	foreach($results as $result)
	{
		print "{$result['brewery']}, {$result['beer']}\t{$result['id']}\n";
	}
}

?>