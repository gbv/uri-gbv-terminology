<?php

/**
 * Alle Anfragen mit der Basis-URL <http://uri.gbv.de/terminology/>
 * (außer statische Seiten) werden durch dieses Skript geroutet.
 */

use GBV\DANTE\Client;
use GBV\RDF\Negotiator;
use JSKOS\Resource;
use JSKOS\Set;
use JSKOS\Concept;
use JSKOS\LoggingService;
use JSKOS\ConceptScheme;

require '../vendor/autoload.php';

$LANGUAGE = 'de'; # TODO: support language selection
$TITLE = 'Normdaten und Terminologien im GBV';


// Map request path and query to $URI, $BASE, $VOCID, and $NOTATION
preg_match('!^/([^/]*)(/)?(.*)!', $_SERVER['PATH_INFO'] ?: '/', $match);
list ($path, $VOCID, $slash, $NOTATION) = $match;

// append trailing slash and force redirect
if ($VOCID != '' && !$slash) {
    header("Location: .$path/");
    exit;
}

$BASE = ($slash ? '../' : './')
      . str_repeat('../', count(explode('/', $NOTATION??''))-1);

$URI = $path == '/' && ($_GET['uri'] ?? null) 
    ? $_GET['uri']
    : "http://uri.gbv.de/terminology$path";

# TOOD: parse URI to KOS and NOTATION
#if (preg_match: "http://uri.gbv.de/terminology$path";

// Collect data
use Monolog\Logger;
use Monolog\Handler\TestHandler;

$API = 'http://api.dante.gbv.de/';

$logger = new Logger('querylogger');
$queryLogger = new TestHandler();
$logger->pushHandler($queryLogger);
$CLIENT = new Client($API, $logger);

if ($URI == "http://uri.gbv.de/terminology/") {
    $JSKOS = $CLIENT->query([],'voc');
} else {
    # FIXME: uri=$URI should also work at DANTE API!
    if ($VOCID != '' && $NOTATION == '') {
	    $JSKOS = $CLIENT->queryResource("voc/$VOCID", ['properties'=>'*']);
	} else {
        $JSKOS = $CLIENT->queryURI($URI, ['properties'=>'*']);
    }
}


// Negotiate response format
$format = Negotiator::negotiate(
    $_GET['format'] ?? '', 
    $_SERVER['HTTP_ACCEPT'] ?? 'text/html'
);

// Send HTTP response
if ($format == 'html') {

    function ll($item, $language)
    {
        if (isset($item[$language])) {
            return $language;
        } else {
            foreach ($item as $language => $label) {
                return $language;
            }
        }
    }

    function label($item, $language) {
        return $item[$language] ?? $item[ll($item, $language)] ?? '???';
    }

    if ($JSKOS instanceof Set) {
        $TEMPLATE = 'welcome.php';
    } elseif ($JSKOS instanceof Resource) {
        $TITLE = label($JSKOS->prefLabel, $LANGUAGE);
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
    header("Content-Type: ".Negotiator::mimeType($format));

    if (isset($JSKOS)) {
        http_response_code(404);
    }

    if ($format == 'jsonld') {
        print isset($JSKOS) ? $JSKOS->json() : '{}';
    } else {
        $parser = new \JSKOS\RDF\Parser();
        $graph = isset($JSKOS) ? $parser->parseJSKOS($JSKOS) : new EasyRdf_Graph();
    	print $graph->serialise($format);
    }

    exit;
}
