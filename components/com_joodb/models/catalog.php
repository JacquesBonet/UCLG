<?php
/**
* @package		JooDatabase - http://joodb.feenders.de
* @copyright	Copyright (C) Computer - Daten - Netze : Feenders. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Dirk Hoeschen (hoeschen@feenders.de)
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');

/**
 * JooDatabase Component Catalog Model
 */
class JoodbModelCatalog extends JModel
{
	/**
	 * Frontpage data array
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Frontpage total
	 *
	 * @var integer
	 */
	var $_total = null;

	/**
	 * Database Object
	 *
	 * @var object
	 */
	var $_joobase = null;

	/**
	 * Where Statemant
	 *
	 * @var string
	 */
	var $_where = null;

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$app = JFactory::getApplication();
		$params	= & $app->getParams();
		$joobase = $params->get("joobase",0);
		// Load the Database parameters
		if ($joobase==0) $joobase = JRequest::getInt('joobase', 1);
		$joodbid = JRequest::getInt( 'joodbid');
		$this->_joobase =& JTable::getInstance( 'joodb', 'Table' );
		if (!$this->_joobase->load( $joobase)) JError::raiseError( 500, $this->_joobase->getErrror());
		if ($this->_joobase->published==0) JError::raiseError( 404, JText::sprintf( 'Database is unpublished or not availiable'));

		$option = "com_joodb".$joobase;

		// access allowed... redirect to login if not
		JoodbHelper::checkAuthorization($this->_joobase,"accessd");
		$this->_db = & $this->_joobase->getTableDBO();

		// get the table field list
		$this->_joobase->fields = $this->_db->getTableFields($this->_joobase->table);
		$this->_joobase->fields = $this->_joobase->fields[$this->_joobase->table];

		// Get the pagination request variables
		$this->setState('limit', $app->getUserStateFromRequest($option.'.limit', 'limit', $params->get('limit','10'), 'int'));
		$this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));
		if ($orderby = JRequest::getVar('orderby')) {
			$ordering = JRequest::getCMD('ordering');
		} else {
			$orderby =  $params->get('orderby','fid');
			$ordering =  $params->get('ordering','DESC');
		}
		$this->setState('orderby', $orderby);
		$this->setState('ordering', $ordering );
		$this->setState('search', $app->getUserStateFromRequest($option.'.search', 'search', JRequest::getVar('search'), 'string'));
		if ($this->getState('search')==JText::_('search...')) $this->setState('search', null);
		$this->setState('alphachar', JRequest::getVar('letter'));
		$where = array();
		if ($params->get("where_statement")!="") {
			 $where[] = ' ('.$params->get("where_statement").')';
		}

		//build search string
		if ($this->getState('alphachar')!="") {
			if (JRequest::getVar('letter')) {
				$this->setState('search','');
			}
			$where[] .= " ( a.`".$this->_joobase->ftitle."` LIKE '".substr($this->getState('alphachar'),0,1)."%' )";
		} else if ($this->getState('search')!="") {
			if (strlen($this->getState('search'))>=3) {
				$search = substr($this->getState('search'),0,40);
				// extended search
				if ($sfield = JRequest::getVar('searchfield')) {
					if ($sfield=="ALL") {
						$fields = $this->_db->getTableFields($this->_joobase->table);
						$wa = array();
						foreach ($fields[$this->_joobase->table] AS $var => $field) {
							switch ($field) {
								case 'varchar' : case 'char' : case 'tinytext' : case 'text' : case 'mediumtext' : case 'longtext' :
									$wa[] = "a.`".$var."` LIKE '%".$search."%'";
								break;
								case 'int' : case 'smallint' : case 'mediumint' : case 'bigint' : case 'tinyint' :
									$wa[] = "a.`".$var."` = '".(int) $search."'";
								break;
								case 'date' : case 'datetime' : case 'timestamp' :
									$wa[] = "a.`".$var."` LIKE '".$search."%'";
								break;
								default :
									$wa[] = "a.`".$var."` LIKE '".$search."'";
							}
						}
						$where[] .= " ( ".join(" OR ", $wa)." ) ";
					} else {
						$where[] .= " ( a.`".addslashes($sfield)."` LIKE '%".$search."%' ) ";
					}

				} else {
					$where[] .= " (a.`".$this->_joobase->ftitle."` LIKE '%".$search."%' ".
				    	        " OR a.`".$this->_joobase->fcontent."` LIKE '%".$search."%' ) ";
				}
			}
		}
		if ($this->_joobase->fstate) $where[] = "`".$this->_joobase->fstate."`='1'";

		if (JRequest::getCmd('reset')=="true") {
			$app->setUserState($option.'.gs',array());
			$app->setUserState($option.'.cid',array());
		}

		// reduce result to selected items
		$ids = $app->getUserStateFromRequest($option.'.cid', 'cid',array(), 'array');
		if (is_array($ids) && count($ids)>=1) {
			foreach ($ids as $n => $fid)
				$ids[$n] = "a.`".$this->_joobase->fid."`= '".$fid."'";
			$where[] = " (".join(" OR ", $ids).") ";
		}

		// add filter from parametric search selects
		$gs =  $app->getUserStateFromRequest($option.'.gs', 'gs',array(), 'array');

		if (is_array($gs) && count($gs)>=1)
		 foreach ($gs as $column => $sv) {
			if (isset($this->_joobase->fields[$column])) {
				foreach ($sv as $n => $value)
					if (empty($value)) unset($sv[$n]);
					else $sv[$n] = "`".$column."` LIKE '".$value."'";
				if (count($sv)>=1) $where[] = " (".join(" OR ", $sv).") ";
			}
		}

		// notepad view select marked articles
		if (JRequest::getCmd("layout")=="notepad") {
			$where = array();
	  		$session =& JFactory::getSession();
			$articles = preg_split("/:/",$session->get('articles'));
			if (count($articles)>=1) {
		    	foreach ($articles as $n => $article) $articles[$n] = " a.`".$this->_joobase->fid."`='".$article."' ";
			} else $articles = array(" a.`".$this->_joobase->fid."`='0'");
			$where[] = " (".join(" OR ", $articles).") ";
		}

		if (count($where)>=1) $this->_where = " WHERE ".join(" AND ", $where);
	}

	/**
	 * Get Object from JooDB table
	 *
	 * @access public
	 * @return single object
	 */
	function getJoobase()
	{
		return	$this->_joobase;
	}

	/**
	 * Method to get Data from table in Database
	 *
	 * @access public
	 * @param boolean $export - ignore pagination limit and page
	 * @return array
	 */
	function getData($export=false)
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$app = & JFactory::getApplication();
			JFactory::getDbo()->getErrors();
			$pagination = & $this->getPagination();
			if ($export===true) {
				$params	= & $app->getParams();
				$pagination->limitstart = 0;
				$pagination->limit = $params->get('eportlimit',"100");
			}
			$this->_data = $this->_getList($query,$pagination->limitstart,$pagination->limit);
			if ($this->_data===null)
				$app->enqueueMessage(JText::_( 'Error' )." : ".$this->_db->getErrorMsg(),"Warning");
		}

		return $this->_data;
	}

	/**
	 * Return Export items ...
	 */
	function getExport() {
		return $this->getData(true);
	}

	/**
	 * Method to get the total number of items in the Database
	 *
	 * @access public
	 * @return integer
	 */
	function getTotal()
	{
		// Get total if not exits
		if (empty($this->_total))
		{
			$query = 'SELECT `'.$this->_joobase->fid.'` AS numlinks FROM `'.$this->_joobase->table."` AS a ".$this->_where;
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	/**
	 * Get the possible values from a column regarding the current selection
	 *
	 * @access public
	 * @return values
	 */
	function getColumnVals($column)
	{
		// Get total if not exits
		$cw = (!empty($this->_data)) ? $this->_where : "";
		$query = "SELECT count(distinct(`".$this->_joobase->fid."`)) AS count,a.`".$column."` AS value, '' AS delimeter FROM `"
				.$this->_joobase->table."` AS a ".$cw." GROUP BY a.`".$column."` ORDER BY a.`".$column."` ASC";
		/** Erweiterung für mircel - Sobald comma listen erkannt werden wertetabelle neu aufbauen */
		if ($values = $this->_getList($query)) {
			foreach ($values as $value) {
				if (substr_count($value->value,",")>=1) { // its a value list - rebuild values
					$query = "SELECT a.`".$column."` AS value FROM `".$this->_joobase->table."` AS a ".$cw." ORDER BY a.`".$column."` ASC";
					$values = $this->_getList($query);
					$v= array();
					foreach ($values as $value) {
						$parts = preg_split("/,/",$value->value);
						foreach ($parts as $p) $v[] = trim($p);
					}
					sort($v);
					$c = array_count_values($v);
					$values = array();
					foreach ($c as $value => $count) {
						$values[] = (object) array ("value" => $value, 'count' => $count, "delimeter" => '%');
					}
                    break;
				}
			}
		}
		return $values;
	}


	/**
	 * Method to get a pagination object
	 *
	 * @access public
	 * @return integer
	 */
	function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}

	/**
	 * Method to get a search value
	 *
	 * @access public
	 * @return string
	 */
	function getSearch()
	{
		return $this->getState('search');
	}

	/**
	 * Method to get a search value
	 *
	 * @access public
	 * @return string
	 */
	function getAlphachar()
	{
		return $this->getState('alphachar');
	}

	/**
	 * Build query string
	 *
	 * @access non-public
	 * @return string
	 */
	function _buildQuery()
	{
		/* Query table and return the relevant fields. */
		$query = 'SELECT a.* '
			. ' FROM '.$this->_joobase->table.' AS a'
			. $this->_where
			. ' GROUP BY a.'.$this->_joobase->fid;

			// build ordering
			$orderby = $this->getState('orderby');
			if ($this->getState('orderby')== "random") {
				$query .= ' ORDER BY RAND() ';
			} else {
				if (isset($this->_joobase->{$orderby})) $orderby = $this->_joobase->{$orderby};
				$query .= ' ORDER BY a.`'.$orderby.'` '.$this->getState('ordering');
			}
			return $query;
	}


}
?>
