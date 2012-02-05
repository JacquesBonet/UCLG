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
 * JooDatabase Component single item Model
 */
class JoodbModelArticle extends JModel
{
	/**
	 * Entry Item Object
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Database Object
	 *
	 * @var object
	 */
	var $_joobase = null;

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
		$this->_db = & $this->_joobase->getTableDBO();

		// access allowed... redirect to login if not
		JoodbHelper::checkAuthorization($this->_joobase,"accessd");

		// get the table field list
		$this->_joobase->fields = $this->_db->getTableFields($this->_joobase->table);
		$this->_joobase->fields = $this->_joobase->fields[$this->_joobase->table];

		$id = $params->get("id", 0);
		if ($id==0) $id = JRequest::getInt('id', 1);
		$this->setId((int)$id);
	}

	/**
	 * Method to set the article id
	 *
	 * @access	public
	 * @param	int	Article ID number
	 */
	function setId($id)
	{
		// Set new article ID and wipe data
		$this->_id		= $id;
		$this->_article	= null;
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
	 * @return array
	 */
	function getData()
	{

		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
		$statequery = ($this->_joobase->fstate) ? " AND `".$this->_joobase->fstate."`=1 " : " ";
		/* Query single object. */
		$this->_db->setQuery('SELECT * FROM '.$this->_joobase->table
							. ' WHERE `'.$this->_joobase->fid.'`='.$this->_id.$statequery.' LIMIT 1;');
		$this->_data = $this->_db->loadObject();

		}

		if (!$this->_data)	{
			JError::raiseError( 404, JText::sprintf( 'Article # not found', $this->_id ) );
		}

		return $this->_data;
	}

}
?>
