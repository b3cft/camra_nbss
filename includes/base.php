<?php
/**
 * Last updated $Date: 2007-04-05 09:27:18 +0100 (Thu, 05 Apr 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 353 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/includes/base.php $
 **/

ob_start('ob_gzhandler');
define('DOCROOT', dirname(__FILE__).'/..');
define('TAB',chr(9));
define('CR',chr(10));
define('CRLF',chr(10).chr(13));
include(DOCROOT.'/VERSION');
include(DOCROOT.'/includes/Config.class.php');
if (!is_file(DOCROOT.'/config.php'))
{
	header('Location: ./install/');
	exit;
}
include(DOCROOT.'/config.php');
include(DOCROOT.'/includes/Request.class.php');
include(DOCROOT.'/includes/Session.class.php');
include(DOCROOT.'/includes/Form.class.php');
if (false === defined('NOSESSION'))
{
    $user = Session::get('user');
}
$request = Request::getInstance();
set_exception_handler('exceptionHandler');
set_error_handler('errorHandler');
checkLoggedIn();
checkPermissions();

/**
 * Custom Exception Handler
 *
 * @param Exception $exception
 */
function exceptionHandler(Exception $exception)
{
	printError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTraceAsString());
}

/**
 * Custom Error Handler
 *
 * @param integer $code
 * @param string $string
 * @param string $file
 * @param $string $line
 */
function errorHandler($code, $string, $file, $line) {
	if (!in_array($code, array(E_WARNING, E_NOTICE, E_STRICT)))
	{
		printError($code, $string, $file, $line, debug_backtrace());
	}
}

/**
 * Error Logger and handler
 *
 * @param string $code
 * @param string $string
 * @param string $file
 * @param string $line
 * @param string $trace
 */
function printError($code, $string, $file, $line, $trace)
{
	global $config, $user, $request;
	$log = fopen($config->get('error', 'logfile'), 'a');

	$errorTxt = "---------------\n";
	$errorTxt.= 'Date: '.date('Y-m-d H:i:s');
	$errorTxt.= "\nPath:".$request->get('uri', 'path');
	$errorTxt.= "\nMesg: ".$string;
	$errorTxt.= "\nErr Code: ".$code;
	$errorTxt.= "\nFile: ".$file;
	$errorTxt.= "\nLine: ".$line;
	$errorTxt.= "\nTrace:\n------\n".$trace."\n------";
	if ( sizeof($request->get('get')) )
	{
		$errorTxt.= "\nGet:\n------";
		foreach ( $request->get('get') as $key=>$value )
		{
			$errorTxt.= "\n['{$key}']=>'{$value}'";
		}
		$errorTxt.= "\n------";
	}
	if ( sizeof($request->get('post')) )
	{
		$errorTxt.= "\nPost:\n------";
		foreach ( $request->get('post') as $key=>$value )
		{
			$errorTxt.= "\n['{$key}']=>'{$value}'";
		}
		$errorTxt.= "\n------";
	}
	$errorTxt.= "\n---------------\n\n";
	fwrite($log, $errorTxt);
	fclose($log);

	print <<<EOT
	<h2>Whoops</h2>
	<p>We seem to have encountered an error.</p>
	<p>Your sysadmin should be aware and sort this out!<p>
	</div>
EOT;
	if ( $config->get('error', 'showInPage') )
	{
		print <<<EOT
<pre>
$errorTxt
</pre>
EOT;
	}
	if ( is_null($user) )
	{
		include(DOCROOT.'/skin/loginfooter.php');
	}
	else
	{
		include(DOCROOT.'/skin/footer.php');
	}
	if ( strlen($config->get('error', 'mailto')) )
	{
		$headers ='From: No Reply <errors@'.$_SERVER['HTTP_HOST'].'>'.CRLF;
		$subject ='Site error at '.$_SERVER['HTTP_HOST'];
		$content ='There has been a site error:'.CR.CR;
		$content.=$errorTxt.CR;
		$content.='From IP address: '.$_SERVER['REMOTE_ADDR'].CR;
		$content.='On: '.date('D jS M Y H:i:s');
		$content.=CR.CR.'Thank you.';
		mail($config->get('error', 'mailto'),$subject,$content,$headers);
	}
}

/**
 * Return DB PDO connection, creates it if not already present.
 *
 * @return PDO
 */
function getDBConnection()
{
	static $dbConn;
	global $config;
	if (is_null($dbConn))
	{
		$dbConn = new PDO($config->get('database', 'connectString'), $config->get('database', 'username'), $config->get('database', 'password'), array(
	  		PDO::ATTR_PERSISTENT => true,
	  		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		));

	}
	return $dbConn;
}

/**
 * Check user is logged in or redirect to login page
 *
 */
function checkLoggedIn()
{
	global $user, $request, $config;
	/* Check for logged in user */
	if ( is_null($user) && !in_array($request->pathPeek(), array('login', 'signup', 'api', 'down')) )
	{
		header('Location: '.$config->get('web','root').'/login/');
		exit;
	}
}

/**
 * Check user has permissions to view current page
 *
 */
function checkPermissions()
{
	global $request, $config, $access, $user;
	/* check for permissions to view */
	if ( !in_array($request->pathPeek(), array('login', 'denied', 'signup', 'api', 'down')) && (!isset($access) || !is_array($access)))
	{
		throw new Exception('Shoot the coder!, access permissions not defined', E_RECOVERABLE_ERROR);
	}
	elseif ( !in_array($request->pathPeek(), array('login', 'denied', 'signup', 'api')) && false === in_array($user['type'], $access) )
	{
		header('Location: '.$config->get('web','root').'/denied/');
	}
}

/**
 * Hash a string
 *
 * @param string $string
 * @return string
 */
function toBase36($string)
{
	$string = strrev($string);
	$len = strlen($string);
	$Data = '';
	for ( $i=0 ; $i<$len ; $i++ )
	{
		$Data .= sprintf("%02x",ord(substr($string, $i, 1)));
	}
	$DataHexChunks = str_split($Data, 10);
	$Convert36 = create_function('$a', 'return base_convert($a, 16, 36);');
	$DataB36Chunks = array_map($Convert36, $DataHexChunks);
	return implode('', $DataB36Chunks);
}

/**
 * Un Hash a string
 *
 * @param string $string
 * @return string
 */
function fromBase36($string)
{
	$DataB36Chunks = str_split($string, 8);
	$Convert16 = create_function('$a', 'return base_convert($a, 36, 16);');
	$DataHexChunks = array_map($Convert16, $DataB36Chunks);
	$string = implode('', $DataHexChunks);
	$len = strlen($string);
	$Data = '';
	for ( $i=0 ; $i<$len ; $i+=2 )
	{
		$Data .= chr(hexdec(substr($string, $i, 2)));
	}
	return strrev($Data);
}

/**
 * check if a data is serialized or not
 *
 * @param mixed $data
 * @return boolean
 */
function is_serialized($data){
   if (trim($data) == "") {
      return false;
   }
   if (preg_match("/^(i|s|a|o|d)(.*);/si",$data)) {
      return true;
   }
   return false;
}

/**
 * Debug printing helper
 *
 * @param mixed $var
 */
function debugPrint($var){
	print('<pre>');
	print_r($var);
	Print('</pre>');
}

/**
 * Database wrapper/helper function
 *
 * @param string $sql
 * @return mixed
 */
function getQueryResults($sql){
	$dbConn = getDBConnection();
	$query = $dbConn->query($sql);
	$err = $dbConn->errorInfo();
	if ( 3==sizeof($err) && '00000' != $err[0] )
	{
		throw new Exception($err[2], $err[1]);
	}
	$result = true;
	try
	{
	 $result = $query->fetchAll(PDO::FETCH_ASSOC);
	}
	catch (PDOException $e)
	{
	  return;
  }
	return $result;
}

/**
 * Select a specific item from array based on a subkey
 *
 * @param mixed $item_id
 * @param array $array
 * @param mixed $key
 * @return mixed
 */
function getItemFromArray($item_id, $array, $key='id') {
	foreach ($array as $arrayItem)
	{
		if ($arrayItem[$key] == $item_id)
		{
			return $arrayItem;
		}
	}
}

/**
 * Remove an item from an array based on a subkey
 *
 * @param mixed $item_id
 * @param array $array
 * @param mixed $key
 */
function removeItemFromArray($item_id, &$array, $key='id') {
	$temp=array();
	foreach ($array as $element)
	{
		if ($element[$key] !=  $item_id)
		{
			$temp[] = $element;
		}
	}
	$array = $temp;
}

/**
 * Store a review in the Database
 *
 * @todo update to use prepared statements in PDO::
 *
 * @param array $postdata
 */
function storeReview($postdata){
	global $config;
	$data=array();
	$data['reviewed']='\''.date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$postdata['date'],date('Y'))).'\'';
	$data['reviewer_id']= $postdata['reviewer_id']!='' ? $postdata['reviewer_id'] : 'NULL';
	$data['reviewer']= $postdata['reviewer']=='' ? 'NULL' : '\''.$postdata['reviewer'].'\'';
	$data['reviewer_email']=$postdata['reviewer_email']=='' ? 'NULL' : '\''.$postdata['reviewer_email'].'\'';
	$data['camra']=isset($postdata['camra']) ? $postdata['camra'] : 0;
	$data['pub_id']=$postdata['pub_id'];
	$data['nora']=isset($postdata['nora']) ? $postdata['nora'] : 0;
	$data['beer_id']=$postdata['beer_id']!=0 ? $postdata['beer_id'] : 'NULL';
	$data['beer']=$postdata['beer']=='' ? 'NULL' : '\''.str_replace("'","\'",$postdata['beer']).'\'';
	$data['rating_id']=$postdata['rating_id']=='' ? 'NULL' : $postdata['rating_id'];
	$data['notes']='\''.str_replace("'","\'",$postdata['notes']).'\'';

	$values=implode(',',$data);
	$columns='`'.implode('`,`',array_keys($data)).'`';

	$sql ='INSERT INTO `'.$config->get('database', 'tablePrefix').'review` ('.$columns.') VALUES ('.$values.')';
	//print($sql);
	getQueryResults($sql);
}

/**
 * Archive reviews for a specific pub from a date
 *
 * @todo update to PDO::
 * @param integer $pub_id
 * @param string $date
 */
function archiveReviews($pub_id, $date)
{
	global $config;
	$prefix = $config->get('database', 'tablePrefix');
	$sql = <<<EOQ
UPDATE {$prefix}review
SET archived = '{$date}'
WHERE pub_id = {$pub_id}
AND reviewed <=  '{$date}'
AND archived IS NULL
EOQ;
	getQueryResults($sql);
}

/**
 * Check users login credentials
 *
 * @todo move to PDO:: prepare execute
 * @param string $memberno
 * @param string $postcode
 * @return array or false on failure
 */
function getUserLogin($memberno, $postcode)
{
	global $config;
	$postcode = strtoupper($postcode);
	$prefix = $config->get('database', 'tablePrefix');
	$sql = <<<EOD
SELECT *
FROM {$prefix}user
WHERE camra_number = '{$memberno}'
AND postcode = '{$postcode}'
AND active = 1
AND verified = 1
EOD;
	$users = getQueryResults($sql);
	if (sizeof($users)==1)
	{
		return reset($users);
	}
	else
	{
		return false;
	}
}

/**
 * Return an array of user who have submitted reviews.
 *
 * @return array
 */
function getUsersWithReviews()
{
	global $config;
	return getQueryResults('SELECT DISTINCT user.id, user.firstname, user.lastname FROM '.$config->get('database', 'tablePrefix').'user user, '.$config->get('database', 'tablePrefix').'review review WHERE user.id=review.reviewer_id ORDER BY lastname, firstname');
}

/**
 * Return users last submissions
 *
 * @param integer $num
 * @param integer $reviewer
 * @param integer $start
 * @param integer $stop
 * @return array
 */
function lastSubmissions($num=10,$reviewer=0,$start='',$stop=''){
	global $config;
	if(strlen($start) && strlen($stop)){
		if ($start<$stop){
			$tmp=$start;
			$start=$stop;
			$stop=$tmp;
		}
		$startdate='\''.date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$start,date('Y'))).'\'';
		$stopdate='\''.date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$stop,date('Y'))).'\'';
		$date=true;
	}else{
		$date=false;
	}
	$sql='SELECT review.id, review.reviewed AS reviewed, user.firstname, user.lastname, town.name AS townname, pub.name AS pubname, brewery.name AS breweryname, beer.name AS beername, review.beer, rating.name as rating, review.nora, review.notes ';
	$sql.='FROM '.$config->get('database', 'tablePrefix').'town town ';
	$sql.='INNER JOIN ('.$config->get('database', 'tablePrefix').'user user RIGHT JOIN ('.$config->get('database', 'tablePrefix').'rating rating RIGHT JOIN ('.$config->get('database', 'tablePrefix').'pub pub INNER JOIN ('.$config->get('database', 'tablePrefix').'brewery brewery RIGHT JOIN ('.$config->get('database', 'tablePrefix').'beer beer RIGHT JOIN '.$config->get('database', 'tablePrefix').'review review ON beer.id = review.beer_id) ON brewery.id = beer.brewery_id) ON pub.id = review.pub_id) ON rating.id = review.rating_id) ON user.id = review.reviewer_id) ON town.id = pub.town_id ';
	$sql.='WHERE 1=1 ';
	$sql.= $reviewer>0 ? 'AND (user.id = '.$reviewer.') ' : '';
	$sql.= $date ? 'AND (review.reviewed BETWEEN '.$startdate.' AND '.$stopdate.') ' : '';
	$sql.='ORDER BY review.reviewed DESC';
	$sql.=$num>0 ? ' LIMIT '.$num : '';
	$sql.=';';
	return getQueryResults($sql);
}

/**
 * Get Pub statistics
 *
 * @param string $sortcol
 * @param integer $sortorder
 * @param integer $limit
 * @param integer $start
 * @param integer $stop
 * @param integer $town_id
 * @param integer $min_dates
 * @param integer $min_reviewers
 * @param integer $min_avg
 * @return array
 */
function getPubStats($sortcol='avg_score', $sortorder=SORT_DESC, $limit=0, $start='', $stop='', $town_id='', $min_dates=0, $min_reviewers=0, $min_avg=0){
	global $config;
	if(strlen($start) && strlen($stop)){
		if ($start<$stop){
			$tmp=$start;
			$start=$stop;
			$stop=$tmp;
		}
		$startdate='\''.date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$start,date('Y'))).'\'';
		$stopdate='\''.date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$stop,date('Y'))).'\'';
		$date=true;
	}else{
		$date=false;
	}

	$sql ='SELECT MAX(review.reviewed) AS lastvisit, town.name AS town, pub.id AS pub_id, pub.name AS pub, AVG(rating.score) AS avg_score, MAX(rating.score) AS max_score, MIN(rating.score) AS min_score, VARIANCE(rating.score) as var_score, COUNT(rating.id) AS reviews, COUNT(DISTINCT review.reviewed) AS dates, COUNT(DISTINCT review.reviewer_id) AS reviewers ';
	$sql.='FROM '.$config->get('database', 'tablePrefix').'town town, '.$config->get('database', 'tablePrefix').'pub pub, '.$config->get('database', 'tablePrefix').'rating rating, '.$config->get('database', 'tablePrefix').'review review ';
	$sql.='WHERE rating.id=review.rating_id ';
	$sql.='AND pub.id=review.pub_id ';
	$sql.='AND town.id=pub.town_id ';
	$sql.= $town_id=='' ? '' : 'AND town.id='.$town_id.' ';
	$sql.= $date ? 'AND (review.reviewed BETWEEN '.$startdate.' AND '.$stopdate.') ' : '';
	$sql.= 'AND archived IS NULL ';
	$sql.='GROUP BY town.name, pub.name';
	if ($min_avg!=0 || $min_dates!=0 || $min_reviewers!=0)
	{
		$having = array();
		if ($min_dates>0) {$having[] = 'dates>='.$min_dates;}
		if ($min_avg>0) {$having[] = 'avg_score>='.$min_avg;}
		if ($min_reviewers>0) {$having[] = 'reviewers>='.$min_reviewers;}
		$sql.=' HAVING '.implode(' AND ',$having);
	}
	$results=getQueryResults($sql);
	$disporder=array();

	foreach ($results as $pub){
		$disporder[]=$pub[$sortcol];
	}
	array_multisort($disporder, $sortorder, $results);
	if ($limit>0 && sizeof($results)>$limit){
		$results=array_slice($results,0,$limit);
	}
	return $results;
}

/**
 * Get in depth Pub statistics
 *
 * @param string $sortcol
 * @param integer $sortorder
 * @param integer $start
 * @param integer $stop
 * @param integer $pub_id
 * @return array
 */
function getPubStatsInDepth($pub_id, $type='mom', $sortcol='date', $sortorder=SORT_DESC, $prevLL=0){
	global $config;
	if ('mom'==$type)
	{
		$sql ='SELECT CONCAT( LEFT( review.reviewed, 7 ), \'-01\') AS date';
	}
	else
	{
		$sql ='SELECT CONCAT( LEFT( review.reviewed, 4 ), \'-01-01\') AS date';
	}
	$sql.=', AVG(rating.score) AS avg_score, MAX(rating.score) AS max_score, MIN(rating.score) AS min_score, VARIANCE(rating.score) AS var_score, COUNT(rating.id) AS reviews, COUNT(DISTINCT review.reviewed) AS dates, COUNT(distinct review.reviewer_id) AS reviewers ';
	$sql.='FROM '.$config->get('database', 'tablePrefix').'town town, '.$config->get('database', 'tablePrefix').'pub pub, '.$config->get('database', 'tablePrefix').'rating rating, '.$config->get('database', 'tablePrefix').'review review ';
	$sql.='WHERE rating.id=review.rating_id ';
	$sql.='AND pub.id=review.pub_id ';
	$sql.='AND town.id=pub.town_id ';
	$sql.='AND pub.id='.$pub_id.' ';
	$sql.= $prevLL ? '' : 'AND archived IS NULL ';
	$sql.='GROUP BY date';

	$results=getQueryResults($sql);
	$disporder=array();

	foreach ($results as $pub){
		$disporder[]=$pub[$sortcol];
	}
	array_multisort($disporder, $sortorder, $results);
	if ($limit>0 && sizeof($results)>$limit){
		$results=array_slice($results,0,$limit);
	}
	return $results;
}

/**
 * Get statistics for Reviewer
 *
 */
function getReviewerStats($reviewer_id, $sortcol='avg_score', $sortorder=SORT_DESC, $limit, $start='', $stop='')
{
	global $config;
	if(strlen($start) && strlen($stop)){
		if ($start<$stop){
			$tmp=$start;
			$start=$stop;
			$stop=$tmp;
		}
		$startdate='\''.date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$start,date('Y'))).'\'';
		$stopdate='\''.date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$stop,date('Y'))).'\'';
		$date=true;
	}else{
		$date=false;
	}

	$sql ='SELECT Max(review.reviewed) as lastvisit, town.name AS town, pub.name AS pub, Avg(rating.score) AS avg_score, Max(rating.score) AS max_score, Min(rating.score) AS min_score, variance(rating.score) as var_score, Count(rating.id) AS reviews, Count(distinct review.reviewed) AS dates ';
	$sql.='FROM '.$config->get('database', 'tablePrefix').'town town, '.$config->get('database', 'tablePrefix').'pub pub, '.$config->get('database', 'tablePrefix').'rating rating, '.$config->get('database', 'tablePrefix').'review review ';
	$sql.='WHERE rating.id=review.rating_id ';
	$sql.='AND review.reviewer_id='.$reviewer_id;
	$sql.=' AND pub.id=review.pub_id ';
	$sql.='AND town.id=pub.town_id ';
	$sql.= $date ? 'AND (review.reviewed BETWEEN '.$startdate.' AND '.$stopdate.') ' : '';
	$sql.= 'AND archived IS NULL ';
	$sql.='GROUP BY town.name, pub.name';
	$results=getQueryResults($sql);
	$disporder=array();

	foreach ($results as $pub){
		$disporder[]=$pub[$sortcol];
	}
	array_multisort($disporder, $sortorder, $results);
	if ($limit>0 && sizeof($results)>$limit){
		$results=array_slice($results,0,$limit);
	}
	return $results;
}
?>
