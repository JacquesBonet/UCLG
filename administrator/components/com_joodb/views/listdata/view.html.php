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

class JooDBViewListdata extends JView
{
	function display($tpl = null)
	{

		$application = JFactory::getApplication();
		$joodbid	= JRequest::getCmd( 'joodbid');

		JRequest::setVar( 'hidemainmenu', 1 );

		$jb =& JTable::getInstance( 'joodb', 'Table' );
		$jb->load( $joodbid );

		JToolBarHelper::title(   $jb->name.': <small><small>['.JText::_( 'Edit Data' ).']</small></small>','joodb.png' );
		JToolBarHelper::addNew('editdata');
		JToolBarHelper::editListX('editdata');
		JToolBarHelper::deleteList('Realy Delete','removedata');
		JToolBarHelper::cancel( );

		// Initialize variables
		$db	=& $jb->getTableDBO();
		$filter		= null;

		// Get some variables from the request
		$option				= JRequest::getCmd( 'option' );
		$context			= 'com_joodb.joodbeditdata';
		$filter_order		= $application->getUserStateFromRequest( $context.'filter_order',		'filter_order',		$jb->fid,	'cmd' );
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
			$where= "WHERE ".$jb->ftitle." LIKE ".$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false )
					." OR ".$jb->fid."=".$db->Quote( $db->getEscaped( $search, true ), false );
		}

		// Get the total number of records
		$query = 'SELECT COUNT(*) FROM '.$jb->table.' AS c '.$where;
		$db->setQuery($query);
		$total = $db->loadResult();

		// Create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);

		// Get the titles
		$query = 'SELECT * FROM '.$jb->table.' AS c '.$where .$order;
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
		$lists['fid'] = $jb->fid;
		$lists['ftitle'] = $jb->ftitle;
		$lists['fstate'] = $jb->fstate;
		$lists['fcontent'] = $jb->fcontent;
		$lists['fdate'] = $jb->fdate;
		$lists['joodbid'] = $joodbid;

		$this->assignRef('lists',$lists);
		$this->assignRef('items',$rows);
		$this->assignRef('page',$pagination);
		parent::display($tpl);

	}
}
