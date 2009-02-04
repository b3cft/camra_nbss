<?php
/**
 * Last updated $Date: 2007-03-07 10:42:18 +0000 (Wed, 07 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 316 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/results/raw/export/index.php $
 **/
$access = array('superreviewer','admin','sysadmin');
include('../../../includes/base.php');
if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT'])) {
	// IE Bug in download name workaround
	ini_set( 'zlib.output_compression','Off' );
}
$show = isset($_REQUEST['show']) ? $_REQUEST['show'] : 10;
$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : '';
$stop = isset($_REQUEST['stop']) ? $_REQUEST['stop'] : '';
$reviewer_id = $request->get('post', 'reviewer_id') ? $request->get('post', 'reviewer_id') : 0;
$results=lastSubmissions($show,$reviewer_id,$start,$stop);
$results=array_reverse($results);//want with oldest at the top.
//sort date for filename
if(strlen($start) && strlen($stop)){
	if ($start<$stop){
		$tmp=$start;
		$start=$stop;
		$stop=$tmp;
	}
	$startdate=date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$start,date('Y')));
	$stopdate=date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$stop,date('Y')));
	$date=true;
}else{
	$date=false;
}
//sort users for filename
if ($reviewer_id>0){
	$reviewer=getQueryResults('SELECT `lastname`,`firstname` FROM `'.$config->get('database', 'tablePrefix').'reviewer` WHERE `id`='.$reviewer_id);
	$filename=$reviewer[0]['lastname'].'_'.$reviewer[0]['firstname'];
}else{
	$filename='All';
}
if ($date){
	$filename.='-'.$startdate.'_to_'.$stopdate;
}else{
	$filename.='-All_Dates';
}
if ($show>0){
	$filename.='-Last_'.$show.'_Reviews';
}else{
	$filename.='-All_Reviews';
}
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Cache-Control: private');
header('Pragma: no-cache');
header('Pragma: public');
header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="'.$filename.'.csv"');
header('Accept-Ranges: bytes');
print('"Date","Reviewer","Town","Pub","Brewery","Beer","Rating","Notes"'.CR);
foreach($results as $result){
	print('"'.date('d/M/Y',strtotime($result['reviewed'])).'",');
	if ($result['lastname']==''){
		print('"'.$result['reviewer'].' '.$result['reviewer_email'].'",');
	}else{
		print('"'.$result['firstname'].' '.$result['lastname'].'",');
	}
	print('"'.$result['townname'].'",');
	print('"'.$result['pubname'].'",');
	print('"'.$result['breweryname'].'",');
	print('"'.(strlen($result['beer']) ? $result['beer'] : $result['beername']).'",');
	if (1 == $result['nora'] )
	{
		print('"No Real Ale",');
	}
	else
	{
		print('"'.$result['rating'].'",');
	}
	print('"'.$result['notes'].'"'.CR);
}
?>