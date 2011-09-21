<?php
/**
 * Last updated $Date: 2007-03-09 13:02:59 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 326 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/history/index.php $
 **/
$title='Drinking History';
$access = array('reviewer','superreviewer','admin','sysadmin');
include('../includes/base.php');
include(DOCROOT.'/skin/header.php');

$show = isset($_REQUEST['show']) ? $_REQUEST['show'] : 10;
$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : '';
$stop = isset($_REQUEST['stop']) ? $_REQUEST['stop'] : '';
$reviewer_id = $user['id'];
$results=lastSubmissions($show,$reviewer_id,$start,$stop);
?>
<form method="post" action="<?php echo $config->get('web', 'root')?>/history/" id="resultform">
	<input type="hidden" name="sortcol" value="<?php print($sortcol);?>" />
	<input type="hidden" name="sortorder" value="<?php print($sortorder);?>" />
	Show: <select name="show">
		<option value="0"<?php if($show==0){print(' selected="selected"');}?>>All&nbsp;&nbsp;</option>
		<option value="3"<?php if($show==3){print(' selected="selected"');}?>>3</option>
		<option value="10"<?php if($show==10){print(' selected="selected"');}?>>10</option>
		<option value="25"<?php if($show==25){print(' selected="selected"');}?>>25</option>
		<option value="50"<?php if($show==50){print(' selected="selected"');}?>>50</option>
	</select>
	results, between <select name="start">
		<option value=""></option>
<?php
		for ($i=0 ; $i<=365 ; $i++){
			$selected = $start!='' && $start==$i ? ' selected="selected"' : '';
			print(TAB.TAB.TAB.TAB.'<option value="'.$i.'"'.$selected.'>'.date('d/M/Y',mktime(0,0,0,date('m'),date('d')-$i,date('Y'))).'</option>'.CR);
		}
?>
	</select>
	and <select name="stop">
		<option value=""></option>
<?php
		for ($i=0 ; $i<=365 ; $i++){
			$selected = $stop!='' && $stop==$i ? ' selected="selected"' : '';
			print(TAB.TAB.TAB.TAB.'<option value="'.$i.'"'.$selected.'>'.date('d/M/Y',mktime(0,0,0,date('m'),date('d')-$i,date('Y'))).'</option>'.CR);
		}
?>
	</select>
	<input type="submit" name="_submit" value="go" />
</form>
<h2>Drinking history for <?php print($user['firstname'].' '.$user['lastname']);?></h2>
<table>
	<caption>Submissions</caption>
	<thead>
		<tr>
			<!--<th scope="col">Action</th>-->
			<th scope="col">Date</th>
			<th scope="col">Town</th>
			<th scope="col">Pub</th>
			<th scope="col">Brewery</th>
			<th scope="col">Beer</th>
			<th scope="col">Rating</th>
			<th scope="col">Notes</th>
		</tr>
	</thead>
	<tbody>
<?php
		foreach($results as $result){
			print(TAB.TAB.'<tr>'.CR);
			//print(TAB.TAB.TAB.'<td><a href="'.$config->get('web', 'root').'/history/edit/?id='.$result['id'].'" title="Edit entry">E</a>&nbsp;|&nbsp;<a href="'.$config->get('web', 'root').'/history/delete/?id='.$result['id'].'" title="Delete Entry">X</a></td>');
			print(TAB.TAB.TAB.'<td>'.date('D, d/M/Y',strtotime($result['reviewed'])).'</td>'.CR);
			print(TAB.TAB.TAB.'<td>'.$result['townname'].'</td>'.CR);
			print(TAB.TAB.TAB.'<td>'.$result['pubname'].'</td>'.CR);
			if (1 == $result['nora'])
			{
				print(TAB.TAB.TAB.'<td colspan="3">No Real Ale Available</td>'.CR);
			}
			else
			{
				print(TAB.TAB.TAB.'<td>'.$result['breweryname'].'</td>'.CR);
				print(TAB.TAB.TAB.'<td>'.(strlen($result['beer']) ? $result['beer'] : $result['beername']).'</td>'.CR);
				print(TAB.TAB.TAB.'<td>'.$result['rating'].'</td>'.CR);
			}
			print(TAB.TAB.TAB.'<td>'.$result['notes'].'</td>'.CR);
			print(TAB.TAB.'</tr>'.CR);
		}
?>
	</tbody>
</table>
<?php
include(DOCROOT.'/skin/footer.php');
?>