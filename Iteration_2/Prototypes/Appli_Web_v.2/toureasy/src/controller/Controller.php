<?php

namespace toureasy\controller;
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

    /**
     * methode controller générant les liens des boutons de la page et appelle Vue pour afficher la page HTML
     * @param Request $rq
     * @param Response $rs
     * @param array $args
     * @return Response
     */
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

        if (!isset($_COOKIE['token'])) {
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

    /**
     * méthode controller appellant l'affichage du formulaire d'ajout d'un monument
     * @param Request $rq
     * @param Response $rs
     * @param array $args
     * @return Response
     */
    public function displayAjouterMonument(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath()
        ];
        $v = new Vue(null);

        if (!isset($_COOKIE['token'])) {
            $htmlvars['url'] = $this->c->router->pathFor('connexion');
            $htmlvars['message'] = "Vous devez vous connecter avant de pouvoir ajouter un monument";
            $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
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

        $data = $rq->getParsedBody();
        $nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
        $description = $data['desc'];

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
                $auteurPrive->idMembre = Membre::getIdBytoken($_COOKIE['token'])->idMembre;
                $auteurPrive->save();
            } else {
                $monument->estPrive = 0;
                $monument->estTemporaire = 1;
                $monument->save();

                $contribution = new Contribution();
                $contribution->monumentTemporaire = $monument->idMonument;
                $contribution->contributeur = Membre::getIdBytoken($_COOKIE['token'])->idMembre;
                $contribution->estNouveauMonument = 1;
                $contribution->statutContribution = "enAttenteDeTraitement";
                $contribution->save();
            }

        } catch (\Illuminate\Database\QueryException $e) {
            echo 'Erreur lors de la création du monument';
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
            $extension_autorise= array('.jpg', '.png', '.gif', '.JPG', '.PNG');

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
                    $monument->delete();
                    echo 'Une erreur est survenue lors du téléchargement de l\'image';
                }
            } else {
                $monument->delete();
                echo 'Seules les images sont acceptées';
            }
        }

        // TODO : creation vue pour affichage réussite de l'ajout (ajouter ':Response' à la déclaration de la méthode)
        echo 'Succès';
    }

    public function displayAjouterListe(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath()
        ];
        $v = new Vue(null);
        $rs->getBody()->write($v->render($htmlvars, Vue::AJOUTER_LISTE));
        return $rs;
    }

    public function postAjouterListe(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'message' => "Succès",
            'url' => $this->c->router->pathFor('mes-listes')
        ];

        $data = $rq->getParsedBody();
        $nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
        $description = filter_var($data['desc'], FILTER_SANITIZE_STRING);
        $id = Membre::getIdBytoken($_COOKIE['token']);

        $liste = new ListeMonument();
        $liste->nom = $nom;
        $liste->description = $description;
        $liste->idCreateur = $id->idMembre;
        $liste->token = bin2hex(random_bytes(5));
        $liste->save();


        $v = new Vue(null);
        $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
        return $rs;
    }

    public function displayMesListes(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath(),
            'createListe' => $this->c->router->pathFor('create-liste', []),
            'createMonument' => $this->c->router->pathFor('ajoutMonument', [])
        ];

        $v = new Vue(null);

        if (!isset($_COOKIE['token'])) {
            $htmlvars['url'] = $this->c->router->pathFor('connexion');
            $htmlvars['message'] = "Vous devez vous connecter avant de pouvoir ajouter un monument";
            $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
        } else {
            $membre = null;

            try {
                $membre = Membre::getIdBytoken($_COOKIE['token']);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $htmlvars['message'] = "Le token indiqué est inexistant";
                $htmlvars['url'] = $this->c->router->pathFor('connexion', []);
                $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
                return $rs;
            }

            $listOfListes = ListeMonument::getListesByIdCreator($membre->idMembre);
            $tabListes = array();
            foreach ($listOfListes as $liste) {
                $url = $this->c->router->pathFor('detail-liste', ['token'=>$liste->token]);
                array_push($tabListes, [$liste, $url]);
            }
            $htmlvars['listes'] = $tabListes;

            $listOfPrivate = AuteurMonumentPrive::getMonumentByCreator($membre->idMembre);
            $tabMonuments = array();
            foreach ($listOfPrivate as $monument) {
                $monument = Monument::getMonumentById($monument->idMonument);
                $url = $this->c->router->pathFor('detail-monument', ['token' => $monument->token]);
                array_push($tabMonuments, [$monument, $url]);
            }
            $htmlvars['monumentsPrivate'] = $tabMonuments;

            $listMonumentCreated = Contribution::getMonumentByIdCreator($membre->idMembre);
            $tabMonuments = array();
            foreach ($listMonumentCreated as $monument) {
                $monument = Monument::getMonumentById($monument->monumentTemporaire);
                if ($monument->estPrive == '0') {
                    $url = $this->c->router->pathFor('detail-monument', ['token' => $monument->token]);
                    array_push($tabMonuments, [$monument, $url]);
                }
            }
            $htmlvars['monumentsPublic'] = $tabMonuments;

            $rs->getBody()->write($v->render($htmlvars, Vue::VUE_ENSEMBLE));
        }
        return $rs;
    }

    public function displayDetailListe(Request $rq, Response $rs, array $args): Response
    {
        $liste = ListeMonument::getListeByToken($args['token']);
        $v = new Vue([$liste]);
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath()
        ];

        $listeMonuments = AppartenanceListe::getMonumentByIdListe($liste->idListe);
        $tabMonuments = array();
        foreach ($listeMonuments as $monument) {
            $monument = Monument::getMonumentById($monument->idMonument);
            $url = $this->c->router->pathFor('detail-monument', ['token'=>$monument->token]);
            array_push($tabMonuments, [$monument, $url]);
        }
        $htmlvars['objets'] = $tabMonuments;

        $rs->getBody()->write($v->render($htmlvars, Vue::LISTE));
        return $rs;
    }

    public function displayDetailMonument(Request $rq, Response $rs, array $args): Response
    {
        $monument = Monument::getMonumentByToken($args['token']);
        $v = new Vue([$monument]);
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath()
        ];

        $rs->getBody()->write($v->render($htmlvars, Vue::MONUMENT));
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
                Membre::getIdBytoken($token);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $htmlvars['message'] = "Le token indiqué est inexistant";
                $htmlvars['url'] = $this->c->router->pathFor('connexion', []);
                $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
                return $rs;
            }
        }

        setcookie('token', $token, time()+3600, "/S3B_S16_BRANCATTI_FRACHE_MOITRIER_ZAPP/");

        $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
        return $rs;
    }

    public function displayInscription(Request $rq, Response $rs, array $args): Response
    {
        //TODO : implement method
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

    public function displayContact(Request $rq, Response $rs, array $args): Response
    {
        //TODO : implement method
    }

    public function displayAboutUs(Request $rq, Response $rs, array $args): Response
    {
        //TODO : implement method
    }

    /**
     * methode controller générant les liens des boutons de la page et appelle Vue pour afficher la page HTML
     * @param Request $rq
     * @param Response $rs
     * @param array $args
     * @return Response
     */
    public function displayProfil(Request $rq, Response $rs, array $args): Response
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath()
        ];
        $v = new Vue(null);

        if (!isset($_COOKIE['token'])) {
            $htmlvars['url'] = $this->c->router->pathFor('connexion');
            $htmlvars['message'] = "Vous devez vous connecter pour accéder à votre profil";
            $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
        } else {
            $m = Membre::getIdBytoken($_COOKIE['token']);
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
        $membre = Membre::getIdBytoken($_COOKIE['token']);

        $membre->prenom = $prenom;
        $membre->nom = $nom;
        $membre->sexe = $sexe;
        $membre->dateNaissance = $naissance;
        $membre->email = $mail;

        $v = new Vue(null);
        $rs->getBody()->write($v->render($htmlvars, Vue::MESSAGE));
        return $rs;
    }

}