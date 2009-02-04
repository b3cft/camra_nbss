<?php
/**
 * Last updated $Date: 2007-03-09 13:02:59 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 326 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/admin/index.php $
 **/
$title='Admin';
$access = array('admin','sysadmin');
include('../includes/base.php');
include(DOCROOT.'/skin/header.php');
if ( $request->get('get', 'msg') )
{
	print('<p class="homehead">'.$request->get('get', 'msg').'</p>');
}
?>
	<h2>Admin Functions</h2>
	<dl>
		<dt>Breweries/Beers</dt>
			<dd><a href="<?php echo $config->get('web', 'root')?>/admin/breweries/">Manage Breweries</a></dd>
			<dd><a href="<?php echo $config->get('web', 'root')?>/admin/beers/">Manage Beers</a></dd>
		<dt>Pubs</dt>
			<dd><a href="<?php echo $config->get('web', 'root')?>/admin/pubs/">Manage Pubs</a></dd>
			<dd><a href="<?php echo $config->get('web', 'root')?>/admin/pubs/newlandlord/">Change of Landlord</a></dd>
		<dt>Members</dt>
			<dd><a href="<?php echo $config->get('web', 'root')?>/admin/members/">Add/Edit Members</a></dd>
			<dd><a href="<?php echo $config->get('web', 'root')?>/admin/members/review/">Review Members</a></dd>
		<dt>Town/Village</dt>
			<dd><a href="<?php echo $config->get('web', 'root')?>/admin/towns/">Manage Town/Village</a></dd>
	</dl>
<?php
include(DOCROOT.'/skin/footer.php');
?>