<?php
/**
* DirectPHP plugin
* allows direct embedding of PHP commands
* right inside Joomla content page for dynamic contents
* Author: kksou
* Copyright (C) 2006-2011. kksou.com. All Rights Reserved
* License: GNU/GPL http://www.gnu.org/copyleft/gpl.html
* Website: http://www.kksou.com/php-gtk2
* v1.5 March 26, 2008
* v1.5.3 May 12, 2008
* v1.5.4 June 21, 2008
* v1.5.6 Apr 8, 2009 added using_no_editor in parameter
* v1.6 Feb 21, 2011 support for Joomla 1.6
* v1.7 Oct 10, 2011 support for Joomla 1.7
*/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.event.plugin' );

class plgContentDirectPHP extends JPlugin {

	function plgContentDirectPHP( &$subject, $params ) {
		parent::__construct( $subject, $params );
 	}

	function onContentPrepare( $context, &$article, &$params, $limitstart=0 ) {
		global $mainframe;
		global $enable_command_block, $block_list;
		#$plugin =& JPluginHelper::getPlugin('content', 'DirectPHP');
		$pluginParams = $this->params;
		$using_no_editor = $pluginParams->get('using_no_editor');
		$enable_command_block = $pluginParams->get('enable_command_block');
		$block_list = $pluginParams->get('block_list');

		$block_list = preg_replace('/\s*/s', '', $block_list);
		$block_list = explode(',', $block_list);

		$php_start = "<?php";
		$php_end = "?>";
		$contents = $article->text;
		$contents = $this->fix_str($contents);
		$output = "";
		$regexp = '/(.*?)'.$this->fix_reg($php_start).'\s+(.*?)'.$this->fix_reg($php_end).'(.*)/s';
		$found = preg_match($regexp, $contents, $matches);
		while ($found) {
			$output .= $matches[1];
			$phpcode = $matches[2];
			#$phpcode2 = $this->fix_str2($phpcode);
			global $errmsg;
			if ($this->check_php($phpcode)) {
				ob_start();
				if ($using_no_editor) {
					eval($phpcode);
				} else {
					eval($this->fix_str2($phpcode));
				}
				$output .= ob_get_contents();
				ob_end_clean();
			} else {
				$output .= "The following command is not allowed: <b>$errmsg</b>";
			}
			$contents = $matches[3];
			$found = preg_match($regexp, $contents, $matches);
		}
		$output .= $contents;
		$article->text = $output;
		return true;
	}

	function fix_str($str) {
		$str = str_replace('{?php', '<?php', $str);
		$str = str_replace('?}', '?>', $str);
		$str = preg_replace(array('%&lt;\?php(\s|&nbsp;|<br\s/>|<br>|<p>|</p>)%s', '/\?&gt;/s', '/-&gt;/'), array('<?php ', '?>', '->'), $str);
		return $str;
	}

	function fix_str2($str) {
		$str = str_replace('<br>', "\n", $str);
		$str = str_replace('<br />', "\n", $str);
		$str = str_replace('<p>', "\n", $str);
		$str = str_replace('</p>', "\n", $str);
		$str = str_replace('&#39;', "'", $str);
		$str = str_replace('&quot;', '"', $str);
		$str = str_replace('&lt;', '<', $str);
		$str = str_replace('&gt;', '>', $str);
		$str = str_replace('&amp;', '&', $str);
		$str = str_replace('&nbsp;', ' ', $str);
		$str = str_replace('&#160;', "\t", $str);
		$str = str_replace(chr(hexdec('C2')).chr(hexdec('A0')), '', $str);
		$str = str_replace(html_entity_decode("&Acirc;&nbsp;"), '', $str);
		return $str;
	}

	function fix_reg($str) {
		$str = str_replace('?', '\?', $str);
		$str = str_replace('{', '\{', $str);
		$str = str_replace('}', '\}', $str);
		return $str;
	}

	function check_php($code) {
		global $enable_command_block, $block_list, $errmsg;
		$status = 1;
		if (!$enable_command_block) return $status;
		$function_list = array();
		if (preg_match_all('/([a-zA-Z0-9_]+)\s*[(|"|\']/s', $code, $matches)) {
			$function_list = $matches[1];
		}

		if (preg_match('/`(.*?)`/s', $code)) {
			$status = 0;
			$errmsg = 'backticks (``)';
			return $status;
		}
		if (preg_match('/\$database\s*->\s*([a-zA-Z0-9_]+)\s*[(|"|\']/s', $code, $matches)) {
			$status = 0;
			$errmsg = 'database->'.$matches[1];
			return $status;
		}
		foreach($function_list as $command) {
			if (in_array($command, $block_list)) {
				$status = 0;
				$errmsg = $command;
				break;
			}
		}
		return $status;
	}

}

?>
