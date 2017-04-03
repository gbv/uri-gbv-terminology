<?php

namespace GBV\RDF;

// don't sent headers
function header($string) {}

class NegotiatorTest extends \PHPUnit_Framework_TestCase
{
    public function testNegotiate()
    {
        $neg = new Negotiator();

        foreach (['', 'html', 'wtf?'] as $name) {
            $this->assertEquals('html', $neg->negotiate($name));
        }

        foreach (['jsonld','turtle','rdfxml','ntriples','rdfjson','svg','png'] as $name) {
            $this->assertEquals($name, $neg->negotiate($name));
            $this->assertEquals($name, $neg->negotiate($name, 'text/html'));
        }

        $mimeTypes = [
            'text/html'                 => 'html',
            'application/xhtml+xml'     => 'html',
            'application/ld+json'       => 'jsonld',
            'application/json'          => 'jsonld',
            'text/json'                 => 'jsonld',
            'text/turtle'               => 'turtle',
            'application/turtle'        => 'turtle',
            'application/x-turtle'      => 'turtle',
            'application/rdf+xml'       => 'rdfxml',
            'application/n-triples'     => 'ntriples',
            'text/plain'                => 'ntriples',
            'text/ntriples'             => 'ntriples',
            'application/ntriples'      => 'ntriples',
            'application/x-ntriples'    => 'ntriples',
            'application/rdf+json'      => 'rdfjson',
        ];

        foreach ($mimeTypes as $type => $format) {
            $this->assertEquals($format, $neg->negotiate('', $type));
        }
    }

    public function testMimeTypes()
    {
        $neg = new Negotiator();

        $mimeTypes = [
            'text/html'                 => 'html',
            'application/json'          => 'jsonld',
            'text/turtle'               => 'turtle',
            'application/rdf+xml'       => 'rdfxml',
            'application/n-triples'     => 'ntriples',
            'application/rdf+json'      => 'rdfjson',
            'image/svg+xml'             => 'svg',
            'image/png'                 => 'png',
        ];

        foreach ($mimeTypes as $type => $format) {
            $this->assertEquals($type, $neg->mimeType($format));
        }
    }
}
