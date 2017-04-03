<?php

# TODO: topConceptOf

$kos = array_map('uri_link_with_label', $JSKOS->inScheme);
row('KOS', implode('<br>', $kos));

$relations = [
    'ancestors' => 'Übergeordnet',
    'broader' => 'Oberbegriffe',
    'narrower' => 'Unterbegriffe',
    'related' => 'Siehe auch',
    'previous' => 'vorher',
    'next' => 'nachher',    
];

$PREFIX = 'http://uri.gbv.de/terminology';

foreach ($relations as $field => $label) {
    if (!isset($JSKOS->$field)) return '';

    if ($field == 'ancestors') {
        $set = array_reverse($JSKOS->$field);
    } else {
        $set = $JSKOS->$field;
        uasort($set, function ($a, $b) {
            return $a->uri <=> $b->uri;
        });
    }
    row($label, implode('<br>', array_map('uri_link_with_label', $set)));
}
