<?php

include_once 'utils.php';

row('URI', uri_link($JSKOS));

row_list('Notation', $JSKOS, 'notation',
    function ($n) { return '<code>'.htmlspecialchars($n).'</code>'; }
);

row_list('Identifier', $JSKOS, 'identifier',
    function ($id) {
        if (preg_match('/^https?:/', $id)) {
            $id = uri_link((object)['uri'=>$id]);
        }
        return "<code>$id</code>";
    }
);

$labelTypes = [
    'altLabel'      => 'Label',
    'hiddenLabel'   => 'Suchbegriffe',
    'scopeNote'     => 'Hinweis',
    'definition'    => 'Definition',
    'example'       => 'Beispiel',
    'editorialNote' => 'Berarbeitungshinweis',
    'changeNote'    => 'Änderungshinweis'
];

foreach ($labelTypes as $type => $name) {
    if (!isset($JSKOS->$type)) continue;

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

# TODO: type
# TODO: subject, subjectOf, depiction
# TODO: created, issued, modified, creator, contributor, publisher, partOf
