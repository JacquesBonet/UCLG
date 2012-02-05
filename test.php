<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jacques
 * Date: 21/01/12
 * Time: 16:09
 * To change this template use File | Settings | File Templates.
 */
$ID = 3;
$polygon = "\t\tvar coordinates3".$ID." = [\n";
$polygon .= "\t\t];\n";
$polygon .= "\t\tvar polygon3".$ID." = new google.maps.Polygon({path: coordinates3".$ID.",map: map,strokeColor: '#0.6',strokeOpacity: 0.6,strokeWeight: 0.4,fillColor: '#0.4',fillOpacity: 0.6";
	if (!$infowindow) $polygon = $polygon.", clickable: false";
	$polygon = $polygon."});\n";
if($infowindow&&$description) {
	$description = str_replace("'", "\'", $description);
	$description = str_replace("\r\n", "'+\r\t\t'", $description);
	$polygon .= "\t\tvar infowindowContent3".$ID." = '".$description."';\n";
	$polygon .= "\t\tcreateInfoWindowPolygon(polygon3".$ID.",infowindowContent3".$ID.");\n";
}
      // modif jb
      $polygon .= "\t\tgoogle.maps.event.addListener(polygon3".$ID.",'mouseover',function(event) {\n";
      $polygon .= "\t\t\tpolygon3".$ID.".setOptions( { fillOpacity: 0.65 })})";
      $polygon .= "\t\tgoogle.maps.event.addListener(polygon3".$ID.",'mouseout',function(event) {\n";
      $polygon .= "\t\t\tpolygon3".$ID.".setOptions( { fillOpacity: 0.2 })})";
echo $polygon;

var coordinates33 = [ ]; var polygon33 = new google.maps.Polygon({path: coordinates33,map: map,strokeColor: '#0.6',strokeOpacity: 0.6,strokeWeight: 0.4,fillColor: '#0.4',fillOpacity: 0.6, clickable: false}); google.maps.event.addListener(polygon33,'mouseover',function(event) { polygon33.setOptions( { fillOpacity: 0.65 })})	 google.maps.event.addListener(polygon33,'mouseout',function(event) { polygon33.setOptions( { fillOpacity: 0.2 })})
?>