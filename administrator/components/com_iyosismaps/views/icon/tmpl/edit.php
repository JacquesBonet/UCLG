<?php
/**
 * @package Iyosis Maps for Joomla!
 * @author Remzi Degirmencioglu
 * @copyright (C) 2011 www.iyosis.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<?php
$editor = JFactory::getEditor();
jimport('joomla.html.pane');
$pane = JPane::getInstance('tabs', array('startOffset'=>0)); 
?>

<script type="text/javascript">
function showIcon() {
	document.getElementById("icon").innerHTML='<img src="'+document.adminForm.jform_icon.value+'">';
}
function showShadow() {
	document.getElementById("shadow").innerHTML='<img src="'+document.adminForm.jform_shadow.value+'">';
}
function firstLoad() {
	showIcon();
	showShadow();
}
window.onload = firstLoad;
</script>

<form action="<?php echo JRoute::_('index.php?option=com_iyosismaps&view=icon&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="icon-form" class="form-validate">
	<div class="width-100">
		<fieldset class="adminform">
		<?php
		echo $pane->startPane( 'pane' );
		echo $pane->startPanel( JText::_( 'COM_IYOSISMAPS_ICON' ), 'panel1' );
		?>
		<div id="icon"></div> <div id="shadow"></div>
		<div class="width-80">
			<ul class="adminformlist">
			<?php foreach($this->form->getFieldset() as $field):
				if (strpos($field->label, "jform_icon-lbl")) {
					$iconInput = str_replace("id=\"jform_icon\"", "id=\"jform_icon\" onchange=\"showIcon()\"", $field->input);
					echo "<li>".$field->label.$iconInput."</li>";
				} elseif (strpos($field->label, "jform_shadow-lbl")) {
					$shadowInput = str_replace("id=\"jform_shadow\"", "id=\"jform_shadow\" onchange=\"showShadow()\"", $field->input);
					echo "<li>".$field->label.$shadowInput."</li>";
				} else {
					echo "<li>".$field->label.$field->input."</li>";
				}
			endforeach; ?>
			</ul>
			<div class="clr"></div><br/>
		</div>
		<?php
		echo $pane->endPanel();
		echo $pane->endPane();
		?>
		</fieldset>
	</div>
	<div>
		<input type="hidden" name="task" value="icon.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<div class="footer" align="center">
	<a href="http://www.iyosis.com/" target="_blank"><?php echo JText::_( 'COM_IYOSISMAPS_FOOTER' ); ?></a>
</div>
