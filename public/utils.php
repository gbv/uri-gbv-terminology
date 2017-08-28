<?php

// emit table row unless value is empty
function row($label, $value, $icon='none') { 
    if (($value ?? '') == '') return;    
?>
  <tr>
    <td>
      <span class="glyphicon glyphicon-<?=$icon?>"></span>
      <?= htmlspecialchars($label) ?>
    </td>
    <td><?= $value ?></td>
  </tr>
<?php }

function row_list($label, $jskos, $field, $callback, $icon='none') {
    if ($jskos->$field) {
        row($label, implode('<br>',$jskos->$field->map($callback)), $icon);
    }
}

// like sprintf but returns NULL if any argument is NULL
function formatted($format) {
    $args = func_get_args();
    array_shift($args);
    if (in_array(NULL, $args, TRUE)) return;
    return vsprintf($format, $args);
}

function startswith($string, $prefix) {
	return substr($string, 0, strlen($prefix)) === $prefix;
}

// link to an item's uri
function uri_link($item, $label=null, $relative = true) {
    global $BASE;

    $PREFIX = 'http://uri.gbv.de/terminology';
    $uri = $item->uri;
    if (startswith($uri, $PREFIX)) {
        $href = $BASE . substr($uri, strlen($PREFIX)+1);
    } elseif ($relative) {# && !startswith($uri, "https://uri.gbv.de/terminology/")) {
		$href = $BASE . "?uri=$uri";
    } else {
    	$href = $uri;
	}
	$label = $label ?? (count($item->notation ?? []) ? $item->notation[0] : $uri);
    return '<a href="'.htmlspecialchars($href).'">'
           .htmlspecialchars($label).'</a>'; 
}

// link to an item's uri
function uri_link_with_label($item) {
    global $LANGUAGE;

    $html = uri_link($item);

    if (isset($item->prefLabel[$LANGUAGE])) {
        return $html . " " . htmlspecialchars($item->prefLabel[$LANGUAGE]);
    } # TODO: other languages?

    return $html;
}

