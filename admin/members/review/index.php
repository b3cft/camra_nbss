<?php
/**
 * Last updated $Date: 2007-03-09 13:02:59 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 326 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/admin/members/review/index.php $
 **/
$title='Member Review';
$access = array('admin','sysadmin');
include('../../../includes/base.php');
include(DOCROOT.'/skin/header.php');
?>
<h2>Member Review</h2>
<?php
if ( $request->get('post', 'id') ) {$member_id = $request->get('post', 'id');}
elseif ( $request->get('get', 'id') ) {$member_id = $request->get('get', 'id');}
else {$member_id = '';}
$sortcol= isset($_REQUEST['sortcol']) ? $_REQUEST['sortcol'] : 'reviews';
$sortorder = $_REQUEST['sortorder']=='asc' ? SORT_ASC : SORT_DESC;
$show = isset($_REQUEST['show']) ? $_REQUEST['show'] : 10;
$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : '';
$stop = isset($_REQUEST['stop']) ? $_REQUEST['stop'] : '';
if ('' == $member_id)
{
	$members = getUsersWithReviews();
?>
	<form action="" method="get">
		<p>
		<label for="frm_member_id">Member to review</label>
		<select name="id" id="frm_member_id">
			<option value="">Selected a reviewer</option>
<?php
		foreach ($members as $member)
		{
			print('<option value="'.$member['id'].'">'.$member['lastname'].', '.$member['firstname'].'</option>');
		}
?>
		</select>
		<input type="submit" class="btnSubmit" value="Review" />
		</p>
	</form>
<?php
}
else
{
	$reviewer = reset(getQueryResults('SELECT * FROM '.$config->get('database', 'tablePrefix').'user WHERE id='.$member_id));
	$reviews = getReviewerStats($member_id, $sortcol, $sortorder, $show, $start, $stop);
?>
<form method="post" action="<?php echo $config->get('web', 'root')?>/admin/members/review/" id="resultform">
	<input type="hidden" name="sortcol" value="<?php print($sortcol);?>" />
	<input type="hidden" name="sortorder" value="<?php print($sortorder);?>" />
	<input type="hidden" name="id" value="<?php print($member_id);?>" />
	<p>
	Show: <select name="show">
		<option value="0"<?php if($show==0){print(' selected="selected"');}?>>All&nbsp;&nbsp;</option>
		<option value="10"<?php if($show==10){print(' selected="selected"');}?>>10</option>
		<option value="20"<?php if($show==20){print(' selected="selected"');}?>>20</option>
		<option value="30"<?php if($show==30){print(' selected="selected"');}?>>30</option>
		<option value="40"<?php if($show==40){print(' selected="selected"');}?>>40</option>
		<option value="50"<?php if($show==50){print(' selected="selected"');}?>>50</option>
	</select>
	results, between <select name="start">
		<option value=""></option>
<?php
		for ($i=0 ; $i<=365 ; $i++){
			$selected = $start!='' && $start==$i ? ' selected="selected"' : '';
			print('<option value="'.$i.'"'.$selected.'>'.date('d/M/Y',mktime(0,0,0,date('m'),date('d')-$i,date('Y'))).'</option>');
		}
?>
	</select>
	and <select name="stop">
		<option value=""></option>
<?php
		for ($i=0 ; $i<=365 ; $i++){
			$selected = $stop!='' && $stop==$i ? ' selected="selected"' : '';
			print('<option value="'.$i.'"'.$selected.'>'.date('d/M/Y',mktime(0,0,0,date('m'),date('d')-$i,date('Y'))).'</option>');
		}
?>
	</select>
	</p>
	<p>
		<input type="submit" name="_submit" class="btnSubmit" value="go" />
		<a href="./" class="btnReset">Go Back &amp; pick another reviewer</a>
	</p>
	<h2>Reviews for <?php print($reviewer['firstname'].' '.$reviewer['lastname']);?></h2>
</form>
<table>
	<caption>Reviewer Statistics (click on headings to sort)</caption>
	<thead>
		<tr>
<?php
			$headings=array('lastvisit'=>'Last Visit','town'=>'Town','pub'=>'Pub','min_score'=>'Min','max_score'=>'Max','avg_score'=>'Average','var_score'=>'Deviation','reviews'=>'Reviews','dates'=>'Dates');
			foreach ($headings as $key=>$value){
				if ($key==$sortcol){
					$order = $sortorder==SORT_DESC ? 'asc' : 'desc';
					$arrow = $sortorder==SORT_DESC ? '&uarr;' : '&darr;';
				}else{
					$order = $sortorder==SORT_DESC ? 'desc' : 'asc';
					$arrow = '';
				}
				print('<th scope="col"><a href="'.$config->get('web', 'root').'/admin/members/review/?sortcol='.$key.'&amp;sortorder='.$order.'&amp;show='.$show.'&amp;start='.$start.'&amp;stop='.$stop.'&amp;id='.$member_id.'">'.$value.$arrow.'</a></th>');
			}
?>
		</tr>
	</thead>
	<tbody>
<?php
		foreach($reviews as $result){
			print('<tr>');
			print('<td>'.date('d/M/Y',strtotime($result['lastvisit'])).'</td>');
			print('<td>'.$result['town'].'</td>');
			print('<td>'.$result['pub'].'</td>');
			print('<td>'.$result['min_score'].'</td>');
			print('<td>'.$result['max_score'].'</td>');
			print('<td>'.number_format($result['avg_score'], 3, '.', '').'</td>');
			print('<td>'.number_format($result['var_score'], 3, '.', '').'</td>');
			print('<td>'.$result['reviews'].'</td>');
			print('<td>'.$result['dates'].'</td>');
			print('</tr>'.CR);
		}
?>
	</tbody>
</table>
<?php
}

include(DOCROOT.'/skin/footer.php');
?>