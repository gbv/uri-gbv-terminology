<?php

# TODO: topConceptOf

$kos = array_map('uri_link', $JSKOS->inScheme);
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
    $set = array_reverse($JSKOS->$field);
    row($label, implode('<br>', array_map('uri_link', $set)));
}
