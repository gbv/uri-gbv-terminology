<?php

/**
 * Utilities to handle RDF.
 */

use ML\JsonLD\JsonLD;
use ML\JsonLD\NQuads;

function jskos2rdf($jskos, $format) {

    if ($format == 'jsonld') {
        return $jskos->json();
    } elseif ($format == 'rdfjson') {
        $format = 'json';
    }

	# $context = json_decode(file_get_contents('jskos-context.json'));
	$context = 'jskos-context.json';
    $rdf = JsonLD::toRdf($jskos, ['expandContext' => $context]);
    # TODO: catch (\ML\JsonLD\Exception\JsonLdException $e) 

	$graph = new \EasyRdf_Graph();
	(new QuadsParser())->parse($graph, $rdf);
	return $graph->serialise($format);
}

// Taken from EasyRdf
class QuadsParser extends \EasyRdf_Parser
{
	public function parse($graph, $data, $format='jsonld', $baseUri=NULL) 
 	{
		parent::checkParseParams($graph, $data, $format, $baseUri);

		foreach ($data as $quad) {
		  $subject = (string) $quad->getSubject();

		  if ('_:' === substr($subject, 0, 2)) {
			$subject = $this->remapBnode($subject);
		  }

		  $predicate = (string) $quad->getProperty();

		  if ($quad->getObject() instanceof \ML\IRI\IRI) {
			$object = array(
			  'type' => 'uri',
			  'value' => (string) $quad->getObject()
			);

			if ('_:' === substr($object['value'], 0, 2)) {
			  $object = array(
				'type' => 'bnode',
				'value' => $this->remapBnode($object['value'])
			  );
			}
		  }
		  else {
			$object = array(
			  'type' => 'literal',
			  'value' => $quad->getObject()->getValue()
			);

			if ($quad->getObject() instanceof \ML\JsonLD\LanguageTaggedString) {
			  $object['lang'] = $quad->getObject()->getLanguage();
			}
			else {
			  $object['datatype'] = $quad->getObject()->getType();
			}
		  }

		  $this->addTriple($subject, $predicate, $object);
		}
    }
}


