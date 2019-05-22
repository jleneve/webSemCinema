<?php
//display error
ini_set('display_errors', 1);

set_time_limit(0);
require_once ('../vendor/autoload.php');
require_once ('../utils/utils.php');
use BorderCloud\SPARQL\SparqlClient;
$fp = fopen('san_francisco.txt', 'w');
$sc = new SparqlClient();

$sc->setEndpointWrite("http://localhost:3030/websemInf/update");
$sc->setEndpointRead("http://localhost:3030/websemInf/query");


if ($handle = fopen("Film_Locations_in_San_Francisco.csv", "r")){
    $row = 0;
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        /*if($row == 10){
            break;
        }*/
        
        if($row !=0){
            $num = count($data); //Nombre d'éléments à traiter sur la ligne
            if($num == 11){

                $titre = $data[0];
                $adresse = $data[2];
                $realisateur = $data[7];
                if ($adresse != "") {
                    $coord = getCoordGps($adresse);
                    if($coord != false){
                        fwrite($fp, $titre.",".$adresse.",".$realisateur.",".$coord["latitude"].",".$coord["longitude"]."\\n");
                        $prefix = "PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
        PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
        PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>

";

                        //insert lieu de tournage
                        $q = "INSERT {?newUri a ?lieu_de_tournage. 
        ?newUri rdfs:label \"".$adresse."\"@fr.}
        WHERE{ 
        ?lieu_de_tournage rdfs:label \"lieu de tournage\"@fr.
        BIND (uri(concat(\"http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#\",encode_for_uri(\"".clear_str($adresse)."\"))) AS ?newUri).
};
";
                        $rows = $sc->query($prefix.$q);

                        //insert film
                        $q = "INSERT {?newUri a ?film. 
?newUri rdfs:label \"".$titre."\"@fr.}
WHERE{ 
  ?film rdfs:label \"film\"@fr.
  BIND (uri(concat(\"http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#\",encode_for_uri(\"".clear_str($titre)."\"))) AS ?newUri).
};
";
                        $rows = $sc->query($prefix.$q);

                        //insert realisateur
                        $q = "INSERT {?newUri a <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLClass_4e48b982_f829_4508_88a7_f6c606d671ae>. 
?newUri rdfs:label \"".$realisateur."\"@fr.}
WHERE{ 
  BIND (uri(concat(\"http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#\",encode_for_uri(\"".clear_str($realisateur)."\"))) AS ?newUri).
};
";
                        $rows = $sc->query($prefix.$q);

                        //insert film tourne dans
                        $q = "INSERT {<http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#".clear_str($titre)."> 
    ?tourne_dans 
<http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#".clear_str($adresse).">}
WHERE{ 
 ?tourne_dans rdfs:label \"a été tourné dans\"@fr.
};
";
                        $rows = $sc->query($prefix.$q);

                        //insert data
                        $q = "INSERT DATA {<http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#coordonne".$row."> <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLDataProperty_3d9dc69a_be17_4ff8_9811_7d64595a225b> \"".$coord["longitude"]."\"^^xsd:decimal. <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#coordonne".$row."> <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLDataProperty_30b3afa8_0143_4c02_ba87_52ed69fdb037> \"".$coord["latitude"]."\"^^xsd:decimal.
 <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#".clear_str($adresse)."> <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLObjectProperty_34bb83bd_13bf_460d_9a5f_9721d3b218d6> <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#coordonne".$row.">.
    <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#".clear_str($titre)."> 
    <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#OWLObjectProperty_debcb6a0_f7ad_45a5_9e31_b62ebba01f27> 
    <http://www.semanticweb.org/fabien/ontologies/2019/1/untitled-ontology-2#".clear_str($realisateur).">

};
";
                        $rows = $sc->query($prefix.$q);

                    }
                }
            }else {
                var_dump("erreur nb de colonne ");
            }
        }
    $row++;
    
    }
}else {
  var_dump("erreur lecture");
}
fclose($fp);

function getCoordGps($adresse){
    $url = 'https://nominatim.openstreetmap.org/search/'.str_replace(" ", "%20", $adresse).',%20San%20Francisco?format=json&limit=1&email=youremail@gmail.com';

    $data = ''; // empty post
    $opts = array(
        'http' => array(
        'header' => "Content-type: text/html\r\nContent-Length: " . strlen($data) . "\r\n",
        'method' => 'POST'
        ),
    ); 
    // Create a stream
    $context = stream_context_create($opts);
    // Open the file - get the json response using the HTTP headers set above
    $jsonfile = file_get_contents($url, false, $context);
    // decode the json 
    if (!json_decode($jsonfile, TRUE)) {
        return false;
    }else{
        $resp = json_decode($jsonfile, true);
  
        $gps['latitude'] = $resp[0]['lat'];
        $gps['longitude'] = $resp[0]['lon'];
        return $gps;
    }
}