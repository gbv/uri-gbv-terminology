<?php

// emit table row unless value is empty
function row($label, $value) { 
    if (($value ?? '') == '') return;    
?>
  <tr>
    <tr>
    <td><?= htmlspecialchars($label) ?></td>
    <td><?= $value ?></td>
  </tr>
<?php }

function row_list($label, $jskos, $field, $callback) {
    if ($jskos->$field) {
        row($label, implode('<br>',$jskos->$field->map($callback)));
    }
}

// like sprintf but returns NULL if any argument is NULL
function formatted($format) {
    $args = func_get_args();
    array_shift($args);
    if (in_array(NULL, $args, TRUE)) return;
    return vsprintf($format, $args);
}

// link to an item's uri
function uri_link($item) {
    global $BASE, $PREFIX;
    $uri = $item->uri;
    $href = $uri;
    if (substr($uri, 0, strlen($PREFIX)) === $PREFIX) {
        $href = $BASE . substr($uri, strlen($PREFIX)+1);
    }
    return '<a href="'.htmlspecialchars($href).'">'
           .htmlspecialchars($uri).'</a>'; 
}

// link to an item's uri
function uri_link_with_label($item) {
    global $LANGUAGE;

    $html = uri_link($item);

    if (isset($item->prefLabel->{$LANGUAGE})) {
        return $html . " " . htmlspecialchars($item->prefLabel->{$LANGUAGE});
    } # TODO: other languages?

    return $html;
}

