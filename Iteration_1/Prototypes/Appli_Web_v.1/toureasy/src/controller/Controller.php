<?php

namespace toureasy\controller;
use Slim\Http\Request;
use Slim\Http\Response;
use toureasy\models\Image;
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

        // generation des balises HTML de boutons avec les liens correspondants
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

        // ajoute ces variables à htmlvars afin de les transférer à la vue
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
        $rs->getBody()->write($v->render($htmlvars, Vue::AJOUTER_MONUMENT));
        return $rs;
    }

    public function postAjouterMonument(Request $rq, Response $rs, array $args)
    {
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath()
        ];

        $data = $rq->getParsedBody();
        $nom = filter_var($data['nom'], FILTER_SANITIZE_STRING);
        $description = filter_var($data['desc'], FILTER_SANITIZE_STRING);

        try {
            $monument = new Monument();
            $monument->nomMonum = $nom;
            $monument->descLongue = $description;
            $monument->estTemporaire = 1;
            $monument->longitude = $_POST['long'];
            $monument->latitude = $_POST['lat'];
            $monument->save();
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

    public function displayConnexion(Request $rq, Response $rs, array $args): Response
    {
        //TODO : implement method
    }

    public function displayInscription(Request $rq, Response $rs, array $args): Response
    {
        //TODO : implement method
    }

    public function displayMap(Request $rq, Response $rs, array $args): Response
    {
        //TODO : implement method
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