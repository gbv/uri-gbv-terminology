<?php

namespace GBV\RDF;

use GBV\RDF\Negotiator as N;

// don't sent headers
function header($string) {}

/**
 * @covers GBV\RDF\Negotiator
 */
class NegotiatorTest extends \PHPUnit_Framework_TestCase
{
    public function testNegotiate()
    {
        foreach (['', 'html', 'wtf?'] as $name) {
            $this->assertEquals('html', N::negotiate($name));
        }

        foreach (['jsonld','turtle','rdfxml','ntriples'] as $name) {
            $this->assertEquals($name, N::negotiate($name));
            $this->assertEquals($name, N::negotiate($name, 'text/html'));
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
            # 'application/rdf+json'      => 'rdfjson',
        ];

        foreach ($mimeTypes as $type => $format) {
            $this->assertEquals($format, N::negotiate('', $type));
        }
    }

    public function testMimeTypes()
    {
        $mimeTypes = [
            'text/html'                 => 'html',
            'application/json'          => 'jsonld',
            'text/turtle'               => 'turtle',
            'application/rdf+xml'       => 'rdfxml',
            'application/n-triples'     => 'ntriples',
            # 'application/rdf+json'      => 'rdfjson',
        ];

        foreach ($mimeTypes as $type => $format) {
            $this->assertEquals($type, N::mimeType($format));
        }
    }
}
