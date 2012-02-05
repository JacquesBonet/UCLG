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
$params = $this->form->getFieldsets('params');
?>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
function initialize() {
	var lat, lng, zoom;
		lat = <?php echo $this->item->latitude ? $this->item->latitude : '20'; ?>;
		lng = <?php echo $this->item->longitude ? $this->item->longitude : '-40'; ?>;
		zoom = <?php echo $this->item->zoomlevel ? $this->item->zoomlevel : '2'; ?>;
	var latlng1 = new google.maps.LatLng(lat,lng);
	var myOptions = {
		zoom: zoom,
		center: latlng1,
		mapTypeId: google.maps.MapTypeId.<?php echo $this->item->maptype ? $this->item->maptype : 'ROADMAP'; ?>
	};
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	var marker = new google.maps.Marker({
		position: latlng1, 
		map: map,
		draggable: true,
		title: 'Drag me!'
	});
	google.maps.event.addListener(map, "bounds_changed", function() {
		document.adminForm.jform_zoomlevel.value = map.getZoom();
	});
	google.maps.event.addListener(marker, "drag", function() {
		document.adminForm.jform_latitude.value = marker.getPosition().lat();
		document.adminForm.jform_longitude.value = marker.getPosition().lng();
	});
}
window.onload = initialize;
</script>

<?php
$editor = JFactory::getEditor();
jimport('joomla.html.pane');
$pane = JPane::getInstance('tabs', array('startOffset'=>0));
?>

<form action="<?php echo JRoute::_('index.php?option=com_iyosismaps&view=map&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="map-form" class="form-validate">
	<div class="width-30 fltrt">
		<?php echo JHtml::_('sliders.start', 'map-slider'); ?>
		<?php foreach ($params as $name => $fieldset): ?>
			<?php echo JHtml::_('sliders.panel', JText::_($fieldset->label), $name.'-params');?>
			<?php if (isset($fieldset->description) && trim($fieldset->description)): ?>
				<p class="tip"><?php echo $this->escape(JText::_($fieldset->description));?></p>
			<?php endif;?>
			<fieldset class="panelform">
				<ul class="adminformlist">
				<?php foreach ($this->form->getFieldset($name) as $field) : ?>
					<li><?php echo $field->label; ?><?php echo $field->input; ?></li>
				<?php endforeach; ?>
				</ul>
			</fieldset>
		<?php endforeach; ?>
		<?php echo JHtml::_('sliders.end'); ?>
	</div>

	<div class="width-70">
		<fieldset class="adminform">
		<?php
		echo $pane->startPane( 'pane' );
		echo $pane->startPanel( JText::_( 'COM_IYOSISMAPS_MAP' ), 'panel1' );
		?>
		<div>
			<ul class="adminformlist">
			<li><?php echo $this->form->getLabel('title'); ?>
			<?php echo $this->form->getInput('title'); ?></li>

			<li><?php echo $this->form->getLabel('published'); ?>
			<?php echo $this->form->getInput('published'); ?></li>

			<li><?php echo $this->form->getLabel('maptype'); ?>
			<?php echo $this->form->getInput('maptype'); ?></li>

			<li><?php echo $this->form->getLabel('width'); ?>
			<?php echo $this->form->getInput('width'); ?></li>

			<li><?php echo $this->form->getLabel('height'); ?>
			<?php echo $this->form->getInput('height'); ?></li>

			<li><?php echo $this->form->getLabel('latitude'); ?>
			<?php echo $this->form->getInput('latitude'); ?></li>

			<li><?php echo $this->form->getLabel('longitude'); ?>
			<?php echo $this->form->getInput('longitude'); ?></li>

			<li><?php echo $this->form->getLabel('zoomlevel'); ?>
			<?php echo $this->form->getInput('zoomlevel'); ?></li>
			</ul>

			<label><?php echo JText::_( 'COM_IYOSISMAPS_FIELD_REFERENCEMAP_DESC' ) ?></label>
			<div id="map_canvas" style="width:100%;height:500px;clear:left;"></div>
			<div class="clr"></div>
		</div>
		<?php
		echo $pane->endPanel();
		echo $pane->startPanel( JText::_( 'COM_IYOSISMAPS_EDIT_TAB_CONTROLS' ), 'panel2' );
		?>
		<div>
			<ul class="adminformlist">
			<li><?php echo $this->form->getLabel('zoomcontrol'); ?>
			<?php echo $this->form->getInput('zoomcontrol'); ?></li>

			<li><?php echo $this->form->getLabel('zoomcontrolstyle'); ?>
			<?php echo $this->form->getInput('zoomcontrolstyle'); ?></li>

			<li><?php echo $this->form->getLabel('maxzoom'); ?>
			<?php echo $this->form->getInput('maxzoom'); ?></li>

			<li><?php echo $this->form->getLabel('minzoom'); ?>
			<?php echo $this->form->getInput('minzoom'); ?></li>

			<li><?php echo $this->form->getLabel('maptypecontrol'); ?>
			<?php echo $this->form->getInput('maptypecontrol'); ?></li>

			<li><?php echo $this->form->getLabel('maptypecontrolstyle'); ?>
			<?php echo $this->form->getInput('maptypecontrolstyle'); ?></li>

			<li><?php echo $this->form->getLabel('pancontrol'); ?>
			<?php echo $this->form->getInput('pancontrol'); ?></li>

			<li><?php echo $this->form->getLabel('scalecontrol'); ?>
			<?php echo $this->form->getInput('scalecontrol'); ?></li>

			<li><?php echo $this->form->getLabel('streetviewcontrol'); ?>
			<?php echo $this->form->getInput('streetviewcontrol'); ?></li>

			<li><?php echo $this->form->getLabel('overviewmapcontrol'); ?>
			<?php echo $this->form->getInput('overviewmapcontrol'); ?></li>

			<li><?php echo $this->form->getLabel('scrollwheel'); ?>
			<?php echo $this->form->getInput('scrollwheel'); ?></li>

			<li><?php echo $this->form->getLabel('disabledoubleclickzoom'); ?>
			<?php echo $this->form->getInput('disabledoubleclickzoom'); ?></li>

			<li><?php echo $this->form->getLabel('draggable'); ?>
			<?php echo $this->form->getInput('draggable'); ?></li>
			</ul>
			<div class="clr"></div>
		</div>
		<?php
		echo $pane->endPanel();
		echo $pane->startPanel( JText::_( 'COM_IYOSISMAPS_EDIT_TAB_CUSTOM_CONTENT' ), 'panel3' );
		?>
		<div>
			<?php echo $this->form->getLabel('contentbefore'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getInput('contentbefore'); ?>
			<br />
			<?php echo $this->form->getLabel('contentafter'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getInput('contentafter'); ?>
			<div class="clr"></div>
		</div>
		<?php
		echo $pane->endPanel();
		echo $pane->startPanel( JText::_( 'COM_IYOSISMAPS_EDIT_TAB_CENTERMARKER' ), 'panel4' );
		?>
		<div>
			<ul class="adminformlist">
			<li><?php echo $this->form->getLabel('centermarker'); ?>
			<?php echo $this->form->getInput('centermarker'); ?></li>

			<li><?php echo $this->form->getLabel('iconid'); ?>
			<?php echo $this->form->getInput('iconid'); ?></li>

			<li><?php echo $this->form->getLabel('infowindow'); ?>
			<?php echo $this->form->getInput('infowindow'); ?></li>
			</ul>

			<?php echo $this->form->getLabel('description'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getInput('description'); ?>
			<div class="clr"></div>
		</div>
		<?php
		echo $pane->endPanel();
		echo $pane->startPanel( JText::_( 'COM_IYOSISMAPS_EDIT_TAB_KML' ), 'panel5' );
		?>
		<div>
			<ul class="adminformlist">
			<li><?php echo $this->form->getLabel('kml'); ?>
			<?php echo $this->form->getInput('kml'); ?></li>

			<li><?php echo $this->form->getLabel('kmlurl'); ?>
			<?php echo $this->form->getInput('kmlurl'); ?></li>
			</ul>
			<div class="clr"></div>
		</div>
		<?php
		echo $pane->endPanel();
		echo $pane->endPane();
		?>
		</fieldset>
	</div>

	<div>
		<input type="hidden" name="task" value="map.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<div class="footer" align="center">
	<a href="http://www.iyosis.com/" target="_blank"><?php echo JText::_( 'COM_IYOSISMAPS_FOOTER' ); ?></a>
</div>
