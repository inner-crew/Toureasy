import someFunction from "./someFunction.js";

mapboxgl.accessToken = 'pk.eyJ1IjoicmVtaXRvczU3IiwiYSI6ImNramsxc3EwaTR2bW4ycm5xZXgwamQ3em0ifQ.LxF1l4i5VksFZHOzuJmqTA';
var troisD;
var beforeMap;
var afterMap;
const urlParams = new URLSearchParams(window.location.search);
var loadingPhase = new loadingBar();

function loadingBar() {
    this.step = 0;
}

loadingBar.prototype.increment = function () {
    this.step++;
    if (this.step === 13) {
        console.log("Les maps sont loads");
        someFunction.mapCharger(beforeMap, afterMap);
    }
}

var init = function () {
    var bounds = [
        [-180, -85],
        [180, 85]
    ];
    troisD = false;
    document.getElementById('comparison-container').innerHTML = `<div id="before" class="map">
<div id="left" class="sidebar flex-center left collapsed">
<div class="sidebar-content rounded-rect flex-center">
<div id="descMonument">
default
</div>
<div id="fleche" class="sidebar-toggle rounded-rect left">
&larr;
</div>
</div>
</div>
</div>
    
    <div id="after" class="map"></div>`;
//<pre id="geoPos"></pre>
    loadingPhase.increment();

    beforeMap = new mapboxgl.Map({
        container: 'before',
        style: 'mapbox://styles/remitos57/ckjk2czpx0sih19ogl1vt6tvz',
        center: [3, 48],
        minZoom: 3,
        pitch: 45,
        maxBounds: bounds
    });
    beforeMap.setRenderWorldCopies(false);

    if (("geolocation" in navigator) && !(urlParams.has('monument'))) {
        navigator.geolocation.getCurrentPosition(position => {
            beforeMap.easeTo({
                center: [position.coords.longitude, position.coords.latitude],
                zoom: 13,
                duration: 1000
            });
        });
    }

    loadingPhase.increment();

    afterMap = new mapboxgl.Map({
        container: 'after',
        style: 'mapbox://styles/mapbox-map-design/ckhqrf2tz0dt119ny6azh975y',
        center: [3, 48],
        zoom: 0,
        minZoom: 3,
        pitch: 45,
        maxBounds: bounds
    });
    afterMap.setRenderWorldCopies(false);

    loadingPhase.increment();

    afterMap.on('load', function () {
        afterMap.addLayer(someFunction.cielSetting);
        afterMap.addSource('mapbox-dem', {
            'type': 'raster-dem',
            'url': 'mapbox://mapbox.mapbox-terrain-dem-v1',
            'tileSize': 512,
            'maxzoom': 14
        });
        loadingPhase.increment();
    });


    beforeMap.on('load', function () {
        beforeMap.addSource('mapbox-dem', {
            'type': 'raster-dem',
            'url': 'mapbox://mapbox.mapbox-terrain-dem-v1',
            'tileSize': 512,
            'maxzoom': 14
        });
        //Ajout du layer deep water pour des océans de giga quality
        beforeMap.addSource('10m-bathymetry-81bsvj', {
            type: 'vector',
            url: 'mapbox://mapbox.9tm8dx88'
        });
        beforeMap.addSource('monuments', {
            type: 'geojson',
            data: null,
            cluster: true,
            clusterMaxZoom: 14,
            clusterRadius: 50,
        });
        beforeMap.addLayer(someFunction.cielSetting);
        beforeMap.addLayer(someFunction.belleMerSetting, 'land-structure-polygon');
        loadingPhase.increment();
    });


    beforeMap.addControl(someFunction.geolocOption, 'top-left'); //Fonction pour ce geolocaliser sur la carte
    beforeMap.addControl(new mapboxgl.NavigationControl(), 'top-left'); //Control bouton haut gauche
    beforeMap.addControl(someFunction.rechercheDeLieuOption, 'top-right'); //Active la box de recherche de lieu

    loadingPhase.increment();

    someFunction.majSelectBoxDesListes();

    loadingPhase.increment();

    //Assemblage des deux map en mode c'est styler
    var container = '#comparison-container';
    var map = new mapboxgl.Compare(beforeMap, afterMap, container, {});

    loadingPhase.increment();

    beforeMap.on('sourcedata', (e) => {
        if (e.isSourceLoaded) {
            loadingPhase.increment();
        } else {
            setTimeout(() => {
                if (e.isSourceLoaded) loadingPhase.increment();
            }, 5000)
        }
    });

    afterMap.on('sourcedata', (e) => {
        if (e.isSourceLoaded) {
            loadingPhase.increment();
        } else {
            setTimeout(() => {
                if (e.isSourceLoaded) loadingPhase.increment();
            }, 5000)
        }
    });
}

window.addEventListener("load", function () {
    init()
})



