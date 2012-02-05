<?php
/**
 * @package Iyosis Maps for Joomla!
 * @author Remzi Degirmencioglu
 * @copyright (C) 2011 www.iyosis.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// require helper file
JLoader::register('IyosisMapsHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'iyosismaps.php');

$document = JFactory::getDocument();
$document->addStyleSheet( 'components/com_iyosismaps/media/css/iyosismaps.css' );

// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by HelloWorld
$controller = JController::getInstance('IyosisMaps');
 
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
