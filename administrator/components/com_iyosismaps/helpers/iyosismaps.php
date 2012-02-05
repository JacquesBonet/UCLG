<?php
/**
 * @package Iyosis Maps for Joomla!
 * @author Remzi Degirmencioglu
 * @copyright (C) 2011 www.iyosis.com
 * @license GNU General Public License
**/

// No direct access to this file
defined('_JEXEC') or die;

/**
 * IyosisMaps component helper.
 */
abstract class IyosisMapsHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_IYOSISMAPS_CONTROL_PANEL'),
			'index.php?option=com_iyosismaps&view=iyosismaps',
			$submenu == 'iyosismaps'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_IYOSISMAPS_MAPS'),
			'index.php?option=com_iyosismaps&view=maps',
			$submenu == 'maps'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_IYOSISMAPS_MARKERS'),
			'index.php?option=com_iyosismaps&view=markers',
			$submenu == 'markers'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_IYOSISMAPS_ICONS'),
			'index.php?option=com_iyosismaps&view=icons',
			$submenu == 'icons'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_IYOSISMAPS_POLYLINES'),
			'index.php?option=com_iyosismaps&view=polylines',
			$submenu == 'polylines'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_IYOSISMAPS_POLYGONS'),
			'index.php?option=com_iyosismaps&view=polygons',
			$submenu == 'polygons'
		);
		JSubMenuHelper::addEntry(JText::_('COM_IYOSISMAPS_CATEGORIES'),
			'index.php?option=com_categories&view=categories&extension=com_iyosismaps',
			$submenu == 'categories');
		// set some global property
		$document = JFactory::getDocument();
		if ($submenu == 'categories') 
		{
			$document->setTitle(JText::_('COM_IYOSISMAPS_ADMINISTRATION_CATEGORIES'));
		}
	}
}
