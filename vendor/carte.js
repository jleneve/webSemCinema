////--------------------------------------------------Déclaration des variables globales--------------------------------------------------------------\\\\

//groupe contenant les marqueurs liées aux parcelles
/*var parcelles = L.markerClusterGroup({
	iconCreateFunction: function(cluster) {
        var icon = markersParcelles._defaultIconCreateFunction(cluster);
        icon.options.className += '-parcelles-group';
        return icon;
    }
});

//groupe contenant les marqueurs liées aux ruchers
var ruchers = L.markerClusterGroup({
	iconCreateFunction: function(cluster) {
        var icon = markersRuchers._defaultIconCreateFunction(cluster);
        icon.options.className += '-ruchers-group';
        return icon;
    }
});*/

//markercluster
/*var markersParcelles = L.markerClusterGroup();
var markersRuchers = L.markerClusterGroup();*/

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
/*var overlayMaps = {
	"Parcelles": parcelles,
	"Ruchers": ruchers
}*/

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
	
	placementMarqueur(lat, lng);
	MAJ_champs_Emplacement(lat, lng);
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
function searchLatLong()
{
	if(document.getElementById('addr1').value != '' && document.getElementById("addr2").value != '')
	{
		$.getJSON('https://nominatim.openstreetmap.org/?format=json&addressdetails=1&q=' + document.getElementById("addr1").value + ' ' + document.getElementById("addr2").value + '&limit=1', function(data) {
			$.each(data, function(key, val) {
				var addr1 = window.document.getElementById("addr1");
				var addr2 = window.document.getElementById("addr2");
				window.document.getElementById("lat").value = val.lat;
				window.document.getElementById("long").value = val.lon;

				if (val.address.house_number && val.address.road) {
					addr1.value = val.address.house_number + ", " + val.address.road;
				}
				else if (val.address.road) {
					addr1.value = val.address.road;
				}
				else if (val.address.pedestrian) {
					addr1.value = val.address.pedestrian;
				}
				else {
					val.value = "Adresse non trouvée";
				}

				if (val.address.village && val.address.country) {
					if(val.address.postcode)
						addr2.value = val.address.postcode + ", " + val.address.village + ", " + val.address.country;
					else
						addr2.value = val.address.village + ", " + val.address.country; 
				}
				else if(val.address.town && val.address.country) {
					if(val.address.postcode)
						addr2.value = val.address.postcode + ", " + val.address.town + ", " + val.address.country;
					else
						addr2.value = val.address.town + ", " + val.address.country;
		 		}
				else if(val.address.city && val.address.country) {
					if(val.address.postcode)
						addr2.value = val.address.postcode + ", " + val.address.city + ", " + val.address.country;
					else
						addr2.value = val.address.city + ", " + val.address.country;
		 		}
		 		else
		 			addr2.value = val.address.county + ", " + val.address.country;

		 		if(val.address.city){
		 			MAJ_nom_ville(val.address.city, val.address.postcode);
		 		}
		 		else if(val.address.town){
		 			MAJ_nom_ville(val.address.town, val.address.postcode);
		 		}
		 		else if(val.address.village){
		 			MAJ_nom_ville(val.address.village, val.address.postcode);
		 		}
		 		else 
		 			MAJ_nom_ville(val.address.county, val.address.postcode);
	 			placementMarqueur(val.lat, val.lon);
			});
		});
	}
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
	layers: [defaut],
	loadingControl: true
});

////------------------------------------------------------- Ajout des contrôleurs à la map ----------------------------------------------------------\\\\

// contrôle layers
L.control.layers(baseMaps, null, {position: 'bottomleft'}).addTo(mymap);

// contrôle échelle
L.control.scale().addTo(mymap);

// contrôle plein écran
L.control.fullscreen({
	position: 'bottomright',
	forceSeparateButton: true
}).addTo(mymap);

mymap.on('click', onMapClick);