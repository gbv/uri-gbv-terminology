<?php

/**
 * Alle Anfragen mit der Basis-URL <http://uri.gbv.de/terminology/>
 * (außer statische Seiten) werden durch dieses Skript geroutet.
 */

use GBV\DANTE\Client;
use Monolog\Logger;
use Monolog\Handler\TestHandler;

require_once '../vendor/autoload.php';

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

if ($path == '/' && ($_GET['uri'] ?? null)) {
    $URI = $_GET['uri'];
    
    if (preg_match('!^http://uri.gbv.de/terminology/([^/]+)(/(.+))?$!', $URI, $match)) {
        $VOCID = $match[1];
        $NOTATION = $match[3];
    } elseif (preg_match('!^http://bartoc\.org/en/node/(\d+)$!', $URI, $match)) {
        $VOCID = 'bartoc';
        $NOTATION = $match[1];
        $URI = "http://bartoc.org/en/node/$NOTATION";
    } 

} else {
    $URI = "http://uri.gbv.de/terminology$path";
}


// Collect data
$queryLogger = new TestHandler();

$API = 'http://api.dante.gbv.de/';

$logger = new Logger('querylogger');
$logger->pushHandler($queryLogger);
$CLIENT = new Client($API, $logger);

if ($VOCID == 'bartoc') { # TODO: add VIAF, Wikidata and more wrapped terminologies
    $URI = "http://bartoc.org/en/node/$NOTATION";

    $service = new \BARTOC\JSKOS\Service();
    if (!$NOTATION) {
        $URI = "http://bartoc.org/en/node/2054";
    }
    $data = $service->query(['uri'=>$URI]);
    $JSKOS = $data[0] ?? null;        
} elseif ($URI == "http://uri.gbv.de/terminology/") {
    $JSKOS = $CLIENT->query([],'voc');
} else {
    # FIXME: uri=$URI should also work at DANTE API!
    if ($VOCID != '' && $NOTATION == '') {
	    $JSKOS = $CLIENT->queryResource("voc/$VOCID", ['properties'=>'*']);
	} else {
        $JSKOS = $CLIENT->queryURI($URI, ['properties'=>'*']);
    }
}

include_once 'sendResponse.php';
