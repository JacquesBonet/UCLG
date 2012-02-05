<?php
/**
* @package		JooDatabase - http://joodb.feenders.de
* @copyright	Copyright (C) Computer - Daten - Netze : Feenders. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Dirk Hoeschen (hoeschen@feenders.de)
*/

// no direct access
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

// edit or add a custom entry from a table
class JoodbViewEditdata extends JView
{
	var $id = 0;
	var $jb = null;
	var $item = null;

	function display($tpl = null)
	{
		JRequest::setVar( 'hidemainmenu', 1 );

		// load the jooDb object with table field infos
		$this->jb =& JTable::getInstance( 'joodb', 'Table' );
		$this->jb->load( JRequest::getInt( 'joodbid') );
		$db	=& $this->jb->getTableDBO();

		$fields = $db->getTableFields($this->jb->table,false);
		$this->jb->fields = $fields[$this->jb->table];

		// get the item to edit
		if ($cid = JRequest::getVar( 'cid', array(), '', 'array' )) {
			JArrayHelper::toInteger( $cid );
			$this->id = $cid[0];
			$db->setQuery("SELECT * FROM ".$this->jb->table." WHERE ".$this->jb->fid."=".$this->id,0,1);
			$this->item = $db->loadObject();
		};

		$text = ( $this->item ? JText::_( 'Edit' ) : JText::_( 'New' ) );
		JToolBarHelper::title(   $this->jb->name.': <small><small>['.$text.']</small></small>','joodb.png' );
		JToolBarHelper::save('savedata');
		JToolBarHelper::apply('applydata');
		JToolBarHelper::cancel('listdata');

		// Load the form validation behavior
		JHTML::_('behavior.formvalidation');

		parent::display($tpl);
	}

}