<?php
/**
 * @package	Joomla.Site
 * @subpackage	MOD_FALANG
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

$headerText	= JString::trim($params->get('header_text'));
$footerText	= JString::trim($params->get('footer_text'));

$cacheid = md5(JRequest::getVar('lang').$module->module);

$cacheparams = new stdClass;
$cacheparams->cachemode = 'id';
$cacheparams->class = 'modFaLangHelper';
$cacheparams->method = 'getList';
$cacheparams->methodparams = $params;
$cacheparams->modeparams = $cacheid;

if (!modFaLangHelper::isFalangDriverActive()){
	echo JText::_("Falang Database driver not enabled");
	return;
}

$list = JModuleHelper::moduleCache ($module, $params, $cacheparams);

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_falang', $params->get('layout', 'default'));
