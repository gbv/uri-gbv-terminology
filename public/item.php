<?php

include_once 'utils.php';

row('URI', uri_link($JSKOS));

row('Notation',  
    implode(', ', array_map(
        function ($n) { return '<code>'.htmlspecialchars($n).'</code>'; },
        $JSKOS->notation)
));

row('Identifier',
    implode('<br>',array_map(
        function ($id) {
            if (preg_match('/^https?:/', $id)) {
                $id = uri_link((object)['uri'=>$id]);
            }
            return "<code>$id</code>";
        },
        $JSKOS->identifier
    )
));
$labelTypes = [
    'altLabel' => 'Label',
    'hiddenLabel' => 'Suchbegriffe',
    'scopeNote' => 'Hinweis',
    'definition' => 'Definition',
    'example' => 'Beispiel',
    'editorialNote' => 'Berarbeitungshinweis',
    'changeNote' => 'Änderungshinweis'
];

foreach ($labelTypes as $type => $name) {
    $labels = [];

    foreach ($JSKOS->$type as $lang => $list) {
        foreach ($list as $s) {
            $s = htmlspecialchars($s);
            if ($lang != $LANGUAGE) $s .= "<sup>$lang</sup>";
            $labels[] = $s;
        }
    }

    row($name, implode('<br>', $labels));
}

row('URL', formatted('<a href="%s">%s</a>', $JSKOS->url, $JSKOS->url));

# TODO: uri, type
# TODO: subject, subjectOf, depiction
# TODO: created, issued, modified, creator, contributor, publisher, partOf
