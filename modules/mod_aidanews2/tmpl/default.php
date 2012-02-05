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
defined('_JEXEC') or die('Restricted access');

/* Preparation */

	/* Count the articles (they may be less or more than the module count param) */
	$conto = 0;
	foreach ($list as $item) {
		$conto++;
	}

	if ($conto) {
	/* Add Stylesheet or load custom CSS from module */
	if($params->get('style') == 0) {
		$document =& JFactory::getDocument();
		$document->addStyleSheet('modules/mod_aidanews2/css/' . $params->get('csspath'));
	}elseif($params->get('style') == 1) {
		$css_code = $params->get('cssfield');
		$document =& JFactory::getDocument();
		$document->addStyleDeclaration($css_code);
	}elseif($params->get('style') == 2) {
		$css_code = $params->get('cssfield');
		$document =& JFactory::getDocument();
		$document->addStyleDeclaration($css_code);
		$document->addStyleSheet('modules/mod_aidanews2/css/' . $params->get('csspath'));
	}
	
	/* Load Tooltip behaviour if needed */
	
	if (($params->get('tol_title')) || ($params->get('tol_img1')) || ($params->get('tol_img2')) || ($params->get('tol_img3'))) {
		JHTML::_('behavior.tooltip');
	}

	/* Get the Layout Positions */
	
	if (($params->get('postyle') == 1)) {
	
		/* From File */
		/* Detect which options are being used */
		
		if (($params->get('style') != 0) && ($params->get('style') != 2)) {
			echo JText::_('LAYSTYLERROR');
			$file = "default.txt";
		}else{
			$file = $params->get('csspath'); $last = strrpos($file, "."); $file = substr($file, 0, $last+1); $file .= 'txt';
		}
	
		/* Store the txt data in an array */
		
		$filename="modules/mod_aidanews2/layout/" . $file;
		$output=array();
		$file = fopen($filename, "r");
		$i = 0;
		while(!feof($file) && $i <= 10) {
			$output[$i] = fgets($file, 4096);
			$i++;
		}
		fclose ($file);
		
		/* Get the Positions */
		
		if (($output[1]) && ($output[1] != "[empty]")) $pos_head = '<div class="aidanews2_head" style="clear: both;">' . $output[1] . '</div>'; else $pos_head = "";
		
		if (($output[2]) && ($output[2] != "[empty]")) $pos_topl = '<div class="aidanews2_topL">' . $output[2] . '</div>'; else $pos_topl = "";
		
		if (($output[3]) && ($output[3] != "[empty]")) $pos_topr = '<div class="aidanews2_topR">' . $output[3] . '</div>'; else $pos_topr = "";
		
		if (($output[4]) && ($output[4] != "[empty]")) $pos_mainl = '<div class="aidanews2_mainL">' . $output[4] . '</div>'; else $pos_mainl = "";
		
		if (($output[5]) && ($output[5] != "[empty]")) $pos_mainc = '<div class="aidanews2_mainC">' . $output[5] . '</div>'; else $pos_mainc = "";
		
		if (($output[6]) && ($output[6] != "[empty]")) $pos_mainr = '<div class="aidanews2_mainR">' . $output[6] . '</div>'; else $pos_mainr = "";
		
		if (($output[7]) && ($output[7] != "[empty]")) $pos_botl = '<div class="aidanews2_botL">' . $output[7] . '</div>'; else $pos_botl = "";
		
		if (($output[8]) && ($output[8] != "[empty]")) $pos_botr = '<div class="aidanews2_botR">' . $output[8] . '</div>'; else $pos_botr = "";
		
		if (($output[9]) && ($output[9] != "[empty]")) $pos_foot = '<div class="aidanews2_foot" style="clear: both;">' . $output[9] . '</div>'; else $pos_foot = "";
		
	}else{
		
		/* From Fields */
			
		if (($params->get("pos_head")) && ($params->get("pos_head") != "[empty]")) $pos_head = '<div class="aidanews2_head" style="clear: both;">' . $params->get("pos_head") . '</div>'; else $pos_head = "";
		
		if (($params->get("pos_topL")) && ($params->get("pos_topL") != "[empty]")) $pos_topl = '<div class="aidanews2_topL">' . $params->get("pos_topL") . '</div>'; else $pos_topl = "";
		
		if (($params->get("pos_topR")) && ($params->get("pos_topR") != "[empty]")) $pos_topr = '<div class="aidanews2_topR">' . $params->get("pos_topR") . '</div>'; else $pos_topr = "";
		
		if (($params->get("pos_mainL")) && ($params->get("pos_mainL") != "[empty]")) $pos_mainl = '<div class="aidanews2_mainL">' . $params->get("pos_mainL") . '</div>'; else $pos_mainl = "";
		
		if (($params->get("pos_mainC")) && ($params->get("pos_mainC") != "[empty]")) $pos_mainc = '<div class="aidanews2_mainC">' . $params->get("pos_mainC") . '</div>'; else $pos_mainc = "";
		
		if (($params->get("pos_mainR")) && ($params->get("pos_mainR") != "[empty]")) $pos_mainr = '<div class="aidanews2_mainR">' . $params->get("pos_mainR") . '</div>'; else $pos_mainr = "";
		
		if (($params->get("pos_botL")) && ($params->get("pos_botL") != "[empty]")) $pos_botl = '<div class="aidanews2_botL">' . $params->get("pos_botL") . '</div>'; else $pos_botl = "";
		
		if (($params->get("pos_botR")) && ($params->get("pos_botR") != "[empty]")) $pos_botr = '<div class="aidanews2_botR">' . $params->get("pos_botR") . '</div>'; else $pos_botr = "";
		
		if (($params->get("pos_foot")) && ($params->get("pos_foot") != "[empty]")) $pos_foot = '<div class="aidanews2_foot" style="clear: both;">' . $params->get("pos_foot") . '</div>'; else $pos_foot = "";
		
	}

	/* Prepare OddEven */
	
	$counter = 1;
	
	/* Prepare Grid */
	
	if ($params->get('grid')) {
		$grid = $params->get('grid_cols');
	}else $grid = '';
	
/* Display the Module */

	echo '<div class="aidanews2' . $params->get('moduleclass_sfx');
	if ($params->get('grid')) {
		echo ' aidanews2_table';
	}
	echo '" style="clear: both;">';

foreach ($list as $item) {
	
	if ($grid && $counter%$grid == 1) {
		echo '<div class="aidanews2_tabrow">';
	}
	
	echo '<div class="aidanews2_art';
	if ($counter%2 == 1) echo ' odd'; else echo ' even';
	if ($counter == 1) echo ' first';
	if ($counter == $conto) echo ' last';
	if ($grid && $counter%$grid == 0) echo ' lastinrow';
	if ($grid && $counter%$grid == 1) echo ' firstinrow';
	if ($grid && $counter <= $grid) echo ' infirstrow';
	if ($grid) { //Make sure the cols don't add up to 100 (if there's an horizontal padding of even 1 pixel it won't display properly!)
		if (intval(100/$grid) * $grid == 100) $gw = intval(100/$grid) -1; else $gw = intval(100/$grid);
		echo '" style="width:' . $gw . '%">'; 
	}else echo '" style="clear: both;">';
	
	$counter++;
	
		/* Refresh patterns for every item */
		$patterns = array ('/\[title\]/', '/\[text\]/', '/\[empty\]/', '/\[readmore\]/', '/\[hits\]/', '/\[rating\]/', '/\[author\]/', '/\[image1\]/', '/\[image2\]/', '/\[image3\]/', '/\[date\]/', '/\[comments\]/', '/\[category\]/', '/\[br\]/');
		$replace = array ($item->title, $item->text, '', $item->rm, $item->hits, $item->rating, $item->author, $item->img1, $item->img2, $item->img3, $item->date, $item->comments, $item->category, '<br/>');
		
		/* Display Layout Positions */
		
		echo '<div class="aidanews2_positions">';
		
		/* Head */
		if (($pos_head != "[empty]") && ($pos_head)) { echo preg_replace($patterns, $replace, $pos_head); }
		
		/* Top */
		if (($pos_topl != "[empty]" && $pos_topl) || ($pos_topr != "[empty]" && $pos_topr)) {
			echo '<div class="aidanews2_top" style="clear: both;">';
				if ($pos_topl != "[empty]") { echo preg_replace($patterns, $replace, $pos_topl); }
				if ($pos_topr != "[empty]") { echo preg_replace($patterns, $replace, $pos_topr); }
				// Insert div to extend main top div (Not using css classes to be sure it is styled)
				echo '<div style="clear: both; width: 100%; padding: 0;"></div>';
			echo '</div>';
		}
		
		/* Main */
		if (($pos_mainl != "[empty]" && $pos_mainl) || ($pos_mainc != "[empty]" && $pos_mainc) || ($pos_mainr != "[empty]" && $pos_mainr)) {
			echo '<div class="aidanews2_main" style="clear: both;">';
				if ($pos_mainl != "[empty]") { echo preg_replace($patterns, $replace, $pos_mainl); }
				if ($pos_mainr != "[empty]") { echo preg_replace($patterns, $replace, $pos_mainr); }
				if ($pos_mainc != "[empty]") { echo preg_replace($patterns, $replace, $pos_mainc); }
			echo '</div>';
		}
		
		/* Bottom */
		if (($pos_botl != "[empty]" && $pos_botl) || ($pos_botr != "[empty]" && $pos_botr)) {
			echo '<div class="aidanews2_bot" style="clear: both;">';
				if ($pos_botl != "[empty]") { echo preg_replace($patterns, $replace, $pos_botl); }
				if ($pos_botr != "[empty]") { echo preg_replace($patterns, $replace, $pos_botr); }
			echo '</div>';
		}
		
		/* Footer */
		if (($pos_foot != "[empty]") && ($pos_foot)) { echo preg_replace($patterns, $replace, $pos_foot); }
		
		echo '</div>';
		
		/* Line */
		
		echo '<div class="aidanews2_line" style="clear: both; padding: 0;"></div>';
		
	echo '</div>';
	
	if ($grid && (($counter%$grid == 1) || ($counter == $conto+1))) {
		echo '</div>';
	}
}

if ($params->get('botlnk'))
	echo '<div class="aidanews2_bottomlink"><a href="' . $params->get('blnk') . '">' . $params->get('btxt') . '</a></div>';

echo '</div>';

}