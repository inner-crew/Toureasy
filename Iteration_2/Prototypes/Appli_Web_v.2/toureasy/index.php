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



// page de navigation sur la carte
$app->get('/map[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayMap($rq,$rs,$args);
})->setName('map');



$app->get('/mon-espace[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayMonEspace($rq,$rs,$args);
})->setName('mes-listes');

$app->get('/mon-espace/cree[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayAjouterListe($rq,$rs,$args);
})->setName('create-liste');

$app->post('/mon-espace/cree[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->postAjouterListe($rq,$rs,$args);
})->setName("createListe");

$app->get('/mon-espace/{token}[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayDetailListe($rq,$rs,$args);
})->setName('detail-liste');

$app->post('/mon-espace/{token}[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->postAjouterMonumentListe($rq,$rs,$args);
});

$app->get('/mon-espace/monument/{token}[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayDetailMonument($rq,$rs,$args);
})->setName('detail-monument');

$app->get('/mon-espace/monument/{token}/modifier[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayModifierMonument($rq,$rs,$args);
})->setName('modifierMonument');

$app->post('/mon-espace/monument/{token}/modifier[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->postModifierMonument($rq,$rs,$args);
});



// page du formulaire d'ajout d'un monument
$app->get('/ajouter-monument[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayAjouterMonument($rq,$rs,$args);
})->setName('ajoutMonument');

$app->post('/ajouter-monument[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->postAjouterMonument($rq,$rs,$args);
});



// page 'à propos' de Toureasy
$app->get('/profil[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayProfil($rq,$rs,$args);
})->setName('profil');

$app->post('/profil[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->postProfil($rq,$rs,$args);
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



// page de reception de demande d'ami
$app->get('/recevoir-demande-ami/{token}[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayDemandeAmi($rq,$rs,$args);
})->setName('reception-demande-ami');

$app->post('/recevoir-demande-ami/{token}[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->postDemandeAmi($rq,$rs,$args);
});



//page test pour... tester ^^
$app->get('/test', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayTest($rq,$rs,$args);
})->setName('test');



$app->run();