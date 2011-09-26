<?php
/**
 * Last updated $Date: 2007-03-09 13:02:59 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 326 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/admin/pubs/newlandlord/index.php $
 **/
$title='Pub Admin : New Landlord';
$access = array('admin','sysadmin');
include('../../../includes/base.php');
include(DOCROOT.'/skin/header.php');
?>
<h2>Change of Landlord</h2>
<?php
	if ( $request->get('post', 'town_id') ) {$town_id = $request->get('post', 'town_id');}
	elseif ( $request->get('get', 'town_id') ) {$town_id = $request->get('get', 'town_id');}
	else {$town_id = '';}

	if ( $request->get('get', 'pub_id') ) {$pub_id = $request->get('get', 'pub_id');}
	elseif ( $request->get('post', 'pub_id') ) {$pub_id = $request->get('post', 'pub_id');}
	else {$pub_id = '';}

	$form = new Form('nbssform');
	$form->class = 'neoAdminForm';
	$form->validationerrormsg = 'Please complete the following fields correctly.<br>Click on name to jump to error.';

	if ($form->submitted)
	{
		$pub = array(
						'id'=>$pub_id,
						'town_id'=>$town_id,
						'brewery_id'=>$request->get('post', 'brewery_id'),
						'name'=>$request->get('post', 'name'),
						'notes'=>$request->get('post', 'notes'),
						'active'=>($request->get('post', 'active')!=1?0:1) );
		if ( 0 == $form->submiterrors )
		{
			if ( 'updatePub'==$form->submittedaction )
			{
				$sql = "UPDATE ".$config->get('database', 'tablePrefix')."pub SET notes='{$pub['notes']}' WHERE id={$pub['id']};";
				archiveReviews($pub['id'], date('Y-m-d',strtotime($request->get('post', 'date'))));
				getQueryResults($sql);
				header('Location: '.$config->get('web','root').'/admin/?msg=Updated+pub+landlord+for+'.$pub['name']);
				exit;
			}
		}
	}
	else
	{
		/* Form has not been submitted */
		if ( ''!=$town_id )
		{
			$town = reset(getQueryResults('SELECT * FROM '.$config->get('database', 'tablePrefix').'town WHERE id='.$town_id));
			if ( !isset($pub) )
			{
				$pub = array('id'=>'new', 'town_id'=>$town_id, 'name'=>'', 'abv'=>'', 'og'=>'', 'notes'=>'');
			}
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
			$form->addOptions(array(''=>'Select town first'));
			$form->addOptions($towns, 'id', 'name');
		$form->addField('selectTown', 'submit', 'Select this town');
			$form->addInputClass('btnSubmit');
	}
	else
	{
		if ( ''!=$pub_id )
		{
			$pub = reset(getQueryResults('SELECT * FROM '.$config->get('database', 'tablePrefix').'pub WHERE id='.$pub_id));
			$form->addField('town_id', 'hidden', $town_id);
			$form->addField('pub_id', 'hidden', $pub_id);
			$form->addField('name', 'hidden', $pub['name']);
			$form->addField('date', 'text', date('d-M-Y', time()));
				$form->addLabel('Date of Change');
				$form->addFieldValidation('required');

			$form->addContent('<img id="cal1Open" src="'.$config->get('web','root').'/skin/images/cal.gif" /><div id="cal1Container"></div>');

			$form->addField('notes', 'textarea', $pub['notes']);
				$form->addLabel('Notes');

			$form->addField('updatePub', 'submit', 'Submit change');
				$form->addInputClass('btnSubmit');

			$form->addField('resetForm', 'reset', 'Reset Form');
				$form->addInputClass('btnReset');
			$form->addContent('<div class="reset"><a class="btnReset fleft" href="'.$config->get('web','root').'/admin/pubs/newlandlord/?town_id='.$town_id.'">Cancel &amp; Pick another pub</a></div>');
		}
		else
		{
			$pubs = getQueryResults('SELECT * FROM '.$config->get('database', 'tablePrefix').'pub WHERE town_id='.$town_id);

			$form->addFieldsetOpen('Pubs in '.$town['name']);
				$form->addField('town_id', 'hidden', $town_id);

				$form->addField('pub_id', 'select');
					$form->addLabel('Pub');
					$form->addOptions($pubs, 'id', 'name');

				$form->addField('selectPub', 'submit', 'Change of landlord');
					$form->addInputClass('btnSubmit');
				$form->addContent('<div class="reset"><a class="btnReset fleft" href="'.$config->get('web','root').'/admin/pubs/newlandlord/">Cancel &amp; Pick another town</a></div>');
			$form->addFieldsetClose();
		}
	}
	print $form->display().$form->submiterrormsg;

include(DOCROOT.'/skin/footer.php');
