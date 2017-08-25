<?php

# TODO: topConceptOf

row_list('KOS', $JSKOS, 'inScheme', 'uri_link_with_label', 'folder-open');

$relations = [
    'ancestors' => ['Übergeordnet','arrow-up'],
    'broader' => ['Oberbegriffe','arrow-up'],
    'narrower' => ['Unterbegriffe','arrow-down'],
    'related' => ['Siehe auch','arrow-left'],
    'previous' => ['vorher','arrow-left'],
    'next' => ['nachher','arrow-right'],
];

foreach ($relations as $field => $rel) {
    if (!$JSKOS->$field) continue;

    list ($label, $icon) = $rel;

    if ($field == 'ancestors') {
        $set = array_reverse(iterator_to_array($JSKOS->$field));
    } else {
        $set = iterator_to_array($JSKOS->$field);
        uasort($set, function ($a, $b) {
            return $a->uri <=> $b->uri;
        });
    }

    row($label, implode('<br>', array_map('uri_link_with_label', $set)), $icon);
}
