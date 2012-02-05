<?php
/** part of JooBatabase component - see http://joodb.feenders.de */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );

/**
 * Main Contoller
 */
class JooDBController extends JController
{
	/**
	 * Constructor
	 */
	function __construct( $config = array() )
	{
		parent::__construct( $config );
		// Register Extra tasks
		$this->registerTask( 'add',			'edit' );
		$this->registerTask( 'apply',		'save' );
		$this->registerTask( 'applydata',		'savedata' );
	}

	/**
	 * Display a view
	 */
	function display() {
		parent::display();
	}

	/** edit Database */
	function edit()
	{
		$document = &JFactory::getDocument();
		$vType		= $document->getType();
		$vName = JRequest::getCmd('view', 'joodbentry');
		$view = &$this->getView( $vName, $vType);
		$vLayout = JRequest::getCmd( 'layout', 'default' );
		$view->setLayout($vLayout);
		$view->display();
	}

	/** list Data of JooDatabase tables */
	function listdata()
	{
		$document = &JFactory::getDocument();
		$vType		= $document->getType();
		$view = &$this->getView( 'listdata', $vType);
		$vLayout = JRequest::getCmd( 'layout', 'default' );
		$view->setLayout($vLayout);
		$view->display();
	}

	/** edit Data of JooDatabase tables */
	function editdata() {
		$document = &JFactory::getDocument();
		$vType		= $document->getType();
		$view = &$this->getView( 'editdata', $vType);
		$vLayout = JRequest::getCmd( 'layout', 'default' );
		$view->setLayout($vLayout);
		$view->display();
	}

	/** add New entry */
	function addNew(){
		parent::display();
	}

	/**
	 * Save data entry in joodb data table
	 */
	function savedata()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		// load the jooDb object with table fiel infos
		$joodbid	= JRequest::getInt( 'joodbid');
		$jb =& JTable::getInstance( 'joodb', 'Table' );
		$jb->load( $joodbid );
		$db	=& $jb->getTableDBO();
		$fields = $db->getTableFields($jb->table,false);
		$item=array();
		foreach ($fields[$jb->table] as $fname=>$fcell) {
			if (isset($_POST[$fname])) {
				$typearr = preg_split("/\(/",$fcell->Type);
				switch ($typearr[0]) {
					case 'text' :
					case 'tinytext' :
					case 'mediumtext' :
					case 'longtext' :
					$item[$fname] = $db->getescaped(JRequest::getVar($fname, '', 'post', 'string', JREQUEST_ALLOWHTML));
				break;
					case 'int' :
					case 'tinyint' :
					case 'smallint' :
					case 'mediumint' :
					case 'bigint' :
					case 'year' :
					$item[$fname] = JRequest::getInt($fname);
				break;
					case 'date' :
					case 'datetime' :
					case 'timestamp' :
					case 'time':
					$item[$fname] = preg_replace("/[^0-9\: \-]/","",JRequest::getVar($fname, '', 'post', 'string'));
				break;
					case 'set' :
					$values = JRequest::getVar($fname, '', 'post');
					$item[$fname] = join(",",$values);
				break;
					default:
					$item[$fname] = $db->getescaped(JRequest::getVar($fname));
				}
			} else {
				if ($fcell->Null=="YES") {
					$item[$fname] = "NULL";
				}
			}
		}

		if ($item[$jb->fid]>0) {
			// UPDATE
			$savestring = "";
			foreach ($item as $field => $value) {
					$savestring .= "`".$field."` = ".(($value==="NULL") ? "NULL," : "'".$value."',");
			}
			$db->setquery("UPDATE `".$jb->table."` SET " . substr($savestring,0,-1) . " WHERE ".$jb->fid." = ".$item[$jb->fid]." LIMIT 1;");
			if(!$db->query()){
				$msg = JText::_( 'Error' )." : ".$db->getErrorMsg();
			} else {
				$msg = JText::_( 'Item Saved' );
			}
			$id = $item[$jb->fid];
		} else {
			// INSERT
			$insert = "";	$values = "";
			foreach ($item as $field => $value) {
				$insert .= "`".$field."`,";
				$values .= ($value==="NULL") ? "NULL," : "'".$value."',";
			}
			$db->setquery("INSERT INTO `".$jb->table."` (".substr($insert,0,-1).") VALUES (".substr($values,0,-1)."); ");
			if(!$db->query()){
				$msg = JText::_( 'Error' )." : ".$db->getErrorMsg();
			} else {
				$id = $db->insertid();
				$msg = JText::_( 'Item Saved' );
			}
		}


		// attach and resize uploaded image
		// Get the uploaded file information
		$newimage = JRequest::getVar('dataset_image', null, 'files', 'array' );
		if ($newimage['name']!="") {

			// Make sure that file uploads are enabled in php
			if (!(bool) ini_get('file_uploads')) {
				JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLFILE'));
				return false;
			}
			$destination = JPATH_ROOT.DS."images".DS."joodb".DS."db".$jb->id."/img".$id;
			$org_img = $destination."-original".strrchr($newimage['name'],".");
			$params = new JParameter( $jb->params );
			// Move uploaded image
			jimport('joomla.filesystem.file');
			$uploaded = JFile::upload($newimage['tmp_name'], $org_img);
			if (file_exists($org_img)) {
			    chmod($org_img, 0664);
   			    // normal image
			    JooDBAdminHelper::resizeImage($org_img,$destination.".jpg",$params->get("img_width",480),$params->get("img_height",600));
   			    // thumbnail image
			    JooDBAdminHelper::resizeImage($org_img,$destination."-thumb.jpg",$params->get("thumb_width",120),$params->get("thumb_height",200));
			}
	     }

	     $task = JRequest::getCmd( 'task' );
		$link = 'index.php?option=com_joodb&joodbid='.$jb->id.(($task=="applydata") ? "&view=editdata&cid[]=".$id : "&view=listdata");
		$this->setRedirect( $link, $msg );
	}

	/**
	 * Save joodb enty
	 */
	function save()
	{
		// Initialize variables
		$row =& JTable::getInstance('joodb', 'Table');

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		if (!$row->bind(JRequest::get('post',2))) {
			JError::raiseError(500, $row->getError() );
		}

		// get params
		$params = JRequest::getVar( 'params', array(), 'post', 'array' );
		if (is_array( $params )) {
			$txt = array();
			foreach ( $params as $k=>$v) {
				$txt[] = "$k=$v";
			}
			$row->params = implode( "\n", $txt );
		}

		$msg = JText::_( 'Item Saved' );

		if (!$row->check()) $msg = $row->getError();
		if (!$row->store()) Jerror::raiseError(500, $row->getError());

		$row->checkin();

		$task = JRequest::getCmd( 'task' );
		switch ($task)
		{
			case 'apply':
				$link = 'index.php?option=com_joodb&task=edit&view=joodbentry&cid[]='. $row->id ;
				break;
			case 'save':
			default:
				$link = 'index.php?option=com_joodb';
				break;
		}

		$this->setRedirect( $link, $msg );
	}

	function cancel()
	{
		//cancel editing a record
		$this->setRedirect( 'index.php?option=com_joodb', JText::_( 'Edit canceled' ) );
	}

	function cancelEditData()
	{
		//cancel editing a record get database
		$this->setRedirect( 'index.php?option=com_joodb', JText::_( 'Edit canceled' ) );
	}

	function exitjoodb()
	{
		$this->setRedirect( 'index.php' );
	}

	/**
	 * Copy one or more databases
	 */
	function copy() {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_joodb' );

		$cid	= JRequest::getVar( 'cid', null, 'post', 'array' );
		$db		=& JFactory::getDBO();
		$row =& JTable::getInstance('joodb', 'Table');
		$user	= &JFactory::getUser();
		$n		= count( $cid );

		if ($n > 0)
		{
			foreach ($cid as $id)
			{
				if ($table->load( (int)$id ))
				{
					$table->id				= 0;
					$table->title			= 'Copy of ' . $table->name;

					if (!$table->store()) {
						return JError::raiseWarning( $table->getError() );
					}
				}
				else {
					return JError::raiseWarning( 500, $table->getError() );
				}
			}
		}
		else {
			return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
		}
		$this->setMessage( JText::sprintf( 'Items copied', $n ) );
	}

	/**
	 * Remove entries from joodb database tables
	 */
	function removedata() {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$joodbid	= JRequest::getInt( 'joodbid');

		$jb =& JTable::getInstance( 'joodb', 'Table' );
		$jb->load( $joodbid );

		$this->setRedirect( 'index.php?option=com_joodb&view=listdata&joodbid='.$jb->id );

		// Initialize variables
		$db	=& $jb->getTableDBO();
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$n		= count( $cid );
		JArrayHelper::toInteger( $cid );

		if (count($cid) < 1) {
			$this->setMessage(JText::_('Select an item to delete'));
		} else {
			$cids = implode(',', $cid);
			$query = 'DELETE FROM '.$jb->table
			. ' WHERE '.$jb->fid.' IN ( '. $cids. ' )';
			$db->setQuery( $query );
			if (!$db->query()) {
				JError::raiseWarning( 500, $db->getError() );
			}
		}

		$this->setMessage( JText::sprintf( 'Items removed', $n ) );
	}

	/**
	 * Sets the publish state of a jodb data table entry to 1 ...
	 */
	function data_publish() {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$joodbid	= JRequest::getInt( 'joodbid');
		$jb =& JTable::getInstance( 'joodb', 'Table' );
		$jb->load( $joodbid );

		// Initialize variables
		$db	=& $jb->getTableDBO();
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		$n		= count( $cid );

		if ($n) {
			$cids = implode(',', $cid);
			$query = 'UPDATE '.$jb->table.' SET '.$jb->fstate.'=1'
			. ' WHERE '.$jb->fid.' IN ( '. $cids. ' )';
			$db->setQuery( $query );
			if (!$db->query()) {
				JError::raiseWarning( 500, $db->getError() );
			}
		}

		$this->setRedirect( 'index.php?option=com_joodb&view=listdata&joodbid='.$jb->id );
		$this->setMessage( JText::sprintf( 'Items Published', $n ) );
	}

	/**
	 * Sets the publish state of a jodb data table entry to 0 ...
	 */
	function data_unpublish() {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$joodbid	= JRequest::getInt( 'joodbid');
		$jb =& JTable::getInstance( 'joodb', 'Table' );
		$jb->load( $joodbid );

		// Initialize variables
		$db	=& $jb->getTableDBO();
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger( $cid );
		$n		= count( $cid );

		if ($n) {
			$cids = implode(',', $cid);
			$query = 'UPDATE '.$jb->table.' SET '.$jb->fstate.'=0'
			. ' WHERE '.$jb->fid.' IN ( '. $cids. ' )';
			$db->setQuery( $query );
			if (!$db->query()) {
				JError::raiseWarning( 500, $db->getError() );
			}
		}

		$this->setRedirect( 'index.php?option=com_joodb&view=listdata&joodbid='.$jb->id );
		$this->setMessage( JText::sprintf( 'Items Unpublished', $n ) );
	}


	/**
	* Remove item(s)
	*/
	function remove($view='joodb') {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$this->setRedirect( 'index.php?option=com_joodb' );

		// Initialize variables
		$db		=& JFactory::getDBO();
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$n		= count( $cid );
		JArrayHelper::toInteger( $cid );

		if (count($cid) < 1) {
			$this->setMessage(JText::_('Select an item to delete'));
		} else {
			$query = 'DELETE FROM #__joodb'
			. ' WHERE id = ' . implode( ' OR id = ', $cid );
			$db->setQuery( $query );
			if (!$db->query()) {
				JError::raiseWarning( 500, $db->getError() );
			}
			$this->setMessage( JText::sprintf( 'Items removed', count( $cid )));
		}
	}


	/**
	* Un Publish item(s)
	*/
	function unpublish() {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$db		=& JFactory::getDBO();
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$n		= count( $cid );
		JArrayHelper::toInteger( $cid );

		if ($n) {
			$query = 'UPDATE #__joodb SET published=0 '
			. ' WHERE id = ' . implode( ' OR id = ', $cid );
			$db->setQuery( $query );
			if (!$db->query()) {
				JError::raiseWarning( 500, $db->getError() );
			} else {
				$msg = JText::sprintf( 'Items Unpublished', count( $cid ) );
			}
		}
		$this->setRedirect( 'index.php?option=com_joodb&task=view', $msg );
	}

	/**
	* Publish item(s)
	*/
	function publish()	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$db		=& JFactory::getDBO();
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$n		= count( $cid );
		JArrayHelper::toInteger( $cid );

		if ($n) {
			$query = 'UPDATE #__joodb SET published=1 '
			. ' WHERE id = ' . implode( ' OR id = ', $cid );
			$db->setQuery( $query );
			if (!$db->query()) {
				JError::raiseWarning( 500, $db->getError() );
			} else {
				$msg = JText::sprintf( 'Items Published', count( $cid ) );
			}
		}
		$this->setRedirect( 'index.php?option=com_joodb&task=view', $msg );
	}

	/**
	* Import excel file
	*/
	function import() {
		JError::raiseWarning( 500, "This function is only available in PRO-Version!" );
	}

	/**
	* Test the existance of a tabel
	*/
	function  testtable() {
		$db = &JFactory::getDbo();
		$exist = false;
		if ($tname = JRequest::getCmd("table"))
			$tables = $db->getTableList();
			$exist = (array_search($tname, $tables)!==false) ? true : false;
		header('Content-type: application/json');
		echo json_encode($exist);
		die();
	}

	/**
	* Tests an sql connection and retuns database names
	*/
	function  testconnection() {
		$db = &JFactory::getDbo();
		$dbs = array();
		$link = @mysql_connect(JRequest::getVar("extdb_server"), JRequest::getVar("extdb_user"), JRequest::getVar("extdb_pass"));
		if ($link) {
			$db_list = mysql_list_dbs($link);
			$cnt = mysql_num_rows($db_list);
			$i=0;
			while ($i < $cnt) {
    			$dbs[] = mysql_db_name($db_list, $i) . "\n";
    			$i++;
			}
		};
		header('Content-type: application/json');
		echo '{"dbs":'.json_encode($dbs)."}";
		die();
	}

}
