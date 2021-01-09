<?php

namespace toureasy\vue;
class Vue
{

    private $data;

    /**
     * constante correspondant à l'affichage de la page d'accueil
     */
    const HOME = 1;

    /**
     * constante correspondant à l'affichage du formulaire d'ajout d'un monument
     */
    const AJOUTER_MONUMENT = 2;

    /**
     * méthode affichant la page d'accueil de Toureasy
     * @param array $v variables contenant les liens des boutons présents sur la page
     * @return string code HTML de la page
     */
    private function homeHtml(array $v): string
    {
        $html = <<<END

        <section class="titre">
            <h1>Toureasy</h1>
        </section>
        <section class="logo">
            <img src="{$v['basepath']}/web/img/Logo_genial.png"/>
        </section>
        <section class="boutons-centre">
            {$v['connexion']}
            {$v['map']}
        </section>
        <footer class="boutons-bottom">
            {$v['contact']}
            {$v['about-us']}
        </footer>

END;
        return $html;
    }

    private function ajoutMonumentHtml(): string
    {
        $html = <<<END
<form method="post" enctype="multipart/form-data">
            <p>Nom du monument<span class="required">*</span> : <input type="text" name="nom" required/></p>
            <input type="file" name="fichier"/> <br>
            <p>Description<span class="required">*</span> : <input type="text" name="desc" required/></p>
            <p><input type="submit" value="Valider"></p>
        </form>
END;
        return $html;
    }

    /**
     * @param array $vars array de variables (htmlvars)
     * @param int $typeAffichage valeur correspondant à une constante de cette classe
     * @return string code HTML de la page
     */
    public function render(array $vars, int $typeAffichage): string
    {
        $content = null;
        switch ($typeAffichage)
        {
            // affichage de la page d'accueil de Toureasy
            case Vue::HOME:
                $content = $this->homeHtml($vars);
                break;
            // affichage de la page permettant d'ajouter un monument
            case Vue::AJOUTER_MONUMENT:
                $content = $this->ajoutMonumentHtml();
                break;
        }
        $html = <<<END
<!DOCTYPE html>
<html>
    <head>
        <title>Toureasy</title>
        <link rel="stylesheet" href="{$vars['basepath']}/web/css/index.css">
    </head>
    <body>
        $content
    </body>
</html>
END;
        return $html;
    }

}