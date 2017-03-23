<?php

# TODO: inScheme, topConceptOf

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
    $set = $JSKOS->$field;
    row($label, implode('<br>', array_map(
        function ($x) { 
            global $BASE, $PREFIX;
            $uri = $x->uri;
            $href = $uri;
            if (substr($uri, 0, strlen($PREFIX)) === $PREFIX) {
                $href = $BASE . substr($uri, strlen($PREFIX)+1);
            }
            return '<a href="'.htmlspecialchars($href).'">'
                   .htmlspecialchars($uri).'</a>'; 
        },
        array_reverse($set)
    )));
}
