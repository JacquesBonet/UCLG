<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div id="system">
	
	<?php if ($this->params->get('show_page_title', 1)) : ?>
	<h1 class="title"><?php echo $this->escape($this->params->get('page_title')); ?></h1>
	<?php endif; ?>

	<?php if ((($this->params->get('image') != -1) && isset($this->image)) || ($this->params->get('show_comp_description') && $this->params->get('comp_description'))) : ?>
	<div class="description">
		<?php if (($this->params->get('image') != -1) && isset($this->image)) echo $this->image; ?>
		<?php if ($this->params->get('show_comp_description')) echo $this->params->get('comp_description'); ?>
	</div>
	<?php endif; ?>


	<?php if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0) : ?>
		<ul>
		<?php foreach($this->items[$this->parent->id] as $id => $item) : ?>
			<?php if($this->params->get('show_empty_categories_cat') || $item->numitems || count($item->getChildren())) : ?>
			<li>
				<span class="item-title"><a href="<?php echo JRoute::_(NewsfeedsHelperRoute::getCategoryRoute($item->id));?>">
					<?php echo $this->escape($item->title); ?></a>
				</span>
				
				<?php if ($this->params->get('show_cat_items_cat') == 1) :?>
					<small>(<?php echo $item->numitems; ?>)</small>
				<?php endif; ?>

				<?php if ($this->params->get('show_subcat_desc_cat') == 1) :?>
					<?php if ($item->description) : ?>
						<br/><?php echo JHtml::_('content.prepare', $item->description); ?>
					<?php endif; ?>
		        <?php endif; ?>

			</li>
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>

</div>