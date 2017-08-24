<?php

namespace GBV\DANTE;

use JSKOS\ConceptScheme;


// TODO: Caching und Fehlerbehandlung
class Client extends \JSKOS\Client {

    public function __construct(string $base='http://api.dante.gbv.de/') {
        parent::__construct($base);
    }

    public function queryURI(string $uri, array $query) {
        $query['uri'] = $uri;
        return $this->queryResource('data', $query);
    }

    public function queryResource(string $base, array $query) {
        $resource = $this->query($query, $base)[0] ?? null;

        if ($resource instanceof ConceptScheme &&
            $resource->uri && 
            count($resource->notation) && 
            !count($resource->topConcept ?? [])
        ) {
            // get selected fields only to speed up query
            $resource->topConcepts = $this->query(
                ['properties'=>'notation'],
                "voc/".$resource->notation[0]."/top"
            );
        }

        return $resource;
    }
}
