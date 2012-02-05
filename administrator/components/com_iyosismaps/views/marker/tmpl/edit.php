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

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
function initialize() {
	var lat, lng, zoom;
		lat = <?php echo $this->item->latitude ? $this->item->latitude : '20'; ?>;
		lng = <?php echo $this->item->longitude ? $this->item->longitude : '-40'; ?>;
		zoom = 2;
	var latlng1 = new google.maps.LatLng(lat,lng);
	var myOptions = {
		zoom: zoom,
		center: latlng1,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	var marker = new google.maps.Marker({
		position: latlng1, 
		map: map,
		draggable: true,
		title: 'Drag me!'
	});
	google.maps.event.addListener(marker, "drag", function() {
		document.adminForm.jform_latitude.value = marker.getPosition().lat();
		document.adminForm.jform_longitude.value = marker.getPosition().lng();
	});
}
function showIcon() {
	document.getElementById("icon").innerHTML='<img src="'+document.adminForm.jform_icon.value+'">';
}
window.onload = initialize;
</script>

<?php
$editor = JFactory::getEditor();
jimport('joomla.html.pane');
$pane = JPane::getInstance('tabs', array('startOffset'=>0));
?>

<form action="<?php echo JRoute::_('index.php?option=com_iyosismaps&view=marker&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="marker-form" class="form-validate">
	<div class="width-100">
		<fieldset class="adminform">
		<?php
		echo $pane->startPane( 'pane' );
		echo $pane->startPanel( JText::_( 'COM_IYOSISMAPS_MARKER' ), 'panel1' );
		?>
		<div class="width-80">
			<ul class="adminformlist">
			<?php foreach($this->form->getFieldset() as $field):
				if (strpos($field->label, "jform_longitude-lbl")) {
					echo "<li>".$field->label.$field->input."</li>";
					echo "<label>".JText::_( 'COM_IYOSISMAPS_FIELD_REFERENCEMAP_DESC' )."</label>";
					echo "<div id=\"map_canvas\" style=\"width:800px;height:500px;clear:left;\"></div>";
					echo "</ul></div>";
					echo $pane->endPanel();
					echo $pane->startPanel( JText::_( 'COM_IYOSISMAPS_EDIT_TAB_INFOWINDOW' ), 'panel2' );
					echo "<div class=\"width-60\"><ul class=\"adminformlist\">";
				} elseif (strpos($field->label, "jform_description-lbl")) {
					echo "<li>".$field->label."<div class=\"clr\"></div>".$field->input."</li>";
					echo "<div class=\"clr\"></div><br/>";
				} else {
					echo "<li>".$field->label.$field->input."</li>";
				}
			endforeach; ?>
			</ul>
		</div>
		<?php
		echo $pane->endPanel();
		echo $pane->endPane();
		?>
		</fieldset>
	</div>
	<div>
		<input type="hidden" name="task" value="marker.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<div class="footer" align="center">
	<a href="http://www.iyosis.com/" target="_blank"><?php echo JText::_( 'COM_IYOSISMAPS_FOOTER' ); ?></a>
</div>
