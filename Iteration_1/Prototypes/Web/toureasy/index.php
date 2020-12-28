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

//Eloquent::start(__DIR__ . '/src/conf/conf.ini');

$app->get('/home[/]', function(Request $rq, Response $rs, array $args) {
    $c = new Controller($this);
    return $c->displayHome($rq,$rs,$args);
})->setName('home');

$app->get('/connexion[/]', function(Request $rq, Response $rs, array $args) {
    echo 'Page de connexion';
})->setName('connexion');

$app->get('/map[/]', function(Request $rq, Response $rs, array $args) {
    echo 'Page de la map';
})->setName('map');

$app->get('/contact[/]', function(Request $rq, Response $rs, array $args) {
    echo 'Nous contacter';
})->setName('contact');

$app->get('/about-us[/]', function(Request $rq, Response $rs, array $args) {
    echo 'A propos';
})->setName('about-us');

$app->run();