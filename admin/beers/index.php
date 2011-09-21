<?php
/**
 * Last updated $Date: 2007-03-09 13:02:59 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 326 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/admin/beers/index.php $
 **/
$title='Beer Admin';
$access = array('admin','sysadmin');
include('../../includes/base.php');
include(DOCROOT.'/skin/header.php');
?>
<h2>Beer Administration</h2>
<?php
	if ( $request->get('post', 'brewery_id') ) {$brewery_id = $request->get('post', 'brewery_id');}
	elseif ( $request->get('get', 'brewery_id') ) {$brewery_id = $request->get('get', 'brewery_id');}
	else {$brewery_id = '';}

	if ( $request->get('get', 'beer_id') ) {$beer_id = $request->get('get', 'beer_id');}
	elseif ( $request->get('post', 'beer_id') ) {$beer_id = $request->get('post', 'beer_id');}
	else {$beer_id = '';}

	$form = new Form('beeradmin'.$brewery_id.'-'.$beer_id);
	$form->class = 'neoAdminForm';
	$form->validationerrormsg = 'Please complete the following fields correctly.<br>Click on name to jump to error.';

	if ($form->submitted)
	{
		$beer = array(
						'id'=>$beer_id,
						'brewery_id'=>$brewery_id,
						'name'=>$request->get('post', 'name'),
						'abv'=>$request->get('post', 'abv') ? $request->get('post', 'abv') : 'NULL',
						'og'=>$request->get('post', 'og') ? $request->get('post', 'og') : 'NULL',
						'notes'=>$request->get('post', 'notes') );
		if ( 0 == $form->submiterrors )
		{
			if ( 'updateBeer'==$form->submittedaction )
			{
				if ('new' == $beer_id)
				{
					$sql = "INSERT INTO ".$config->get('database', 'tablePrefix')."beer (name, brewery_id, abv, og, notes) VALUES ('{$beer['name']}', {$beer['brewery_id']}, {$beer['abv']}, {$beer['og']}, '{$beer['notes']}');";
					$mesg = 'Created new beer: '.$beer['name'];
				}
				else
				{
					$sql = "UPDATE ".$config->get('database', 'tablePrefix')."beer SET name='{$beer['name']}', brewery_id={$beer['brewery_id']}, abv={$beer['abv']}, og={$beer['og']}, notes='{$beer['notes']}' WHERE id={$beer['id']};";
					$mesg = 'Updated beer: '.$beer['name'];
				}
				getQueryResults($sql);
				header('Location: '.$config->get('web','root').'/admin/beers/?mesg=Updated+beer+list&brewery_id='.$brewery_id);
				exit;
			}
		}
	}

		if ( ''!=$brewery_id )
		{
			$brewery = reset(getQueryResults('SELECT * FROM '.$config->get('database', 'tablePrefix').'brewery WHERE id='.$brewery_id));
			if ( !isset($beer) )
			{
				$beer = array('id'=>'new', 'brewery_id'=>$brewery_id, 'name'=>'', 'abv'=>'', 'og'=>'', 'notes'=>'');
			}
		}
		else
		{
			$breweries=getQueryResults('SELECT id, name FROM '.$config->get('database', 'tablePrefix').'brewery WHERE active=1 ORDER BY name;');
		}

	if ( isset($breweries) )
	{
		$form->addField('brewery_id', 'select');
			$form->addLabel('Select Brewery');
			$form->addOptions(array(''=>'Select brewery first'));
			$form->addOptions($breweries, 'id', 'name');
		$form->addField('selectBrewery', 'submit', 'Select this brewery');
			$form->addInputClass('btnSubmit');
	}
	else
	{
		if ( ''!=$beer_id )
		{
			if ( 'new'!=$beer_id )
			{
				$beer = reset(getQueryResults('SELECT * FROM '.$config->get('database', 'tablePrefix').'beer WHERE id='.$beer_id));
			}
			$form->addContent('<h3>Beers for '.$brewery['name'].'</h3>');
			$form->addField('brewery_id', 'hidden', $brewery_id);
			$form->addField('beer_id', 'hidden', $beer_id);
			$form->addField('name', 'text', $beer['name']);
				$form->addLabel('Beer Name');
				$form->addFieldValidation('required');

			$form->addField('abv', 'text', $beer['abv']);
				$form->addLabel('<span class="abbr" title="Alcohol By Volume"><abbr title="Alcohol By Volume">ABV</abbr></span>');

			$form->addField('og', 'text', $beer['og']);
				$form->addLabel('<span class="abbr" title="Original Gravity"><abbr title="Original Gravity">OG</abbr></span>');

			$form->addField('notes', 'textarea', $beer['notes']);
				$form->addLabel('Notes');

			if ( 'new' == $beer_id )
			{
				$form->addField('updateBeer', 'submit', 'Create Beer');
			}
			else
			{
				$form->addField('updateBeer', 'submit', 'Update Beer');
			}
				$form->addInputClass('btnSubmit');
			$form->addField('resetForm', 'reset', 'Reset Form');
				$form->addInputClass('btnReset');

			$form->addContent('<div class="reset"><a href="'.$config->get('web','root').'/admin/beers/?brewery_id='.$brewery_id.'" class="btnReset fleft" title="go back to the list of beers and pick another">Cancel &amp; Pick another beer</a></div>');
		}
		else
		{
			$beers = getQueryResults('SELECT * FROM '.$config->get('database', 'tablePrefix').'beer WHERE brewery_id='.$brewery_id);
			?>
			<h3>Beers for <?php echo $brewery['name'];?></h3>
			<div class="submit"><a class="btnSubmit fleft" href="<?php echo $config->get('web', 'root')?>/admin/beers/?brewery_id=<?php echo $brewery_id;?>&amp;beer_id=new">Add new beer</a></div>
			<div class="reset"><a class="btnReset fleft" href="<?php echo $config->get('web', 'root')?>/admin/beers/">Go back to the brewery list</a></div>
			<p class="clear">Or edit existing beers:</p>
			<?php
			print('<ul>');
			foreach ($beers as $iBeer)
			{
				print('<li><a href="?brewery_id='.$brewery_id.'&amp;beer_id='.$iBeer['id'].'">'.$iBeer['name'].'</a></li>');
			}
			print('</ul>');
		}
	}
	print $form->display().$form->submiterrormsg;
include(DOCROOT.'/skin/footer.php');
?>