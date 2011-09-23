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
<?php if (false !== $config->get('web', 'googleAnalytics')) {?>
<script type="text/javascript">
  var _gaq = _gaq || [];_gaq.push(['_setAccount', '<?php echo $config->get('web', 'googleAnalytics')?>']);_gaq.push(['_setDomainName', 'none']);_gaq.push(['_setAllowLinker', true]);_gaq.push(['_trackPageview']);
  (function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);})();
</script>
<?php }?>
</html>