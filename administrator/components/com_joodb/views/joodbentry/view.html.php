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

class JoodbViewJoodbentry extends JView
{
	var $bar = null;
	var $version = null;
	var $fields = array();
	var $tables = array();

	function display($tpl = null)
	{
		$app =& JFactory::getApplication();
		$document =& JFactory::getDocument();
		$document->addStyleSheet('components/com_joodb/assets/singleview.css');
		$this->version = new JVersion();
		$this->bar = & JToolBar::getInstance('toolbar');

		$layout	= JRequest::getCmd('layout');
		if ($layout=="step1") {
	 		JToolBarHelper::title(JText::_( "Step1 choose Table" ), 'cpanel.png' );
			$this->bar->appendButton('Standard', 'forward', JText::_( "Continue" ), 'addnew',false);
			$db =& $this->getDbo();
			$this->tables = $db->getTableList();
		} else if ($layout=="extern") {
	 		JToolBarHelper::title(JText::_( "External Database" ), 'cpanel.png' );
			$this->bar->appendButton('Standard', 'forward', JText::_( "Continue" ), 'addnew',false);
		} else if ($layout=="step2") {
	 		JToolBarHelper::title(JText::_( "Step2 define Fields" ), 'cpanel.png' );
			$this->bar->appendButton('Standard', 'forward', JText::_( "Continue" ), 'addnew',false);
			$this->assignRef('dbtable', JRequest::getVar('dbtable'));
			$this->assignRef('dbname', JRequest::getVar('dbname'));
			$db =& $this->getDbo();
			$db->setQuery('SHOW COLUMNS FROM '.$this->dbtable);
			$this->fields = $db->loadObjectList();
		} else if ($layout=="step3") {
			// Add new entry
			$post = JRequest::get( 'post' );
			$row =& JTable::getInstance('joodb', 'Table');
			if (!empty($post['server'])) {
				$post['params'] = "extdb=".$post['database']."\nextdb_server=".$post['server']."\nextdb_user=".$post['user']."\nextdb_pass=".$post['pass']."\n";
			}
			if (!$row->save( $post )) {
				return JError::raiseWarning( 500, $row->getError() );
			}
	 		JToolBarHelper::title(JText::_( "Step3 no step" ), 'cpanel.png' );
			$this->bar->appendButton('Standard', 'cancel', JText::_( "close" ), 'close',false);
		} else {
			$cid	= JRequest::getVar( 'cid', array(), '', 'array' );
			JArrayHelper::toInteger( $cid );
			$id = $cid[0];
			$text = ( $id ? JText::_( 'Edit' ) : JText::_( 'New' ) );
			JToolBarHelper::title(   JText::_( "JooDatabase" ).': <small><small>['.$text.']</small></small>','joodb.png' );
			JToolBarHelper::save();
			JToolBarHelper::apply();
			JToolBarHelper::cancel();
			$bar = & JToolBar::getInstance('toolbar');
			$bar->appendButton( 'Popup', 'help','Help', 'http://joodb.feenders.de', "980", "600" );

			$row =& JTable::getInstance( 'joodb', 'Table' );
			if (!$row->load( $id )) {
				$app->enqueueMessage( JText::_($row->getError()), 'error' );
			} else {
				$tdb = & $row->getTableDBO();
				$tdb->setQuery('SHOW COLUMNS FROM '.$row->table);
				$this->fields = $tdb->loadObjectList();
				$this->tables = $tdb->getTableList();
			}

			// Import javascript for tooltips
//			JHTML::_('behavior.tooltip');
			jimport('joomla.html.pane');

			$params = new JParameter( $row->params, JPATH_ADMINISTRATOR .'/components/com_joodb/config_items.xml', 'component' );

			$this->assignRef('item',$row);
			$this->assignRef('params', $params);

			JRequest::setVar( 'hidemainmenu', 1 );
		}

		$template = JFactory::getApplication()->getTemplate();

		if ($this->version->RELEASE=="1.5") {
			$document->addStyleSheet('templates/'.$template.'/css/icon.css');
		}

		// Load the form validation behavior
		JHTML::_('behavior.formvalidation');

		parent::display($tpl);
	}

	/**
	 * Get external DB if external server ...
	 * @return JDatabase
	 */
	function getDbo() {
		if (!empty($_POST['server'])) {
			$options = array ('host' => JRequest::getVar('server'), 'user' => JRequest::getVar('user'), 'password' => JRequest::getVar('pass'), 'database' => JRequest::getVar('database'), 'prefix' => '');
			$db = JDatabase::getInstance($options);
			if (JError::isError($db)) {
				$this->setError(JText::_('Database Error: ' . (string) $db));
				return false;
			}
			if ($db->getErrorNum() > 0) {
				$this->setError('Database Error: ' .$db->getErrorMsg());
				return false;
			}
			return $db;
		}
		return JFactory::getDbo();
	}

}