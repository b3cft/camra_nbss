<?php
/**
 * Last updated $Date: 2007-04-11 15:47:41 +0100 (Wed, 11 Apr 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 356 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/signup/index.php $
 **/
$title='Sign up';
include('../includes/base.php');
include(DOCROOT.'/skin/header.php');

	$form = new Form('nbssv2signup');
	$form->class = 'neoAdminForm';
	$form->validationerrormsg = 'The following fields must be completed:';

	$member = array(
				'firstname'=>$request->get('post', 'firstname'),
				'lastname'=>$request->get('post', 'lastname'),
				'camra_number'=>$request->get('post', 'camra_number'),
				'camra_member'=>($request->get('post', 'camra_member')?1:0),
				'postcode'=>$request->get('post', 'postcode'),
				'email'=>$request->get('post', 'email'),
				'verified'=>0,
				'active'=>($config->get('web', 'signupAutoEnable')?1:0)
			);

	if ($form->submitted && $form->submiterrors==0)
	{
		$prefix = $config->get('database', 'tablePrefix');
		$userCheck = getQueryResults('SELECT * FROM '.$prefix.'user WHERE camra_number=\''.md5($member['camra_number']).'\'');
		if (0==sizeof($userCheck))
		{
			if (strlen($config->get('web', 'signupEmail')))
			{
				$headers ='From: No Reply <signup@'.$_SERVER['HTTP_HOST'].'>'.CRLF;
				$subject ='Account Signup at '.$_SERVER['HTTP_HOST'];
				$content ='There has been a new signup, please verify him/her.'.CR.CR;
				$content.='Firstname: '.$member['firstname'].CR;
				$content.='Lastname: '.$member['lastname'].CR;
				$content.='Email: '.$member['email'].CR;
				$content.='Camra Number/Username: '.$member['camra_number'].CR;
				$content.='Camra Member?: '.($member['lastname']? 'Yes' : 'No').CR;
				$content.='Postcode: '.$member['postcode'].CR;
				$content.='Auto Activated: '.($member['active']? 'Yes' : 'No').CR;
				$content.='Signup from IP address: '.$_SERVER['REMOTE_ADDR'].CR;
				$content.='On: '.date('D jS M Y H:i:s');
				$content.=CR.CR.'Thank you.';
				mail($config->get('web', 'signupEmail'),$subject,$content,$headers);
			}
			$date = gmdate('YmdHis', time());
			$camra_number = md5(strtoupper(trim(ltrim($member['camra_number']),'0')));
			$postcode = md5(strtoupper(str_replace(' ', '', $member['postcode'])));
			$sql = <<<EOQ
INSERT INTO {$prefix}user
(firstname, lastname, type, camra_number, camra_member, postcode, email, active, verified, created, updated)
VALUES
('{$member['firstname']}', '{$member['lastname']}', 'reviewer', '{$camra_number}', {$member['camra_member']}, '{$postcode}', '{$member['email']}', {$member['active']}, {$member['verified']}, {$date}, {$date});
EOQ;
			getQueryResults($sql);
			$headers ='From: No Reply <signup@'.$_SERVER['HTTP_HOST'].'>'.CRLF;
			$subject ='Your account signup at '.$_SERVER['HTTP_HOST'];
			$content ='Thank you for your request for any account.'.CR.CR;
			$content.='To validate your signup please follow the link below'.CR;
			$content.='http://'.$_SERVER['HTTP_HOST'].$config->get('web', 'root').'/signup/at8/?v='.md5($member['camra_number']).CR;
			$content.=CR.CR.'Thank you.';
			mail($member['email'],$subject,$content,$headers);
			header('Location: '.$config->get('web', 'root').'/signup/thankyou/');
			exit;
		}
		else
		{
			$form->submiterrormsg.='<p class="error">Username/CAMRA Number is already registered.</p><p> Please choose another one or check with your system administrator.</p>';
		}
	}

	$form->addFieldsetOpen('Signup');

	$form->addField('firstname', 'text', $member['firstname']);
			$form->addLabel('Firstname');
			$form->addFieldValidation('required');

		$form->addField('lastname', 'text', $member['lastname']);
			$form->addLabel('Lastname');
			$form->addFieldValidation('required');

		$form->addField('email', 'text', $member['email']);
			$form->addLabel('Email Address');
			$form->addFieldValidation('required email');

		$form->addField('camra_number', 'text', $member['camra_number']);
		if ($config->get('web', 'signupNonCamra'))
		{
			$form->addLabel('CAMRA Membership Number/Username');
			$form->addFieldValidation('required');
		}
		else
		{
			$form->addLabel('CAMRA Membership Number');
			$form->addFieldValidation('required numeric');
		}
		$form->addField('postcode', 'text', $member['postcode']);
			$form->addLabel('Postcode');
			$form->addFieldValidation('required');
		if ($config->get('web', 'signupNonCamra'))
		{
		$form->addField('camra_member', 'checkbox', $member['camra_member']);
			$form->addLabel('I am a CAMRA Member', null, 'right');
			$form->addOptions(array(1,0));
		}
	$form->addFieldsetClose();

	$form->addField('register', 'submit', 'Register' );
		$form->addInputClass('btnSubmit');

	$form->addField('reset', 'reset', 'Reset form');
		$form->addInputClass('btnReset');

	$form->addContent('<div class="reset"><a href="'.$config->get('web', 'root').'/login/" class="btnReset fleft">Cancel &amp; back to Login</a></div>');

	echo $form->submiterrormsg.$form->display();

include(DOCROOT.'/skin/loginfooter.php');
?>