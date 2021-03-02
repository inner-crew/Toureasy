<?php

namespace toureasy\controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use toureasy\models\AppartenanceListe;
use toureasy\models\AuteurMonumentPrive;
use toureasy\models\Contribution;
use toureasy\models\Image;
use toureasy\models\ListeMonument;
use toureasy\models\Membre;
use toureasy\models\Monument;
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
        // url redirigeant vers la page de connexion
        $urlConnexion = $this->c->router->pathFor('connexion');
        // url redirigeant vers la page de navigation sur la carte
        $urlAccederMap = $this->c->router->pathFor('map');
        // url redirigeant vers la page de contact
        $urlContact = $this->c->router->pathFor('contact');
        // url redirigeant vers la page 'à propos'
        $urlAPropos = $this->c->router->pathFor('about-us');

        if (!$this->verifierUtilisateurConnecte()) {
            $urlAccederMap = $urlConnexion;
        }

        // ajoute ces variables à htmlvars afin de les transférer à la vue
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'map' => $urlAccederMap,
            'contact' => $urlContact,
            'about-us' => $urlAPropos
        ];

        $v = new Vue(null);
        $rs->getBody()->write($v->render($htmlvars, Vue::HOME));
        return $rs;
    }

    public function displayConnexion(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath()
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
            'message' => "Connexion réussie"
        ];
        $data = $rq->getParsedBody();
        $token = filter_var($data['action'], FILTER_SANITIZE_STRING);
        $v = new Vue(null);

        if ($token === "Obtenir un token") {
            $token = bin2hex(random_bytes(5));
            $htmlvars['message'] = "Votre token est : $token";
            $membre = new Membre();
            $membre->token = $token;
            $membre->save();
        } else {
            $token = filter_var($data['token'], FILTER_SANITIZE_STRING);
            try {
                Membre::getMembreByToken($token);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $htmlvars['message'] = "Le token indiqué est inexistant";
                $htmlvars['url'] = $this->c->router->pathFor('connexion', []);
                $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
                return $rs;
            }
        }

        setcookie('token', $token, time()+3600, "/");

        $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
        return $rs;
    }

    public function displayProfil(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath()
        ];
        $v = new Vue(null);

        if (!$this->verifierUtilisateurConnecte()) {
            return $this->genererRedirectionPageConnexion($rs, $rq);
        } else {
            $m = Membre::getMembreByToken($_COOKIE['token']);
            $v= new Vue([$m]);
            $rs->getBody()->write($v->render($htmlvars, Vue::PROFIL));
        }

        return $rs;
    }

    public function postProfil(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'message' => "Succès",
            'url' => $this->c->router->pathFor('profil')
        ];

        $data = $rq->getParsedBody();
        $prenom = filter_var($data['prenom'], FILTER_SANITIZE_STRING);
        $nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
        $sexe = filter_var($data['sexe'], FILTER_SANITIZE_STRING);
        $naissance = filter_var($data['naissance'], FILTER_SANITIZE_STRING);
        $mail = filter_var($data['mail'], FILTER_SANITIZE_STRING);
        $membre = Membre::getMembreByToken($_COOKIE['token']);

        $membre->prenom = $prenom;
        $membre->nom = $nom;
        $membre->sexe = $sexe;
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
            'createMonument' => $this->c->router->pathFor('ajoutMonument', [])
        ];

        if (!$this->verifierUtilisateurConnecte()) {
            return $this->genererRedirectionPageConnexion($rs, $rq);
        } else {
            $membre = Membre::getMembreByToken($_COOKIE['token']);

            $listeDesListesUtilisateur = ListeMonument::getListesByIdCreator($membre->idMembre);
            $tabListes = array();
            foreach ($listeDesListesUtilisateur as $liste) {
                $url = $this->c->router->pathFor('detail-liste', ['token'=>$liste->token]);
                array_push($tabListes, [$liste, $url]);
            }

            $listeMonumentsPrives = AuteurMonumentPrive::getMonumentByCreator($membre->idMembre);
            $arrayMonumentsPrives = array();
            foreach ($listeMonumentsPrives as $monument) {
                $monument = Monument::getMonumentById($monument->idMonument);
                $url = $this->c->router->pathFor('detail-monument', ['token' => $monument->token]);
                array_push($arrayMonumentsPrives, [$monument, $url]);
            }

            $listeMonumentsPublics = Contribution::getMonumentByIdCreator($membre->idMembre);
            $arrayMonumentsPublics = array();
            foreach ($listeMonumentsPublics as $monument) {
                $monument = Monument::getMonumentById($monument->monumentTemporaire);
                if ($monument->estPrive == '0') {
                    $url = $this->c->router->pathFor('detail-monument', ['token' => $monument->token]);
                    array_push($arrayMonumentsPublics, [$monument, $url]);
                }
            }

            $v = new Vue([$tabListes, $arrayMonumentsPrives, $arrayMonumentsPublics]);

            $rs->getBody()->write($v->render($htmlvars, Vue::VUE_ENSEMBLE));
            return $rs;
        }
    }

    public function displayMap(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath()
        ];
        $v = new Vue(null);
        $rs->getBody()->write($v->render($htmlvars, Vue::MAP));
        return $rs;
    }

    public function displayAjouterMonument(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath()
        ];
        $v = new Vue(null);

        if (!$this->verifierUtilisateurConnecte()) {
            return $this->genererRedirectionPageConnexion($rs, $rq);
        } else {
            $rs->getBody()->write($v->render($htmlvars, Vue::AJOUTER_MONUMENT));
        }
        return $rs;
    }

    public function postAjouterMonument(Request $rq, Response $rs, array $args)
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath()
        ];

        $v = new Vue(null);

        $data = $rq->getParsedBody();
        $nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
        $description = $data['desc'];
        $contribution = null;
        $auteurPrive = null;

        try {
            $monument = new Monument();

            $monument->nomMonum = $nom;
            $monument->descLongue = $description;
            $monument->longitude = $_POST['long'];
            $monument->latitude = $_POST['lat'];
            $monument->token = bin2hex(random_bytes(5));

            if($data['visibilite'] === "private") {
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

            $strJsonFileContents = file_get_contents("./web/carteSetting/data/tmp/monumentsDeLaDataBase.json");
            $jsonMonuemnts = json_decode($strJsonFileContents, true);

            array_push($jsonMonuemnts['features'], array (
                "type" => "Feature",
                "geometry" => array (
                    "type" => "Point",
                    "coordinates" => array($monument->longitude, $monument->latitude)
                ),
                "properties" => array (
                    "title" => $monument->nomMonum,
                    "description" => $monument->descLongue
                )
            ));

            $nvJson = json_encode($jsonMonuemnts);
            $bytes = file_put_contents("./web/carteSetting/data/tmp/monumentsDeLaDataBase.json", $nvJson);

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
            return $this->genererMessageAvecRedirection($rs, $rq,"Erreur lors de l'ajout du monument", "ajoutMonument");
        }

        // si une image est bien présente dans la variable
        if(!empty($_FILES)){
            // récupération du nom du fichier
            $file_name = $_FILES['fichier']['name'];
            // récupération de l'extension du fichier
            $file_extension = strrchr($file_name,".");
            // stockage temporaire du fichier
            $file_tmp_name = $_FILES['fichier']['tmp_name'];
            // ajout destination du fichier
            $file_dest = "web/img/".$file_name;
            // conditions de format du fichier
            $extension_autorise= array('.jpg', '.png', '.JPG', '.PNG');

            // si fichier corrélation avec conditions
            if(in_array($file_extension, $extension_autorise)){
                if(move_uploaded_file($file_tmp_name, $file_dest)){
                    $image = new Image();

                    // TODO : changer l'attribution de numeroImage quand le trigger sera fait
                    $image->numeroImage = 1;

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
                    return $this->genererMessageAvecRedirection($rs, $rq, 'Une erreur est survenue lors du téléchargement de l\'image', "ajoutMonument");
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
                return $this->genererMessageAvecRedirection($rs, $rq, 'Veuillez ajouter une image valide pour votre monument', "ajoutMonument");
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
            return $this->genererMessageAvecRedirection($rs,$rq,"Vous devez mettre une image pour ajouter un monument", "ajoutMonument");
        }

        return $this->genererMessageAvecRedirection($rs, $rq,"Monument créé avec succès", "mes-listes");
    }

    public function displayAjouterListe(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath()
        ];
        $v = new Vue(null);

        if ($this->verifierUtilisateurConnecte()) {
            $rs->getBody()->write($v->render($htmlvars, Vue::AJOUTER_LISTE));
            return $rs;
        } else {
            return $this->genererRedirectionPageConnexion($rs, $htmlvars);
        }
    }

    public function postAjouterListe(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'message' => "Succès",
            'url' => $this->c->router->pathFor('mes-listes')
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
            return $this->genererMessageAvecRedirection($rs, $htmlvars,"Erreur lors de la création de votre liste", "createListe");
        }
        $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
        return $rs;
    }

    public function displayDetailListe(Request $rq, Response $rs, array $args): Response
    {
        $liste = ListeMonument::getListeByToken($args['token']);
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath()
        ];

        if ($this->verifierUtilisateurConnecte()) {
            $listeMonumentsDeCetteListe = AppartenanceListe::getMonumentByIdListe($liste->idListe);
            $tabMonumentsDeCetteListe = array();
            foreach ($listeMonumentsDeCetteListe as $monument) {
                $monument = Monument::getMonumentById($monument->idMonument);
                $url = $this->c->router->pathFor('detail-monument', ['token'=>$monument->token]);
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
            return $this->genererRedirectionPageConnexion($rs, $rq);
        }

        return $rs;
    }

    public function displayDetailMonument(Request $rq, Response $rs, array $args): Response
    {
        $monument = Monument::getMonumentByToken($args['token']);
        $v = new Vue([$monument]);

        $image = Image::getImageUrlByIdMonument($monument->idMonument);

        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            "urlImage" => $image->urlImage,
            "modifierMonument" => $this->c->router->pathFor('modifierMonument', ["token" => $args['token']])
        ];

        if ($this->verifierUtilisateurConnecte()) {
            $rs->getBody()->write($v->render($htmlvars, Vue::MONUMENT));
        } else {
            return $this->genererRedirectionPageConnexion($rs, $rq);
        }

        return $rs;
    }

    public function displayModifierMonument(Request $rq, Response $rs, array $args): Response
    {
        $monument = Monument::getMonumentByToken($args['token']);
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath()
        ];
        $v = new Vue([$monument]);

        $rs->getBody()->write($v->render($htmlvars, Vue::MODIFIER_MONUMENT));
        return $rs;
    }

    public function postModifierMonument(Request $rq, Response $rs, array $args): Response
    {
        $monument = Monument::getMonumentByToken($args['token']);

        if ($monument->estPrive) {
            return $this->postModifierMonumentPrive($rq,$rs,$args);
        }
    }

    public function postModifierMonumentPrive(Request $rq, Response $rs, array $args): Response
    {
        $monument = Monument::getMonumentByToken($args['token']);
        $data = $rq->getParsedBody();

        $nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
        $description = $data['desc'];

        $monument->descLongue = $description;
        $monument->nomMonum = $nom;
        $monument->save();

        return $this->genererMessageAvecRedirection($rs,$rq,"Monument modifié","detail-monument",["token" => $args['token']]);

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

        return $this->genererMessageAvecRedirection($rs, $rq, "Monument ajouté à la liste avec succès", 'detail-liste', ['token' => $args['token']]);
    }

    private function verifierUtilisateurConnecte(): Bool
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

    private function genererRedirectionPageConnexion($rs, $rq) {
        $v = new Vue(null);
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'message' => "Vous devez vous connecter pour accéder à cette page",
            'url' => $this->c->router->pathFor('connexion', [])
        ];
        $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
        return $rs;
    }

    private function genererMessageAvecRedirection($rs, $rq, $message, $nameRedirection, $argsUrl = array()) {
        $v = new Vue(null);
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'message' => $message,
            'url' => $this->c->router->pathFor($nameRedirection, $argsUrl)
        ];
        $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
        return $rs;
    }

    public function displayContact(Request $rq, Response $rs, array $args): Response
    {
        //TODO : implement method
    }

    public function displayAboutUs(Request $rq, Response $rs, array $args): Response
    {
        //TODO : implement method
    }

}