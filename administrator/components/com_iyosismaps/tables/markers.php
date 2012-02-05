<?php
/**
 * @package Iyosis Maps for Joomla! 1.5
 * @author Remzi Degirmencioglu
 * @copyright (C) 2011 www.iyosis.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class IyosisMapsTableMarkers extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__iyosismaps_markers', 'id', $db);
	}
}
