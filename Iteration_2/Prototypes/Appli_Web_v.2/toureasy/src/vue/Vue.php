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
     * constante correspondant à l'affichage de la carte
     */
    const MAP = 3;

    /**
     * méthode affichant la page d'accueil de Toureasy
     * @param array $v variables contenant les liens des boutons présents sur la page
     * @return string code HTML de la page
     */
    private function homeHtml(array $v): string
    {
        $html = <<<END
        <body class="boutons-bottom">
            {$v['contact']}
            {$v['about-us']}
        </body>
        <header class="logo">
            <img src="{$v['basepath']}/web/img/Logo_genial.png"/>
            <h1>Toureasy</h1>
        </header> 
        <div class = "cadre-diapo">
	        <img class="diapo" src="{$v['basepath']}/web/img/diapo1.jpg" alt>
	        <img class="diapo" src="{$v['basepath']}/web/img/diapo2.jpg" alt>
            <img class="diapo" src="{$v['basepath']}/web/img/diapo3.jpg" alt>
            <img class="diapo" src="{$v['basepath']}/web/img/diapo4.jpg" alt>
            <button class="precedent" aria-label="précédent" onclick="boutons(-1)">❮</button>
            <button class="suivant" aria-label="suivant" onclick="boutons(1)">❯</button>
        </div> 
        <script>
            var diaporama = 1;
            affichage(diaporama);
                    
            function boutons(n) {
                affichage(diaporama += n);
            }
                    
            function affichage(n) {
                var i;
                var diapoImg = document.getElementsByClassName("diapo");
                if (n > diapoImg.length) {diaporama = 1}    
                if (n < 1) {diaporama = diapoImg.length}
                for (i = 0; i < diapoImg.length; i++) {
                    diapoImg[i].style.opacity = "0";
                }
                diapoImg[diaporama-1].style.opacity = "1";    
                indic[diaporama-1].className += " numeros";
            } 

        </script>
        </div>
        <footer class="boutons-centre">
            {$v['connexion']}
            <br>
            {$v['map']}
        </footer>

END;
        return $html;
    }

    private function ajoutMonumentHtml(): string
    {
        $html = <<<END
<form method="post" enctype="multipart/form-data">
            <p>Nom du monument<span class="required">*</span> : <input type="text" name="nom" required/></p>
            <input type="file" name="fichier"/><input type="hidden" name="lat"/><input type="hidden" name="long"/> <br>
            <p>Description<span class="required">*</span> : <input type="text" name="desc" required/></p>
            <p><input type="submit" value="Valider" </p>
        </form>
<script>

    window.onload=function getPos(){
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(position => {
                document.getElementsByName("lat")[0].value = position.coords.latitude;
                document.getElementsByName("long")[0].value = position.coords.longitude;
            });
        } else {
            document.getElementsByName("lat")[0].value="error";
            document.getElementsByName("long")[0].value="error"; 
        }
    }
    
</script>
END;
        return $html;
    }

    private function affichageMap(array $v): string
    {
        $html = <<<END
    <script src="{$v['basepath']}/web/carteSetting/js/API_MapBox/main_mapbox-gl.js"></script>                                       <!--Le js principal de la l'api mapBox-->
    <script type="text/javascript" src="{$v['basepath']}/web/carteSetting/js/API_MapBox/soleil_suncalc.min.js"></script>            <!--Le script pour calculer la pos du soleil pour faire le ciel-->
    <script src="{$v['basepath']}/web/carteSetting/js/API_MapBox/doubleMap_mapbox-gl-compare.js"></script>                          <!-- le script qui fait fonctionner le swap entre les 2 maps-->
    <script src="{$v['basepath']}/web/carteSetting/js/API_MapBox/barreDeRecherche_mapbox-gl-geocoder.min.js"></script>              <!--le script de la recherche de lieu (geocoder)-->
    <script src="{$v['basepath']}/web/carteSetting/js/index.js"></script>                                                           <!--le script de toureasy-->

    <link href="{$v['basepath']}/web/carteSetting/css/main_mapbox-gl.css" rel="stylesheet"/>                                        <!--Le css principal de la l'api mapBox-->
    <link href="{$v['basepath']}/web/carteSetting/css/index.css" rel="stylesheet"/>                                                 <!--Le css principal de la page web-->
    <link rel="stylesheet" href="{$v['basepath']}/web/carteSetting/css/doubleMap_mapbox-gl-compare.css" type="text/css"/>           <!--le css qui gère la transition des 2 maps-->
    <link rel="stylesheet" href="{$v['basepath']}/web/carteSetting/css/barreDeRecherche_mapbox-gl-geocoder.css" type="text/css"/>   <!--css de la recherche de lieu-->

<div id="comparison-container">
    <div id="before" class="map"></div>
    <pre id="geoPos"></pre>
    <div id="after" class="map"></div>
</div>
<input type= "image" id="btn" src="{$v['basepath']}/web/carteSetting/data/images/mark.png" width="100px" height="200px"/>

<script src="{$v['basepath']}/web/carteSetting/js/index.js"></script>   <!--le script de toureasy (il est lourd)-->
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
                // affichage de la carte
            case Vue::MAP:
                $content = $this->affichageMap($vars);
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