<table class="table">
<?php 

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
<?php include 'location.php'; ?>
<?php include('meta.php'); ?>
