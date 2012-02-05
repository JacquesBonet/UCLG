<?php
/**
 * @package Iyosis Maps for Joomla!
 * @author Remzi Degirmencioglu
 * @copyright (C) 2011 www.iyosis.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

class IyosisMapsViewCsv extends JView
{
	function display($tpl = null)
	{
		$this->task = JRequest::getVar( 'task' );

		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Set the toolbar
		$this->addToolBar();
 
		// Display the template
		parent::display($tpl);
 
		// Set the document
		$this->setDocument();
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		if ($this->task == 'importMarker') $text = JText::_( 'COM_IYOSISMAPS_MARKER' )." ".JText::_( 'COM_IYOSISMAPS_IMPORT' );
		else $this->task = JText::_( 'COM_IYOSISMAPS_MARKER' )." ".JText::_( 'COM_IYOSISMAPS_EXPORT' );
		JToolBarHelper::title(   JText::_( 'COM_IYOSISMAPS_CSV' ).': <small><small>[ ' . $text.' ]</small></small>' );
		if ($this->task == 'importMarker') JToolBarHelper::custom('csv.importMarker2', 'importdata.png', 'importdata.png','COM_IYOSISMAPS_IMPORT', false);
		else JToolBarHelper::custom('csv.exportMarker', 'exportdata.png', 'exportdata.png','COM_IYOSISMAPS_EXPORT', false);
		JToolBarHelper::custom('csv.'.$this->task.'Cancel', 'cancel', 'cancel', 'JTOOLBAR_CLOSE', false);
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_IYOSISMAPS_ADMINISTRATION'));
	}
}
