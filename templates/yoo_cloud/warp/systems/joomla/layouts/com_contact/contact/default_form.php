<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

defined('_JEXEC') or die;
	
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
 if (isset($this->error)) : ?>
	<div class="contact-error">
		<?php echo $this->error; ?>
	</div>
<?php endif; ?>

<form class="submission box style" action="<?php echo JRoute::_( 'index.php' );?>" method="post" name="emailForm" id="emailForm">
	<fieldset>
		<legend><?php echo JText::_('COM_CONTACT_FORM_LABEL'); ?></legend>

		<div>
			<?php echo $this->form->getLabel('contact_name'); ?>
			<?php echo $this->form->getInput('contact_name'); ?>
		</div>

		<div>
			<?php echo $this->form->getLabel('contact_email'); ?>
			<?php echo $this->form->getInput('contact_email'); ?>
		</div>

		<div>
			<?php echo $this->form->getLabel('contact_subject'); ?>
			<?php echo $this->form->getInput('contact_subject'); ?>
		</div>

		<div>
			<?php echo $this->form->getLabel('contact_message'); ?>
			<?php echo $this->form->getInput('contact_message'); ?>
		</div>

		<?php if ($this->params->get('show_email_copy')) : ?>
		<div>
			<?php echo $this->form->getLabel('contact_email_copy'); ?>
			<?php echo $this->form->getInput('contact_email_copy'); ?>
		</div>
		<?php endif; ?>
	</fieldset>
	
	<div>
		<button class="button validate" type="submit"><?php echo JText::_('Send'); ?></button>
	</div>

	<input type="hidden" name="option" value="com_contact" />
	<input type="hidden" name="view" value="contact" />
	<input type="hidden" name="id" value="<?php echo $this->contact->slug; ?>" />
	<input type="hidden" name="task" value="contact.submit" />
	<?php echo JHTML::_( 'form.token' ); ?>

</form>