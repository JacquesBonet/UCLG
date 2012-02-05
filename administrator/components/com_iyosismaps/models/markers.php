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

class IyosisMapsModelMarkers extends JModelList
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
		$query->from('#__iyosismaps_markers as m');
		$query->leftJoin('#__iyosismaps_icons as i ON m.iconid=i.id');
		$query->leftJoin('#__iyosismaps_maps as map ON map.id=m.mapid');
		$query->leftJoin('#__categories as c ON m.catid=c.id');
		// Select some fields
		$query->select('m.id, m.title, m.published, m.latitude, m.longitude, c.title as category, map.title as maptitle, i.icon as icon');
		return $query;
	}
}
