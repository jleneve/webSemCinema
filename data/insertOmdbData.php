<?php

use BorderCloud\SPARQL\SparqlClient;

set_time_limit(0);
require_once ('../vendor/autoload.php');
require_once ('../utils/utils.php');


$endpoint = "http://localhost:3030/websemInf/sparql";
$sc = new SparqlClient();

$sc->setEndpointRead($endpoint);
$sc->setEndpointWrite("http://localhost:3030/websemInf/update");

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
}";
$rows = $sc->query($q, 'rows');

$err = $sc->getErrors();
if ($err) {
    print_r($err);
    throw new Exception(print_r($err, true));
}

$tabFilms = array();

$i = 0;
//var_dump($rows);

foreach ($rows["result"]["rows"] as $row) {
    sleep(0.5);
    $titre = explode("/", $row["titre"])[0];
    $realisateur = $row["nomRealisateur"];
    requeteOmdb($sc,$titre,$realisateur);
    /*if($i == 10){
        break;
    }*/
}

function requeteOmdb($sc,$titre,$realisateur)
{

    $prefix = "PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
        PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
        PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

";
    echo "//////////////////////////////////////////////////////";
    //echo "num ligne ".$row;
    $response = file_get_contents('http://www.omdbapi.com/?apikey=99bac2bc&t='.str_replace(" ","%20",$titre));
    $response = json_decode($response,true);
    if($response["Response"]== "True"){
        $note = $response["imdbRating"];
        $affiche = $response["Poster"];
        $duree = (int) filter_var($response["Runtime"], FILTER_SANITIZE_NUMBER_INT);
        $categorie = $response["Genre"];
        $resume = $response["Plot"];
        $realisateurOmdb = $response["Director"];

        if(clear_str($realisateur) == clear_str($realisateurOmdb)){
            echo $note;
            echo $affiche;
            echo $duree;
            echo $categorie;
            echo $resume;
            echo $realisateurOmdb;
            echo $titre;

            $q = "INSERT DATA {
        <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#".clear_str($titre).">
        <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLDataProperty_614c3545_53b1_487b_9503_c8edf9c9a4b7>
        \"".$note."\"^^xsd:decimal. 
        <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#".clear_str($titre).">
        <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLDataProperty_3817489b_921d_40a7_bdc1_c730e9e48e5f>
        \"".$affiche."\"^^xsd:string.
        <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#".clear_str($titre).">
        <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLDataProperty_98d4f6aa_7216_46e4_8fc1_ae238cc80f63>
        \"".$duree."\"^^xsd:decimal.
        <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#".clear_str($titre).">
        <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLDataProperty_0a4126dc_7ee4_4bc4_950e_4d157ce9cc6b>
        \"".$categorie."\"^^xsd:string.
        <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#".clear_str($titre).">
        <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLDataProperty_a0bb5b29_0359_4903_8e1c_808b3101a5a5>
        \"".$resume."\"^^xsd:string.
        };";

            $rows = $sc->query($prefix.$q);
            //echo $response["Director"];
            $directors = explode(", ", $response["Director"]);
            /*foreach ($directors as $realisateur) {
                $q = "INSERT {?newUri a ?realisateurUri. 
        ?newUri rdfs:label \"".$realisateur."\"@fr.}
        WHERE{ 
          ?realisateurUri rdfs:label \"réalisateur\"@fr.
          BIND (uri(concat(\"http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#\",encode_for_uri(\"".clear_str($realisateur)."\"))) AS ?newUri).
        };";
                $rows = $sc->query($prefix.$q);

                $q = "INSERT DATA {
        <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#".clear_str($titre).">
        <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLObjectProperty_debcb6a0_f7ad_45a5_9e31_b62ebba01f27>
        <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#".clear_str($realisateur).">
        };";
                $rows = $sc->query($prefix.$q);
            }*/
            //echo $response["Actors"];
            $actors = explode(", ", $response["Actors"]);
            foreach ($actors as $comedien) {
                $q = "INSERT {?newUri a ?comedienUri. 
        ?newUri rdfs:label \"".$comedien."\"@fr.}
        WHERE{ 
          ?comedienUri rdfs:label \"comédien\"@fr.
          BIND (uri(concat(\"http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#\",encode_for_uri(\"".clear_str($comedien)."\"))) AS ?newUri).
        };";
                $rows = $sc->query($prefix.$q);

                $q = "INSERT DATA { <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#".clear_str($titre).">
        <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLObjectProperty_916650df_eb4f_4983_b3b0_a69682283a11>
        <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#".clear_str($comedien)."> }";
                $rows = $sc->query($prefix.$q);
            }
        }
    }
}