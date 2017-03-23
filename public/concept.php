<table class="table">
<?php 

include 'item.php';
include 'relations.php';

row('Anfang', $JSKOS->startDate);
row('Ende', $JSKOS->endDate);
row('Datum', $JSKOS->date);
row('Ort', $JSKOS->location ? json_encode($JSOS->location) : NULL);

?>
</table>
<?php
include 'jskos.php';
