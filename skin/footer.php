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
<script src="<?php echo $config->get('web', 'root')?>/skin/includes/?uri=yui/yahoo/yahoo.js" type="text/javascript"></script>
<script src="<?php echo $config->get('web', 'root')?>/skin/includes/?uri=yui/event/event.js" type="text/javascript"></script>
<script src="<?php echo $config->get('web', 'root')?>/skin/includes/?uri=yui/dom/dom.js" type="text/javascript"></script>
<script src="<?php echo $config->get('web', 'root')?>/skin/includes/?uri=yui/calendar/calendar.js" type="text/javascript"></script>
<script src="<?php echo $config->get('web', 'root')?>/skin/includes/?uri=std.js" type="text/javascript"></script>
</body>
</html>