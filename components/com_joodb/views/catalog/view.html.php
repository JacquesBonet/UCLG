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

JHTML::_('behavior.modal');


/**
 * HTML View class for the JooDatabase cataloges
 */
class JoodbViewCatalog extends JView
{
	var $joobase = null;
	var $items = null;
	var $params = null;
	var $pagination = null;

	function display($tpl = null)
	{
		$app = &JFactory::getApplication();

		// Load the menu object and parameters
		// Get some objects from the JApplication
		$pathway  =& $app->getPathway();
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true)."/components/com_joodb/assets/joodb.css");
		
		// Get the current menu item
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();
		$this->params	= &$app->getParams();

		// read database configuration from joobase table
		$this->joobase = &$this->get('joobase');

		//get the data page
		$this->items = &$this->get('data');

		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		if (!$this->params->get( 'page_title' ) )
			$this->params->set('page_title',	$this->joobase->name );

		if (!$this->params->get( 'page_heading' ) )
			$this->params->set('page_heading', $this->joobase->name);

		$document->setTitle( $this->joobase->name." - ".$app->getCfg("sitename"));

		$this->pagination = & $this->get('pagination');
		$this->params->set('search', $this->get('search'));
		$this->params->set('alphachar', $this->get('alphachar'));
		parent::display($tpl);
	}

	/**
	 * Parse Template part and replace with view specific elements
	 * @param array $parts - the split parts of the template
	 */
	function parseTemplate($parts)
	{
		$output = "";
		// replace item content with wildcards
    	foreach( $parts as $n => $part ) {
		  switch ($part->function) {
   			case ('pagenav'):
   				$output .= $this->pagination->getPagesLinks();
   				break;
   			case ('pagecount'):
   				$output .= $this->pagination->getPagesCounter();
   				break;
   			case ('resultcount'):
   				$output .=  $this->pagination->getResultCounter();
   				break;
   			case ('limitbox'):
   				$output .=  $this->pagination->getLimitBox();
   				break;
   			case ('searchbox'):
  				$output .=  JoodbHelper::getSearchbox($this->params->get('search'),$this->joobase,$part->parameter);
   				break;
			case ('groupselect'):
				$model = & $this->getModel();
				$values = $model->getColumnVals($part->parameter[0]);
				$output .= JoodbHelper::getGroupselect($this->joobase,$part->parameter,$values);
   				break;
   			case ('alphabox'):
   				$output .= JoodbHelper::getAlphabox($this->params->get('alphachar'),$this->joobase);
   				break;
			case ('orderlink'):
   				$output .= JoodbHelper::getOrderlink($part->parameter);
   				break;
			case ('exportbutton'):
   				$output .= "<input class='button export' type='submit' value='".(isset($part->parameter[0]) ? $part->parameter[0] : JText::_('Export XLS'))."' onmousedown='submitSearch(\"xportxls\");void(0);' >";
   				break;
			case ('resetbutton'):
   				$output .= "<input class='button reset' type='submit' value='".(isset($part->parameter[0]) ? $part->parameter[0] : JText::_('Reset'))."' onmousedown='submitSearch(\"reset\");void(0);' >";
   				break;
			case ('searchbutton'):
   				$output .= "<input class='button search' type='submit' value='".(isset($part->parameter[0]) ? $part->parameter[0] : JText::_('Search'))."' onmousedown='submitSearch();void(0);' >";
   				break;
		  }
   		  $output .= $part->text;
    	}
    	return $output;
	}

}
?>
