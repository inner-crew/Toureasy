<?php

namespace toureasy\controller;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use toureasy\models\Amis;
use toureasy\models\AppartenanceListe;
use toureasy\models\AuteurMonumentPrive;
use toureasy\models\Contribution;
use toureasy\models\DemandeAmi;
use toureasy\models\Favoris;
use toureasy\models\Image;
use toureasy\models\ListeMonument;
use toureasy\models\Membre;
use toureasy\models\Monument;
use toureasy\vue\Vue;

class Controller
{

    private $c = null;

    public function __construct($c)
    {
        $this->c = $c;
    }

    public function displayHome(Request $rq, Response $rs, array $args): Response
    {
        // url redirigeant vers la page de connexion
        $urlConnexion = $this->c->router->pathFor('connexion');
        // url redirigeant vers la page de navigation sur la carte
        $urlAccederMap = $this->c->router->pathFor('map');

        if (!$this->verifierUtilisateurConnecte()) {
            $urlAccederMap = $urlConnexion;
        }

        // ajoute ces variables à htmlvars afin de les transférer à la vue
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'map' => $urlAccederMap,
            'menu' => $this->getMenu($args)
        ];

        $v = new Vue(null);
        $rs->getBody()->write($v->render($htmlvars, Vue::HOME));
        return $rs;
    }

    public function displayConnexion(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'menu' => $this->getMenu($args)
        ];
        $v = new Vue(null);
        $rs->getBody()->write($v->render($htmlvars, Vue::CONNEXION));
        return $rs;
    }

    public function postConnexion(Request $rq, Response $rs, array $args)
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'url' => $this->c->router->pathFor('map', []),
            'message' => "Connexion réussie",
            'menu' => $this->getMenu($args)

        ];
        $data = $rq->getParsedBody();
        $token = filter_var($data['action'], FILTER_SANITIZE_STRING);
        $v = new Vue(null);

        if ($token === "Obtenir un code") {
            $token = bin2hex(random_bytes(5));
            $htmlvars['message'] = "Votre code d'identification est $token";
            $membre = new Membre();
            $membre->token = $token;
            $membre->save();
        } else {
            $token = filter_var($data['token'], FILTER_SANITIZE_STRING);
            try {
                Membre::getMembreByToken($token);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $htmlvars['message'] = "Le code d'identification indiqué est inexistant";
                $htmlvars['url'] = $this->c->router->pathFor('connexion', []);
                $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
                return $rs;
            }
        }

        setcookie('token', $token, time() + 60 * 60 * 24 * 500, "/");

        $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
        return $rs;
    }

    public function displayProfil(Request $rq, Response $rs, array $args): Response
    {
        $urlMap = $this->c->router->pathFor('map');

        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'map' => $urlMap,
            'menu' => $this->getMenu($args)
        ];

        if (!$this->verifierUtilisateurConnecte()) {
            return $this->genererRedirectionPageConnexion($rs, $rq, $args);
        } else {
            $m = Membre::getMembreByToken($_COOKIE['token']);
            $v = new Vue([$m]);
            $rs->getBody()->write($v->render($htmlvars, Vue::PROFIL));
        }

        return $rs;
    }

    public function postProfil(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'message' => "Succès",
            'url' => $this->c->router->pathFor('profil'),
            'menu' => $this->getMenu($args)
        ];

        $data = $rq->getParsedBody();
        $prenom = filter_var($data['prenom'], FILTER_SANITIZE_STRING);
        $nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
        $sexe = $data['sexe'];
        $naissance = filter_var($data['naissance'], FILTER_SANITIZE_STRING);
        $mail = filter_var($data['mail'], FILTER_SANITIZE_STRING);
        $membre = Membre::getMembreByToken($_COOKIE['token']);

        if ($mail === "") {
            $mail = null;
        }

        $membre->prenom = $prenom;
        $membre->nom = $nom;
        switch ($sexe) {
            case 'm':
                $membre->sexe = 'homme';
                break;
            case 'f':
                $membre->sexe = 'femme';
                break;
            case 'x':
                $membre->sexe = 'non-renseigné';
                break;
            default:
                break;
        }
        $membre->dateNaissance = $naissance;
        $membre->email = $mail;
        $membre->save();

        $v = new Vue(null);
        $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
        return $rs;
    }

    public function displayMonEspace(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'createListe' => $this->c->router->pathFor('create-liste', []),
            'createMonument' => $this->c->router->pathFor('ajoutMonument', []),
            'menu' => $this->getMenu($args)
        ];

        if (!$this->verifierUtilisateurConnecte()) {
            return $this->genererRedirectionPageConnexion($rs, $rq, $args);
        } else {
            $idMembre = Membre::getMembreByToken($_COOKIE['token'])->idMembre;

            $v = new Vue([$this->getListeDunUser($idMembre), $this->getMonumentPriveDunUser($idMembre), $this->getMonumentPubliqueDunUser($idMembre)]);

            $rs->getBody()->write($v->render($htmlvars, Vue::VUE_ENSEMBLE));
            return $rs;
        }
    }

    private function getMonumentPubliqueDunUser($idMembre, bool $avoirUrl = true): array
    {
        $listeMonumentsPublics = Contribution::getMonumentByIdCreator($idMembre);
        $arrayMonumentsPublics = array();
        foreach ($listeMonumentsPublics as $monument) {
            $monument = Monument::getMonumentById($monument->monumentTemporaire);
            $image = Image::where('idMonument', '=', $monument->idMonument)->first();
            if ($monument->estPrive == '0') {
                if ($avoirUrl) {
                    $url = $this->c->router->pathFor('detail-monument', ['token' => $monument->token]);
                    $partage = $_SERVER["HTTP_HOST"] . $this->c->router->pathFor('mapDetailMonument', ['token' => $monument->token]);
                    array_push($arrayMonumentsPublics, [$monument, $url, $partage]);
                } else {
                    $monument->urlImage = $image->urlImage;
                    $monument->nomImage = substr($image->urlImage, 8);
                    array_push($arrayMonumentsPublics, $monument);
                }
            }
        }
        return $arrayMonumentsPublics;
    }

    private function getMonumentPriveDunUser($idMembre, bool $avoirUrl = true): array
    {
        $listeMonumentsPrives = AuteurMonumentPrive::getMonumentByCreator($idMembre);
        $arrayMonumentsPrives = array();
        foreach ($listeMonumentsPrives as $monument) {
            $monument = Monument::getMonumentById($monument->idMonument);
            $image = Image::where('idMonument', '=', $monument->idMonument)->first();
            if ($avoirUrl) {
                $url = $this->c->router->pathFor('detail-monument', ['token' => $monument->token]);
                $partage = $_SERVER["HTTP_HOST"] . $this->c->router->pathFor('mapDetailMonument', ['token' => $monument->token]);
                array_push($arrayMonumentsPrives, [$monument, $url, $partage]);
            } else {
                $monument->urlImage = $image->urlImage;
                $monument->nomImage = substr($image->urlImage, 8);
                array_push($arrayMonumentsPrives, $monument);
            }
        }
        return $arrayMonumentsPrives;
    }

    private function getListeDunUser($idMembre, bool $avoirUrl = true): array
    {
        $listeDesListesUtilisateur = ListeMonument::getListesByIdCreator($idMembre);
        $tabListes = array();
        foreach ($listeDesListesUtilisateur as $liste) {
            if ($avoirUrl) {
                $url = $this->c->router->pathFor('detail-liste', ['token' => $liste->token]);
                $partage = $_SERVER["HTTP_HOST"] . $this->c->router->pathFor('mapDetailListe', ['token' => $liste->token]);
                array_push($tabListes, [$liste, $url, $partage]);
            } else array_push($tabListes, $liste);
        }
        if (isset($_GET['liste'])) {
            array_push($tabListes, ListeMonument::getListeByToken($_GET['liste']));
        }
        return $tabListes;
    }

    private function getMonumentUrl()
    {
        if (isset($_GET['monument'])) {
            $monument = Monument::getMonumentByToken($_GET['monument']);
            $image = Image::where('idMonument', '=', $monument->idMonument)->first();
            $monument->urlImage = $image->urlImage;
            $monument->nomImage = substr($image->urlImage, 8);
            return [$monument];
        } else {
            return null;
        }
    }

    private function getMonumentDuneListe($tokenListe): array
    {
        $liste = ListeMonument::getListeByToken($tokenListe);
        $listeMonumentsDeCetteListe = AppartenanceListe::getMonumentByIdListe($liste->idListe);
        $tabMonumentsDeCetteListe = array();
        foreach ($listeMonumentsDeCetteListe as $monument) {
            $image = Image::where('idMonument', '=', $monument->idMonument)->first();
            $monument = Monument::getMonumentById($monument->idMonument);
            $monument->urlImage = $image->urlImage;
            $monument->nomImage = substr($image->urlImage, 8);
            array_push($tabMonumentsDeCetteListe, $monument);
        }
        return $tabMonumentsDeCetteListe;
    }

    private function getMonumentFavoriDunUser($idMembre): array
    {
        $favoris = Membre::with('monumentsFavoris')->where('idMembre', '=', $idMembre)->get()->toArray();
        $tabListeMonuement = array();
        //var_dump($favoris[0]['monuments_favoris']);
        $tmp = $favoris[0]['monuments_favoris'];
        foreach ($tmp as $unFav) {
            $monument = Monument::getMonumentById($unFav['idMonument']);
            $image = Image::where('idMonument', '=', $unFav['idMonument'])->first();
            $monument->urlImage = $image->urlImage;
            $monument->nomImage = substr($image->urlImage, 8);
            array_push($tabListeMonuement, $monument);
        }
        return $tabListeMonuement;
    }

    public function displayMap(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'menu' => $this->getMenu($args),
            'map' => $this->c->router->pathFor('map')
        ];

        if ($this->verifierUtilisateurConnecte()) {
            $idMembre = Membre::getMembreByToken($_COOKIE['token'])->idMembre;
            $arrayListesMonuments = array();
            foreach ($this->getListeDunUser($idMembre, false) as $uneListe) {
                array_push($arrayListesMonuments, ["liste" => $uneListe, "assosiation" => AppartenanceListe::getMonumentByIdListe($uneListe->idListe)->toArray()]);
            }
            $monuments = null;
            if (isset($_GET['monument'])) {
                $monuments = $this->getMonumentUrl();
            } else if (isset($_GET['liste'])) {
                $monuments = $this->getMonumentDuneListe($_GET['liste']);
            }
            $res = array("Listes" => $arrayListesMonuments,
                "monumentsPrives" => $this->getMonumentPriveDunUser($idMembre, false),
                "monumentsPubliques" => $this->getMonumentPubliqueDunUser($idMembre, false),
                "monumentPartager" => $monuments,
                "monumentsFavoris" => $this->getMonumentFavoriDunUser($idMembre)
            );

            $leJson = json_encode($res);
            file_put_contents("./web/carteSetting/data/tmp/{$_COOKIE['token']}.json", $leJson);

            $v = new Vue(null);
            $rs->getBody()->write($v->render($htmlvars, Vue::MAP));
            return $rs;
        } else {
            return $this->genererMessageAvecRedirection($rs, $rq, 'Veuillez vous connecter pour accéder à la Map Toureasy', 'connexion', $args);
        }
    }

    public function displayMapDetailMonument(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'menu' => $this->getMenu($args),
            'back' => $this->c->router->pathFor('map'),
            'modifierMonument' => $this->c->router->pathFor('mapModifierMonument', ["token" => $args['token']]),
        ];

        if ($this->verifierUtilisateurConnecte()) {
            $monument = Monument::getMonumentByToken($args['token']);
            $images = Image::getImageUrlByIdMonument($monument->idMonument);
            $membre = Membre::getMembreByToken($_COOKIE['token']);

            $favori = $membre->monumentsFavoris->contains($monument->idMonument);
            if ($favori) $htmlvars['estFavori'] = true;
            else $htmlvars['estFavori'] = false;

            $htmlvars['seeOnMap'] = $this->c->router->pathFor('map') . "?monument=" . $monument->token;

            $v = new Vue([$monument, $images]);
            $rs->getBody()->write($v->render($htmlvars, Vue::MAP_MONUMENT));
            return $rs;
        } else {
            return $this->genererMessageAvecRedirection($rs, $rq, 'Veuillez vous connecter pour accéder à la Map Toureasy', 'connexion', $args);
        }
    }

    public function displayMapDetailListeMonument(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'menu' => $this->getMenu($args),
            'back' => $this->c->router->pathFor('mapDetailListe', ['token' => $args['token']])
        ];

        if ($this->verifierUtilisateurConnecte()) {
            $monument = Monument::getMonumentByToken($args['tokenM']);
            $images = Image::getImageUrlByIdMonument($monument->idMonument);
            $membre = Membre::getMembreByToken($_COOKIE['token']);

            try {
                $favori = Favoris::query()->where([['idMonument', '=', $monument->idMonument], ['idMembre', '=', $membre->idMembre]])->firstOrFail();
                $htmlvars['estFavori'] = true;
            } catch (ModelNotFoundException $e) {
                $htmlvars['estFavori'] = false;
            }

            $htmlvars['seeOnMap'] = $this->c->router->pathFor('map') . "?monument=" . $monument->token;

            $v = new Vue([$monument, $images]);
            $rs->getBody()->write($v->render($htmlvars, Vue::MAP_MONUMENT));
            return $rs;
        } else {
            return $this->genererMessageAvecRedirection($rs, $rq, 'Veuillez vous connecter pour accéder à la Map Toureasy', 'connexion', $args);
        }
    }

    public function displayMapDetailListe(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'menu' => $this->getMenu($args),
            'back' => $this->c->router->pathFor('map')
        ];

        if ($this->verifierUtilisateurConnecte()) {
            $liste = ListeMonument::getListeByToken($args['token']);
            $listeMonumentsDeCetteListe = AppartenanceListe::getMonumentByIdListe($liste->idListe);
            $tabMonumentsDeCetteListe = array();
            foreach ($listeMonumentsDeCetteListe as $monument) {
                $monument = Monument::getMonumentById($monument->idMonument);
                $url = $this->c->router->pathFor('mapDetailListeMonument', ['token' => $liste->token, 'tokenM' => $monument->token]);
                array_push($tabMonumentsDeCetteListe, [$monument, $url]);
            }

            $htmlvars['seeOnMap'] = $this->c->router->pathFor('map') . "?liste=" . $liste->token;

            $v = new Vue([$liste, $tabMonumentsDeCetteListe]);
            $rs->getBody()->write($v->render($htmlvars, Vue::MAP_LISTE));
            return $rs;
        } else {
            return $this->genererMessageAvecRedirection($rs, $rq, 'Veuillez vous connecter pour accéder à la Map Toureasy', 'connexion', $args);
        }
    }

    public function displayAjouterMonument(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'menu' => $this->getMenu($args),
            'publique' => isset($_GET['publique'])
        ];
        $v = new Vue(null);

        if (!$this->verifierUtilisateurConnecte()) {
            return $this->genererRedirectionPageConnexion($rs, $rq, $args);
        } else {
            $rs->getBody()->write($v->render($htmlvars, Vue::AJOUTER_MONUMENT));
        }
        return $rs;
    }

    public function postAjouterMonument(Request $rq, Response $rs, array $args)
    {
        $data = $rq->getParsedBody();
        $nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
        $description = filter_var($data['desc'], FILTER_SANITIZE_STRING);
        $contribution = null;
        $auteurPrive = null;

        try {
            $monument = new Monument();

            $monument->nomMonum = $nom;
            $monument->descLongue = $description;
            $monument->longitude = $_POST['long'];
            $monument->latitude = $_POST['lat'];
            $monument->token = bin2hex(random_bytes(5));

            if ($data['visibilite'] === "private") {
                $monument->estPrive = 1;
                $monument->estTemporaire = 0;
                $monument->save();

                $auteurPrive = new AuteurMonumentPrive();
                $auteurPrive->idMonument = $monument->idMonument;
                $auteurPrive->idMembre = Membre::getMembreByToken($_COOKIE['token'])->idMembre;
                $auteurPrive->save();
            } else {
                $monument->estPrive = 0;
                $monument->estTemporaire = 1;
                $monument->save();

                $contribution = new Contribution();
                $contribution->monumentTemporaire = $monument->idMonument;
                $contribution->contributeur = Membre::getMembreByToken($_COOKIE['token'])->idMembre;
                $contribution->estNouveauMonument = 1;
                $contribution->statutContribution = "enAttenteDeTraitement";
                $contribution->save();
            }

        } catch (ModelNotFoundException $e) {
            if ($contribution != null) {
                $contribution->delete();
            }
            if ($auteurPrive != null) {
                $auteurPrive->delete();
            }
            if ($monument != null) {
                $monument->delete();
            }
            return $this->genererMessageAvecRedirection($rs, $rq, "Erreur lors de l'ajout du monument", "ajoutMonument", $args);
        }

        // si une image est bien présente dans la variable
        if (!empty($_FILES)) {
            $total = count($_FILES['fichier']['name']);
            $nomFichierImage = $_FILES['fichier']['name'][0];
            $imageJson = "web/img/" . $nomFichierImage;
            for ($i = 0; $i < $total; $i++) {

                // récupération du nom du fichier
                $file_name = $_FILES['fichier']['name'][$i];
                // récupération de l'extension du fichier
                $file_extension = strrchr($file_name, ".");
                // stockage temporaire du fichier
                $file_tmp_name = $_FILES['fichier']['tmp_name'][$i];
                // ajout destination du fichier
                $file_dest = "web/img/taken_" . date('Y_m_d_H_i_s') . bin2hex(random_bytes(2)) . $file_extension;
                // conditions de format du fichier
                $extension_autorise = array('.jpg', '.png', '.JPG', '.PNG');

                // si fichier corrélation avec conditions
                if (in_array($file_extension, $extension_autorise)) {
                    if (move_uploaded_file($file_tmp_name, $file_dest)) {
                        $image = new Image();
                        $image->numeroImage = $i;
                        $image->idMonument = $monument->idMonument;
                        $image->urlImage = $file_dest;
                        $image->save();
                    } else {
                        if ($contribution != null) {
                            $contribution->delete();
                        }
                        if ($auteurPrive != null) {
                            $auteurPrive->delete();
                        }
                        if ($monument != null) {
                            $monument->delete();
                        }
                        return $this->genererMessageAvecRedirection($rs, $rq, 'Une erreur est survenue lors du téléchargement de l\'image', "ajoutMonument", $args);
                    }
                } else {
                    if ($contribution != null) {
                        $contribution->delete();
                    }
                    if ($auteurPrive != null) {
                        $auteurPrive->delete();
                    }
                    if ($monument != null) {
                        $monument->delete();
                    }
                    return $this->genererMessageAvecRedirection($rs, $rq, 'Veuillez ajouter une image valide pour votre monument', "ajoutMonument", $args);
                }
                if ($i === 0) {
                    if ($monument->estPrive == 0 && $monument->estTemporaire == 0) {
                        $strJsonFileContents = file_get_contents("./web/carteSetting/data/monumentPublique.json");
                        $jsonMonuemnts = json_decode($strJsonFileContents, true);

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
                                "nomImage" => $nomFichierImage,
                                "token" => $monument->token
                            )
                        ));

                        $nvJson = json_encode($jsonMonuemnts);
                        $bytes = file_put_contents("./web/carteSetting/data/monumentPublique.json", $nvJson);
                    }
                }
            }
        } else {
            if ($contribution != null) {
                $contribution->delete();
            }
            if ($auteurPrive != null) {
                $auteurPrive->delete();
            }
            if ($monument != null) {
                $monument->delete();
            }
            return $this->genererMessageAvecRedirection($rs, $rq, "Vous devez mettre une image pour ajouter un monument", "ajoutMonument", $args);
        }

        return $this->genererMessageAvecRedirection($rs, $rq, "Monument créé avec succès", "mes-listes", $args);
    }

    public function displayAjouterListe(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'menu' => $this->getMenu($args)
        ];
        $v = new Vue(null);

        if ($this->verifierUtilisateurConnecte()) {
            $rs->getBody()->write($v->render($htmlvars, Vue::AJOUTER_LISTE));
            return $rs;
        } else {
            return $this->genererRedirectionPageConnexion($rs, $args, $htmlvars);
        }
    }

    public  function postAjouterListe(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'message' => "Succès",
            'url' => $this->c->router->pathFor('mes-listes'),
            'menu' => $this->getMenu($args)
        ];

        $v = new Vue(null);

        $data = $rq->getParsedBody();
        $nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
        $description = filter_var($data['desc'], FILTER_SANITIZE_STRING);
        $id = Membre::getMembreByToken($_COOKIE['token']);

        try {
            $liste = new ListeMonument();
            $liste->nom = $nom;
            $liste->description = $description;
            $liste->idCreateur = $id->idMembre;
            $liste->token = bin2hex(random_bytes(5));
            $liste->save();
        } catch (ModelNotFoundException $e) {
            if ($liste != null) {
                $liste->delete();
            }
            return $this->genererMessageAvecRedirection($rs, $htmlvars, "Erreur lors de la création de votre liste", "createListe", $args);
        }
        $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
        return $rs;
    }

    public function displayDetailListe(Request $rq, Response $rs, array $args): Response
    {
        $liste = ListeMonument::getListeByToken($args['token']);
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'modifierListe' => $this->c->router->pathFor('modifierListe', ["token" => $args['token']]),
            'menu' => $this->getMenu($args),
            'back' => $this->c->router->pathFor('mes-listes')
        ];

        if ($this->verifierUtilisateurConnecte()) {
            $listeMonumentsDeCetteListe = AppartenanceListe::getMonumentByIdListe($liste->idListe);
            $tabMonumentsDeCetteListe = array();
            foreach ($listeMonumentsDeCetteListe as $monument) {
                $monument = Monument::getMonumentById($monument->idMonument);
                $url = $this->c->router->pathFor('detail-monument', ['token' => $monument->token]);
                array_push($tabMonumentsDeCetteListe, [$monument, $url]);
            }

            $listeMonumentsDeLaListe1Dimension = array();
            foreach ($listeMonumentsDeCetteListe as $monument) {
                $monument = Monument::getMonumentById($monument->idMonument);
                array_push($listeMonumentsDeLaListe1Dimension, $monument);
            }

            $tabTousMonuments = Membre::getTousLesMonumentsUtilisateurByToken($_COOKIE['token']);
            $tabMonumentsPasDansCetteListe = array_diff($tabTousMonuments, $listeMonumentsDeLaListe1Dimension);

            $v = new Vue([$liste, $tabMonumentsDeCetteListe, $tabMonumentsPasDansCetteListe]);

            $rs->getBody()->write($v->render($htmlvars, Vue::LISTE));
        } else {
            return $this->genererRedirectionPageConnexion($rs, $rq, $args);
        }

        return $rs;
    }

    public function displayModifierListe(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'menu' => $this->getMenu($args)
        ];

        $liste = ListeMonument::getListeByToken($args['token']);

        $v = new Vue([$liste]);
        $rs->getBody()->write($v->render($htmlvars, Vue::MODIFIER_LISTE));
        return $rs;
    }

    public function postModifierListe(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
        ];

        $data = $rq->getParsedBody();
        $nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
        $description = filter_var($data['desc'], FILTER_SANITIZE_STRING);

        $liste = ListeMonument::getListeByToken($args['token']);
        $liste->nom = $nom;
        $liste->description = $description;
        $liste->save();

        return $this->genererMessageAvecRedirection($rs, $rq, "Liste modifiée avec succès", "mes-listes", $args);
    }

    public function displayDetailMonument(Request $rq, Response $rs, array $args): Response
    {
        $monument = Monument::getMonumentByToken($args['token']);
        $images = Image::getImageUrlByIdMonument($monument->idMonument);

        $v = new Vue([$monument, $images]);

        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            "modifierMonument" => $this->c->router->pathFor('modifierMonument', ["token" => $args['token']]),
            'menu' => $this->getMenu($args),
            'back' => $this->c->router->pathFor('mes-listes')
        ];

        if ($this->verifierUtilisateurConnecte()) {
            $rs->getBody()->write($v->render($htmlvars, Vue::MONUMENT));
        } else {
            return $this->genererRedirectionPageConnexion($rs, $rq, $args);
        }

        return $rs;
    }

    public function displayModifierMonument(Request $rq, Response $rs, array $args): Response
    {
        $monument = Monument::getMonumentByToken($args['token']);
        $image = Image::getImageUrlByIdMonument($monument->idMonument);
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'menu' => $this->getMenu($args)
        ];
        $v = new Vue([$monument, $image]);

        $rs->getBody()->write($v->render($htmlvars, Vue::MODIFIER_MONUMENT));
        return $rs;
    }

    public function postModifierMonument(Request $rq, Response $rs, array $args): Response
    {
        $monument = null;
        if (isset($args['tokenM'])) {
            $monument = Monument::getMonumentByToken($args['tokenM']);
        } else {
            $monument = Monument::getMonumentByToken($args['token']);
        }


        if ($monument->estPrive) {
            return $this->postModifierMonumentPrive($rq, $rs, $args, $monument);
        } else {
            return $this->postModifierMonumentPublic($rq, $rs, $args, $monument);
        }
    }

    public function postModifierMonumentPublic(Request $rq, Response $rs, array $args, Monument $m): Response
    {
        $data = $rq->getParsedBody();

        $nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
        $description = $data['desc'];

        $monument = new Monument();
        $monument->descLongue = $description;
        $monument->nomMonum = $nom;
        $monument->estTemporaire = 1;
        $monument->estPrive = 0;
        $monument->token = bin2hex(random_bytes(5));
        $monument->save();

        $contribution = new Contribution();
        $contribution->monumentTemporaire = $monument->idMonument;
        $contribution->monumentAModifier = $m->idMonument;
        $contribution->contributeur = Membre::getMembreByToken($_COOKIE['token'])->idMembre;
        $contribution->estNouveauMonument = 0;
        $contribution->statutContribution = 'enAttenteDeTraitement';
        $contribution->save();

        $arrayImageDelete = explode("-", $data['delete']);

        $idMonument = $monument->idMonument;

        Image::copierImageMonumentPublicANouveau($idMonument, $m->idMonument);

        foreach ($arrayImageDelete as $idImage) {
            if ($idImage != "") {
                Image::supprimerImageById($idImage, $idMonument);
            }
        }

        if (!empty($_FILES) && $_FILES['fichier']['name'][0] != "") {
            $total = count($_FILES['fichier']['name']);
            for ($i = 0; $i < $total; $i++) {

                // récupération du nom du fichier
                $file_name = $_FILES['fichier']['name'][$i];
                // récupération de l'extension du fichier
                $file_extension = strrchr($file_name, ".");
                // stockage temporaire du fichier
                $file_tmp_name = $_FILES['fichier']['tmp_name'][$i];
                // ajout destination du fichier
                $file_dest = "web/img/taken_" . date('Y_m_d_H_i_s') . bin2hex(random_bytes(2)) . $file_extension;
                // conditions de format du fichier
                $extension_autorise = array('.jpg', '.png', '.JPG', '.PNG');

                // si fichier corrélation avec conditions
                if (in_array($file_extension, $extension_autorise)) {
                    if (move_uploaded_file($file_tmp_name, $file_dest)) {
                        $image = new Image();
                        $image->numeroImage = $i;
                        $image->idMonument = $monument->idMonument;
                        $image->urlImage = $file_dest;
                        $image->save();
                    } else {
                        return $this->genererMessageAvecRedirection($rs, $rq, 'Une erreur est survenue lors du téléchargement de l\'image', "modifierMonument", $args, ['token' => $args['token']]);
                    }
                } else {
                    return $this->genererMessageAvecRedirection($rs, $rq, 'Veuillez ajouter une image valide pour votre monument', "modifierMonument", $args, ['token' => $args['token']]);
                }
            }
        }
        $monument->save();

        return $this->genererMessageAvecRedirection($rs, $rq, "Monument modifié", "detail-monument", $args, ["token" => $args['token']]);
    }

    public function postModifierMonumentPrive(Request $rq, Response $rs, array $args): Response
    {
        $monument = Monument::getMonumentByToken($args['token']);
        $data = $rq->getParsedBody();

        $nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
        $description = $data['desc'];

        $monument->descLongue = $description;
        $monument->nomMonum = $nom;

        $arrayImageDelete = explode("-", $data['delete']);

        $idMonument = $monument->idMonument;

        foreach ($arrayImageDelete as $idImage) {
            if ($idImage != "") {
                Image::supprimerImageById($idImage, $idMonument);
            }
        }

        $debutIndex = Image::getPlusGrandIndex($idMonument)->numeroImage + 1;

        if (!empty($_FILES) && $_FILES['fichier']['name'][0] != "") {
            $total = count($_FILES['fichier']['name']);
            for ($i = 0; $i < $total; $i++) {

                // récupération du nom du fichier
                $file_name = $_FILES['fichier']['name'][$i];
                // récupération de l'extension du fichier
                $file_extension = strrchr($file_name, ".");
                // stockage temporaire du fichier
                $file_tmp_name = $_FILES['fichier']['tmp_name'][$i];
                // ajout destination du fichier
                $file_dest = "web/img/taken_" . date('Y_m_d_H_i_s') . bin2hex(random_bytes(2)) . $file_extension;
                // conditions de format du fichier
                $extension_autorise = array('.jpg', '.png', '.JPG', '.PNG');

                // si fichier corrélation avec conditions
                if (in_array($file_extension, $extension_autorise)) {
                    if (move_uploaded_file($file_tmp_name, $file_dest)) {
                        $image = new Image();
                        $image->numeroImage = $debutIndex;
                        $image->idMonument = $monument->idMonument;
                        $image->urlImage = $file_dest;
                        $image->save();
                        $debutIndex++;
                    } else {
                        return $this->genererMessageAvecRedirection($rs, $rq, 'Une erreur est survenue lors du téléchargement de l\'image', "modifierMonument", $args, ['token' => $args['token']]);
                    }
                } else {
                    return $this->genererMessageAvecRedirection($rs, $rq, 'Veuillez ajouter une image valide pour votre monument', "modifierMonument", $args, ['token' => $args['token']]);
                }
            }
        }
        $monument->save();

        return $this->genererMessageAvecRedirection($rs, $rq, "Monument modifié", "detail-monument", $args, ["token" => $args['token']]);
    }

    public function postAjouterMonumentListe(Request $rq, Response $rs, array $args): Response
    {
        $data = $rq->getParsedBody();
        $idMonument = $data["monuments"];

        $appartenanceListe = new AppartenanceListe();
        $liste = ListeMonument::getListeByToken($args['token']);
        $monument = Monument::getMonumentById($idMonument);

        $appartenanceListe->idListe = $liste->idListe;
        $appartenanceListe->idMonument = $monument->idMonument;
        $appartenanceListe->save();

        return $this->genererMessageAvecRedirection($rs, $rq, "Monument ajouté à la liste avec succès", 'detail-liste', $args, ['token' => $args['token']]);
    }

    private function verifierUtilisateurConnecte(): bool
    {
        if (isset($_COOKIE['token'])) {
            try {
                $membre = Membre::getMembreByToken($_COOKIE['token']);
                return true;
            } catch (ModelNotFoundException $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    private function genererRedirectionPageConnexion($rs, $rq, array $args)
    {
        $v = new Vue(null);
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'message' => "Vous devez vous connecter pour accéder à cette page",
            'url' => $this->c->router->pathFor('connexion', []),
            'menu' => $this->getMenu($args)
        ];
        $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
        return $rs;
    }

    private function genererMessageAvecRedirection($rs, $rq, $message, $nameRedirection, array $args, $argsUrl = array())
    {
        $v = new Vue(null);

        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'message' => $message,
            'url' => $this->c->router->pathFor($nameRedirection, $argsUrl),
            'menu' => $this->getMenu($args)
        ];
        $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
        return $rs;
    }

    public function displayAboutUs(Request $rq, Response $rs, array $args): Response
    {
        $v = new Vue(null);
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'menu' => $this->getMenu($args)
        ];
        $rs->getBody()->write($v->render($htmlvars, Vue::ABOUT));
        return $rs;
    }

    public function displayDemandeAmi(Request $rq, Response $rs, array $args): Response
    {
        $v = new Vue(null);


        if (!$this->verifierUtilisateurConnecte()) {
            return $this->genererRedirectionPageConnexion($rs, $rq, $args);
        }

        try {
            $demandeur = DemandeAmi::getDemandeurByTokenDemande($args['token']);
            $demande = DemandeAmi::getDemandeByToken($args['token']);
        } catch (ModelNotFoundException $e) {
            return $this->genererMessageAvecRedirection($rs, $rq, "La demande d'ami à laquelle vous essayez
            d'accèder n'existe pas", 'home', $args);
        }

        $membre = Membre::getMembreByToken($_COOKIE['token']);

        if ($_COOKIE['token'] == $demandeur->token) {
            return $this->genererMessageAvecRedirection($rs, $rq, "Vous êtes à l'origine de
            cette demande d'ami, tentez plutôt de l'envoyer à quelqu'un d'autre", 'home', $args);
        }

        $tabAmis = Amis::getAllAmisByIdMembre($demandeur->idMembre);
        if (in_array($membre, $tabAmis)) {
            return $this->genererMessageAvecRedirection($rs, $rq, "Vous êtes déjà ami avec ce membre", 'home', $args);
        }

        if (!$demande->disponible) {
            return $this->genererMessageAvecRedirection($rs, $rq, "Cette demande n'est plus disponible,
            elle a probablement déjà été utilisée par quelqu'un d'autre", 'home', $args);
        }

        if (isset($demandeur['username'])) {
            $username = $demandeur['username'];
        } else {
            $username = "Un membre anonyme";
        }

        $htmlvars = [
            'username' => $username,
            'basepath' => $rq->getUri()->getBasePath(),
            'menu' => $this->getMenu($args)
        ];

        $rs->getBody()->write($v->render($htmlvars, Vue::DEMANDE_AMI));
        return $rs;
    }

    public function postDemandeAmi(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'message' => "Succès",
            'url' => $this->c->router->pathFor('profil'),
            'menu' => $this->getMenu($args)
        ];

        $data = $rq->getParsedBody();
        if ($data["rep"] == "Accepter") {
            $demandeur = DemandeAmi::getDemandeurByTokenDemande($args['token']);
            $receveur = Membre::getMembreByToken($_COOKIE['token']);

            $amis = new Amis();
            $id1 = $demandeur->idMembre;
            $id2 = $receveur->idMembre;

            if ($id1 < $id2) {
                $amis->amis1 = $id1;
                $amis->amis2 = $id2;
            } else {
                $amis->amis1 = $id2;
                $amis->amis2 = $id1;
            }

            $amis->save();

            $demandeAmi = DemandeAmi::getDemandeByToken($args['token']);
            $demandeAmi->disponible = 0;
            $demandeAmi->save();

            return $this->genererMessageAvecRedirection($rs, $rq, "Le membre a bien été ajouté à vos amis", 'home', $args);
        } else {
            // return $this->genererMessageAvecRedirection($rs, $rq, "La demande a été refusée",'home', $args);
            return $this->genererMessageAvecRedirection($rs, $rq, "La demande a bien été refusée", 'home', $args);


        }
    }

    public function creerListeAmis($tabAmis)
    {
        $tabAmisParam = [];

        foreach ($tabAmis as $amis) {
            $username = (isset($amis['username'])) ? $amis['username'] : "Anonyme";
            $param = array("username" => $username,
                "dateInscription" => $amis->dateInscription);

            array_push($tabAmisParam, $param);

        }
        return $tabAmisParam;
    }

    public function displayAmis(Request $rq, Response $rs, array $args): Response
    {
        $v = new Vue(null);

        if (!$this->verifierUtilisateurConnecte()) {
            return $this->genererRedirectionPageConnexion($rs, $rq, $args);
        }
        $idUser = Membre::getMembreByToken($_COOKIE['token'])->idMembre;
        $tabAmis = Amis::getAllAmisByIdMembre($idUser);
        $tabAmisParam = $this->creerListeAmis($tabAmis);

        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'menu' => $this->getMenu($args),
            'listeAmis' => $tabAmisParam,
        ];

        $rs->getBody()->write($v->render($htmlvars, Vue::AMIS));
        return $rs;
    }

    public function postAmis(Request $rq, Response $rs, array $args): Response
    {
        $v = new Vue(null);

        if (!$this->verifierUtilisateurConnecte()) {
            return $this->genererRedirectionPageConnexion($rs, $rq);
        }
        $tokenDemande = bin2hex(random_bytes(7));

        $urlDemande = $_SERVER["HTTP_HOST"] . $this->c->router->pathFor("reception-demande-ami", ["token" => $tokenDemande]);
        echo '<script> console.log("' . $urlDemande . '"); </script>';


        $demande = new DemandeAmi();
        $demande->idDemandeur = Membre::getMembreByToken($_COOKIE['token'])->idMembre;
        $demande->disponible = 1;
        $demande->token = $tokenDemande;
        $demande->save();


        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'message' => "hello",
            'url' => $this->c->router->pathFor("amis", []),
            'menu' => $this->getMenu($args),
            'urlDemande' => $urlDemande,
        ];

        $rs->getBody()->write($v->render($htmlvars, Vue::AMISNOUVEAULIEN));
        return $rs;

    }


    public function displayTest(Request $rq, Response $rs, array $args): Response
    {
        $v = new Vue(null);

        echo "<sqh1>";

        $tab = Amis::getAllAmisByIdMembre(2);
        foreach ($tab as $t) {
            echo($t . "\n\n");
        }


        echo "<qsd/h1>";

        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
        ];

        $rs->getBody()->write($v->render($htmlvars, Vue::TEST));
        return $rs;
    }

    private function getMenu()
    {
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
                'about-us' => $urlAPropos,
                'map' => $urlMap,
                'espace' => $urlEspace,
                'ajout' => $urlAjouterMonument,
                'profil' => $urlProfil,
                'amis' => $urlAmis,
                'home' => $urlHome,
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