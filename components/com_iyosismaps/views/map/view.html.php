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

class IyosisMapsViewMap extends JView
{
	function display($tpl = null)
	{
		//$this->categories = $this->get('Categories');
		//$this->markers = $this->get('Markers');
		//$this->polylines = $this->get('Polylines');
		//$this->polygons = $this->get('Polygons');
		$this->javascript = $this->get('Javascript');
		$this->map = $this->get('Map');

		parent::display($tpl);
 
		// Set the document
		$this->setDocument();
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->addScript( 'http://maps.google.com/maps/api/js?sensor=false' );
	}
}

