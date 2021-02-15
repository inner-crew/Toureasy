<?php

use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use toureasy\controller\Controller;
use toureasy\database\Eloquent;

require_once __DIR__ . '/vendor/autoload.php';

$c = new Container(['settings'=>['displayErrorDetails'=>true]]);
$app = new App($c);

Eloquent::start(__DIR__ . '/src/conf/conf.ini');

// page d'accueil de Toureasy
$app->get('/', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayHome($rq,$rs,$args);
})->setName('home');

// page de connexion de Toureasy
$app->get('/connexion[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayConnexion($rq,$rs,$args);
})->setName('connexion');

$app->post('/connexion[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->postConnexion($rq,$rs,$args);
});

// page d'inscription de Toureasy
$app->get('/inscription[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayInscription($rq,$rs,$args);
})->setName('inscription');

// page de navigation sur la carte
$app->get('/map[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayMap($rq,$rs,$args);
})->setName('map');

$app->get('/mes-listes[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayMesListes($rq,$rs,$args);
})->setName('mes-listes');

$app->get('/mes-listes/cree[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayAjouterListe($rq,$rs,$args);
})->setName('create-liste');

$app->post('/mes-listes/cree[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->postAjouterListe($rq,$rs,$args);
});

$app->get('/mes-listes/{token}[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayDetailListe($rq,$rs,$args);
})->setName('detail-liste');

$app->get('/mes-listes/monumentPrivate/{token}[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayDetailMonument($rq,$rs,$args);
})->setName('detail-monument');

// page du formulaire d'ajout d'un monument
$app->get('/ajouter-monument[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayAjouterMonument($rq,$rs,$args);
})->setName('ajoutMonument');

$app->post('/ajouter-monument[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->postAjouterMonument($rq,$rs,$args);
});

// page de contact
$app->get('/contact[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayContact($rq,$rs,$args);
})->setName('contact');

// page 'à propos' de Toureasy
$app->get('/about-us[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayAboutUs($rq,$rs,$args);
})->setName('about-us');

$app->run();