<?php
/************************************************************************************
 mod_aidanews2 for Joomla 1.7 by Danilo A.

 @author: Danilo A. - dan@cdh.it

This file is a modification of standard Joomla hidden parameter.
Original file's copyright: 
 @version		$Id:hidden.php 6961 2007-03-15 16:06:53Z tcp $
 @package		Joomla.Framework
 @subpackage	Parameter
 @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 @license		GNU/GPL
 Joomla! is free software. This version may have been modified pursuant
 to the GNU General Public License, and as distributed it includes or
 is derivative of works licensed under the GNU General Public License or
 other free or open source software licenses.

 ----- This file is part of the AiDaNews2 Module. -----

    AiDaNews2 Module is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    AiDaNews2 is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this module.  If not, see <http://www.gnu.org/licenses/>.
************************************************************************************/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


jimport('joomla.html.html');
jimport('joomla.form.formfield');//import the necessary class definition for formfield


/**
 * Check if AiDaNews is updated
 * @since  1.7
 */
class JFormFieldupdatecheck extends JFormField {
	/**
	* The form field type.
	*
	* @var  string
	* @since	1.7
	*/
	protected $type = 'updatecheck'; //the form field type

	/**
	* Let's do this!
	*
	* @return	array	The field option objects.
	* @since	1.7
	*/
	protected function getInput() {
	  
		$fileName = "http://aimini.it/update/check/aida2_17.xml";

		echo '<div style="font-weight: bold; margin: 10px 0; float: left;">';
		
		if (function_exists('curl_init')) {
			// initialize a new curl resource
			$ch = curl_init();
			// set the url to fetch
			curl_setopt($ch, CURLOPT_URL, $fileName);
			// don't give me the headers just the content
			curl_setopt($ch, CURLOPT_HEADER, 0);
			// return the value instead of printing the response to browser
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// use a user agent to mimic a browser
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
			$xml = curl_exec($ch);
			// remember to always close the session and free all resources
			curl_close($ch);
			
			if ($xml) {
			
				$ver = '2.0.7'; // Change this parameter with every update! (No spaces)
				
				if ($xml == $ver) {
					echo JText::_('UPRECENT') . ' ' . $ver;
				}else{
					echo JText::_('CURVER') . ' ' . $ver . JText::_('SERVER') . ' ' . $xml . '<br/>' . JText::_('UPCLICK') . ' ' . '<a href="' . JText::_('UPDATEURL') . '">' . JText::_('UPHERE') . '</a>' . ' ' . JText::_('UPTOUPDATE') . '<br/>' . JText::_('UPHELP');
				}
			
			}
		
		} else {
			echo JText::_('UPNOCURL');
		}
		
		echo '</div>';
  
	}
}