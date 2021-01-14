<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Prototype map</title>
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.0.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.0.1/mapbox-gl.css" rel="stylesheet" />
</head>
<body>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/suncalc/1.8.0/suncalc.min.js"></script> <!--Le script pour calculer la pos du soleil pour faire le ciel-->
<style type="text/css">
    #geoPos {
        display: block;
        position: relative;
        margin: 0px auto;
        width: max-content;
        padding: 10px;
        border: none;
        border-radius: 3px;
        font-size: 12px;
        text-align: center;
        color: #000000;
        background-color: rgba(32, 221, 118, .5);

    }

    body {
        overflow: hidden;
        margin: 0;
        padding: 0;
    }

    body * {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .map {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
    }
    #btn {
        position: absolute;
        font-family: 'Open Sans', sans-serif;
        bottom: 0;
    }
    .marker {
        background-image: url('markOmbre.png');
        background-size: cover;
        width: 90px;
        height: 85px;
        cursor: pointer;
    }
    .mapboxgl-popup {
        max-width: 200px;
    }

    .mapboxgl-popup-content {
        text-align: center;
        font-family: 'Open Sans', sans-serif;
    }


</style>
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-compare/v0.4.0/mapbox-gl-compare.js"></script> <!-- le script qui fait fonctionner le swap entre les 2 maps-->
<link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-compare/v0.4.0/mapbox-gl-compare.css" type="text/css"/> <!--le css qui gère la transition des 2 maps-->

<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.min.js"></script>     <!--le script de la recherche de lieu (geocoder)-->
<link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.css" type="text/css"/> <!--css de la recherche de lieu-->

<div id="comparison-container">
    <div id="before" class="map"></div>
    <pre id="geoPos"></pre>
    <div id="after" class="map"></div>
</div>
<input type= "image" id="btn" src="./mark.png" width="100px" height="200px"/>

<?php
try {
    $dsn = 'mysql:host=localhost;dbname=toureasy';
    $db = new PDO($dsn, 'root', '', [
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES=>false,
        PDO::ATTR_STRINGIFY_FETCHES=>false])
    ;
    echo "Succès";

} catch (\PDOException $e){
    echo $e->getMessage();
}

$ttLesMonuments = $db->prepare('SELECT * FROM monument');
$ttLesMonuments->execute();

$featureDeMerde = array();

while ($row = $ttLesMonuments->fetch(PDO::FETCH_OBJ)) {
    $coord = [$row->latitude, $row->longitude];
    $titre = $row->nomMonum;
    $description = $row->descCourte;
    $monument = array('type' => 'Feature', 'geometry' => array('type' => 'Point', 'coordinates' => $coord), 'properties' => array('title' => $titre, 'description' => $description));
    array_push($featureDeMerde, $monument);
}
$jsonFile = array('type' => 'FeatureCollection', 'features' => $featureDeMerde);
$json = json_encode($jsonFile);
$bytes = file_put_contents("testJson.json", $json);
echo "The number of bytes written are $bytes.";
?>

<script>
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
//  beforeMap.addControl(new mapboxgl.FullscreenControl(), 'top-left'); //Fullscreen feature (cassé)
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


    var lesMonuments = {
        type: 'FeatureCollection',
        features: [{
            type: 'Feature',
            geometry: {
                type: 'Point',
                coordinates: [6.66761, 48.8025]
            },
            properties: {
                title: "Maison d'arthur",
                description: "C'est bien celle çi, bien présente, oui la maison d'arthur"
            }
        },
            {
                type: 'Feature',
                geometry: {
                    type: 'Point',
                    coordinates: [6.161, 48.6828]
                },
                properties: {
                    title: "IUT Nancy-Charlemagne",
                    description: "Un cauchemar..."
                }
            }]
    };
    // add markers to map
    fetch("testJson.json")
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

</script>



</body>
</html>