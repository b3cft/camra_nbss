<?php
/**
 * Last updated $Date: 2007-03-09 13:02:59 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 326 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/results/index.php $
 **/
$title='Results';
$access = array('superreviewer','admin','sysadmin');
include('../includes/base.php');
include(DOCROOT.'/skin/header.php');

$sortcol= isset($_REQUEST['sortcol']) ? $_REQUEST['sortcol'] :'avg_score';
$sortorder = $_REQUEST['sortorder']=='asc' ? SORT_ASC : SORT_DESC;
$show = isset($_REQUEST['show']) ? $_REQUEST['show'] : 10;
$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : '';
$stop = isset($_REQUEST['stop']) ? $_REQUEST['stop'] : '';
$town_id = isset($_REQUEST['town_id']) ? $_REQUEST['town_id'] : '';
$min_dates = isset($_REQUEST['min_dates']) ? $_REQUEST['min_dates'] : 0;
$min_reviewers = isset($_REQUEST['min_reviewers']) ? $_REQUEST['min_reviewers'] : 0;
$min_avg = isset($_REQUEST['min_avg']) ? $_REQUEST['min_avg'] : 0;
$stats=getPubStats($sortcol, $sortorder, $show, $start, $stop, $town_id, $min_dates, $min_reviewers, $min_avg);
$towns=getQueryResults('SELECT * FROM '.$config->get('database', 'tablePrefix').'town WHERE active=1 ORDER BY name ASC;');
?>
<form method="post" action="<?php echo $config->get('web', 'root')?>/results/" id="resultform">
	<input type="hidden" name="sortcol" value="<?php print($sortcol);?>" />
	<input type="hidden" name="sortorder" value="<?php print($sortorder);?>" />
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
		Limit to
	</p>
	<p>
	Town:
	<select name="town_id">
		<option value="">All Towns</option>
<?php
		foreach ($towns as $town)
		{
			$selected = $town_id==$town['id'] ? ' selected="selected"' : '';
			print('<option value="'.$town['id'].'"'.$selected.'>'.$town['name'].'</option>');
		}
?>
	</select>
	Min Average:
	<select name="min_avg">
<?php
	$options = array(0,1,1.5,2,2.5,3,3.1,3.2,3.3,3.4,3.5,3.6,3.7,3.8,3.9,4,4.1,4.2,4.3);
	foreach ($options as $option)
	{
		$selected = $min_avg == $option ? ' selected="selected"' : '';
		print('<option value="'.$option.'"'.$selected.'>'.number_format($option,1,'.',',').'</option>');
	}
?>
	</select>
	Min Dates:
	<select name="min_dates">
<?php
	$options = array(0,5,10,15);
	foreach ($options as $option)
	{
		$selected = $min_dates == $option ? ' selected="selected"' : '';
		print('<option value="'.$option.'"'.$selected.'>'.$option.'</option>');
	}
?>
	</select>
	Min Reviewers:
	<select name="min_reviewers">
<?php
	$options = array(0,5,10,15);
	foreach ($options as $option)
	{
		$selected = $min_reviewers == $option ? ' selected="selected"' : '';
		print('<option value="'.$option.'"'.$selected.'>'.$option.'</option>');
	}
?>
	</select>
	</p>
	<input type="submit" name="_submit" class="btnSubmit" value="go" />
</form>
<p><a href="<?php echo $config->get('web', 'root')?>/results/raw/">Click here to see raw results</a></p>
<table>
	<caption>Pub Statistics (click on headings to sort)</caption>
	<thead>
		<tr>
<?php
			$headings=array('lastvisit'=>'Last Visit','town'=>'Town','pub'=>'Pub','min_score'=>'Min','max_score'=>'Max','avg_score'=>'Average','var_score'=>'Deviation','reviews'=>'Reviews','dates'=>'Dates','reviewers'=>'Reviewers');
			foreach ($headings as $key=>$value){
				if ($key==$sortcol){
					$order = $sortorder==SORT_DESC ? 'asc' : 'desc';
					$arrow = $sortorder==SORT_DESC ? '&uarr;' : '&darr;';
				}else{
					$order = $sortorder==SORT_DESC ? 'desc' : 'asc';
					$arrow = '';
				}
				print('<th scope="col"><a href="'.$config->get('web', 'root').'/results/?sortcol='.$key.'&amp;sortorder='.$order.'&amp;show='.$show.'&amp;start='.$start.'&amp;stop='.$stop.'&amp;town_id='.$town_id.'&amp;min_avg='.$min_avg.'&amp;min_dates='.$min_dates.'&amp;min_reviewers='.$min_reviewers.'">'.$value.$arrow.'</a></th>');
			}
?>
		</tr>
	</thead>
	<tbody>
<?php
		foreach($stats as $result){
			print('<tr>');
			print('<td>'.date('d/M/Y',strtotime($result['lastvisit'])).'</td>');
			print('<td>'.$result['town'].'</td>');
			print('<td><a href="./indepth/?pub_id='.$result['pub_id'].'">'.$result['pub'].'</a></td>');
			print('<td>'.$result['min_score'].'</td>');
			print('<td>'.$result['max_score'].'</td>');
			print('<td>'.number_format($result['avg_score'], 3, '.', '').'</td>');
			print('<td>'.number_format($result['var_score'], 3, '.', '').'</td>');
			print('<td>'.$result['reviews'].'</td>');
			print('<td>'.$result['dates'].'</td>');
			print('<td>'.$result['reviewers'].'</td>');
			print('</tr>'.CR);
		}
?>
	</tbody>
</table>
<?php
include(DOCROOT.'/skin/footer.php');
?>