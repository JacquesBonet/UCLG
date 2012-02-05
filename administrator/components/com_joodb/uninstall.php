<?php
/**
* @package		JooDatabase - http://joodb.feenders.de
* @copyright	Copyright (C) Computer - Daten - Netze : Feenders. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Dirk Hoeschen (hoeschen@feenders.de)
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
function com_uninstall(){
   global $errors;

  $db =& JFactory::getDBO();
//  $db->setQuery( "DROP TABLE IF EXISTS `#__joodb`" );
//  $db->query();
//  $db->setQuery( "DROP TABLE IF EXISTS `#__joodb_sample`" );
//  $db->query();
//  $db->setQuery( "DROP TABLE IF EXISTS `#__joodb_settings`" );
//  $db->query();

?>
	<center>
	<h2>Thank you for using JooDatabase</h2>
	<p>The component JooDatabase was succesfully uninstalled!</p>
	<p style="color: #d40000; font-weight: bold;">Remember: Your JooDB database tables jos_joodb and jos_joodb_settings where not removed.</p>
	</center>
	<br/>
<?php
}
?>
