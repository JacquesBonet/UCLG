<?php
/**
 * @package Iyosis Maps for Joomla!
 * @author Remzi Degirmencioglu
 * @copyright (C) 2011 www.iyosis.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controller');

/**
 * Controller
 */
class IyosisMapsControllerCsv extends JController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Csv', $prefix = 'IyosisMapsModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function importMarker() {
		$this->setRedirect('index.php?option=com_iyosismaps&view=csv&task=importMarker', false);
	}

	public function importMarker2() {
		//Get Model
		$model =& $this->getModel();
		$msg = $model->importMarker();

		JFactory::getApplication()->enqueueMessage( $msg );
		$this->setRedirect('index.php?option=com_iyosismaps&view=csv&task=importMarker', false);
	}

	public function importMarkerCancel() {
		$this->setRedirect('index.php?option=com_iyosismaps&view=markers', false);
	}

	public function exportMarker() {
	
		$model =& $this->getModel();
		$data = $model->exportMarker();
		$fp = fopen(JPATH_ADMINISTRATOR.'/components/com_iyosismaps/media/export.csv', 'w');
		
		$header = array("title", "published", "mapid", "latitude", "longitude", "iconid", "infowindow", "description", "catid");

		fputcsv($fp, $header);

		foreach($data as $fields) {
			fputcsv($fp, $fields);
		}
		fclose($fp);
		
		$this->setRedirect(JURI::root()."administrator/components/com_iyosismaps/media/export.csv");
	}
}
