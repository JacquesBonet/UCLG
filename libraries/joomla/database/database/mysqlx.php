<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Database
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

JLoader::register('JDatabaseMySQL', dirname(__FILE__).'/mysql.php');
JLoader::register('JDatabaseQueryMySQL', dirname(__FILE__).'/mysqlquery.php');
JLoader::register('JDatabaseExporterMySQL', dirname(__FILE__).'/mysqlexporter.php');
JLoader::register('JDatabaseImporterMySQL', dirname(__FILE__).'/mysqlimporter.php');

require_once( JPATH_SITE.'/components/com_falang/helpers/defines.php' );
require_once( JPATH_SITE.'/components/com_falang/helpers/falang.class.php' );
require_once( JPATH_SITE."/administrator/components/com_falang/classes/FalangManager.class.php");

/**
 * MySQLi database driver
 *
 * @package     Joomla.Platform
 * @subpackage  Database
 * @see         http://php.net/manual/en/book.mysql.php
 * @since       11.1
 */
class JDatabaseMySQLx extends JDatabaseMySQL
{

	/**
	 * The character(s) used to quote SQL statement names such as table names or field names,
	 * etc.  The child classes should define this as necessary.  If a single character string the
	 * same character is used for both sides of the quoted name, else the first character will be
	 * used for the opening quote and the second for the closing quote.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $nameQuote = '`';

	/**
	 * The null or zero representation of a timestamp for the database driver.  This should be
	 * defined in child classes to hold the appropriate value for the engine.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $nullDate = '0000-00-00 00:00:00';

	/**
	 * Constructor.
	 *
	 * @param   array  $options  List of options used to configure the connection
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */

        //Joomfish import
	/** @var array list of multi lingual tables */
	var $_mlTableList=null;
	/** @var Internal variable to hold array of unique tablenames and mapping data*/
	var $_refTables=null;

	/** @var Internal variable to hold flag about whether setRefTables is needed - JF queries don't need it */
	var $_skipSetRefTables = false;

	var $orig_limit	= 0;
	var $orig_offset	= 0;
        //fin joomfish
        //add sbou
        var $_table_prefix = null;

        //internal variable to know if we are on the site or admin section
        protected $_isSite = false;
	var $profileData = array();
    
	protected function __construct($options)
	{
            parent::__construct($options);
            $this->_table_prefix = $options['prefix'];
            $pfunc = $this->_profile();

            $query = "select distinct reference_table from #__falang_content";
            $this->setQuery( $query );
            $this->_skipSetRefTables = true;
            $this->_mlTableList = $this->loadColumn(0,false);
            $this->_skipSetRefTables = false;
            if( !$this->_mlTableList ){
                    if ($this->getErrorNum()>0){
                            JError::raiseWarning( 200, JTEXT::_('No valid table list:') .$this->getErrorMsg());
                    }
            }

            $pfunc = $this->_profile($pfunc);
	}



	/**
	 * Gets an exporter class object.
	 *
	 * @return  JDatabaseExporterMySQL  An exporter object.
	 *
	 * @since   11.1
	 * @throws  DatabaseException
	 */
	public function getExporter()
	{
		// Make sure we have an exporter class for this driver.
		if (!class_exists('JDatabaseExporterMySQL')) {
			throw new DatabaseException(JText::_('JLIB_DATABASE_ERROR_MISSING_EXPORTER'));
		}

		$o = new JDatabaseExporterMySQL;
		$o->setDbo($this);

		return $o;
	}

	/**
	 * Gets an importer class object.
	 *
	 * @return  JDatabaseImporterMySQLi  An importer object.
	 *
	 * @since   11.1
	 * @throws  DatabaseException
	 */
	public function getImporter()
	{
		// Make sure we have an importer class for this driver.
		if (!class_exists('JDatabaseImporterMySQL')) {
			throw new DatabaseException(JText::_('JLIB_DATABASE_ERROR_MISSING_IMPORTER'));
		}

		$o = new JDatabaseImporterMySQL;
		$o->setDbo($this);

		return $o;
	}

	/**
	 * Description
	 *
	 * @access public
	 * @return int The number of rows returned from the most recent query.
	 */
	function getNumRows( $cur=null, $translate=true, $language=null )
	{
		$count = parent::getNumRows($cur);
		if (!$translate) return $count;

		// setup Joomfish plugins
		$dispatcher	   = JDispatcher::getInstance();
		JPluginHelper::importPlugin('joomfish');

		// must allow fall back for contnent table localisation to work
		$allowfallback = true;
		$refTablePrimaryKey = "";
		$reference_table = "";
		$ids="";
		$jfm = FalangManager::getInstance();
		$this->_setLanguage($language);
		$registry = JFactory::getConfig();
		$defaultLang = $registry->getValue("config.defaultlang");
		if ($defaultLang == $language){
			$rows = array($count);
			$dispatcher->trigger('onBeforeTranslation', array (&$rows, &$ids, $reference_table, $language, $refTablePrimaryKey, $this->getRefTables(), $this->sql, $allowfallback));
			$count = $rows[0];
			return $count;
		}

		$rows = array($count);

		$dispatcher->trigger('onBeforeTranslation', array (&$rows, &$ids, $reference_table, $language, $refTablePrimaryKey, $this->getRefTables(), $this->sql, $allowfallback));

		$dispatcher->trigger('onAfterTranslation', array (&$rows, &$ids, $reference_table, $language, $refTablePrimaryKey, $this->getRefTables(), $this->sql, $allowfallback));
		$count = $rows[0];
		return $count;
	}

	/**
	 * Execute the SQL statement.
	 *
	 * @return  mixed  A database cursor resource on success, boolean false on failure.
	 *
	 * @since   11.1
	 * @throws  DatabaseException
	 */
	public function query()
	{

        $success = parent::query();
		if ($this->_isSite && $success && !$this->_skipSetRefTables){
			$this->setRefTables();
		}
                return $this->cursor;
	}




//sbou falang method
	/**
	* Overwritten Database method to loads the first field of the first row returned by the query.
	*
	* @return The value returned in the query or null if the query failed.
	*/
	function loadResult( $translate=true, $language=null ) {
		if (!$translate){
			$this->_skipSetRefTables=true;
			$result = parent::loadResult();
			$this->_skipSetRefTables=false;
			return $result;
		}
		$result=null;
		$ret=null;

		$result = $this->_loadObject( $translate, $language );

		$pfunc = $this->_profile();

		if( $result != null ) {
			$fields = get_object_vars( $result );
			$field = each($fields);
			$ret = $field[1];
		}

		$pfunc = $this->_profile($pfunc);

		return $ret;
	}

	function loadObjectList( $key='', $translate=true, $language=null ) {
		//sbou
                //sbou TODO check récursive pb
                //$jfManager = FalangManager::getInstance();

		if (!$translate) {
			$this->_skipSetRefTables=true;
			$result = parent::loadObjectList( $key );
			$this->_skipSetRefTables=false;
			return $result;
		}

		$result = parent::loadObjectList( $key );

//		if( isset($jfManager)) {
//			$this->_setLanguage($language);
//		}

		// TODO check the impact of this on frontend translation
		// It does stop Joomfish plugins from working on missing translations e.g. regional content so disable for now
		// Don't do it for now since translation caching is so effective
		/*
		$registry = JFactory::getConfig();
		$defaultLang = $registry->getValue("config.defaultlang");
		if ($defaultLang == $language){
		$translate = false;
		}
		*/

                //sbou TODO this is not the right solution.
//		if( isset($jfManager)) {
                if (true){
			$doTranslate=false;
			$tables =$this->getRefTables();
			if ($tables == null) return $result; // an unstranslatable query to return result as is
			// if we don't have "fieldTablePairs" then we can't translate
			if (!array_key_exists("fieldTablePairs",$tables)){
				return $result;
			}
			foreach ($tables["fieldTablePairs"] as $i=>$table) {
				if ($this->translatedContentAvailable($table)) {
					$doTranslate=true;
					break;
				}
			}
			if ($doTranslate ) {
				$pfunc = $this->_profile();
                                //sbou TODO cache desactived
//				if ($jfManager->getCfg("transcaching",1)){
                                if (false) {
					// cache the results
					// TODO call based on config
					//$cache = JFactory::getCache('jfquery');
                                        $cache = $jfManager->getCache($language);
					$this->orig_limit	= $this->limit;
					$this->orig_offset	= $this->offset;
					$result = $cache->get( array("JoomFish", 'translateListCached'), array($result, $language, $this->getRefTables() ));
					$this->orig_limit	= 0;
					$this->orig_offset	= 0;
				}
				else {
					$this->orig_limit	= $this->limit;
					$this->orig_offset	= $this->offset;
					Falang::translateList( $result, $language, $this->getRefTables() );
					$this->orig_limit	= 0;
					$this->orig_offset	= 0;
				}
				$pfunc = $this->_profile($pfunc);
			}
		}
		return $result;
	}

	/**
	 * private function to handle the requirement to call different loadObject version based on class
	 *
	 * @param boolran $translate
	 * @param string $language
	 */
	function _loadObject( $translate=true, $language=null ) {
		return $this->loadObject();
	}


//joomfish method
	function setRefTables(){

		$pfunc = $this->_profile();

		if($this->cursor===true || $this->cursor===false) {
			$pfunc = $this->_profile($pfunc);
			return;
		}

		// Before joomfish manager is created since we can't translate so skip this anaylsis
                //sbou TODO vérifier le besoin
//		$jfManager = FalangManager::getInstance();
//		if (!$jfManager) return;

		// only needed for selects at present - possibly add for inserts/updates later
                if (is_a($this->sql,'JDatabaseQueryMySQL')) {
                   $tempsql = $this->sql->__toString();
                } else {
   		   $tempsql = $this->sql;
                }
        //use tempprefixsql for mysql only driver
        $tempprefixsql = $this->replacePrefix((string) $tempsql);


        if (strpos(strtoupper(trim($tempsql)),"SELECT")!==0) {
			$pfunc = $this->_profile($pfunc);
			return;
		}

		$config = JFactory::getConfig();

		// get column metadata
		$fields = $this->_getFieldCount();

		if ($fields<=0) {
			$pfunc = $this->_profile($pfunc);
			return;
		}

		$this->_refTables=array();
		$this->_refTables["fieldTablePairs"]=array();
		$this->_refTables["tableAliases"]=array();
		$this->_refTables["reverseTableAliases"]=array();
		$this->_refTables["fieldAliases"]=array();
		$this->_refTables["fieldTableAliasData"]=array();
		$this->_refTables["fieldCount"]=$fields;
		// Do not store sql in _reftables it will disable the cache a lot of the time

		$tableAliases = array();
		for ($i = 0; $i < $fields; ++$i) {
			$meta = $this->_getFieldMetaData($i);
			if (!$meta) {
				echo JText::_("No information available<br />\n");
			}
			else {
				$tempTable =  $meta->table;
				// if I have already found the table alias no need to do it again!
				if (array_key_exists($tempTable,$tableAliases)){
					$value = $tableAliases[$tempTable];
				}
				// mysqli only
                else if (isset($meta->orgtable)){
                    $value = $meta->orgtable;
                    if (isset($this->_table_prefix) && strlen($this->_table_prefix)>0 && strpos($meta->orgtable,$this->_table_prefix)===0) $value = substr($meta->orgtable, strlen( $this->_table_prefix));
                    $tableAliases[$tempTable] = $value;
				}
				else {
                    if (!isset($tempTable) || strlen($tempTable)==0) {
                        continue;
                    }
                    //echo "<br>Information for column $i of ".($fields-1)." ".$meta->name." : $tempTable=";
                    $tempArray=array();
                    //sbou TODO optimize this section
                    $prefix = $this->_table_prefix;

                    preg_match_all("/`?$prefix(\w+)`?\s+(?:AS\s)?+`?".$tempTable."`?[,\s]/i",$tempprefixsql, $tempArray, PREG_PATTERN_ORDER);
                    //preg_match_all("/`?$prefix(\w+)`?\s+AS\s+`?".$tempTable."`?[,\s]/i",$this->_sql, $tempArray, PREG_PATTERN_ORDER);
                    if (count($tempArray)>1 && count($tempArray[1])>0) $value = $tempArray[1][0];
                    else $value = null;
                    if (isset($this->_table_prefix) && strlen($this->_table_prefix)>0 && strpos($tempTable,$this->_table_prefix)===0) $tempTable = substr($tempTable, strlen( $this->_table_prefix));
                    $value = $value?$value:$tempTable;
                    $tableAliases[$tempTable]=$value;
				}
                                
                if ((!($value=="session" || strpos($value,"jf_")===0)) && $this->translatedContentAvailable($value)){
                    /// ARGH !!! I must also look for aliases for fieldname !!
                    if (isset($meta->orgname)){
                        $nameValue = $meta->orgname;
                    }
                    else {
                        $tempName = $meta->name;
                        $tempArray=array();
                        // This is a bad match when we have "SELECT id" at the start of the query
                        preg_match_all("/`?(\w+)`?\s+(?:AS\s)?+`?".$tempName."`?[,\s]/i",$tempprefixsql, $tempArray, PREG_PATTERN_ORDER);
                        //preg_match_all("/`?(\w+)`?\1s+AS\s+`?".$tempName."`?[,\s]/i",$this->_sql, $tempArray, PREG_PATTERN_ORDER);
                        if (count($tempArray)>1 && count($tempArray[1])>0) {
                            //echo "$meta->name is an alias for ".$tempArray[1][0]."<br>";
                            // must ignore "SELECT id"
                            if (strtolower($tempArray[1][0])=="select"){
                                $nameValue = $meta->name;
                            }
                            else {
                                $nameValue = $tempArray[1][0];
                            }
                        }
                        else $nameValue = $meta->name;
                    }

                    if (!array_key_exists($value,$this->_refTables["tableAliases"])) $this->_refTables["tableAliases"][$value]=$meta->table;
                    if (!array_key_exists($meta->table,$this->_refTables["reverseTableAliases"])) $this->_refTables["reverseTableAliases"][$meta->table]=$value;

                    // I can't use the field name as the key since it may not be unique!
                    if (!in_array($value,$this->_refTables["fieldTablePairs"])) $this->_refTables["fieldTablePairs"][]=$value;
                    if (!array_key_exists($nameValue,$this->_refTables["fieldAliases"])) $this->_refTables["fieldAliases"][$meta->name]=$nameValue;

                    // Put all the mapping data together so that everything is in sync and I can check fields vs aliases vs tables in one place
                    $this->_refTables["fieldTableAliasData"][$i]=array("fieldNameAlias"=>$meta->name, "fieldName"=>$nameValue,"tableNameAlias"=>$meta->table,"tableName"=>$value);

				}

			}
		}
		$pfunc = $this->_profile($pfunc);
	}

	function _profile($func = "", $forcestart=false){
		if (!$this->debug) return "";
		// start of function
		if ($func==="" || $forcestart){
			if (!$forcestart){
				$backtrace = debug_backtrace();
				if (count($backtrace)>1){
					if (array_key_exists("class",$backtrace[1])){
						$func = $backtrace[1]["class"]."::".$backtrace[1]["function"];
					}
					else {
						$func = $backtrace[1]["function"];
					}
				}
			}
			if (!array_key_exists($func,$this->profileData)){
				$this->profileData[$func]=array("total"=>0, "count"=>0);
			}
			if (!array_key_exists("start",$this->profileData[$func])) {
				$this->profileData[$func]["start"]=array();
			}
			list ($usec,$sec) = explode(" ", microtime());
			$this->profileData[$func]["start"][] = floatval($usec)+floatval($sec);
			$this->profileData[$func]["count"]++;
			return $func;
		}
		else {
			if (!array_key_exists($func,$this->profileData)){
				exit("JFProfile start not found for function $func");
			}
			list ($usec,$sec) = explode(" ", microtime());
			$laststart = array_pop($this->profileData[$func]["start"]);
			$this->profileData[$func]["total"] += (floatval($usec)+floatval($sec)) - $laststart;
		}
	}

	/**
	 * Overwritten Load column of single field results into an array
	 * see loadResultArray on Joomfish
	 * @access	public
	 */
	function loadColumn($numinarray = 0,  $translate=true, $language=null){
		if (!$translate){
			//return parent::loadResultArray($numinarray);
                        return parent::loadColumn($numinarray);
		}
		$results=array();
		$ret=array();
		$results = $this->loadObjectList( '', $translate, $language );

		$pfunc = $this->_profile();

		if( $results != null && count($results)>0) {
			foreach ($results as $row) {
				$fields = get_object_vars( $row );
				$keycount = 0;
				foreach ($fields as $k=>$v) {
					if ($keycount==$numinarray){
						$key = $k;
						break;
					}
				}
				$ret[] = $fields[$key];
			}
		}

		$pfunc = $this->_profile($pfunc);

		return $ret;
	}

	function _getFieldCount(){
	    if (!is_resource($this->cursor)){
			// This is a serious problem since we do not have a valid db connection
			// or there is an error in the query
			$error = JError::raiseError( 500, JTEXT::_('No valid database connection:') .$this->getErrorMsg());
			return $error;
		}

		$fields = mysql_num_fields($this->cursor);
		return $fields;
	}

	function _getFieldMetaData($i){
		$meta = mysql_fetch_field($this->cursor, $i);
		return $meta;
	}

	/**
	 * Public function to test if table has translated content available
	 *
	 * @param string $table : tablename to test
	 */
	function translatedContentAvailable($table){
		return in_array( $table, $this->_mlTableList) || $table=="content";
	}

	/** Internal function to return reference table names from an sql query
	 *
	 * @return	string	table name
	 */
	function getRefTables(){
		return $this->_refTables;
	}

        public function setSite($site) {
            $this->_isSite = $site;
        }

	/**
	* This global function loads the first row of a query into an object
	*/
	function loadObject( $translate=true, $language=null ) {
		$objects = $this->loadObjectList("",$translate,$language);
		if (!is_null($objects) && count($objects)>0){
			return $objects[0];
		}
		return null;
	}

}
