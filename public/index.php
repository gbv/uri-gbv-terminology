<?php

/**
 * Alle Anfragen mit der Basis-URL <http://uri.gbv.de/terminology/>
 * (außer statische Seiten) werden durch dieses Skript geroutet.
 */

use JSKOS\Resource;
use JSKOS\Concept;
use JSKOS\ConceptScheme;
use GBV\DANTE\Client;

require '../vendor/autoload.php';

$CLIENT = new Client();

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

function prepareView() {
    global $JSKOS, $LANGUAGE, $TITLE, $MAIN, $KOS, $URI;

    if ($JSKOS instanceof Resource) {
        $TITLE = label($JSKOS->prefLabel, $LANGUAGE);
        if ($JSKOS instanceof Concept and count($JSKOS->notation)) {
            $TITLE = $JSKOS->notation[0] . " $TITLE";
        }
        $class = substr(get_class($JSKOS),6);
        $MAIN = strtolower("$class.php");
        $URI = $JSKOS->uri;
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
    $TITLE = 'Normdaten und Terminologien im GBV';

    if (isset($_GET['uri'])) {
        $URI = $_GET['uri'];
        $JSKOS = $CLIENT->queryURI($URI,['properties'=>'*']);
        prepareView();
    } else {
        $KOSLIST = $CLIENT->query([],'voc');
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
    if ($ID=='') {
	    $JSKOS = $CLIENT->queryResource("voc/$KOS", ['properties'=>'*']);
	} else {
        $JSKOS = $CLIENT->queryURI($URI,['properties'=>'*']);
    }
    prepareView();
}

// serialize as RDF if required

use GBV\RDF\Negotiator;

$accept = $_SERVER['HTTP_ACCEPT'] ?? 'text/html';
$negotiator = new Negotiator();
$format = $negotiator->negotiate($_GET['format'] ?? '', $accept);

if (isset($JSKOS) && $format != 'html') {
    header("Content-Type: ".$negotiator->mimeType($format));
    
    if ($format == 'jsonld') {
        print $JSKOS->json();
    } else {
        $parser = new \JSKOS\RDF\Parser();
        $graph = $parser->parseJSKOS($JSKOS);
    	print $graph->serialise($format);
    }

    exit;
}

// show HTML otherwise

$PREFIX = 'http://uri.gbv.de/terminology';
$FORMATS = $negotiator->formats;

include 'header.php';
include $MAIN;
include 'footer.php';
