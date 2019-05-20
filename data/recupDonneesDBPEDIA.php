<?php

use BorderCloud\SPARQL\SparqlClient;

set_time_limit(0);
require_once ('../vendor/autoload.php');

$endpoint = "http://localhost:3030/websemInf/sparql";
$sc = new SparqlClient();

$sc->setEndpointRead($endpoint);
//$sc->setMethodHTTPRead("GET");
$q = "PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

SELECT ?titre ?nomRealisateur WHERE 
{
  	?a rdf:type <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#film>.
  	?a rdfs:label ?titre.
    ?a <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLObjectProperty_debcb6a0_f7ad_45a5_9e31_b62ebba01f27> ?realisateur.
    ?realisateur rdfs:label ?nomRealisateur
}
LIMIT 10";
$rows = $sc->query($q, 'rows');

$err = $sc->getErrors();
if ($err) {
    print_r($err);
    throw new Exception(print_r($err, true));
}

$tabFilms = array();

$i = 0;
foreach ($rows["result"]["rows"] as $row) {
	$titre =  explode("/", $row["titre"])[0];
	$realisateur = $row["nomRealisateur"];
    $tabFilms[$i] = requeteDBPEDIA($titre, $realisateur);
    $i++;
}

echo json_encode($tabFilms);

function requeteDBPEDIA($titre, $realisateur)
{
	$endpoint = "http://dbpedia.org/sparql";
	$sc = new SparqlClient();

	$sc->setEndpointRead($endpoint);
	//$sc->setMethodHTTPRead("GET");
	$q = "SELECT DISTINCT ?a WHERE {
	?a rdfs:label ?x.
	?a rdf:type <http://dbpedia.org/ontology/TelevisionShow>.
        ?a <http://dbpedia.org/ontology/writer> ?b.
        ?b rdfs:label ?nom.
	Filter(regex(?x,\"^".$titre."\",\"i\") && regex(?nom,\"^".$realisateur."\",\"i\"))
}";

	$rows = $sc->query($q, 'rows');

	$err = $sc->getErrors();
	if ($err) {
	    print_r($err);
	    throw new Exception(print_r($err, true));
	}

	if(count($rows["result"]["rows"]) == 0)
	{
		$q = "SELECT DISTINCT ?a WHERE {
		?a rdfs:label ?x.
		?a rdf:type <http://dbpedia.org/ontology/TelevisionShow>.
	        ?a <http://dbpedia.org/ontology/creator> ?b.
	        ?b rdfs:label ?nom.
		Filter(regex(?x,\"^".$titre."\",\"i\") && regex(?nom,\"^".$realisateur."\",\"i\"))
	}";

		$rows = $sc->query($q, 'rows');

		$err = $sc->getErrors();
		if ($err) {
		    print_r($err);
		    throw new Exception(print_r($err, true));
		}

		if(count($rows["result"]["rows"]) == 0)
		{
			$q = "SELECT DISTINCT ?a WHERE {
			?a rdfs:label ?x.
			?a rdf:type <http://dbpedia.org/ontology/Film>.
		        ?a <http://dbpedia.org/ontology/creator> ?b.
		        ?b rdfs:label ?nom.
			Filter(regex(?x,\"^".$titre."\",\"i\") && regex(?nom,\"^".$realisateur."\",\"i\"))
		}";
			$rows = $sc->query($q, 'rows');

			$err = $sc->getErrors();
			if ($err) {
			    print_r($err);
			    throw new Exception(print_r($err, true));
			}

			if(count($rows["result"]["rows"]) == 0)
			{
				$q = "SELECT DISTINCT ?a WHERE {
				?a rdfs:label ?x.
				?a rdf:type <http://dbpedia.org/ontology/Film>.
			        ?a <http://dbpedia.org/ontology/creator> ?b.
			        ?b rdfs:label ?nom.
				Filter(regex(?x,\"^".$titre."\",\"i\") && regex(?nom,\"^".$realisateur."\",\"i\"))
			}";
				$rows = $sc->query($q, 'rows');

				$err = $sc->getErrors();
				if ($err) {
				    print_r($err);
				    throw new Exception(print_r($err, true));
				}
			}
		}
	}

	foreach ($rows["result"]["rows"] as $row) {
	    foreach ($rows["result"]["variables"] as $variable) {
	        return $row[$variable];
	    }
	}
}

//requeteDBPEDIA("Le bureau des légendes");