<?php

use BorderCloud\SPARQL\SparqlClient;

require_once ('../vendor/autoload.php');

$endpoint = "http://localhost:3030/websemInf/sparql";
$sc = new SparqlClient();

$sc->setEndpointRead($endpoint);
//$sc->setMethodHTTPRead("GET");
$q = "PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

SELECT ?x ?y ?note ?titre ?resume ?poster ?nomRealisateur ?duree WHERE 
{
  	?a a ?coordonnees.
	?coordonnees rdfs:label \"coordonnées\"@fr.
  	?a <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLDataProperty_30b3afa8_0143_4c02_ba87_52ed69fdb037> ?x.
  ?a <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLDataProperty_3d9dc69a_be17_4ff8_9811_7d64595a225b> ?y.
  ?adresse <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLObjectProperty_34bb83bd_13bf_460d_9a5f_9721d3b218d6> ?a.
  ?film <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLObjectProperty_8b624f36_40f7_4152_94b4_2243b6a3a2ac> ?adresse.
  OPTIONAL {?film <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLDataProperty_614c3545_53b1_487b_9503_c8edf9c9a4b7> ?note.}.
  ?film rdfs:label ?titre.
  OPTIONAL {?film <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLDataProperty_a0bb5b29_0359_4903_8e1c_808b3101a5a5> ?resume}.
  OPTIONAL {?film <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLDataProperty_3817489b_921d_40a7_bdc1_c730e9e48e5f> ?poster}.
  ?film <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLObjectProperty_debcb6a0_f7ad_45a5_9e31_b62ebba01f27> ?realisateur.
  ?realisateur rdfs:label ?nomRealisateur.
  OPTIONAL {?film <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLDataProperty_98d4f6aa_7216_46e4_8fc1_ae238cc80f63> ?duree.}.
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
    if(isset($row["note"]))
    {
    	$tab[$i]["note"] = $row["note"];
    }
    else
    {
    	$tab[$i]["note"] = null;
    }
    $tab[$i]["titre"] = $row["titre"];
    if(isset($row["resume"]))
    {
    	$tab[$i]["resume"] = $row["resume"];
    }
    else
    {
    	$tab[$i]["resume"] = null;
    }
    if(isset($row["poster"]))
    {
    	$tab[$i]["poster"] = $row["poster"];
    }
    else
    {
    	$tab[$i]["poster"] = null;
    }
    $tab[$i]["nomRealisateur"] = $row["nomRealisateur"];
    if(isset($row["duree"]))
    {
    	$tab[$i]["duree"] = $row["duree"];
    }
    else
    {
    	$tab[$i]["duree"] = null;
    }
    $i ++;
}
echo json_encode($tab);