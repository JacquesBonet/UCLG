<?php
/**
 * @author Andy Sharman
 * @copyright Andy Sharman (www.udjamaflip.com)
 * @link http://www.udjamaflip.com
 * @license GNU/GPL V2+
 * @version 1.0.1
 * @package com_udjacomments
**/ 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
jimport('joomla.application.router');
require_once(JPATH_SITE . '/components/com_content/helpers/route.php');
require_once(JPATH_SITE . '/includes/router.php');

/**
 * HelloWorldList Model
 */
class UdjaCommentsModelCpanel extends JModelList
{
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields
		$query->select('id,full_name,email,url,content,ip,is_spam,is_published');
		//order by
		$query->order('time_added DESC');
		// From the hello table
		$query->from('#__udjacomments');
		return $query;
	}
	
	public function getMediaDir()
	{
		return '../media/com_udjacomments/';
	}
	
	public function getAssets()
	{
		$doc =& JFactory::getDocument();
		$doc->addCustomTag('<style type="text/css">.icon-48-udjacomments { background-image:url("../media/com_udjacomments/images/udjacomments-48x48.png"); }</style>');
		JHTML::_('behavior.mootools');
		JHTML::_('behavior.modal');
	}
	
	public function getJCommentsTable()
	{
		$dbo = JFactory::getDbo();
		$app = JFactory::getApplication();
		
		$sql = 'SELECT
					`table_name`
				FROM
					`information_schema`.`tables`
				WHERE
					`table_schema` = "'.$app->getCfg('db').'"
				AND
					`table_name` LIKE "%_jcomments"';
		
		$dbo->setQuery($sql);
		if ($dbo->Query())
		{
			if ($row = $dbo->loadObject())
			{
				return $row->table_name;
			}	
		}
		
		if ($err = $dbo->getErrorMsg()) { die($err); }
		
		return false;
	}
	
	/**
	* Method to delete record(s)
	*
	* @access    public
	* @return    boolean    True on success
	*/
	public function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		
		//get db object
		$dbo = JFactory::getDbo();
		
		foreach($cids as $cid) {
			if (is_numeric($cid))
			{
				$sql = 'DELETE FROM `#__udjacomments` WHERE id='.$cid;
				$dbo->setQuery($sql);
				if (!$dbo->Query()) {
					$this->setError('Error deleting comment :: '.$dbo->getErrorMsg());
					return false;
				}
			}
		}
		
		return true;
	}
	
	public function import($type)
	{		
		$dbo = JFactory::getDBO();
		
		//first lets import the basics.
		$sql = 'INSERT INTO
				`#__udjacomments`
				(
					`full_name`,
					`email`,
					`url`,
					`ip`,
					`content`,
					`parent_id`,
					`comment_url`,
					`is_published`,
					`is_spam`,
					`receive_notifications`,
					`receive_emailers`,
					`time_added`
				)
				SELECT
					`jcomm`.`name`,
					`jcomm`.`email`,
					`jcomm`.`homepage`,
					`jcomm`.`ip`,
					`jcomm`.`comment`,
					`jcomm`.`parent`,
					CONCAT(`object_group`,CONCAT(":",`jcomm`.`object_id`)),
					`jcomm`.`published`,
					`jcomm`.`checked_out`,
					`jcomm`.`checked_out`,
					`jcomm`.`subscribe`,
					`jcomm`.`date`
				FROM
					`'.$this->getJCommentsTable().'` as `jcomm`';
		$dbo->setQuery($sql);
		
		if ($dbo->Query())
		{
			
			$affectedRows = $dbo->getAffectedRows();
			return $affectedRows;
		}
		
		return 0;
	}
	
}