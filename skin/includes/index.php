<?php
/**
 * Last updated $Date: 2007-03-01 00:32:53 +0000 (Thu, 01 Mar 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 303 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/skin/includes/index.php $
 **/
$file = isset($_GET['uri']) ? $_GET['uri'] : null;
if (!is_null($file) && is_file($file) ) {
	$fileBits = pathinfo($file);
	// GZip compress and cache it for 10 days
	$expiresOffset = 3600 * 24 * 1000;		// 1000 days util client cache expires
	ob_start ("ob_gzhandler");

	if ($fileBits['extension'] == 'js') {
		header("Content-type: text/javascript; charset: UTF-8");
	}else{
		header("Content-type: text/css; charset: UTF-8");
	}
	header("Cache-Control: private");
	header("Expires: " . gmdate("D, d M Y H:i:s", time() + $expiresOffset) . " GMT");
	readfile($file);
	ob_end_flush();
}else{
	header('HTTP/1.0 404 Not Found');
}
?>