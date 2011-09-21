<?php
/**
 * Last updated $Date: 2007-04-11 15:47:41 +0100 (Wed, 11 Apr 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 356 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/login/index.php $
 **/
$title='Login';
include('../includes/base.php');
include(DOCROOT.'/skin/header.php');

	$form = new Form('nbssv2login');
	$form->class = 'neoAdminForm';
	$form->validationerrormsg = 'The following fields must be completed:';

	if ($form->submitted && $form->submiterrors==0)
	{
		$user = getUserLogin(md5(strtoupper(trim(ltrim($request->get('post', 'membershipno'),'0')))), md5(strtoupper(str_replace(' ', '', $request->get('post', 'postcode')))));
		if ($user !== false)
		{
			Session::set('user', $user);
			Session::set('welcomed', false);
			getQueryResults('UPDATE '.$config->get('database', 'tablePrefix').'user SET lastlogin='.gmdate('YmdHis',time()).' WHERE id='.$user['id']);
			header('Location: '.$config->get('web','root').'/');
			exit;
		}
		else
		{
			$form->submiterrormsg .= 'Invalid membership number, postcode or inactive account.';
		}
	}

	$form->addFieldsetOpen('Login');

	$form->addField( 'membershipno', 'text', $request->get('post', 'membershipno') );
		$form->addLabel('Membership Number');
		$form->addFieldValidation('required');
		$form->addHelp('Please use the membership number from your CAMRA membership card.');

	$form->addField( 'postcode', 'text', $request->get('post', 'postcode') );
		$form->addLabel('Postcode');
		$form->addFieldValidation('required');
		$form->addHelp('Enter your postcode from your normal residence (You know, where CAMRA sends your &quot;What\'s Brewing&quot;.)');

	$form->addFieldsetClose();

	$form->addField( 'login', 'submit', 'Login' );
		$form->addInputClass('btnSubmit');

	$form->addContent('<div class="reset"><a href="'.$config->get('web','root').'/signup/" class="btnSubmit fleft" title="sign up for an account">Sign up for an account</a></div>');
	echo $form->submiterrormsg.$form->display();

include(DOCROOT.'/skin/loginfooter.php');
?>
