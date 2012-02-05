<?php
/**
* @package		JooDatabase - http://joodb.feenders.de
* @copyright	Copyright (C) Computer - Daten - Netze : Feenders. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Dirk Hoeschen (hoeschen@feenders.de)
*/

function JoodbBuildRoute(&$query)
{
	$segments = array();

	// get a menu item based on Itemid or currently active
	$app		= JFactory::getApplication();
	$menu		= $app->getMenu();
	// we need a menu item.  Either the one specified in the query, or the current active one if none specified
	if (empty($query['Itemid'])) {
		$menuItem = $menu->getActive();
		$menuItemGiven = false;
	} else {
		$menuItem = $menu->getItem($query['Itemid']);
		$menuItemGiven = true;
	}

	$mView	= (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
	$mId	= (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];

	if(isset($query['view']))
	{
		$segments[] = $query['view'];
		unset($query['view']);
	};

	// are we dealing with an article that is attached to a menu item?
	if (($mView == 'article') and (isset($query['id'])) and ($mId == intval($query['id']))) {
		unset($query['view']);;
		unset($query['id']);
	}

	if (empty($query['Itemid'])) {
		if(isset($query['joobase']))	{
			$segments[] = $query['joobase'];
			unset($query['joobase']);
		};
	} else if(isset($query['joobase'])) unset($query['joobase']);

	if(isset($query['id'])) {
		$segments[] = $query['id'];
		unset($query['id']);
	};

	return $segments;
}

function JoodbParseRoute( $segments )
{
	$vars = array();

	//Get the active menu item
	$menu =& JSite::getMenu();
	$item =& $menu->getActive();

	// Count route segments
	$count = count($segments)-1;

	//routing for articles if menu item unknown joodb ID is included
	if($count>=2) {
		$vars['view']  = $segments[$count-2];
		$id = explode( ':', $segments[$count-1] );
		$vars['joobase']  = $id[0];
		$id = explode( ':', $segments[$count] );
		$vars['id']    = $id[0];
	} else if($count==1) {
		$vars['view'] = $segments[0];
		$id = explode( ':', $segments[1] );
		$vars['id']    = $id[0];
	} else {
		$vars['view'] = $segments[0];
	}

	return $vars;
}
