<?php


namespace toureasy\controller;
use Slim\Http\Request;
use Slim\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use toureasy\models\Contribution;
use toureasy\models\Image;
use toureasy\models\Monument;
use toureasy\vue\VueAdmin;
use toureasy\models\Membre;


class ControllerAdmin
{

    private $c = null;

    public function __construct($c)
    {
        $this->c = $c;
    }

    public function displayTableauAdmin(Request $rq, Response $rs, array $args): Response {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'message' => "Vous devez vous connecter pour accéder à cette page",
            'url' => $this->c->router->pathFor('connexion', []),
            'menu' => $this->getMenu($args)
        ];

        if ($this->verifierUtilisateurAdmin()) {
            $contributions = Contribution::getMonumentsTemporaires();
            $res = [];
            foreach ($contributions as $c) {
                $c['url'] = $this->c->router->pathFor('detailAdmin', ['token' => $c['url']]);
                $url = $this->c->router->pathFor('detailAdmin', ['token' => $c['originel']]);
                array_push($res, ['Contribution' => $c['Contribution'], "url" => $c['url'], 'originel' => $url]);
            }

            $v = new VueAdmin([$res]);
            $rs->getBody()->write($v->render($htmlvars, VueAdmin::TABLEAU));
            return $rs;
        } else {
            $membre = Membre::getMembreByToken($_COOKIE['token'])->first();
            return $this->genererMessageAvecRedirection($rs,$rq,"Vous n'êtes pas administrateur", 'home', $args);
        }
    }

    public function displayDetailMonument(Request $rq, Response $rs, array $args): Response {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'menu' => $this->getMenu($args),
            'back' => $this->c->router->pathFor('admin'),
            ];

        if ($this->verifierUtilisateurConnecte()) {
            $monument = Monument::getMonumentByToken($args['token']);
            $images = Image::getImageUrlByIdMonument($monument->idMonument);

            $v = new VueAdmin([$monument, $images]);
            $rs->getBody()->write($v->render($htmlvars, VueAdmin::DETAIL_MONUMENT));
            return $rs;
        } else {
            return $this->genererMessageAvecRedirection($rs, $rq,'Veuillez vous connecter pour accéder à la Map Toureasy', 'connexion', $args);
        }
    }

    private function verifierUtilisateurAdmin(): bool
    {
        if (isset($_COOKIE['token'])) {
            try {
                $membre = Membre::getMembreByToken($_COOKIE['token']);
                if ($membre->role === 2 || $membre->role === 3) {
                    return true;
                } else {
                    return false;
                }

            } catch (ModelNotFoundException $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    private function verifierUtilisateurConnecte(): bool
    {
        if (isset($_COOKIE['token'])) {
            try {
                $membre = Membre::getMembreByToken($_COOKIE['token'])->first();
                return true;
            } catch (ModelNotFoundException $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    private function genererMessageAvecRedirection($rs, $rq, $message, $nameRedirection,array $args, $argsUrl = array())
    {
        $v = new VueAdmin(null);

        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'message' => $message,
            'url' => $this->c->router->pathFor($nameRedirection, $argsUrl),
            'menu' => $this->getMenu($args)
        ];
        $rs->getBody()->write($v->render($htmlvars, VueAdmin::MESSAGE));
        return $rs;
    }

    private function getMenu() {
        $urlContact = $this->c->router->pathFor('contact');
        $urlAPropos = $this->c->router->pathFor('about-us');
        $urlConnexion = $this->c->router->pathFor('connexion');
        $urlMap = $this->c->router->pathFor('map');
        $urlAmis = $this->c->router->pathFor('amis');
        $urlHome = $this->c->router->pathFor('home');

        $isConnected = $this->verifierUtilisateurConnecte();
        $menu = [];
        if ($isConnected) {
            $urlEspace = $this->c->router->pathFor('mes-listes', ['token' => $_COOKIE['token']]);
            $urlAjouterMonument = $this->c->router->pathFor('ajoutMonument');
            $urlProfil = $this->c->router->pathFor('profil');

            $menu = [
                'contact' => $urlContact,
                'about-us' => $urlAPropos,
                'map' => $urlMap,
                'espace' => $urlEspace,
                'ajout' => $urlAjouterMonument,
                'profil' => $urlProfil,
                'amis' => $urlAmis,
                'home' =>$urlHome,
            ];
        } else {
            $menu = [
                'contact' => $urlContact,
                'about-us' => $urlAPropos,
                'connexion' => $urlConnexion,
                'home' => $urlHome,
            ];
        }
        return $menu;
    }

}