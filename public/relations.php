<?php

# TODO: topConceptOf

row_list('KOS', $JSKOS, 'inScheme', 'uri_link_with_label');

$relations = [
    'ancestors' => 'Übergeordnet',
    'broader' => 'Oberbegriffe',
    'narrower' => 'Unterbegriffe',
    'related' => 'Siehe auch',
    'previous' => 'vorher',
    'next' => 'nachher',    
];

foreach ($relations as $field => $label) {
    if (!isset($JSKOS->$field)) continue;

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
