<?php
/**
* @package		JooDatabase - http://joodb.feenders.de
* @copyright	Copyright (C) Computer - Daten - Netze : Feenders. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Dirk Hoeschen (hoeschen@feenders.de)
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Load the controller
require_once (JPATH_COMPONENT.DS.'controller.php');

// Require the com_content helper library
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'joodb.php' );
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'joodb.php');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

// Create the controller
$controller = new JoodbController( );

// Perform the Request task
$controller->execute(JRequest::getVar('task', null, 'default', 'cmd'));

// Redirect if set by the controller
$controller->redirect();

?>