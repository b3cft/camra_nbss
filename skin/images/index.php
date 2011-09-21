<?php
/**
 * Last updated $Date: 2007-02-14 08:11:08 +0000 (Wed, 14 Feb 2007) $
 * by $Author: andy $
 *
 * This file is $Revision: 280 $
 * $HeadURL: http://svn.b3cft.net/camra/nbss/version2/skin/images/index.php $
 **/
$file = isset($_GET['uri']) ? $_GET['uri'] : null;
if (!is_null($file) && is_file($file) ) {
	$fileBits = pathinfo($file);
	$expiresOffset = 3600 * 24 * 365 * 10;		// 10 years until client cache expires

	switch ($fileBits['extension'])
	{
		case 'png':
			header("Content-type: image/png");
			break;
		case 'gif':
			header("Content-type: image/gif");
			break;
		case 'jpe':
		case 'jpg':
		case 'jpeg':
			header("Content-type: image/jpeg");
			break;
	}
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expiresOffset) . ' GMT');
	header('Cache-Control: max-age='.$expiresOffset);
	readfile($file);
}else{
	header('HTTP/1.0 404 Not Found');
}
?>