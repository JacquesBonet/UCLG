<?php
/**
*/


defined( '_JEXEC' ) or die( 'Restricted access' );

class FalangVersion {
	var $_version	= '1.7.0b4';
	var $_versionid	= '';
	var $_date	= '2011-11-07';
	var $_status	= 'Beta 4';
	var $_revision	= '';
	var $_copyyears = '';

	/**
	 * This method delivers the full version information in one line
	 *
	 * @return string
	 */
	function getVersion() {
		//return 'V' .$this->_version. ' ('.$this->_versionid.')';
            return 'V' .$this->_version. '';
	}

	/**
	 * This method delivers a special version String for the footer of the application
	 *
	 * @return string
	 */
	function getCopyright() {
		//return '&copy; ' .$this->_copyyears;
            return '';
	}

	/**
	 * Returns the complete revision string for detailed packaging information
	 *
	 * @return unknown
	 */
	function getRevision() {
		return '' .$this->_revision. ' (' .$this->_date. ')';
	}
}
