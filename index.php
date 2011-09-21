<?php
/**
 * Last updated $Date: 2007-03-09 13:02:59 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 326 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/index.php $
 **/
$title = 'Scoring';
$access = array('reviewer','superreviewer','admin','sysadmin');
include('includes/base.php');
include(DOCROOT.'/skin/header.php');

if (!Session::get('welcomed'))
{
	print('<h2 class="welcome glow">Welcome back '.$user['firstname'].' '.$user['lastname'].'</h2>');
	print('<p>Your last login was on '.gmdate('D, d-M-Y, \a\t H:i', strtotime($user['lastlogin'])).' GMT</p>');
	Session::set('welcomed', true);
}
else
{
	print('<h2>North Herts CAMRA Beer Scoring</h2>');
}
$lastentries = lastSubmissions(3, $user['id']);
?>
<p class="homehead">To enter another review <a class="btnConfirm" href="<?php echo $config->get('web', 'root')?>/enter/">Click Here</a></p>
<table>
	<caption>Your last 3 entries are:</caption>
	<thead>
		<tr>
			<th scope="col">Date</th>
			<th scope="col">Town</th>
			<th scope="col">Pub</th>
			<th scope="col">Beer</th>
		</tr>
	</thead>
	<tbody>
<?php
	foreach($lastentries as $result){
		print(TAB.TAB.'<tr>'.CR);
		print(TAB.TAB.TAB.'<td>'.date('D, d/M/Y',strtotime($result['reviewed'])).'</td>'.CR);
		print(TAB.TAB.TAB.'<td>'.$result['townname'].'</td>'.CR);
		print(TAB.TAB.TAB.'<td>'.$result['pubname'].'</td>'.CR);
		if (1 == $result['nora'])
		{
			print(TAB.TAB.TAB.'<td>No Real Ale Available</td>'.CR);
		}
		else
		{
			print(TAB.TAB.TAB.'<td>'.$result['breweryname'].(strlen($result['beer']) ? $result['beer'] : ', '.$result['beername']).'</td>'.CR);
		}
		print(TAB.TAB.'</tr>'.CR);
	}
?>
	</tbody>
</table>
<?php
include(DOCROOT.'/skin/footer.php')
?>