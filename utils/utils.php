<?php
function clear_str($text, $separator = '_', $charset = 'utf-8') {
	// Pour l'encodage
	$text = mb_convert_encoding($text,'HTML-ENTITIES',$charset);

	$text = strtolower(trim($text));

	// On vire les accents
	$text = preg_replace( array('/ß/','/&(..)lig;/', '/&([aouAOU])uml;/','/&(.)[^;]*;/'),
	array('ss',"$1","$1".'e',"$1"),
	$text);

	// on vire tout ce qui n'est pas alphanumérique
	/*$text_clear = preg_replace("[^a-z0-9_-]",' ',trim($text));// ^a-zA-Z0-9_-
	$text_clear = str_replace('\'',' ', trim($text_clear));*/
	$text_clear = preg_replace('#[^[:alnum:]]#u', ' ', trim($text));

	// Nettoyage pour un espace maxi entre les mots
	$array = explode(' ', $text_clear);
	$str = '';
	$i = 0;
	foreach($array as $cle=>$valeur){

		if(trim($valeur) != '' AND trim($valeur) != $separator AND $i > 0)
			$str .= $separator.$valeur;
		elseif(trim($valeur) != '' AND trim($valeur) != $separator AND $i == 0)
			$str .= $valeur;

		$i++;
	}

	//on renvoie la chaîne transformée
	return $str;
}

//echo clear_str("UNE FEMME D'EXCEPTION");
?>