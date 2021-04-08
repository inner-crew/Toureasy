<?php


namespace toureasy\vue;


use toureasy\models\Monument;

class VueAdmin
{

    private $data;

    const MESSAGE = 0;

    const TABLEAU = 1;

    const DETAIL_MONUMENT = 2;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function unMessage($vars): string
    {
        $html = $this->insererEnteteSite($vars);

        $html .= <<<END
        <div class="message">
            <h1>{$vars['message']}</h1>
            <div class="box">
                <button onclick="window.location.href='{$vars['url']}'">Ok</button>
            </div>          
        </div>      
    </div>

END;
        return $html;
    }

    private function tableauAdmin($contributions, $vars): string {
        $html = $this->insererEnteteSite($vars);

        $html .= <<<END
<section class="sectionEspace">
<section class="tableau">
            <table class="content-table">
                <thead>
                    <tr>
                        <th>Source contribution</th>
                        <th>Id Monument</th>
                        <th>Date</th>
                        <th>Lien nouveau Monument</th>
                        <th>Lien monument originel</th>
                        <th>Est nouveau monument</th>
                        <th>Accepter</th>
                        <th>Refuser</th>
                    </tr>
                </thead>
                <tbody>
END;

        foreach ($contributions as $c) {
            $url = $c['url'];
            $originel = $c['originel'];
            $c = $c['Contribution'];
            $nouveauMonument= "";

            if ($c['estNouveauMonument'] === 1) {
                $nouveauMonument = "Oui";
            } else {
                $nouveauMonument = "Non";
            }

            $html .= <<<END
<tr>
    <td>{$c['statutContribution']}</td>
    <td>{$c['monumentTemporaire']}</td>
    <td>{$c['date']}</td>
    <td><a href="$url">Voir</a></td>
    <td><a href="$originel">Voir</a></td>
    <td>$nouveauMonument</td>
    <td><form method="post">
        <input type="hidden" name="nouveau" value="{$c['monumentTemporaire']}">
        <input type="submit" name="valider" value="Valider"/>
    </form></td>
    <td><form method="post">
        <input type="hidden" name="nouveau" value="{$c['monumentTemporaire']}">
        <input type="submit" name="refuser" value="Refuser"/>
    </form></td>
</tr>
END;
        }

        $html .= <<<END
</tbody>
</table>
</section>
</section>
</div>
END;


        return $html;
    }

    private function insererMenu($vars): string
    {
        $html = "";
        if (isset($vars['menu']['profil'])) {
            $html .= <<<END
    <li><a href='{$vars['menu']['map']}'">Map</a></li>
    <li><a href='{$vars['menu']['espace']}'">Mon Espace</a></li>
    <li><a href='{$vars['menu']['profil']}'">Mon Profil</a></li>
    <li><a href='{$vars['menu']['ajout']}'">Ajouter un monument</a></li>
    <li><a href='{$vars['menu']['amis']}'">Page amis</a></li>
    </br>
    <li><a href='{$vars['menu']['about-us']}'">A propos</a></li>
END;

        } else {
            $html .= <<<END
    <li><a href='{$vars['menu']['connexion']}'">Connexion</a></li>
    </br>
    <li><a href='{$vars['menu']['about-us']}'">A propos</a></li>
END;
        }
        $html .= <<<END
</ul>
END;

        return $html;
    }

    /**
     * Retourne un string avec l'entête, ne pas oublier de fermer la div container à la fin du contenu
     * @param $vars
     * @return string
     */
    private function insererEnteteSite($vars): string
    {
        $html = <<<END
        <header>
            <div class="menu-btn">
                <div class="menu-btn__burger"></div>
            </div>
            <div id="divNomSite" style="cursor: pointer;" onclick="window.location='{$vars['menu']['home']}';">
                <h1 id="name"  >Toureasy</h1>         
            </div>
        </header>
        <div class="container">
            <div>
                <ul id="menu">
END;
        $html .= $this->insererMenu($vars);
        $html .= "</div>";

        return $html;
    }

    public function detailMonumentMap(Monument $monument, array $images, $vars): string
    {
        $html = $this->insererEnteteSite($vars);
        $html .= <<<END
        <section class="infos">
            <h3 id="nom">{$monument->nomMonum}</h3>
            <p class="desc" id="descMonu">{$monument->descLongue}</p>
END;
        if (sizeof($images) > 0) {
            $html .= "<div class='wrapper' id='galerie'>";
            foreach ($images as $img) {
                $html .= <<<END
<div class='cell'>
    <img src='{$vars['basepath']}/{$img['urlImage']}'>
</div>
END;
            }
            $html .= "</div>";
        }

            $html .= <<<END
</section>
<div id="back"><img onclick="back('{$vars['back']}')" src="{$vars['basepath']}/web/img/back.png"/></div></div>
<script>
    function back(chemin) {
        window.location = chemin
    }
</script>
END;
        return $html;
    }

    public function render(array $vars, int $typeAffichage): string
    {
        $content = null;
        switch ($typeAffichage) {
            case VueAdmin::MESSAGE:
                $content = $this->unMessage($vars);
                break;
            case VueAdmin::TABLEAU:
                $content = $this->tableauAdmin($this->data[0],$vars);
                break;
            case VueAdmin::DETAIL_MONUMENT:
                $content = $this->detailMonumentMap($this->data[0],$this->data[1],$vars);
                break;
        }


        $html = <<<END
<!DOCTYPE html>
<html>
    <head>
        <title>Toureasy</title>
        <link rel="stylesheet" href="{$vars['basepath']}/web/css/index.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    </head>
    <body>
        $content
    </body>
    <script src="{$vars['basepath']}/web/js/menu.js"></script>
</html>
END;
        return $html;
    }


}