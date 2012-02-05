<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jacques
 * Date: 20/01/12
 * Time: 14:49
 * To change this template use File | Settings | File Templates.
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.application.input');
jimport('joomla.event.dispatcher');
jimport('joomla.environment.response');
jimport('joomla.log.log');

/**
 */
function getLang()
{
    // Get the full request URI.
  	$uri	= clone JURI::getInstance();

    if (strpos( $uri, "/fr/") > 0)
        return "fr";
    else
    if (strpos( $uri, "/es/") > 0)
        return "es";
    else
        return "eng";
}