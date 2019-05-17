<?php
//display error
ini_set('display_errors', 1);

set_time_limit(0);
require_once ('../vendor/autoload.php');
require_once ('../utils/utils.php');
use BorderCloud\SPARQL\SparqlClient;

$sc = new SparqlClient();

$sc->setEndpointWrite("http://localhost:3030/websemInf/update");
$sc->setEndpointRead("http://localhost:3030/websemInf/query");

if ($handle = fopen("lieu_de_tournage.csv", "r")){
    $row = 0;
    while ((($data = fgetcsv($handle, 1000, ",")) !== FALSE) /*&& $row != 10*/) {
        if($row !=0){
            $num = count($data); //Nombre d'éléments à traiter sur la ligne
            if($num == 9){
                //col 0 titre
                $titre = $data[0];
                $titre = str_replace(" ", "%20", $titre);
                echo $data[0];
                //col 1 realisateur
                $realisateur = $data[1];
                echo $data[1];
                //col 2 adresse
                $adresse = $data[2];
                echo $data[2];
                //col 3 organisme demandeur non gardé
                //echo $data[3];
                //col 4 type de tournage
                $type_tournage =  $data[4];
                echo $data[4];
                //col 5 ardt non gardé
                //echo $data[5];
                //col 6 date debut non gardé
                //echo $data[6];
                //col 7 date fin non gardé
                //echo $data[7];
                //col 8 xy
                //echo $data[8];
                $prefix = "PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
        PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
        PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

";
                echo "//////////////////////////////////////////////////////";
                echo "num ligne ".$row;
                $response = file_get_contents('http://www.omdbapi.com/?apikey=99bac2bc&t='.$titre);
                $response = json_decode($response,true);
                if($response["Response"]== "True"){
                    var_dump($response);
                    $note = $response["imdbRating"];
                    $affiche = $response["Poster"];
                    $duree = (int) filter_var($response["Runtime"], FILTER_SANITIZE_NUMBER_INT);
                    $categorie = $response["Genre"];
                    $resume = $response["Plot"];

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
                    foreach ($directors as $realisateur) {
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
                    }
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
                echo "//////////////////////////////////////////////////////";
                echo "<br/>";
            }else {
                var_dump("erreur nb de colonne ");
            }
        }
    $row++;
    }
}else {
  var_dump("erreur lecture");
}