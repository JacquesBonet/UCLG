<?php
/**
 * @package Iyosis Maps for Joomla!
 * @author Remzi Degirmencioglu
 * @copyright (C) 2011 www.iyosis.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

class plgContentIyosisMaps extends JPlugin
{
	function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		$app = JFactory::getApplication();
		if($app->isAdmin()) {
			return;
		}

		// expression to search for
		$regex		= '/{iyosismaps\s+(.*?)}/i';
		$matches	= array();

		// simple performance check to determine whether bot should process further
		if (strpos($article->text, 'iyosismaps') === false) {
			return true;
		}

		// Edit mode?
		$option = JRequest::getVar('option', '');	
		$view = JRequest::getVar('view', '');	
		$task = JRequest::getVar('task', '');	
		$this->editmode = ($option=='com_content'&&$view=='article'&&$task=="edit");
		
		// check if article is in edit mode then don't replace {iyosismaps} so it can be edited
		if ($this->editmode) return true;

		// find all instances of plugin and put in $matches
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

		$document = JFactory::getDocument();
		$document->addScript( 'http://maps.google.com/maps/api/js?sensor=false' );

		require_once( JPATH_SITE.DS.'components'.DS.'com_iyosismaps'.DS.'models'.DS.'map.php' );
		$this->model = new IyosisMapsModelMap();

		foreach ($matches as $match) {
			// $match[0] is full pattern match, $match[1] is the position
			$mapID=str_replace('id=','',$match[1]);

			$javascript = $this->model->getJavascript($mapID);
			$html = $this->model->getHTML();

			// We should replace only first occurrence in order to allow positions with the same name to regenerate their content:
			$article->text = preg_replace("|$match[0]|", $html, $article->text, 1);
		}
	}
}
