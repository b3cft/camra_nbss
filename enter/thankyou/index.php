<?php
/**
 * Last updated $Date: 2007-03-09 13:02:59 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 326 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/enter/thankyou/index.php $
 **/
$title = 'Scoring : Thank you';
$access = array('reviewer','superreviewer','admin','sysadmin');
include('../../includes/base.php');
include(DOCROOT.'/skin/header.php');

$submission = Session::get('submission');
$date = date('Y-m-d', strtotime($submission['date']));
$created = gmdate('YmdHis', time());
for( $i=0 ; $i<$submission['pubCount'] ; $i++)
{
	for ( $j=0 ; $j<$submission['drinkCount-'.$i] ; $j++)
	{
		if ( strlen($submission['beername-'.$i.'-'.$j]) )
		{
			$prefix=$config->get('database', 'tablePrefix');
$sql = <<<EOD
INSERT INTO {$prefix}review
(	reviewed,
	reviewer_id,
	pub_id,
	rating_id,
	beer,
	notes,
	created,
	updated
)
VALUES
(	'{$date}',
	{$user['id']},
	{$submission['pub_id-'.$i]},
	{$submission['rating_id-'.$i.'-'.$j]},
	'{$submission['beername-'.$i.'-'.$j]}',
	'{$submission['notes-'.$i.'-'.$j]}',
	$created,
	$created
);
EOD;
		}
		elseif (1 == $submission['nora-'.$i])
		{
			$prefix=$config->get('database', 'tablePrefix');
$sql = <<<EOD
INSERT INTO {$prefix}review
(	reviewed,
	reviewer_id,
	pub_id,
	nora,
	created,
	updated
)
VALUES
(	'{$date}',
	{$user['id']},
	{$submission['pub_id-'.$i]},
	1,
	$created,
	$created

);
EOD;
		}
		else
		{
			$prefix=$config->get('database', 'tablePrefix');
$sql = <<<EOD
INSERT INTO {$prefix}review
(	reviewed,
	reviewer_id,
	pub_id,
	rating_id,
	beer_id,
	notes,
	created,
	updated
)
VALUES
(	'{$date}',
	{$user['id']},
	{$submission['pub_id-'.$i]},
	{$submission['rating_id-'.$i.'-'.$j]},
	'{$submission['beer_id-'.$i.'-'.$j]}',
	'{$submission['notes-'.$i.'-'.$j]}',
	$created,
	$created
);
EOD;
		}
		getQueryResults($sql);
	}
}
Session::delete('submission');
?>
	<h2>Thank you</h2>
	<p>Your submission has been entered.</p>
<?php
include(DOCROOT.'/skin/footer.php');
?>