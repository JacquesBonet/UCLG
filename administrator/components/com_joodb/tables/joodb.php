<?php
/**
* JooDB Table Definition
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**  */
class TableJooDB extends JTable
{

	/** @var int Primary key */
	var $id					= null;
	/** @var string */
	var $name				= null;
	/** @var string */
	var $table				= null;
	/** @var text */
	var $tpl_list		= null;
	/** @var text */
	var $tpl_single		= null;
	/** @var text */
	var $tpl_print			= null;
	/** @var text */
	var $tpl_form			= null;
	/** @var string */
	var $fid				= null;
	/** @var string */
	var $ftitle				= null;
	/** @var string */
	var $fcontent			= null;
	/** @var string */
	var $fabstract			= null;
	/** @var string */
	var $fdate				= null;
	/** @var string */
	var $fstate			= null;
	/** @var boolean */
	var $published				= 1;
	/** @var string */
	var $params				=  "";
	/** @var date */
	var $created				= null;

	/** Database Object of the Data Table database
 	 * @var		object
	 * @since	1.7
	 */
	protected $_tbldb = null;

	/** Parameter Object of the Data Table database
 	 * @var		object
	 * @since	1.7
	 */
	protected $_jbparams = null;

	/**
	* @param database A database connector object
	*/
	function __construct( &$db ) {
		parent::__construct( '#__joodb', 'id', $db );
		$this->_tbldb = & $this->getDbo();
	}

	/**
	 * Overloaded check function
	 */
	function check()
	{
		if(empty($this->name)) {
			$this->setError(JText::_('Database must have a name'));
			return false;
		}
		if(empty($this->table)) {
			$this->setError(JText::_('Please choose Table'));
			return false;
		}
		if(empty($this->fid)) {
			$this->setError(JText::_('Error Define Fields'));
			return false;
		}
		if(empty($this->ftitle)) {
			$this->setError(JText::_('Error Define Fields'));
			return false;
		}
		if(empty($this->fcontent)) {
			$this->setError(JText::_('Error Define Fields'));
			return false;
		}
		/** load the default templates into field if empty */
		if(empty($this->tpl_list)) {
			$this->tpl_list = $this->getDefaultTemplate("listview");
		}
		if(empty($this->tpl_single)) {
			$this->tpl_single = $this->getDefaultTemplate("singleview");
		}
		if(empty($this->tpl_print)) {
			$this->tpl_print = $this->getDefaultTemplate("printview");
		}
		if(empty($this->tpl_form)) {
			$this->tpl_form = $this->getDefaultTemplate("formview");
		}
		return true;
	}

	/**
	 * Overloaded load function
	 */
	function load($id=null,$reset=false)
	{
		parent::load($id,$reset);
		jimport( 'joomla.html.parameter' );
		$this->_jbparams = new JParameter( $this->params );
		$p = & $this->_jbparams;
		// Prepare external Database for Datatable
		if ($p->get('extdb_server')!="") {
			$options = array ('host' => $p->get('extdb_server'), 'user' => $p->get('extdb_user'), 'password' => $p->get('extdb_pass'), 'database' => $p->get('extdb'),'prefix' => '');
			$this->_tbldb = JDatabase::getInstance($options);
			if (JError::isError($this->_tbldb)) {
				$this->setError(JText::_('Database Error: ' . (string) $this->_tbldb));
				return false;
			}
			if ($this->_tbldb->getErrorNum() > 0) {
				$this->setError('Database Error: ' .$this->_tbldb->getErrorMsg());
				return false;
			}
		}
		return true;
	}


	/**
	 * load the default templates into field
	 */
	function getDefaultTemplate($tname) {
		$tfile = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joodb'.DS.'assets'.DS.$tname.".tpl";
		if (file_exists($tfile)) {
			$template = file_get_contents($tfile);
			// replace tag with table specific infos
			if ($tname=="listview") {
				$header = JText::_('Title');
				$loop = "<div class='{joodb loopclass}' ><div style='width:80px;float:left;' align='middle'><h3>{joodb ".$this->fid."}</h3></div><div><strong>{joodb ".$this->ftitle."}</strong>".chr(10);
				if (!empty($this->fdate)) {
					$loop .= "<br/><span class='small'>".JText::_('Date').": {joodb ".$this->fdate."}</span>";
				}
				if (!empty($this->fabstract)) {
					$loop .= "<p>{joodb ".$this->fabstract."|120}</p>".chr(10);
				} else {
					$loop .= "<p>{joodb ".$this->fcontent."|120}</p>".chr(10);
				}
				$loop .= "</div></div>".chr(10);
				$template = str_replace("#C_DEFAULT_HEADER", $header, $template);
				$template = str_replace("#C_DEFAULT_LOOP", $loop, $template);
			} else if ($tname=="formview") {
				$content = '<label id="'.$this->ftitle.'msg" for="'.$this->ftitle.'" class="">'.JText::_('title').'</label><br/>'.chr(10);
				$content .= '{joodb form|'.$this->ftitle.'}<br />'.chr(10);
				$content .= '<label id="'.$this->fcontent.'msg" for="'.$this->fcontent.'" class="">'.JText::_('content').'</label><br />'.chr(10);
				$content .= '{joodb form|'.$this->fcontent.'}<br />'.chr(10);
				$template = str_replace("#S_DEFAULT_FIELDS", $content, $template);
			} else {
				$header = "<div class='componentheading'><h2>{joodb ".$this->ftitle."}</h2></div>".chr(10);
				if (!empty($this->fdate)) {
					$header .= "<span class='small'>".JText::_('Date').": {joodb ".$this->fdate."}</span>".chr(10);
				}
				$header .= "<p>{joodb ".$this->fcontent."}</p>".chr(10);
				$template = str_replace("#S_DEFAULT_HEADER", $header, $template);
				$db	=& JFactory::getDBO();
				$fields = $db->getTableFields($this->table);
				$content = "<ul>".chr(10);
				foreach ($fields[$this->table] as $fname => $ftype) {
					if (($fname!=$this->ftitle) && ($fname!=$this->fcontent) && ($fname!=$this->fabstract)) {
						if (($ftype == "text") || ($ftype == "varchar")) { $content .= "<li>{joodb ".$fname."}</li>".chr(10);}
					}
				}
				$content .= "</ul>".chr(10);
				$template = str_replace("#S_DEFAULT_FIELDS", $content, $template);
			}
		}
		return $template;
	}


	/**
	 * Get the external database from the joodb parameters
	 * return database object
	 */
	function getTableDBO() {
		return $this->_tbldb;
	}

	/**
	 * Get the parameters object for the joobase table
	 * return parameters object
	 */
	function getParameters() {
		return $this->_jbparams;
	}

	/**
	 * Get the field List from the Database table;
	 */
	function getTableFieldList(&$db) {
		$db = & $this->getTableDBO();
		$db->setQuery('SHOW COLUMNS FROM '.$this->table);
		return $db->loadObjectList();
	}

}
