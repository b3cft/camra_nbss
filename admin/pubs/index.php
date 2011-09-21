<?php
/**
 * Last updated $Date: 2007-03-09 14:26:08 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 331 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/admin/pubs/index.php $
 **/
$title='Pub Admin';
$access = array('admin','sysadmin');
include('../../includes/base.php');
include(DOCROOT.'/skin/header.php');
?>
<h2>Pub Administration</h2>
<?php
	if ( $request->get('post', 'town_id') ) {$town_id = $request->get('post', 'town_id');}
	elseif ( $request->get('get', 'town_id') ) {$town_id = $request->get('get', 'town_id');}
	else {$town_id = '';}

	if ( $request->get('get', 'pub_id') ) {$pub_id = $request->get('get', 'pub_id');}
	elseif ( $request->get('post', 'pub_id') ) {$pub_id = $request->get('post', 'pub_id');}
	else {$pub_id = '';}

	$form = new Form('pubadmin'.$town_id.'-'.$pub_id);
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
				if ('new' == $pub_id)
				{
					$sql = "INSERT INTO ".$config->get('database', 'tablePrefix')."pub (name, town_id, brewery_id, notes, active) VALUES ('{$pub['name']}', {$pub['town_id']}, {$pub['brewery_id']}, '{$pub['notes']}', {$pub['active']});";
					$mesg = 'Created new pub: '.$pub['name'];
				}
				else
				{
					$sql = "UPDATE ".$config->get('database', 'tablePrefix')."pub SET name='{$pub['name']}', town_id={$pub['town_id']}, brewery_id={$pub['brewery_id']}, notes='{$pub['notes']}', active={$pub['active']} WHERE id={$pub['id']};";
					$mesg = 'Updated pub: '.$pub['name'];
				}
				getQueryResults($sql);
				header('Location: '.$config->get('web','root').'/admin/pubs/?mesg=Updated+pub+list&town_id='.$town_id);
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
		print('<h3>pubs for '.$town['name'].'</h3>');
		if ( ''!=$pub_id )
		{
			if ( 'new'!=$pub_id )
			{
				$pub = reset(getQueryResults('SELECT * FROM '.$config->get('database', 'tablePrefix').'pub WHERE id='.$pub_id));
			}
			$form->addField('town_id', 'hidden', $town_id);
			$form->addField('pub_id', 'hidden', $pub_id);
			$form->addField('name', 'text', $pub['name']);
				$form->addLabel('pub Name');
				$form->addFieldValidation('required');

			$form->addField('brewery_id', 'select', $pub['brewery_id']);
				$form->addLabel('Brewery Owned?');
				$form->addOptions(array('0'=>'None/Freehold'));
				$form->addOptions(getQueryResults('SELECT id,name FROM '.$config->get('database', 'tablePrefix').'brewery ORDER by name'), 'id', 'name');

			$form->addField('active', 'checkbox', $pub['active']);
				$form->addLabel('Active', null, 'right');
				$form->addOptions(array(1,0));

				$form->addField('notes', 'textarea', $pub['notes']);
				$form->addLabel('Notes');

			if ( 'new' == $pub_id )
			{
				$form->addField('updatePub', 'submit', 'Create pub');
			}
			else
			{
				$form->addField('updatePub', 'submit', 'Update pub');
			}
				$form->addInputClass('btnSubmit');

			$form->addField('resetForm', 'reset', 'Reset Form');
				$form->addInputClass('btnReset');
			$form->addContent('<div class="reset"><a class="btnReset fleft" href="'.$config->get('web','root').'/admin/pubs/?town_id='.$town_id.'">Cancel &amp; Pick another pub</a></div>');
		}
		else
		{
			$pubs = getQueryResults('SELECT * FROM '.$config->get('database', 'tablePrefix').'pub WHERE town_id='.$town_id);
			?>
			<div class="reset"><a class="btnReset fleft" href="<?php echo $config->get('web', 'root')?>/admin/pubs/?town_id=<?php echo $town_id;?>&amp;pub_id=new">Add new pub</a></div>
			<div class="reset"><a class="btnReset fleft" href="<?php echo $config->get('web', 'root')?>/admin/pubs/">Go back to the town list</a></div>
			<p class="clear">Or edit existing pubs:</p>
			<?php
			print('<ul>');
			foreach ($pubs as $ipub)
			{
				print('<li><a href="?town_id='.$town_id.'&amp;pub_id='.$ipub['id'].'">'.$ipub['name'].'</a></li>');
			}
			print('</ul>');
		}
	}
	print $form->display().$form->submiterrormsg;
include(DOCROOT.'/skin/footer.php');
?>