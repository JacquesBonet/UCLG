<?php
/**
* @package		JooDatabase - http://joodb.feenders.de
* @copyright	Copyright (C) Computer - Daten - Netze : Feenders. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Dirk Hoeschen (hoeschen@feenders.de)
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Component Helper
jimport('joomla.application.component.helper');

/**
 * JooDB Component Helper
 */
class JoodbHelper
{

	/**
 	* Parse template for wildcards and return text
 	*
 	* @access public
 	* @param JooDB-Objext with fieldnames, Array with template parts, Object with Item-Data
 	* @return The parsed output$parmas
 	*
 	*/
	function parseTemplate(& $joobase, & $parts, & $item, &$params) {
		$output = "";
		// generate link to the item
	   	$itemlink = JRoute::_('index.php?option=com_joodb&view=article&joobase='.$joobase->id.'&id='.$item->{$joobase->fid}.':'.JFilterOutput::stringURLSafe($item->{$joobase->ftitle}),false);
		$doOutput = true;
		$imgpart = DS."images".DS."joodb".DS."db".$joobase->id."/img".$item->{$joobase->fid};
	   	// replace item content with wildcards
    	foreach( $parts as $n => $part ) {
			if ($doOutput) {
	    		// replace field command with 1st parameter
    			if ($part->function=="field") {
    				$part->function = $part->parameter[0]; unset($part->parameter[0]);
					$output .=joodbHelper::replaceField($joobase, $part, $item->{$part->function}, $itemlink,$params);
    			} else if (isset($joobase->fields[$part->function])) {
    				/** only replace exisiting fields
    				 *  @deprecated */
				  	$output .=joodbHelper::replaceField($joobase, $part, $item->{$part->function}, $itemlink,$params);
	   			} else if ($part->function=="readon") { // replace readon field
					 $output .= "<a class='readon readmore' href='".$itemlink."'>".Jtext::_('Read more...')."</a>";
    			} else if ($part->function=="loopclass") { // replace item loop class field
					$output .= $item->loopclass;
	   			} else if ($part->function=="notepadbutton") { // replace readon field
					$output .= JoodbHelper::getNotepadButton($item,$joobase);
	   			} else if ($part->function=="printbutton") { // replace readon field
					$output .= JoodbHelper::getPrintPopup($item,$joobase);
	   			} else if ($part->function=="backbutton") { // replace readon field
					$output .= JoodbHelper::getBackbutton();
	   			} else if ($part->function=="path2image") { // get image
					$output .= JURI::root(true).(file_exists(JPATH_ROOT.$imgpart.".jpg") ? str_replace(DS, "/",$imgpart).".jpg" : "/components/com_joodb/assets/images/nopic.png");
	   			} else if ($part->function=="path2thumb") { // get image
					$output .= JURI::root(true).(file_exists(JPATH_ROOT.$imgpart."-thumb.jpg") ? str_replace(DS, "/",$imgpart)."-thumb.jpg" : "/components/com_joodb/assets/images/nopic.png");
	   			} else if ($part->function=="checkbox") { // print checkbox
	   				$ids = JRequest::getVar('cid', array(), '', 'array');
	   				$checked = (in_array($item->{$joobase->fid},$ids)) ? 'checked="checked"' : '';
					$output .= '<input class="inputbox check" type="checkbox" id="cb'.$item->{$joobase->fid}.'" name="cid[]" value="'.$item->{$joobase->fid}.'" '.$checked.' />';
	   			}
			}
    		if ($part->function=="ifis") { // check if field condition is true
    			if (isset($part->parameter[1])) {
    				$doOutput = ($item->{$part->parameter[0]}==$part->parameter[1]) ? true : false;
    			} else {
    				$doOutput = ($item->{$part->parameter[0]}) ? true : false;
    			}
	   		} else if ($part->function=="ifnot") { // check if field condition is false
    			if (isset($part->parameter[1])) {
    				$doOutput = ($item->{$part->parameter[0]}!=$part->parameter[1]) ? true : false;
    			} else {
	   				$doOutput = (!$item->{$part->parameter[0]}) ? true : false;
    			}
	   		} else if ($part->function=="endif") {	$doOutput = true; }
	   		if ($doOutput) $output .= $part->text;

  	 	}
  	 	return $output;
	}

	/**
 	* Replaces a joodb fieldname with field contennt
 	*
 	* @access public
 	* @param JooDB-Object with fieldnames, Part-object from the template, Text with field content
 	* @return The parsed output
 	*
 	*/
	function replaceField(&$joobase, &$part, $field, $itemlink, &$params) {
		$fieldname = $part->function;
		if (($fieldname==$joobase->ftitle) && ($params->get('link_titles',0))) {
			$field= "<a href='".$itemlink."' title='".Jtext::_('Read more...')."' class='joodb_titletink'>".$field."</a>";
		}
		// convert some of the fieldtypes
		switch(strtolower($joobase->fields[$part->function])) {
			case "date":
				$field= JHTML::_('date', $field, JText::_('DATE_FORMAT_LC3'));
			break;
			case "datetime":
				$field= JHTML::_('date', $field, JText::_('DATE_FORMAT_LC2'));
			break;
			case "timestamp":
				$field= JHTML::_('date', $field, JText::_('DATE_FORMAT_LC2'));
			break;
			case "varchar":
			case "tinytext":
				if ($params->get('link_urls','0')) {
					// try to detect and link urls ans emails
					if (preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $field)) {
						$field= JHtml::_('email.cloak', $field);
					} else if (strtolower(substr($field,0,4))=="www.") {
						$field= '<a href="http://'.$field.'" target"_blank">'.$field.'</a>';
					} else if (strtolower(substr($field,0,4))=="http") {
						$field= '<a href="http://'.$field.'" target"_blank">'.$field.'</a>';
					}
				}
			break;
		}
		// shorten a text for abscracts
		if ((isset($part->parameter[0])) && ($part->parameter[0]>1)) {
			$field = JoodbHelper::wrapText($field,$part->parameter[0]);
		}
		return $field;
	}

	/**
	 * Analyses the field-parameter and returns a formfield ...
	 * @param string $fieldname
	 * @param object $joobase
	 */
	function getFormField(&$joobase,&$params) {
		$fieldname = $params[0];
		foreach ($joobase->fields as $field) {
			if ($fieldname==$field->Field) {
				$typearr = preg_split("/\(/",$field->Type);
				$typevals = array();
				$required = ($field->Null=="NO") ? "required" :"";
				if (isset($typearr[1])) { $typevals =  preg_split("/','/",trim($typearr[1],"')"));	}
				if (!$value = JRequest::getVar($fieldname)) $value = $field->Default;
				$formfield = "";
			switch ($typearr[0]) {
				case 'varchar' :
				case 'char' :
				case 'tinytext' :
					if (count($params)>=2 && $params[1]=="email") $required.= " validate-email";
					$formfield = '<input class="inputbox '.$required.'" type="'.((count($params)>=2 && $params[1]=="password") ? "password" : "text").'" name="'.$fieldname.'" id="'.$fieldname.'" value="'.htmlspecialchars($value, ENT_COMPAT, 'UTF-8').'" maxlength="'.$typevals[0].'" size="50" />';
				break;
				case 'int' :
				case 'smallint' :
				case 'mediumint' :
				case 'bigint' :
					$formfield =  '<input class="inputbox '.$required.'" type="text" name="'.$fieldname.'" id="'.$fieldname.'" value="'.(preg_replace("/[^0-9]/","",$value)).'" maxlength="40" size="20" style="width: 200px" />';
				break;
				case 'decimal' :
				case 'float' :
				case 'double' :
				case 'real' :
					$formfield =  '<input class="inputbox '.$required.'" type="text" name="'.$fieldname.'" id="'.$fieldname.'" value="'.(preg_replace("/[^0-9.]/","",$value)).'" maxlength="40" size="20" style="width: 200px" />';
				break;
				case 'tinyint' :
					$formfield = '<input class="inputbox '.$required.'" type="text" name="'.$fieldname.'" id="'.$fieldname.'" value="'.(int) $value.'" maxlength="4" size="4" style="width: 50px" />';
				break;
				case 'datetime' :
				case 'timestamp' :
					$value = preg_replace("/[^0-9:\- ]/","",$value);
					$formfield =  JHTML::_('calendar', $value , $fieldname, $fieldname, '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19'));
					break;
				case 'date' :
					$value = preg_replace("/[^0-9\-]/","",$value);
					$formfield = JHTML::_('calendar', $value , $fieldname, $fieldname, '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'10'));
				break;
				case 'year' :
					$formfield =  '<input class="inputbox '.$required.'" name="'.$fieldname.'" id="'.$fieldname.'"  value="'.(int) $value.'" maxlength="4" size="4" />';
				break;
				case 'text' :
				case 'mediumtext' :
				case 'longtext' :
					$formfield = '<textarea class="'.$required.'" cols="60" rows="5"  name="'.$fieldname.'" id="'.$fieldname.'">'.stripslashes($value).'</textarea>';
				break;
				// special handling for enum and set
				case 'enum' :
					$formfield = '<select class="inputbox '.$required.'" type="text"  name="'.$fieldname.'" id="'.$fieldname.'" />';
					$formfield .= '<option value="" >...</option>';
					foreach ($typevals as $val) {
						$formfield .= '<option value="'.$val.'" '.(($val==$value) ? 'selected' : '' ).'>'.$val.'</option>';
					}
					$formfield .= '</select>';
				break;
				case 'set' :
					if (!is_array($value)) $value = preg_split("/,/",$value);
					foreach ($typevals as $val) {
						$formfield .=  '<input type="checkbox" name="'.$fieldname.'[]" value="'.$val.'" '.((array_search($val,$value)!==false) ? 'checked' : '' ).' >&nbsp;'.$val.'</input> ';
					}
				break;
				default:
					$formfield = '<input class="inputbox '.$required.'" type="text" name="'.$fieldname.'" id="'.$fieldname.'" value="'.htmlspecialchars($value, ENT_COMPAT, 'UTF-8').'" maxlength="254" size="50" />';
				}
				return $formfield;
				break;
			}
		}
		return $joobase->fields[$fieldname];
	}

	/**
 	* Split a template into parts return a array of of objects
 	*
 	* @access public
 	* @param String with template text
 	*/
	function splitTemplate($template) {
		$psplit = preg_split('/\{joodb /U', $template);
		$parts=array();
		// insert text only for the first part
		if (substr($template,0,6)!="{joodb") {
			$e = new joodbPart();
			$e->text = array_shift($psplit);
			$parts[] =$e;
		}
		foreach ($psplit as $part) {
			$part = $part;
			$e = new joodbPart();
			$p=strpos($part,"}");
			if ($p===false) {
				$e->text=$part;
			} else {
				$e->text=substr($part,$p+1);
				$e->parameter = preg_split("/\|/",trim(substr($part,0,$p)));
				$e->function = array_shift($e->parameter);
			}
			$parts[] =$e;
		}
		return $parts;
	}

	/**
 	* Shorten a text
 	*
 	* @access public
 	* @param String with text, Integer with maximum length
 	*/
	function wrapText($text,$maxlen=120) {
		if (strlen($text)>$maxlen) {
			$text = strip_tags($text);
			$len = strpos($text," ",$maxlen);
			if ($len) $text = substr($text,0,$len).' &hellip;';
		}
		return $text;
	}

	/**
 	* Returns popup link for printview as Icon or Text
 	*
 	* @access public
 	* @param Item, Params
 	*/

	function getPrintPopup(&$item, &$joobase, $attribs = array())
	{
		$application = JFactory::getApplication();
		$params	= &$application->getParams();

		$url  = 'index.php?option=com_joodb&view=article&joobase='.$joobase->id.'&id='.$item->{$joobase->fid}.'&layout=print&tmpl=component&print=1';

		$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

		// checks template image directory for image, if non found default are loaded
		if ( $params->get( 'show_icons', '1' ) ) {
			$text = JHTML::_('image.site',  'print.png', 'components/com_joodb/assets/images/', NULL, NULL, JText::_( 'Print' ) );
		} else {
			$text = JText::_( 'ICON_SEP' ) .'&nbsp;'. JText::_( 'Print' ) .'&nbsp;'. JText::_( 'ICON_SEP' );
		}

		$attribs['title']	= JText::_( 'Print' );
		$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
		$attribs['rel']     = 'nofollow';

		return JHTML::_('link', JRoute::_($url), $text, $attribs);
	}

	/**
 	* Returns a add to notepad or remove from notepad link
 	*
 	* @access public
 	* @param object Item
 	* @param object joobase
 	*/
	function getNotepadButton(&$item, &$joobase)
	{
		if (JRequest::getCmd('tmpl')!='component') {

		$application = & JFactory::getApplication();
		$params	= &$application->getParams();

		$task = (JRequest::getCmd('layout')=="notepad") ? 'removeFromNotepad'  : 'addToNotepad';
		$url  = 'index.php?option=com_joodb&view=catalog&layout=notepad&joobase='.$joobase->id.'&task='.$task.'&article='.$item->{$joobase->fid};
		$linktext = (JRequest::getCmd('layout')=="notepad") ?  JText::_('Remove from Notepad') : JText::_('Add to Notepad');
		// checks template image directory for image, if non found default are loaded
		if ( $params->get( 'show_icons', '1' ) ) {
			$icon = (JRequest::getCmd('layout')=="notepad") ? "remove.png" : "add.png";
			$text = JHTML::_('image.site',  $icon, 'components/com_joodb/assets/images/', NULL, NULL, $linktext );
		} else {
			$text = JText::_( 'ICON_SEP' ) .'&nbsp;'. $linktext .'&nbsp;'. JText::_( 'ICON_SEP' );
		}
		$attribs= array('title'=> $linktext);
		return JHTML::_('link', JRoute::_($url), $text,$attribs);
		}
	}

	/**
 	* Returns a back to prev page link
 	*
 	* @access public
 	*/
	function getBackbutton() {
		if ( JFactory::getApplication()->getParams()->get( 'show_icons', '1' ) ) {
			$text = JHTML::_('image.site',  "back.png", 'components/com_joodb/assets/images/', NULL, NULL,  JText::_('BACK') );
		} else {
			$text = JText::_( 'ICON_SEP' ) .'&nbsp;'.  JText::_('BACK') .'&nbsp;'. JText::_( 'ICON_SEP' );
		}
		return JHTML::_('link',  'javascript:history.back();', $text,array('title'=>  JText::_('BACK'),'class'=>'backbutton'));
	}

	/**
 	* Returns Search box for catalog view
 	*
 	* @access public
 	* @param  current Searchstring, Joobase
 	*/
	function getSearchbox($search="",&$joobase,$parameter)
	{
		$stext = JText::_('search...');
		$sval = ($search!="") ? $search : $stext;
		$searchform =  "<input class='inputbox searchword' type='text' onfocus='if(this.value==\"".$stext."\") this.value=\"\";' onblur='if(this.value==\"\") this.value=\"".$stext."\";' value='".$sval."'' size='20'' alt='".$stext."' maxlength='20' name='search'/>";
		if (!empty($parameter[0])) {
			$fields = @preg_split("/,/",$parameter[0]);
			$searchform .= "&nbsp;<select class='inputbox' name='searchfield'><option value='ALL'>".JText::_('All fields')."</option>" ;
			$rf = JRequest::getVar("searchfield");
			foreach ($fields as $field) {
				$field=trim($field);
				$searchform .= "<option value='".$field."' " ;
				if ($rf==$field) $searchform .= "selected";
				$searchform .= ">".ucfirst($field)."</option>" ;
			}
			$searchform .= "</select>" ;
		}
		$searchform .=  "&nbsp;<input class='button search' type='submit' value='".$stext."' >"
					   ."&nbsp;<input class='button reset' type='submit' value='".JText::_('Reset...')."' onmousedown='submitSearch(\"reset\");void(0);' >";
		return $searchform;
	}

	/**
 	* Returns a select-box of possible row to search for
 	* @access public
 	* @param  current Joobase, parameters, values
 	*/
	function getGroupselect(&$joobase,$parameter,$values)
	{
		$app = JFactory::getApplication();
		$gs =  $app->getUserStateFromRequest("com_joodb".$joobase->id.'.gs', 'gs',array(), 'array');
		$sv = (isset($gs[$parameter[0]])) ? $gs[$parameter[0]] : array();
		$size = (isset($parameter[1])) ? 'size="'.$parameter[1].'" multiple ' : "";
		$searchform = '<select class="inputbox groupselect" name="gs['.$parameter[0].'][]" '.$size.' >' ;
		if ((!isset($parameter[1]))) $searchform .= '<option value="">...</option>';
		foreach ($values as $value) {
			$selected = (array_search($value->delimeter.$value->value.$value->delimeter, $sv)!==false) ? 'selected="selected"' : '';
			if (!empty($value->value))
				$searchform .= '<option value="'.$value->delimeter.$value->value.$value->delimeter.'" '.$selected.'>'.$value->value.' ('.$value->count.')</option>';
		}
		$searchform .= "</select>";
		return $searchform;
	}


	/**
 	* Returns a roman alphabet to select the first letters ot the title
 	*
 	* @access public
 	* @param current Alphachar
 	*/
	function getAlphabox($alphachar, &$joobase)
	{
		$alphabox = "<div class='pagination alphabox'><ul>";
		$alphabet= array ('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
		foreach ($alphabet as $achar) {
			if ($achar==$alphachar) {
				$alphabox .= "<li><span class='active'>".ucfirst($achar)."</span></li>";
			} else {
				$alphabox .= "<li><a href='".JoodbHelper::_findItem($joobase,"&letter=".$achar)."'>".ucfirst($achar)."</a></li>";
			}
		}
		$alphabox .=  "<li><a href='".JoodbHelper::_findItem($joobase)."'>&raquo;".JText::_('All')."</a></li></ul></div>";
		return $alphabox;
	}

	/**
 	* Get complete link or only url to sort
 	*
 	* @access public
 	* @params fieldname fpr sort, [linktext]
 	*/
	function getOrderlink(&$parameter)
	{
		$app = JFactory::getApplication();
		$params	= &$app->getParams();
		$ordering = "ASC"; $orderclass = "";
		if ($app->getUserStateFromRequest('com_joodb.orderby', 'orderby', $params->get('orderby','fid'), 'string')==$parameter[0]) {
			$ordering = (strtolower(JRequest::getCMD('ordering')) == "asc") ? "DESC" : "ASC";
			$orderclass = strtolower(JRequest::getCMD('ordering'));
		}
		$link = JUri::current().'?orderby='.$parameter[0].'&ordering='.$ordering;
		if (count($parameter)>1) {
			$link = '<a href="'.$link.'" class="order '.$orderclass.'">'.$parameter[1]."</a>";
		}
		return $link;
	}


	/**
 	* Returns a captcha box
 	*
 	* @access public
 	*
 	*/
	function getCaptcha(){
		$captcha ="<div class='joocaptcha' style='margin: 5px 0;' >"
				."<img src='".Juri::root(false)."index.php?option=com_joodb&task=captcha' alt='captcha' style='width:200px; height:50px; margin: 5px 0; border: 1px solid black;'  />"
				."<br><input class='inputbox required' name='joocaptcha' id='joocaptcha' style='width:190px;' size='1' maxlength='5' /></div>";
		return $captcha;
	}

	/**
 	* Output of a captcha image
 	*
 	* @access public
 	*
 	*/
	function printCaptcha(){

		header("Content-Type: image/png");

	    // Generate code for Captcha
    	$code = "";
    	$codelength = 5;
    	$pool = "qwertzupasdfghkyxcvbnm23456789";
    	srand ((double)microtime()*1000000);
    	for($n = 0; $n < $codelength; $n++) {
            	$code .= substr($pool,(rand()%(strlen ($pool))), 1);
     	}

		$includepath=JPATH_ROOT."/components/com_joodb/assets/images/";
    	$fontsize=20;
	    // Get the size
    	$bbox = imagettfbbox($fontsize, 0, $includepath."captcha.ttf", $code);

	    // calculate size of the image
    	$x= $bbox[2]+(2*$bbox[3]);
    	$y= (-$bbox[7])+(2*$bbox[3]);
    	$background = imagecreatefromjpeg($includepath."captcha.jpg");

	    //prepare the image
    	$im  =  ImageCreateTrueColor ( 200,  50 );
    	$fill = ImageColorAllocate ( $im ,  0,  0, 0 );
    	$color = ImageColorAllocate ( $im , 235  , 235, 235 );

	    imagecopy($im,$background,0,0,rand(0,600),rand(0,500),200,50);
	    $startx = rand(5,110); $starty = rand(25,40);

	    // rotate and shift each char randomly
    	for($i=0; $i<$codelength; $i++) {
    		$ch = $code{$i};
    		ImageTTFText ($im, $fontsize, rand(-10,10) , $startx + (15*$i) , $starty , $color, $includepath."captcha.ttf", $ch);
    	}

	    ImagePNG ( $im );
    	ImageDestroy ($im);

    	// store the code to the session
    	$session =& JFactory::getSession();
	  	$session->set('joocaptcha',$code);

	}

	/**
	 * Output text... trigge content event before ...
	 * @param object $text
	 * @param array $params
	 */
	function printOutput(& $page, & $params) {
	    $dispatcher =& JDispatcher::getInstance();
     	JPluginHelper::importPlugin('content');
     	$version = new JVersion();
     	if ($version->RELEASE=="1.5") {
     		$results = $dispatcher->trigger('onPrepareContent', array (&$page, &$params));
     	} else {
     		$results = $dispatcher->trigger('onContentPrepare', array ('com_joodb.article', &$page, &$params,0));
     	}
     	echo $page->text;
	}


	/**
	 * Add new DS to joodb table
	 * @param $table var tablename
	 * @param $item array empty array
	 */
	function saveData($table,&$item) {
		$db	=& JFactory::getDBO();
		// load the jooDb object with table fiel infos
		$fields = $db->getTableFields($table,false);
		foreach ($fields[$table] as $fname=>$fcell) {
			if (isset($_POST[$fname])) {
				$typearr = preg_split("/\(/",$fcell->Type);
				switch ($typearr[0]) {
					case 'text' :
					case 'tinytext' :
					case 'mediumtext' :
					case 'longtext' :
					$item[$fname] = JRequest::getVar($fname, '', 'post', 'string');
					$item[$fname] = $db->getescaped(nl2br($item[$fname]));
				break;
					case 'int' :
					case 'tinyint' :
					case 'smallint' :
					case 'mediumint' :
					case 'bigint' :
					case 'year' :
					$item[$fname] = JRequest::getInt($fname);
				break;
					case 'date' :
					case 'datetime' :
					case 'timestamp' :
					$item[$fname] = ereg_replace("[^0-9\: \-]","",JRequest::getVar($fname, '', 'post', 'string'));
				break;
					case 'set' :
					$values = JRequest::getVar($fname, '', 'post');
					$item[$fname] = join(",",$values);
				break;
					default:
					$item[$fname] = $db->getescaped(JRequest::getVar($fname));
				}
			} else {
				if ($fcell->Null=="YES") {
					$item[$fname] = "NULL";
				}
			}
		}
		// INSERT dataset
		$insert = "";	$values = "";
		foreach ($item as $field => $value) {
			$insert .= "`".$field."`,";
			$values .= ($value=="NULL") ? "NULL," : "'".$value."',";
		}
		$db->setquery("INSERT INTO `".$table."` (".substr($insert,0,-1).") VALUES (".substr($values,0,-1)."); ");
		if(!$db->query()){
			$msg = JText::_( 'Error' )." : ".$db->getErrorMsg();
		} else {
			$msg = JText::_( 'Item Saved' );
		}
		return $msg;
	}

	/**
 	* Try to find menuitem for the database
 	*
 	* @access private
 	* @param id of the referring database
 	*/
	function _findItem($joobase,$params="")
	{
			return JRoute::_("index.php?option=com_joodb&view=catalog&joobase=".$joobase->id.$params,false);

	}

	/**
	 * Check the Authorization ...
	 * @param string $section
	 * @todo Jooml 1.6 ACL compatibilty
	 *
	 */
	function checkAuthorization($joobase, $section="accessd") {
		// get database parameters
		jimport('joomla.html.parameter');
		$jparams = new JParameter($joobase->params);

		// 0=public , 1=users only, 2=forbidden in frontend
		if ($jparams->get($section, 0) > 0) {
			// raise error only if access is special
			if ($jparams->get($section)==2) {
			  	JError::raiseError( 403, JText::_("ALERTNOTAUTH") );
			}
			// Do we have access to the data view? must be registered User
			$user = & JFactory::getUser();
			$version = new JVersion();

			if (!$user->authorize( 'login', 'site','users', 'registered' )) {
				$uri = JFactory::getURI();
				$return	= $uri->toString();
				$version = new JVersion();
				$option = ($version->RELEASE=="1.5") ? "com_user&view=login" : "com_users";
				$url  = JRoute::_('index.php?option='.$option.'&return=');
				$url .= base64_encode($return);
				$app = & JFactory::getApplication();
				$app->redirect($url, JText::_('Please login') );
			}
		}
	}

}

// a pure object class to keep parts
class joodbPart {
	//the joodb function
	var $function = false;
	//an array of parameters
	var $parameter = array();
	// the text to the next comand
	var $text = "";
}

?>
