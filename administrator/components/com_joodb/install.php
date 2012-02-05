<?php
/**
* @package		JooDatabase - http://joodb.feenders.de
* @copyright	Copyright (C) Computer - Daten - Netze : Feenders. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Dirk Hoeschen (hoeschen@feenders.de)
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

function com_install(){

	$joodb_version = "1.7";

 	@ini_set('max_execution_time',0);
	@ini_set('memory_limit','128M');

	$db =& JFactory::getDbo();
	$msgs = array();

	// Create image directory
	$imagedb = JPATH_SITE.DS.'images'.DS.'joodb'.DS;
	if (!file_exists($imagedb)){
		$msgs[] = (mkdir($imagedb, 0775)) ? "Imagedirectory ".$imagedb." was created" : "Unable to create image directory. Please make shure that /images ist writable!";}

	if ($version=get_installed_version()) { // update from old version
		$path = DS.'components'.DS.'com_joodb'.DS;
//		if (file_exists(JPATH_ADMINISTRATOR.$path)) @JFolder::delete(JPATH_ADMINISTRATOR.$path);
//		if (file_exists(JPATH_ROOT.$path)) @JFolder::delete(JPATH_ROOT.$path);
		$msgs[] = (insert_sql_file("update-".$version.".sql")) ? "JooDatabase was updated from version ".$version : "<b>Error</b> updating JooDatabase from version ".$version;
		$db->setQuery("DELETE FROM `#__joodb_settings` WHERE `name` = 'version' AND `jb_id` IS NULL ");
		$db->query();
	} else{
		$msgs[] = (insert_sql_file("install-complete.sql")) ? "JooDatabase SQL-tables were installed": "<b>Error</b> installing JooDatabase SQL-tables";
		// install complete
		$db =& JFactory::getDBO();
  		$db->setQuery( "UPDATE `#__joodb` SET `table` = '".$db->getPrefix()."joodb_sample' WHERE `#__joodb`.`id` =1 LIMIT 1 ;" );
  		$db->query();	}
	$db->setQuery("INSERT INTO `#__joodb_settings` (`name`, `value`) VALUES ('version', '".$joodb_version."')");
	$db->query();

?>
	<hr/>
	<h2>Thank you for using JooDatabase</h2>
	<h4>JooDatabase was made by</h4>
	<h3>Computer &sdot; Daten &sdot; Netze &bull; Feenders</h3>
	<ul>
		<li>Autor: Dirk Hoeschen (<a href="mailto:hoeschen@feenders.de">hoeschen@feenders.de</a>)</li>
	</ul>
	<p>Feenders does not offer free support for this version. However: If you need professional support or want individual modifications, ask for conditions.</p>
	<p>For more informations (user forum,help,FAQs and examples), look at <a href="http://joodb.feenders.de" target="_blank" title="joodb.feenders.de">joodb.feenders.de</a>.
	<br/>German support can be found at <a href="http://joodb.feenders.de/help/hilfe-ger.html" target="_blank">joodb.feenders.de/help/hilfe-ger.html</a></p>
	<p>Visit the <a href="http://joodb.feenders.de">JooDatabase site</a> for the lastest news and updates.</p>
	<br/>
	<b>Installation messages</b>
	<ul>
	<?php foreach ($msgs as $msg) echo "<li>".$msg."</li>"; ?>
	</ul>
<?php
}
/**
 * Check if joodb is already installed ...
 * Returns versionnumber. false if not exist.
 */
function get_installed_version(){
	$db =& JFactory::getDBO();
  	$db->setQuery("SELECT id FROM `#__joodb` LIMIT 0 , 1");
  	if ($test=$db->loadResult()){
		$db->setQuery("SELECT value FROM `#__joodb_settings` WHERE  `name` = 'version' LIMIT 0 , 1");
		return ($version=$db->loadResult()) ? $version : "1.0";
  	} else{
  		return false;}}

/**
 * Insert a sql file into database
 * @param string $filename
 */
function insert_sql_file($filename){
    $path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joodb'.DS.'assets'.DS;
	if (file_exists($path.$filename)){
		$db =& JFactory::getDBO();
		$lines = file($path.$filename);
		$query="";
		foreach ($lines as $sqlstr){
			$query .= trim($sqlstr);
			if (substr($query, -1)==";"){
				$db->setQuery($query);
				$db->query();
				if ($db->getErrorNum()>0) return false;
				$query="";
			}
		}
	}
	return true;}


?>
