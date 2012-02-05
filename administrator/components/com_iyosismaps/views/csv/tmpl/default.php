<?php
/**
 * @package Iyosis Maps for Joomla!
 * @author Remzi Degirmencioglu
 * @copyright (C) 2011 www.iyosis.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<form action="<?php echo JRoute::_('index.php?option=com_iyosismaps&view=csv'); ?>" method="post" name="adminForm" id="csv-form" enctype="multipart/form-data">
	<?php if ($this->task == 'importMarker') { ?>
	<div class="width-100">
		<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_IYOSISMAPS_FIELD_IMPORT_DATA_FROM_FILE' ); ?></legend>
			<label><?php echo JText::_('Upload a file '); ?></label>
			<input type="file" name="file" id="file" size="65" />
		</fieldset>
	</div>
	<div>
		<input type="hidden" name="task" value="csv.importMarker2" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	<?php } else { ?>
	<div class="width-100">
		<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_IYOSISMAPS_FIELD_IMPORT_DATA_FROM_FILE' ); ?></legend>
			<input type="file" name="file" id="file" size="57" />
			<input type="submit" value="<?php echo JText::_('Upload'); ?>">
		</fieldset>
	</div>
	<div>
		<input type="hidden" name="task" value="csv.importData" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	<?php } ?>
</form>
<div class="footer" align="center">
	<a href="http://www.iyosis.com/" target="_blank"><?php echo JText::_( 'COM_IYOSISMAPS_FOOTER' ); ?></a>
</div>
