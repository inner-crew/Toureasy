<?php

namespace toureasy\vue;
class Vue
{

    private $data;

    /**
     * constante correspondant à l'affichage de la page d'accueil
     */
    const HOME = 1;

    private function HomeHtml(array $v): string
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

    public function render(array $vars, $typeAffichage): string
    {
        $content = null;
        switch ($typeAffichage)
        {
            case Vue::HOME:
                $content = $this->HomeHtml($vars);
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