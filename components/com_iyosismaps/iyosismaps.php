<?php
/**
 * @package Iyosis Maps for Joomla! 1.5
 * @author Remzi Degirmencioglu
 * @copyright (C) 2011 www.iyosis.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by IyosisMaps
$controller = JController::getInstance('IyosisMaps');
 
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
