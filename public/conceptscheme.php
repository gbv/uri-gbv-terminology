<table class="table">
<?php

include_once 'utils.php';

include 'item.php';

include 'license.php';

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

if (count($JSKOS->subject)) {
    $subjects = $JSKOS->subject->map(function($s) {
        return uri_link_with_label($s);
    });
    row('Thema', implode('<br>', $subjects));
}

# TODO: type (thesaurus, classification...)

?>
</table>
<?php include('meta.php'); ?>
