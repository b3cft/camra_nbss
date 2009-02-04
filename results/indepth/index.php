<?php
/**
 * Last updated $Date: 2007-03-09 13:02:59 +0000 (Fri, 09 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 326 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/results/indepth/index.php $
 **/
$title='Results';
$access = array('superreviewer','admin','sysadmin');
include('../../includes/base.php');
include(DOCROOT.'/skin/header.php');

$sortcol= isset($_REQUEST['sortcol']) ? $_REQUEST['sortcol'] :'date';
$sortorder = $_REQUEST['sortorder']=='asc' ? SORT_ASC : SORT_DESC;
$type = $_REQUEST['type']=='yoy' ? 'yoy' : 'mom';
$pub_id = isset($_REQUEST['pub_id']) ? $_REQUEST['pub_id'] : 132;
$prevLL = $_REQUEST['prevLL']=='1' ? 1 : 0;
$stats = getPubStatsInDepth($pub_id, $type, $sortcol, $sortorder, $prevLL);
$pub = reset(getQueryResults('SELECT town.name as town_name, pub.name as pub_name FROM '.$config->get('database', 'tablePrefix').'pub pub, '.$config->get('database', 'tablePrefix').'town town WHERE pub.town_id=town.id AND pub.id='.$pub_id));
?>
<form method="post" action="<?php echo $config->get('web', 'root')?>/results/indepth/" id="resultform">
	<input type="hidden" name="sortcol" value="<?php print($sortcol);?>" />
	<input type="hidden" name="sortorder" value="<?php print($sortorder);?>" />
	<input type="hidden" name="pub_id" value="<?php print($pub_id);?>" />
	Type <select name="type">
<?php
		print('<option value="mom"'.($type=='mom' ? ' selected="selected"' : '').'>Month-on-Month</option>');
		print('<option value="yoy"'.($type=='yoy' ? ' selected="selected"' : '').'>Year-on-Year</option>');
?>
	</select>
	<label for="frm_prevLL">Include previous Landlords?</label><input type="checkbox" name="prevLL" id="frm_prevLL" value="1" <?php echo (1==$prevLL ? 'checked="checked"' : '')?>/>
	</p>
	<input type="submit" name="_submit" class="btnSubmit" value="go" />
</form>
<h2>Statistics for <?php echo $pub['pub_name'].', '.$pub['town_name']?></h2>
<table>
	<caption>Pub Statistics (click on headings to sort)</caption>
	<thead>
		<tr>
<?php
			$headings=array('date'=>'Date','min_score'=>'Min','max_score'=>'Max','avg_score'=>'Average','var_score'=>'Deviation','reviews'=>'Reviews','dates'=>'Dates','reviewers'=>'Reviewers');
			foreach ($headings as $key=>$value){
				if ($key==$sortcol){
					$order = $sortorder==SORT_DESC ? 'asc' : 'desc';
					$arrow = $sortorder==SORT_DESC ? '&uarr;' : '&darr;';
				}else{
					$order = $sortorder==SORT_DESC ? 'desc' : 'asc';
					$arrow = '';
				}
				print('<th scope="col"><a href="'.$config->get('web', 'root').'/results/indepth/?sortcol='.$key.'&amp;sortorder='.$order.'&amp;prevLL='.$prevLL.'&amp;pub_id='.$pub_id.'">'.$value.$arrow.'</a></th>');
			}
?>
		</tr>
	</thead>
	<tbody>
<?php
		foreach($stats as $result){
			print('<tr>');
			print('<td>'.date('M/Y',strtotime($result['date'])).'</td>');
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