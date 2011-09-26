	</div><!--#content-->
	<div id="footer">
		<ul>
			<li class="first"><a href="<?php echo $config->get('web', 'root')?>/logout/">Logout</a></li>
			<li><a href="<?php echo $config->get('web', 'root')?>/enter/">Submit Review</a></li>
			<li><a href="<?php echo $config->get('web', 'root')?>/history/">Drinking History</a></li>
	<?php
		if (strlen($config->get('web', 'feedbackEmail')))
		{
			print(TAB.TAB.'<li><a href="'.$config->get('web', 'root').'/feedback/">Feedback</a></li>');
		}
		switch ($user['type'])
		{
			case 'sysadmin':
				/* no sysadmin stuff been coded yet */
				/* no break intentionally */

			case 'admin':
				print(TAB.TAB.'<li><a href="'.$config->get('web','root').'/admin/">Admin</a></li>'.CR);
				/* no break intentionally */

			case 'superreviewer':
				print(TAB.TAB.'<li><a href="'.$config->get('web','root').'/results/">Results</a></li>'.CR);
		}
	?>
		</ul>
	</div><!--#footer-->
	<?php
	if ( $config->get('web', 'customFooter') )
	{
		print('<div id="customFooter">'.$config->get('web', 'customFooter').'</div>');
	}
	print('<div id="versionFooter">'.VERSION.'</div>');
	?>
</div><!--#body-->
<?php
printJSInclude('yui/yahoo/yahoo.js');
printJSInclude('yui/event/event.js');
printJSInclude('yui/dom/dom.js');
printJSInclude('yui/calendar/calendar.js');
printJSInclude('std.js');
?>
<?php if (false !== $config->get('web', 'googleAnalytics')) {?>
<script type="text/javascript">
  var _gaq = _gaq || [];_gaq.push(['_setAccount', '<?php echo $config->get('web', 'googleAnalytics')?>']);_gaq.push(['_setDomainName', 'none']);_gaq.push(['_setAllowLinker', true]);_gaq.push(['_trackPageview']);
  (function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);})();
</script>
<?php }?>
</body>
</html>