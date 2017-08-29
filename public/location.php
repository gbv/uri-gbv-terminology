<?php 
if ($JSKOS->location) {
?><script>
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
