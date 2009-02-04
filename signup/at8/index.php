<?php
/**
 * Last updated $Date: 2007-03-09 15:18:51 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 333 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/signup/at8/index.php $
 **/
$title = 'Signup : Activation';
include('../../includes/base.php');
include(DOCROOT.'/skin/header.php');

$member = getQueryResults('SELECT * FROM '.$config->get('database', 'tablePrefix').'user WHERE camra_number=\''.$request->get('get', 'v').'\'');
if (1==sizeof($member))
{
	$member = reset($member);
	getQueryResults('UPDATE '.$config->get('database', 'tablePrefix').'user SET verified=1 WHERE id='.$member['id']);
	if (strlen($config->get('web', 'signupEmail')))
	{
		$headers ='From: No Reply <signup@'.$_SERVER['HTTP_HOST'].'>'.CRLF;
		$subject ='Account activated at '.$_SERVER['HTTP_HOST'];
		$content ='There has been a new account activation.'.CR.CR;
		$content.='Firstname: '.$member['firstname'].CR;
		$content.='Lastname: '.$member['lastname'].CR;
		$content.='Email: '.$member['email'].CR;
		$content.='Auto Activated: '.($config->get('web', 'signupAutoEnable') ? 'Yes' : 'No').CR;
		$content.='Activated from IP address: '.$_SERVER['REMOTE_ADDR'].CR;
		$content.='On: '.date('D jS M Y H:i:s');
		$content.=CR.CR.'Thank you.';
		mail($config->get('web', 'signupEmail'),$subject,$content,$headers);
	}
?>
	<h2>Thank you</h2>
	<p>You have just activated your account.</p>
<?php
	if ( $config->get('web', 'signupAutoEnable') )
	{
		Session::set('user', $member);
		Session::set('welcomed', false);
		getQueryResults('UPDATE '.$config->get('database', 'tablePrefix').'user SET active=1, lastlogin='.gmdate('YmdHis',time()).' WHERE id='.$member['id']);
?>
	<p>You can now go and submit your scores.</p>
	<p><a href="<?php echo $config->get('web','root')?>/" class="btnSubmit">Click here to get going.</a></p>
<?php
	}
	else
	{
?>
	<p>Unfortunately the administator of this site has opted to manually authorise all signups.</p>
	<p>The site administrator has been notified and you should be notified soon.</p>
<?php
	}
}
else
{

}
include(DOCROOT.'/skin/loginfooter.php');
?>