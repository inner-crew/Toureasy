<?php
$token = $_POST['token'];
$fav = $_POST['fav'];

require_once __DIR__ . '/../../vendor/autoload.php';
\toureasy\database\Eloquent::start(__DIR__ . '/../../src/conf/conf.ini');

$monument = \toureasy\models\Monument::getMonumentByToken($token);

$membre = \toureasy\models\Membre::getMembreByToken($_COOKIE['token']);

/*if ($fav === "true") {
    $favori = new \toureasy\models\Favoris();
    $favori->idMonument = $monument->idMonument;
    $favori->idMembre = $membre->idMembre;
    $favori->save();
} else {
    $favori = \toureasy\models\Favoris::query()->where([['idMonument','=',$monument->idMonument],['idMembre','=',$membre->idMembre]]);
    $favori->delete();
}*/

if ($fav === "true") {
    $membre->monumentsFavoris()->attach([$monument->idMonument]);
} else {
    $membre->monumentsFavoris()->detach([$monument->idMonument]);
}


