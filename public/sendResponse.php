<?php

/**
 * Send out HTTP response.
 */

use JSKOS\Set;
use JSKOS\Resource;
use JSKOS\Concept;
use GBV\RDF\Negotiator;

// Negotiate response format
$format = Negotiator::negotiate(
    $_GET['format'] ?? '', 
    $_SERVER['HTTP_ACCEPT'] ?? 'text/html'
);

if ($format == 'html') {
// Send HTTP response

    require_once 'utils.php';

    if ($JSKOS instanceof Set) {
        $TEMPLATE = 'welcome.php';
    } elseif ($JSKOS instanceof Resource) {
        list ($TITLE) = requireLabel($JSKOS->prefLabel, $LANGUAGE);
        if ($JSKOS instanceof Concept and count($JSKOS->notation)) {
            $TITLE = $JSKOS->notation[0] . " $TITLE";
        }
        $class = substr(get_class($JSKOS),6);
        $TEMPLATE = strtolower("$class.php");
        # $URI = $JSKOS->uri;
    } else {
        $TITLE = 'Nicht gefunden';
        $TEMPLATE = '404.php';
    }

    $FORMATS = Negotiator::FORMATS;
    include 'header.php';
    include $TEMPLATE;
    $QUERIES = array_map(function($q) use ($API) {
        $q['context']['time'] = $q['datetime']->format(DATE_ATOM);        
        $q['context']['url'] = $API
            . $q['context']['path'] . '?'
            . http_build_query($q['context']['query']);
        return $q['context'];
    }, $queryLogger->getRecords());    
    include 'queries.php';
    include 'footer.php';

} else {
// Send RDF response

    header("Content-Type: ".Negotiator::mimeType($format));

    if (!isset($JSKOS)) {
        http_response_code(404);
    }

    if ($format == 'jsonld') {
        print isset($JSKOS) ? $JSKOS->json() : '{}';
    } else {
        $parser = new \JSKOS\RDF\Parser();
        $graph = isset($JSKOS) ? $parser->parseJSKOS($JSKOS) : new \EasyRdf_Graph();
    	print $graph->serialise($format);
    }
}
