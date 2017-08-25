<table class="table">
<?php 

function locationMap($location) {
    if (!$location) return;
    return json_encode($location, true);
}

include 'item.php';
include 'relations.php';

row('Anfang', $JSKOS->startDate, 'time');
row('Ende', $JSKOS->endDate, 'time');
row('Datum', $JSKOS->relatedDate, 'time');

$location = $JSKOS->location ?? [];
if ($JSKOS->location) { ?>
  <tr>
    <td>
      <span class="glyphicon glyphicon-map-marker"></span>
      location
    </td>
    <td><div id='map'></div></td>
  </tr>
<?php
}

?>
</table>
<?php if ($JSKOS->location) { ?>
<script>
    var map = L.map('map');
    $('#map').show().width('600').height('400');
    var osmUrl='http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    var osmAttrib='Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors';
    var osm = new L.TileLayer(osmUrl, {minZoom: 8, maxZoom: 12, attribution: osmAttrib});    
    map.addLayer(osm);
    var locationLayer = L.geoJSON().addTo(map);
    var loc = <?=json_encode($JSKOS->location)?>;    
    locationLayer.addData(loc);
    map.fitBounds(locationLayer.getBounds());
    map.fitBounds(bounds);
</script>
<?php } ?>
<?php include('meta.php'); ?>
