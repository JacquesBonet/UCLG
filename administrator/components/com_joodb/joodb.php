<?php
/**
* @package		JooDatabase - http://joodb.feenders.de
* @copyright	Copyright (C) Computer - Daten - Netze : Feenders. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Dirk Hoeschen (hoeschen@feenders.de)
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/*
 * Make sure the user is authorized to view this page
 */
$version = new JVersion();
if ($version->RELEASE>="1.6") {
	if (!JFactory::getUser()->authorise('core.manage', '')) {
		return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	}
}

// Set the table directory
JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

// Require the helper library
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'joodb.php' );

// add some custem styles
$document =& JFactory::getDocument();
$document->addStyleSheet('components/com_joodb/assets/joodb.css');

// get the controller
if ($controllerName = JRequest::getCmd( 'controller')) {
	$controllerName = 'JooDBController'.$controllerName;
	require_once( JPATH_COMPONENT.DS.'controllers'.DS.$controllerName.'.php' );
} else {
	$controllerName = 'JooDBController';
	require_once( JPATH_COMPONENT.DS.'controller.php' );
}
$controller = new $controllerName( array('default_task' => 'display') );
$controller->registerTask('apply', 'save', 'unpublish','publish');
$controller->execute( JRequest::getCmd( 'task' ) );
$controller->redirect();

?>
