<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jacques
 * Date: 21/01/12
 * Time: 16:09
 * To change this template use File | Settings | File Templates.
 */
$polygon = "\t\tvar coordinates3".$ID." = [\n";
$coordinates = str_replace("\n", "", $coordinates);
$coordinates = str_replace("\r", "", $coordinates);
$coordinates = explode(";", $coordinates);
$coordinates = array_filter($coordinates);
$i = 0;
$len = count($coordinates);
foreach ($coordinates as $coordinate) {
	if ($i == $len - 1) $polygon .= "\t\tnew google.maps.LatLng".$coordinate."\n";
	else $polygon .= "\t\tnew google.maps.LatLng".$coordinate.",\n";
	$i++;
}
$polygon .= "\t\t];\n";
$polygon .= "\t\tvar polygon3".$ID." = new google.maps.Polygon({path: coordinates3".$ID.",map: map,strokeColor: '#".$strokecolor."',strokeOpacity: ".$strokeopacity.",strokeWeight: ".$strokeweight.",fillColor: '#".$fillcolor."',fillOpacity: ".$fillopacity;
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
return $polygon;
echo $polygon;

echo '<h2 style="text-align: right; margin-top : 20px; margin-right: 18px;">World</h2>';
echo "<div style=\"margin:-20px 0px 0px 0px;padding:40px 0px 0px 0px;width:1040px; height:272px; background-image:url(/images/region_selection_bg.png)\"><div style=\"float:left;margin-left:15px\"> {iyosismaps id=2}</div><div class=\"bannermenu\"><ul><li><a href=\"/home/africa-section\" style=\"color:#795f7f\">Afrique</a></li><li><a href=\"/home/asia-pacific-section\" style=\"color:#269b32\">Asie Pacifique</a></li><li><a href=\"/home/eurasia-section\" style=\"color:#5b6900\">Eurasie</a></li><li><a href=\"/home/latin-america-section\" style=\"color:#930e01\">Amerique Latine</a></li><li><a href=\"/home/mewa-section\" style=\"color:#079cff\">MEWA</a></li><li><a href=\"/home/north-america-section\" style=\"color:#fb491c\">Amerique du Nord</a></li><li><a href=\"/home/ue-section\" style=\"color:#0e12c0\">Europe</a></li></ul></div></div>";

?>