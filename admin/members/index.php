<?php
/**
 * Last updated $Date: 2007-04-11 15:47:41 +0100 (Wed, 11 Apr 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 356 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/admin/members/index.php $
 **/
$title='Member Admin';
$access = array('admin','sysadmin');
include('../../includes/base.php');
include(DOCROOT.'/skin/header.php');
?>
<h2>Member Admin</h2>
<?php
	$member_id = $request->get('post', 'member_id');

	$form = new Form('memberadmin'.$member_id);
	$form->class = 'neoAdminForm';
	$form->validationerrormsg = 'Please complete the following fields correctly.<br>Click on name to jump to error.';

	if ($form->submitted)
	{
		$member = array(
				'id'=>$request->get('post', 'member_id'),
				'firstname'=>$request->get('post', 'firstname'),
				'lastname'=>$request->get('post', 'lastname'),
				'type'=>'reviewer',
				'email'=>$request->get('post', 'email'),
				'active'=>($request->get('post', 'active')!=1?0:1)
			);
		if ( 0 == $form->submiterrors && 'updateUser'==$form->submittedaction)
		{
			$date = gmdate('YmdHis', time());
			$camra_number = md5(strtoupper(trim(ltrim($request->get('post', 'camra_number'),'0'))));
			$postcode = md5(strtoupper(str_replace(' ', '', $request->get('post', 'postcode'))));
			if ('new' == $member_id)
			{
				$prefix = $config->get('database', 'tablePrefix');
				$sql = <<< EOQ
INSERT INTO {$prefix}user
(firstname, lastname, type, camra_number, postcode, email, active, created, updated)
VALUES
('{$member['firstname']}', '{$member['lastname']}', 'reviewer', '{$camra_number}', '{$postcode}', '{$member['email']}', {$member['active']}, {$date}, {$date});
EOQ;
				$location = '/admin/?msg=User+created';
			}
			else
			{
				$prefix = $config->get('database', 'tablePrefix');
				$sql = <<<EOQ
UPDATE {$prefix}user
SET
	firstname='{$member['firstname']}',
	lastname='{$member['lastname']}',
	email='{$member['email']}',
	active={$member['active']},
	updated={$date}
EOQ;
				$sql .= (''!=$request->get('post', 'camra_number')) ? ", camra_number='{$camra_number}'" : '';
				$sql .= (''!=$request->get('post', 'postcode')) ? ", postcode='{$postcode}'" : '';
				$sql .= " WHERE id={$member['id']}";

				$location = '/admin/?msg=User+updated';
			}
			getQueryResults($sql);
			header('Location: '.$config->get('web','root').$location);
			exit;
		}
		elseif ( 'deleteUser'==$form->submittedaction )
		{
			getQueryResults('DELETE FROM user WHERE id='.$request->get('post', 'member_id'));
			header('Location: '.$config->get('web','root').'/admin/?msg=User+deleted');
			exit;
		}
	}
	else
	{
		if ( !is_null($member_id) && ''!=$member_id && 'new'!=$member_id)
		{
			$member = reset(getQueryResults('SELECT * FROM '.$config->get('database', 'tablePrefix').'user WHERE id='.$member_id));
		}
		elseif ('new' == $member_id)
		{
			$member = array(
				'id'=>'new',
				'firstname'=>($request->get('post', 'firstname')?$request->get('post', 'firstname'):''),
				'lastname'=>($request->get('post', 'lastname')?$request->get('post', 'lastname'):''),
				'type'=>'reviewer',
				'camra_number'=>($request->get('post', 'camra_number')?$request->get('post', 'camra_number'):''),
				'postcode'=>($request->get('post', 'postcode')?$request->get('post', 'postcode'):''),
				'email'=>($request->get('post', 'email')?$request->get('post', 'email'):''),
				'active'=>($request->get('post', 'active')!=1?0:1)
			);
		}
		else
		{
			$members=getQueryResults('SELECT id, CONCAT(lastname, \', \', firstname) as name FROM `'.$config->get('database', 'tablePrefix').'user` ORDER BY `lastname`,`firstname`;');
		}

	}

	if ( isset($members) )
	{
		$form->addField('member_id', 'select');
			$form->addLabel('Select Member');
			$form->addOptions(array('new'=>'Add New Member'));
			$form->addOptions($members, 'id', 'name');
		$form->addField('selectMember', 'submit', 'Select Member');
			$form->addInputClass('btnSubmit');
	}
	else
	{
		$form->addField('member_id', 'hidden', $member['id']);

		$form->addField('firstname', 'text', $member['firstname']);
			$form->addLabel('Firstname');
			$form->addFieldValidation('required');

		$form->addField('lastname', 'text', $member['lastname']);
			$form->addLabel('Lastname');
			$form->addFieldValidation('required');

		$form->addField('email', 'text', $member['email']);
			$form->addLabel('Email Address');
			$form->addFieldValidation('email');

		$form->addField('camra_number', 'text');
			$form->addLabel('CAMRA Membership Number');
			if ( 'new' == $member['id'] )
			{
				$form->addFieldValidation('required numeric');
			}
			else
			{
				$form->addFieldValidation('numeric');
			}

		$form->addField('postcode', 'text');
			$form->addLabel('Postcode');
			if ( 'new' == $member['id'] )
			{
				$form->addFieldValidation('required');
			}

		$form->addField('active', 'checkbox', $member['active']);
			$form->addLabel('Active', null, 'right');
			$form->addOptions(array(1,0));

		if ( 'new' == $member_id )
		{
			$form->addField('updateUser', 'submit', 'Create User');
		}
		else
		{
			$form->addField('updateUser', 'submit', 'Update User');
		}
			$form->addInputClass('btnSubmit');
		if ( 0 == $member['active'] )
		{
			$form->addField('deleteUser', 'submit', 'Delete User');
				$form->addInputClass('btnReset');
		}

		$form->addField('resetForm', 'reset', 'Reset Form');
			$form->addInputClass('btnReset');

		$form->addContent('<div class="reset"><a href="'.$config->get('web','root').'/admin/members/" class="btnReset fleft" title="go back to the list of members and pick another">Cancel &amp; Back to members list</a></div>');
	}

	print $form->display().$form->submiterrormsg;
include(DOCROOT.'/skin/footer.php');
?>