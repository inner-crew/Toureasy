<?php

namespace toureasy\controller;
use Slim\Http\Request;
use Slim\Http\Response;
use toureasy\vue\Vue;

class Controller
{

    private $c = null;

    /**
     * Controller constructor
     * @param null $c
     */
    public function __construct($c)
    {
        $this->c = $c;
    }

    public function displayHome(Request $rq, Response $rs, array $args): Response
    {
        $urlConnexion = $this->c->router->pathFor('connexion');
        $urlAccederMap = $this->c->router->pathFor('map');
        $urlContact = $this->c->router->pathFor('contact');
        $urlAPropos = $this->c->router->pathFor('about-us');
        $htmlConnexion = <<<END
 <button onclick="location.href='$urlConnexion'">Connexion</button>
 END;
        $htmlMap = <<<END
 <button onclick="location.href='$urlAccederMap'">Accéder sans se connecter</button>
 END;
        $htmlContact = <<<END
 <button onclick="location.href='$urlContact'">Nous contacter</button>
 END;
        $htmlAboutUs = <<<END
 <button onclick="location.href='$urlAPropos'">A propos</button>
 END;
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'connexion' => $htmlConnexion,
            'map' => $htmlMap,
            'contact' => $htmlContact,
            'about-us' => $htmlAboutUs
        ];

        $v = new Vue(null);
        $rs->getBody()->write($v->render($htmlvars, Vue::HOME));
        return $rs;
    }

}