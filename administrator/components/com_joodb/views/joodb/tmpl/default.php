<?php

// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
JHTML::_('behavior.tooltip');

?>

<form action="index.php" method="post" name="adminForm">
<div id="editcell">
			<table>
				<tr>
					<td width="100%">
						<?php echo JText::_( 'Filter' ); ?>:
						<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_( 'Filter by title or enter article ID' );?>"/>
						<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
						<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
				</tr>
			</table>
	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="30" align = "right"><?php echo JHTML::_('grid.sort',   'ID', 'c.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="30"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th class="title"  width="30"><?php echo JText::_('Edit Data'); ?></th>
			<th class="title"><?php echo JHTML::_('grid.sort',   'Name', 'c.name', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="title"><?php echo JHTML::_('grid.sort',   'TABLE IN DATABASE', 'c.table', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th width="5%"><?php  echo JText::_('Menu item'); ?></th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'Published', 'c.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="title" width="100" ><?php echo JHTML::_('grid.sort',   'Date', 'c.published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="8">
				<?php echo $this->page->getListFooter(); ?>
			</td>
	</tfoot>
	<tbody>	<?php
	$dbimage = "components/com_joodb/assets/images/database.png";
	$menuimage = "components/com_joodb/assets/images/add-menu.png";
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];

		$published 	= JHTML::_('grid.published', $row,  $i);
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
		$termLink	= JRoute::_("index.php?option=com_joodb&task=edit&view=joodbentry&cid[]=$row->id");
		$editLink	= JRoute::_("index.php?option=com_joodb&task=listdata&view=listdata&joodbid=$row->id");
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td align = "right"><?php echo $row->id; ?></td>
			<td align = "center"><?php echo $checked; ?></td>
			<td align="center">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit data of' )." <b>".$row->table."</b>";?>">
					<a href="<?php echo $editLink; ?>">
						<img src="<?php echo $dbimage ?>" border="0" />
					</a>
				</span>
			</td>
			<td><a href='<?php echo $termLink; ?>'><?php  echo $row->name; ?></a></td>
			<td><?php echo $row->table; ?></td>
			<td align = "center">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Create menu item' )." <b>".$row->name."</b>";?>">
					<a class="modal" href="index.php?option=com_joodb&tmpl=component&view=addmenuitem&cid[]=<?php echo $row->id ?>" rel="{handler: 'iframe', size: {x: 480, y: 120}}">
						<img src="<?php echo $menuimage ?>" border="0" />
					</a>
				</span>
			</td>
			<td align="center">
				<?php echo $published;?>
			</td>
			<td><?php echo JHTML::_('date', $row->created, JText::_('DATE_FORMAT_LC3')); ?></td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
	</table>
</div>

<input type="hidden" name="option" value="com_joodb" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="joodb" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' );?>
</form>
