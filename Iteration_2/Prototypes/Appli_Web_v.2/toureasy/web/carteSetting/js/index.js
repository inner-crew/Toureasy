//Creation des deux map ------------------------------------------------------------------------------------------------------
mapboxgl.accessToken = 'pk.eyJ1IjoicmVtaXRvczU3IiwiYSI6ImNramsxc3EwaTR2bW4ycm5xZXgwamQ3em0ifQ.LxF1l4i5VksFZHOzuJmqTA';
var beforeMap = new mapboxgl.Map({
    container: 'before',
    style: 'mapbox://styles/remitos57/ckjk2czpx0sih19ogl1vt6tvz',
    center: [0, 0],
    minZoom: 3,
    pitch: 45
});

var afterMap = new mapboxgl.Map({
    container: 'after',
    style: 'mapbox://styles/remitos57/ckjprz1r02jy519qkxyw4f09h',
    center: [0, 0],
    zoom: 0,
    minZoom: 3,
    pitch: 45
});

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
}
afterMap.on('load', function () {
    afterMap.addLayer(cielSetting);
});
function getSunPosition(date) {
    var center = afterMap.getCenter();
    var sunPos = SunCalc.getPosition(
        date || Date.now(),
        center.lat,
        center.lng
    );
    var sunAzimuth = 180 + (sunPos.azimuth * 180) / Math.PI;
    var sunAltitude = 90 - (sunPos.altitude * 180) / Math.PI;
    return [sunAzimuth, sunAltitude];
}




//Ajout du layer deep water pour des océans de giga quality
beforeMap.on('load', function () {
    beforeMap.addSource('10m-bathymetry-81bsvj', {
        type: 'vector',
        url: 'mapbox://mapbox.9tm8dx88'
    });
    beforeMap.addLayer(cielSetting);
    beforeMap.addLayer(
        {
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
        },
        'land-structure-polygon'
    );
});

//Assemblage des deux map en mode c'ets styler
var container = '#comparison-container';
var map = new mapboxgl.Compare(beforeMap, afterMap, container, {});

//La géolocalisation....
var geolocOption = new mapboxgl.GeolocateControl({positionOptions: {enableHighAccuracy: true}, trackUserLocation: true});
beforeMap.addControl(geolocOption, 'top-left');

beforeMap.addControl(new mapboxgl.NavigationControl(), 'top-left'); //Control bouton haut gauche

beforeMap.addControl(    //Active la box de recherche de lieu
    new MapboxGeocoder({
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
    }), 'top-right'
);

beforeMap.on('mousemove', function (e) {document.getElementById('geoPos').innerHTML = JSON.stringify(e.lngLat.wrap());}); //actualise la position géographique de la souris

var btn = document.querySelector('#btn');
btn.addEventListener('click', afficherCoord);

function afficherCoord(){
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(position => {
            console.log(position.coords.latitude, position.coords.longitude);
        });
    } else { console.log("Euh erreur mdr"); }
}


// add markers to map
fetch("../web/carteSetting/data/tmp/monumentsDeLaDataBase.json")
    .then(response => response.json())
    .then(data => {
        data.features.forEach(function(marker) {

            // create a HTML element for each feature
            var el = document.createElement('div');
            el.className = 'marker';

            // make a marker for each feature and add to the map
            new mapboxgl.Marker(el)
                .setLngLat(marker.geometry.coordinates)
                .setPopup(new mapboxgl.Popup({ offset: 25 }) // add popups
                    .setHTML('<h3>' + marker.properties.title + '</h3><p>' + marker.properties.description + '</p>'))
                .addTo(beforeMap);
        });
    });
