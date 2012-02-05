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

	<!-- our 1.5 version, seems to work though -->
	<?php if ($this->params->get('show_page_title', 1)) : ?>
	<h1 class="title"><?php echo $this->escape($this->params->get('page_title')); ?></h1>
	<?php endif; ?>

	<?php if ((($this->params->def('image', -1) != -1) && isset($this->image)) || ($this->params->get('show_comp_description', 1) && $this->params->get('comp_description'))) : ?>
	<div class="description">
		<?php if ($this->params->get('image') && isset($this->image)) echo $this->image; ?>
		<?php if ($this->params->get('show_comp_description', 1)) echo $this->params->get('comp_description'); ?>
	</div>
	<?php endif; ?>



	<?php if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0) :
	?>
	<ul>
	<?php foreach($this->items[$this->parent->id] as $id => $item) : ?>
		<?php
		if($this->params->get('show_empty_categories_cat') || $item->numitems || count($item->getChildren())) : ?>
		<li>
			<a href="<?php echo JRoute::_(WeblinksHelperRoute::getCategoryRoute($item->id));?>">
				<?php echo $this->escape($item->title); ?>
			</a>
			
			<?php if ($this->params->get('show_cat_num_links_cat') == 1) :?>
				<small>(<?php echo $item->numitems; ?>)</small>
			<?php endif; ?>

		</li>
		<?php endif; ?>
	<?php endforeach; ?>
	</ul>
	<?php endif; ?>
	
</div>