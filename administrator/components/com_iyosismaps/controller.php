<?php
/**
 * @package Iyosis Maps for Joomla! 1.5
 * @author Remzi Degirmencioglu
 * @copyright (C) 2011 www.iyosis.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * General Controller of HelloWorld component
 */
class IyosisMapsController extends JController
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			$cachable	If true, the view output will be cached
	 * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false) 
	{
		// set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view', 'iyosismaps'));

		// Load the submenu
		IyosisMapsHelper::addSubmenu(JRequest::getCmd('view', 'iyosismaps'));
 
		// call parent behavior
		parent::display($cachable);
	}
}
