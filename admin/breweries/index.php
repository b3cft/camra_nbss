<?php
/**
 * Last updated $Date: 2007-03-09 13:02:59 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 326 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/admin/breweries/index.php $
 **/
$title='Brewery Admin';
$access = array('admin','sysadmin');
include('../../includes/base.php');
include(DOCROOT.'/skin/header.php');
?>
<h2>Brewery Admin</h2>
<?php
	$brewery_id = $request->get('post', 'brewery_id');

	$form = new Form('breweryadmin'.$brewery_id);
	$form->class = 'neoAdminForm';
	$form->validationerrormsg = 'Please complete the following fields correctly.<br>Click on name to jump to error.';

	if ($form->submitted)
	{
		$brewery = array(
						'id'=>$request->get('post', 'brewery_id'),
						'name'=>$request->get('post', 'name'),
						'location'=>$request->get('post', 'location'),
						'active'=>($request->get('post', 'active')!=1?0:1) );
		if ( 0 == $form->submiterrors )
		{
			if ( 'updateBrewery'==$form->submittedaction )
			{
				if ('new' == $brewery_id)
				{
					$sql = "INSERT INTO ".$config->get('database', 'tablePrefix')."brewery (name, location, active) VALUES ('{$brewery['name']}', '{$brewery['location']}', {$brewery['active']});";
				}
				else
				{
					$sql = "UPDATE ".$config->get('database', 'tablePrefix')."brewery SET name='{$brewery['name']}', location='{$brewery['location']}', active={$brewery['active']} WHERE id={$brewery['id']};";
				}
				getQueryResults($sql);
			}
		}
	}
	else
	{
		/* Form has not been submitted */
		if ( !is_null($brewery_id) && ''!=$brewery_id && 'new'!=$brewery_id)
		{
			$brewery = reset(getQueryResults('SELECT * FROM '.$config->get('database', 'tablePrefix').'brewery WHERE id='.$brewery_id));
		}
		elseif ('new' == $brewery_id)
		{
			$brewery = array('id'=>'new', 'name'=>'', 'location'=>'', 'active'=>1);
		}
		else
		{
			$breweries=getQueryResults('SELECT id, name FROM '.$config->get('database', 'tablePrefix').'brewery WHERE active=1 ORDER BY name;');
		}
	}

	if ( isset($breweries) )
	{
		$form->addField('brewery_id', 'select');
			$form->addLabel('Select Brewery');
			$form->addOptions(array('new'=>'Add New Brewery'));
			$form->addOptions($breweries, 'id', 'name');
		$form->addField('selectBrewery', 'submit', 'Select Brewery');
			$form->addInputClass('btnSubmit');
	}
	else
	{
		$form->addField('brewery_id', 'hidden', $brewery['id']);

		$form->addField('name', 'text', $brewery['name']);
			$form->addLabel('Brewery Name');
			$form->addFieldValidation('required');

		$form->addField('location', 'text', $brewery['location']);
			$form->addLabel('Location');

		$form->addField('active', 'checkbox', $brewery['active']);
			$form->addLabel('Active', null, 'right');
			$form->addOptions(array(1,0));

		if ( 'new' == $brewery_id )
		{
			$form->addField('updateBrewery', 'submit', 'Create brewery');
		}
		else
		{
			$form->addField('updateBrewery', 'submit', 'Update brewery');
		}
			$form->addInputClass('btnSubmit');
		$form->addField('resetForm', 'reset', 'Reset form');
			$form->addInputClass('btnReset');

		$form->addContent('<div class="reset"><a href="'.$config->get('web','root').'/admin/breweries/" class="btnReset fleft" title="go back to the list of breweries and pick another">Cancel &amp; Pick another brewery</a></div>');
	}
	print $form->display().$form->submiterrormsg;
include(DOCROOT.'/skin/footer.php');
?>