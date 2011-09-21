<?php
/**
 * Last updated $Date: 2007-03-15 13:59:24 +0000 (Thu, 15 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 345 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/enter/confirm/index.php $
 **/
$title = 'Scoring : Confirm';
$access = array('reviewer','superreviewer','admin','sysadmin');
include('../../includes/base.php');
include(DOCROOT.'/skin/header.php');

$submission = Session::get('submission');
?>

	<form method="post" action="<?php echo $config->get('web', 'root')?>/enter/" >
	<table>
		<thead>
		<tr>
			<th>Town</th>
			<th>Pub</th>
			<th>Drink</th>
			<th>Rating</th>
			<th>Notes</th>
		</tr>
		</thead>
		<tbody>
<?php
print('<input type="hidden" name="date" value="'.$submission['date'].'" />');
print('<input type="hidden" name="pubCount" value="'.$submission['pubCount'].'" />');
for( $i=0 ; $i<$submission['pubCount'] ; $i++)
{
	print('<input type="hidden" name="drinkCount-'.$i.'" value="'.$submission['drinkCount-'.$i].'" />');
	$pub = reset(getQueryResults('SELECT pub.name AS name, town.name AS town FROM '.$config->get('database', 'tablePrefix').'pub pub, '.$config->get('database', 'tablePrefix').'town town WHERE pub.town_id=town.id AND pub.id='.$submission['pub_id-'.$i]));
	for ( $j=0 ; $j<$submission['drinkCount-'.$i] ; $j++)
	{
		if ( strlen($submission['beername-'.$i.'-'.$j]) )
		{
			$beername = $submission['beername-'.$i.'-'.$j];
			$rating = intval($submission['rating_id-'.$i.'-'.$j])-1;
		}
		elseif (1 == $submission['nora-'.$i])
		{
			$beername = 'No Real Ale Available';
			$rating = '';
			print('<input type="hidden" name="nora-'.$i.'" value="1" /');
		}
		else
		{
			if (!strlen($submission['beer_id-'.$i.'-'.$j]) )
			{
				if (strlen($submission['beername-'.$i.'-'.$j]))
				{
					$beername = $submission['beername-'.$i.'-'.$j];
				}
				else
				{
					$beername = 'None specified';
				}
			}
			else
			{
				$beer = reset(getQueryResults('SELECT brewery.name as brewery, beer.name as beer FROM '.$config->get('database', 'tablePrefix').'brewery brewery, '.$config->get('database', 'tablePrefix').'beer beer WHERE beer.brewery_id=brewery.id AND beer.id='.$submission['beer_id-'.$i.'-'.$j]));
				$beername = $beer['brewery'].', '.$beer['beer'];
			}

			$rating = intval($submission['rating_id-'.$i.'-'.$j])-1;
		}
		$submission['notes-'.$i.'-'.$j] = htmlentities($submission['notes-'.$i.'-'.$j]);
		echo <<<EOT
		<tr>
			<td>{$pub['town']}</td>
			<td>{$pub['name']}<input type="hidden" name="pub_id-{$i}" value="{$submission['pub_id-'.$i]}" /></td>
			<td>{$beername}
				<input type="hidden" name="rating_id-{$i}-{$j}" value="{$submission['rating_id-'.$i.'-'.$j]}" />
				<input type="hidden" name="brewery_id-{$i}-{$j}" value="{$submission['brewery_id-'.$i.'-'.$j]}" />
				<input type="hidden" name="beer_id-{$i}-{$j}" value="{$submission['beer_id-'.$i.'-'.$j]}" />
				<input type="hidden" name="beername-{$i}-{$j}" value="{$submission['beername-'.$i.'-'.$j]}" />
			</td>
			<td>{$rating}<input type="hidden" name="rating_id-{$i}-{$j}" value="{$submission['rating_id-'.$i.'-'.$j]}" /></td>
			<td>{$submission['notes-'.$i.'-'.$j]}<input type="hidden" name="notes-{$i}-{$j}" value="{$submission['note-'.$i.'-'.$j]}" /></td></td>
		</tr>
EOT;
	}
}
?>

		</tbody>
	</table>
	<input type="submit" class="btnSubmit" value="Go Back &amp; Amend" /> <a href="<?php echo $config->get('web', 'root')?>/enter/thankyou/" class="btnConfirm">Confirm</a>
	</form>
<?php
include(DOCROOT.'/skin/footer.php');
?>