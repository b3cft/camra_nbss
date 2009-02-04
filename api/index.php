<?php
/**
 * Last updated $Date: 2007-04-05 09:27:49 +0100 (Thu, 05 Apr 2007) $
 * by $Author: andybrock $
 *
 * This file is $Revision: 354 $
 * $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/api/index.php $
 **/
$title = 'API Tester';
$access = array('reviewer','superreviewer','admin','sysadmin');
include('../includes/base.php');
include(DOCROOT.'/skin/header.php');
if ( $request->get('post', 'date') )
{
	Session::set('submission', $request->get('post'));
	header('Location: '.$config->get('web','root').'/enter/confirm/');
	exit;
}
?>
<!-- Submission Form begins-->
<form id="submissionform" action="" method="post" class="jsshowme">
	<div class="text required">
		<label for="frm_nbssform_date" class="text">Date of Visit<span class="required" title="Required">*</span></label>
		<input type="text" id="frm_nbssform_date" name="date" value="<?php echo date('d-M-Y',time())?>" />
		<img id="cal1Open" src="/skin/images/cal.gif" alt="open calendar" />
	</div><div id="cal1Container"></div>
	<div class="text required">
        <label for="pubinput">Pub</label>
        <input type="text" id="pubinput" class="ac_input" />
	    <div id="pubcontainer" class="ac_container"></div>
    </div><div class="hidden"><input type="hidden" id="pub_id" class="hidden" /></div>
	<div class="text">
        <label for="beerinput">Beer</label>
        <input type="text" id="beerinput" class="ac_input" />
    	<div id="beercontainer" class="ac_container"></div>
    </div><div class="hidden"><input type="hidden" id="beer_id" class="hidden" /></div>
    <div class="select">
    	<label for="ratinginput">Rating</label>
    	<select id="ratinginput">
    		<option value="0">No Real Ale</option>
    		<option value="1">0 Undrinkable</option>
    		<option value="2">1 Poor</option>
    		<option value="3">2 Average</option>
    		<option value="4">3 Good</option>
    		<option value="5">4 Very Good</option>
    		<option value="6">5 Perfect</option>
    	</select>
    </div>
    <div class="textarea">
    	<label for="notes">Notes</label>
    	<textarea id="notes" rows="4" cols="50"></textarea>
    </div>
    <div class="submit">
    	<input type="button" class="btnSubmit" value="Add Review" id="addReview" />
    </div>

	<div class="hidden">
		<input type="hidden" name="pubCount" id="pubCount" value="0" />
	</div>
	<table id="submissiontable" class="hidden">
		<thead>
			<tr>
				<th>Pub</th>
				<th>Drink</th>
				<th>Score</th>
			</tr>
		</thead>
		<tfoot>
			<tr><td colspan="3"><input name="_submitnbssform_enterReviews" type="submit" class="btnSubmit" value="Submit review" /></td></tr>
		</tfoot>
		<tbody>

		</tbody>
	</table>
</form>
<!-- Submission Form ends -->

<!-- Dependencies -->
<script type="text/javascript" src="/skin/includes/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="/skin/includes/yui/connection/connection-min.js"></script>
<script type="text/javascript" src="/skin/includes/yui/animation/animation-min.js"></script>
<script type="text/javascript" src="/skin/includes/yui/autocomplete/autocomplete-min.js"></script>
<?php
include(DOCROOT.'/skin/footer.php')
?>