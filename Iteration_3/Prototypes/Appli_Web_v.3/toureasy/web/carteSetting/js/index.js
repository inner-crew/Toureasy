import someFunction from "./someFunction.js";

var troisD = true;

var afficherLesMaps = function () {
    //Creation des deux map ------------------------------------------------------------------------------------------------------
    mapboxgl.accessToken = 'pk.eyJ1IjoicmVtaXRvczU3IiwiYSI6ImNramsxc3EwaTR2bW4ycm5xZXgwamQ3em0ifQ.LxF1l4i5VksFZHOzuJmqTA';
    document.getElementById('comparison-container').innerHTML = `<div id="before" class="map"></div>
    <pre id="geoPos"></pre>
    <div id="after" class="map"></div>`;

    var beforeMap = new mapboxgl.Map({
        container: 'before',
        style: 'mapbox://styles/remitos57/ckjk2czpx0sih19ogl1vt6tvz',
        center: [3, 48],
        minZoom: 3,
        pitch: 45
    });

    var afterMap = new mapboxgl.Map({
        container: 'after',
        style: 'mapbox://styles/mapbox-map-design/ckhqrf2tz0dt119ny6azh975y',
        center: [3, 48],
        zoom: 0,
        minZoom: 3,
        pitch: 45
    });


    afterMap.on('load', function () {
        afterMap.addLayer(someFunction.cielSetting);
        if (troisD) {
            afterMap.addSource('mapbox-dem', {
                'type': 'raster-dem',
                'url': 'mapbox://mapbox.mapbox-terrain-dem-v1',
                'tileSize': 512,
                'maxzoom': 14
            });
            afterMap.setTerrain({'source': 'mapbox-dem', 'exaggeration': 1.5});
        }
    });


    beforeMap.on('load', function () {
        //Ajout du layer deep water pour des océans de giga quality
        beforeMap.addSource('10m-bathymetry-81bsvj', {
            type: 'vector',
            url: 'mapbox://mapbox.9tm8dx88'
        });
        beforeMap.addLayer(someFunction.cielSetting);
        beforeMap.addLayer(someFunction.belleMerSetting, 'land-structure-polygon');


        function addImages(map, images) {
            const addImage = (map, id, url) => {
                return new Promise((resolve, reject) => {
                    map.loadImage(url, (error, image) => {
                        if (error) {
                            reject(error);
                            return;
                        }
                        map.addImage(id, image);
                        resolve(image);
                    });
                });
            }
            const promises = images.map(imageData => addImage(map, imageData.id, imageData.url));
            /*return fetch(Promise.all(promises)).then(data => {
                return data;
            });*/
            return Promise.all(promises);
        }
        //someFunction.getImageUrlId()
        addImages(beforeMap, [
            {url: "../web/img/02167290.JPG", id: "02167290.JPG"},
            {url: "../web/img/02167291.JPG", id: "02167291.JPG"}
        ]).then(() => {
            beforeMap.addSource('monuments', {
                type: 'geojson',
                data: null,
                cluster: true,
                clusterMaxZoom: 14,
                clusterRadius: 50,
            });

            beforeMap.addLayer({
                id: 'clusters',
                type: 'circle',
                source: 'monuments',
                filter: ['has', 'point_count'],
                paint: {
                    'circle-color': [
                        'step',
                        ['get', 'point_count'],
                        '#51bbd6',
                        100,
                        '#f1f075',
                        750,
                        '#f28cb1'
                    ],
                    'circle-radius': [
                        'step',
                        ['get', 'point_count'],
                        20,
                        100,
                        30,
                        750,
                        40
                    ]
                }
            });
            beforeMap.addLayer({
                id: 'cluster-count',
                type: 'symbol',
                source: 'monuments',
                filter: ['has', 'point_count'],
                layout: {
                    'text-field': '{point_count_abbreviated}',
                    'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
                    'text-size': 12
                }
            });

            beforeMap.addLayer({
                id: 'unclustered-point',
                type: 'symbol',
                source: 'monuments',
                filter: ['!', ['has', 'point_count']],
                layout: {
                    'icon-image': ["get", "type"],
                    'icon-size': 0.5,
                    'icon-allow-overlap': true,
                },
            });

            // inspect a cluster on click
            beforeMap.on('click', 'clusters', function (e) {
                var features = beforeMap.queryRenderedFeatures(e.point, {
                    layers: ['clusters']
                });
                var clusterId = features[0].properties.cluster_id;
                beforeMap.getSource('monuments').getClusterExpansionZoom(
                    clusterId,
                    function (err, zoom) {
                        if (err) {
                            return;
                        }
                        beforeMap.easeTo({
                            center: features[0].geometry.coordinates,
                            zoom: zoom
                        });
                    }
                );
            });

// When a click event occurs on a feature in
// the unclustered-point layer, open a popup at
// the location of the feature, with
// description HTML from its properties.
            beforeMap.on('click', 'unclustered-point', function (e) {
                var coordinates = e.features[0].geometry.coordinates;
// Ensure that if the map is zoomed out such that
// multiple copies of the feature are visible, the
// popup appears over the copy being pointed to.
                while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                    coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                }

                new mapboxgl.Popup()
                    .setLngLat(coordinates)
                    .setHTML(
                        e.features[0].properties.title
                    )
                    .addTo(beforeMap);
            });


//La géolocalisation....

            beforeMap.addControl(someFunction.geolocOption, 'top-left'); //Fonction pour ce geolocaliser sur la carte

            beforeMap.addControl(new mapboxgl.NavigationControl(), 'top-left'); //Control bouton haut gauche

            beforeMap.addControl(someFunction.rechercheDeLieuOption, 'top-right'); //Active la box de recherche de lieu

            beforeMap.on('mousemove', function (e) {
                document.getElementById('geoPos').innerHTML = JSON.stringify(e.lngLat.wrap());
            }); //actualise la position géographique de la souris

            document.querySelector('#btn').addEventListener('click', someFunction.afficherCoordonner);  //Afficher ses coordonner dans la console


            //Affiche les monuments à partir d'un fichier json
            var mark = [];

            function afficherMonument(json) {
                beforeMap.getSource('monuments').setData(json);
                /*json.features.forEach(function (marker) {
                    // create a HTML element for each feature
                    var el = document.createElement('div');
                    el.className = 'marker';

                    // make a marker for each feature and add to the map
                    mark.push(new mapboxgl.Marker(el)
                        .setLngLat(marker.geometry.coordinates)
                        .setPopup(new mapboxgl.Popup({offset: 25}) // add popups
                            .setHTML('<h3>' + marker.properties.title + '</h3><p>' + marker.properties.description + '</p>'))
                        .addTo(beforeMap));
                });*/
            }

            someFunction.majSelectBoxDesListes();
            document.getElementById("monumentAfficher").onchange = (e) => {
                if (mark != null) mark.forEach(unMark => unMark.remove());
                switch (e.target.value) {
                    case ('publique') :
                        someFunction.getJsonFile("monumentPublique").then(json => {
                            afficherMonument(json);
                        });
                        break;
                    case('mesMonuments') :
                        someFunction.getJsonFile(someFunction.getCookie("token")).then(json => {
                            let tmp = someFunction.convertirMonumentsEnFeature(json.monumentsPrives);
                            let tmp2 = someFunction.convertirMonumentsEnFeature(json.monumentsPubliques);
                            tmp2.features.forEach(unFeature => {
                                tmp.features.push(unFeature);
                            })
                            afficherMonument(tmp);
                        });
                        break;
                    default :
                        let idDeLaListe = e.target.value;
                        someFunction.getJsonFile(someFunction.getCookie("token")).then(json => {
                            var lesMonuments = [];
                            var listesDesIdMonuments = [];
                            json.Listes.forEach(uneListe => {
                                if (uneListe.liste.idListe.toString() === idDeLaListe.toString()) {
                                    uneListe.assosiation.forEach(uneAssociation => {
                                        listesDesIdMonuments.push(uneAssociation.idMonument);
                                    });
                                }
                            });
                            let ttLesMonuments = json.monumentsPrives;
                            json.monumentsPubliques.forEach(unMonument => {
                                ttLesMonuments.push(unMonument);
                            });
                            ttLesMonuments.forEach(unMonument => {
                                listesDesIdMonuments.forEach(unId => {
                                    if (unId === unMonument.idMonument) lesMonuments.push(unMonument);
                                });
                            });
                            afficherMonument(someFunction.convertirMonumentsEnFeature(lesMonuments));
                        });
                        break;
                }
            }

            someFunction.getJsonFile("monumentPublique").then(json => {
                afficherMonument(json);
            });
        });

        //Assemblage des deux map en mode c'est styler
        var container = '#comparison-container';
        var map = new mapboxgl.Compare(beforeMap, afterMap, container, {});

    });
        /*addImages(beforeMap, [
            {url: "../web/img/02167290.JPG", id: "02167290.JPG"}
        ]);*/

}

window.addEventListener("load", function () {
    afficherLesMaps();
    document.getElementById("3dSwitch").addEventListener("change", function () {
        if (this.checked) troisD = true;
        else troisD = false
        afficherLesMaps();
    })
})





