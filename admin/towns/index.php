<?php
/**
 * Last updated $Date: 2007-03-09 14:19:01 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 330 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/admin/towns/index.php $
 **/
$title='Town Admin';
$access = array('admin','sysadmin');
include('../../includes/base.php');
include(DOCROOT.'/skin/header.php');
?>
<h2>Town Admin</h2>
<?php
	$town_id = $request->get('post', 'town_id');

	$form = new Form('townadmin'.$town_id);
	$form->class = 'neoAdminForm';
	$form->validationerrormsg = 'Please complete the following fields correctly.<br>Click on name to jump to error.';

	if ($form->submitted)
	{
		$town = array(
						'id'=>$request->get('post', 'town_id'),
						'name'=>$request->get('post', 'name'),
						'active'=>($request->get('post', 'active')!=1?0:1) );
		if ( 0 == $form->submiterrors )
		{
			if ( 'updateTown'==$form->submittedaction )
			{
				if ('new' == $town_id)
				{
					$sql = "INSERT INTO ".$config->get('database', 'tablePrefix')."town (name, active) VALUES ('{$town['name']}', {$town['active']});";
					$location = '/admin/?msg=Town+created';
				}
				else
				{
					$sql = "UPDATE ".$config->get('database', 'tablePrefix')."town SET name='{$town['name']}', active={$town['active']} WHERE id={$town['id']};";
					$location = '/admin/?msg=Town+updated';
				}
				getQueryResults($sql);
				header('Location: '.$config->get('web','root').$location);
				exit;
			}
		}
	}
	else
	{
		/* Form has not been submitted */
		if ( !is_null($town_id) && ''!=$town_id && 'new'!=$town_id)
		{
			$town = reset(getQueryResults('SELECT * FROM '.$config->get('database', 'tablePrefix').'town WHERE id='.$town_id));
		}
		elseif ('new' == $town_id)
		{
			$town = array('id'=>'new', 'name'=>'', 'active'=>1);
		}
		else
		{
			$towns=getQueryResults('SELECT id, name FROM '.$config->get('database', 'tablePrefix').'town WHERE active=1 ORDER BY name;');
		}
	}

	if ( isset($towns) )
	{
		$form->addField('town_id', 'select');
			$form->addLabel('Select town');
			$form->addOptions(array('new'=>'Add new town'));
			$form->addOptions($towns, 'id', 'name');
		$form->addField('selecttown', 'submit', 'Select town');
			$form->addInputClass('btnSubmit');
	}
	else
	{
		$form->addField('town_id', 'hidden', $town['id']);

		$form->addField('name', 'text', $town['name']);
			$form->addLabel('Town Name');
			$form->addFieldValidation('required');

		$form->addField('active', 'checkbox', $town['active']);
			$form->addLabel('Active', null, 'right');
			$form->addOptions(array(1,0));

		if ( 'new' == $town_id )
		{
			$form->addField('updateTown', 'submit', 'Create town');
		}
		else
		{
			$form->addField('updateTown', 'submit', 'Update town');
		}
			$form->addInputClass('btnSubmit');
		$form->addField('resetForm', 'reset', 'Reset Form');
			$form->addInputClass('btnReset');
		$form->addContent('<div class="reset"><a href="'.$config->get('web','root').'/admin/towns/" class="btnReset fleft">Cancel &amp; Select another town</a></div>');
	}
	print $form->display().$form->submiterrormsg;
include(DOCROOT.'/skin/footer.php');
?>