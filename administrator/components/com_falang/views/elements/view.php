<?php
/**
 * Joom!Fish - Multi Lingual extention and translation manager for Joomla!
 * Copyright (C) 2003 - 2011, Think Network GmbH, Munich
 *
 * All rights reserved.  The Joom!Fish project is a set of extentions for
 * the content management system Joomla!. It enables Joomla!
 * to manage multi lingual sites especially in all dynamic information
 * which are stored in the database.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * -----------------------------------------------------------------------------
 * $Id: view.php 1551 2011-03-24 13:03:07Z akede $
 * @package joomfish
 * @subpackage Views
 *
*/

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

JLoader::import( 'views.default.view',FALANG_ADMINPATH);

/**
 * HTML View class for the WebLinks component
 *
 * @static
 * @package		Joomla
 * @subpackage	Weblinks
 * @since 1.0
 */
class ElementsViewElements extends FalangViewDefault
{
	function display($tpl = null)
	{
		$document = JFactory::getDocument();
		// browser title
		$document->setTitle(JText::_('COM_FALANG_TITLE') . ' :: ' .JText::_('COM_FALANG_TITLE_CONTENT_ELEMENTS'));
		// set page title
		JToolBarHelper::title( JText::_( 'COM_FALANG_TITLE_CONTENT_ELEMENTS' ), 'extension' );
		
		$layout = $this->getLayout();
		if (method_exists($this,$layout)){
			$this->$layout($tpl);
		} else {
			$this->overview($tpl);
		}
		parent::display($tpl);
	}

	function overview($tpl = null) {
		// Set toolbar items for the page
		JToolBarHelper::custom("elements.installer","archive","archive", JText::_( 'COM_FALANG_INSTALL' ),false);
		JToolBarHelper::custom("elements.detail","preview","preivew", JText::_( 'COM_FALANG_DETAIL' ),true);
		JToolBarHelper::deleteList(JText::_("ARE YOU SURE YOU WANT TO DELETE THIS CE FILE"), "elements.remove");
//		JToolBarHelper::custom( 'cpanel.show', 'joomfish', 'joomfish', 'CONTROL PANEL' , false );
		JToolBarHelper::help( 'screen.elements', true);

		JSubMenuHelper::addEntry(JText::_('COM_FALANG_CONTROL_PANEL'), 'index.php?option=com_falang');
		JSubMenuHelper::addEntry(JText::_('COM_FALANG_TRANSLATION'), 'index.php?option=com_falang&amp;task=translate.overview');
		JSubMenuHelper::addEntry(JText::_('COM_FALANG_ORPHANS'), 'index.php?option=com_falang&amp;task=translate.orphans');
//		JSubMenuHelper::addEntry(JText::_('Manage Translations'), 'index.php?option=com_falang&amp;task=manage.overview', false);
//		JSubMenuHelper::addEntry(JText::_('Statistics'), 'index.php?option=com_falang&amp;task=statistics.overview', false);
//		JSubMenuHelper::addEntry(JText::_('Language Configuration'), 'index.php?option=com_falang&amp;task=languages.show', false);
		JSubMenuHelper::addEntry(JText::_('COM_FALANG_CONTENT_ELEMENTS'), 'index.php?option=com_falang&amp;task=elements.show', true);
		JSubMenuHelper::addEntry(JText::_('COM_FALANG_HELP_AND_HOWTO'), 'index.php?option=com_falang&amp;task=help.show', false);
	}
	
	function edit($tpl = null)
	{
		// Set toolbar items for the page
		JToolBarHelper::back();
//		JToolBarHelper::custom( 'cpanel.show', 'joomfish', 'joomfish', 'CONTROL PANEL' , false );
		JToolBarHelper::help( 'screen.elements', true);

		// hide the sub menu
		$this->_hideSubmenu();		
	}	

	function installer($tpl = null)
	{
		// browser title
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_FALANG_TITLE') . ' :: ' .JText::_('CONTENT ELEMENT INSTALLER'));
		
		// set page title
		JToolBarHelper::title( JText::_('COM_FALANG_TITLE') .' :: '. JText::_( 'CONTENT ELEMENT INSTALLER' ), 'falang' );

		// Set toolbar items for the page
		JToolBarHelper::custom( 'elements.show', 'back', 'back', JText::_( 'Back' ), false );
		JToolBarHelper::deleteList(JText::_("ARE YOU SURE YOU WANT TO DELETE THIS CE FILE"), "elements.remove_install");
		//JToolBarHelper::custom( 'cpanel.show', 'joomfish', 'joomfish', 'CONTROL PANEL' , false );
		JToolBarHelper::help( 'screen.elements', true);

		// hide the sub menu
		$this->_hideSubmenu();
	}	
}
