<?php
/**
* @package		JooDatabase - http://joodb.feenders.de
* @copyright	Copyright (C) Computer - Daten - Netze : Feenders. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Dirk Hoeschen (hoeschen@feenders.de)
*
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the JooDatabase singel element
 */
class JoodbViewForm extends JView
{
	var $joobase = null;
	var $params = null;
	var $menu = null;

	function display($tpl = null)
	{
		$application = &JFactory::getApplication();
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true)."/components/com_joodb/assets/joodb.css");
		
		// Get the current menu item
		$menus	= &JSite::getMenu();
		$this->menu	= $menus->getActive();

		$this->params= & $application->getParams();

		// read database configuration from joobase table
		$this->joobase = &$this->get('joobase');

		JHTML::_('behavior.formvalidation');

		parent::display($tpl);
	}

	/**
 	* Parse Template part and replace with view specific elements
 	*/
	function parseTemplate(&$joobase, &$parts)
	{
		$output = "";
		// replace item content with wildcards
    	foreach( $parts as $n => $part ) {
		  switch ($part->function) {
   			case ('submitbutton'):
   				$output .= '<button class="button validate"  onmousedown="validateForm();" type="submit">'.JText::_('Send')."</button>";
   				break;
   			case ('captcha'):
  				$output .=  JoodbHelper::getCaptcha();
   				break;
   			case ('form'):
  				$output .=  JoodbHelper::getFormField($joobase, $part->parameter);
   				break;
   			case ('imageupload'):
  				$output .=  '<input name="joodb_dataset_image" class="inputbox" type="file" accept="image/*" />';
   				break;
		  }
   		  $output .= $part->text;
    	}
    	return $output;
	}

}
?>
