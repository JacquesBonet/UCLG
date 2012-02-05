<?php
/**
 * @package Iyosis Maps for Joomla!
 * @author Remzi Degirmencioglu
 * @copyright (C) 2011 www.iyosis.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

class IyosisMapsModelPolygons extends JModelList
{
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// From the table
		$query->from('#__iyosismaps_polygons as p');
		$query->leftJoin('#__iyosismaps_maps as map ON map.id=p.mapid');
		$query->leftJoin('#__categories as c ON p.catid=c.id');
		// Select some fields
		$query->select('p.id, p.title, p.published, c.title as category, map.title as maptitle');
		return $query;
	}
}
