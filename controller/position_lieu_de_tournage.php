<?php

use BorderCloud\SPARQL\SparqlClient;

require_once ('../vendor/autoload.php');

$endpoint = "http://localhost:3030/websemInf/sparql";
$sc = new SparqlClient();

$sc->setEndpointRead($endpoint);
//$sc->setMethodHTTPRead("GET");
$q = "PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

SELECT ?x ?y WHERE 
{
  	?a a ?coordonnees.
	?coordonnees rdfs:label \"coordonnées\"@fr.
  	?a <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLDataProperty_30b3afa8_0143_4c02_ba87_52ed69fdb037> ?x.
  ?a <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLDataProperty_3d9dc69a_be17_4ff8_9811_7d64595a225b> ?y
}";
$rows = $sc->query($q, 'rows');

$err = $sc->getErrors();
if ($err) {
    print_r($err);
    throw new Exception(print_r($err, true));
}

$tab = array();
$i = 0;
foreach ($rows["result"]["rows"] as $row) {
    $tab[$i]["x"] = $row["x"];
    $tab[$i]["y"] = $row["y"];
    $i ++;
}
echo json_encode($tab);