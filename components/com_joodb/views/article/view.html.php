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
 * HTML View class for the JooDatabase single element
 */
class JoodbViewArticle extends JView
{
	var $joobase = null;
	var $item = null;
	var $params = null;

	function display($tpl = null)
	{

		$application = &JFactory::getApplication();
		// Load the menu object and parameters
		// Get some objects from the JApplication
		$pathway  =& $application->getPathway();
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true)."/components/com_joodb/assets/joodb.css");
		
		// Get the current menu item
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();
		$this->params = & $application->getParams();

		//get the data page
		$this->item =& $this->get('data');

		// read database configuration from joobase table
		$this->joobase = &$this->get('joobase');

		if (!$this->params->get( 'page_title' ) )
			$this->params->set('page_title',	JText::_( $this->joobase->name ));

		if (!$this->params->get( 'page_heading' ) )
			$this->params->set('page_heading',JText::_( $this->joobase->name ));

		$document	= &JFactory::getDocument();
		$document->setTitle( $this->item->{$this->joobase->ftitle}." - ".$this->joobase->name." - ".$application->getCfg('sitename') );

		// we dont want to lin title fields in single view
		$this->params->set('link_titles',false);

		JHTML::_('behavior.modal');

		parent::display($tpl);
	}
}
?>
