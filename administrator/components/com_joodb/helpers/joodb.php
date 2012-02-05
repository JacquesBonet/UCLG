<?php
/**
* @package		JooDatabase - http://joodb.feenders.de
* @copyright	Copyright (C) Computer - Daten - Netze : Feenders. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Dirk Hoeschen (hoeschen@feenders.de)
*/

class JooDBAdminHelper
{

	//  returns a list with all availiable functions
	function getFunctions($context) {
		$functions = array (
		"catalog" => array('loop','pagenav','pagecount','resultcount','nodata','limitbox','searchbox','alphabox','orderlink|FIELDNAME','readon','backbutton'),
		"single" => array('printbutton','backbutton'),
		"print" => array(),
		"form" => array('form|FIELDNAME','submitbutton','captcha','imageupload'),
		"general" => array('ifnot|FIELDNAME','ifis|FIELDNAME','endif','path2image','path2thumb') );
		return array_merge($functions[$context],$functions['general']);
	}

	//  returns a optionlist with all availiable functions
	function printTemplateFooter($editorid,$fieldlist,$context) {
		echo "<table class='paramlist admintable' style='width: auto;'><tr><td class='paramlist_key'>".JText::_( 'Insert field' )."</td><td class='paramlist_value'>";
		echo '<select name="ifld_'.$editorid.'" onChange="jInsertEditorText(this.options[this.selectedIndex].value,\''.$editorid.'\');this.selectedIndex=0;"><option>...</option>\n';
		foreach ($fieldlist as $field) {
			echo "<option>{joodb field|".$field->Field."}</option>\n";
		}
		echo '</select>';
		echo "</td><td class='paramlist_key'>".JText::_( 'Insert function' )."</td><td class='paramlist_value'>";
		echo '<select name="ifunc_'.$editorid.'" onChange="jInsertEditorText(this.options[this.selectedIndex].value,\''.$editorid.'\');this.selectedIndex=0;"><option>...</option>\n';
		$flist = JooDBAdminHelper::getFunctions($context);
		foreach ($flist as $f) {
			echo "<option>{joodb ".$f."}</option>\n";
		}
		echo '</select></td></tr></table>';
	}


	/**
	 * Get a list with Fields of a special type ...
	 * @param string $type
	 * @param object $fields
	 * @return array of fieldnames
	 */
	function selectFieldTypes($type, &$fields) {
		$fselect = array();
		foreach ($fields as $fname=>$fcell) {
			if ($type=="primary") {
				if (strtoupper($fcell->Key) == "PRI") $fselect[] = $fcell->Field;
			} else if ($type=="text") {
				if (strpos($fcell->Type,"text")!==false) $fselect[] = $fcell->Field;
			} else if ($type=="shorttext") {
				if (strpos($fcell->Type,"varchar")!==false) $fselect[] = $fcell->Field;
				if (strpos($fcell->Type,"text")!==false) $fselect[] = $fcell->Field;
			} else if ($type=="date") {
				if (strpos($fcell->Type,"date")!==false) $fselect[] = $fcell->Field;
				if (strpos($fcell->Type,"timestamp")!==false) $fselect[] = $fcell->Field;
			} else if ($type=="number") {
				if (strpos($fcell->Type,"int")!==false) $fselect[] = $fcell->Field;
			}
		}
		return $fselect;
	}

	/**
	 * Resize a image to smaller jpg ...
	 * @param string $source
	 * @param string $destination
	 * @param int $size_w
	 * @param int $size_h
	 * @param int $quality
	 * @param boolean $force_resize
	 * @param boolean $greyscale
	 */
	function resizeImage($source,$destination, $size_w=200, $size_h=200, $quality=80,$force_resize=false,$greyscale=false) {
   $imageinfo = getimagesize($source);
   $src_img = null;
   switch ($imageinfo[2]) {
   		case 1:
   			$src_img = imagecreatefromgif($source);
		break;
   		case 2:
   			$src_img = imagecreatefromjpeg($source);
		break;
   		case 3:
   			$src_img = imagecreatefrompng($source);
		break;
   		case 15:
   			$src_img = imagecreatefromwbmp($source);
		break;
		default:
			$src_img = false;
   }

   if ($src_img) {
        $src_width = $imageinfo[0];
        $src_height = $imageinfo[1];
        if($src_width>=$src_height) {
				$new_w = $size_w;
				$new_h = abs(($size_w/$src_width)*$src_height);
			    if ($new_h>=$size_h) {
				  $new_h = $size_h;
				  $new_w = abs(($size_h/$src_height)*$src_width);
			    }
			}
			else {
				$new_h = $size_h;
				$new_w = abs(($size_h/$src_height)*$src_width);
			    if ($new_w>=$size_w) {
				   $new_w = $size_w;
				   $new_h = abs(($size_w/$src_width)*$src_height);
			    }
			}
		// keep original size if file is to smal
        if (($src_height<=$size_h) && ($src_width<=$size_w) && ($force_resize==false)) {
            $new_h = $src_height;
            $new_w = $src_width;
         }
        $dst_img = imagecreatetruecolor($new_w,$new_h);
        // Creating the Canvas
        imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_w,$new_h,$imageinfo[0],$imageinfo[1]);

    	if ($greyscale) {
    	  $bwimage= imagecreate($new_w,$new_h);
     	  //Creates the 256 color palette
    	  for ($c=0;$c<256;$c++){ $palette[$c] = imagecolorallocate($bwimage,$c,$c,$c);}
		   //Reads the origonal colors pixel by pixel
		  for ($y=0;$y<$new_h;$y++) {
			for ($x=0;$x<$new_w;$x++) {
				$rgb = imagecolorat($dst_img,$x,$y);
				$r = ($rgb >> 16) & 0xFF; $g = ($rgb >> 8) & 0xFF; $b = $rgb & 0xFF;
				//This is where we actually use yiq to modify our rbg values, and then convert them to our grayscale palette
				$gs = (($r*0.299)+($g*0.587)+($b*0.114));
				imagesetpixel($bwimage,$x,$y,$palette[$gs]);
				}
			}
    	  imagejpeg($bwimage, $destination, $quality);
    	} else {
    	  imagejpeg($dst_img, $destination, $quality);
    	}
    	chmod($destination, 0665);
    	return true;
     }
    return false;
  }

}
