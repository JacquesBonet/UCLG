<?php
/**
 * @package Iyosis Maps for Joomla!
 * @author Remzi Degirmencioglu
 * @copyright (C) 2011 www.iyosis.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
JHTML ::_(' behavior. mootools');
?>

<div style="background-color: #FFFFFF;padding:20px;margin: 10px;border: 1px solid #CCCCCC;">
<table class="admintable">
	<tr>
		<td valign="top" colspan="2">
			<div id="cpanel">
				<div style="float:left;">
					<div class="icon">
						<a href="index.php?option=com_iyosismaps&view=maps">
							<?php echo JHTML::_('image.site',  'icon-48-maps.png', '/components/com_iyosismaps/media/images/'); ?>
							<span><?php echo JText::_('COM_IYOSISMAPS_MAPS'); ?></span>
						</a>
					</div>
				</div>
				<div style="float:left;">
					<div class="icon">
						<a href="index.php?option=com_iyosismaps&view=markers">
							<?php echo JHTML::_('image.site',  'icon-48-markers.png', '/components/com_iyosismaps/media/images/'); ?>
							<span><?php echo JText::_('COM_IYOSISMAPS_MARKERS'); ?></span>
						</a>
					</div>
				</div>
				<div style="float:left;">
					<div class="icon">
						<a href="index.php?option=com_iyosismaps&view=icons">
							<?php echo JHTML::_('image.site',  'icon-48-icons.png', '/components/com_iyosismaps/media/images/'); ?>
							<span><?php echo JText::_('COM_IYOSISMAPS_ICONS'); ?></span>
						</a>
					</div>
				</div>
				<div style="float:left;">
					<div class="icon">
						<a href="index.php?option=com_iyosismaps&view=polylines">
							<?php echo JHTML::_('image.site',  'icon-48-polylines.png', '/components/com_iyosismaps/media/images/'); ?>
							<span><?php echo JText::_('COM_IYOSISMAPS_POLYLINES'); ?></span>
						</a>
					</div>
				</div>
				<div style="float:left;">
					<div class="icon">
						<a href="index.php?option=com_iyosismaps&view=polygons">
							<?php echo JHTML::_('image.site',  'icon-48-polygons.png', '/components/com_iyosismaps/media/images/'); ?>
							<span><?php echo JText::_('COM_IYOSISMAPS_POLYGONS'); ?></span>
						</a>
					</div>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<table class="admintable">
				<tr>
					<td class="key">
						<?php echo JText::_( 'COM_IYOSISMAPS_CP_VERSION' );?>
					</td>
					<td>
						<?php echo JText::_( 'COM_IYOSISMAPS_VERSION_NUMBER' );?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_( 'COM_IYOSISMAPS_CP_IYOSISHOMEPAGE' );?>
					</td>
					<td>
						<a href="http://www.iyosis.com/" target="_blank">www.iyosis.com</a>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_( 'COM_IYOSISMAPS_CP_FORUM' );?>
					</td>
					<td>
						<a href="http://www.iyosis.com/forum" target="_blank">www.iyosis.com/forum</a>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_( 'COM_IYOSISMAPS_CP_COPYRIGHT' );?>
					</td>
					<td>
						© 2011 by Iyosis.com
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_( 'COM_IYOSISMAPS_CP_LICENSE' );?>
					</td>
					<td>
						<a href="http://www.gnu.org/licenses/gpl-3.0.html" target="_blank">GNU General Public License v3</a>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_( 'COM_IYOSISMAPS_MAPS' );?>
					</td>
					<td>
						Maps are created by Google Maps™
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>
<div class="footer" align="center">
	<a href="http://www.iyosis.com/" target="_blank"><?php echo JText::_( 'COM_IYOSISMAPS_FOOTER' ); ?></a>
</div>
<div class="clr"></div>
