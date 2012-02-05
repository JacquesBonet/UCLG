<?php
/**
 * @package Iyosis Maps for Joomla!
 * @author Remzi Degirmencioglu
 * @copyright (C) 2011 www.iyosis.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<form action="<?php echo JRoute::_('index.php?option=com_iyosismaps&view=polylines'); ?>" method="post" name="adminForm" id="adminForm">
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="3%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<th>
				<?php echo JText::_( 'JGLOBAL_TITLE' ); ?>
			</th>
			<th width="7%">
				<?php echo JText::_( 'JSTATUS' ); ?>
			</th>
			<th width="15%">
				<?php echo JText::_( 'COM_IYOSISMAPS_MAP' ); ?>
			</th>
			<th width="15%">
				<?php echo JText::_( 'JCATEGORY' ); ?>
			</th>
			<th width="5%">
				<?php echo JText::_( 'JGRID_HEADING_ID' ); ?>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($this->items as $i => $item): ?>
		<tr class="row<?php echo $i % 2; ?>">
			<td>
				<?php echo JHtml::_('grid.id', $i, $item->id); ?>
			</td>
			<td>
				<a href="<?php echo JRoute::_('index.php?option=com_iyosismaps&task=polyline.edit&id=' . $item->id); ?>">
					<?php echo $item->title; ?>
				</a>
			</td>
			<td class="center">
				<?php echo JHtml::_('jgrid.published', $item->published, $i, 'polylines.'); ?>
			</td>
			<td class="center">
				<?php echo $item->maptitle; ?>
			</td>
			<td class="center">
				<?php echo $item->category ? $item->category : '-'; ?>
			</td>
			<td class="center">
				<?php echo $item->id; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>
	</table>
</div>
<div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>
</div>
</form>
<div class="footer" align="center">
	<br /><a href="http://www.iyosis.com/" target="_blank"><?php echo JText::_( 'COM_IYOSISMAPS_FOOTER' ); ?></a>
</div>
