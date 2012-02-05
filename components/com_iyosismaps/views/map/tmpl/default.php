<?php
/**
 * @package Iyosis Maps for Joomla!
 * @author Remzi Degirmencioglu
 * @copyright (C) 2011 www.iyosis.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<div id="iyosismaps">

	<?php if($this->map->params->get('showtitle', '1')) { ?>
		<h2 class="componentheading"><?php echo $this->map->title; ?></h2>
	<?php } ?>

	<?php if($this->map->contentbefore) echo $this->map->contentbefore; ?>

	<?php
		if ($this->map->params->get('mapalign', '2')==0) $mapalign = false;
		elseif ($this->map->params->get('mapalign', '2')==1) $mapalign = "margin-left:auto;margin-right:0";
		else $mapalign = "margin:0 auto";
	?>

	<div id="map_canvas" style="width:<?php echo $this->map->width ? $this->map->width : '500'; ?>px;height:<?php echo $this->map->height ? $this->map->height : '300'; ?>px;<?php if($mapalign) echo $mapalign; ?>"></div>

	<?php if($this->map->contentafter) echo $this->map->contentafter; ?>

	<?php if($this->map->params->get('footerlink', '1')) { ?>
		<div style="text-align:right;clear:both;margin-top:10px;margin-bottom:10px;font-size:9px;">
			<a href="http://www.iyosis.com" style="text-decoration:none;" target="_blank" title="<?php echo JText::_( 'COM_IYOSISMAPS_FOOTER' );?>">
				<?php echo JText::_( 'COM_IYOSISMAPS_FOOTER' );?>
			</a>
		</div>
	<?php } ?>

</div>
