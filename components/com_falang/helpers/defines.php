<?php
/**
 *
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

//Global definitions
if( !defined('DS') ) {
	define( 'DS', DIRECTORY_SEPARATOR );
}

if( !defined('FALANG_PATH') ) {
	define( 'FALANG_PATH', JPATH_SITE .DS.'components'.DS.'com_falang' );
	define( 'FALANG_ADMINPATH', JPATH_SITE .DS.'administrator'.DS.'components'.DS.'com_falang' );
	define( 'FALANG_LIBPATH', FALANG_ADMINPATH .DS. 'libraries' );
	define( 'FALANG_LANGPATH', FALANG_PATH .DS. 'language' );
	define( 'FALANG_URL', '/components/com_falang');
}
?>
