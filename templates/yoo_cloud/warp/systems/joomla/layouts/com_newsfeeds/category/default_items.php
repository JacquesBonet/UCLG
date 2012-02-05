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

<form action="<?php echo JRoute::_('index.php?view=category&id='.$this->category->slug); ?>" method="post" name="adminForm">

	<?php if ($this->params->get('show_limit')) : ?>
	<div class="filter">
		<?php
			echo JText::_('Display Num') .'&nbsp;';
			echo $this->pagination->getLimitBox();
		?>
	</div>
	<?php endif; ?>
	
	<table class="zebra" border="0" cellspacing="0" cellpadding="0">
		<?php if ( $this->params->get( 'show_headings' ) ) : ?>
		<thead>
			<tr>
				<th align="right" width="5%"><?php echo JText::_('Num'); ?></th>
				
				<th align="left"><?php echo JText::_( 'Feed Name' ); ?></th>
				
				<?php if ( $this->params->get( 'show_articles' ) ) : ?>
				<th align="center" width="5%"><?php echo JText::_( 'Articles' ); ?></th>
				<?php endif; ?>
				
			</tr>
		</thead>
		<?php endif; ?>
		
		<tbody>
		
			<?php foreach ($this->items as $i => $item) : ?>
			<tr class="<?php if ($item->odd) { echo 'even'; } else { echo 'odd'; } ?>">
			
				<td align="right"><?php echo $i + 1; ?></td>
				
				<td><a href="<?php echo JRoute::_(NewsFeedsHelperRoute::getNewsfeedRoute($item->slug, $item->catid)); ?>" class="category<?php echo $this->params->get( 'pageclass_sfx' ); ?>"><?php echo $item->name; ?></a></td>
				
				<?php if ( $this->params->get( 'show_articles' ) ) : ?>
				<td align="center"><?php echo $item->numarticles; ?></td>
				<?php endif; ?>
				
			</tr>
			<?php endforeach; ?>
		
		</tbody>
	
	</table>
	
	<?php echo $this->pagination->getPagesLinks(); ?>

</form>