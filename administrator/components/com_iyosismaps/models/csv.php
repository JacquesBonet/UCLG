<?php
/**
 * @package Iyosis Maps for Joomla!
 * @author Remzi Degirmencioglu
 * @copyright (C) 2011 www.iyosis.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );

class IyosisMapsModelCsv extends JModel
{
	public function importMarker() {

		jimport('joomla.filesystem.file');
		
		$file = JRequest::getVar("file", null, 'FILES', 'array');
						
		$fileName    = str_replace(' ', '', JFile::makeSafe($file['name']));		
		$fileTmp     = $file["tmp_name"];
		
		$ext = strrchr($fileName, '.');
		
		if(filesize($fileTmp) == 0)
			return JText::_('Please select a file to upload.');
		
		if($ext <> '.csv')
			return JText::_('Only .csv files can be imported.');
			
		$to = JPATH_ADMINISTRATOR.'/components/com_iyosismaps/media/import.csv';
		move_uploaded_file($fileTmp, $to);

		$row = 1;
		$imported = 0;

		if (($handle = fopen($to, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
				if (is_numeric($data[1])&&is_numeric($data[2])&&is_numeric($data[3])&&is_numeric($data[4])) {
					$num = count($data);

					$query = 'insert into #__iyosismaps_markers (title,published,mapid,latitude,longitude,iconid,infowindow,description,catid) values (';

					for ($c=0; $c < $num; $c++) {
						$query .= "'".$data[$c]."'";
						if ($c!=($num-1)) $query .= ",";
					}
					$query .= ')';

					$this->_db->setQuery( $query );
					if(!$this->_db->Query()) {
						return $this->_db->getErrorMsg();
					} else {
						$imported++;
					}
				}
				$row++;
			}
			fclose($handle);
		}
		$result = JText::_('Data successfully imported.')." ".($row-1)." lines  and ".$imported." markers in the imported file.";
		
		return $result;
	}

	public function exportMarker() {
		$query = 'select title, published, mapid, latitude, longitude, iconid, infowindow, description, catid from #__iyosismaps_markers';
		$this->_db->setQuery( $query );
		$data = $this->_db->loadRowList();
		return $data;
	}
}
