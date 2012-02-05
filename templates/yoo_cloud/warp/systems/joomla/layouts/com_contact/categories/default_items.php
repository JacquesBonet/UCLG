<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// no direct access
defined('_JEXEC') or die;
?>

<?php if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0) : ?>
<ul class="zebra">

	<?php foreach($this->items[$this->parent->id] as $id => $item) : ?>
	<?php if($this->params->get('show_empty_categories_cat') || $item->numitems || count($item->getChildren())) : ?>
	<li>

		<span class="item-title">
			<a href="<?php echo JRoute::_(ContactHelperRoute::getCategoryRoute($item->id));?>"><?php echo $this->escape($item->title); ?></a>
		</span>

		<?php if ($this->params->get('show_subcat_desc_cat') == 1) :?>
			<?php if ($item->description) : ?>
			<div class="category-desc">
				<?php echo $item->description; ?>
			</div>
			<?php endif; ?>
	    <?php endif; ?>


		<?php if ($this->params->get('show_cat_items_cat') == 1) :?>
	    <div>
			<?php echo JText::_('COM_CONTACT_COUNT'); ?> 
			<?php echo $item->numitems; ?>
		</div>
		<?php endif; ?>
		
	</li>
	<?php endif; ?>
	<?php endforeach; ?>

</ul>
<?php endif; ?>