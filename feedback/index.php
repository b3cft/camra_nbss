<?php
/**
 * Last updated $Date: 2007-04-05 09:27:49 +0100 (Thu, 05 Apr 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 354 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/feedback/index.php $
 **/
$title='Feedback';
$access = array('reviewer','superreviewer','admin','sysadmin');
include('../includes/base.php');
include(DOCROOT.'/skin/header.php');
if (strlen($config->get('web', 'feedbackEmail')))
{
?>
	<h2>Feedback</h2>
	<p>Want a new feature? Found a new pub?</p>
	<p>New Landlord? Missing beers or we missed a town?</p>
	<p>Give us some feedback and we'll get on it</p>
<?php
	$form = new Form('feedbackform');
	$form->class = 'neoAdminForm';

	if ( $form->submitted && 0==$form->submiterrors )
	{
		$headers ='From: No Reply <feedback@'.$_SERVER['HTTP_HOST'].'>'.CRLF;
		$subject ='Feedback on '.$_SERVER['HTTP_HOST'];
		$content ='There has been new feedback'.CR.CR;
		$content.='From: '.$user['firstname'].' '.$user['lastname'].CR;
		$content.='About:  '.$request->get('post', 'about').CR;
		$content.='Message:'.CR;
		$content.='--------------------------------------'.CR;
		$content.=$request->get('post', 'message').CR;
		$content.='--------------------------------------'.CR;
		$content.='Post from IP address: '.$_SERVER['REMOTE_ADDR'].CR;
		$content.='On: '.date('D jS M Y H:i:s');
		$content.=CR.CR.'Thank you.';
		mail($config->get('web', 'feedbackEmail'),$subject,$content,$headers);
		header('Location: '.$config->get('web', 'root').'/feedback/thankyou/');
		exit;
	}

	$form->addFieldsetOpen('Feedback');
		$form->addContent('<div><p class="fieldSubstitute"><span class="label">From:</span><span class="input">'.$user['firstname'].' '.$user['lastname'].'</span></p></div>');
		$form->addField('about', 'select');
			$form->addLabel('About');
			$form->addOptions(array(''=>'Select a subject', 'missbeer'=>'Missing Beer', 'missbrewery'=>'Missing Brewery', 'misspubs'=>'Missing Pub', 'misstowns'=>'Missing Town', 'newlandlord'=>'New Landlords', 'suggestion'=>'NBSS Suggestions', 'other'=>'Other'));
			$form->addFormValidationErrorMessage('required');

		$form->addField('message', 'textarea');
			$form->addLabel('Text');
			$form->addFormValidationErrorMessage('required');

		$form->addField('send', 'submit', 'Send');
			$form->addInputClass('btnSubmit');

		$form->addField('reset', 'reset', 'Clear');
			$form->addInputClass('btnReset');

	$form->addFieldsetClose();

	print $form->display();
}
else
{
?>
	<h2>Feedback disabled</h2>
	<p>The administrator has disabled feedback for this site.</p>
<?php
}
include(DOCROOT.'/skin/footer.php');
?>