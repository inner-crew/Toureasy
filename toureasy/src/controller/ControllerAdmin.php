<?php


namespace toureasy\controller;
use Slim\Http\Request;
use Slim\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use toureasy\models\AppartenanceListe;
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
            'url' => $this->c->router->pathFor('home', []),
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

    public function postTableauAdmin(Request $rq, Response $rs, array $args): Response {
        $data = $rq->getParsedBody();

        $contribution = Contribution::getContributionByIdMonument($data['nouveau']);

        if (isset($data['valider'])) {
            $contribution->statutContribution = "acceptée";
            $contribution->moderateurDemande = Membre::getMembreByToken($_COOKIE['token'])->idMembre;
            $contribution->save();

            if (isset($contribution->monumentAModifier)) {
                $nouveau = Monument::getMonumentById($data['nouveau']);
                $originel = Monument::getMonumentById($contribution->monumentAModifier);

                $images = Image::getImagesIdByIdMonument($originel->idMonument);
                foreach ($images as $i) {
                    Image::supprimerImageById($i['numeroImage'], $originel->idMonument);
                }

                $nouvellesImages = Image::getImagesByIdMonument($nouveau->idMonument);
                foreach ($nouvellesImages as $i) {
                    $i->idMonument = $originel->idMonument;
                    $i->save();
                }

                $originel->descLongue = $nouveau->descLongue;
                $originel->nomMonum = $nouveau->nomMonum;
                $originel->save();

                $strJsonFileContents = file_get_contents("./web/carteSetting/data/monumentPublique.json");
                $jsonMonuemnts = json_decode($strJsonFileContents, true);
                $nouveauJson = [];


                $file_dest = Image::getImageUrlByIdMonument($originel->idMonument);
                $file_dest = $file_dest[0]['urlImage'];

                foreach ($jsonMonuemnts['features'] as $unMonument) {
                    if ($unMonument['properties']['token'] === $originel->token) {
                        $unMonument['properties']['title'] = $originel->nomMonum;
                        $unMonument['properties']['description'] = $originel->descLongue;
                        $unMonument['properties']['urlImage'] = $file_dest;
                        $unMonument['properties']['nomImage'] = substr($file_dest, 8);
                        array_push($nouveauJson,  $unMonument);
                    } else {
                        array_push($nouveauJson, $unMonument);
                    }
                }

                $jsonMonuemnts['features'] = $nouveauJson;
                $nvJson = json_encode($jsonMonuemnts);
                $bytes = file_put_contents("./web/carteSetting/data/monumentPublique.json", $nvJson);

            } else {
                $monument = Monument::getMonumentById($data['nouveau']);
                $monument->estTemporaire = 0;
                $monument->save();

                $strJsonFileContents = file_get_contents("./web/carteSetting/data/monumentPublique.json");
                $jsonMonuemnts = json_decode($strJsonFileContents, true);

                $file_dest = Image::getImageUrlByIdMonument($monument->idMonument);
                $file_dest = $file_dest[0]['urlImage'];

                array_push($jsonMonuemnts['features'], array(
                    "type" => "Feature",
                    "geometry" => array(
                        "type" => "Point",
                        "coordinates" => array($monument->longitude, $monument->latitude)
                    ),
                    "properties" => array(
                        "title" => $monument->nomMonum,
                        "description" => $monument->descLongue,
                        "urlImage" => $file_dest,
                        "nomImage" => substr($file_dest, 8),
                        "token" => $monument->token
                    )
                ));

                $nvJson = json_encode($jsonMonuemnts);
                $bytes = file_put_contents("./web/carteSetting/data/monumentPublique.json", $nvJson);
            }

            return $this->genererMessageAvecRedirection($rs,$rq,"La contribution a été validée", 'admin', $args);
        } else {
            $contribution->statutContribution = "refusée";
            $contribution->moderateurDemande = Membre::getMembreByToken($_COOKIE['token'])->idMembre;
            $contribution->save();

            return $this->genererMessageAvecRedirection($rs,$rq,"La contribution a été refusée", 'admin', $args);
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
            return $this->genererMessageAvecRedirection($rs, $rq,'Veuillez vous connecter pour accéder à la Map Toureasy', 'home', $args);
        }
    }

    private function verifierUtilisateurAdmin(): bool
    {
        if (isset($_COOKIE['token'])) {
            try {
                $membre = Membre::getMembreByToken($_COOKIE['token']);
                if ($membre->role >= 2 ) {
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
        $urlAPropos = $this->c->router->pathFor('about-us');
        $urlConnexion = $this->c->router->pathFor('home');
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
                'about-us' => $urlAPropos,
                'connexion' => $urlConnexion,
                'home' => $urlHome,
            ];
        }
        return $menu;
    }

}