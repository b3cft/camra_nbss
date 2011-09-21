<?php
/**
* Last updated $Date: 2007-03-09 20:55:20 +0000 (Fri, 09 Mar 2007) $
* by $Author: andybrock $
*
* This file is $Revision: 336 $
* $HeadURL: https://svn.sf.net/svnroot/camranbss/camra/nbss/version2/includes/Form.class.php $
*
* @package formClass
* @author Andy Brockhurst
**/

/**
* You may wish to remove these definitions or change them to suit your site/cms/setup
**/
define('DEFAULT_TEXTAREA_COLS','50');
define('DEFAULT_TEXTAREA_ROWS','5');
define('PATH_TO_TINYMCE','/skin/include/tiny_mce/');
if (!defined('CR')){define('CR',chr(13));}
if (!defined('LANG_REQUIRED')){define('LANG_REQUIRED','Required');}
if (!defined('LANG_HELPTITLE')){define('LANG_HELPTITLE','Help is available for this field');}
/**
* @desc (X)HTML form generator complete with client and serverside validation.
*
**/
class Form {

	var $field = array();
	var $method = 'post';
	var $action = '';
	var $class = null;
	var $enctype = null;
	var $target = null;
	var $id = null;
	var $focus = true;
	var	$fieldcount = 0;
	var	$labelcount = 0;
	var	$fieldsetcount = 0;
	var	$fieldsetopen = 0;
	var $optgroupopen = 0;
	var $validationerrormsg='Form validation error';
	var $fieldseterrormsg='Uneven fieldsets, please check open/close fieldset definitions';
	var $optgrouperrormsg='Uneven optgroups, please check open/close optgroup definitions';
	var $submitted = false;
	var $submittedaction = '';
	var $submitfields = array();
	var $submiterrors = 0;
	var $submiterrormsg = '';

	/**
	* @desc constructor for form class, form display and handler.
	*
	**/
	function form($id,$action='',$method='post',$focus=true,$class=null){
		$this->id=$id;
		$this->action=$action;
		$this->method=$method;
		$this->focus=$focus;
		$this->class=$class;
		$this->_checkForSubmit();
	}

	/**
	* @return void
	* @param string $msg
	* @desc change the default form validation error message, triggered when validation fails.
	**/
	function addFormValidationErrorMessage($msg){
		$this->validationerrormsg=$msg;
	}

	/**
	* @return void
	* @param string $name
	* @param string $type
	* @param variant $value
	* @param boolean $newlinebefore
	* @param boolean $newlineafter
	* @param string $class
	* @param string $validation
	* @param string $other
	* @desc Add's a new field to the form, $name and $type are required, all others are optional and can be added via additional commands.
	**/
	function addField($name,$type,$value=null,$newlinebefore=false,$newlineafter=true,$class=null,$validation=null,$other=null){
		$this->fieldcount++;
		$this->field[]=array();
		$this->currfield=sizeof($this->field)-1;
		$this->field[$this->currfield]['name']=$name;
		$this->field[$this->currfield]['value']=$value;
		$this->field[$this->currfield]['type']=$type;
		$this->field[$this->currfield]['inputclass']=$class;
		$this->field[$this->currfield]['validation']=$validation;
		$this->field[$this->currfield]['inputother']=$other;
		$this->field[$this->currfield]['nlbefore']=$newlinebefore;
		$this->field[$this->currfield]['nlafter']=$newlineafter;
		if ($type=='file'){
			$this->enctype='multipart/form-data';
		}

	}

	/**
	* @return void
	* @param string $helpText
	* @param string $helpLinkText
	* @desc add help text to current field.
	**/
	function addHelp($helpText,$helpLinkText='?'){
		$this->field[$this->currfield]['helpText']=$helpText;
		$this->field[$this->currfield]['helpLinkText']=$helpLinkText;
	}

	/**
	* @return void
	* @param string $param
	* @param string $value
	* @desc Add's additional parameters to the CURRENT FIELD, such as inputclass, labelclass. May be overridden by subsequent commands.
	**/
	function addParams($param,$value){
		$this->field[$this->currfield][$param]=$value;
	}

	/**
	* @return void
	* @param array $options
	* @param string $objkey
	* @param string $objvalue
	* @desc add options for checkboxes,radio buttons, and select boxes. If an array of objects is passed, use $objkey and $objvalue to define displayed values
	**/
	function addOptions($options, $objkey=null, $objvalue=null){
		if (true === empty($this->field[$this->currfield]['options']))
		{
			$this->field[$this->currfield]['options']=array();
		}
		$options = is_scalar($options) ? array($options=>$options) : $options;
		foreach ($options as $basekey=>$option){
			$this->addOption($option,$basekey,$objkey,$objvalue);
		}
	}

	/**
	* @return void
	* @param array $options
	* @param string $objkey
	* @param string $objvalue
	* @desc add single option for checkboxes,radio buttons, and select boxes. If an object is passed, use $objkey and $objvalue to define displayed values
	**/
	function addOption($option,$basekey,$objkey=null,$objvalue=null){
			$thisoption=array();
			$thisoption['type']='option';
			if(is_object($option)){
				$thisoption['key']=$option->$objkey;
				$thisoption['value']=$option->$objvalue;
			}else{
				if (is_scalar($option)){
					$thisoption['key'] = isset($this->field[$this->currfield]['nokey']) ? $option : $basekey;
					$thisoption['value'] = $option;
				}elseif (!is_null($objkey) && !is_null($objvalue)){
					$thisoption['key'] = $option[$objkey];
					$thisoption['value'] = $option[$objvalue];
				}
			}
			$this->field[$this->currfield]['options'][]=$thisoption;
	}

	/**
	* @return void
	* @param string $label
	* @param string $title
	* @param string $class
	* @param string $id
	* @desc Adds a optgroup open tag to select form elements
	**/
	function addOptgroupOpen($label=NULL,$title=NULL,$class=NULL,$id=NULL){
		$this->optgroupopen++;
		$this->field[$this->currfield]['options'][]=array('type'=>'optgroupopen','label'=>$label,'title'=>$title,'class'=>$class,'id'=>$id);
	}

	/**
	* @return void
	* @desc Closes Option group.
	**/
	function addOptgroupClose(){
		$this->optgroupopen--;
		$this->field[$this->currfield]['options'][]=array('type'=>'optgroupclose');
	}

	/**
	* @return void
	* @param array $arrOptions
	* @param string $subkey
	* @param string $objkey
	* @param string $objvalue
	* @desc Adds and nests array of arrays or objects to select options as OptGroup
	**/
	function addOptionsGrouped($arrOptions,$subkey,$objkey='id',$objvalue='name'){
		$this->addOption($arrOptions,$arrOptions->$objkey,$objkey,$objvalue);

		if (is_object($arrOptions)){
			if (sizeof($arrOptions->$subkey)){
				$this->addOptgroupOpen($arrOptions->$objvalue.'->');
				foreach ($arrOptions->$subkey as $option){
					$this->addOptionsGrouped($option,$subkey,$objkey,$objvalue);
				}
				$this->addOptgroupClose();
			}
		}elseif (is_array($arrOptions)){
			if (sizeof($arrOptions[$subkey])){
				$this->addOptgroupOpen($arrOptions[$objvalue].'->');
				foreach ($arrOptions[$subkey] as $option){
					$this->addOptionsGrouped($options,$subkey,$objkey,$objvalue);
				}
				$this->addOptgroupClose();
			}
		}

	}

	/**
	* @return void
	* @param string $validation
	* @desc adds validation to the CURRENT FIELD, such as 'required', 'integer', 'float'. Coming soon 'email','postcode','regexp:[expression]'
	**/
	function addFieldValidation($validation){
		$this->field[$this->currfield]['validation']=$validation;
	}

	/**
	* @return void
	* @param string $label
	* @param string $class
	* @param string $position
	* @param string $other
	* @desc add Label to CURRENT FIELD, Label is required, rest are optional or added via $form->addParams()
	**/
	function addLabel($label,$class=null,$position='left',$other=null){
		$this->labelcount++;
		$this->field[$this->currfield]['label']=$label;
		$this->field[$this->currfield]['labelclass']=$class;
		$this->field[$this->currfield]['labelpos']=$position;
		$this->field[$this->currfield]['labelother']=$other;
	}

	/**
	* @return void
	* @param string $class
	* @desc Adds a class or classes (space delimited) to the CURRENT FIELD
	**/
	function addInputClass($class){
		$this->field[$this->currfield]['inputclass']=$class;
	}

	/**
	* @return void
	* @param string $class
	* @desc Adds a class or classes (space delimited) to the CURRENT FIELD LABEL
	**/
	function addLabelClass($class){
		$this->field[$this->currfield]['labelclass']=$class;
	}

	/**
	* @return void
	* @param string $title
	* @desc Adds a title to the CURRENT FIELD AND LABEL
	**/
	function addTitle($title){
		$this->field[$this->currfield]['title']=$title;
	}

	/**
	* @return void
	* @param string $legend
	* @desc add an open fieldset and legend to form
	**/
	function addFieldsetOpen($legend,$fieldsetclass=null,$legendclass=null){
		$this->fieldsetcount++;
		$this->fieldsetopen++;
		$this->field[]=array();
		$this->currfield=sizeof($this->field)-1;
		$this->field[$this->currfield]['type']='fieldsetopen';
		$this->field[$this->currfield]['legend']=$legend;
		$this->field[$this->currfield]['legendclass']=$legendclass;
		$this->field[$this->currfield]['fieldsetclass']=$fieldsetclass;
	}

	/**
	* @return void
	* @desc closes current fieldset
	**/
	function addFieldsetClose(){
		$this->fieldsetopen--;
		$this->field[]=array();
		$this->currfield=sizeof($this->field)-1;
		$this->field[$this->currfield]['type']='fieldsetclose';
	}

	/**
	* @return void
	* @param string $content
	* @desc add any content to a form, could be status messages of table tags (if you must!)
	**/
	function addContent($content){
		$this->fieldcount++;
		$this->field[]=array();
		$this->currfield=sizeof($this->field)-1;
		$this->field[$this->currfield]['type']='content';
		$this->field[$this->currfield]['value']=$content;
	}

	/**
	* @return void
	* @desc internal, called on form intialisation, sets registers and calls serverside validation if submitted.
	**/
	function _checkForSubmit(){
		if (isset($_POST) && isset($_POST[$this->id.'-formdata'])){
			$form=unserialize(gzuncompress(base64_decode(urldecode($_POST[$this->id.'-formdata']))));
			$this->validationerrormsg= isset($form['vm']) && strlen($form['vm']) ? $form['vm'] : $this->validationerrormsg;
			foreach ($form['s'] as $submit=>$value){
				if (isset($_POST[$submit]) && htmlspecialchars($_POST[$submit])==$value){
					$this->submitted=true;
					$str='_submit'.$this->id.'_';
					$this->submittedaction=substr($submit,strlen($str));
				}
			}

			//handle checkboxes
			foreach ($form['c'] as $check=>$value){
				if (!isset($_POST[$check])){
					$_POST[$check]=$value;
				}
			}

			//handle any form validation
			foreach ($form['v'] as $check){
				switch ($check['t'].'-'.$check['v']){

					case 'text-required':
					case 'textarea-required':
					case 'password-required':
						if (!isset($_POST[$check['n']]) || strlen($_POST[$check['n']])==0){
							$this->submiterrors++;
							$this->submiterrormsg.='<br /><label for="frm_'.$this->id.'_'.$check['n'].'">'.$check['l'].'</label>';
						}
						break;

					case 'text-email':
					case 'textarea-email':
						if (isset($_POST[$check['n']]) && strlen($_POST[$check['n']]) && preg_match('/^[\w-\.\']{1,}\@([\da-zA-Z-]{1,}\.){1,}[\da-zA-Z-]{2,}$/',$_POST[$check['n']])===0){
							$this->submiterrors++;
							$this->submiterrormsg.='<br /><label for="frm_'.$this->id.'_'.$check['n'].'">'.$check['l'].'</label>';
						}
						break;

					case 'text-regexp':
					case 'textarea-regexp':
					case 'password-regexp':
						if (isset($_POST[$check['n']]) && strlen($_POST[$check['n']]) && preg_match($check['d'],$_POST[$check['n']])===0){
							$this->submiterrors++;
							$this->submiterrormsg.='<br /><label for="frm_'.$this->id.'_'.$check['n'].'">'.$check['l'].'</label>';
						}
						break;

					case 'text-float':
					case 'textarea-float': //you could i guess, PI anyone?
						if (!is_numeric($_POST[$check['n']])){
							$_POST[$check['n']]=floatval($_POST[$check['n']]);
							$this->submiterrors++;
							$this->submiterrormsg.='<br /><label for="frm_'.$this->id.'_'.$check['n'].'">'.$check['l'].'</label> '.LANG_NOTFLOAT;
						}
						break;

					case 'text-int':
					case 'textarea-int': //you could i guess, large primes?
						if (!is_numeric($_POST[$check['n']])){
							$_POST[$check['n']]=intval($_POST[$check['n']]);
							$this->submiterrors++;
							$this->submiterrormsg.='<br /><label for="frm_'.$this->id.'_'.$check['n'].'">'.$check['l'].'</label> '.LANG_NOTINT;
						}
						break;
				}
			}

			//handle uploaded files
			foreach ($form['f'] as $file=>$check){
				$uploaddir = $check['p'];
				$uploadfile = isset($check['f']) ? $check['f'] : $_FILES[$file]['name'];
				$overwrite = isset($check['o']) ? $check['o'] : 'unique';
				switch (strtolower($overwrite)) {
					case 'no':
						if (file_exists($uploaddir.$uploadfile)){
							$this->submiterrors++;
							$this->submiterrormsg.='<br /><label for="frm_'.$this->id.'_'.$check['n'].'">'.$check['l'].'</label> '.LANG_FILEEXISTS;
						}else{
							move_uploaded_file($_FILES[$file]['tmp_name'], $uploaddir.$uploadfile);
						}
						break;
					case 'unique':
						if (file_exists($uploaddir.$uploadfile)){
							$uploadfile=tempnam($uploaddir,'');
							@unlink($uploadfile); //tidyup tmp file if created (is on WIN32)
							$uploadfile=basename($uploadfile);
							$tmp=explode('.',$_FILES[$file]['name']); //pick out extension
							$tmp=array_reverse($tmp);
							$uploadfile.='.'.$tmp[0];
						}
					case 'yes':
						move_uploaded_file($_FILES[$file]['tmp_name'], $uploaddir.$uploadfile);
						break;
				}

			}

			//handle any html passed in form vars except for allowed params
			foreach ($_POST as $var=>$val){
				if (array_search($var,$form['h'])===false){
					$_POST[$var]=htmlentities($val);
				}
			}
		}

		if ($this->submiterrors>0){
			$this->submiterrormsg = '<p class="error"><strong>'.$this->validationerrormsg.'</strong>'.$this->submiterrormsg.'</p>';
		}

	}

	/**
	* @return string
	* @desc displays form.
	**/
	function display(){
		if ($this->fieldsetopen!=0){
			print('<p style="color:#f00;background:#fff;border:2px solid #f00;"><strong>'.$this->fieldseterrormsg.'</strong></p>');
		}
		if ($this->optgroupopen!=0){
			print('<p style="color:#f00;background:#fff;border:2px solid #f00;"><strong>'.$this->optgrouperrormsg.'</strong></p>');
		}
		$html='<form action="'.$this->action.'" method="'.$this->method.'"';
		$html.=is_null($this->id) ? '' : ' id="'.$this->id.'"';
		$html.=is_null($this->class) ? '' : ' class="'.$this->class.'"';
		$html.=is_null($this->enctype) ? '' : ' enctype="'.$this->enctype.'"';
		$html.=is_null($this->target) ? '' : ' target="'.$this->target.'"';
		$html.=' onsubmit="return formvalidate'.$this->id.'(this);">'.CR;
		$firstfield=null;
		foreach ($this->field as $key=>$field){
			switch($field['type']){

				case 'select':
					$html.=$this->_displaySelect($field);
					$firstfield = is_null($firstfield) ? $field['name'] : $firstfield;
					break;

				case 'date':
					$html.=$this->_displayDate($field);
					$firstfield = is_null($firstfield) ? $field['name'].'day' : $firstfield;
					break;

				case 'checkbox':
					$html.=$this->_displayCheckbox($field);
					$firstfield = is_null($firstfield) ? $field['name'] : $firstfield;
					break;

				case 'radio':
					$html.=$this->_displayRadio($field);
					$firstfield = is_null($firstfield) ? $field['name'] : $firstfield;
					break;

				case 'tinymce':
					$this->field[$key]['allowhtml']=true;
					//TinyMCE translates any HTML entities, so needs to be double encoded if tags are in content.
					$field['value']=htmlentities($field['value']);
					$html.=$this->_displayTinyMCE($field);
					$firstfield = is_null($firstfield) ? $field['name'] : $firstfield;
					break;

				case 'textarea':
					$html.=$this->_displayTextarea($field);
					$firstfield = is_null($firstfield) ? $field['name'] : $firstfield;
					break;

				case 'file':
					$html.=$this->_displayFile($field);
					$firstfield = is_null($firstfield) ? $field['name'] : $firstfield;
					break;

				case 'fieldsetopen':
					$html.=$this->_displayFieldsetOpen($field);
					break;

				case 'fieldsetclose':
					$html.=$this->_displayFieldsetClose();
					break;

				case 'text':
				case 'password';
					$html.=$this->_displayText($field);
					$firstfield = is_null($firstfield) ? $field['name'] : $firstfield;
					break;

				case 'submit':
					$html.=$this->_displaySubmit($field);
					$this->submitfields['_submit'.$this->id.'_'.$field['name']]=$field['value'];
					break;

				case 'reset':
					$html.=$this->_displayReset($field);
					break;

				case 'content':
					$html.=$field['value'];
					break;

				case 'hidden':
				default:
					$html.=$this->_displayHidden($field);
					break;

			}
		}
		$html.='<div class="hidden"><input type="hidden" name="'.$this->id.'-formdata" value="'.$this->_formdata().'" /></div>';
		$html.='</form>'.CR;
		/*$html.='<script type="text/javascript" defer>'.CR;
		$html.='<!--'.CR;
		$html.=$this->focus ? 'document.'.$this->id.'.'.$firstfield.'.focus();'.CR : '';
		$html.='function formvalidate'.$this->id.'(frm){'.CR;
		$html.='var error=\'\';'.CR;
		$html.=$this->_displayJSValidation();
		$html.='if (error){'.CR;
		$html.='alert(\''.$this->validationerrormsg.'\n\n\'+error)'.CR;
		$html.='return false;'.CR;
		$html.='}else{'.CR;
		$html.='return true;'.CR;
		$html.='}'.CR;
		$html.='}'.CR;
		$html.='//-->'.CR;
		$html.='</script><noscript><!--fallback to serverside validation--></noscript>'.CR;*/
		$html.=$this->_displayHelpText();
		return $html;
	}

	/**
	* @return string
	* @desc parses form and returns a serialized, gzipped, base64encoded version of required fields and buttons, to allow validation on submit without having the form already defined ;->. Very clever if I say so myself.
	**/
	function _formdata(){
		$validation=array();
		$validationmsg='';
		$checkbox=array();
		$submit=array();
		$file=array();
		$html=array();
		foreach ($this->field as $field){
			if (isset($field['validation']) ){
				$validaters=explode(' ',$field['validation']);
				foreach ($validaters as $check){
					$item=array();
					$item['t']=$field['type'];
					$item['n']=$field['name'];
					$item['l']=$field['label'];
					$item['v']=$check;
					if (isset($field['validationdata'])){$item['d']=$field['validationdata'];}
					$validation[]=$item;
					$validationmsg=$this->validationerrormsg;
				}
			}
			if ($field['type']=='file'){
				$item=array();
				$item['p']=$field['path'];
				if (isset($field['filename'])){
					$item['f']=$field['filename'];
				}
				if (isset($field['overwrite'])){
					$item['o']=$field['overwrite'];
				}
				$file[$field['name']]=$item;
			}
			if ($field['type']=='checkbox'){
				$checkbox[$field['name']]=$field['options'][1]['value'];
			}
			if ($field['type']=='submit'){
				$submit['_submit'.$this->id.'_'.$field['name']]=$field['value'];
			}
			if (isset($field['allowhtml']) && $field['allowhtml']==true){
				$html[]=$field['name'];
			}
		}
		$form=array();
		$form['s']=$submit;
		$form['c']=$checkbox;
		$form['v']=$validation;
		$form['vm']=$validationmsg;
		$form['f']=$file;
		$form['h']=$html;
		return urlencode(base64_encode(gzcompress(serialize($form),9)));
	}

	/**
	 * Display the help text for form elements. JS is included in this version.
	 *
	 * @return string
	 */
	function _displayHelpText(){
		$html='';
		foreach ($this->field as $field){
			if (isset($field['helpText'])){
				if (isset($field['id'])){
					$id=$field['id'];
				}else{
					$id=$field['name'];
				}
				$html.='<div id="frm_'.$this->id.'_'.$id.'help" class="helpbox">';
	        	$html.='<h3>'.$field['label'].'</h3>'.CR;
	        	$html.=$field['helpText'];
		    	$html.='</div>';
	    		$html.='<p><a href="#frm_'.$this->id.'_'.$id.'">Back to '.$field['label'].'</a>.</p>';
			}
		}
		if (strlen($html)){
			$html='<div id="frm_'.$this->id.'helpcontainer" class="helptextContainer"><h2>Help Section</h2>'.$html.'</div>';
		}
		return $html;
	}

	/**
	* @return string
	* @desc returns javascript validation code for current form.
	**/
	function _displayJSValidation(){
		$js='';
		foreach ($this->field as $field){
			if (isset($field['validation']) && strlen($field['validation'])){
				$validation=explode(' ',$field['validation']);
				foreach ($validation as $check){
					switch ($field['type'].'-'.$check){
						case 'text-required':
						case 'textarea-required':
						case 'password-required':
						case 'file-required':
							$js.='if (!frm.'.$field['name'].'.value){error+=\''.strip_tags(html_entity_decode($field['label'])).'\\n\'}'.CR;
							break;

						case 'text-email':
						case 'textarea-email':
							$js.='if (frm.'.$field['name'].'.value && !frm.'.$field['name'].'.value.match(/^[\w-\.\']{1,}\@([\da-zA-Z-]{1,}\.){1,}[\da-zA-Z-]{2,}$/)){error+=\''.strip_tags(html_entity_decode($field['label'])).' '.LANG_ISNOTVALID.'\\n\'}'.CR;
							break;

						case 'text-regexp':
						case 'textarea-reqexp':
						case 'password-reqexp':
							$js.='if (frm.'.$field['name'].'.value && !frm.'.$field['name'].'.value.match('.$field['validationdata'].')){error+=\''.strip_tags(html_entity_decode($field['label'])).' regexp '.LANG_ISNOTVALID.'\\n\'}'.CR;
							break;

						case 'text-float':
							//regexp?
							break;

						case 'text-int':
							//regexp?
							break;
					}
				}
			}
		}
		return $js;
	}

	/**
	* @return string
	* @param array $field
	* @param internal, display (X)HTML input form field for current form object field iteration.
	**/
	function _displayHidden($field){
		return '<div class="hidden"><input type="hidden" name="'.$field['name'].'" value="'.$field['value'].'" /></div>'.CR;
	}

	/**
	* @return string
	* @param array $field
	* @param internal, display (X)HTML input form field for current form object field iteration.
	**/
	function _displayText($field){
		$label=$this->_displayLabel($field);
		if (isset($field['id'])){
			$id=$field['id'];
		}else{
			$id=$field['name'];
		}
		$input='<input type="'.$field['type'].'" id="frm_'.$this->id.'_'.$id.'" name="'.$field['name'].'"';
		$input.=' value="'.$field['value'].'"';
		$input.= empty($field['inputclass']) ? '' : ' class="'.$field['inputclass'].'"';
		$input.= !isset($field['title']) || is_null($field['title']) ? '' : ' title="'.$field['title'].'"';
		$input.= empty($field['inputother']) ? '' : ' '.$field['inputother'];
		$input.=' />';

		return $this->_displayElement($label,$input,$field['labelpos'],$field);
	}

	/**
	* @return string
	* @param array $field
	* @param internal, display (X)HTML input form field for current form object field iteration.
	**/
	function _displayCheckbox($field){
		$label=$this->_displayLabel($field);
		if (isset($field['id'])){
			$id=$field['id'];
		}else{
			$id=$field['name'];
		}
		if (isset($field['options'])){
			$value=$field['options'][0]['value'];
		}else{
			$value=$field['value'];
		}
		if (isset($field['checked'])){
			$checked =  $field['checked']==true ? true : false;
		}else{
			$checked = $value==$field['value'] ? true : false;
		}
		$input='<input type="'.$field['type'].'" id="frm_'.$this->id.'_'.$id.'" name="'.$field['name'].'"';
		$input.=' value="'.$value.'"';
		$input.= $checked ? ' checked="checked"' : '';
		$input.= empty($field['inputclass']) ? '' : ' class="'.$field['inputclass'].'"';
		$input.= empty($field['title']) ? '' : ' title="'.$field['title'].'"';
		$input.= empty($field['inputother']) ? '' : ' '.$field['inputother'];
		$input.=' />';

		return $this->_displayElement($label,$input,$field['labelpos'],$field);
	}

	/**
	* @return string
	* @param array $field
	* @param internal, display (X)HTML input form field for current form object field iteration.
	**/
	function _displayRadio($field){
		$label=$this->_displayLabel($field);
		if (isset($field['id'])){
			$id=$field['id'];
		}else{
			$id=$field['name'];
		}
		if (isset($field['options'])){
			$value=$field['options'][0]['value'];
		}else{
			$value=$field['value'];
		}
		if (isset($field['checked'])){
			$checked =  $field['checked']==true ? true : false;
		}else{
			$checked = $value==$field['value'] ? true : false;
		}
		$input='<input type="'.$field['type'].'" id="frm_'.$this->id.'_'.$id.'" name="'.$field['name'].'"';
		$input.=' value="'.$value.'"';
		$input.= $checked ? ' checked="checked"' : '';
		$input.= empty($field['inputclass']) ? '' : ' class="'.$field['inputclass'].'"';
		$input.= empty($field['title']) ? '' : ' title="'.$field['title'].'"';
		$input.= empty($field['inputother']) ? '' : ' '.$field['inputother'];
		$input.=' />';

		return $this->_displayElement($label,$input,$field['labelpos'],$field);
	}

	/**
	* @return string
	* @param array $field
	* @param internal, display (X)HTML input form field for current form object field iteration.
	**/
	function _displayTextarea($field){
		$label=$this->_displayLabel($field);
		if (isset($field['id'])){
			$id=$field['id'];
		}else{
			$id=$field['name'];
		}
		$input='<textarea id="frm_'.$this->id.'_'.$id.'" name="'.$field['name'].'"';
		$input.= ' cols="'.(isset($field['cols']) ? $field['cols'] : DEFAULT_TEXTAREA_COLS).'"';
		$input.= ' rows="'.(isset($field['rows']) ? $field['rows'] : DEFAULT_TEXTAREA_ROWS).'"';
		$input.= empty($field['inputclass']) ? '' : ' class="'.$field['inputclass'].'"';
		$input.= empty($field['title']) ? '' : ' title="'.$field['title'].'"';
		$input.= empty($field['inputother']) ? '' : ' '.$field['inputother'];
		$input.='>';
		$input.=$field['value'];
		$input.='</textarea>';

		return $this->_displayElement($label,$input,$field['labelpos'],$field);
	}

	/**
	* @return string
	* @param array $field
	* @param internal, display (X)HTML input form field for current form object field iteration.
	**/
	function _displayFile($field){
		$label=$this->_displayLabel($field);
		if (isset($field['id'])){
			$id=$field['id'];
		}else{
			$id=$field['name'];
		}
		$input='<input type="file" id="frm_'.$this->id.'_'.$id.'" name="'.$field['name'].'"';
		$input.= empty($field['inputclass']) ? '' : ' class="'.$field['inputclass'].'"';
		$input.= empty($field['title']) ? '' : ' title="'.$field['title'].'"';
		$input.= empty($field['inputother']) ? '' : ' '.$field['inputother'];
		$input.='>';

		return $this->_displayElement($label,$input,$field['labelpos'],$field);
	}

	/**
	* @return string
	* @param array $field
	* @param internal, display (X)HTML fieldset and legend for current form object field iteration.
	**/
	function _displayFieldsetOpen($field){
		$fieldset = '<fieldset';
		$fieldset.= empty($field['fieldsetclass']) ? '' : ' class="'.$field['fieldsetclass'].'"';
		$fieldset.='>'.CR.'<legend';
		$fieldset.= empty($field['legendclass']) ? '' : ' class="'.$field['legendclass'].'"';
		$fieldset.='>'.$field['legend'].'</legend>'.CR;
		return $fieldset;
	}

	/**
	* @return string
	* @param array $field
	* @param internal, display (X)HTML fieldset close tag for current form object field iteration.
	**/
	function _displayFieldsetClose(){
		return '</fieldset>'.CR;
	}

    /**
    * @return string
    * @param array $field
    * @param internal, display (X)HTML label for current form object field iteration.
    **/
    function _displayLabel($field){
            if (isset($field['displaylabel']) && $field['displaylabel']==false){
                    return;
            }
            if (isset($field['id'])){
                    $id=$field['id'];
            }else{
                    $id=$field['name'];
            }
            $html='<label for="frm_'.$this->id.'_'.$id.'"';
            $html.=' class="'.$field['type'].(is_null($field['labelclass']) ? '"' : ' '.$field['labelclass'].'"');
            $html.= empty($field['title']) ? '' : ' title="'.$field['title'].'"';
            $html.= empty($field['labelother']) ? '' : ' '.$field['labelother'];
            $html.='>';

            $html.=$field['label'];
            if (strlen($field['validation'])){
                    $validation=explode(' ',$field['validation']);
                    $html.=array_search('required',$validation)!==false ? '<span class="required" title="'.LANG_REQUIRED.'">*</span>' : '';
            }
            $html.='</label>';

            $html.=$this->_displayHelpLink($field);

            return $html;
    }

    /**
    * @return string
    * @desc adds HTML Help text to an element.
    **/
	function _displayHelpLink($field){
        if (isset($field['helpText'])){
        	if (isset($field['id'])){
                    $id=$field['id'];
            }else{
                    $id=$field['name'];
            }
        	return '<a href="#frm_'.$this->id.'_'.$id.'help" class="helplink">'.$field['helpLinkText'].'</a>';
        }
	}

	/**
	* @return string
	* @param array $field
	* @param internal, display (X)HTML submit button for current form object field iteration.
	**/
	function _displaySubmit($field){
		$html='<input type="submit" name="_submit'.$this->id.'_'.$field['name'].'"';
		$html.=' value="'.$field['value'].'"';
		$html.=' id="frm_'.$this->id.'_submit"';
		$html.= empty($field['inputclass']) ? '' : ' class="'.$field['inputclass'].'"';
		$html.= empty($field['title']) ? '' : ' title="'.$field['title'].'"';
		$html.= empty($field['inputother']) ? '' : ' '.$field['inputother'];
		$html.=' />';

		return $this->_displayElement('',$html,'left',$field);
	}

	/**
	* @return string
	* @param array $field
	* @param internal, display (X)HTML select form field for current form object field iteration.
	**/
	function _displaySelect($field){
		if (isset($field['id'])){
			$id=$field['id'];
		}else{
			$id=$field['name'];
		}
		$options=$field['options'];
		$label=$this->_displayLabel($field);
		$input='<select id="frm_'.$this->id.'_'.$id.'" name="'.$field['name'].'"';
		$input.= isset($field['multiple']) ? ' multiple="multiple"' : '' ;
		$input.= isset($field['size']) ? ' size="'.$field['size'].'"' : '' ;
		$input.= empty($field['inputclass']) ? '' : ' class="'.$field['inputclass'].'"';
		$input.= empty($field['title']) ? '' : ' title="'.$field['title'].'"';
		$input.= empty($field['inputother']) ? '' : ' '.$field['inputother'];

		$input.='>'.CR;
		$input.=$this->_displayOptions($field);
		$input.='</select>';
		return $this->_displayElement($label,$input,$field['labelpos'],$field);
	}

	/**
	* @return string
	* @param array $field
	* @desc accepts array of options return HTML options (with groupings)
	**/
	function _displayOptions($field){
		$html='';
		$options=$field['options'];
		foreach ($options as $option){
			switch ($option['type']){
				case 'option':
					$value=isset($field['nokey']) ? $option['value'] : $option['key'];
					$selected=strval($value)==strval($field['value']) ? ' selected="selected"' : '';
					$html.='<option value="'.$value.'"'.$selected.'>'.$option['value'].'</option>'.CR;
					break;

				case 'optgroupopen':
					$html.='<optgroup';
					$html.= empty($option['label']) ? '' : ' label="'.$option['label'].'"';
					$html.= empty($option['title']) ? '' : ' title="'.$option['title'].'"';
					$html.= empty($option['class']) ? '' : ' class="'.$option['class'].'"';
					$html.= empty($option['id']) ? '' : ' id="'.$option['id'].'"';
					$html.='>'.CR;
					break;

				case 'optgroupclose':
					$html.='</optgroup>'.CR;
					break;

			}
		}
		return $html;
	}

	/**
	* @return string
	* @param array $field
	* @param internal, display (X)HTML select box set for current form object field iteration.
	**/
	function _displayDate($field){
		if (isset($field['id'])){
			$id=$field['id'];
		}else{
			$id=$field['name'];
		}
		//Day
		$day=date('d',strtotime($field['value']));
		$label=$this->_displayLabel($field);
		$input='<select id="frm_'.$this->id.'_'.$id.'day" name="'.$field['name'].'day"';
		$input.=' class="'.$field['type'].(is_null($field['inputclass']) ? '"' : ' '.$field['inputclass'].'"');
		$input.= empty($field['title']) ? '' : ' title="'.$field['title'].' '.LANG_DAY.'"';
		$input.='>'.CR;
		$input.='<option value="">'.LANG_DAY.'</option>'.CR;
		$options=range(1,31);
		foreach ($options as $option){
			$selected=$option==$day ? ' selected="selected"' : '';
			$input.='<option value="'.$option.'"'.$selected.'>'.$option.'</option>'.CR;
		}
		$input.='</select>';

		//Month
		$month=date('m',strtotime($field['value']));
		$input.='<select id="frm_'.$this->id.'_'.$id.'month" name="'.$field['name'].'month"';
		$input.= empty($field['inputclass']) ? '' : ' class="'.$field['inputclass'].'"';
		$input.= empty($field['title']) ? '' : ' title="'.$field['title'].' '.LANG_MONTH.'"';
		$input.='>'.CR;
		$input.='<option value="">'.LANG_MONTH.'</option>'.CR;
		$options=range(1,12);
		foreach ($options as $option){
			$selected=$option==$month ? ' selected="selected"' : '';
			$input.='<option value="'.$option.'"'.$selected.'>'.$option.'</option>'.CR;
		}
		$input.='</select>';

		//Year
		$year=date('Y',strtotime($field['value']));
		$input.='<select id="frm_'.$this->id.'_'.$id.'year" name="'.$field['name'].'year"';
		$input.= empty($field['inputclass']) ? '' : ' class="'.$field['inputclass'].'"';
		$input.= empty($field['title']) ? '' : ' title="'.$field['title'].' '.LANG_YEAR.'"';
		$input.='>'.CR;
		$input.='<option value="">'.LANG_YEAR.'</option>'.CR;
		$options=range(date('Y')-1,date('Y')+4);
		foreach ($options as $option){
			$selected=$option==$year ? ' selected="selected"' : '';
			$input.='<option value="'.$option.'"'.$selected.'>'.$option.'</option>'.CR;
		}
		$input.='</select>';

		return $this->_displayElement($label,$input,$field['labelpos'],$field['nlbefore'],$field['nlafter']);
	}

	/**
	* @return string
	* @param array $field
	* @param internal, display (X)HTML textarea with TinyMCE instance for current form object field iteration. tinyMCE attribute should be included in field['inputother']='tinymce="tinymce"' or whatever triggers you have set in mceinit.js
	**/
	function _displayTinyMCE($field){
		$html.='<!-- tinyMCE -->'.CR;
		$html.='<script type="text/javascript" src="'.PATH_TO_TINYMCE.'tiny_mce.js"></script><noscript><!--fallsback to textarea--></noscript>'.CR;
		$html.='<script type="text/javascript" src="'.PATH_TO_TINYMCE.'mceinit.js.php"></script><noscript><!--fallsback to textarea--></noscript>'.CR;
		$html.='<!-- /tinyMCE -->'.CR;

		$html.=$this->_displayTextarea($field);
		return $html;
	}

	/**
	* @return string
	* @param array $field
	* @param internal, display (X)HTML reset button for current form object field iteration.
	**/
	function _displayReset($field){
		$html='<input type="reset" name="_reset'.$this->id.'"';
		$html.=' value="'.$field['value'].'"';
		$html.=' id="frm_'.$this->id.'_reset"';
		$html.= empty($field['inputclass']) ? '' : ' class="'.$field['inputclass'].'"';
		$html.= empty($field['title']) ? '' : ' title="'.$field['title'].'"';
		$html.= empty($field['inputother']) ? '' : ' '.$field['inputother'];
		$html.=' />';

		return $this->_displayElement('',$html,'left',$field);
	}

	/**
	* @return string
	* @param array $field
	* @param internal, display (X)HTML label and input tags in correct order for current form object field iteration.
	**/
	function _displayElement($label,$input,$labelpos,$field){

		switch ($labelpos){
			case 'right':
				$html=$input.$label;
				break;

			case 'left':
			default:
				$html=$label.$input;
				break;
		}
		$class = $field['type'];
		$class .= empty($field['containerclass']) ? '' : ' '.$field['containerclass'];
		$class .= empty($field['validation']) ? '' : ' '.$field['validation'];
		return '<div class="'.$class.'">'.$html.'</div>'.CR;
	}
}
?>