<table class="table">
<?php

include_once 'utils.php';

include 'item.php';

if ($JSKOS->license) {
    row('Lizenz',
        implode('<br>', $JSKOS->license->map(
            function ($x) { 
                $uri = htmlspecialchars($x->uri);
                return "<a href='$uri'>$uri</a>";
            }
    )), 'copyright-mark');
}

// if $jskos->$field ?? []: $field->

row('Umfang', $JSKOS->extent);

if ($JSKOS->languages) {
    row('Sprachen', $JSKOS->languages->implode(', '));
}

if ($JSKOS->topConcepts) {
    $top = iterator_to_array($JSKOS->topConcepts);
    uasort($top, function ($a, $b) {
        return $a->uri <=> $b->uri;
    });
    row('Oberste Begriffe', implode('<br>',
        array_map('uri_link_with_label', $top)
    ),'arrow-down');
}

?>
</table>
<?php include('meta.php'); ?>
