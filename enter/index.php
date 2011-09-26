<?php
/**
 * Last updated $Date: 2007-04-05 09:26:51 +0100 (Thu, 05 Apr 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 352 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/enter/index.php $
 **/
$title = 'Scoring';
$access = array('reviewer','superreviewer','admin','sysadmin');
include('../includes/base.php');
include(DOCROOT.'/skin/header.php');

	$towns=getQueryResults('SELECT * FROM `'.$config->get('database', 'tablePrefix').'town` WHERE `active`=1 ORDER BY `name` ');
	$breweries=getQueryResults('SELECT * FROM `'.$config->get('database', 'tablePrefix').'brewery` WHERE `active`=1 ORDER BY `name`');
	$ratings=array(
		array('id'=>1,	'name'=>'Select Rating'),
		array('id'=>1,	'name'=>'0 Undrinkable',				'score'=>0, 'desc'=>'No cask ale available or so poor you have to take it back or can\'t finish it.'),
		array('id'=>2,	'name'=>'1 Poor',					'score'=>1, 'desc'=>'Beer that is anything from barely drinkable to drinkable with considerable resentment.'),
        array('id'=>3,	'name'=>'2 Average',					'score'=>2, 'desc'=>'Competently kept, drinkable pint but doesn\'t inspire in any way, not worth moving to another pub but you drink the beer without really noticing.'),
        array('id'=>4,	'name'=>'3 Good',					'score'=>3, 'desc'=>'Good beer in good form. You cancel plans to move to the next pub. You want to stay for another pint and  seek out the beer again.'),
        array('id'=>5,	'name'=>'4 Very Good',				'score'=>4, 'desc'=>'Excellent beer in excellent condition.'),
        array('id'=>6,	'name'=>'5 Perfect',					'score'=>5, 'desc'=>'Probably the best you are ever likely to find. A seasoned drinker will award this score very rarely.'));
 	$ratingsHelpContent = '<p>Camra <abbr title="National Beer Scoring System">NBSS</abbr> Ratings</p><dl>';
 	foreach ($ratings as $rating)
 	{
 		$ratingsHelpContent .= '<dt>'.$rating['name'].'</dt><dd>'.$rating['desc'].'</dd>';
 	}
	$ratingsHelpContent .= '</dl>';
 	$user = Session::get('user');
	$currDate = Session::get('date') ? Session::get('date') : time();

	$pubCount = is_null($request->get('post', 'pubCount')) ? 1 : $request->get('post', 'pubCount');
	$drinkCount = array();

	$town_id = $request->get('post', 'town_id');

	$form = new Form('nbssform');
	$form->class = 'neoAdminForm jshideme';
	$form->validationerrormsg = 'Please complete the following fields correctly.<br>Click on name to jump to error.';
	if ($form->submitted)
	{
		$currDate = strtotime($request->get('post', 'date'));
		Session::set('date', $currDate);
		if ( 0 == $form->submiterrors && 'enterReviews'==$form->submittedaction)
		{
			Session::set('submission', $request->get('post'));
			header('Location: '.$config->get('web','root').'/enter/confirm/');
			exit;
		}
		elseif ( 'addPub'==$form->submittedaction )
		{
			$pubCount++;
		}
	}

	$form->addFieldsetOpen('Review by '.$user['firstname'].' '.$user['lastname']);
	$form->addField('date', 'text', date('d-M-Y', $currDate));
		$form->addLabel('Date of Visit');
		$form->addFieldValidation('required');
	$form->addContent('<img id="cal1Open" src="'.$config->get('web','root').'/skin/images/cal.gif" alt="open calendar" /><div id="cal1Container"></div>');

	if ( is_null($town_id) || '' == $town_id )
	{
		$form->addFieldsetOpen('Town');
		$form->addField('town_id', 'select', $town_id);
			$form->addLabel('Town');
			$form->addOptions(array(''=>'Select town'));
			$form->addOptions($towns, 'id', 'name');
		$form->addField('selectTown', 'submit', 'Select this town');
			$form->addInputClass('btnSubmit');
		$pubs = array(array('id'=>'','name'=>'Select town first'));
	}
	else
	{
		$town = getItemFromArray($town_id, $towns);
		$pubs = array(''=>'Select Pub');
		$pubs = array_merge($pubs, getQueryResults("SELECT * FROM ".$config->get('database', 'tablePrefix')."pub WHERE town_id={$town_id} AND active=1 ORDER BY name"));
		if ( 2 == sizeof($pubs) && $pubs[0]['id']!='')
		{
			$pub = reset(array_reverse($pubs));
			$form->addFieldsetOpen('Pub: '.$pub['name'].' at '.$town['name']);
			//$form->addField('pub_id-0', 'hidden', $pub['id']);
		}
		else
		{
			$form->addFieldsetOpen('Town: '.$town['name']);
		}
		$form->addField('town_id', 'hidden', $town['id']);
	}
	if ( !is_null($town_id) )
	{
		for ($pubIndex = $pubCount-1 ; $pubIndex >= 0 ; $pubIndex--)
		{
			$drinkCount[$pubIndex] = is_null($request->get('post', 'drinkCount-'.$pubIndex)) ? 1 : $request->get('post', 'drinkCount-'.$pubIndex);
			if ( $form->submittedaction=='addDrink-'.$pubIndex ){
				/* Increment Drink count for this pub */
				$drinkCount[$pubIndex]++;
			}
			$form->addField('drinkCount-'.$pubIndex, 'hidden', $drinkCount[$pubIndex]);
			if ( 2 < sizeof($pubs) || (2 == sizeof($pubs) && $pubIndex == 0) )
			{
				if ( '' == $request->get('post', 'pub_id-'.$pubIndex) && 2!=sizeof($pubs) )
				{
					/* more than one pub in town and not selected this iteration */
					$form->addFieldsetOpen('Pub '.($pubIndex+1));
					$form->addField('pub_id-'.$pubIndex, 'select', $request->get('post', 'pub_id-'.$pubIndex) );
						$form->addLabel('Pub');
						$form->addOptions($pubs, 'id', 'name');
					$form->addField('submitPub', 'submit', 'Select this pub');
						$form->addInputClass('btnSubmit');
				}
				else
				{
					if ( 2 == sizeof($pubs) )
					{
						/* only one pub in this town */
						$pub = reset(array_reverse($pubs));
					}
					else
					{
						/* more than one pub and one selected */
						$pub = getItemFromArray($request->get('post', 'pub_id-'.$pubIndex), $pubs);
						$form->addFieldsetOpen('Pub '.$pub['name']);
					}

					/* add the pub id and start review */
					$form->addField('pub_id-'.$pubIndex, 'hidden', $pub['id']);
					$form->addField('nora-'.$pubIndex, 'checkbox', $request->get('post', 'nora-'.$pubIndex));
						$form->addLabel('No Real Ale at the '.$pub['name'], null, 'right');
						$form->addOptions(array(1,0));
				}
			}
			if ( '' != $request->get('post', 'pub_id-'.$pubIndex) || 2==sizeof($pubs) )
			{
				for ($drinkIndex=$drinkCount[$pubIndex]-1 ; $drinkIndex >= 0 ; $drinkIndex--)
				{
					$form->addFieldsetOpen('Drink '.($drinkIndex+1).' Details');
					$brewery_id = $request->get('post', 'brewery_id-'.$pubIndex.'-'.$drinkIndex);
					if ( ''==$brewery_id )
					{
						/* brewery not selected so show the list */
						$form->addField('brewery_id-'.$pubIndex.'-'.$drinkIndex, 'select', $brewery_id );
							$form->addLabel('Brewery');
							$form->addOptions(array(''=>'Select brewery'));
							$form->addOptions($breweries, 'id', 'name');
						$form->addField('selectBrewery', 'submit', 'Select this brewery');
							$form->addInputClass('btnSubmit');
						$beers = array(''=>'Select brewery first');
					}
					else
					{
						/* brewery selected, get beers */
						$brewery = getItemFromArray($brewery_id, $breweries);
						$beers = array(''=>'Select beer');
						$beers = array_merge($beers, getQueryResults('SELECT * FROM '.$config->get('database', 'tablePrefix').'beer WHERE brewery_id='.$brewery_id.';'));
						$form->addField('brewery_id-'.$pubIndex.'-'.$drinkIndex, 'hidden', $brewery_id );
						$form->addContent('<div><p class="fieldSubstitute"><span class="label">Brewery:</span><span class="input">'.$brewery['name'].'</span></p></div>');
					}
						$form->addField('beer_id-'.$pubIndex.'-'.$drinkIndex, 'select', $request->get('post', 'beer_id-'.$pubIndex.'-'.$drinkIndex) );
							$form->addLabel('Beer');
							$form->addOptions($beers, 'id', 'name');
						$form->addContent('<p class="optionseparator">or</p>');
						$form->addField('beername-'.$pubIndex.'-'.$drinkIndex, 'text', $request->get('post', 'beername-'.$pubIndex.'-'.$drinkIndex) );
							$form->addLabel('Unlisted Brewery/Beer');
							$form->addHelp('If your beer cannot be found in the lists above, please include the Brewery and the Name of the beer and we will add popular ones to the list.');

						$form->addField('rating_id-'.$pubIndex.'-'.$drinkIndex, 'select', $request->get('post', 'rating_id-'.$pubIndex.'-'.$drinkIndex) );
							$form->addLabel('Rating');
							$form->addOptions($ratings, 'id', 'name');
							$form->addHelp($ratingsHelpContent);

						$form->addField('notes-'.$pubIndex.'-'.$drinkIndex, 'textarea', $request->get('post', 'notes-'.$pubIndex.'-'.$drinkIndex) );
							$form->addLabel('Notes');

					$form->addFieldsetClose(); /* Drink */
				}
				$form->addField('addDrink-'.$pubIndex, 'submit', 'Add another drink at this pub');
					$form->addInputClass('btnSubmit');
			}
			if ( 2 < sizeof($pubs) )
			{
				$form->addFieldsetClose();
			}
			if ( isset($pub) )
			{
				removeItemFromArray($pub['id'], $pubs);
			}
		}
		if ( 2 <= sizeof($pubs) )
		{
			$form->addField('addPub', 'submit', 'Add different pub');
				$form->addInputClass('btnSubmit');
		}
	}
	$form->addFieldsetClose();/* town */

	if ( isset($pub) && isset($town_id))
	{
		$form->addField('enterReviews', 'submit', 'Enter your review');
			$form->addInputClass('btnSubmit');
	}

	$form->addField('pubCount', 'hidden', $pubCount);
	$form->addFieldsetClose();/* date */
	print $form->display();
if ( $request->get('post', '_submitjsform') )
{
	Session::set('submission', $request->get('post'));
	header('Location: '.$config->get('web','root').'/enter/confirm/');
	exit;
}
?>
<!-- Submission Form begins-->
<form id="submissionform" action="" method="post" class="jsshowme">
	<h2>This form submits all score for a single date.</h2>
	<div class="text required">
		<label for="frm_nbssform_date" class="text">Date of Visit<span class="required" title="Required">*</span></label>
		<input type="text" id="frm_nbssform_date" name="date" value="<?php echo date('d-M-Y',time())?>" />
		<img id="cal1Open" src="/skin/images/cal.gif" alt="open calendar" />
	</div><div id="cal1Container"></div>
	<div class="text required">
        <label for="pubinput">Pub</label>
        <input type="text" id="pubinput" class="ac_input" />
	    <div id="pubcontainer" class="ac_container"></div>
    </div><div class="hidden"><input type="hidden" id="pub_id" class="hidden" /></div>
	<div class="text">
        <label for="beerinput">Beer</label>
        <input type="text" id="beerinput" class="ac_input" />
    	<div id="beercontainer" class="ac_container"></div>
    </div><div class="hidden"><input type="hidden" id="beer_id" class="hidden" /></div>
    <div class="select">
    	<label for="ratinginput">Rating</label>
    	<select id="ratinginput">
    		<option value="0">No Real Ale</option>
    		<option value="1">0 Undrinkable</option>
    		<option value="2">1 Poor</option>
    		<option value="3">2 Average</option>
    		<option value="4">3 Good</option>
    		<option value="5">4 Very Good</option>
    		<option value="6">5 Perfect</option>
    	</select>
    </div>
    <div class="textarea">
    	<label for="notes">Notes</label>
    	<textarea id="notes" rows="4" cols="50"></textarea>
    </div>
    <div class="submit">
    	<input type="button" class="btnSubmit" value="Add Review" id="addReview" />
    </div>

	<div class="hidden">
		<input type="hidden" name="pubCount" id="pubCount" value="0" />
	</div>
	<table id="submissiontable" class="hidden">
		<thead>
			<tr>
				<th>Pub</th>
				<th>Drink</th>
				<th>Score</th>
			</tr>
		</thead>
		<tfoot>
			<tr><td colspan="3"><input name="_submitjsform" type="submit" class="btnSubmit" value="Submit review" /></td></tr>
		</tfoot>
		<tbody>

		</tbody>
	</table>
</form>
<!-- Submission Form ends -->
<?php
printJSInclude('yui/yahoo-dom-event/yahoo-dom-event.js');
printJSInclude('yui/connection/connection-min.js');
printJSInclude('yui/animation/animation-min.js');
printJSInclude('yui/autocomplete/autocomplete-min.js');
include(DOCROOT.'/skin/footer.php')
?>