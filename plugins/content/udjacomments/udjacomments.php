<?php
/**
 * @author Andy Sharman
 * @copyright Andy Sharman (www.udjamaflip.com)
 * @link http://www.udjamaflip.com
 * @license GNU/GPL V2+
 * @version 1.0.1
 * @package com_udjacomments
**/
// No direct access allowed to this file
defined( '_JEXEC' ) or die( 'Restricted access' );


jimport('joomla.event.plugin');
jimport( 'joomla.application.application' );
jimport( 'joomla.application.categories' );
require_once(JPATH_SITE . '/components/com_content/helpers/route.php');

class plgContentUdjaComments extends JPlugin
{
	
	function plgContentUdjaComments(&$subject, $config )
	{		
		//run plugin
		parent::__construct($subject, $config);
		$this->loadLanguage();		
	}
	
	function onContentAfterDisplay($item, &$article)
	{		
		//get component params
		$componentParams = JComponentHelper::getParams('com_udjacomments');
		
		//default to no output
		$content = '';
		
		//output comments if article view.
		if (JRequest::getString('view') == 'article')
		{
			$document = &JFactory::getDocument();
			$renderer = $document->loadRenderer('module');

			//get module as an object
			$database = JFactory::getDBO();
			$database->setQuery('SELECT * FROM #__modules WHERE `module`="mod_udjacomments" LIMIT 1');
			$modules = $database->loadObjectList();

			//just to get rid of that stupid php warning
			$modules[0]->user = '';
			
			//render module content
			$content = $renderer->render($modules[0], array('style'=>'none'));
		}
		//else (if enabled) display comment count.
		else
		{
			//only show output if cat filter says so.
			if ($this->getViewable($componentParams, $article))
			{
				//get URL for article
				$url	= ContentHelperRoute::getArticleRoute($article->id.':'.$article->alias, $article->catid);
				$pos	= strpos($url, "index");
				if ($pos > 1) { $url = substr($url,$pos); }
				
				$pos = strpos($url, "index");
				if ($pos > 1) { $url = substr($url,$pos); }
				$url	= (substr($url,0,1) == '/') ? substr($url,1,(strlen($url)-1)) : $url ;
				
				//get Comment count
				$count	= $this->getNumComments(str_ireplace(JURI::base(),'',$url), $article);
				
				//get correct term for comment count.
				$commentCountString = ($count != 1) ? $count . ' ' . JText::_('PLG_UDJACOMMENTS_COMMENTS') : '1 ' . JTEXT::_('PLG_UDJACOMMENTS_COMMENT');
				
				//create output
				$content = '<p class="articleMeta"><a href="'.$url.'#frmUdjaComments" class="commentCount" title="'.$commentCountString.'">'.$commentCountString.'</a></p>';
			}
		}
		
		return $content;
		//return a string value. Returned value from this event will be displayed in a placeholder. 
		// Most templates display this placeholder after the article separator.
	}
	
	private function getViewable($compParams, $article)
	{
		//if the category filter param is enabled check if this category is allowed.
		if ($compParams->get('enable_catlist',0) == 1 && !in_array($article->catid, $compParams->get('cat_list', array()))) { return false; }
	
		return true;		
	}
	
	private function getNumComments($url, $article)
	{
		$sql = 'SELECT
					count(`id`) AS commentCount
				FROM
					`#__udjacomments`
				WHERE
					(
						`comment_url` = "'.$url.'"
						OR
						`comment_url` = "com_content:'.$article->id.'"
					)
				AND
					`is_spam` = 0
				AND
					`is_published` = 1';			
		
		$dbo = JFactory::getDBO();
		
		$dbo->setQuery($sql);
		
		if ($dbo->Query())
		{
			if ($result = $dbo->loadObject())
			{			
				return $result->commentCount;
			}
		}
		
		if ($err = $dbo->getErrorMsg()) { die($err); }
		
		return 0;
	}
}