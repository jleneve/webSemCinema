<?php

use BorderCloud\SPARQL\SparqlClient;

require_once ('../vendor/autoload.php');

$endpoint = "http://localhost:3030/websemInf/sparql";
$sc = new SparqlClient();

$sc->setEndpointRead($endpoint);
//$sc->setMethodHTTPRead("GET");
$q = "PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>

SELECT ?filmDBPedia ?labelActeurs WHERE
{
  ?film rdfs:label \"".$_GET["titre"]."\"@fr.
  ?film a <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#film>.
  OPTIONAL{?film owl:sameAs ?filmDBPedia.
  FILTER(contains(str(?filmDBPedia), \"dbpedia\")).}.
  SERVICE <http://dbpedia.org/sparql> {
?filmDBPedia <http://dbpedia.org/ontology/starring> ?acteurs.
    ?acteurs rdfs:label ?labelActeurs.
    FILTER(lang(?labelActeurs) = \"en\").
}
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
    $tab[$i]["acteur"] = $row["labelActeurs"];
    $i ++;
}
echo json_encode($tab);