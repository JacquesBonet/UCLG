<?php
/************************************************************************************
 mod_aidanews2 for Joomla 1.7 by Danilo A.

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

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class modAiDaNews2Helper{

	function imgpreflist_arrange ($p) {
		$i = 0;
		while ($i < 5) {
			if ($p[$i] != 0) {
				$j = $i+1;
				while ($j < 5) {
					if (($p[$j] == $p[$i]) || ($p[$i] == 9 && $p[$j] == 10) || ($p[$i] == 10 && $p[$j] == 9) || ($p[$i] == 2 && $p[$j] == 3) || 
					($p[$i] == 11 && $p[$j] == 12) || ($p[$i] == 12 && $p[$j] == 11) || ($p[$i] == 3 && $p[$j] == 2))  $p[$j] = 0;
					$j++;
				}
			}
			$i++;
		}
	}
	
	function imgpreflist_findlink ($p, $txt, $cimg, $uid, $num) {
		$db	=& JFactory::getDBO();
		$i = 0;
		$imgurl="";
		while ($i < 5 && empty($imgurl)) {
			if ($p[$i] == 1) { //Default
				$imgurl = "modules/mod_aidanews2/img/aidadefault" . $num . ".jpg";
			}elseif ($p[$i] == 2) { //First IMG Tag
				$matches = array();
				if (preg_match("#<img[^>]+src=['|\"](.*?)['|\"][^>]*>#i", $txt, $matches)){$imgurl = $matches[1];}
			}elseif ($p[$i] == 3) { //Last IMG Tag
				$matches = array();
				if (preg_match_all("#<img[^>]+src=['|\"](.*?)['|\"][^>]*>#i", $txt, $matches)){
					$hll = $matches[1]; $li = 0;
					foreach ($hll as $hl) {
						$li++;
					}
					$imgurl = $hll[$li-1];
				}
			}elseif ($p[$i] == 4) { //Category IMG
				if ($cimg) { //"image":"images\/banners\/white.png"
					if (preg_match("#['|\"]image['|\"]:['|\"](.*?)['|\"]#i", $cimg, $matches)) {
						$imgurl = $matches[1];
						$imgurl = preg_replace( "#\\\#", '', $imgurl );
					}
				}
			}elseif ($p[$i] == 5) { //JomSocial Avatar (Full)
				$query = 'SELECT avatar FROM #__community_users WHERE userid = ' . $uid;
					$db->setQuery($query);
					$imgurl = $db->loadResult();
			}elseif ($p[$i] == 13) { //JomSocial Avatar (Thumb)
				$query = 'SELECT thumb FROM #__community_users WHERE userid = ' . $uid;
					$db->setQuery($query);
					$imgurl = $db->loadResult();
			}elseif ($p[$i] == 6) { // CB Avatar
				$query = 'SELECT avatar FROM #__comprofiler WHERE id = ' . $uid;
					$db->setQuery($query);
					$cbavatar = $db->loadResult();
					if ($cbavatar) { $imgurl = 'images/comprofiler/' . $cbavatar; }
			}elseif ($p[$i] == 7) {
				$imgurl=""; // Kunena Avatar
			}elseif ($p[$i] == 8) { //JSocialSuite Avatar
			}elseif ($p[$i] == 9) { //First YouTube URL
				$vid = "";
				$matches = array();
				if (preg_match("'{youtube}([^<]*){/youtube}'si", $txt, $matches)){
					$vid = $matches[1];
				}elseif(preg_match('~(http://www\.youtube\.com/watch\?v=[%&=#\w-]*)~',$txt,$matches)){
					$url = $matches[1];
					if (preg_match('%youtube\\.com/(.+)%', $url, $match)) {
						$match = $match[1];
						$replace = array("watch?v=", "v/", "vi/");
						$vid = str_replace($replace, "", $match);
					}
				}
				if ($vid) {
					if (strlen($vid) > 11) {
						$vid = substr($vid, 0, 11);
					}
					$imgurl = "http://img.youtube.com/vi/" . $vid . "/0.jpg";
				}
			}elseif ($p[$i] == 10) { //Last Youtube TAG
				$vid = "";
				$matches = array();
				if (preg_match_all("'{youtube}([^<]*){/youtube}'si", $txt, $matches)){
					$vhh = $matches[1]; $vc = 0;
					foreach ($vhh as $vh) {
						$vc++;
					}
					$vid = $vhh[$vc-1];
				}elseif(preg_match_all('~(http://www\.youtube\.com/watch\?v=[%&=#\w-]*)~',$txt,$matches)){
					$vhh = $matches[1]; $vc = 0;
					foreach ($vhh as $vh) {
						$vc++;
					}
					$url = $vhh[$vc-1];
					if (preg_match('%youtube\\.com/(.+)%', $url, $match)) {
						$match = $match[1];
						$replace = array("watch?v=", "v/", "vi/");
						$vid = str_replace($replace, "", $match);
					}
				}
				if ($vid) {
					if (strlen($vid) > 11) {
						$vid = substr($vid, 0, 11);
					}
					$imgurl = "http://img.youtube.com/vi/" . $vid . "/0.jpg";
				}
			}elseif ($p[$i] == 11) { // First Gallery TAG
				$gal = "";
				if (preg_match("'{gallery}([^<]*){/gallery}'si", $txt, $matches)){$gal = $matches[1];}
				if ($gal) {
					$fold = 'images/stories' . '/' . $gal;
					$d = dir($fold) or die("Wrong path: $fold");
					$gimages = array();
					while (false !== ($entry = $d->read())) {
						if($entry != '.' && $entry != '..' && !is_dir($entry)) {
							$gimages[] = $entry;
						}
					}
					$d->close();
					$gimgurl = $gimages[0];
					if (($gimgurl == "index.htm") || ($gimgurl == "index.html")) {
						$gimgurl = $gimages[1];
					}
					$imgurl = $fold . '/' . $gimgurl;
				}
			}elseif ($p[$i] == 12) { //Last Gallery TAG
				$gal = "";
				if (preg_match_all("'{gallery}([^<]*){/gallery}'si", $txt, $matches)){
					$hal = $matches[1]; $hac = 0;
					foreach ($hal as $ha) {
						$hac++;
					}
					$gal = $hal[$hac-1];
				}
				if ($gal) {
					$fold = 'images/stories' . '/' . $gal;
					$d = dir($fold) or die("Wrong path: $fold");
					$gimages = array();
					while (false !== ($entry = $d->read())) {
						if($entry != '.' && $entry != '..' && !is_dir($entry)) {
							$gimages[] = $entry;
						}
					}
					$d->close();
					$gimgurl = $gimages[0];
					if (($gimgurl == "index.htm") || ($gimgurl == "index.html")) {
						$gimgurl = $gimages[1];
					}
					$imgurl = $fold . '/' . $gimgurl;
				}
			}
			$i++;
		}
		return $imgurl;
	}

	function shorten($txt, $cut, $type, $end){
		if ($cut > 0) {
			if ($type){
				$cut += 5;
				if (function_exists('mb_substr')) {
					$txt = mb_substr($txt, 0, $cut, 'UTF-8');
					$txt = mb_substr($txt, 0, mb_strrpos($txt," "), 'UTF-8');
				}else{
					$txt = substr($txt, 0, $cut);
					$txt = substr($txt, 0, strrpos($txt," "));
				}
				$txt .= $end;
			}else{
				$array = explode(" ", $txt);
				if (count($array)<= $cut) {
					//Do nothing
				}else{
					array_splice($array, $cut);
					$txt = implode(" ", $array) . $end;
				}
			}
		}
		$txt = str_replace('"', '&quot;', $txt);
		return $txt;
	}
	
	function creaThumb ($image, $params, $num, $id) {
	
		//Check if thumbnails folder exists - if not, create it
		
		$folder = 'cache/mod_aidanews2/thumbs/';
		
		if (!is_dir($folder)) {
			if(!is_dir('cache/mod_aidanews2')) {
				if(!is_dir('cache'))
					mkdir('cache');
				mkdir('cache/mod_aidanews2');
			}
			mkdir('cache/mod_aidanews2/thumbs/');
		}
		
		//If the module has a Thumb Suffix, get it and adjust it
		if ($params->get('tsubf')) {
			$folder .= $params->get('tsubf') . '/';
			if (!is_dir($folder)) {
				mkdir($folder);
			}
		}

		$last = strrpos($image, "/");
		$name = substr($image, $last+1);
		$ext = strrchr($name, '.');
		//If the image has a strange extension (example: '.jpg?v=7683') then adjust all the variables)
		if (strpos($ext, '?')) {
			$ext = substr($ext, 0, strpos($ext, '?'));
			$name = substr($name, 0, strpos($name, '?'));
			$image = substr($image, 0, strrpos($image, '?'));
		}
		$thumb = substr($name, 0, -strlen($ext)); 
		if ($params->get('thumbsid'))
			$newtb = $folder . $id . '-' . $num . '-' . $thumb . ".jpg";
		else
			$newtb = $folder . $num . '-' . $thumb . ".jpg";
		
		if (preg_match('~(http://img\.youtube\.com/vi/[%&=#\w-]*)~',$image,$matches)) {
			$url = $matches[1];
			preg_match('%youtube\\.com/(.+)%', $url, $match);
			$match = $match[1];
			$replace = array("watch?v=", "v/", "vi/");
			$vid = str_replace($replace, "", $match);
			$newtb = $folder . $num . '-' . $vid . '.jpg';
		}

		if (!file_exists($newtb)) {
			
			$imageHeight = $params->get('img' . $num . 'H');
			$imageWidth = $params->get('img' . $num . 'W');
			if ($imageHeight == "auto") $imageHeight = 0;
			if ($imageWidth == "auto") $imageWidth = 0;
			
			$tb = new AiDa2TeC();
			$tb->openImg($image);
			
			if($imageHeight && empty($imageWidth)) {
				$newWidth = $tb->getRightWidth($imageHeight);
				$tb->creaThumb($newWidth, $imageHeight);
				$w = $newWidth;
				$h = $imageHeight;
			}elseif(empty($imageHeight) && $imageWidth) {
				$newHeight = $tb->getRightHeight($imageWidth);
				$tb->creaThumb($imageWidth, $newHeight);
				$w = $imageWidth;
				$h = $newHeight;
			}elseif($imageHeight && $imageWidth) {
				$newWidth = $tb->getRightWidth($imageHeight);
				$newHeight = $tb->getRightHeight($imageWidth);
				if ($newWidth > $imageWidth) {
					$subWidth = ($newWidth - $imageWidth) / 2;
					$tb->creaThumb($newWidth, $imageHeight);
					$tb->setThumbAsOriginal();
					$tb->cropThumb($imageWidth, $imageHeight, $subWidth, 0);
				}elseif ($newWidth == $imageWidth) {
					$tb->creaThumb($imageWidth, $imageHeight);
				}elseif ($newWidth < $imageWidth) {
					$subHeight = ($newHeight - $imageHeight) / 2;
					$tb->creaThumb($imageWidth, $newHeight);
					$tb->setThumbAsOriginal();
					$tb->cropThumb($imageWidth, $imageHeight, 0, $subHeight);
				}
			}else{
				$orHeight = $tb->getHeight();
				$orWidth = $tb->getWidth();
				$tb->creaThumb($orWidth, $orHeight);
			}
			$tb->saveThumb($newtb, $params->get('quality'));
			$tb->closeImg();
		}
		
		return $newtb;
	}

	function getList(&$params) {
		global $mainframe;

		$db			=& JFactory::getDBO();
		$user		= JFactory::getUser();
		$userId		= (int) $user->get('id');

		$count		= (int) $params->get('count', 5);
		$offset		= (int) $params->get('offset', 0);
		
		
		$catid		= $params->get('catid');
		if ($params->get('incsub')) {
			for ($i = 0; $i <= count($catid)-1; $i++) {
				$query = 'SELECT id FROM #__categories WHERE extension = "com_content" AND parent_id = ' . $catid[$i];
				$db->setQuery($query);
				$subs = $db->loadResultArray();
				if($subs) {
					foreach ($subs as $s) {
						$catid[count($catid)] = $s;
					}
				}
			}
			if (is_array($catid)) $catid = implode(", ", $catid);
		}else{
			if (is_array($catid)) $catid = implode(", ", $catid);
		}
		
		
		$aid		= $user->get('access');
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$aid = 0;
		foreach ($authorised as $a) {
			if ($a > $aid) $aid = $a;
		};

		$nullDate	= $db->getNullDate();

		$date =& JFactory::getDate();
		$now = $date->toMySQL();
		
		/* IMAGE CHECKS - Before activating everything concerning images, make sure you need them! */
		
		$chpos = $params->get("pos_head") . ' ' . $params->get("pos_topL") . ' ' . $params->get("pos_topR") . ' ' . $params->get("pos_mainL")
		 . ' ' . $params->get("pos_mainC") . ' ' . $params->get("pos_mainR") . ' ' . $params->get("pos_botL") . ' ' . $params->get("pos_botR")
		  . ' ' . $params->get("pos_foot");
		$chimg1 = strrpos ($chpos, '[image1]');
		$chimg2 = strrpos ($chpos, '[image2]');
		$chimg3 = strrpos ($chpos, '[image3]');

		// User Filter
		$wauth = '';
		switch ($params->get( 'user_id' ))
		{
			case 'by_me':
				if ($userId)
				$wauth = '(a.created_by = ' . (int) $userId . ' OR a.modified_by = ' . (int) $userId . ')';
				if (!$userId) $wauth = ' AND (a.created_by = 0)';
				break;
			case 'not_me':
				if ($userId)
				$wauth = '(created_by <> ' . (int) $userId . ' AND modified_by <> ' . (int) $userId . ')';
				break;
			case 'sel':
				if (!$params->get('sauth')) {
					$authors = $params->get('authors');
					if (is_array($authors)) {
						$authors = implode(",", $authors);
					}
					$wauth = "a.created_by IN ( $authors )";
				}
				break;
		}
		
		// Options related to the article you're viewing at the moment
		
		$pauth = "";
		
		if (($params->get('cco')) || ($params->get('sauth'))) {      
			$temp = JRequest::getVar('id');
			if($temp) {
				if ( strpos($temp,':') > 0 ) {
					$temp = substr($temp,0,strpos($temp,':'));
				}
				
				// Show articles taken from the same category
				
				if ($params->get('cco')) {
					$query = 'SELECT catid FROM #__content WHERE id = ' . $temp;
						$db->setQuery($query);
						$catid = $db->loadResult();
				}
				
				// Show articles from the same author
				
				if ($params->get('sauth')) {
					$query = 'SELECT created_by FROM #__content WHERE id = ' . $temp;
						$db->setQuery($query);
						$sauth = $db->loadResult();
						$pauth = "a.created_by = " . $sauth . " ";
				}
			}
		}
		
		// Prepare Comment table and column
		
		if ($params->get('ctab') == '3') {
			$ctable = '#__webeeComment_Comment';
			$cartcol = 'articleId';
		}elseif ($params->get('ctab') == '5') {
			$ctable = '#__yvcomment';
			$cartcol = 'parentid';
		}elseif ($params->get('ctab') == '6') {
			$ctable = '#__zimbcomment_comment';
			$cartcol = 'articleId';
		}elseif ($params->get('ctab') == '7') {
			$ctable = '#__rdbs_comment_comments';
			$cartcol = 'refid';
		}elseif ($params->get('ctab') == '8') {
			$ctable = '#__comments';
			$cartcol = 'cotid';
		}elseif ($params->get('ctab') == '9') {
			$ctable = '#__jomcomment';
			$cartcol = 'contentid';
		}elseif ($params->get('ctab') == '10') {
			$ctable = "#__kunena_messages AS m JOIN #__kunenadiscuss AS d ON m.thread = d.thread_id";
			$cartcol = "d.content_id";
		}
		
		
		
		
		
		
		
		
		
		
		
		// Last X days
		$recent 				= $params->get('recent', 0);
		






		
		// Related Articles

		$relatedcond = '';
		if ($params->get('related') == 1) {
			$remp				= JRequest::getString('id');
			$remp				= explode(':', $remp);
			$id					= $remp[0];
			if ($id) {
				$query = 'SELECT metakey' .
					' FROM #__content' .
					' WHERE id = '.(int) $id;
					$db->setQuery($query);
					$metakey = trim($db->loadResult());

					if ($metakey) {
						// explode the meta keys on a comma
						$keys = explode(',', $metakey);
						$likes = array ();

						// assemble any non-blank word(s)
						foreach ($keys as $key) {
							$key = trim($key);
							if ($key) {
								$likes[] = ',' . $db->getEscaped($key) . ','; // surround with commas so first and last items have surrounding commas
							}
							$glue = "%' OR CONCAT(',', REPLACE(a.metakey,', ',','),',') LIKE '%";
							$relatedcond = "( CONCAT(',', REPLACE(a.metakey,', ',','),',') LIKE '%" . implode( $glue , $likes) . "%' )";
						}
						$relnorepeat = "a.id <> " . $id;
						
						if (empty($relatedcond) && empty($relnorepeat)) {
							$relatedcond = "";
							$relnorepeat = "";
						}
					}else{
						$relatedcond = "a.id = 'die'";
						$relnorepeat = "";
					}
			}else{				
				$relatedcond = "a.id = 'die'";
				$relnorepeat = "";
			}
		}else{
			$relatedcond = "";
			$relnorepeat = "";
		}

		// Ordering
		
			//Order by Comments
			$oc = "";
			
			//Events Ordering
			$evcon = "";
		
		if ($params->get("dasc")) $dasc = "DESC"; else $dasc = "ASC";
		if ($params->get("sdasc")) $sdasc = "DESC"; else $sdasc = "ASC";
		
		/* Primary */
		if ($params->get('ordering') == 0)
			$ordering = 'a.modified ' . $dasc;
		elseif ($params->get('ordering') == 1)
			$ordering = 'a.created ' . $dasc;
		elseif ($params->get('ordering') == 2)
			$ordering = 'a.hits ' . $dasc;
		elseif ($params->get('ordering') == 3)
			$ordering = 'RAND() ';
		elseif ($params->get('ordering') == 4)
			$ordering = 'a.title ' . $dasc;
		elseif ($params->get('ordering') == 5)
			$ordering = 'r.rating_sum / r.rating_count ' . $dasc;
		elseif ($params->get('ordering') == 6)
			$ordering = 'r.rating_count ' . $dasc;
		elseif ($params->get('ordering') == 7)
			$ordering = 'a.id ' . $dasc;
		elseif ($params->get('ordering') == 8){
			if ($params->get('ctab') != '0') {
				$oc = "(SELECT COUNT(*) FROM " . $ctable . " WHERE " . $cartcol . " = a.id ) AS comen";
				$ordering = 'comen ' . $dasc;
			}else{
				echo '<span class="aidawarning">' . JText::_('COMORDWARNING') . '</span>';
				$ordering = " RAND()";
			}
		}elseif ($params->get('ordering') == 9) {
			$ordering = 'a.publish_down ASC';
			$evcon = "\n AND a.publish_down >= '$now' " ;
		}elseif ($params->get('ordering') == 10)
			$ordering = 'f.ordering ' . $dasc;
			
		/* Secondary */
		if ($params->get('sord') == 20)
			$ordering .= ', a.modified ' . $sdasc;
		elseif ($params->get('sord') == 1)
			$ordering .= ', a.created ' . $sdasc;
		elseif ($params->get('sord') == 2)
			$ordering .= ', a.hits ' . $sdasc;
		elseif ($params->get('sord') == 3)
			$ordering .= ', RAND() ';
		elseif ($params->get('sord') == 4)
			$ordering .= ', a.title ' . $sdasc;
		elseif ($params->get('sord') == 5)
			$ordering .= ', r.rating_sum / r.rating_count ' . $sdasc;
		elseif ($params->get('sord') == 6)
			$ordering .= ', r.rating_count ' . $sdasc;
		elseif ($params->get('sord') == 7)
			$ordering .= ', a.id ' . $sdasc;
		elseif ($params->get('sord') == 8){
			if ($params->get('ctab') != '0') {
				$oc = "(SELECT COUNT(*) FROM " . $ctable . " AS ordcom WHERE ordcom." . $cartcol . " = a.id ) AS comen";
				$ordering .= ", comen " . $sdasc;
			}else{
				echo '<span class="aidawarning">' . JText::_('COMORDWARNING') . '</span>';
				$ordering = " RAND()";
			}
		}elseif ($params->get('sord') == 10)
			$ordering .= ', f.ordering ' . $dasc;
			
		if ($catid)
		{
			$ids = explode( ',', $catid );
			JArrayHelper::toInteger( $ids );
			$catCondition = '(cc.id = ' . implode( ' OR cc.id = ', $ids ) . ')';
		}
		
		$feats = '';
		if ($params->get('foffset')) {
			$foffset = $params->get('foffset');
			$query = $db->getQuery(true);
			$query->select('a.*')
			->select('r.rating_count')
			->select('r.rating_sum')
			->select('cc.params AS catparams')
			->select('cc.title AS cattle')
			->select('cc.alias AS category_alias');
			if ($oc) $query->select($oc);
			$query->from('#__content AS a')
			->innerJoin('#__categories AS cc ON cc.id = a.catid')
			->leftJoin('#__content_rating AS r ON r.content_id = a.id');
			$query->innerJoin('#__content_frontpage AS f ON f.content_id = a.id');
			$query->where('cc.published = 1')
			->where('( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )')
			->where('( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )');
			if($wauth) $query->where($wauth);
			if($pauth) $query->where($pauth);
			if($catid) $query->where($catCondition);
			if($params->get('show_front') == 1) $query->where('f.content_id IS NULL ');
			if($access && $params->get('sua')) $query->where('a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid);
			if($recent) $query->where('DATEDIFF('.$db->Quote($now).', a.created) < ' . $recent);
			if($relnorepeat) $query->where($relnorepeat);
			if($relatedcond) $query->where($relatedcond);
			if($params->get('show_trash') == 1) $query->where('a.state = 1');
			if($params->get('show_trash') == 2) $query->where('a.state <> 1');
			$query->order($ordering);
			$db->setQuery($query, $offset, $foffset);
			$feats = $db->loadResultArray();
			$feats = implode(',', $feats);
		}
		
		$query = $db->getQuery(true);
		$query->select('a.*')
		->select('r.rating_count')
		->select('r.rating_sum')
		->select('cc.params AS catparams')
		->select('cc.title AS cattle')
		->select('cc.alias AS category_alias');
		if ($oc) $query->select($oc); // Needs to be tested with a commenting system!
		$query->from('#__content AS a')
		->innerJoin('#__categories AS cc ON cc.id = a.catid')
		->leftJoin('#__content_rating AS r ON r.content_id = a.id');
		if($params->get('show_front')) {
			if($params->get('show_front') == 1) $query->leftJoin('#__content_frontpage AS f ON f.content_id = a.id');
			if($params->get('show_front') == 2) $query->innerJoin('#__content_frontpage AS f ON f.content_id = a.id');
		}elseif($params->get('ordering') == 10 || $params->get('sord') == 10){
			$query->leftJoin('#__content_frontpage AS f ON f.content_id = a.id');
		}
		$query->where('cc.published = 1')
		->where('( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )')
		->where('( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )');
		if($wauth) $query->where($wauth);
		if($pauth) $query->where($pauth);
		if($catid) $query->where($catCondition);
		// modif for UCLG
		if (isset($_SESSION['uclg_section']))
		{ 
			if (strcmp( $_SESSION['uclg_section'], "world") != 0)
			{
				$filter = $_SESSION['uclg_section'];
				$query->where('LOWER(a.alias) LIKE \'%'.$filter.'%\' OR LOWER(a.alias) LIKE \'%-world-%\' ');
			}
		}
		// end modif for UCLGz
		if($params->get('show_front') == 1) $query->where('f.content_id IS NULL ');
		if($access && $params->get('sua')) $query->where('a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid);
		if($recent) $query->where('DATEDIFF('.$db->Quote($now).', a.created) < ' . $recent);
		if($relnorepeat) $query->where($relnorepeat);
		if($relatedcond) $query->where($relatedcond);
		if ($params->get('foffset') && $feats) $query->where('a.id NOT IN (' . $feats . ')');
		if($params->get('show_trash') == 1) $query->where('a.state = 1');
		if($params->get('show_trash') == 2) $query->where('a.state <> 1');
		$query->order($ordering);
		$db->setQuery($query, $offset, $count);
		$rows = $db->loadObjectList();

		$i		= 0;
		$lists	= array();
		
		
		
		
		
		
		
		
		/* GET IMAGE PREFERENCE LIST FOR IMAGE 1 */
		
		if ($chimg1) {
		
			$pref1 = array();
			
			$pref1[0] = $params->get("img1pref1");
			$pref1[1] = $params->get("img1pref2");
			$pref1[2] = $params->get("img1pref3");
			$pref1[3] = $params->get("img1pref4");
			$pref1[4] = $params->get("img1pref5");
			
			modAiDaNews2Helper::imgpreflist_arrange($pref1);
			
		}
			
		/* GET IMAGE PREFERENCE LIST FOR IMAGE 2 */
		
		if ($chimg2) {
		
			$pref2 = array();
			
			$pref2[0] = $params->get("img2pref1");
			$pref2[1] = $params->get("img2pref2");
			$pref2[2] = $params->get("img2pref3");
			$pref2[3] = $params->get("img2pref4");
			$pref2[4] = $params->get("img2pref5");
			
			modAiDaNews2Helper::imgpreflist_arrange($pref2);
		
		}
		
		/* GET IMAGE PREFERENCE LIST FOR IMAGE 3 */
		
		if ($chimg3) {
		
			$pref3 = array();
			
			$pref3[0] = $params->get("img3pref1");
			$pref3[1] = $params->get("img3pref2");
			$pref3[2] = $params->get("img3pref3");
			$pref3[3] = $params->get("img3pref4");
			$pref3[4] = $params->get("img3pref5");
			
			modAiDaNews2Helper::imgpreflist_arrange($pref3);
			
		}

		/* START WITH ITEMS */
		
		foreach ( $rows as $row ) {
		
		// Prepare some variables for each article...
			
			$row->slug = $row->id.':'.$row->alias;
			$row->catslug = $row->catid.':'.$row->category_alias;
		
		/* LINKS CREATION */
			
			/* ARTICLE LINK */
			
			$artlink = ContentHelperRoute::getArticleRoute($row->slug, $row->catslug);
			if($row->access <= $aid) {
				if ($params->get('omid'))
					if (preg_match("'&Itemid=([^<]*)'si", $artlink))
						$artlink = JRoute::_(preg_replace("'&Itemid=([^<]*)'si", '&Itemid=' . $params->get('cmid'), $artlink));
					else
						$artlink = JRoute::_($artlink . '&Itemid=' . $params->get('cmid'));
				else
					$artlink = JRoute::_($artlink);
			} else {
				$artlink = JRoute::_('index.php?option=com_users&view=login');
			}

			
			
			
			
			
			
			
			/* CATEGORY LINK */
			
				$catlink = JRoute::_(ContentHelperRoute::getCategoryRoute($row->catid));
			
			
			
			
			
			/* COMMUNITY BUILDER LINK */
			
				$cblink = JRoute::_('index.php?option=com_comprofiler&task=userProfile&user=' . $row->created_by);
			
			/* JOMSOCIAL LINK */
			
				$jslink = JRoute::_('index.php?option=com_community&view=profile&userid=' . $row->created_by);
			
			/* KUNENA LINK */
			
				$kunlink = 'index.php?option=com_kunena&func=profile&userid=' . $row->created_by;
			
			/* JSOCIALSUITE LINK */
			
				$jsslink = JRoute::_('index.php?option=com_jsocialsuite&amp;task=profile.view&amp;id=' . $row->created_by);
			
		/* ELEMENTS */
		
			/* IMAGE 1 */
			
		if ($chimg1) {
				
				$img1url = "";
				$img1url = modAiDaNews2Helper::imgpreflist_findlink($pref1, $row->introtext . ' ' . $row->fulltext, $row->catparams, $row->created_by, 1);
				
			if($img1url) {
				
				if ($params->get('usethumbs')) {
					$img1url = modAiDaNews2Helper::creaThumb($img1url, $params, 1, $row->id);
					list($w, $h) = getimagesize($img1url);
					$img1url = '<img src="' . $img1url . '" width="' . $w . '" height="' . $h . '" alt="' . $row->alias . '"/>';
				}else{
					$h = $params->get('img1H');
					$w = $params->get('img1W');
					$img1url = '<img src="' . $img1url . '"';
					if ($w && $w != "auto") $img1url .= ' width="' . $w . '"';
					if ($h && $h != "auto") $img1url .= ' height="' . $h . '"';
					$img1url .= ' alt="' . $row->alias . '"/>';
				}
				
				//Insert Links
				
				if ($params->get('img1lnk') == 1) {
					$img1url = '<a class="aidanews2_img1" href="' . $artlink . '">' . $img1url . '</a>';
				}elseif ($params->get('img1lnk') == 2) {
					$img1url = '<a class="aidanews2_img1" href="' . $catlink . '">' . $img1url . '</a>';
				}elseif ($params->get('img1lnk') == 4) {
					$img1url = '<a class="aidanews2_img1" href="' . $cblink . '">' . $img1url . '</a>';
				}elseif ($params->get('img1lnk') == 5) {
					$img1url = '<a class="aidanews2_img1" href="' . $jslink . '">' . $img1url . '</a>';
				}elseif ($params->get('img1lnk') == 6) {
					$img1url = '<a class="aidanews2_img1" href="' . $kunlink . '">' . $img1url . '</a>';
				}elseif ($params->get('img1lnk') == 7) {
					$img1url = '<a class="aidanews2_img1" href="' . $jsslink . '">' . $img1url . '</a>';
				}
				
				$lists[$i]->img1 = $img1url;
				
			}else $lists[$i]->img1 = '';
			
		}else $lists[$i]->img1 = '';
			


			/* IMAGE 2 */
			
		if ($chimg2) {
		
				$img2url = "";
				$img2url = modAiDaNews2Helper::imgpreflist_findlink($pref2, $row->introtext . ' ' . $row->fulltext, $row->catparams, $row->created_by, 2);
				
				//Thumbs?
				
			if($img2url) {
			
				if ($params->get('usethumbs')) {
					$img2url = modAiDaNews2Helper::creaThumb($img2url, $params, 2, $row->id);
					list($w, $h) = getimagesize($img2url);
					$img2url = '<img src="' . $img2url . '" width="' . $w . '" height="' . $h . '" alt="' . $row->alias . '"/>';
				}else{
					$h = $params->get('img2H');
					$w = $params->get('img2W');
					$img2url = '<img src="' . $img2url . '"';
					if ($w && $w != "auto") $img2url .= ' width="' . $w . '"';
					if ($h && $h != "auto") $img2url .= ' height="' . $h . '"';
					$img2url .= ' alt="' . $row->alias . '"/>';
				}
				
				//Insert Links
				
				if ($params->get('img2lnk') == 1) {
					$img2url = '<a class="aidanews2_img2" href="' . $artlink . '">' . $img2url . '</a>';
				}elseif ($params->get('img2lnk') == 2) {
					$img2url = '<a class="aidanews2_img2" href="' . $catlink . '">' . $img2url . '</a>';
				}elseif ($params->get('img2lnk') == 4) {
					$img2url = '<a class="aidanews2_img2" href="' . $cblink . '">' . $img2url . '</a>';
				}elseif ($params->get('img2lnk') == 5) {
					$img2url = '<a class="aidanews2_img2" href="' . $jslink . '">' . $img2url . '</a>';
				}elseif ($params->get('img2lnk') == 6) {
					$img2url = '<a class="aidanews2_img2" href="' . $kunlink . '">' . $img2url . '</a>';
				}elseif ($params->get('img2lnk') == 7) {
					$img2url = '<a class="aidanews2_img2" href="' . $jsslink . '">' . $img2url . '</a>';
				}
				
				$lists[$i]->img2 = $img2url;
				
			}else $lists[$i]->img2 = '';
			
		}else $lists[$i]->img2 = '';
			


			/* IMAGE 3 */
			
		if ($chimg3) {
			
				$img3url = "";
				$img3url = modAiDaNews2Helper::imgpreflist_findlink($pref3, $row->introtext . ' ' . $row->fulltext, $row->catparams, $row->created_by, 3);
				
				//Thumbs?
			
			if($img3url) {
			
				if ($params->get('usethumbs')) {
					$img3url = modAiDaNews2Helper::creaThumb($img3url, $params, 3, $row->id);
					list($w, $h) = getimagesize($img3url);
					$img3url = '<img src="' . $img3url . '" width="' . $w . '" height="' . $h . '" alt="' . $row->alias . '"/>';
				}else{
					$h = $params->get('img3H');
					$w = $params->get('img3W');
					$img3url = '<img src="' . $img3url . '"';
					if ($w && $w != "auto") $img3url .= ' width="' . $w . '"';
					if ($h && $h != "auto") $img3url .= ' height="' . $h . '"';
					$img3url .= ' alt="' . $row->alias . '"/>';
				}
				
				//Insert Links
				
				if ($params->get('img3lnk') == 1) {
					$img3url = '<a class="aidanews2_img3" href="' . $artlink . '">' . $img3url . '</a>';
				}elseif ($params->get('img3lnk') == 2) {
					$img3url = '<a class="aidanews2_img3" href="' . $catlink . '">' . $img3url . '</a>';
				}elseif ($params->get('img3lnk') == 4) {
					$img3url = '<a class="aidanews2_img3" href="' . $cblink . '">' . $img3url . '</a>';
				}elseif ($params->get('img3lnk') == 5) {
					$img3url = '<a class="aidanews2_img3" href="' . $jslink . '">' . $img3url . '</a>';
				}elseif ($params->get('img3lnk') == 6) {
					$img3url = '<a class="aidanews2_img3" href="' . $kunlink . '">' . $img3url . '</a>';
				}elseif ($params->get('img3lnk') == 7) {
					$img3url = '<a class="aidanews2_img3" href="' . $jsslink . '">' . $img3url . '</a>';
				}
				
				$lists[$i]->img3 = $img3url;
				
			}else $lists[$i]->img3 = '';
			
		}else $lists[$i]->img3 = '';
			


			/* TITLE */
			
				/* GET TITLE */
			
				$tit = str_replace ('$', '\$', htmlspecialchars( $row->title ));
			
				/* LINK */
			
				$titlinkb = ""; $titlinke = "";
				if ($params->get('titnp')) { $titblank = ' target="_blank"'; }else{ $titblank = ""; }
				if ($params->get('lnktit')) { $titlinkb = '<a href="' . $artlink . '"' . $titblank . '>'; $titlinke = '</a>'; }
				
				/* H1 H2 H3 Span */
				
				$hspanb = ""; $hspane = "";
				if ($params->get('hspan') == 0) {
					$hspanb = '<span class="aidanews2_title">'; $hspane = '</span>';
				}elseif ($params->get('hspan') == 1) {
					$hspanb = '<h1 class="aidanews2_title">'; $hspane = '</h1>';
				}elseif ($params->get('hspan') == 2) {
					$hspanb = '<h2 class="aidanews2_title">'; $hspane = '</h2>';
				}elseif ($params->get('hspan') == 3) {
					$hspanb = '<h3 class="aidanews2_title">'; $hspane = '</h3>';
				}
				
				/* SHORTEN TITLE */
				
				if ($params->get('titnum') && strlen($tit) > $params->get('titnum')) $tit = modAiDaNews2Helper::shorten($tit, $params->get('titnum'), $params->get('titsh'), $params->get("titend"));
			
			$lists[$i]->title = $hspanb . $titlinkb .  $tit . $titlinke . $hspane;
			
			/* TEXT */
			
				/* CHOOSE TEXT */
				
				if ($params->get('txtwhat') == 0) {
					$txt = str_replace ('$', '\$', $row->introtext);
				}elseif ($params->get('txtwhat') == 1) {
					$txt = str_replace ('$', '\$', $row->fulltext);
				}elseif ($params->get('txtwhat') == 2) {
					$txt = str_replace ('$', '\$', $row->introtext . $row->fulltext);
				}elseif ($params->get('txtwhat') == 3) {
					$txt = str_replace ('$', '\$', $row->metadesc);
				}
				
				/* STRIP TAGS */
				
				if ($params->get('txtstrip')) { $txtallow = $params->get('txtallow'); $txt = strip_tags(str_replace ("<br/>"," ",$txt), $txtallow); }
				
				/* STRIP PLUGINS */
				
				if ($params->get('txtplugs')) {
					$txt = preg_replace("'{.*?}([^<]*){/.*?}'si", '', $txt);
					$txt = preg_replace('#\{.*?\}#', '', $txt);
				}
				
				/* SHORTEN TEXT */
				
				if ($params->get('txtnum') && strlen($txt) > $params->get('txtnum')) $txt = modAiDaNews2Helper::shorten($txt, $params->get('txtnum'), $params->get('txtsh'), $params->get("txtend"));
			
			$lists[$i]->text = '<span class="aidanews2_text">' . $txt . '</span>';
			
			/* READ MORE */
			
			$lists[$i]->rm = '<a href="' . $artlink . '" class="readon"><span class="aidanews2_readmore">' . $params->get('readmore') . '</span></a>';
			
			/* HITS */
			
			$lists[$i]->hits = '<span class="aidanews2_hits">' . $row->hits . '</span>';
			
			/* RATING */
			
			if ($row->rating_count == 0) $row->rating_count = 1;
			
			if ($params->get('rstars')) {
				$rate = round($row->rating_sum / $row->rating_count, 0);
				$lists[$i]->rating = '<div class="aidanews2_stars_rating">';
				for ($rr = 0; $rr < 5; $rr++) {
					if ($rr < $rate) $lists[$i]->rating .= '<img src="modules/mod_aidanews2/img/default/rating.png" alt="' . $rate . '" title="' . $rate . '" width="16" height="16"/>';
					else $lists[$i]->rating .= '<img src="modules/mod_aidanews2/img/default/no-rating.png" alt="' . $rate . '" title="' . $rate . '" width="16" height="16"/>';
				}
				$lists[$i]->rating .= '</div>';
			}else{
				$lists[$i]->rating = '<span class="aidanews2_rating">' . round($row->rating_sum / $row->rating_count, $params->get('rround')) . '</span>';
			}
			
			/* CATEGORY */
			
			if ($params->get('caturl'))
				$lists[$i]->category = '<span class="aidanews2_category aidacat_' . $row->catid . '"><a href="' . $catlink . '">' . $row->cattle . '</a></span>';
			else
				$lists[$i]->category = '<span class="aidanews2_category aidacat_' . $row->catid . '">' . $row->cattle . '</span>';
			
			
			
			
			
			
			
			
			/* DATE */
			
			$dto = $params->get('dto');
			$dst = false; $dnd = 0;
			if (strpos($dto, "[st]") !== false) $dst = true;
			
			if ($params->get('wdate') == 0) {
				$date = JHTML::_('date', $row->created, $dto);
				if ($dst) $dnd = JHTML::_('date', $row->created, "%d");
			} elseif ($params->get('wdate') == 1) {
				$date = JHTML::_('date', $row->modified, $dto);
				if ($dst) $dnd = JHTML::_('date', $row->modified, "%d");
			} elseif ($params->get('wdate') == 2) {
				$date = JHTML::_('date', $row->publish_up, $dto);
				if ($dst) $dnd = JHTML::_('date', $row->publish_up, "%d");
			} elseif ($params->get('wdate') == 3) {
				$date = JHTML::_('date', $row->publish_down, $dto);
				if ($dst) $dnd = JHTML::_('date', $row->publish_down, "%d");
			}
			
			if ($dnd) {
				if ($dnd == 1) $date = str_replace('[st]', "st", $date);
				elseif ($dnd == 2) $date = str_replace('[st]', "nd", $date);
				elseif ($dnd == 3) $date = str_replace('[st]', "rd", $date);
				elseif ($dnd == 21) $date = str_replace('[st]', "st", $date);
				elseif ($dnd == 22) $date = str_replace('[st]', "nd", $date);
				elseif ($dnd == 23) $date = str_replace('[st]', "rd", $date);
				elseif ($dnd == 31) $date = str_replace('[st]', "st", $date);
				else $date = str_replace('[st]', "th", $date);
			}
			
			$lists[$i]->date = '<span class="aidanews2_date">' . $date . '</span>';
			
			/* AUTHOR */
			
				/* GET NAME OR USERNAME */
				
				$auth = "";
				$alias = "";
				
				if ($params->get('authtype') == 0) {
					$query = 'SELECT name FROM #__users WHERE id = ' . $row->created_by;
						$db->setQuery($query);
						$auth = $db->loadResult();
				}elseif ($params->get('authtype') == 1) {
					$query = 'SELECT username FROM #__users WHERE id = ' . $row->created_by;
						$db->setQuery($query);
						$auth = $db->loadResult();
				}elseif ($params->get('authtype') == 2) {
					if ($row->created_by_alias) {
						$alias = $row->created_by_alias;
					}else{
						$query = 'SELECT name FROM #__users WHERE id = ' . $row->created_by;
							$db->setQuery($query);
							$auth = $db->loadResult();
					}
				}elseif ($params->get('authtype') == 3) {
					if ($row->created_by_alias) {
						$alias = $row->created_by_alias;
					}else{
						$query = 'SELECT username FROM #__users WHERE id = ' . $row->created_by;
							$db->setQuery($query);
							$auth = $db->loadResult();
					}
				}
				
				/* LINK AUTHOR */
				
				if ($alias) {
					$aut = $alias;
				}else{
					if ($params->get('authlnk') == 1)
						$aut = '<a href="' . $cblink . '">' . $auth . '</a>';
					elseif ($params->get('authlnk') == 2)
						$aut = '<a href="' . $jslink . '">' . $auth . '</a>';
					elseif ($params->get('authlnk') == 3)
						$aut = '<a href="' . $jsslink . '">' . $auth . '</a>';
					else
						$aut = $auth;
				}
				
			$lists[$i]->author = '<span class="aidanews2_author">' . $aut . '</span>';
			
			/* COMMENTS */
			
			if ($params->get('ctab') != '0') {
			$query = 'SELECT COUNT(*) FROM ' . $ctable . ' WHERE ' . $cartcol . ' = ' . $row->id ;
				$db->setQuery($query);
				$comments = $db->loadResult();
				
				if ($params->get('ctab') == '10' && $comments) {
					$comments -= 1;
				}
						
			$lists[$i]->comments = '<span class="aidanews2_comments">' . $comments . '</span>';} else $lists[$i]->comments = '';
			
			/* TOOLTIPS */
			
			if (($params->get('tol_title')) || ($params->get('tol_img1')) || ($params->get('tol_img2')) || ($params->get('tol_img3'))) {
			
				/* TAGS THAT CAN BE INSERTED IN THE TOOLTIPS */
				
					/* Refresh patterns for every item */
					$patterns = array ('/\[title\]/', '/\[text\]/', '/\[empty\]/', '/\[author\]/', '/\[date\]/', '/\[category\]/');
					$replace = array ($row->title, $txt, '', $aut, $date, $row->cattle);
					
				/* EXCHANGE TAGS AND ADD TOOLTIPS TO ELEMENTS */
				
					$toltit = $params->get('tol_title');
				
					if ($toltit && $toltit != '[empty]') {
						$lists[$i]->title = '<span class="hasTip" title="' . preg_replace($patterns, $replace, $toltit) . '">' . $lists[$i]->title . '</span>';
					}
					
					$tolimg1 = $params->get('tol_img1');
					
					if ($tolimg1 && $tolimg1 != '[empty]') {
						$lists[$i]->img1 = '<span class="hasTip" title="' . preg_replace($patterns, $replace, $tolimg1) . '">' . $lists[$i]->img1 . '</span>';
					}
					
					$tolimg2 = $params->get('tol_img2');
					
					if ($tolimg2 && $tolimg2 != '[empty]') {
						$lists[$i]->img2 = '<span class="hasTip" title="' . preg_replace($patterns, $replace, $tolimg2) . '">' . $lists[$i]->img2 . '</span>';
					}
					
					$tolimg3 = $params->get('tol_img3');
					
					if ($tolimg3 && $tolimg3 != '[empty]') {
						$lists[$i]->img3 = '<span class="hasTip" title="' . preg_replace($patterns, $replace, $tolimg3) . '">' . $lists[$i]->img3 . '</span>';
					}
			}
				
			/* ...NEXT! */
			
			$i++;
		}

		return $lists;
	}
	
}

class AiDa2TeC {

		private $handleimg;
		private $original = "";
		private $handlethumb;
		private $oldoriginal;

		/*
			Apre l'immagine da manipolare
		*/
		public function openImg($file)
		{
			$this->original = $file;

			if($this->extension($file) == 'jpg' || $this->extension($file) == 'jpeg')
			{
				$this->handleimg = imagecreatefromjpeg($file);
			}
			elseif($this->extension($file) == 'png')
			{
				$this->handleimg = imagecreatefrompng($file);
			}
			elseif($this->extension($file) == 'gif')
			{
				$this->handleimg = imagecreatefromgif($file);
			}
			elseif($this->extension($file) == 'bmp')
			{
				$this->handleimg = imagecreatefromwbmp($file);
			}
		}

		/*
			Ottiene la larghezza dell'immagine
		*/
		public function getWidth()
		{
			return imageSX($this->handleimg);
		}

		/*
			Ottiene la larghezza proporzionata all'immagine partendo da un'altezza
		*/
		public function getRightWidth($newheight)
		{
			$oldw = $this->getWidth();
			$oldh = $this->getHeight();

			$neww = ($oldw * $newheight) / $oldh;

			return $neww;
		}

		/*
			Ottiene l'altezza dell'immagine
		*/
		public function getHeight()
		{
			return imageSY($this->handleimg);
		}

		/*
			Ottiene l'altezza proporzionata all'immagine partendo da una larghezza
		*/
		public function getRightHeight($newwidth)
		{
			$oldw = $this->getWidth();
			$oldh = $this->getHeight();

			$newh = ($oldh * $newwidth) / $oldw;

			return $newh;
		}

		/*
			Crea una miniatura dell'immagine
		*/
		public function creaThumb($newWidth, $newHeight)
		{
			$oldw = $this->getWidth();
			$oldh = $this->getHeight();

			$this->handlethumb = imagecreatetruecolor($newWidth, $newHeight);

			return imagecopyresampled($this->handlethumb, $this->handleimg, 0, 0, 0, 0, $newWidth, $newHeight, $oldw, $oldh);
		}

		/*
			Ritaglia un pezzo dell'immagine
		*/
		public function cropThumb($width, $height, $x, $y)
		{
			$oldw = $this->getWidth();
			$oldh = $this->getHeight();

			$this->handlethumb = imagecreatetruecolor($width, $height);

			return imagecopy($this->handlethumb, $this->handleimg, 0, 0, $x, $y, $width, $height);
		}

		/*
			Salva su file la Thumbnail
		*/
		public function saveThumb($path, $qualityJpg = 100)
		{
			if($this->extension($this->original) == 'jpg' || $this->extension($this->original) == 'jpeg')
			{
				return imagejpeg($this->handlethumb, $path, $qualityJpg);
			}
			elseif($this->extension($this->original) == 'png')
			{
				return imagepng($this->handlethumb, $path);
			}
			elseif($this->extension($this->original) == 'gif')
			{
				return imagegif($this->handlethumb, $path);
			}
			elseif($this->extension($this->original) == 'bmp')
			{
				return imagewbmp($this->handlethumb, $path);
			}
		}

		/*
			Stampa a video la Thumbnail
		*/
		public function printThumb()
		{
			if($this->extension($this->original) == 'jpg' || $this->xtension($this->original) == 'jpeg')
			{
				header("Content-Type: image/jpeg");
				imagejpeg($this->handlethumb);
			}
			elseif($this->extension($this->original) == 'png')
			{
				header("Content-Type: image/png");
				imagepng($this->handlethumb);
			}
			elseif($this->extension($this->original) == 'gif')
			{
				header("Content-Type: image/gif");
				imagegif($this->handlethumb);
			}
			elseif($this->extension($this->original) == 'bmp')
			{
				header("Content-Type: image/bmp");
				imagewbmp($this->handlethumb);
			}
		}

		/*
			Distrugge le immagine per liberare le risorse
		*/
		public function closeImg()
		{
			imagedestroy($this->handleimg);
			imagedestroy($this->handlethumb);
		}

		/*
			Imposta la thumbnail come immagine sorgente,
			in questo modo potremo combinare la funzione crea con la funzione crop
		*/
		public function setThumbAsOriginal()
		{
			$this->oldoriginal = $this->handleimg;
			$this->handleimg = $this->handlethumb;
		}

		/*
			Resetta l'immagine originale
		*/
		public function resetOriginal()
		{
			$this->handleimg = $this->oldoriginal;
		}

		/*
			Estrae l'estensione da un file o un percorso
		*/
		private function extension($percorso)
		{
			if(eregi("[\|\\]", $percorso))
			{
				// da percorso
				$nome = $this->nomefile($percorso);

				$spezzo = explode(".", $nome);

				return strtolower(trim(array_pop($spezzo)));
			}
			else
			{
				//da file
				$spezzo = explode(".", $percorso);

				return strtolower(trim(array_pop($spezzo)));
			}
		}

		/*
			Estrae il nome del file da un percorso
		*/
		public function nomefile($path, $ext = true)
		{
			$diviso = spliti("[/|\\]", $path);

			if($ext)
			{
				return trim(array_pop($diviso));
			}
			else
			{
				$nome = explode(".", trim(array_pop($diviso)));

				array_pop($nome);

				return trim(implode(".", $nome));
			}
		}
	}

?>
