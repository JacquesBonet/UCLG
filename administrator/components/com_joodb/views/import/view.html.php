<?php
/**
* @package		JooDatabase - http://joodb.feenders.de
* @copyright	Copyright (C) Computer - Daten - Netze : Feenders. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Dirk Hoeschen (hoeschen@feenders.de)
*/

// no direct access
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class JoodbViewImport extends JView
{
	var $bar = null;
	var $version = null;

	function display($tpl = null)
	{
		$this->version = new JVersion();
		$document =& JFactory::getDocument();
		$this->bar = & JToolBar::getInstance('toolbar');
	 	JToolBarHelper::title(JText::_( "Import" ), 'cpanel.png' );
		$this->bar->appendButton('Standard', 'forward', JText::_( "Continue" ), 'import',false);
		$template = JFactory::getApplication()->getTemplate();
		$document->addStyleSheet('components/com_joodb/assets/singleview.css');

		if ($this->version->RELEASE=="1.5") {
			$document->addStyleSheet('templates/'.$template.'/css/icon.css');
		}

		// Load the form validation behavior
		JHTML::_('behavior.formvalidation');
		parent::display($tpl);


	}

}