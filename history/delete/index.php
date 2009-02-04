<?php
/**
 * Last updated $Date: 2007-03-09 13:02:59 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 326 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/admin/beers/index.php $
 **/
$title='Delete Review';
$access = array('reviewer', 'superreviewer', 'admin', 'sysadmin');
include('../../includes/base.php');
include(DOCROOT.'/skin/header.php');

if ( $request->get('post', 'id') )
{
 $submission_id = $request->get('post', 'id');
}
elseif ( $request->get('get', 'id') )
{
  $submission_id = $request->get('get', 'id');
}
else
{
  header('Location: '.$config->get('web','root').'/history/');
  exit;
}
$tablePrefix = $config->get('database', 'tablePrefix');

$sql = <<<EOQ
SELECT beer.name as beer, brewery.name as brewery, town.name as town, pub.name as pub, review.reviewed as date
FROM
${tablePrefix}review as review,
${tablePrefix}town as town,
${tablePrefix}pub as pub
RIGHT JOIN (${tablePrefix}beer as beer ON review.beer_id = beer.id
  INNER JOIN (${tablePrefix}brewery as brewery ON beer.brewery_id = brewery.id)
)
WHERE review.pub_id = pub.id
AND pub.town_id = town.id
AND review.id = ${submission_id};
EOQ;
print $sql;
$submission = getQueryResults($sql);
?>
<h2>Delete Review</h2>
<?php
print_r($submission);
include(DOCROOT.'/skin/footer.php');
?>