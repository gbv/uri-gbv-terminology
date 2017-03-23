<table class="table">
<?php

include 'item.php';

row('Lizenz',
    implode('<br>', array_map(
        function ($x) { 
            $uri = htmlspecialchars($x->uri);
            return "<a href='$uri'>$uri</a>";
        },
        $JSKOS->license
    ))
);

row('Umfang', $JSKOS->extent);
row('Sprachen', implode(', ', $JSKOS->languages));

?>
</table>
<?php
include 'jskos.php';
