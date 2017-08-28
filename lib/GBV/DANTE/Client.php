<?php

namespace GBV\DANTE;

use JSKOS\ConceptScheme;
use JSKOS\Service;
use JSKOS\Result;
use JSKOS\LoggingService;
use Psr\Log\LoggerInterface;

// TODO: Caching und Fehlerbehandlung
class Client extends Service 
{
    protected $client;

    public function __construct(string $base, LoggerInterface $logger) 
    {
        $this->client = new LoggingService(new \JSKOS\Client($base), $logger);
    }

    public function query(array $query=[], string $path=''): Result
    {
        return $this->client->query($query, $path);
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
