<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

defined('_JEXEC') or die;

	// Determine if contact's address shall be displayed
	$params = JComponentHelper::getParams('com_contact');
	$adress_check = false;
	if ($params->get('show_street_address') || $params->get('show_suburb') || $params->get('show_state') || $params->get('show_postcode') || $params->get('show_country')) {
				$address_check = true;
	}
?>

<?php if ( $address_check &&  ($this->contact->address || $this->contact->suburb  || $this->contact->state || $this->contact->country || $this->contact->postcode )) : ?>
<div class="address">
	<h3><?php echo JText::_('Address'); ?></h3>
	<ul class="blank">
	
		<?php if ($this->contact->address && $this->contact->params->get('show_street_address')) : ?>
		<li class="street"><?php echo nl2br($this->escape($this->contact->address)); ?></li>
		<?php endif; ?>
		
		<?php if ($this->contact->suburb && $this->contact->params->get('show_suburb')) : ?>
		<li class="suburb"><?php echo $this->escape($this->contact->suburb); ?></li>
		<?php endif; ?>
		
		<?php if ($this->contact->state && $this->contact->params->get('show_state')) : ?>
		<li class="state"><?php echo $this->escape($this->contact->state); ?></li>
		<?php endif; ?>
		
		<?php if ($this->contact->postcode && $this->contact->params->get('show_postcode')) : ?>
		<li class="postcode"><?php echo $this->escape($this->contact->postcode); ?></li>
		<?php endif; ?>
		
		<?php if ($this->contact->country && $this->contact->params->get('show_country')) : ?>
		<li class="country"><?php echo $this->escape($this->contact->country); ?></li>
		<?php endif; ?>
		
	</ul>
</div>
<?php endif; ?>

<?php if ( ($this->contact->email_to && $this->contact->params->get('show_email')) || 
			($this->contact->telephone && $this->contact->params->get('show_telephone')) || 
			($this->contact->fax && $this->contact->params->get('show_fax')) || 
			($this->contact->mobile && $this->contact->params->get('show_mobile')) || 
			($this->contact->webpage && $this->contact->params->get('show_webpage')) || 
			($this->contact->misc && $this->contact->params->get('show_misc'))) : ?>
<div class="contact">
	<h3><?php echo JText::_('Contact'); ?></h3>
	<ul class="blank">
	
		<?php if ($this->contact->email_to && $this->contact->params->get('show_email')) : ?>
		<li><?php echo $this->contact->email_to; ?></li>
		<?php endif; ?>
	
		<?php if ($this->contact->telephone && $this->contact->params->get('show_telephone')) : ?>
		<li><?php echo nl2br($this->contact->telephone); ?></li>
		<?php endif; ?>
		
		<?php if ($this->contact->fax && $this->contact->params->get('show_fax')) : ?>
		<li><?php echo nl2br($this->escape($this->contact->fax)); ?></li>
		<?php endif; ?>
		
		<?php if ($this->contact->mobile && $this->contact->params->get('show_mobile')) :?>
		<li><?php echo nl2br($this->escape($this->contact->mobile)); ?></li>
		<?php endif; ?>
		
		<?php if ($this->contact->webpage && $this->contact->params->get('show_webpage')) : ?>
		<li><a href="<?php echo $this->contact->webpage; ?>" target="_blank"><?php echo $this->escape($this->contact->webpage); ?></a></li>
		<?php endif; ?>
	
		<?php if ($this->contact->misc && $this->contact->params->get('show_misc')) : ?>
		<li><?php echo nl2br($this->contact->misc); ?></li>
		<?php endif; ?>
	
	</ul>
</div>
<?php endif; ?>