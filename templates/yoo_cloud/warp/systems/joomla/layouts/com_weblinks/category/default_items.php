<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

?>

<script language="javascript" type="text/javascript">
	function tableOrdering( order, dir, task ) {
		var form = document.adminForm;
	
		form.filter_order.value 	= order;
		form.filter_order_Dir.value	= dir;
		document.adminForm.submit( task );
	}
</script>

<form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post" name="adminForm">

	<div class="filter">
	<?php
		echo JText::_('Display Num') .'&nbsp;';
		echo $this->pagination->getLimitBox();
	?>
	</div>
	
	<table class="zebra" border="0" cellspacing="0" cellpadding="0">
		<?php if ( $this->params->def( 'show_headings', 1 ) ) : ?>
		<thead>
			<tr>
				<th width="5%">#</th>
				
				<th class="text-left"><?php echo JHtml::_('grid.sort',  'COM_WEBLINKS_GRID_TITLE', 'title', $listDirn, $listOrder); ?></th>
				
				<?php if ($this->params->get('show_link_hits')) : ?>
				<th width="5%">
						<?php echo JHtml::_('grid.sort',  'JGLOBAL_HITS', 'hits', $listDirn, $listOrder); ?>
				</th>
				<?php endif; ?>
				
			</tr>
		</thead>
		<?php endif; ?>
		
		<tbody>
		
			<?php $itemcount = count($this->items); ?>
			<?php foreach ($this->items as $i => $item) : ?>
			<tr class="<?php if ($i % 2 == 1) { echo 'even'; } else { echo 'odd'; } ?>">
			
				<td class="text-right" width="5%"><?php echo $this->pagination->getRowOffset( $itemcount ); ?></td>
				
				<td><a href="<?php echo $item->link; ?>"><?php echo $this->escape($item->title); ?></a>
					<?php if ( $this->params->get( 'show_link_description' ) ) : ?>
					<br /><?php echo nl2br($item->description); ?>
					<?php endif; ?>
				</td>
				
				<?php if ( $this->params->get( 'show_link_hits' ) ) : ?>
				<td  class="text-center" width="5%"><?php echo $item->hits; ?></td>
				<?php endif; ?>
				
			</tr>
			<?php endforeach; ?>
		
		</tbody>
		
	</table>
	
	<?php echo $this->pagination->getPagesLinks(); ?>
	
	<input type="hidden" name="filter_order" value="<?php echo $this->escape($this->state->get('list.ordering')); ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<input type="hidden" name="viewcache" value="0" />
	
</form>