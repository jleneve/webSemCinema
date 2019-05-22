<?php

use BorderCloud\SPARQL\SparqlClient;

set_time_limit(0);
require_once ('../vendor/autoload.php');

$endpoint = "http://localhost:3030/websemInf/sparql";
$sc = new SparqlClient();

$sc->setEndpointRead($endpoint);
$sc->setEndpointWrite("http://localhost:3030/websemInf/update");
//$sc->setMethodHTTPRead("GET");
$q = "PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

SELECT ?titre ?nomRealisateur ?a WHERE 
{
  	?a rdf:type <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#film>.
  	?a rdfs:label ?titre.
    ?a <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLObjectProperty_debcb6a0_f7ad_45a5_9e31_b62ebba01f27> ?realisateur.
    ?realisateur rdfs:label ?nomRealisateur
}";
$rows = $sc->query($q, 'rows');

$err = $sc->getErrors();
if ($err) {
    print_r($err);
    throw new Exception(print_r($err, true));
}

$tabFilms = array();

foreach ($rows["result"]["rows"] as $row) {
	$titre =  explode("/", $row["titre"])[0];
	$realisateur = $row["nomRealisateur"];
	$uriFilm = $row["a"];
	$film = requeteDBPEDIA($titre, $realisateur);
	if($film != null && !in_array($film, $tabFilms))
	{
		$tabFilms[$uriFilm] = $film;
	}
	$film = requeteDBPEDIAWriter($titre, $realisateur);
	if($film != null && !in_array($film, $tabFilms))
	{
		$tabFilms[$uriFilm] = $film;
	}
	$film = requeteDBPEDIADirector($titre, $realisateur);
	if($film != null && !in_array($film, $tabFilms))
	{
		$tabFilms[$uriFilm] = $film;
	}
	$film = requeteDBPEDIADirectorOnto($titre, $realisateur);
	if($film != null && !in_array($film, $tabFilms))
	{
		$tabFilms[$uriFilm] = $film;
	}
}

echo json_encode($tabFilms);

foreach($tabFilms as $key => $value)
{
	$q = "PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
		PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
		PREFIX owl: <http://www.w3.org/2002/07/owl#>

	INSERT DATA {
 		<".$key."> owl:sameAs <".$value.">
        };";

        $rows = $sc->query($q);
}

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
		?a rdf:type <http://dbpedia.org/ontology/Film>.
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
	}

	foreach ($rows["result"]["rows"] as $row) {
	    foreach ($rows["result"]["variables"] as $variable) {
	        return $row[$variable];
	    }
	}
}

function requeteDBPEDIAWriter($titre, $realisateur)
{
	$endpoint = "http://dbpedia.org/sparql";
	$sc = new SparqlClient();

	$sc->setEndpointRead($endpoint);
	//$sc->setMethodHTTPRead("GET");
	
	$q = "SELECT DISTINCT ?a WHERE {
	?a rdfs:label ?x.
	?a rdf:type <http://dbpedia.org/ontology/TelevisionShow>.
        ?a <http://dbpedia.org/property/writer> ?b.
	Filter(regex(?x,\"^".$titre."\",\"i\") && regex(?b,\"^".$realisateur."\",\"i\"))
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
	        ?a <http://dbpedia.org/property/writer> ?b.
		Filter(regex(?x,\"^".$titre."\",\"i\") && regex(?b,\"^".$realisateur."\",\"i\"))
	}";
		$rows = $sc->query($q, 'rows');

		$err = $sc->getErrors();
		if ($err) {
		    print_r($err);
		    throw new Exception(print_r($err, true));
		}
	}

	foreach ($rows["result"]["rows"] as $row) {
	    foreach ($rows["result"]["variables"] as $variable) {
	        return $row[$variable];
	    }
	}
}


function requeteDBPEDIADirector($titre, $realisateur)
{
	$endpoint = "http://dbpedia.org/sparql";
	$sc = new SparqlClient();

	$sc->setEndpointRead($endpoint);
	//$sc->setMethodHTTPRead("GET");

	$q = "SELECT DISTINCT ?a WHERE {
		?a rdfs:label ?x.
		?a rdf:type <http://dbpedia.org/ontology/TelevisionShow>.
	        ?a <http://dbpedia.org/property/director> ?b.
		Filter(regex(?x,\"^".$titre."\",\"i\") && regex(?b,\"^".$realisateur."\",\"i\"))
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
	        ?a <http://dbpedia.org/property/director> ?b.
		Filter(regex(?x,\"^".$titre."\",\"i\") && regex(?b,\"^".$realisateur."\",\"i\"))
	}";

		$rows = $sc->query($q, 'rows');

		$err = $sc->getErrors();
		if ($err) {
		    print_r($err);
		    throw new Exception(print_r($err, true));
		}
	}

	foreach ($rows["result"]["rows"] as $row) {
	    foreach ($rows["result"]["variables"] as $variable) {
	        return $row[$variable];
	    }
	}
}

function requeteDBPEDIADirectorOnto($titre, $realisateur)
{
	$endpoint = "http://dbpedia.org/sparql";
	$sc = new SparqlClient();

	$sc->setEndpointRead($endpoint);
	//$sc->setMethodHTTPRead("GET");
	$q = "SELECT DISTINCT ?a WHERE {
	?a rdfs:label ?x.
	?a rdf:type <http://dbpedia.org/ontology/TelevisionShow>.
        ?a <http://dbpedia.org/ontology/director> ?b.
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
	        ?a <http://dbpedia.org/ontology/director> ?b.
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

	foreach ($rows["result"]["rows"] as $row) {
	    foreach ($rows["result"]["variables"] as $variable) {
	        return $row[$variable];
	    }
	}
}