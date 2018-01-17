<?php

/**
 * Alle Anfragen mit der Basis-URL <http://uri.gbv.de/terminology/>
 * (außer statische Seiten) werden durch dieses Skript geroutet.
 */

use GBV\DANTE\Client;
use Monolog\Logger;
use Monolog\Handler\TestHandler;

require_once '../vendor/autoload.php';

function redirect($url) {
    header("Location: $url");
    exit;
}

$LANGUAGE = 'de'; # TODO: support language selection
$TITLE = 'Wissensorganisationssysteme im GBV';


// Map request path and query to $URI, $BASE, $VOCID, and $NOTATION
preg_match('!^/([^/]*)(/)?(.*)!', @$_SERVER['PATH_INFO'] ?: '/', $match);
list ($path, $VOCID, $slash, $NOTATION) = $match;

// append trailing slash and force redirect
if ($VOCID != '' && !$slash) {
    redirect(".$path/");
}

$BASE = ($slash ? '../' : './')
      . str_repeat('../', count(explode('/', $NOTATION??''))-1);

if ($VOCID == 'about' && $NOTATION == '' ) {
    require 'about.php';
    exit;
}

// search by location
if ($VOCID != '' && isset($_GET['local'])) {
    // TODO: support external URIs such as iconclass
    redirect("$BASE$VOCID/".$_GET['local']);
}


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
$DANTE = new Client($API, $logger);

if ($VOCID == 'bartoc') { # TODO: add VIAF, Wikidata and more wrapped terminologies
    $URI = "http://bartoc.org/en/node/$NOTATION";

    $service = new \BARTOC\JSKOS\Service();
    if (!$NOTATION) {
        $URI = "http://bartoc.org/en/node/2054";
    }
    $data = $service->query(['uri'=>$URI]);
    $JSKOS = $data[0] ?? null;        
    
    if ($JSKOS) {
        // TODO: anreichern per DANTE
    }

} elseif ($URI == "http://uri.gbv.de/terminology/") {
    $JSKOS = $DANTE->query([],'voc');
} else {
    if ($VOCID != '' && $NOTATION == '') {
        $JSKOS = $DANTE->queryResource("voc/$VOCID", ['properties'=>'*']);
        if ($JSKOS && substr($JSKOS->uri,0,30) != 'http://uri.gbv.de/terminology/') {
            redirect("$BASE?uri=".$JSKOS->uri);
        }
	} else {
        $JSKOS = $DANTE->queryURI($URI, ['properties'=>'*']);
        if ($JSKOS && $VOCID == '' && $JSKOS->type[0] == 'http://www.w3.org/2004/02/skos/core#ConceptScheme') {
            $VOCID = $JSKOS->notation[0] ?? '';
        }
    }
}

include_once 'sendResponse.php';
