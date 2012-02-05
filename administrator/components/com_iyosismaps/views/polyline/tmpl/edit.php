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
var map;
var polyline;
var coordinates = [];
var markersArray = [];
function initialize() {
	var lat, lng, zoom;
		lat = 20;
		lng = -40;
		zoom = 2;
	var latlng1 = new google.maps.LatLng(lat,lng);
	var myOptions = {
		zoom: zoom,
		center: latlng1,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	coordinates = [
		<?php
		$coordinatesTemp = $this->item->coordinates;
		$coordinatesTemp = str_replace("\n", "", $coordinatesTemp);
		$coordinatesTemp = str_replace("\r", "", $coordinatesTemp);
		$coordinates = explode(";", $coordinatesTemp);
		foreach ($coordinates as $coordinate)
			if($coordinate) echo "new google.maps.LatLng".$coordinate.",\n";
		?>
	];
	polyline = new google.maps.Polyline({
		path: coordinates,
		map: map,
		strokeColor: "#FF0000",
		strokeOpacity: 1.0,
		strokeWeight: 2
	});
	google.maps.event.addListener(map, "click", function(event) {
		document.adminForm.jform_coordinates.value = document.adminForm.jform_coordinates.value+event.latLng+";\n";
	});
	google.maps.event.addListener(map, 'click', addLatLng);
	function addLatLng(event) {
		if (!polyline.getMap()) {
			coordinates = [];
			polyline = new google.maps.Polyline({
				path: coordinates,
				map: map,
				strokeColor: "#FF0000",
				strokeOpacity: 1.0,
				strokeWeight: 2
			});
		}

		var path = polyline.getPath();

		// Because path is an MVCArray, we can simply append a new coordinate
		// and it will automatically appear
		path.push(event.latLng);

		// Add a new marker at the new plotted point on the polyline.
		marker = new google.maps.Marker({
			position: event.latLng,
			title: '#' + path.getLength(),
			map: map
		});
		markersArray.push(marker);
	}
}

function clearTheMap() {
	document.adminForm.jform_coordinates.value = null;
	polyline.setMap(null);
	if (markersArray) {
		for (i in markersArray) {
		markersArray[i].setMap(null);
		}
	}
}

window.onload = initialize;
</script>

<?php
$editor = JFactory::getEditor();
jimport('joomla.html.pane');
$pane = JPane::getInstance('tabs', array('startOffset'=>0)); 
?>

<form action="<?php echo JRoute::_('index.php?option=com_iyosismaps&view=polyline&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="polyline-form" class="form-validate">
	<div class="width-100">
		<fieldset class="adminform">
		<?php
		echo $pane->startPane( 'pane' );
		echo $pane->startPanel( JText::_( 'COM_IYOSISMAPS_POLYLINE' ), 'panel1' );
		?>
		<div>
			<ul class="adminformlist">
			<li><?php echo $this->form->getLabel('title'); ?>
			<?php echo $this->form->getInput('title'); ?></li>

			<li><?php echo $this->form->getLabel('catid'); ?>
			<?php echo $this->form->getInput('catid'); ?></li>

			<li><?php echo $this->form->getLabel('published'); ?>
			<?php echo $this->form->getInput('published'); ?></li>

			<li><?php echo $this->form->getLabel('mapid'); ?>
			<?php echo $this->form->getInput('mapid'); ?></li>

			<li><?php echo $this->form->getLabel('strokecolor'); ?>
			<?php echo $this->form->getInput('strokecolor'); ?></li>

			<li><?php echo $this->form->getLabel('strokeopacity'); ?>
			<?php echo $this->form->getInput('strokeopacity'); ?></li>

			<li><?php echo $this->form->getLabel('strokeweight'); ?>
			<?php echo $this->form->getInput('strokeweight'); ?></li>

			<li><?php echo $this->form->getLabel('coordinates'); ?>
			<?php echo $this->form->getInput('coordinates'); ?></li>
			</ul>

			<div class="clr"></div>
			<input onclick="clearTheMap();" type=button value="<?php echo JText::_( 'COM_IYOSISMAPS_FIELD_CLEAR_THE_MAP' ) ?>"/>
			<label><?php echo JText::_( 'COM_IYOSISMAPS_FIELD_COORDINATES_DESC' ) ?></label>
			<div id="map_canvas" style="width:100%;height:500px;clear:left;"></div>
			<div class="clr"></div>
		</div>
		<?php
		echo $pane->endPanel();
		echo $pane->startPanel( JText::_( 'COM_IYOSISMAPS_EDIT_TAB_INFOWINDOW' ), 'panel2' );
		?>
		<div>
			<ul class="adminformlist">
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
		echo $pane->endPane();
		?>
		</fieldset>
	</div>

	<div>
		<input type="hidden" name="task" value="polyline.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<div class="footer" align="center">
	<a href="http://www.iyosis.com/" target="_blank"><?php echo JText::_( 'COM_IYOSISMAPS_FOOTER' ); ?></a>
</div>
