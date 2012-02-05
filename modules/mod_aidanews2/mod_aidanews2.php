<?php
/************************************************************************************
 mod_aidanews2 for Joomla 1.5 by Danilo A.

 @author: Danilo A. - dan@cdh.it

 ----- This file is part of the AiDaNews2 Module. -----

    AiDaNews2 Module is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    AiDaNews2 is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this module.  If not, see <http://www.gnu.org/licenses/>.
************************************************************************************/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$list = modAiDaNews2Helper::getList($params);
require(JModuleHelper::getLayoutPath('mod_aidanews2'));