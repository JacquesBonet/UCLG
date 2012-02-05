<?php
/**
 * Joom!Fish - Multi Lingual extention and translation manager for Joomla!
 * Copyright (C) 2003 - 2011, Think Network GmbH, Munich
 *
 * All rights reserved.  The Joom!Fish project is a set of extentions for
 * the content management system Joomla!. It enables Joomla!
 * to manage multi lingual sites especially in all dynamic information
 * which are stored in the database.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * -----------------------------------------------------------------------------
 * $Id: controllerHelper.php 1462 2010-03-16 15:47:13Z akede $
 * @package joomfish
 * @subpackage helpers
 *
*/


defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * This helper includes various static methods that allow to access simple standard functions.
 * The collection of functions may be used by any extension such as the module, plugins or component
 * of the JoomFish collection.
 * 
 * External extensions may use the helper to refer or interact with the JoomFish extension
 * 
 * @package joomfish
 * @since	2.1
 */
class  FalangExtensionHelper  {
	
	private static $imagePath;
	
	/**
	 * Is JoomFish activated and ready to work?
	 * @return	true if the JoomFish extension is correctly installed, configured and activated
	 */
	public static function isFalangActive() {
		$db = JFactory::getDBO();
                //sbou
		//if (!is_a($db,"JFDatabase")){
		if (!is_a($db,"JDatabaseMySQLix") && !is_a($db,"JDatabaseMySQLx")){
                //fin sbou
			return false;
		}
		return true;
	}
	
	/**
	 * The method cleans the internal image path in order to force a re-check
	 * of images.
	 * @return void
	 */
	public static function cleanImagePathCache() {
		self::$imagePath = null;
	}
	

}
