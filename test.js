/**
 * Created by JetBrains PhpStorm.
 * User: Jacques
 * Date: 21/01/12
 * Time: 16:16
 * To change this template use File | Settings | File Templates.
 */
var coordinates33 = [ ];
var polygon33 = new google.maps.Polygon({path: coordinates33,map: map,strokeColor: '#0.6',strokeOpacity: 0.6,strokeWeight: 0.4,fillColor: '#0.4',fillOpacity: 0.6, clickable: false});
google.maps.event.addListener(polygon33,'mouseover',function(event) {
    polygon33.setOptions( { fillOpacity: 0.65 });
});
google.maps.event.addListener(polygon33,'mouseout',function(event) { polygon33.setOptions( { fillOpacity: 0.2 })})
