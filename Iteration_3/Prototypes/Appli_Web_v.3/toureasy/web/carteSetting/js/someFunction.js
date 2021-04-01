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
var batimentExtruction = {
    'id': '3d-buildings',
    'source': 'composite',
    'source-layer': 'building',
    'filter': ['==', 'extrude', 'true'],
    'type': 'fill-extrusion',
    'minzoom': 13,
    'paint': {
        'fill-extrusion-color': '#aaa',

// use an 'interpolate' expression to add a smooth transition effect to the
// buildings as the user zooms in
        'fill-extrusion-height': [
            'interpolate',
            ['linear'],
            ['zoom'],
            13,
            0,
            15.05,
            ['get', 'height']
        ],
        'fill-extrusion-base': [
            'interpolate',
            ['linear'],
            ['zoom'],
            13,
            0,
            15.05,
            ['get', 'min_height']
        ],
        'fill-extrusion-opacity': 0.6
    }
}
var clusterLayer = {
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
}
var nbMonumentInClusterLayer = {
    id: 'cluster-count',
    type: 'symbol',
    source: 'monuments',
    filter: ['has', 'point_count'],
    layout: {
        'text-field': '{point_count_abbreviated}',
        'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
        'text-size': 12
    }
}
var unMonumentLayer = {
    id: 'unclustered-point',
    type: 'symbol',
    source: 'monuments',
    filter: ['!', ['has', 'point_count']],
    layout: {
        'icon-image': ["get", "nomImage"],
        'icon-size': 0.15,
        'icon-allow-overlap': true,
    },
}


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

var getImageUrlId = async function () {
    let jsonPublique = await getJsonFile("monumentPublique")
    let jsonPriver = await getJsonFile(getCookie("token"))
    let res = [];
    jsonPublique.features.forEach(unMonumentPublique => {
        res.push({url: ("../" + unMonumentPublique.properties.urlImage), id: unMonumentPublique.properties.nomImage})
    });
    jsonPriver.monumentsPrives.forEach(unMonumentPrive => {
        res.push({url: ("../" + unMonumentPrive.urlImage), id: unMonumentPrive.nomImage})
    });
    return res;
}

var addImages = function(map, images) {
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
    return Promise.all(promises);
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
        .then(response => {
            if (response.ok) {
                return response.json()
            } else {
                console.log('response error : ' +
                    response.status + ' ' +
                    response.statusText)
            }
        })
        .then(data => {
            return data;
        })
        .catch(error => {
            console.log('network error : ' + error);
        });
}

//convertir un json de monuments de la database pour la rendre compatible avec l'API MapBox
var convertirMonumentsEnFeature = function (jsonDeMonuments) {
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


var majSelectBoxDesListes = function () {
    let selectBox = document.getElementById("monumentAfficher");
    selectBox.innerHTML = `<option value="publique" selected>Monuments certifiés</option>
  <option value="mesMonuments">Vos contribution</option>`
    getJsonFile(getCookie("token")).then(json => {
        json.Listes.forEach(uneListe => {
            selectBox.innerHTML += `<option value="${uneListe.liste.idListe}">${uneListe.liste.nom}</option>`;
        });
    });
}

var onClickCluster = function (cluster, map) {
    var features = map.queryRenderedFeatures(cluster.point, {
        layers: ['clusters']
    });
    var clusterId = features[0].properties.cluster_id;
    map.getSource('monuments').getClusterExpansionZoom(
        clusterId,
        function (err, zoom) {
            if (err) {
                return;
            }
            map.easeTo({
                center: features[0].geometry.coordinates,
                zoom: zoom
            });
        }
    );
}

var onClickMonument = function (unClusteredPoint, map) {
    var coordinates = unClusteredPoint.features[0].geometry.coordinates;
// Ensure that if the map is zoomed out such that
// multiple copies of the feature are visible, the
// popup appears over the copy being pointed to.
    while (Math.abs(unClusteredPoint.lngLat.lng - coordinates[0]) > 180) {
        coordinates[0] += unClusteredPoint.lngLat.lng > coordinates[0] ? 360 : -360;
    }

    /*new mapboxgl.Popup()
        .setLngLat(coordinates)
        .setHTML(
            unClusteredPoint.features[0].properties.title
        )
        .addTo(map);*/
    toggleSideBar(map, unClusteredPoint.features[0].properties);
}

var quoiAfficherSurLaMap = function(e, map) {
    switch (e.target.value) {
        case ('publique') :
            getJsonFile("monumentPublique").then(json => {
                afficherMonument(json, map);
            });
            break;
        case('mesMonuments') :
            getJsonFile(getCookie("token")).then(json => {
                let tmp = convertirMonumentsEnFeature(json.monumentsPrives);
                let tmp2 = convertirMonumentsEnFeature(json.monumentsPubliques);
                tmp2.features.forEach(unFeature => {
                    tmp.features.push(unFeature);
                })
                afficherMonument(tmp, map);
            });
            break;
        default :
            let idDeLaListe = e.target.value;
            getJsonFile(getCookie("token")).then(json => {
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
                afficherMonument(convertirMonumentsEnFeature(lesMonuments), map);
            });
            break;
    }
}

var afficherMonument = function(json, map) {
    map.getSource('monuments').setData(json);
}

var genererMonumentDescr = function (monument) {
    let html = `
        <h1>${monument.title}</h1>
        <p>${monument.description}</p>
        <img src="../${monument.urlImage}" alt="${monument.nomImage}">
    `
    return html;
}

var toggleSideBar = function (map, leMonument) {
    let id = "left"
    var elem = document.getElementById(id);
    var classes = elem.className.split(' ');
    var collapsed = classes.indexOf('collapsed') !== -1;

    var padding = {};

    if (collapsed) {
// Remove the 'collapsed' class from the class list of the element, this sets it back to the expanded state.
        classes.splice(classes.indexOf('collapsed'), 1);

        padding[id] = 300; // In px, matches the width of the sidebars set in .sidebar CSS class
        map.easeTo({
            padding: padding,
            duration: 1000 // In ms, CSS transition duration property for the sidebar matches this value
        });
        console.log(genererMonumentDescr(leMonument));
        document.getElementById("descMonument").innerHTML = genererMonumentDescr(leMonument)
    } else {
        setInterval(document.getElementById("descMonument").innerHTML = "<p>Monument vide</p>", 1000);
        padding[id] = 0;
// Add the 'collapsed' class to the class list of the element
        classes.push('collapsed');

        map.easeTo({
            padding: padding,
            duration: 1000
        });
    }

// Update the class list on the element
    elem.className = classes.join(' ');
}

var mapCharger = function (streetMap, sateliteMap) {
    document.getElementById("fleche").onclick = function () {
        toggleSideBar(streetMap);
    };
    document.getElementById("3dSwitch").checked = false;
    document.getElementById("3dSwitch").addEventListener("change", function () {
        if (this.checked) {
            streetMap.addLayer(batimentExtruction);
            streetMap.setTerrain({'source': 'mapbox-dem', 'exaggeration': 1.5});
            sateliteMap.setTerrain({'source': 'mapbox-dem', 'exaggeration': 1.5});
        } else {
            streetMap.removeLayer('3d-buildings');
            streetMap.setTerrain(null);
            sateliteMap.setTerrain(null);
        }
    });
    getImageUrlId().then(data => {
        addImages(streetMap, data).then(() => {
            console.log("Image ajouter");
        });
    });

    streetMap.addLayer(clusterLayer);
    streetMap.addLayer(nbMonumentInClusterLayer);
    streetMap.addLayer(unMonumentLayer);
    streetMap.on('click', 'clusters', function (e) {
        onClickCluster(e, streetMap)
    });
    streetMap.on('click', 'unclustered-point', function (e) {
        onClickMonument(e, streetMap)
    });
    majSelectBoxDesListes();
    document.getElementById("monumentAfficher").onchange = (e) => {
        quoiAfficherSurLaMap(e, streetMap);
    }
    getJsonFile("monumentPublique").then(json => {
        afficherMonument(json, streetMap);
    });
}


export default {
    cielSetting: cielSetting,
    belleMerSetting: belleMerSetting,
    batimentExtruction: batimentExtruction,
    premierFonction: premierFonction,
    geolocOption: geolocOption,
    rechercheDeLieuOption: rechercheDeLieuOption,
    getImageUrlId: getImageUrlId,
    afficherCoordonner: afficherCoordonner,
    getJsonFile: getJsonFile,
    convertirMonumentsEnFeature: convertirMonumentsEnFeature,
    getCookie: getCookie,
    majSelectBoxDesListes: majSelectBoxDesListes,
    mapCharger: mapCharger
}