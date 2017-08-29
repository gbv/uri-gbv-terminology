<?php

/**
 * Return a possibly relative URL from an URI.
 */
function uriLink(string $uri, string $base, bool $external = false)
{
    $prefix = 'http://uri.gbv.de/terminology';

    if (substr($uri, 0, strlen($prefix)) === $prefix) {
        return $base . substr($uri, strlen($prefix)+1);
    } elseif ($external) {
        return $uri;
    } else {
		return "$base?uri=$uri";
	}
}

/**
 * Get a label or fallback.
 * TODO: move as utility function to JSKOS-PHP
 */
function requireLabel($labels, string $lang, string $fallback = '???')
{
    if (isset($labels[$lang])) {
        return [$labels[$lang], $lang];
    } else {
        # TODO: better heuristics instead of random language
        foreach ($labels as $language => $label) {
            return [$label, $language];
        }
    }
    return [$fallback, 'und'];
}


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

// link to an item's uri
function uri_link($item, $label=null, $relative = true) {
    global $BASE;

    $PREFIX = 'http://uri.gbv.de/terminology';
    $uri = $item->uri;
    if (!$uri) return;

    $href = uriLink($uri, $BASE, !$relative);
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

