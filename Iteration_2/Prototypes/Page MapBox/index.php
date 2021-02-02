<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Prototype map</title>
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />

    <script src="./js/API_MapBox/main_mapbox-gl.js"></script>                                       <!--Le js principal de la l'api mapBox-->
    <script type="text/javascript" src="./js/API_MapBox/soleil_suncalc.min.js"></script>            <!--Le script pour calculer la pos du soleil pour faire le ciel-->
    <script src="./js/API_MapBox/doubleMap_mapbox-gl-compare.js"></script>                          <!-- le script qui fait fonctionner le swap entre les 2 maps-->
    <script src="./js/API_MapBox/barreDeRecherche_mapbox-gl-geocoder.min.js"></script>              <!--le script de la recherche de lieu (geocoder)-->
    <script src="./js/index.js"></script>                                                           <!--le script de toureasy-->

    <link href="./css/main_mapbox-gl.css" rel="stylesheet"/>                                        <!--Le css principal de la l'api mapBox-->
    <link href="./css/index.css" rel="stylesheet"/>                                                 <!--Le css principal de la page web-->
    <link rel="stylesheet" href="./css/doubleMap_mapbox-gl-compare.css" type="text/css"/>           <!--le css qui gère la transition des 2 maps-->
    <link rel="stylesheet" href="./css/barreDeRecherche_mapbox-gl-geocoder.css" type="text/css"/>   <!--css de la recherche de lieu-->
</head>

<body>

<div id="comparison-container">
    <div id="before" class="map"></div>
    <pre id="geoPos"></pre>
    <div id="after" class="map"></div>
</div>
<input type= "image" id="btn" src="data/images/mark.png" width="100px" height="200px"/>

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
$bytes = file_put_contents("./data/tmp/monumentsDeLaDataBase.json", $json);
echo "The number of bytes written are $bytes.";
?>

<script src="./js/index.js"></script>   <!--le script de toureasy (il est lourd)-->

</body>
</html>