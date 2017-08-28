<?php

namespace GBV\RDF;

use EasyRdf_Format;

class Negotiator
{
    const FORMATS = ['html','jsonld','turtle','rdfxml','ntriples'];

    public static function mimeType(string $name) {

        // JSON-LD and RDF/JSON use different mime types in EasyRdf
        if ($name == 'jsonld') {
            return 'application/json';
        } elseif ($name == 'rdfjson') {
            return 'application/rdf+json';
        }

        return EasyRdf_Format::getFormat($name)->getDefaultMimeType();
    }

    public static function negotiate(string $name='', $accept='text/html') {

        // explicit choice of a name
        if (in_array($name, self::FORMATS)) {
            return $name;
        }

        // inform caches that a decision was made based on Accept header
        header('Vary: Accept');

        $mimeTypes = [
            'text/html', 'application/xhtml+xml',
            'application/ld+json', 'application/json', 'text/json',
            'text/turtle', 'application/turtle', 'application/x-turtle',
            'application/rdf+xml',
            'application/n-triples', 'text/plain', 'text/ntriples', 'application/ntriples', 'application/x-ntriples',
            'application/rdf+json',
        ];

        $negotiator = new \Negotiation\Negotiator();
        $format = $negotiator->getBest($accept, $mimeTypes);
        if (!$format) {
            return 'html';
        }

        // JSON-LD and RDF/JSON use different mime types in EasyRdf
        $type = $format->getValue(); 
        if (in_array($type,['application/ld+json', 'application/json', 'text/json'])) {
            return 'jsonld';
        }

        $format = EasyRdf_Format::getFormat($type);
        if ($format and in_array($format->getName(), self::FORMATS)) {
            return $format->getName();
        } else {
            return 'html';
        }
    }
}
