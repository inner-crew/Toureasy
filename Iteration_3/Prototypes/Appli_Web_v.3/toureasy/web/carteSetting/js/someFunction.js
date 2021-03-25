mapboxgl.accessToken = 'pk.eyJ1IjoicmVtaXRvczU3IiwiYSI6ImNramsxc3EwaTR2bW4ycm5xZXgwamQ3em0ifQ.LxF1l4i5VksFZHOzuJmqTA';
var premierFonction = function () {
    console.log("BlaBla");
}

function getSunPosition(date) {
    var sunPos = SunCalc.getPosition(
        date || Date.now(),
        3,
        48
    );
    var sunAzimuth = 180 + (sunPos.azimuth * 180) / Math.PI;
    var sunAltitude = 90 - (sunPos.altitude * 180) / Math.PI;
    return [sunAzimuth, sunAltitude];
};
var cielSetting = {
    'id': 'sky',
    'type': 'sky',
    'paint': {
        'sky-opacity': [
            'interpolate',
            ['linear'],
            ['zoom'],
            0,
            0,
            5,
            0.3,
            8,
            1
        ],
        // set up the sky layer for atmospheric scattering
        'sky-type': 'atmosphere',
        // explicitly set the position of the sun rather than allowing the sun to be attached to the main light source
        'sky-atmosphere-sun': getSunPosition(),
        // set the intensity of the sun as a light source (0-100 with higher values corresponding to brighter skies)
        'sky-atmosphere-sun-intensity': 5
    }
};
var belleMerSetting = {
    'id': '10m-bathymetry-81bsvj',
    'type': 'fill',
    'source': '10m-bathymetry-81bsvj',
    'source-layer': '10m-bathymetry-81bsvj',
    'layout': {},
    'paint': {
        'fill-outline-color': 'hsla(337, 82%, 62%, 0)',
// cubic bezier is a four point curve for smooth and precise styling
// adjust the points to change the rate and intensity of interpolation
        'fill-color': [
            'interpolate',
            ['cubic-bezier', 0, 0.5, 1, 0.5],
            ['get', 'DEPTH'],
            200,
            '#78bced',
            9000,
            '#15659f'
        ]
    }
};

var geolocOption = new mapboxgl.GeolocateControl({
    positionOptions: {enableHighAccuracy: true},
    trackUserLocation: false
});

var rechercheDeLieuOption = new MapboxGeocoder({
    accessToken: mapboxgl.accessToken,
    flyTo: {
        bearing: 0,
        speed: 2,
        curve: 1,
    },
    marker: {
        color: 'orange'
    },
    mapboxgl: mapboxgl
});

var getImageUrlId = function () {
    let res = new Array();
    getJsonFile(getCookie("token")).then(json => {
        json.monumentsPrives.forEach(unMonumentPrive => {
            res.push({url: ("../"+unMonumentPrive.urlImage), id: unMonumentPrive.nomImage})
        });
    });
    getJsonFile("monumentPublique").then(json => {
        json.features.forEach(unMonumentPublique => {
            console.log(unMonumentPublique);
            res.push({url: ("../"+unMonumentPublique.properties.urlImage), id: unMonumentPublique.properties.nomImage})
        });
    });
    return res;
}

var afficherCoordonner = function () {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(position => {
            console.log(position.coords.latitude, position.coords.longitude);
        });
    } else {
        console.log("Euh erreur mdr");
    }
}

var getJsonFile = function (nom) { //Revoir une promesse d'un fichier json
    let path;
    if (nom === "monumentPublique") path = `../web/carteSetting/data/${nom}.json`;
    else path = `../web/carteSetting/data/tmp/${nom}.json`;
    return fetch(path)
        .then(response => response.json())
        .then(data => {
            return data;
        });
}

//convertir un json de monuments de la database pour la rendre compatible avec l'API MapBox
var convertirMonumentsEnFeature = function(jsonDeMonuments) {
    let res = {
        features: []
    };
    jsonDeMonuments.forEach(unMonument => {
        res.features.push({
            "type": "Feature",
            "geometry": {
                "type": "Point",
                "coordinates": [
                    unMonument.longitude,
                    unMonument.latitude
                ]
            },
            "properties": {
                "title": unMonument.nomMonum,
                "description": unMonument.descLongue,
                "urlImage": unMonument.urlImage,
                "nomImage": unMonument.nomImage
            }
        })
    });
    return res;
}

var getCookie = function (cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}


var majSelectBoxDesListes = function() {
    let selectBox = document.getElementById("monumentAfficher");
    selectBox.innerHTML = `<option value="publique" selected>Monuments certifiés</option>
  <option value="mesMonuments">Vos contribution</option>`
    getJsonFile(getCookie("token")).then(json => {
        json.Listes.forEach(uneListe => {
            selectBox.innerHTML += `<option value="${uneListe.liste.idListe}">${uneListe.liste.nom}</option>`;
        });
    });
}


export default {
    cielSetting: cielSetting,
    belleMerSetting: belleMerSetting,
    premierFonction: premierFonction,
    geolocOption: geolocOption,
    rechercheDeLieuOption: rechercheDeLieuOption,
    getImageUrlId: getImageUrlId,
    afficherCoordonner: afficherCoordonner,
    getJsonFile: getJsonFile,
    convertirMonumentsEnFeature: convertirMonumentsEnFeature,
    getCookie: getCookie,
    majSelectBoxDesListes: majSelectBoxDesListes
}