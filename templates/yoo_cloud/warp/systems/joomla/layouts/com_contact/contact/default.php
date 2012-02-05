<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

defined('_JEXEC') or die;

$cparams = JComponentHelper::getParams ('com_media');
?>

<div id="system">

<!-- 	<?php if ($this->params->get('show_page_title', 1) && !$this->contact->params->get('popup') && $this->params->get('page_title') != $this->contact->name) : ?>
	<h1 class="title"><?php echo $this->escape($this->params->get('page_title')); ?></h1>
 -->	<?php endif; ?>

	<?php if ($this->params->get('show_contact_list') && count($this->contacts) > 1) : ?>
	<div class="filter">
		<form action="<?php echo JRoute::_('index.php') ?>" method="post" name="selectForm" id="selectForm">
			<?php echo JText::_('Select Contact'); ?>:
			<?php echo JHTML::_('select.genericlist',  $this->contacts, 'contact_id', 'class="inputbox" onchange="this.form.submit()"', 'id', 'name', $this->contact->id);?>
			<input type="hidden" name="option" value="com_contact" />
		</form>
	</div>
	<?php endif; ?>

	<div class="item">
	
		<?php if ($this->contact->name && $this->contact->params->get('show_name')) : ?>
		<h1 class="title"><?php echo $this->contact->name; ?></h1>
		<?php endif; ?>
		
		<?php if ($this->contact->con_position && $this->contact->params->get('show_position')) : ?>
		<h2 class="subtitle"><?php echo $this->contact->con_position; ?></h2>
		<?php endif; ?>
	
		<?php if ($this->contact->image && $this->contact->params->get('show_image')) : ?>
		<?php echo JHTML::_('image', 'images/stories' . '/'.$this->contact->image, JText::_('Contact'), array('class' => 'align-right')); ?>
		<?php endif; ?>
	
		<?php echo $this->loadTemplate('address'); ?>
	
		<?php if ($this->contact->params->get('allow_vcard')) : ?>
		<p>
			<?php echo JText::_('Download information as a');?>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_contact&amp;task=vcard&amp;contact_id=<?php echo $this->contact->id; ?>&amp;format=raw&amp;tmpl=component"><?php echo JText::_('VCard');?></a>
		</p>
		<?php endif; ?>

		<?php if ($this->params->get('show_links')) : ?>
			<?php echo $this->loadTemplate('links'); ?>
		<?php endif; ?>

		<?php if ($this->params->get('show_articles')) : ?>
			<?php echo $this->loadTemplate('articles'); ?>
		<?php endif; ?>

		<?php if ($this->params->get('show_profile')) : ?>
			<?php echo $this->loadTemplate('profile'); ?>
		<?php endif; ?>

		
		<?php if ($this->contact->params->get('show_email_form') && ($this->contact->email_to || $this->contact->user_id))
				echo $this->loadTemplate('form'); ?>


	
		
	</div>
	
</div>
