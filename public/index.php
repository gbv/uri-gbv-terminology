<?php

/**
 * Alle Anfragen mit der Basis-URL <http://uri.gbv.de/terminology/> werden durch
 * dieses Skript geroutet (außer statische Seiten).
 */

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
    $MAIN = 'welcome.php';
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

    // JSKOS-Daten abrufen (TODO: Caching und Fehlerbehandlung)
    if ($ID == '') {
        $TYPE = 'ConceptScheme';
        $APIURL = "http://api.dante.gbv.de/voc/$KOS";
        $json = file_get_contents($APIURL); 
        $JSKOS = new JSKOS\ConceptScheme($json);

        if ($JSKOS->uri and !($JSKOS->topConcept ?? 0)) {
            // TODO: get selected fields only to speed up query
            $url = "http://api.dante.gbv.de/voc/$KOS/top";
            $json = file_get_contents($url); 
            $JSKOS->topConcepts = json_decode($json);
        }
    } else {
        $TYPE = 'Concept';
        $APIURL = 'http://api.dante.gbv.de/data?uri='.urlencode($URI);
        $json = file_get_contents($APIURL); 
        $data = json_decode($json);
        if (count($data)) {
            $JSKOS = new JSKOS\Concept($data);
        }
    }
    
    if (!$JSKOS) {
        $TITLE = 'Nicht gefunden';
        $MAIN = '404.php';
    } else {
        $TITLE = label((array)$JSKOS->prefLabel, $LANGUAGE);
        if ($TYPE == 'Concept' and count($JSKOS->notation)) {
            $TITLE = $JSKOS->notation[0] . " $TITLE";
        }
        $MAIN = strtolower("$TYPE.php");
    }
}


require 'rdf.php';

$RDF_FORMATS = array_intersect(['turtle','ntriples','rdfxml','png','svg'],\EasyRdf_Format::getNames());
$FORMAT = $_GET['format'] ?? '';

use \Negotiation\Negotiator;

// if there was no proposed format, negotiate a suitable format
if ($FORMAT != 'html' and !in_array($FORMAT, $RDF_FORMATS)) {
	$accept = $_SERVER['HTTP_ACCEPT'] ?? 'text/turtle';
	$choices = ['text/html', 'application/xhtml+xml'];
	$choices[] = 'text/turtle';
	$choices[] = 'application/n-triples';
	$choices[] = 'application/rdf+xml';
	
	header('Vary: Accept'); // inform caches that a decision was made based on Accept header
	$negotiator = new Negotiator();
	$format = $negotiator->getBest($accept, $choices);
	if ($format !== null) {
	 	$format = $format->getValue();
		$format = EasyRdf_Format::getFormat($format);
		if ($format and in_array($format->getName(),$RDF_FORMATS)) {
			$FORMAT = $format->getName();
		}
	}
}

if ($JSKOS && in_array($FORMAT, $RDF_FORMATS)) {
	$mimeType = EasyRdf_Format::getFormat($FORMAT)->getDefaultMimeType();
	header("Content-Type: $mimeType");
    print jskos2rdf($JSKOS, $FORMAT);
    exit;
}

include 'header.php';
include $MAIN;
include 'footer.php';
