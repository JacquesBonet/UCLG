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

class JooDBViewJooDB extends JView
{
	function display($tpl = null)
	{
		$application = &JFactory::getApplication();

		$text = JText::_( 'Databases' );
		JToolBarHelper::title(   JText::_( "JooDatabase" ).': <small><small>['.$text.']</small></small>','joodb.png' );
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Popup', 'restore','Import', 'index.php?option=com_joodb&amp;tmpl=component&amp;view=import', "680", "350" );
		$bar->appendButton( 'Popup', 'new','New', 'index.php?option=com_joodb&amp;tmpl=component&amp;view=joodbentry&amp;layout=step1&amp;task=addnew', "680", "350" );
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList('Really delete');
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::cancel( 'exitjoodb' );
		$bar->appendButton( 'Popup', 'help','Help', 'http://joodb.feenders.de/help.html', "980", "600" );

		// init toolbar
		JSubMenuHelper::addEntry(JText::_('Databases'), 'index.php?option=com_joodb',true);
		JSubMenuHelper::addEntry(JText::_('About JooDatabase'), 'index.php?option=com_joodb&view=info',false);


		// Initialize variables
		$db			=& JFactory::getDBO();
		$filter		= null;

		// Get some variables from the request
		$option				= JRequest::getCmd( 'option' );
		$context			= 'com_joodb.joodb';
		$filter_order		= $application->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'id',	'cmd' );
		$filter_order_Dir	= $application->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'DESC',	'word' );
		$filter_state		= $application->getUserStateFromRequest( $context.'filter_state',		'filter_state',		'',	'word' );
		$search				= $application->getUserStateFromRequest( $context.'search',			'search',			'',	'string' );
		$search				= JString::strtolower($search);

		$limit		= $application->getUserStateFromRequest('global.list.limit', 'limit', $application->getCfg('list_limit'), 'int');
		$limitstart	= $application->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');

		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ( $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0 );

		$order = ' ORDER BY '. $filter_order .' '. $filter_order_Dir;
		$all = 1;

		$where = "";

		// Keyword filter
		if ($search) {
			$where= "WHERE name LIKE ".$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
					." OR id=".$db->Quote( $db->getEscaped( $search, true ), false );
		}

		// Get the total number of records
		$query = 'SELECT COUNT(*) FROM #__joodb AS c '.$where;
		$db->setQuery($query);
		$total = $db->loadResult();

		// Create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);

		// Get the titles
		$query = 'SELECT * FROM #__joodb AS c '.$where .$order;
		$db->setQuery($query, $pagination->limitstart, $pagination->limit);
		$rows = $db->loadObjectList();

		// If there is a database query error, throw a HTTP 500 and exit
		if ($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		$lists['search'] = $search;

		$this->assignRef('lists',$lists);
		$this->assignRef('items',$rows);
		$this->assignRef('page',$pagination);
		parent::display($tpl);

	}
}
