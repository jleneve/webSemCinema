////--------------------------------------------------Déclaration des variables globales--------------------------------------------------------------\\\\

//groupe contenant les marqueurs liées aux parcelles
/*var filmControverse = L.markerClusterGroup({
	iconCreateFunction: function(cluster) {
        var icon = markersfilmControverse._defaultIconCreateFunction(cluster);
        icon.options.className += '-filmControverse-group';
        return icon;
    }
});

var filmBanal = L.markerClusterGroup({
	iconCreateFunction: function(cluster) {
        var icon = markersfilmBanal._defaultIconCreateFunction(cluster);
        icon.options.className += '-filmBanal-group';
        return icon;
    }
});

var filmBon = L.markerClusterGroup({
	iconCreateFunction: function(cluster) {
        var icon = markersfilmBon._defaultIconCreateFunction(cluster);
        icon.options.className += '-filmBon-group';
        return icon;
    }
});

var filmExcellent = L.markerClusterGroup({
	iconCreateFunction: function(cluster) {
        var icon = markersfilmExcellent._defaultIconCreateFunction(cluster);
        icon.options.className += '-filmExcellent-group';
        return icon;
    }
});

var filmChefDoeuvre = L.markerClusterGroup({
	iconCreateFunction: function(cluster) {
        var icon = markersfilmChefDoeuvre._defaultIconCreateFunction(cluster);
        icon.options.className += '-filmChefDoeuvre-group';
        return icon;
    }
});

var filmNonNote = L.markerClusterGroup({
	iconCreateFunction: function(cluster) {
        var icon = markersfilmNonNote._defaultIconCreateFunction(cluster);
        icon.options.className += '-filmNonNote-group';
        return icon;
    }
});*/

/*//groupe contenant les marqueurs liées aux ruchers
var ruchers = L.markerClusterGroup({
	iconCreateFunction: function(cluster) {
        var icon = markersRuchers._defaultIconCreateFunction(cluster);
        icon.options.className += '-ruchers-group';
        return icon;
    }
});*/

//markercluster
/*var markersfilmControverse = L.markerClusterGroup();
var markersfilmBanal = L.markerClusterGroup();
var markersfilmBon = L.markerClusterGroup();
var markersfilmExcellent = L.markerClusterGroup();
var markersfilmChefDoeuvre = L.markerClusterGroup();
var markersfilmNonNote = L.markerClusterGroup();*/
/*var markersRuchers = L.markerClusterGroup();*/

var filmControverse = L.layerGroup();
var filmBanal = L.layerGroup();
var filmBon = L.layerGroup();
var filmExcellent = L.layerGroup();
var filmChefDoeuvre = L.layerGroup();
var filmNonNote = L.layerGroup();


//tuile par défaut pour la map
var defaut = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	attribution: 'Map data &copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licences/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://mapbox.com">MapBox</a>',
	maxZoom: 20,
	id: 'your.mapbox.project.id',
	accessToken: 'your.mapbox.public.access.token'
});

//tuile : vue satellite
var satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
	attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
	maxZoom: 20
});

//Groupe de vue de la map
var baseMaps = {
	"Défaut": defaut,
	"Satellite": satellite
}

//Groupe de vue des parcelles/ruchers
var overlayMaps = {
	"Film Controversé": filmControverse,
	"Film Banal": filmBanal,
	"Film Bon": filmBon,
	"Film Excellent": filmExcellent,
	"Film Chef d'oeuvre": filmChefDoeuvre,
	"Film Non Noté": filmNonNote,
	/*"Ruchers": ruchers*/
}

//icône marqueur bleu
var myIconBleu = L.icon({
    iconUrl: '../images/marker-icon-bleu.png',

    iconSize:     [41, 41], // size of the icon
    iconAnchor:   [20, 41], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
});

//icône marqueur vert
var myIconVert = L.icon({
	iconUrl: '../images/marker-icon-vert.png',

	iconSize:     [41, 41], // size of the icon
    iconAnchor:   [20, 41], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
});

//icône marqueur rose
var myIconRose = L.icon({
	iconUrl: '../images/marker-icon-pink.png',

	iconSize:     [41, 41], // size of the icon
    iconAnchor:   [20, 41], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
});

//icône marqueur rose
var myIconRouge = L.icon({
	iconUrl: '../images/marker-icon-rouge.png',

	iconSize:     [41, 41], // size of the icon
    iconAnchor:   [20, 41], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
});

//icône marqueur rose
var myIconOrange = L.icon({
	iconUrl: '../images/marker-icon-orange.png',

	iconSize:     [41, 41], // size of the icon
    iconAnchor:   [20, 41], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
});

//icône marqueur rose
var myIconJaune = L.icon({
	iconUrl: '../images/marker-icon-jaune.png',

	iconSize:     [41, 41], // size of the icon
    iconAnchor:   [20, 41], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
});

//icône marqueur rose
var myIconVertCitron = L.icon({
	iconUrl: '../images/marker-icon-vert-citron.png',

	iconSize:     [41, 41], // size of the icon
    iconAnchor:   [20, 41], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
});

//icône marqueur rose
var myIconNoir = L.icon({
	iconUrl: '../images/marker-icon-noir.png',

	iconSize:     [41, 41], // size of the icon
    iconAnchor:   [20, 41], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
});

// variable globale marker
var marker;

////------------------------------------------------------ Déclaration des fonctions -----------------------------------------------------------------\\\\

// fonction exécuté lors du clic sur la map
// avec ajout d'un marqueur ou déplacement du marqueur s'il existe déjà
// et remplissage automatique du formuaire "Emplacement"
function onMapClick(e) {
	var lat = e.latlng.lat;
	var long = e.latlng.lng;
	
	if (marker)
	{
		marker.setLatLng(new L.LatLng(lat,long));
		marker.setIcon(myIconRose);
		marker.addTo(mymap);
	}
	else
	{
		marker = L.marker([lat, long], {icon: myIconRose}).addTo(mymap);
	}
	
	/*window.document.getElementById("lat").value = lat;
	window.document.getElementById("long").value = long;

	geocodeInverseAdresse(lat, long);*/
}

// fonction executée lors d'une sélection d'une adresse dans la barre de recherche
// permet de positionner la carte à l'endroit sélectionné
// placement d'un marker ou déplacement du amrker s'il existe déjà 
// remplissage automatique du formulaire "Emplacement"
function chooseAddr(lat, lng, type) {
	var location = new L.LatLng(lat, lng);
	mymap.panTo(location);

	if (type == 'city' || type == 'administrative') {
		mymap.setZoom(11);
	} 
	else 
	{
		mymap.setZoom(13);
	}
	
	//placementMarqueur(lat, lng);
	//MAJ_champs_Emplacement(lat, lng);
}

// fonction permettant de créer un nouveau marker
// ou de la déplcer s'il existe déjà
function placementMarqueur(lat, lng)
{
	if (marker)
	{
		marker.setLatLng(new L.LatLng(lat,lng));
	}
	else
	{
		marker = L.marker([lat, lng], {icon: myIconBleu}).addTo(mymap);
	}
}

// fonction qui permet de remplir automatiquement ou mettre à jour les champs
// latitude - longitude - addr1 - addr2 - ville - nom du formulaire "Emplacement"
function MAJ_champs_Emplacement(lat, lng)
{
	window.document.getElementById("lat").value = lat;
	window.document.getElementById("long").value = lng;

	geocodeInverseAdresse(lat, lng);
}

// fonction permettant de retrouver une adresse à partir d'une latitude et d'une longitude
// et mise a jour de tous les champs du formulaire
function geocodeInverseAdresse(lat, long){
	$.getJSON('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + long + '&zoom=18', function(data) {

		var addr1 = window.document.getElementById("addr1");
		var addr2 = window.document.getElementById("addr2");

		if (data.address.house_number && data.address.road) {
			addr1.value = data.address.house_number + ", " + data.address.road;
		}
		else if (data.address.road) {
			addr1.value = data.address.road;
		}
		else if (data.address.pedestrian) {
			addr1.value = data.address.pedestrian;
		}
		else {
			addr1.value = "Adresse non trouvée";
		}

		if (data.address.village && data.address.country) {
			if(data.address.postcode)
				addr2.value = data.address.postcode + ", " + data.address.village + ", " + data.address.country;
			else
				addr2.value = data.address.village + ", " + data.address.country;
		}
		else if(data.address.town && data.address.country) {
			if(data.address.postcode)
				addr2.value = data.address.postcode + ", " + data.address.town + ", " + data.address.country;
			else
				addr2.value = data.address.town + ", " + data.address.country;
 		}
		else if(data.address.city && data.address.country) {
			if(data.address.postcode)
				addr2.value = data.address.postcode + ", " + data.address.city + ", " + data.address.country;
			else
				addr2.value = data.address.city + ", " + data.address.country;
 		}
 		else
 			addr2.value = data.address.county + ", " + data.address.country;

 		if(data.address.city){
 			MAJ_nom_ville(data.address.city, data.address.postcode);
 		}
 		else if(data.address.town){
 			MAJ_nom_ville(data.address.town, data.address.postcode);
 		}
 		else if(data.address.village){
 			MAJ_nom_ville(data.address.village, data.address.postcode);
 		}
 		else
 			MAJ_nom_ville(data.address.county, data.address.postcode);
 	});
}


function searchAdresse()
{
	lat = document.getElementById('lat').value;
	long = document.getElementById('long').value;
	if(lat != '' && long != '')
	{
		geocodeInverseAdresse(lat, long);
		placementMarqueur(lat,long);
	}
}
// fonction permettant de retrouver la latitude et longitude
// à partir d'une adresse si les deux champs adresses sont renseignés
function searchLatLong(adresse)
{
	$.getJSON('https://nominatim.openstreetmap.org/?format=json&addressdetails=1&q=' + adresse + '&limit=1', function(data) {
		$.each(data, function(key, val) {
 			alert(val.lat + " | " + val.lon);
		});
	});
}


// fonction permettant de mettre à jour les champs ville et nom
function MAJ_nom_ville(ville, codePostal){
	var typeEmplacement = window.document.getElementById("typeEmplacement");
	var nom = window.document.getElementById("nom");
	var codeDepartement;

	if(codePostal != null)
		codeDepartement = codePostal.substring(0,2);


	document.getElementById('nomVille').value = ville;
	document.getElementById('codeDepartement').value = codeDepartement;

	if(ville != null){
		nom.value = ville;
	}
	else {
		nom.value = "VilleNonRenseignée";
	}
}

////--------------------------------------------------------- Création de la map -------------------------------------------------------------------\\\\

var mymap = L.map('map', {
	center: [46.628346, 2.577960],
	zoom: 6,
	layers: [defaut, filmControverse, filmBanal, filmBon, filmExcellent, filmChefDoeuvre, filmNonNote],
	loadingControl: true
});

////------------------------------------------------------- Ajout des contrôleurs à la map ----------------------------------------------------------\\\\

// contrôle layers
L.control.layers(baseMaps, overlayMaps, {position: 'bottomleft'}).addTo(mymap);

// contrôle échelle
L.control.scale().addTo(mymap);

// contrôle plein écran
L.control.fullscreen({
	position: 'bottomright',
	forceSeparateButton: true
}).addTo(mymap);

//mymap.on('click', onMapClick);

var tabAllFilms = [];
var tabCourtMetrages = [];
var tabLongMetrages = [];
var j = 0;
var k = 0;
var l = 0;

var recupEmplacements = $.getJSON("http://localhost/webSemCinema/controller/position_lieu_de_tournage.php", function(data) {
	$.each(data, function(key, val){
		var tableau = [];
		tableau["x"] = val.x;
		tableau["y"] = val.y;
		tableau["note"] = val.note;
		tableau["titre"] = val.titre;
		tableau["resume"] = val.resume;
		tableau["poster"] = val.poster;
		tableau["nomRealisateur"] = val.nomRealisateur;
		tableau["duree"] = val.duree;
		tabAllFilms[j] = tableau;
		j++;
		if(val.duree == null)
		{
			//on ne fait rien
		}
		else if(val.duree <= 59 )
		{
			tabCourtMetrages[k] = tableau;
			k++;
		}
		else if(val. duree > 59)
		{
			tabLongMetrages[l] = tableau;
			l++;
		}
		//console.log(val.note + "        " + val.titre + "\n");
		/*if(val.note == null)
		{
			filmNonNote.addLayer(L.marker([val.x, val.y], {icon: myIconNoir}).bindPopup("Titre : " + val.titre + "<br>Résumé : " + val.resume + "<br>Réalisateur : " +val.nomRealisateur+ "<br>Durée : " + val.duree + " min<br>Note : " + val.note + "/10<br><img src=\""+val.poster+"\"/>"));
		}
		else if(val.note < 2)
		{
			filmControverse.addLayer(L.marker([val.x, val.y], {icon: myIconRouge}).bindPopup("Titre : " + val.titre + "<br>Résumé : " + val.resume + "<br>Réalisateur : " +val.nomRealisateur+ "<br>Durée : " + val.duree + " min<br>Note : " + val.note + "/10<br><img src=\""+val.poster+"\"/>"));
			//markersfilmControverse.addLayer(filmControverse);
		}
		else if(val.note < 4 && val.note >= 2)
		{
			filmBanal.addLayer(L.marker([val.x, val.y], {icon: myIconOrange}).bindPopup("Titre : " + val.titre + "<br>Résumé : " + val.resume + "<br>Réalisateur : " +val.nomRealisateur+ "<br>Durée : " + val.duree + " min<br>Note : " + val.note + "/10<br><img src=\""+val.poster+"\"/>"));
			//markersfilmBanal.addLayer(filmBanal);
		}
		else if(val.note < 6 && val.note >= 4)
		{
			filmBon.addLayer(L.marker([val.x, val.y], {icon: myIconJaune}).bindPopup("Titre : " + val.titre + "<br>Résumé : " + val.resume + "<br>Réalisateur : " +val.nomRealisateur+ "<br>Durée : " + val.duree + " min<br>Note : " + val.note + "/10<br><img src=\""+val.poster+"\"/>"));
			//markersfilmBon.addLayer(filmBon);
		}
		else if(val.note < 8 && val.note >= 6)
		{
			filmExcellent.addLayer(L.marker([val.x, val.y], {icon: myIconVertCitron}).bindPopup("Titre : " + val.titre + "<br>Résumé : " + val.resume + "<br>Réalisateur : " +val.nomRealisateur+ "<br>Durée : " + val.duree + " min<br>Note : " + val.note + "/10<br><img src=\""+val.poster+"\"/>"));
			//markersfilmExcellent.addLayer(filmExcellent);
		}
		else if(val.note < 10 && val.note >= 8)
		{
			filmChefDoeuvre.addLayer(L.marker([val.x, val.y], {icon: myIconVert}).bindPopup("Titre : " + val.titre + "<br>Résumé : " + val.resume + "<br>Réalisateur : " +val.nomRealisateur+ "<br>Durée : " + val.duree + " min<br>Note : " + val.note + "/10<br><img src=\""+val.poster+"\"/>"));
			//markersfilmChefDoeuvre.addLayer(filmChefDoeuvre);
		}*/

	});
});

function useCatFilms(categorie)
{
	recupEmplacements.complete(function() {
		var tab = [];
		if(categorie == "all")
		{
			tab = tabAllFilms;
		}
		else if(categorie == "court-metrage")
		{
			tab = tabCourtMetrages;
		}
		else if(categorie == "long-metrage")
		{
			tab = tabLongMetrages;
		}
		for(var i = 0; i <tab.length; i++)
		{
			if(tab[i]["note"] == null)
			{
				filmNonNote.addLayer(L.marker([tab[i]["x"], tab[i]["y"]], {icon: myIconNoir}).bindPopup("Titre : " + tab[i]["titre"] + "<br>Résumé : " + tab[i]["resume"] + "<br>Réalisateur : " +tab[i]["nomRealisateur"]+ "<br>Durée : " + tab[i]["duree"] + " min<br>Note : " + tab[i]["note"] + "/10<br><img src=\""+tab[i]["poster"]+"\"/>"));
			}
			else if(tab[i]["note"] < 2)
			{
				filmControverse.addLayer(L.marker([tab[i]["x"], tab[i]["y"]], {icon: myIconRouge}).bindPopup("Titre : " + tab[i]["titre"] + "<br>Résumé : " + tab[i]["resume"] + "<br>Réalisateur : " +tab[i]["nomRealisateur"]+ "<br>Durée : " + tab[i]["duree"] + " min<br>Note : " + tab[i]["note"] + "/10<br><img src=\""+tab[i]["poster"]+"\"/>"));
				//markersfilmControverse.addLayer(filmControverse);
			}
			else if(tab[i]["note"] < 4 && tab[i]["note"] >= 2)
			{
				filmBanal.addLayer(L.marker([tab[i]["x"], tab[i]["y"]], {icon: myIconOrange}).bindPopup("Titre : " + tab[i]["titre"] + "<br>Résumé : " + tab[i]["resume"] + "<br>Réalisateur : " +tab[i]["nomRealisateur"]+ "<br>Durée : " + tab[i]["duree"] + " min<br>Note : " + tab[i]["note"] + "/10<br><img src=\""+tab[i]["poster"]+"\"/>"));
				//markersfilmBanal.addLayer(filmBanal);
			}
			else if(tab[i]["note"] < 6 && tab[i]["note"] >= 4)
			{
				filmBon.addLayer(L.marker([tab[i]["x"], tab[i]["y"]], {icon: myIconJaune}).bindPopup("Titre : " + tab[i]["titre"] + "<br>Résumé : " + tab[i]["resume"] + "<br>Réalisateur : " +tab[i]["nomRealisateur"]+ "<br>Durée : " + tab[i]["duree"] + " min<br>Note : " + tab[i]["note"] + "/10<br><img src=\""+tab[i]["poster"]+"\"/>"));
				//markersfilmBon.addLayer(filmBon);
			}
			else if(tab[i]["note"] < 8 && tab[i]["note"] >= 6)
			{
				filmExcellent.addLayer(L.marker([tab[i]["x"], tab[i]["y"]], {icon: myIconVertCitron}).bindPopup("Titre : " + tab[i]["titre"] + "<br>Résumé : " + tab[i]["resume"] + "<br>Réalisateur : " +tab[i]["nomRealisateur"]+ "<br>Durée : " + tab[i]["duree"] + " min<br>Note : " + tab[i]["note"] + "/10<br><img src=\""+tab[i]["poster"]+"\"/>"));
				//markersfilmExcellent.addLayer(filmExcellent);
			}
			else if(tab[i]["note"] < 10 && tab[i]["note"] >= 8)
			{
				filmChefDoeuvre.addLayer(L.marker([tab[i]["x"], tab[i]["y"]], {icon: myIconVert}).bindPopup("Titre : " + tab[i]["titre"] + "<br>Résumé : " + tab[i]["resume"] + "<br>Réalisateur : " +tab[i]["nomRealisateur"]+ "<br>Durée : " + tab[i]["duree"] + " min<br>Note : " + tab[i]["note"] + "/10<br><img src=\""+tab[i]["poster"]+"\"/>"));
				//markersfilmChefDoeuvre.addLayer(filmChefDoeuvre);
			}
		}
	});
}

useCatFilms("court-metrage");

document.getElementById("allbutton").onclick = function ()
{
	
	filmControverse.clearLayers();
	filmBanal.clearLayers();
	filmBon.clearLayers();
	filmExcellent.clearLayers();
	filmChefDoeuvre.clearLayers();
	filmNonNote.clearLayers();
	useCatFilms("all");
	mymap.invalidateSize();
	
}

document.getElementById("courtmetragebutton").onclick = function ()
{
	filmControverse.clearLayers();
	filmBanal.clearLayers();
	filmBon.clearLayers();
	filmExcellent.clearLayers();
	filmChefDoeuvre.clearLayers();
	filmNonNote.clearLayers();
	useCatFilms("court-metrage");
	mymap.invalidateSize();
}

document.getElementById("longmetragebutton").onclick = function ()
{
	filmControverse.clearLayers();
	filmBanal.clearLayers();
	filmBon.clearLayers();
	filmExcellent.clearLayers();
	filmChefDoeuvre.clearLayers();
	filmNonNote.clearLayers();
	useCatFilms("long-metrage");
	mymap.invalidateSize();
}

recupEmplacements.complete(function() {
	/*mymap.addLayer(markersfilmControverse);
	mymap.addLayer(markersfilmBanal);
	mymap.addLayer(markersfilmBon);
	mymap.addLayer(markersfilmExcellent);
	mymap.addLayer(markersfilmChefDoeuvre);
	mymap.addLayer(markersfilmNonNote);*/
});

// contrôle localisation actuelle 
L.control.locate({
	icon: 'fa fa-map-marker',
	position: 'topright',
	locateOptions: {
		maxZoom: 19
	}
}).addTo(mymap);

// contrôle géocoder (barre de recherche)
var geocoder = L.Control.geocoder().addTo(mymap);

geocoder.markGeocode = function(result) {
	chooseAddr(result.center.lat, result.center.lng);
};

////------------------------------------------------------- Fonctions ------------------------------------------------------------------------------\\\\
function onLocationFound(e) 
{
	onMapClick(e);
}
////------------------------------------------------------- Ajout des évènements à la map ----------------------------------------------------------\\\\

// Evènement onClick
//mymap.on('click', onMapClick);

mymap.on('locationfound', onLocationFound);

//searchLatLong("San Francisco, Mason & California Streets (Nob Hill)");