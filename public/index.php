<?php

/**
 * Alle Anfragen mit der Basis-URL <http://uri.gbv.de/terminology/>
 * (außer statische Seiten) werden durch dieses Skript geroutet.
 */

use JSKOS\Client;

require '../vendor/autoload.php';

$LANGUAGE = 'de'; # TODO: Sprache wählen

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

function clientRequest($url) {
    return json_decode(file_get_contents($url), true);
}

// JSKOS-Daten abrufen (TODO: Caching und Fehlerbehandlung)
function query($url=null) {
    global $URI, $ID, $KOS, $TYPE, $APIURL, $JSKOS;

	if ($url) {
		$APIURL = $url;
	// TODO: official URI vs. DANTE-URI von Vokabularen 
	} elseif ($ID=='') {
    	$APIURL = "http://api.dante.gbv.de/voc/$KOS?properties=*";
	} else {
	    $APIURL = 'http://api.dante.gbv.de/data?uri='.urlencode($URI).'&properties=*';
	}
    $json = clientRequest($APIURL); 

    if (!count($json)) {
		return;
	} 

	$TYPE = in_array('http://www.w3.org/2004/02/skos/core#ConceptScheme',$json[0]['type'])
		  ? 'ConceptScheme' : 'Concept';
	$class = "JSKOS\\$TYPE";
	$JSKOS = new $class($json[0]);

	if ($TYPE == 'ConceptScheme' && $JSKOS->uri && !($JSKOS->topConcept ?? 0)) {
		$KOS = $JSKOS->notation[0];
		// TODO: get selected fields only to speed up query
		$url = "http://api.dante.gbv.de/voc/$KOS/top?properties=notation";
		$JSKOS->topConcepts = clientRequest($url);
	}
    
	return $JSKOS;
}

function prepareView() {
    global $JSKOS, $LANGUAGE, $TYPE, $TITLE, $MAIN, $KOS;

    if (isset($JSKOS)) {
       $TITLE = label($JSKOS->prefLabel, $LANGUAGE);
        if ($TYPE == 'Concept' and count($JSKOS->notation)) {
            $TITLE = $JSKOS->notation[0] . " $TITLE";
        }
        $MAIN = strtolower("$TYPE.php");
    } else {
        $TITLE = 'Nicht gefunden';
        $MAIN = '404.php';
        $KOS = '';
    }
}

   
// Anfragepfad parsen
$PATH = $_SERVER['PATH_INFO'] ?? '/';
if (!$PATH) $PATH = '/';
$URI = "http://uri.gbv.de/terminology$PATH";

// Ermittelt $SELF and $BASE als relative Pfade
$parts = explode('/', $PATH);
$SELF = './'.$parts[count($parts)-1];
array_shift($parts);
$BASE = './' . str_repeat('../', count($parts)-1);

// Anfragepfad => /$KOS/$ID
$KOS = array_shift($parts);
$ID =  implode('/', $parts);

// Startseite
if ($PATH == '/') {
    unset($URI);
    $TITLE = 'Normdaten und Terminologien des GBV';

    if (isset($_GET['uri'])) {
        $URI = $_GET['uri'];
        $JSKOS = query('http://api.dante.gbv.de/data?uri='.urlencode($URI).'&properties=*');
		if ($JSKOS) {
            prepareView();
        } else {
            $TITLE = 'Nicht gefunden';
            $MAIN = '404.php';
        }
    } else {
        $APIURL = 'http://api.dante.gbv.de/voc';
        $client = new Client($APIURL);
        $KOSLIST = $client->query([]);
        $MAIN = 'welcome.php';
    }
}
// Fehlerhafte URL
elseif($KOS == '') {
    $TITLE = 'Nicht gefunden';
    $MAIN = '404.php';
}
// Fehlendes '/' am Ende
elseif ($ID == '' and substr($PATH,-1) != '/') {
    header("Location: .$PATH/");
    exit;
} 
else {
	$JSKOS = query();
    prepareView();
}

// serialize as RDF if required

require 'rdf.php';

use GBV\RDF\Negotiator;

$accept = $_SERVER['HTTP_ACCEPT'] ?? 'text/html';
$negotiator = new Negotiator();
$format = $negotiator->negotiate($_GET['format'] ?? '', $accept);

if (isset($JSKOS) && $format != 'html') {
    header("Content-Type: ".$negotiator->mimeType($format));
    print jskos2rdf($JSKOS, $format); # TODO: base $URI/$KOS
    exit;
}

// show HTML otherwise

$PREFIX = 'http://uri.gbv.de/terminology';
$FORMATS = $negotiator->formats;

include 'header.php';
include $MAIN;
include 'footer.php';
