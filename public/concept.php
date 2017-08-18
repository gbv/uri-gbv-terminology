<table class="table">
<?php 

include 'item.php';
include 'relations.php';

row('Anfang', $JSKOS->startDate, 'time');
row('Ende', $JSKOS->endDate, 'time');
row('Datum', $JSKOS->relatedDate, 'time');
row('Ort', $JSKOS->location ? json_encode($JSOS->location) : NULL, 'map-marker');

?>
</table>
