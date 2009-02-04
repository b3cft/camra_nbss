<?php
	if ( $config->get('web', 'customFooter') )
	{
		print('<div id="customFooter">'.$config->get('web', 'customFooter').'</div>');
	}
	print('<div id="versionFooter">'.VERSION.'</div>');
?>
</div><!--#body-->
</body>
<script src="<?php echo $config->get('web', 'root')?>/skin/includes/?uri=yui/yahoo/yahoo.js" type="text/javascript"></script>
<script src="<?php echo $config->get('web', 'root')?>/skin/includes/?uri=yui/dom/dom.js" type="text/javascript"></script>
<script src="<?php echo $config->get('web', 'root')?>/skin/includes/?uri=std.js" type="text/javascript"></script>
</html>