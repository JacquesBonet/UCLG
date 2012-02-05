<?php
/**
* @file joodb.php created 28.07.2011, 12:23:47
* @package		Joomla
* @author	feenders - dirk hoeschen (hoeschen@feenders.de)
* @abstract	custom component for client
* @link	http://www.feenders.de
* @copyright	Copyright (C) 2011 computer daten netze :: feenders
* @license		CC-GNU-LGPL
* @version  1.0
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class joodbModelImport extends JModel {

	/**
	 * Update dataset
	 * @param $id int id des datensatzes
	 * @param $item object
	 * @param $table string
	 */
	function saveEntry($id, $item,$table){
		$db = & $this->getDBO();
		$savestring = "";
		foreach ($item as $field => $value) {
			$savestring .= " `".$field."` = ".(($value!="") ? "'".mysql_escape_string($value)."'" : "NULL").",";
		}
		$query = "UPDATE `".$table."` SET " . substr($savestring,0,-1) . " WHERE id = $id";
		$db->setquery($query);
		if(!$db->query()){
			$this->setError($db->getErrorMsg());
			return false;
		} else { return true; }
	}

	/**
	 * Add new data
	 * @param object $item
	 * @param string $table
	 */
	function insertEntry($item,$table,$ferstellt=null){
		$db = & $this->getDBO();
		$insert = ""; $values = "";
		$empty = true;
		foreach ($item as $field => $value) {
			$insert .= "`".$field."`,";
			$values .= ($value=="") ? "NULL," : "'".mysql_escape_string($value)."',";
			if ($value!="") $empty = false;
		}
		if ($empty) return true;
		if($ferstellt) {
			$insert .= "`".$ferstellt."`,";
			$values .= "NOW(),";
		}
		$db->setquery("INSERT INTO `".$table."` (".substr($insert,0,-1).") VALUES (".substr($values,0,-1)."); ");
		if(!$db->query()){
			$this->setError($db->getErrorMsg());
			return false;
		} else { return true; }
	}

	/**
	 * Generates a table with primary key and pulished field ...
	 * @param array $columns
	 * @param string $tablename
	 */
	function generateTable($columns,$tablename) {
		$db = &$this->getDBO();
		$db->setquery("DROP TABLE IF EXISTS `".$tablename."`;");
		$db->query();
		$query = " CREATE TABLE IF NOT EXISTS `".$tablename."` ("
  				." `id` int(11) NOT NULL AUTO_INCREMENT, "
  				." `published` BOOLEAN NOT NULL DEFAULT '1' ,";
		foreach ($columns AS $n => $column) {
			if ($column->title=="id" && $column->title=="published") continue;
			$query .= " `".$column->title."` ".$column->type.",";
		}
		$query .= "PRIMARY KEY (`id`))";
		$db->setquery($query);
		if(!$db->query()){
			$this->setError($db->getErrorMsg());
			return false;
		} else { return true; }
	}

 /**
 * Import excel spredsheet into a table
 * @param array $file
 * @param string $table
 * @param boolean $column_names
 */
  function importXls($file,$table,$column_names) {
  	$app = JFactory::getApplication();
  	// find format from filetype
  	$ext = strrchr($file['name'],".");
  	switch ($ext) {
   		case ".xml":
   		$format = "Excel2003xml";
		break;
   		case ".xlsx":
   		$format = "Excel2007";
		break;
   		case ".xls":
   		$format = "Excel5";
		break;
   		default:
		$format = false;
	}
	if ($format!==false) {
		$or = PHPExcel_IOFactory::createReader($format);
		$or->setReadDataOnly(false);
		$oe = $or->load($file['tmp_name']);
		$ws = $oe->getActiveSheet();
		foreach ($ws->getRowIterator() as $row) {
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(true);
			// get table structure
			if (1 == $row->getRowIndex ()) {
				$columns = array();
				// get column names
				foreach ($cellIterator as $i => $cell)
					if (!is_null($cell)) {
						$columns[$i] = null;
						if ($column_names===true) {
						   $columns[$i]->title = str_replace(" ","_",preg_replace("/[^a-zA-Z0-9_\- ]/", "", $cell->getCalculatedValue()));
						} else {
						   $columns[$i]->title = "column_".sprintf( '%03d', $i);
						}
						$dcell = $ws->getCell($cell->getColumn()."2");
						$columns[$i]->type = $dcell->getDataType();
						$columns[$i]->format = $ws->getStyle($dcell->getCoordinate())->getNumberFormat()->getFormatCode();
						$f = strtolower($columns[$i]->format);
						if ($columns[$i]->type=="n" && PHPExcel_Shared_Date::isDateTime($dcell)) {
							if (strpos($f,"y")!==false) {
								$columns[$i]->format  = (strpos($f,"h")!==false) ? "yyyy-mm-dd hh:mm:ss" : "yyyy-mm-dd";
								$columns[$i]->type  = (strpos($f,"h")!==false) ? "datetime DEFAULT NULL" : "date DEFAULT NULL";
							} else {
								$columns[$i]->format = "hh:mm:ss";
								$columns[$i]->type = "time DEFAULT NULL";
							}
						} else if ($columns[$i]->type=="n") {
							if ($columns[$i]->format=="general" OR $columns[$i]->format=="0") {
							   $columns[$i]->type = "float DEFAULT NULL";
							} else $columns[$i]->type = "varchar(254) DEFAULT NULL";
						} else $columns[$i]->type = (strlen($dcell->getValue())>50) ? "text" : "varchar(254) DEFAULT NULL";
						if ($columns[$i]->format=="@") $columns[$i]->type =  "text";
					}
				// generate table
				if (!$this->generateTable($columns,$table)) {
					$error = $this->getErrors();
					$app->enqueueMessage($error[0],"error");
					return false;
				}
			}
			// import data
			if ((1 != $row->getRowIndex ()) || ($column_names===false)) {
				$item = array();
				foreach ($cellIterator as $i => $cell) {
					if (!is_null($cell) && isset($columns[$i]->title)) {
						$ws->getStyle($cell->getCoordinate())->getNumberFormat()->setFormatCode($columns[$i]->format);
						$item[$columns[$i]->title] = (!is_null($cell)) ? $cell->getFormattedValue() : "";
					}
				}
				if (!$this->insertEntry($item,$table)) {
					$error = $this->getErrors();
					$app->enqueueMessage($error[0],"error");
					return false;
				}
			}
		}
	} else {
		$app->enqueueMessage("Unknown file format");
		return false;
	}
	return true;
  }


}


?>